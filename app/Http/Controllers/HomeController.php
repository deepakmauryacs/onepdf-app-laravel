<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Display the application home page.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Display the features page.
     */
    public function features()
    {
        return view('features');
    }

    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        return view('pricing');
    }

    /**
     * Display the how it works page.
     */
    public function howItWorks()
    {
        return view('how-it-works');
    }

    public function contact()
    {
        return view('contact');
    }

    public function partnerships()
    {
        return view('partnerships');
    }

    

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('terms');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('privacy');
    }

    /**
     * Display the login page.
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Display the registration page.
     */
    public function registration()
    {
        return view('registration');
    }

    /**
     * Handle registration form submission.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json(['message' => 'Registration successful']);
    }
}
