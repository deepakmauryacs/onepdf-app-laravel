<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function files(User $user)
    {
        $documents = Document::where('user_id', $user->id)->paginate(15);
        return view('admin.users.files', compact('user', 'documents'));
    }
}
