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
      background:#000; /* pure black */
      color:var(--ink);
      min-height:100vh;
    }

    .card-container{
      max-width: 560px;
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
    .form-control, .form-select{
      border-radius:12px;
      border:1px solid var(--line);
    }
    .form-control:focus, .form-select:focus{
      border-color:#111;
      box-shadow:0 0 0 .2rem rgba(255,255,255,.08);
    }
    .is-invalid{ border-color:#dc2626 !important; }

    .btn-primary{
      background:#111; border-color:#111; font-weight:700; border-radius:12px;
      transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
    }
    .btn-primary:hover{ background:#000; border-color:#000; transform:translateY(-1px); box-shadow:var(--shadow); }

    .form-check-input{ border-radius:6px; }
    .form-check-input:focus{ box-shadow:0 0 0 .2rem rgba(255,255,255,.08); border-color:#111; }
    .form-check-input:checked{ background-color:#111; border-color:#111; }

    /* Floating password like other floating fields */
    .password-floating .form-control{
      padding-right:2.25rem; /* space for the eye icon */
    }
    .password-floating .toggle-password{
      position:absolute;
      right:12px;
      top:50%;
      transform:translateY(-50%);
      cursor:pointer;
      color:#9ca3af;
      z-index:3;
    }
    .password-floating .toggle-password:hover{ color:#111; }

    /* Errors */
    .error-message{ color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1rem; }

    /* Password strength (monochrome) */
    #password-strength{ margin-top:8px; display:flex; align-items:center; gap:.5rem; }
    .progress{ height:8px; background:#f1f1f1; flex:1; }
    .progress-bar{ background:#111 !important; } /* monochrome bar */
    #strength-text{ color:#6b7280; min-width:64px; text-align:right; }

    /* Bootstrap toast container */
    .toast-container{ z-index:1080; }
    .toast .toast-header .bi{ font-size:1rem; }

    @keyframes fadeIn{ from{opacity:0; transform:translateY(12px);} to{opacity:1; transform:none;} }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card card-container">
          <h3 class="text-center mb-1">Create Account</h3>
          <p class="text-center text-muted mb-4">Join OneLinkPDF in minutes.</p>

          <form id="registerForm" novalidate>
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-floating mb-1">
                  <input type="text" name="first_name" class="form-control" id="firstName" placeholder="First Name" required>
                  <label for="firstName">First Name</label>
                </div>
                <div id="first_name_error" class="error-message"></div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-1">
                  <input type="text" name="last_name" class="form-control" id="lastName" placeholder="Last Name" required>
                  <label for="lastName">Last Name</label>
                </div>
                <div id="last_name_error" class="error-message"></div>
              </div>
            </div>

            <div class="form-floating mb-1">
              <select name="country" class="form-select" id="country" required>
                <option value="" disabled selected>Select Country</option>
                @foreach($countries as $country)
                  <option value="{{ $country->name }}">{{ $country->name }}</option>
                @endforeach
              </select>
              <label for="country">Country</label>
            </div>
            <div id="country_error" class="error-message"></div>

            <div class="form-floating mb-1">
              <input type="text" name="company" class="form-control" id="company" placeholder="Company" required>
              <label for="company">Company</label>
            </div>
            <div id="company_error" class="error-message"></div>

            <div class="form-floating mb-1">
              <select name="plan_id" class="form-select" id="plan" required>
                <option value="" selected>Select Plan</option>
                <option value="1">Free - $0</option>
                <option value="2">Pro - $12/month</option>
                <option value="3">Pro - $499/year</option>
                <option value="4">Business - $25/month</option>
                <option value="5">Business - $1999/year</option>
              </select>
              <label for="plan">Plan</label>
            </div>
            <div id="plan_id_error" class="error-message"></div>

            <div class="form-floating mb-1">
              <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
              <label for="email">Email address</label>
            </div>
            <div id="email_error" class="error-message"></div>

            <!-- UPDATED PASSWORD FIELD (floating like First Name) -->
            <div class="form-floating mb-1 position-relative password-floating">
              <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6">
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
              <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-decoration-underline">terms and conditions</a></label>
            </div>
            <div id="agreed_terms_error" class="error-message"></div>

            <div class="mb-3">
              <label for="captcha" id="captcha_label" class="form-label">What is {{ $captcha_a }} + {{ $captcha_b }}?</label>
              <div class="input-group">
                <input type="text" class="form-control" id="captcha" name="captcha">
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

          <div id="regAlert" class="mt-3"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Toast container (top-right) -->
  <div id="bsToasts" class="toast-container position-fixed top-0 end-0 p-3"></div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /** Bootstrap Toast helper
     *  showToast(message, title='Notice', variant='primary', delayMs=4000, onClick=null)
     *  variants: primary | success | danger | warning | info | dark | light
     */
    function showToast(message, title = 'Notice', variant = 'primary', delayMs = 4000, onClick = null){
      const container = document.getElementById('bsToasts');

      const iconMap = {
        primary: 'info-circle',
        success: 'check-circle',
        danger: 'exclamation-octagon',
        warning: 'exclamation-triangle',
        info: 'info-circle',
        dark: 'bell',
        light: 'bell'
      };
      const icon = iconMap[variant] || 'info-circle';

      const el = document.createElement('div');
      el.className = `toast text-bg-${variant} border-0 shadow`;
      el.setAttribute('role', 'alert');
      el.setAttribute('aria-live', 'assertive');
      el.setAttribute('aria-atomic', 'true');

      el.innerHTML = `
        <div class="toast-header ${variant === 'light' ? '' : 'text-bg-'+variant}">
          <i class="bi bi-${icon} me-2"></i>
          <strong class="me-auto">${title}</strong>
          <small class="opacity-75">now</small>
          <button type="button" class="btn-close ${variant==='light' ? '' : 'btn-close-white'} ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>
      `;

      if (typeof onClick === 'function') {
        el.style.cursor = 'pointer';
        el.addEventListener('click', (e) => {
          // Don't trigger on close button click
          if (!e.target.classList.contains('btn-close')) onClick();
        });
      }

      container.appendChild(el);
      const toast = new bootstrap.Toast(el, { autohide: true, delay: delayMs });
      toast.show();
      el.addEventListener('hidden.bs.toast', () => el.remove());
    }

    // Validation helpers
    function setErr(inputId, errorId, msg){
      const i = document.getElementById(inputId);
      const e = document.getElementById(errorId);
      if(i) i.classList.add('is-invalid');
      if(e) e.textContent = msg || '';
    }
    function clearAllErrors(){
      document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
      document.querySelectorAll('.form-control, .form-select, .form-check-input').forEach(el => el.classList.remove('is-invalid'));
    }

    const refreshBtn = document.getElementById('refreshCaptcha');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', async function(){
        try {
          const res = await fetch(@json(route('register.captcha')), {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
          });
          const json = await res.json();
          if (json.captcha_a && json.captcha_b) {
            const label = document.getElementById('captcha_label');
            if (label) label.textContent = `What is ${json.captcha_a} + ${json.captcha_b}?`;
            const input = document.getElementById('captcha');
            if (input) input.value = '';
          }
        } catch(err){}
      });
    }

    // Submit
    document.getElementById('registerForm').addEventListener('submit', async function(e){
      e.preventDefault();
      const form = e.target;
      clearAllErrors();

      const data = new FormData(form);
      let valid = true;

      if(!data.get('first_name')) { setErr('firstName','first_name_error','First name is required.'); valid = false; }
      if(!data.get('last_name'))  { setErr('lastName','last_name_error','Last name is required.'); valid = false; }
      if(!data.get('country'))    { setErr('country','country_error','Country is required.'); valid = false; }
      if(!data.get('company'))    { setErr('company','company_error','Company is required.'); valid = false; }
      if(!data.get('plan_id'))    { setErr('plan','plan_id_error','Plan is required.'); valid = false; }

      const email = data.get('email');
      if(!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)){
        setErr('email','email_error','A valid email is required.'); valid = false;
      }

      const password = data.get('password');
      if(!password || password.length < 6){
        setErr('password','password_error','Password must be at least 6 characters.'); valid = false;
      }

      if(!data.get('agreed_terms')){
        setErr('terms','agreed_terms_error','You must agree to the terms.'); valid = false;
      }
      if(!data.get('captcha')) { setErr('captcha','captcha_error','Captcha is required.'); valid = false; }

      if(!valid){
        showToast('Please correct the errors above.','Validation Error','danger');
        return;
      }

      try{
        const res = await fetch(@json(route('register.store')), {
          method:'POST',
          headers:{
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
          },
          body:data
        });
        let result = {};
        try{ result = await res.json(); } catch(_){ }

        if (res.status === 422) {
          const map = {
            first_name: 'firstName',
            last_name: 'lastName',
            country: 'country',
            company: 'company',
            plan_id: 'plan',
            email: 'email',
            password: 'password',
            agreed_terms: 'terms',
            captcha: 'captcha'
          };
          Object.entries(result.errors || {}).forEach(([k,v]) => {
            const inputId = map[k] || k;
            setErr(inputId, `${k}_error`, Array.isArray(v) ? v[0] : v);
          });
          showToast('Please correct the errors above.','Validation Error','danger');
          return;
        }

        if(res.ok && result.success){
          showToast('Registration successful. Redirecting to login...','Success','success');
          setTimeout(()=> window.location = @json(route('login')), 1200);
        }else{
          const msg = result.error || 'Registration failed.';
          showToast(msg,'Error','danger');

          if((msg || '').toLowerCase().includes('email')){
            showToast('Already have an account? Click here to log in.','Login','info', 4000,
              () => window.location = @json(route('login'))
            );
          }
        }
      }catch(err){
        showToast('Something went wrong. Please try again.','Error','danger');
      }
    });

    // Password strength (monochrome bar; width only)
    document.getElementById('password').addEventListener('input', function(){
      const pw = this.value;
      const bar = document.querySelector('#password-strength .progress-bar');
      const text = document.getElementById('strength-text');

      let strength = 0;
      if(pw.length >= 8) strength += 25;
      if(/[A-Z]/.test(pw)) strength += 25;
      if(/[0-9]/.test(pw)) strength += 25;
      if(/[!@#\$%\^&\*]/.test(pw)) strength += 25;

      let label = '';
      if(!pw.length){ strength = 0; label = ''; }
      else if(strength < 50){ label = 'Weak'; }
      else if(strength < 100){ label = 'Medium'; }
      else { label = 'Strong'; }

      bar.style.width = strength + '%';
      text.textContent = label;
    });

    // Toggle password
    (function(){
      const toggle = document.getElementById('togglePassword');
      const input = document.getElementById('password');
      function togglePw(){
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        toggle.classList.toggle('bi-eye');
        toggle.classList.toggle('bi-eye-slash');
      }
      toggle.addEventListener('click', togglePw);
      toggle.addEventListener('keypress', (e)=>{ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); togglePw(); }});
    })();
  </script>
</body>
</html>
