<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\CashfreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CashfreeController extends Controller
{
    public function createOrder(Request $request, CashfreeService $cashfree)
    {
        if (! $cashfree->enabled()) {
            abort(404);
        }

        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'currency' => ['required', 'in:INR,USD'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);

        if (! $plan->isCashfreeEligible()) {
            throw ValidationException::withMessages([
                'plan_id' => 'The selected plan does not support Cashfree payments.',
            ]);
        }

        $currency = strtoupper($validated['currency']);
        $amount = $currency === 'INR'
            ? (float) $plan->inr_price
            : (float) $plan->usd_price;

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'plan_id' => 'The selected plan does not require a payment.',
            ]);
        }

        $orderId = sprintf(
            '%s-%s-%s',
            $cashfree->orderPrefix(),
            now()->format('YmdHis'),
            strtoupper(Str::random(6))
        );

        $customerId = substr(preg_replace('/[^A-Za-z0-9]/', '', strtolower($validated['email'])), 0, 35);
        if ($customerId === '') {
            $customerId = 'guest-' . strtolower(Str::random(8));
        }

        $customerName = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $payload = [
            'order_id' => $orderId,
            'order_amount' => round($amount, 2),
            'order_currency' => $currency,
            'customer_details' => array_filter([
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'customer_email' => $validated['email'],
            ]),
            'order_note' => sprintf('OneLinkPDF %s (%s)', $plan->name, strtoupper($plan->billing_cycle)),
        ];

        if (! empty($validated['country'])) {
            $payload['customer_details']['customer_country'] = $validated['country'];
        }

        if (! empty($validated['company'])) {
            $payload['order_meta']['udf1'] = $validated['company'];
        }

        if ($returnUrl = config('cashfree.return_url')) {
            $payload['order_meta']['return_url'] = str_replace('{order_id}', $orderId, $returnUrl);
        }

        try {
            $order = $cashfree->createOrder($payload);
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'cashfree' => $exception->getMessage(),
            ]);
        }

        if (empty($order['payment_session_id'])) {
            throw ValidationException::withMessages([
                'cashfree' => 'Unable to initiate Cashfree checkout at the moment. Please try again.',
            ]);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order['order_id'] ?? $orderId,
            'payment_session_id' => $order['payment_session_id'],
            'order_amount' => round($amount, 2),
            'order_currency' => $currency,
        ]);
    }

    public function verifyOrder(Request $request, CashfreeService $cashfree)
    {
        if (! $cashfree->enabled()) {
            abort(404);
        }

        $validated = $request->validate([
            'order_id' => ['required', 'string', 'max:100'],
        ]);

        try {
            $order = $cashfree->getOrder($validated['order_id']);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'order_status' => $order['order_status'] ?? null,
            'order_amount' => isset($order['order_amount']) ? (float) $order['order_amount'] : null,
            'order_currency' => $order['order_currency'] ?? null,
        ]);
    }
}
