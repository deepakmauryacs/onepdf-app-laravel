<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnershipMessage;
use App\Rules\Captcha;

class PartnershipsController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming AJAX data
        $data = $request->validate([
            'firstName'       => ['required','string','max:100'],
            'lastName'        => ['required','string','max:100'],
            'email'           => ['required','email','max:150'],
            'contact_number'  => ['required','string','max:32'],
            'message'         => ['required','string','min:5','max:10000'],
            'captcha'         => ['required', new Captcha()],
        ], [], [
            'firstName' => 'first name',
            'lastName'  => 'last name',
        ]);

        // Save
        PartnershipMessage::create([
            'first_name'     => $data['firstName'],
            'last_name'      => $data['lastName'],
            'email'          => $data['email'],
            'contact_number' => $data['contact_number'],
            'message'        => $data['message'],
        ]);
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        session(['captcha_answer' => $a + $b]);

        return response()->json([
            'success'    => true,
            'captcha_a'  => $a,
            'captcha_b'  => $b,
        ]);
    }
}
