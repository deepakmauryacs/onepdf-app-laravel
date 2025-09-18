<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Plan;
use App\Rules\Captcha;
use App\Services\CashfreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

            $requiresCashfree = $plan->billing_cycle === 'month'
                && in_array(strtolower($plan->name), ['pro', 'business'], true)
                && ((float) $plan->inr_price > 0 || (float) $plan->usd_price > 0);

            $paymentData = [
                'provider' => null,
                'reference' => null,
                'amount' => null,
                'currency' => null,
                'status' => null,
            ];

            if ($requiresCashfree) {
                if (! $cashfree->enabled()) {
                    throw ValidationException::withMessages([
                        'cashfree' => 'Cashfree payments are currently unavailable. Please try again later or contact support.',
                    ]);
                }

                $orderId = $validated['cashfree_order_id'] ?? null;

                if (! $orderId) {
                    throw ValidationException::withMessages([
                        'cashfree' => 'Payment verification is required for the selected plan.',
                    ]);
                }

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
                'status'     => 1,
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

            return response()->json([
                'success'   => true,
                'captcha_a' => $a,
                'captcha_b' => $b,
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
}
