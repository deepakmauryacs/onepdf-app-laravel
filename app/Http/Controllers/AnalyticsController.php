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
        $city = null; $state = null; $country = null; $geo_json = null;
        if ($request->event_type === 'geolocation' && $request->location) {
            $lat = $request->location['latitude'];
            $lon = $request->location['longitude'];

            // Example using OpenStreetMap Nominatim (free)
            $response = Http::withHeaders([
                'User-Agent' => 'OnePDFApp/1.0 (contact@yourdomain.com)'
            ])->get("https://nominatim.openstreetmap.org/reverse", [
                'lat' => $lat,
                'lon' => $lon,
                'format' => 'json',
            ]);
            if ($response->ok()) {
                $data = $response->json();
                $city = $data['address']['city']
                        ?? $data['address']['town']
                        ?? $data['address']['village']
                        ?? $data['address']['hamlet']
                        ?? $data['address']['county']
                        ?? null;
                $state = $data['address']['state'] ?? null;
                $country = $data['address']['country'] ?? null;
                // Store only the required subset of the response
                $geo_json = json_encode([
                    'lat' => $data['lat'] ?? null,
                    'lon' => $data['lon'] ?? null,
                    'class' => $data['class'] ?? null,
                    'type' => $data['type'] ?? null,
                    'addresstype' => $data['addresstype'] ?? null,
                    'name' => $data['name'] ?? null,
                    'display_name' => $data['display_name'] ?? null,
                    'address' => $data['address'] ?? null,
                ]);
            }
        }

        // If no geolocation → fallback to IP
        // if (!$city) {
        //     $geo = geoip()->getLocation($request->ip());
        //     $city = $geo->city;
        //     $state = $geo->state_name;
        //     $country = $geo->country;
        // }

        // Verify PDF signature if event relates to PDFs
        if (in_array($request->event_type, ['pdf_open', 'pdf_page_view'])) {
            $expected = hash_hmac('sha256', $request->target, config('app.key'));

            if ($request->signature !== $expected) {
                return response()->json(['error' => 'Invalid PDF signature'], 403);
            }
        }

        $referer = $request->headers->get('referer'); // HTTP referer

        // Find or create analytics session
        $session = AnalyticsSession::where('session_id', $sessionId)->first();
        if (!$session) {
            $session = AnalyticsSession::create([
                'session_id' => $sessionId,
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'city'      => $city ?? null,
                'state'     => $state ?? null,
                'country'   => $country ?? null,
                'geo_json'  => $geo_json ?? null,
                'device'    => $request->userAgent(),
                'platform'  => $request->input('platform'),
                'browser'   => $request->input('browser'),
                'referer'   => $referer
            ]);
        } else {
            // Only update city/state/country/geo_json if not already set
            $updateData = [
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'device'    => $request->userAgent(),
                'platform'  => $request->input('platform'),
                'browser'   => $request->input('browser'),
                'referer'   => $referer
            ];
            if (empty($session->city) && $city) $updateData['city'] = $city;
            if (empty($session->state) && $state) $updateData['state'] = $state;
            if (empty($session->country) && $country) $updateData['country'] = $country;
            if (empty($session->geo_json) && $geo_json) $updateData['geo_json'] = $geo_json;
            $session->update($updateData);
        }

        // Store event
        AnalyticsEvent::create([
            'session_id' => $session->id,
            'event_type' => $request->event_type,
            'target'     => $request->target,
            'page_number'=> $request->page_number,
            'duration'   => $request->duration ?? 0,
        ]);

        return response()->json(['success' => true]);//, 'session_id' => $session->toArray()
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
