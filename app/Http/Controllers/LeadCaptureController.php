<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

        $lead = Lead::create([
            'document_id'  => $link->document_id,
            'lead_form_id' => $link->lead_form_id,
            'name'         => $request->name ?? '',
            'email'        => $request->email,
        ]);

        // Also persist the lead data to a JSON file for easy export/viewing.
        $leads = [];
        if (Storage::disk('local')->exists('leads.json')) {
            $leads = json_decode(Storage::disk('local')->get('leads.json'), true) ?? [];
        }
        $leads[] = $lead->toArray();
        Storage::disk('local')->put('leads.json', json_encode($leads));

        return response()->json(['success' => true]);
    }
}
