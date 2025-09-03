<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\LinkAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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

        // All docs (paginated 10)
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
                // If you track unique sessions:
                DB::raw('COUNT(DISTINCT link_analytics.session_id) as sessions'),
                DB::raw('MAX(link_analytics.created_at) as last_view_at')
            )
            ->groupBy('documents.id', 'documents.filename')
            ->orderByDesc('views')
            ->paginate(10)
            ->withQueryString();

        // Optional: average/total time (kept null until you add a numeric duration column)
        $avgTime   = null;
        $totalTime = null;

        return view('vendor.analytics.index', [
            'visits'     => $visits,
            'topDocs'    => $topDocs,
            'allDocs'    => $allDocs,
            'avgTime'    => $avgTime,
            'totalTime'  => $totalTime,
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
}
