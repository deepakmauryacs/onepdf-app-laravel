<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Accept both AJAX and normal, but return JSON always
        $data = $request->validate([
            'firstName' => ['required','string','max:100'],
            'lastName'  => ['required','string','max:100'],
            'email'     => ['required','email','max:150'],
            'company'   => ['nullable','string','max:100'],
            'subject'   => ['required','in:sales,support,billing,partnership,other'],
            'message'   => ['required','string','min:10','max:10000'],
        ], [], [
            'firstName' => 'first name',
            'lastName'  => 'last name',
        ]);

        Contact::create([
            'first_name' => $data['firstName'],
            'last_name'  => $data['lastName'],
            'email'      => $data['email'],
            'company'    => $data['company'] ?? null,
            'subject'    => $data['subject'],
            'message'    => $data['message'],
        ]);

        return response()->json(['success' => true]);
    }
}
