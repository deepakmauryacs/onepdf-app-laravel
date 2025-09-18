<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Registration</title>

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

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ asset('assets/assets-auth/css/auth-style.css') }}">
</head>
@php
  $cashfree = array_merge([
    'enabled' => false,
    'mode' => 'sandbox',
    'order_url' => null,
    'verify_url' => null,
  ], $cashfree ?? []);
@endphp

<body class="auth-page register-page d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card card-container">
          <h3 class="text-center mb-1">Create Account</h3>
          <p class="text-center text-muted mb-2">Join OneLinkPDF in minutes.</p>

          <!-- Centered Free Plan Message -->
          <div class="free-pill-wrapper">
            <span class="free-pill">No credit card or payment required for the Free plan</span>
          </div>

          <form id="registerForm" method="POST" novalidate
                data-store-url="{{ route('register.store') }}"
                data-captcha-url="{{ route('register.captcha') }}"
                data-login-url="{{ route('login') }}"
                data-cashfree-enabled="{{ $cashfree['enabled'] ? '1' : '0' }}"
                data-cashfree-mode="{{ $cashfree['mode'] }}"
                data-cashfree-order-url="{{ $cashfree['order_url'] ?? '' }}"
                data-cashfree-verify-url="{{ $cashfree['verify_url'] ?? '' }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-floating mb-1 with-icon">
                  <i class="bi bi-person fi"></i>
                  <input type="text" name="first_name" class="form-control" id="firstName" placeholder="First Name" required>
                  <label for="firstName">First Name</label>
                </div>
                <div id="first_name_error" class="error-message"></div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-1 with-icon">
                  <i class="bi bi-person fi"></i>
                  <input type="text" name="last_name" class="form-control" id="lastName" placeholder="Last Name" required>
                  <label for="lastName">Last Name</label>
                </div>
                <div id="last_name_error" class="error-message"></div>
              </div>
            </div>

            <div class="form-floating mb-1 with-icon floating-select">
              <i class="bi bi-globe fi"></i>
              <select name="country" class="form-select" id="country" required>
                <option value="" disabled selected>Select Country</option>
                @foreach($countries as $country)
                  <option value="{{ $country->name }}" data-iso="{{ $country->iso }}">{{ $country->name }}</option>
                @endforeach
              </select>
              <!-- <label for="country">Country</label> -->
            </div>
            <div id="country_error" class="error-message"></div>

            <div class="form-floating mb-1 with-icon">
              <i class="bi bi-building fi"></i>
              <input type="text" name="company" class="form-control" id="company" placeholder="Company" required>
              <label for="company">Company</label>
            </div>
            <div id="company_error" class="error-message"></div>

            <div class="form-floating mb-1 with-icon floating-select">
              <i class="bi bi-layers fi"></i>
              <select name="plan_id" class="form-select" id="plan" required disabled aria-disabled="true">
                @if($plans->isNotEmpty())
                  <option value="" selected>Select Plan</option>
                  @foreach($plans as $plan)
                    @php
                      $usdString = (string) $plan->usd_price;
                      $decimalPart = '';
                      if (str_contains($usdString, '.')) {
                          $decimalPart = rtrim(substr($usdString, strpos($usdString, '.') + 1), '0');
                      }
                      $decimalCount = $decimalPart !== '' ? 2 : 0;
                      $usdValue = (float) $plan->usd_price;
                      $suffixMap = ['month' => '/month', 'year' => '/year'];
                      $suffix = $suffixMap[$plan->billing_cycle] ?? '';
                      $defaultLabel = $usdValue > 0
                          ? $plan->name . ' - $' . number_format($usdValue, $decimalCount) . $suffix
                          : $plan->name . ' - Free';
                    @endphp
                    <option value="{{ $plan->id }}"
                            data-plan="true"
                            data-name="{{ $plan->name }}"
                            data-billing="{{ $plan->billing_cycle }}"
                            data-inr-price="{{ $plan->inr_price }}"
                            data-usd-price="{{ $plan->usd_price }}">
                      {{ $defaultLabel }}
                    </option>
                  @endforeach
                @else
                  <option value="" selected disabled>No plans available</option>
                @endif
              </select>
              <!-- <label for="plan">Plan</label> -->
            </div>
            <div id="plan_id_error" class="error-message"></div>

            <input type="hidden" name="cashfree_order_id" id="cashfreeOrderId">
            <input type="hidden" name="cashfree_payment_currency" id="cashfreeCurrency">
            <input type="hidden" name="cashfree_payment_amount" id="cashfreeAmount">

            <div id="cashfreePaymentSection" class="card border-0 shadow-sm mt-2 d-none">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <i class="bi bi-credit-card-2-front text-primary fs-4 me-2"></i>
                  <div>
                    <h5 class="mb-0" style="font-weight:600;">Complete your payment</h5>
                    <small class="text-muted">Pro and Business monthly plans require a secure Cashfree checkout. Choose INR or USD below to activate your subscription.</small>
                  </div>
                </div>
                <p id="cashfreePlanSummary" class="small text-muted mb-3 d-none" aria-live="polite"></p>
                <div class="d-flex flex-wrap gap-2 mb-2">
                  <button type="button" class="btn btn-outline-primary" data-cashfree-currency="INR">
                    <span class="d-flex align-items-center">
                      <span>Pay with Cashfree (INR)</span>
                      <span class="cashfree-amount badge bg-light text-dark ms-2" data-amount-currency="INR"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-outline-primary" data-cashfree-currency="USD">
                    <span class="d-flex align-items-center">
                      <span>Pay with Cashfree (USD)</span>
                      <span class="cashfree-amount badge bg-light text-dark ms-2" data-amount-currency="USD"></span>
                    </span>
                  </button>
                </div>
                <p id="cashfreePaymentStatus" class="small text-muted mb-1"></p>
                <div id="cashfree_error" class="error-message"></div>
              </div>
            </div>

            <div class="form-floating mb-1 with-icon">
              <i class="bi bi-envelope fi"></i>
              <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required autocomplete="off">
              <label for="email">Email address</label>
            </div>
            <div id="email_error" class="error-message"></div>

            <div class="form-floating mb-1 position-relative password-floating with-icon">
              <i class="bi bi-lock fi"></i>
              <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6" autocomplete="off">
              <label for="password">Password</label>
              <i class="bi bi-eye toggle-password" id="togglePassword" aria-label="Show/Hide password" role="button" tabindex="0"></i>
            </div>
            <div id="password_error" class="error-message"></div>

            <!-- Strength meter -->
            <div id="password-strength" class="mt-2">
              <div class="progress" role="progressbar" aria-label="Password strength" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: 0%"></div>
              </div>
              <span id="strength-text" class="text-muted"></span>
            </div>

            <div class="form-check mb-2 mt-3">
              <input class="form-check-input" type="checkbox" name="agreed_terms" id="terms">
              <label class="form-check-label" for="terms">
                I agree to the
                <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer">Privacy Policy</a>
                &
                <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer">Terms of Service</a>
              </label>
            </div>
            <div id="agreed_terms_error" class="error-message"></div>

            <div class="mb-3">
              <label for="captcha" id="captcha_label" class="form-label">What is {{ $captcha_a }} + {{ $captcha_b }}?</label>
              <div class="input-group">
                <input type="text" class="form-control" id="captcha" name="captcha" aria-label="Captcha">
                <button type="button" class="btn btn-outline-secondary" id="refreshCaptcha" aria-label="Refresh captcha">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </div>
              <div id="captcha_error" class="error-message"></div>
            </div>

            <div class="d-grid gap-2 mt-3">
              <button type="submit" class="btn btn-primary btn-lg">Register</button>
            </div>

            <div class="text-center mt-3">
              <span class="text-muted">Already have an account?</span>
              <a href="{{ route('login') }}" class="fw-semibold">Log in</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Toasts -->
  <div id="bsToasts" class="toast-container position-fixed top-0 end-0 p-3"></div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @if($cashfree['enabled'])
    <script src="https://sdk.cashfree.com/js/ui/2.0.0/cashfree.js"></script>
  @endif
  <script src="{{ asset('assets/assets-auth/js/auth-main.js') }}"></script>
</body>
</html>
