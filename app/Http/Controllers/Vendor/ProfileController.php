<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(Request $request)
    {
        return view('vendor.profile.edit');
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $user->update($validated);
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'Profile updated successfully.'
            ]);
        }

        return redirect()->route('profile')->with('status', 'Profile updated successfully.');
    }
}
