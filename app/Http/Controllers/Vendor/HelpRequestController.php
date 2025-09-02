<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Illuminate\Support\Facades\Auth;

class HelpRequestController extends Controller
{
    public function manage()
    {
        return view('vendor.help.manage');
    }

    public function manageList(Request $request)
    {
        $query = HelpRequest::where('user_id', Auth::id());

        if ($search = $request->get('search')) {
            $query->where('subject', 'like', "%{$search}%");
        }

        $requests = $query->orderByDesc('created_at')->paginate(10);

        return response()->json([
            'requests' => $requests->items(),
            'current_page' => $requests->currentPage(),
            'last_page' => $requests->lastPage(),
            'total' => $requests->total(),
            'per_page' => $requests->perPage(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file',
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'Inprogress';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('help_attachments');
        }

        HelpRequest::create($data);

        return response()->json(['success' => true]);
    }
}
