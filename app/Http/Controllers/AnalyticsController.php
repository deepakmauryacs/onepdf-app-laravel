<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalyticsSession;
use App\Models\AnalyticsEvent;
use App\Models\Document;
use Illuminate\Support\Facades\Http;
use GeoIP;

class AnalyticsController extends Controller
{

    public function track(Request $request)
    {
        $sessionId = $request->input('session_id') 
            ?? $request->cookie('analytics_session_id') 
            ?? session()->getId();

        // Validate input
        $request->validate([
            'event_type' => 'required|string',
            'target' => 'nullable',
            'page_number' => 'nullable|integer',
            'duration' => 'nullable|numeric',
            'signature' => 'nullable|string',
            'location' => 'nullable|array',
        ]);

        // If geolocation event with lat/lon → reverse geocode
        $city = null; $state = null; $country = null;
        if ($request->event_type === 'geolocation' && $request->location) {
            $lat = $request->location['latitude'];
            $lon = $request->location['longitude'];

            // Example using OpenStreetMap Nominatim (free)
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
            ]);

            if ($response->ok()) {
                $data = $response->json();
                $city = $data['address']['city'] ?? ($data['address']['town'] ?? null);
                $state = $data['address']['state'] ?? null;
                $country = $data['address']['country'] ?? null;
            }
        }

        // If no geolocation → fallback to IP
        if (!$city) {
            $geo = geoip()->getLocation($request->ip());
            $city = $geo->city;
            $state = $geo->state_name;
            $country = $geo->country;
        }

        // Verify PDF signature if event relates to PDFs
        if (in_array($request->event_type, ['pdf_open', 'pdf_page_view'])) {
            $expected = hash_hmac('sha256', $request->target, config('app.key'));

            if ($request->signature !== $expected) {
                return response()->json(['error' => 'Invalid PDF signature'], 403);
            }
        }

        // 🔹 Detect IP
        // $ip = $request->ip();

        // // 🔹 Lookup location (skip if localhost)
        // $geo = null;
        // if ($ip !== '127.0.0.1' && $ip !== '::1') {
        //     try {
        //         $geo = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/")->json();
        //     } catch (\Exception $e) {
        //         $geo = [];
        //     }
        // }

        // $location = geoip($request->ip()); // ← lookup city/country
        // $location = geoip("139.5.1.192"); // ← lookup city/country
        // $location = geoip("1110.226.231.85"); // ← lookup city/country

        $referer = $request->headers->get('referer'); // HTTP referer

        // Find or create analytics session
        $session = AnalyticsSession::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'city'      => $city ?? null,
                'country'   => $country ?? null,
                'device'    => $request->userAgent(),
                'platform'  => $request->input('platform'),
                'browser'   => $request->input('browser'),
                'referer'   => $referer
            ]
        );

        // Create or update session
        // $session = AnalyticsSession::firstOrCreate(
        //     ['session_id' => $sessionId],
        //     [
        //         'user_id' => auth()->id(),
        //         'ip' => $request->ip(),
        //         'device' => $request->userAgent(),
        //         'platform'  => $request->input('platform'),
        //         'browser'   => $request->input('browser'),
        //     ]
        // );

        // $session->update([
        //     'location' => json_encode([
        //         'city' => $city,
        //         // 'state' => $state,
        //         'country' => $country,
        //     ])
        // ]);

        // Store event
        AnalyticsEvent::create([
            'session_id' => $session->id,
            'event_type' => $request->event_type,
            'target'     => $request->target,
            'page_number'=> $request->page_number,
            'duration'   => $request->duration ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    /*
    public function track1(Request $request)
    {
        // $sessionId = $request->cookie('analytics_session_id') ?? session()->getId();
        // echo $sessionId;
        // echo "<pre>";
        // print_r($request->all());
        // die;
        $sessionId = $request->input('session_id') 
            ?? $request->cookie('analytics_session_id') 
            ?? session()->getId();

        // Validate input
        $request->validate([
            'event_type' => 'required|string',
            'target' => 'nullable',
            'page_number' => 'nullable|integer',
            'duration' => 'nullable|numeric',
            'signature' => 'nullable|string'
        ]);

        // ✅ Verify PDF signature if event relates to PDFs
        if (in_array($request->event_type, ['pdf_open', 'pdf_page_view'])) {
            $expected = hash_hmac('sha256', $request->target, config('app.key'));

            if ($request->signature !== $expected) {
                return response()->json(['error' => 'Invalid PDF signature'], 403);
            }
        }

        // ✅ Find or create analytics session
        $session = AnalyticsSession::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'location' => null, // You can enrich with IP-to-Geo lookup
            ]
        );

        // ✅ Store event
        AnalyticsEvent::create([
            'session_id' => $session->id,
            'event_type' => $request->event_type,
            'target' => $request->target,
            'page_number' => $request->page_number,
            'duration' => $request->duration ?? 0,
        ]);

        return response()->json(['success' => true]);
    }

    public function trackOLD(Request $request) {
        // Validate allowed events
        $allowed = ['page_view', 'page_duration', 'pdf_open', 'pdf_page_view'];
        if (! in_array($request->event_type, $allowed)) {
            return response()->json(['error' => 'Invalid event type'], 400);
        }

        // Create/find session
        $session = AnalyticsSession::firstOrCreate(
            ['session_id' => $request->session_id],
            [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'device' => $request->device,
                'platform' => $request->platform,
                'browser' => $request->browser,
            ]
        );

        // Extra check for PDFs
        if (in_array($request->event_type, ['pdf_open', 'pdf_page_view'])) {
            $pdf = Document::find($request->target_id);
            if (! $pdf) {
                return response()->json(['error' => 'Invalid PDF'], 400);
            }

            // Validate signature (for public users)
            $expected = hash_hmac('sha256', $pdf->id, config('app.key'));
            if ($request->signature !== $expected) {
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        // Store event
        AnalyticsEvent::create([
            'session_id' => $session->id,
            'event_type' => $request->event_type,
            'target_id' => $request->target_id,
            'page_number' => $request->page_number,
            'duration' => $request->duration ?? 0,
            'signature' => $request->signature ?? null,
        ]);

        return response()->json(['status' => 'ok']);
    }
    */
}
