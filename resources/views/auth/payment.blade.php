<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Complete Payment</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Favicons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">
  <link rel="shortcut icon" href="{{ asset('assets/favicon_io/favicon.ico') }}">

  <!-- Fonts & Vendors -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <link rel="stylesheet" href="{{ asset('assets/assets-auth/css/auth-style.css') }}">
</head>
@php
  $billingCycle = strtolower($plan->billing_cycle ?? '');
  $billingLabel = match ($billingCycle) {
    'month' => 'Monthly',
    'year' => 'Yearly',
    default => 'One-time',
  };
  $inrAmount = (float) ($plan->inr_price ?? 0);
  $usdAmount = (float) ($plan->usd_price ?? 0);
  $hasInr = $inrAmount > 0;
  $hasUsd = $usdAmount > 0;
  $pendingPayment = (bool) ($requiresPayment ?? false);

  $formatAmount = function (float $amount, string $currency): string {
    $decimals = abs($amount - round($amount)) > 0.0001 ? 2 : 0;
    $formatted = number_format($amount, $decimals);
    return match ($currency) {
      'INR' => '₹' . $formatted,
      'USD' => '$' . $formatted,
      default => $currency . ' ' . $formatted,
    };
  };

  $statusValue = strtoupper((string) $userPlan->payment_status);
  [$statusText, $statusClass] = match ($statusValue) {
    'PAID' => ['Payment completed', 'bg-success'],
    'NOT_REQUIRED' => ['No payment required', 'bg-success'],
    'PENDING' => ['Payment pending', 'bg-warning text-dark'],
    default => ['Pending confirmation', 'bg-secondary'],
  };
@endphp
<body class="auth-page payment-page d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">
        <div class="card card-container">
          <h3 class="text-center mb-2">Complete Your Subscription</h3>
          <p class="text-center text-muted mb-3">Thank you, {{ $user->first_name }}. One more step to activate your plan.</p>

          <div id="paymentApp"
               data-plan-id="{{ $plan->id }}"
               data-first-name="{{ $user->first_name }}"
               data-last-name="{{ $user->last_name }}"
               data-email="{{ $user->email }}"
               data-mobile="{{ $user->mobile ?? '' }}"
               data-company="{{ $user->company ?? '' }}"
               data-country="{{ $user->country ?? '' }}"
               data-cashfree-enabled="{{ $cashfree['enabled'] ? '1' : '0' }}"
               data-cashfree-mode="{{ $cashfree['mode'] }}"
               data-cashfree-order-url="{{ $cashfree['order_url'] ?? '' }}"
               data-complete-url="{{ $completeUrl ?? '' }}"
               data-login-url="{{ route('login') }}"
               data-requires-payment="{{ $pendingPayment ? '1' : '0' }}">
            <div class="border rounded p-3 mb-3">
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                  <h5 class="mb-1">{{ $plan->name }}</h5>
                  <p class="text-muted mb-0">Billing: {{ $billingLabel }}</p>
                </div>
                <span class="badge {{ $statusClass }} px-3 py-2">{{ $statusText }}</span>
              </div>
              <hr>
              <ul class="list-unstyled mb-0">
                <li class="mb-1"><strong>Plan ID:</strong> {{ $plan->id }}</li>
                @if($hasInr)
                  <li class="mb-1"><strong>Amount (INR):</strong> {{ $formatAmount($inrAmount, 'INR') }}{{ $billingCycle ? ' / ' . strtolower($billingLabel) : '' }}</li>
                @endif
                @if($hasUsd)
                  <li class="mb-1"><strong>Amount (USD):</strong> {{ $formatAmount($usdAmount, 'USD') }}{{ $billingCycle ? ' / ' . strtolower($billingLabel) : '' }}</li>
                @endif
                @unless($hasInr || $hasUsd)
                  <li class="mb-1"><strong>Amount:</strong> <span class="badge bg-success">Free</span></li>
                @endunless
              </ul>
            </div>

            @if($pendingPayment)
              <div class="mb-3">
                <h5 class="mb-2">Choose payment currency</h5>
                <p class="text-muted mb-3">Click Pay Now to complete your subscription securely. You can select INR or USD based on your preference.</p>
                @if(!($cashfree['enabled'] ?? false) || empty($cashfree['order_url'] ?? null) || empty($completeUrl))
                  <div class="alert alert-warning" role="alert">
                    Online payments are currently unavailable. Please contact support to complete your subscription.
                  </div>
                @endif
                <div class="d-flex flex-wrap gap-2">
                  @if($hasInr)
                    <button type="button" class="btn btn-primary" data-pay-currency="INR">
                      <span class="d-flex align-items-center">
                        <i class="bi bi-credit-card me-2"></i>
                        <span>Pay Now ({{ $formatAmount($inrAmount, 'INR') }})</span>
                      </span>
                    </button>
                  @endif
                  @if($hasUsd)
                    <button type="button" class="btn btn-outline-primary" data-pay-currency="USD">
                      <span class="d-flex align-items-center">
                        <i class="bi bi-credit-card me-2"></i>
                        <span>Pay Now ({{ $formatAmount($usdAmount, 'USD') }})</span>
                      </span>
                    </button>
                  @endif
                </div>
                <div class="alert d-none mt-3" data-base-class="alert mt-3" data-payment-status role="alert"></div>
              </div>
            @else
              @if($statusValue === 'PAID')
                <div class="alert alert-success" role="alert">
                  Your payment has been received. You can continue to sign in and start using your account.
                </div>
              @else
                <div class="alert alert-success" role="alert">
                  This plan does not require a payment. You can continue to sign in and start using your account.
                </div>
              @endif
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mt-4">
              <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                <i class="bi bi-arrow-left"></i> Back to Home
              </a>
              <a href="{{ route('login') }}" class="btn btn-success w-100 w-md-auto">
                Go to Login
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @if(($cashfree['enabled'] ?? false) && $requiresPayment)
    <script src="https://sdk.cashfree.com/js/ui/2.0.0/cashfree.js"></script>
  @endif
  <script src="{{ asset('assets/assets-auth/js/payment-page.js') }}"></script>
</body>
</html>
