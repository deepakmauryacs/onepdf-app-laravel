<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Validation\ValidationException;

class NewsletterSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required','email','max:150'],
        ]);

        // Create or detect duplicate gracefully
        $subscriber = NewsletterSubscriber::where('email', $validated['email'])->first();

        if ($subscriber) {
            return response()->json([
                'success' => true,
                'message' => 'You are already subscribed. 🎉'
            ]);
        }

        NewsletterSubscriber::create(['email' => $validated['email']]);

        return response()->json([
            'success' => true,
            'message' => 'Thanks for subscribing! Please check your inbox.'
        ]);
    }
}
