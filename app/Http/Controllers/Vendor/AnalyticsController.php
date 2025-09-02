<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\LinkAnalytics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display basic analytics for the authenticated vendor.
     */
    public function index()
    {
        $user = Auth::user();

        // Total analytics events for the user's documents in the last 7 days
        $visits = LinkAnalytics::where('created_at', '>=', now()->subDays(7))
            ->whereHas('link.document', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->count();

        // Top 5 documents by analytics events
        $topDocs = DB::table('documents')
            ->where('documents.user_id', $user->id)
            ->leftJoin('links', 'links.document_id', '=', 'documents.id')
            ->leftJoin('link_analytics', 'link_analytics.link_id', '=', 'links.id')
            ->select('documents.filename', DB::raw('COUNT(link_analytics.id) as views'))
            ->groupBy('documents.id', 'documents.filename')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        return view('vendor.analytics.index', [
            'visits'  => $visits,
            'topDocs' => $topDocs,
        ]);
    }
}

