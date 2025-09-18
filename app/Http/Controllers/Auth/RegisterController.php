<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Rules\Captcha;
use App\Services\CashfreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function create(CashfreeService $cashfree)
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);
        session(['captcha_answer' => $a + $b]);

        $countries = Country::orderBy('name')->get();
        $plans = Plan::orderBy('id')->get();

        $cashfreeConfig = [
            'enabled' => $cashfree->enabled(),
            'mode' => $cashfree->mode(),
            'order_url' => $cashfree->enabled() ? route('register.cashfree.order') : null,
            'verify_url' => $cashfree->enabled() ? route('register.cashfree.verify') : null,
        ];

        return view('auth.register', [
            'captcha_a' => $a,
            'captcha_b' => $b,
            'countries' => $countries,
            'plans' => $plans,
            'cashfree' => $cashfreeConfig,
        ]);
    }

    public function store(Request $request, CashfreeService $cashfree)
    {
        // Inline validation (no FormRequest class needed)
        $validated = $request->validate([
            'first_name'   => ['required','string','max:255'],
            'last_name'    => ['required','string','max:255'],
            'country'      => ['required','string','max:255','exists:countries,name'],
            'company'      => ['required','string','max:255'],
            'plan_id'      => ['required','exists:plans,id'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:6'],
            'agreed_terms' => ['accepted'], // checkbox must be checked
            'captcha'      => ['required', new Captcha()],
            'cashfree_order_id' => ['nullable', 'string', 'max:100'],
            'cashfree_payment_currency' => ['nullable', 'string', 'max:3'],
            'cashfree_payment_amount' => ['nullable', 'numeric'],
        ], [
            'agreed_terms.accepted' => 'Terms must be accepted',
        ]);

        try {
            DB::beginTransaction();

            // Generate use_id (16 digits)
            $useId = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT)
                   . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

            $user = User::create([
                'use_id'       => $useId,
                'country'      => $validated['country'],
                'first_name'   => $validated['first_name'],
                'last_name'    => $validated['last_name'],
                'company'      => $validated['company'],
                'email'        => $validated['email'],
                'password'     => $validated['password'],
                'agreed_terms' => true,
            ]);

            $planId = (int) $validated['plan_id'];
            $plan = Plan::find($planId);

            if (! $plan) {
                throw ValidationException::withMessages([
                    'plan_id' => 'Selected plan is no longer available.',
                ]);
            }

            $requiresCashfree = $plan->requiresCashfreePayment();
            $paymentIsRequired = ((float) $plan->inr_price > 0) || ((float) $plan->usd_price > 0);

            $planStatus = $paymentIsRequired ? 2 : 1;

            $paymentData = [
                'provider' => null,
                'reference' => null,
                'amount' => null,
                'currency' => null,
                'status' => $paymentIsRequired ? 'PENDING' : 'NOT_REQUIRED',
            ];

            if ($requiresCashfree && $cashfree->enabled()) {
                $orderId = $validated['cashfree_order_id'] ?? null;

                if ($orderId) {
                    try {
                        $order = $cashfree->getOrder($orderId);
                    } catch (\RuntimeException $exception) {
                        throw ValidationException::withMessages([
                            'cashfree' => 'Unable to verify payment: ' . $exception->getMessage(),
                        ]);
                    }

                    $status = strtoupper($order['order_status'] ?? '');
                    if ($status !== 'PAID') {
                        throw ValidationException::withMessages([
                            'cashfree' => 'Cashfree payment has not been completed yet.',
                        ]);
                    }

                    $currency = strtoupper($order['order_currency'] ?? '');
                    $amount = isset($order['order_amount']) ? (float) $order['order_amount'] : null;

                    if (! in_array($currency, ['INR', 'USD'], true) || $amount === null) {
                        throw ValidationException::withMessages([
                            'cashfree' => 'Unexpected payment details received from Cashfree.',
                        ]);
                    }

                    $expectedAmount = $currency === 'INR'
                        ? (float) $plan->inr_price
                        : (float) $plan->usd_price;

                    if (abs($expectedAmount - $amount) > 0.01) {
                        throw ValidationException::withMessages([
                            'cashfree' => 'Cashfree payment amount does not match the selected plan.',
                        ]);
                    }

                    $paymentData = [
                        'provider' => 'cashfree',
                        'reference' => $orderId,
                        'amount' => round($amount, 2),
                        'currency' => $currency,
                        'status' => $status,
                    ];

                    $planStatus = 1;
                }
            }

            $start = Carbon::today();
            $end = match ($plan->billing_cycle) {
                'month' => $start->copy()->addMonth()->toDateString(),
                'year'  => $start->copy()->addYear()->toDateString(),
                default => null,
            };

            DB::table('user_plans')->insert([
                'user_id'    => $user->id,
                'plan_id'    => $plan->id,
                'start_date' => $start->toDateString(),
                'end_date'   => $end,
                'status'     => $planStatus,
                'payment_provider' => $paymentData['provider'],
                'payment_reference' => $paymentData['reference'],
                'payment_amount' => $paymentData['amount'],
                'payment_currency' => $paymentData['currency'],
                'payment_status' => $paymentData['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $a = random_int(1, 9);
            $b = random_int(1, 9);
            session(['captcha_answer' => $a + $b]);

            $redirectUrl = URL::temporarySignedRoute('register.payment', now()->addMinutes(30), [
                'user' => $user->id,
            ]);

            return response()->json([
                'success'   => true,
                'captcha_a' => $a,
                'captcha_b' => $b,
                'redirect_url' => $redirectUrl,
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            DB::rollBack();
            throw $ve;
        } catch (\Throwable $e) {
            DB::rollBack();

            // Map unique email error to your old API message
            if ($e instanceof \Illuminate\Database\QueryException &&
                str_contains(strtolower($e->getMessage()), 'unique') &&
                str_contains(strtolower($e->getMessage()), 'users_email_unique')) {
                return response()->json(['error' => 'Email already exists'], 422);
            }

            return response()->json(['error' => 'Registration failed'], 500);
        }
    }

    public function payment(Request $request, CashfreeService $cashfree, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $userPlan = UserPlan::where('user_id', $user->id)
            ->latest('id')
            ->with('plan')
            ->first();

        if (! $userPlan || ! $userPlan->plan) {
            abort(404);
        }

        $plan = $userPlan->plan;
        $paymentIsRequired = ((float) $plan->inr_price > 0) || ((float) $plan->usd_price > 0);
        $paymentPending = $paymentIsRequired && strtoupper((string) $userPlan->payment_status) !== 'PAID';

        $cashfreeConfig = [
            'enabled' => $cashfree->enabled(),
            'mode' => $cashfree->mode(),
            'order_url' => $cashfree->enabled() ? route('register.cashfree.order') : null,
        ];

        $completeUrl = null;
        if ($cashfreeConfig['enabled'] && $plan->requiresCashfreePayment() && $paymentPending) {
            $completeUrl = URL::temporarySignedRoute(
                'register.payment.complete',
                now()->addMinutes(30),
                ['user' => $user->id, 'plan' => $plan->id]
            );
        }

        return view('auth.payment', [
            'user' => $user,
            'plan' => $plan,
            'userPlan' => $userPlan,
            'requiresPayment' => $paymentPending,
            'cashfree' => $cashfreeConfig,
            'completeUrl' => $completeUrl,
        ]);
    }

    public function completePayment(Request $request, CashfreeService $cashfree, User $user, Plan $plan)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $validated = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
        ]);

        $userPlan = UserPlan::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->latest('id')
            ->first();

        if (! $userPlan) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription record not found for this account.',
            ], 404);
        }

        if (! $plan->requiresCashfreePayment()) {
            return response()->json([
                'success' => false,
                'message' => 'This plan does not require a Cashfree payment.',
            ], 422);
        }

        if (! $cashfree->enabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Cashfree payments are currently unavailable. Please try again later.',
            ], 422);
        }

        try {
            $order = $cashfree->getOrder($validated['order_id']);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to verify payment: ' . $exception->getMessage(),
            ], 422);
        }

        $status = strtoupper($order['order_status'] ?? '');
        if ($status !== 'PAID') {
            return response()->json([
                'success' => false,
                'message' => 'Cashfree has not confirmed this payment yet.',
            ], 422);
        }

        $currency = strtoupper($order['order_currency'] ?? '');
        $amount = isset($order['order_amount']) ? (float) $order['order_amount'] : null;

        if (! in_array($currency, ['INR', 'USD'], true) || $amount === null) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected payment details received from Cashfree.',
            ], 422);
        }

        $expectedAmount = $currency === 'INR'
            ? (float) $plan->inr_price
            : (float) $plan->usd_price;

        if (abs($expectedAmount - $amount) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Cashfree payment amount does not match the selected plan.',
            ], 422);
        }

        DB::table('user_plans')
            ->where('id', $userPlan->id)
            ->update([
                'status' => 1,
                'payment_provider' => 'cashfree',
                'payment_reference' => $validated['order_id'],
                'payment_amount' => round($amount, 2),
                'payment_currency' => $currency,
                'payment_status' => $status,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('login'),
        ]);
    }
}
