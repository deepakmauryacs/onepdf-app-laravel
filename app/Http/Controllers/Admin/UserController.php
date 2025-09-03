<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;

class UserController extends Controller
{
    public function index()
    {
        $query = User::where('is_admin', false);
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users', 'search'));
    }

    public function files(User $user)
    {
        $documents = Document::where('user_id', $user->id)->paginate(15);
        return view('admin.users.files', compact('user', 'documents'));
    }
}
