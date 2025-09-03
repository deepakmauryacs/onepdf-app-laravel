<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class UserPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = UserPlan::with(['user', 'plan']);
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })->orWhereHas('plan', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                });
            });
        }
        $userPlans = $query->latest()->paginate(15)->withQueryString();
        return view('admin.user_plans.index', compact('userPlans', 'search'));
    }
}
