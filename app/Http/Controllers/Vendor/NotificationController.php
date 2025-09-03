<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications and mark unread as read.
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::forUser($user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        Notification::forUser($user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('vendor.notifications.index', compact('notifications'));
    }
}

