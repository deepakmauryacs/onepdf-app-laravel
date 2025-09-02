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

        $docs = Document::where('user_id', $user->id)
            ->with('link')
            ->latest()
            ->paginate(10);

        $files = $docs->getCollection()->map(function ($doc) {
            return [
                'id'       => $doc->id,
                'filename' => $doc->filename,
                'size'     => (int) $doc->size,
                'modified' => optional($doc->updated_at)->format('Y-m-d H:i:s'),
                'url'      => $doc->link ? URL::to('/view') . '?doc=' . $doc->link->slug : null,
            ];
        });

        return view('vendor.dashboard.index', [
            'files'        => $files,
            'total'        => $docs->total(),
            'current_page' => $docs->currentPage(),
            'last_page'    => $docs->lastPage(),
        ]);
    }

    /**
     * Return paginated files for the dashboard (AJAX "Load More").
     */
    public function list()
    {
        $user = Auth::user();

        $docs = Document::where('user_id', $user->id)
            ->with('link')
            ->latest()
            ->paginate(10);

        $files = $docs->getCollection()->map(function ($doc) {
            return [
                'id'       => $doc->id,
                'filename' => $doc->filename,
                'size'     => (int) $doc->size,
                'modified' => optional($doc->updated_at)->format('Y-m-d H:i:s'),
                'url'      => $doc->link ? URL::to('/view') . '?doc=' . $doc->link->slug : null,
            ];
        });

        return response()->json([
            'files'        => $files,
            'current_page' => $docs->currentPage(),
            'last_page'    => $docs->lastPage(),
            'total'        => $docs->total(),
        ]);
    }
}
