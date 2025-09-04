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

    public function destroy(Lead $lead)
    {
        $lead->load('document');
        abort_unless(optional($lead->document)->user_id === Auth::id(), 403);
        $lead->delete();
        return redirect()->route('vendor.leads.index')->with('status', 'Lead deleted');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->route('vendor.leads.index');
        }

        $userId = Auth::id();
        Lead::whereIn('id', $ids)
            ->whereHas('document', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->delete();

        return redirect()->route('vendor.leads.index')->with('status', 'Selected leads deleted');
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $leads = Lead::whereHas('document', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['document', 'leadForm'])->cursor();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads.csv"',
        ];

        $callback = static function () use ($leads) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Document', 'Form', 'Date']);
            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->name,
                    $lead->email,
                    optional($lead->document)->filename,
                    optional($lead->leadForm)->name,
                    $lead->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
