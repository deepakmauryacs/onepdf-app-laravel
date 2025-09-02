<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

  <style>
    body { background: linear-gradient(to bottom, #4facfe, #0a58ca); font-family: 'Inter', sans-serif; }
    .card-container { max-width: 500px; margin: 0 auto; border-radius: 0; box-shadow: 5px 5px 0 rgba(0,0,0,.2);
      background:#fff; padding:3rem; animation: fadeIn 1s ease-in-out; }
    .form-control,.btn { border-radius:0 !important; }
    .form-control:focus { box-shadow:0 0 0 .25rem rgba(13,110,253,.25); border-color:#0d6efd; }
    .btn-primary { background:#0d6efd; border-color:#0d6efd; transition: transform .2s, box-shadow .2s; }
    .btn-primary:hover { transform: translate(2px,2px); box-shadow:none; }
    .input-group-password { position:relative; }
    .toggle-password { position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; }
    #password-strength { margin-top:5px; display:flex; align-items:center; }
    .progress { height:8px; margin-right:10px; flex-grow:1; }
    .progress-bar { transition: width .3s ease; }
    .error-message { color:#dc3545; font-size:.875em; margin-top:.25rem; min-height:1rem; }
    @keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
  </style>

  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-container">
          <h3 class="text-center mb-4">Create Account 📦</h3>

          <form id="registerForm" novalidate>
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="text" name="first_name" class="form-control" id="firstName" placeholder="First Name" required>
                  <label for="firstName">First Name</label>
                </div>
                <div id="first_name_error" class="error-message"></div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-3">
                  <input type="text" name="last_name" class="form-control" id="lastName" placeholder="Last Name" required>
                  <label for="lastName">Last Name</label>
                </div>
                <div id="last_name_error" class="error-message"></div>
              </div>
            </div>

            <div class="form-floating mb-3">
              <input type="text" name="country" class="form-control" id="country" placeholder="Country" required>
              <label for="country">Country</label>
            </div>
            <div id="country_error" class="error-message"></div>

            <div class="form-floating mb-3">
              <input type="text" name="company" class="form-control" id="company" placeholder="Company" required>
              <label for="company">Company</label>
            </div>
            <div id="company_error" class="error-message"></div>

            <div class="form-floating mb-3">
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

            <div class="form-floating mb-3">
              <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
              <label for="email">Email address</label>
            </div>
            <div id="email_error" class="error-message"></div>

            <div class="mb-3">
              <label class="form-label" for="password">Password</label>
              <div class="input-group-password">
                <input type="password" name="password" class="form-control" id="password" required minlength="6">
                <i class="bi bi-eye toggle-password" id="togglePassword"></i>
              </div>
              <div id="password_error" class="error-message"></div>
              <div id="password-strength">
                <div class="progress" role="progressbar" aria-label="Password strength" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                  <div class="progress-bar" style="width: 0%"></div>
                </div>
                <span id="strength-text" class="text-muted"></span>
              </div>
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="agreed_terms" id="terms">
              <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-decoration-none">terms and conditions</a></label>
            </div>
            <div id="agreed_terms_error" class="error-message"></div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-lg">Register</button>
            </div>

            <div class="text-center mt-3">
              <span class="text-muted">Already have an account?</span>
              <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Log in</a>
            </div>
          </form>

          <div id="regAlert" class="mt-3"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script>
  document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;

    // reset errors
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-control, .form-select, .form-check-input').forEach(el => el.classList.remove('is-invalid'));

    const data = new FormData(form);

    // client-side checks (same as your original)
    let valid = true;
    const requireField = (name, inputId, msg) => {
      if (!data.get(name)) {
        document.getElementById(inputId + '_error').textContent = msg;
        document.getElementById(inputId === 'plan_id' ? 'plan' : (inputId === 'agreed_terms' ? 'terms' : (inputId === 'email' ? 'email' : inputId.replace('_error','')))).classList.add('is-invalid');
        valid = false;
      }
    };

    requireField('first_name','first_name','First name is required.');
    requireField('last_name','last_name','Last name is required.');
    requireField('country','country','Country is required.');
    requireField('company','company','Company is required.');
    requireField('plan_id','plan_id','Plan is required.');

    const email = data.get('email');
    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
      document.getElementById('email_error').textContent = 'A valid email is required.';
      document.getElementById('email').classList.add('is-invalid');
      valid = false;
    }
    const password = data.get('password');
    if (!password || password.length < 6) {
      document.getElementById('password_error').textContent = 'Password must be at least 6 characters.';
      document.getElementById('password').classList.add('is-invalid');
      valid = false;
    }
    if (!data.get('agreed_terms')) {
      document.getElementById('agreed_terms_error').textContent = 'You must agree to the terms.';
      document.getElementById('terms').classList.add('is-invalid');
      valid = false;
    }

    if (!valid) { toastr.error('Please correct the errors above.', 'Validation Error'); return; }

    try {
      const res = await fetch('{{ route('register.store') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: data
      });
      const result = await res.json();

      if (res.ok && result.success) {
        toastr.success('Registration successful. Redirecting to login...', 'Success');
        setTimeout(() => window.location = '{{ route('login') }}', 1500);
      } else {
        const msg = result.error || 'Registration failed.';
        toastr.error(msg, 'Error');
        if ((msg || '').toLowerCase().includes('email')) {
          toastr.info('Already have an account? Click to log in.', 'Login', {
            onclick: () => window.location = '{{ route('login') }}',
            timeOut: 4000
          });
        }
      }
    } catch (e) {
      toastr.error('Something went wrong. Please try again.', 'Error');
    }
  });

  // Password strength
  document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const bar = document.querySelector('#password-strength .progress-bar');
    const text = document.getElementById('strength-text');
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[!@#\$%\^&\*]/.test(password)) strength += 25;

    let barColor = '', label = '';
    if (!password.length) { strength = 0; label=''; }
    else if (strength < 50) { barColor = 'bg-danger'; label='Weak'; }
    else if (strength < 100) { barColor = 'bg-warning'; label='Medium'; }
    else { barColor='bg-success'; label='Strong'; }

    bar.style.width = strength + '%';
    bar.className = `progress-bar ${barColor}`;
    text.textContent = label;
  });

  // Toggle password
  (function(){
    const toggle = document.getElementById('togglePassword');
    const input = document.getElementById('password');
    toggle.addEventListener('click', function() {
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });
  })();
  </script>
</body>
</html>

