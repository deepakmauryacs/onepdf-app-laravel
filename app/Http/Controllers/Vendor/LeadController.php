<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Lead::whereHas('document', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('leadForm')->latest();

        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('document', function ($dq) use ($search) {
                      $dq->where('filename', 'like', "%{$search}%");
                  })
                  ->orWhereHas('leadForm', function ($fq) use ($search) {
                      $fq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $leads = $query->paginate(10)->withQueryString();

        return view('vendor.leads.index', [
            'leads' => $leads,
            'search' => $search,
        ]);
    }
}
