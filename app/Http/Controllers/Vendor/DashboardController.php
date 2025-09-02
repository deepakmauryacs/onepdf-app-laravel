<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    /**
     * Show the vendor dashboard with recent files for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $days = (int) $request->get('days', 7);
        if (!in_array($days, [7, 15, 30])) {
            $days = 7;
        }

        $docs = Document::where('user_id', $user->id)
            ->where('updated_at', '>=', now()->subDays($days))
            ->with('link')
            ->latest()
            ->paginate(10)
            ->appends(['days' => $days]);

        $docs->getCollection()->transform(function ($doc) {
            return [
                'id'       => $doc->id,
                'filename' => $doc->filename,
                'size'     => (int) $doc->size,
                'modified' => optional($doc->updated_at)->format('Y-m-d H:i:s'),
                'url'      => $doc->link ? URL::to('/view') . '?doc=' . $doc->link->slug : null,
            ];
        });

        return view('vendor.dashboard.index', [
            'files' => $docs,
            'days'  => $days,
        ]);
    }
}
