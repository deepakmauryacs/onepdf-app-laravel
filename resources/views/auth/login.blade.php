<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
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
<body class="auth-page login-page d-flex align-items-center min-vh-100" data-home-url="{{ url('/') }}">
  <div class="container py-5">
    <!-- Back to website (top) -->
    <div class="toplinks mb-3 text-center">
      <a id="backHomeTop" href="{{ url('/') }}" title="Go to main website (Esc or Alt+H)">
        <i class="bi bi-arrow-left-circle me-1"></i> Back to Website
      </a>
    </div>

    <div class="card-container">
      <h3 class="text-center mb-3">Login</h3>
      <p class="text-center text-muted mb-4">Welcome back — enter your credentials to continue.</p>

      <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate data-redirect="{{ url('/vendor/dashboard') }}">
        @csrf

        <!-- Email with left icon -->
        <div class="form-floating mb-2 with-icon">
          <i class="bi bi-envelope fi" aria-hidden="true"></i>
          <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
          <label for="email">Email address</label>
        </div>
        <div id="email_error" class="error-message"></div>

        <!-- Password with left lock icon and right eye toggle -->
        <div class="form-floating mb-2 position-relative password-floating with-icon">
          <i class="bi bi-lock fi" aria-hidden="true"></i>
          <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
          <label for="password">Password</label>
          <i class="bi bi-eye toggle-password" id="togglePassword" aria-label="Show/Hide password" role="button" tabindex="0"></i>
        </div>
        <div id="password_error" class="error-message"></div>

        <div class="d-grid gap-2 mt-3">
          <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>

        <div class="text-center mt-3">
          <a id="forgotPasswordLink" href="{{ url('/forgot-password') }}" class="text-decoration-underline">Forgot password?</a>
        </div>

        <div class="text-center mt-3">
          <span class="text-muted">Don’t have an account?</span>
          <a id="registerLink" href="{{ route('register') }}" class="fw-semibold">Create one</a>
        </div>
      </form>

      <div id="loginAlert" class="mt-3"></div>
    </div>
  </div>

  <!-- Bootstrap Toast container (top-right) -->
  <div id="bsToasts" class="toast-container position-fixed top-0 end-0 p-3"></div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/assets-auth/js/auth-main.js') }}"></script>
</body>
</html>
