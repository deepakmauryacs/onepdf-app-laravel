<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Favicons (optional) --}}
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

  <!-- Page CSS (Monochrome, black background) -->
  <style>
    :root{
      --ink:#0a0a0a;
      --muted:#6b7280;
      --line:#e6e7eb;
      --panel:#ffffff;
      --radius:14px;
      --shadow:0 6px 24px rgba(0,0,0,.20);
      --shadow-lg:0 16px 40px rgba(0,0,0,.35);
    }
    *{ font-family:'Inter',system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; }
    body{
      background:#000;
      color:var(--ink);
      min-height:100vh;
    }
    .card-container{
      max-width: 520px;
      margin: 0 auto;
      background:var(--panel);
      border:1px solid var(--line);
      border-radius:var(--radius);
      box-shadow:var(--shadow-lg);
      padding:2.25rem;
      animation:fadeIn .6s ease;
    }
    h3{ font-weight:700; }
    a{ color:#111; text-decoration:underline; text-underline-offset:2px; }
    a:hover{ opacity:.85; }

    /* Inputs & buttons */
    .form-label{ font-weight:600; }
    .form-control{
      border-radius:12px;
      border:1px solid var(--line);
    }
    .form-control:focus{
      border-color:#111;
      box-shadow:0 0 0 .2rem rgba(255,255,255,.08);
    }
    .is-invalid{ border-color:#dc2626 !important; }

    .btn-primary{
      background:#111; border-color:#111; font-weight:700; border-radius:12px;
      transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
    }
    .btn-primary:hover{ background:#000; border-color:#000; transform:translateY(-1px); box-shadow:var(--shadow); }

    /* Errors */
    .error-message{ color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1rem; }

    /* Toast container */
    .toast-container{ z-index:1080; }
    .toast .toast-header .bi{ font-size:1rem; }

    /* Top link area */
    .toplinks a{
      color:#f5f5f5; text-decoration:none; font-weight:600;
    }
    .toplinks a .bi{ vertical-align:-2px; }
    .toplinks a:hover{ opacity:.85; text-decoration:underline; }

    @keyframes fadeIn{ from{opacity:0; transform:translateY(12px);} to{opacity:1; transform:none;} }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container py-5">

    <div class="card-container">
      <h3 class="text-center mb-3">Forgot your password?</h3>
      <p class="text-center text-muted mb-4">Enter your email and we'll send you a reset link.</p>

      {{-- Fallback for no-JS; toasts will also show this --}}
      @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
      @endif

      <form id="forgotForm" method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <!-- Floating email -->
        <div class="form-floating mb-2">
          <input
            id="email"
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            name="email"
            value="{{ old('email') }}"
            placeholder="name@example.com"
            required autofocus>
          <label for="email">Email address</label>
        </div>
        <div id="email_error" class="error-message">
          @error('email') {{ $message }} @enderror
        </div>

        <div class="d-grid gap-2 mt-3">
          <button type="submit" class="btn btn-primary btn-lg">Send Reset Link</button>
        </div>

        <div class="text-center mt-3">
          <a id="backLogin" href="#" class="fw-semibold">← Back to login</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap Toast container (top-right) -->
  <div id="bsToasts" class="toast-container position-fixed top-0 end-0 p-3"></div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Centralize your base URL here (same as Login)
    const BASE = "{{ url('/') }}";

    // Wire up nav links
    document.getElementById('backLogin').href = BASE + '/login';
    document.getElementById('backHomeTop').href = BASE + '/';
    document.getElementById('backHomeBottom').href = BASE + '/';

    // Keyboard shortcuts: Esc or Alt+H => Home
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' || (e.altKey && (e.key.toLowerCase?.() === 'h'))) {
        window.location = BASE + '/';
      }
    });

    /** Bootstrap Toast helper (same as Login) */
    function showToast(message, title = 'Notice', variant = 'primary', delayMs = 4000){
      const container = document.getElementById('bsToasts');
      const iconMap = { primary:'info-circle', success:'check-circle', danger:'exclamation-octagon',
                        warning:'exclamation-triangle', info:'info-circle', dark:'bell', light:'bell' };
      const icon = iconMap[variant] || 'info-circle';

      const toastEl = document.createElement('div');
      toastEl.className = `toast text-bg-${variant} border-0 shadow`;
      toastEl.setAttribute('role', 'alert'); toastEl.setAttribute('aria-live', 'assertive'); toastEl.setAttribute('aria-atomic', 'true');
      toastEl.innerHTML = `
        <div class="toast-header ${variant === 'light' ? '' : 'text-bg-'+variant}">
          <i class="bi bi-${icon} me-2"></i>
          <strong class="me-auto">${title}</strong>
          <small class="opacity-75">now</small>
          <button type="button" class="btn-close ${variant==='light' ? '' : 'btn-close-white'} ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>`;
      container.appendChild(toastEl);
      const toast = new bootstrap.Toast(toastEl, { autohide:true, delay:delayMs });
      toast.show();
      toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    // Server feedback -> toast
    const serverStatus = @json(session('status'));
    const serverErrors = @json($errors->all());
    if (serverStatus) showToast(serverStatus, 'Success', 'success');
    if (serverErrors && serverErrors.length) showToast('Please correct the highlighted errors.', 'Validation Error', 'danger', 5000);

    // Client-side validation (email format)
    document.getElementById('forgotForm').addEventListener('submit', function(e){
      const emailEl = document.getElementById('email');
      document.getElementById('email_error').textContent = '';
      emailEl.classList.remove('is-invalid');

      if (!emailEl.value || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(emailEl.value)){
        e.preventDefault();
        document.getElementById('email_error').textContent = 'A valid email is required.';
        emailEl.classList.add('is-invalid');
        showToast('Please correct the errors above.', 'Validation Error', 'danger');
      }
    });
  </script>
</body>
</html>
