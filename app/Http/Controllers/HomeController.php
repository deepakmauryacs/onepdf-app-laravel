<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
