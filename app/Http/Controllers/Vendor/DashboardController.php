<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the vendor dashboard.
     */
    public function index()
    {
        return view('vendor.dashboard.index');
    }
}
