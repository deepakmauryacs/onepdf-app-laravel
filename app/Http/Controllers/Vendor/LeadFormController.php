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

    public function edit(LeadForm $lead_form)
    {
        abort_if($lead_form->user_id !== Auth::id(), 403);

        return view('vendor.lead_forms.edit', [
            'form' => $lead_form,
        ]);
    }

    public function update(Request $request, LeadForm $lead_form)
    {
        abort_if($lead_form->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'fields' => 'nullable|string',
        ]);

        $lead_form->update([
            'fields' => $data['fields'] ? json_decode($data['fields'], true) : null,
        ]);

        return redirect()->route('vendor.lead_forms.index');
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        LeadForm::where('user_id', Auth::id())
            ->whereIn('id', $data['ids'])
            ->delete();

        return redirect()->route('vendor.lead_forms.index');
    }
}
