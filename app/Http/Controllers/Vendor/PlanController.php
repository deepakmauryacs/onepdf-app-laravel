<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Show the current plan and available upgrades.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $currentPlan = UserPlan::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->orderByDesc('start_date')
            ->first();

        $plansQuery = Plan::orderBy('usd_price');
        if ($currentPlan) {
            $plansQuery->where('id', '!=', $currentPlan->plan_id);
        }
        $plans = $plansQuery->get();

        $usedBytes = \App\Models\Document::where('user_id', $user->id)->sum('size');
        $monthlyCount = \App\Models\Document::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $planModel = $currentPlan?->plan ?? Plan::where('billing_cycle', 'free')->first();
        $storageLimitBytes = $planModel?->storageBytes();
        $monthlyLimit = $planModel?->monthlyFileLimit();

        $usedReadable = $this->formatBytes($usedBytes);
        $limitReadable = $storageLimitBytes ? $this->formatBytes($storageLimitBytes) : null;

        return view('vendor.plan.index', compact(
            'currentPlan', 'plans', 'usedReadable', 'limitReadable', 'monthlyCount', 'monthlyLimit'
        ));
    }

    /**
     * Upgrade the authenticated user's plan.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($data['plan_id']);
        $user = $request->user();

        $start = Carbon::today();
        $end = null;
        if ($plan->billing_cycle === 'month') {
            $end = $start->copy()->addMonth();
        } elseif ($plan->billing_cycle === 'year') {
            $end = $start->copy()->addYear();
        }

        DB::transaction(function () use ($user, $plan, $start, $end) {
            UserPlan::where('user_id', $user->id)
                ->where('status', 1)
                ->update(['status' => 2]);

            UserPlan::create([
                'user_id'    => $user->id,
                'plan_id'    => $plan->id,
                'start_date' => $start->toDateString(),
                'end_date'   => $end?->toDateString(),
                'status'     => 1,
            ]);
        });

        return response()->json(['success' => true]);
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B','KB','MB','GB','TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2).' '.$units[$i];
    }
}
