<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LeadCaptureController extends Controller
{
    public function store(Request $request)
    {
        // Normalize empty values so validation rules work even when fields
        // are omitted or left blank on the lead form.
        $request->merge([
            'email' => $request->email ?: null,
            'name'  => $request->name ?: null,
        ]);

        $request->validate([
            'slug' => 'required|string',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $link = Link::where('slug', $request->slug)->firstOrFail();

        if (!Schema::hasTable('leads')) {
            return response()->json(['error' => 'Leads table not found'], 500);
        }

        Lead::create([
            'document_id'  => $link->document_id,
            'lead_form_id' => $link->lead_form_id,
            'name'         => $request->name ?? '',
            'email'        => $request->email,
        ]);

        return response()->json(['success' => true]);
    }
}
