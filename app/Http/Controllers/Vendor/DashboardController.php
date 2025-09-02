<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    /**
     * Show the vendor dashboard with recent files for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $documents = Document::where('user_id', $user->id)
            ->with('link')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($doc) {
                return [
                    'id'       => $doc->id,
                    'filename' => $doc->filename,
                    'size'     => (int) $doc->size,
                    'modified' => optional($doc->updated_at)->format('Y-m-d H:i:s'),
                    'url'      => $doc->link ? URL::to('/view') . '?doc=' . $doc->link->slug : null,
                ];
            });

        $total = Document::where('user_id', $user->id)->count();

        return view('vendor.dashboard.index', [
            'files' => $documents,
            'total' => $total,
        ]);
    }
}
