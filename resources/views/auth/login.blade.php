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

  <!-- Page CSS (Monochrome, black background) -->
  <style>
    :root{
      --ink:#0a0a0a; --muted:#6b7280; --line:#e6e7eb; --panel:#ffffff;
      --radius:14px; --shadow:0 6px 24px rgba(0,0,0,.20); --shadow-lg:0 16px 40px rgba(0,0,0,.35);
    }
    *{ font-family:'Inter',system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; }
    body{ background:#000; color:var(--ink); min-height:100vh; }

    .card-container{
      max-width:520px; margin:0 auto; background:var(--panel);
      border:1px solid var(--line); border-radius:var(--radius);
      box-shadow:var(--shadow-lg); padding:2.25rem; animation:fadeIn .6s ease;
    }

    h3{ font-weight:700; }
    a{ color:#111; text-decoration:underline; text-underline-offset:2px; }
    a:hover{ opacity:.85; }

    .form-label{ font-weight:600; }
    .form-control{ border-radius:12px; border:1px solid var(--line); }
    .form-control:focus{ border-color:#111; box-shadow:0 0 0 .2rem rgba(255,255,255,.08); }
    .is-invalid{ border-color:#dc2626 !important; }

    .btn-primary{
      background:#111; border-color:#111; font-weight:700; border-radius:12px;
      transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
    }
    .btn-primary:hover{ background:#000; border-color:#000; transform:translateY(-1px); box-shadow:var(--shadow); }

    /* LEFT icons inside floating controls */
    .with-icon{ position:relative; }
    .with-icon .fi{
      position:absolute; left:12px; top:50%; transform:translateY(-50%);
      color:#9ca3af; z-index:3; pointer-events:none; font-size:1.1rem;
    }
    /* make space for icon in floating inputs */
    .with-icon .form-control{ padding-left:2.6rem; }
    .with-icon label{ padding-left:2.6rem; color:var(--muted); }

    /* Password: left lock + right eye */
    .password-floating .form-control{ padding-right:2.25rem; }
    .password-floating .toggle-password{
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      cursor:pointer; color:#9ca3af; z-index:3;
    }
    .password-floating .toggle-password:hover{ color:#111; }

    .error-message{ color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1rem; }
    .toast-container{ z-index:1080; }
    .toast .toast-header .bi{ font-size:1rem; }

    .toplinks a{ color:#f5f5f5; text-decoration:none; font-weight:600; }
    .toplinks a .bi{ vertical-align:-2px; }
    .toplinks a:hover{ opacity:.85; text-decoration:underline; }

    @keyframes fadeIn{ from{opacity:0; transform:translateY(12px);} to{opacity:1; transform:none;} }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <!-- Back to website (top) -->
    <div class="toplinks mb-3 text-center">
      <a id="backHomeTop" href="#" title="Go to main website (Esc or Alt+H)">
        <i class="bi bi-arrow-left-circle me-1"></i> Back to Website
      </a>
    </div>

    <div class="card-container">
      <h3 class="text-center mb-3">Login</h3>
      <p class="text-center text-muted mb-4">Welcome back — enter your credentials to continue.</p>

      <form id="loginForm" novalidate>
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
          <a id="forgotPasswordLink" href="#" class="text-decoration-underline">Forgot password?</a>
        </div>

        <div class="text-center mt-3">
          <span class="text-muted">Don’t have an account?</span>
          <a id="registerLink" href="#" class="fw-semibold">Create one</a>
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

  <script>
    const BASE = "{{ url('/') }}";
    document.getElementById('registerLink').href = BASE + '/register';
    document.getElementById('forgotPasswordLink').href = BASE + '/forgot-password';
    document.getElementById('backHomeTop').href = BASE + '/';

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' || (e.altKey && (e.key.toLowerCase?.() === 'h'))) {
        window.location = BASE + '/';
      }
    });

    function showToast(message, title='Notice', variant='primary', delayMs=4000){
      const container = document.getElementById('bsToasts');
      const iconMap = { primary:'info-circle', success:'check-circle', danger:'exclamation-octagon',
                        warning:'exclamation-triangle', info:'info-circle', dark:'bell', light:'bell' };
      const icon = iconMap[variant] || 'info-circle';
      const el = document.createElement('div');
      el.className = `toast text-bg-${variant} border-0 shadow`;
      el.setAttribute('role','alert'); el.setAttribute('aria-live','assertive'); el.setAttribute('aria-atomic','true');
      el.innerHTML = `
        <div class="toast-header ${variant==='light'?'':'text-bg-'+variant}">
          <i class="bi bi-${icon} me-2"></i>
          <strong class="me-auto">${title}</strong>
          <small class="opacity-75">now</small>
          <button type="button" class="btn-close ${variant==='light'?'':'btn-close-white'} ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>`;
      container.appendChild(el);
      const t = new bootstrap.Toast(el, { autohide:true, delay:delayMs }); t.show();
      el.addEventListener('hidden.bs.toast', () => el.remove());
    }

    document.getElementById('loginForm').addEventListener('submit', async function(e){
      e.preventDefault();
      document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
      document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));

      const data = new FormData(e.target);
      let valid = true;

      const email = data.get('email');
      const password = data.get('password');

      if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        document.getElementById('email_error').textContent = 'A valid email is required.';
        document.getElementById('email').classList.add('is-invalid');
        valid = false;
      }
      if (!password) {
        document.getElementById('password_error').textContent = 'Password is required.';
        document.getElementById('password').classList.add('is-invalid');
        valid = false;
      }
      if (!valid) { showToast('Please correct the errors above.','Validation Error','danger'); return; }

      try{
        const res = await fetch(BASE + '/login', {
          method:'POST',
          headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
          body:data
        });
        let result={}; try{ result=await res.json(); }catch(_){}
        if(res.ok && result.success){
          showToast('Login successful. Redirecting...','Success','success');
          const redirect = result.redirect || (BASE + '/vendor/dashboard');
          setTimeout(()=> window.location = redirect, 1000);
        }else{
          showToast(result.error || 'Login failed.','Error','danger');
        }
      }catch(_){
        showToast('Something went wrong. Please try again.','Error','danger');
      }
    });

    // Show/Hide password
    (function(){
      const toggle=document.getElementById('togglePassword');
      const input=document.getElementById('password');
      function togglePw(){
        const type=input.getAttribute('type')==='password'?'text':'password';
        input.setAttribute('type', type);
        toggle.classList.toggle('bi-eye'); toggle.classList.toggle('bi-eye-slash');
      }
      toggle.addEventListener('click', togglePw);
      toggle.addEventListener('keypress', (e)=>{ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); togglePw(); }});
    })();
  </script>
</body>
</html>
