<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\LinkAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\AnalyticsSession;
use App\Models\AnalyticsEvent;
use App\Models\Document;

class AnalyticsController extends Controller
{
    /**
     * Display analytics for the authenticated vendor.
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $range  = $request->input('range', 'Last 7 days');

        // Resolve date range (inclusive)
        [$from, $to] = $this->resolveRange($range);

        // Get this vendor's PDFs
        $vendorPdfIds = Document::where('user_id', $user->id)->pluck('id');

        // Total Visits (unique sessions)
        // $totalVisits = AnalyticsSession::whereBetween('created_at', [$from, $to])->count();
        $totalVisits = AnalyticsSession::whereHas('events', function ($q) use ($from, $to, $vendorPdfIds) {
                                $q->whereBetween('created_at', [$from, $to])
                                    ->whereIn('target', $vendorPdfIds);
                            })
                            ->count();
        //

        // Average Reading Time per session
        // $avgReadingTime = AnalyticsSession::with(['events' => function($q) use ($from, $to) {
        //                         $q->whereBetween('created_at', [$from, $to]);
        //                     }])
        //                     ->get()
        //                     ->map(function($session) {
        //                         return $session->events
        //                             ->where('event_type', 'pdf_page_view')
        //                             ->sum('duration');
        //                     })
        //                     ->avg();
        $avgReadingTime = AnalyticsSession::with(['events' => function($q) use ($from, $to, $vendorPdfIds) {
                                $q->whereBetween('created_at', [$from, $to])
                                    ->whereIn('target', $vendorPdfIds);
                            }])
                            ->get()
                            ->map(function($session) {
                                return $session->events
                                    ->where('event_type', 'pdf_page_view')
                                    ->sum('duration');
                            })
                            ->avg();
        // 

        // Total Reading Time
        $totalReadingTime = AnalyticsEvent::where('event_type', 'pdf_page_view')
                            ->whereBetween('created_at', [$from, $to])
                            ->whereIn('target', $vendorPdfIds)
                            ->sum('duration');
        // 

        // Vendor-wise Top Documents
        $vendorDocs = Document::join('analytics_events', 'documents.id', '=', 'analytics_events.target')
                ->join('analytics_sessions', 'analytics_events.session_id', '=', 'analytics_sessions.id')
                ->select(
                    'documents.filename as document',
                    DB::raw('COUNT(analytics_events.id) as views'),
                    DB::raw('AVG(analytics_events.duration) as engagement')
                )
                ->where('documents.user_id', $user->id)
                ->whereBetween('analytics_events.created_at', [$from, $to])
                ->groupBy('documents.id', 'documents.filename')
                ->orderBy('views', 'desc')
                ->get();
        // 

        // Vendor-wise Visitors (from sessions)
        $cityVisitors = Document::join('analytics_events', 'documents.id', '=', 'analytics_events.target')
                    ->join('analytics_sessions', 'analytics_events.session_id', '=', 'analytics_sessions.id')
                    ->select(
                        'analytics_sessions.city',
                        DB::raw('COUNT(DISTINCT analytics_sessions.id) as visitors')
                    )
                    ->where('documents.user_id', $user->id)
                    ->whereBetween('analytics_sessions.created_at', [$from, $to])
                    ->groupBy('analytics_sessions.city')
                    ->orderBy('visitors', 'desc')
                    ->get();
        // 
        // echo "<pre>";
        // print_r($vendorPdfIds);
        // die;

        return view('vendor.analytics.index', [
            'totalVisits'      => $totalVisits,
            'avgReadingTime'   => $this->formatTimeDuration(round($avgReadingTime)), // format
            'totalReadingTime' => $this->formatTimeDuration($totalReadingTime),     // format
            'vendorDocs'       => $vendorDocs,
            'cityVisitors'     => $cityVisitors,
            'range'            => $range,
        ]);
    }
    public function index_COPY(Request $request)
    {
        $user   = Auth::user();
        $range  = $request->input('range', 'Last 7 days');

        // Resolve date range (inclusive)
        [$from, $to] = $this->resolveRange($range);

        // Base: analytics for this user's docs in range
        $analyticsBase = LinkAnalytics::query()
            ->whereBetween('link_analytics.created_at', [$from, $to])
            ->whereHas('link.document', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Visits in range
        $visits = (clone $analyticsBase)->count();

        // Top 5 docs
        $topDocs = DB::table('documents')
            ->where('documents.user_id', $user->id)
            ->leftJoin('links', 'links.document_id', '=', 'documents.id')
            ->leftJoin('link_analytics', function ($join) use ($from, $to) {
                $join->on('link_analytics.link_id', '=', 'links.id')
                     ->whereBetween('link_analytics.created_at', [$from, $to]);
            })
            ->select(
                'documents.id',
                'documents.filename',
                DB::raw('COUNT(link_analytics.id) as views')
            )
            ->groupBy('documents.id', 'documents.filename')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        // Optional: average/total time (kept null until you add a numeric duration column)
        $avgTime   = null;
        $totalTime = null;

        return view('vendor.analytics.index', [
            'visits'     => $visits,
            'topDocs'    => $topDocs,
            'avgTime'    => $avgTime,
            'totalTime'  => $totalTime,
        ]);
    }

    /**
     * Paginated analytics for all documents.
     */
    public function documents(Request $request)
    {
        $user  = Auth::user();
        $range = $request->input('range', 'Last 7 days');
        [$from, $to] = $this->resolveRange($range);

        // Get vendor PDFs with pagination
        $documents = Document::where('documents.user_id', $user->id)
            ->with(['analyticsEvents' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            }])
            ->paginate(10); // pagination size

        // Map analytics data for each document
        $documents->getCollection()->transform(function ($doc) use ($from, $to) {
            $views = $doc->analyticsEvents->count();

            $sessions = AnalyticsSession::whereHas('events', function ($q) use ($doc, $from, $to) {
                    $q->where('target', $doc->id)
                    ->whereBetween('created_at', [$from, $to]);
                })
                ->count();

            $lastView = $doc->analyticsEvents->sortByDesc('created_at')->first();

            return (object) [
                'id' => $doc->id,
                'filename' => $doc->filename,
                'views' => $views,
                'sessions' => $sessions,
                'last_view' => $lastView ? $lastView->created_at->format('d M Y, H:i') : null,
            ];
        });

        return view('vendor.analytics.documents', [
            'documents' => $documents,
            'range'     => $range,
        ]);
    }

    public function documents_COPY(Request $request)
    {
        $user  = Auth::user();
        $range = $request->input('range', 'Last 7 days');
        [$from, $to] = $this->resolveRange($range);

        $allDocs = DB::table('documents')
            ->where('documents.user_id', $user->id)
            ->leftJoin('links', 'links.document_id', '=', 'documents.id')
            ->leftJoin('link_analytics', function ($join) use ($from, $to) {
                $join->on('link_analytics.link_id', '=', 'links.id')
                     ->whereBetween('link_analytics.created_at', [$from, $to]);
            })
            ->select(
                'documents.id',
                'documents.filename',
                DB::raw('COUNT(link_analytics.id) as views'),
                DB::raw('COUNT(DISTINCT link_analytics.session_id) as sessions'),
                DB::raw('MAX(link_analytics.created_at) as last_view_at')
            )
            ->groupBy('documents.id', 'documents.filename')
            ->orderByDesc('views')
            ->paginate(10)
            ->withQueryString();

        return view('vendor.analytics.documents', [
            'allDocs' => $allDocs,
        ]);
    }

    /**
     * Details page for a single document (referrers/sources).
     * Route example: Route::get('/vendor/analytics/document/{id}', [AnalyticsController::class, 'document'])->name('vendor.analytics.document');
     */
    public function document(Request $request, int $id)
    {
        $user  = Auth::user();
        $range = $request->input('range', 'Last 7 days');
        [$from, $to] = $this->resolveRange($range);

        // Fetch the PDF (make sure it belongs to vendor)
        $pdf = Document::where('user_id', $user->id)->findOrFail($id);

        // Views and Sessions within range
        $events = AnalyticsEvent::where('target', $pdf->id)
                    ->whereBetween('analytics_events.created_at', [$from, $to]);

        $views = (clone $events)->count();
        $sessions = (clone $events)->distinct('session_id')->count('session_id');

        // Last view timestamp
        $lastView = (clone $events)->latest('created_at')->value('created_at');

        // Traffic sources (for now direct/unknown, later you can expand with referrer)
        $trafficSources = (clone $events)
            ->join('analytics_sessions', 'analytics_events.session_id', '=', 'analytics_sessions.id')
            ->select(
                DB::raw("COALESCE(analytics_sessions.referrer, 'Direct/Unknown') as source"),
                DB::raw("COUNT(analytics_events.id) as views"),
                DB::raw("COUNT(DISTINCT analytics_sessions.id) as sessions")
            )
            ->whereBetween('analytics_events.created_at', [$from, $to]) // 👈 fixed
            ->where('analytics_events.target', $pdf->id)
            ->groupBy('source')
            ->get();

        return view('vendor.analytics.document', [
            // 'doc'     => $doc,
            // 'sources' => $sources,
            // 'range'   => $range,
            'pdf'            => $pdf,
            'range'          => $range,
            'views'          => $views,
            'sessions'       => $sessions,
            'lastView'       => $lastView,
            'trafficSources' => $trafficSources,
        ]);
    }

    public function document_COPY(Request $request, int $id)
    {
        $user  = Auth::user();
        $range = $request->input('range', 'Last 7 days');
        [$from, $to] = $this->resolveRange($range);

        // Ensure the document belongs to this user
        $doc = DB::table('documents')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        abort_unless($doc, 404);

        // Aggregate referrers (adjust column if yours is different: referrer, referrer_host, etc.)
        $sources = DB::table('links')
            ->join('link_analytics', 'link_analytics.link_id', '=', 'links.id')
            ->where('links.document_id', $id)
            ->whereBetween('link_analytics.created_at', [$from, $to])
            ->select(
                DB::raw("COALESCE(NULLIF(link_analytics.referrer,''), 'Direct/Unknown') as source"),
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT link_analytics.session_id) as sessions')
            )
            ->groupBy('source')
            ->orderByDesc('views')
            ->paginate(20)
            ->withQueryString();

        return view('vendor.analytics.document', [
            'doc'     => $doc,
            'sources' => $sources,
            'range'   => $range,
        ]);
    }

    private function resolveRange(string $range): array
    {
        $now = Carbon::now();
        return match ($range) {
            'Today' => [ (clone $now)->startOfDay(), (clone $now)->endOfDay() ],
            'Last 7 days' => [ (clone $now)->startOfDay()->subDays(6), (clone $now)->endOfDay() ],
            'Last 30 days' => [ (clone $now)->startOfDay()->subDays(29), (clone $now)->endOfDay() ],
            'This month' => [ (clone $now)->startOfMonth(), (clone $now)->endOfMonth() ],
            'This year' => [ (clone $now)->startOfYear(), (clone $now)->endOfYear() ],
            default => [ (clone $now)->startOfDay()->subDays(6), (clone $now)->endOfDay() ],
        };
    }

    private function formatDuration(int $seconds): string
    {
        $hours   = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs    = $seconds % 60;
        if ($hours > 0) return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        return sprintf('%d:%02d', $minutes, $secs);
    }
    private function formatTimeDuration(int $seconds): string
    {
        $hours   = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs    = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d Hour', $hours, $minutes, $secs);
        }

        if ($minutes > 0) {
            return sprintf('%d:%02d Min', $minutes, $secs);
        }

        return sprintf('%d Sec', $secs);
    }

}
