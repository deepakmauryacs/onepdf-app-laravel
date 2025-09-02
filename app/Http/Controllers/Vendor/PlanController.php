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

        return view('vendor.plan.index', compact('currentPlan', 'plans'));
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
}
