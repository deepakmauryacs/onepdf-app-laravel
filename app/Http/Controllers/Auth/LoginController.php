<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Inline validation
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        // Attempt auth
        if (Auth::attempt($credentials, true)) {
            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Mirror your PHP session variables
            $user = Auth::user();
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->first_name ?? $user->name ?? '');
            Session::put('use_id', $user->use_id ?? null);

            $redirect = $user->is_admin ? route('admin.dashboard') : route('dashboard');

            return response()->json([
                'success' => true,
                'redirect' => $redirect,
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 422);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
