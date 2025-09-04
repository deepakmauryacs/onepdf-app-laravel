<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\LeadForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadFormController extends Controller
{
    public function index()
    {
        $forms = LeadForm::where('user_id', Auth::id())->latest()->get();

        return view('vendor.lead_forms.index', [
            'forms' => $forms,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        LeadForm::create([
            'user_id' => Auth::id(),
            'name'    => $data['name'],
        ]);

        return redirect()->back();
    }
}
