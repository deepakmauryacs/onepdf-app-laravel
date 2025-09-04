<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $leads = Lead::whereHas('document', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('leadForm')->latest()->paginate(10);

        return view('vendor.leads.index', [
            'leads' => $leads,
        ]);
    }
}
