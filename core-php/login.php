<?php require_once 'config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
  <link rel="manifest" href="assets/favicon_io/site.webmanifest">
  <link rel="shortcut icon" href="assets/favicon_io/favicon.ico">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>

  <style>
    body {
      background: linear-gradient(to bottom, #4facfe, #0a58ca);
      font-family: 'Inter', sans-serif;
    }
    .card-container {
      max-width: 500px;
      margin: 0 auto;
      border-radius: 0;
      box-shadow: 5px 5px 0px 0px rgba(0,0,0,0.2);
      overflow: hidden;
      background-color: #fff;
      padding: 3rem;
      animation: fadeIn 1s ease-in-out;
    }
    .form-control, .btn { border-radius: 0 !important; }
    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
      border-color: #0d6efd;
    }
    .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-primary:hover { transform: translate(2px, 2px); box-shadow: none; }

    .input-group-password { position: relative; }
    .toggle-password {
      position: absolute; right: 15px; top: 50%;
      transform: translateY(-50%); cursor: pointer;
    }

    /* Error lines always visible (space reserved) */
    .error-message { color:#dc3545; font-size:0.875em; margin-top:0.25rem; min-height:1rem; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="d-flex align-items-center min-vh-100">
  <div class="container py-5">
    <div class="card-container">
      <h3 class="text-center mb-4">Login 📦</h3>

      <form id="loginForm" novalidate>
        <div class="form-floating mb-3">
          <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
          <label for="email">Email address</label>
        </div>
        <div id="email_error" class="error-message"></div>
       
        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <div class="input-group-password">
            <input type="password" name="password" class="form-control" id="password" required>
            <i class="bi bi-eye toggle-password" id="togglePassword"></i>
          </div>
        </div>
        <div id="password_error" class="error-message"></div>
       
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </div>

        <!-- NEW: No account? Register -->
        <div class="text-center mt-3">
          <span class="text-muted">Don’t have an account?</span>
          <a href="registration" class="fw-semibold text-decoration-none">Create one</a>
        </div>
      </form>

      <div id="loginAlert" class="mt-3"></div>
    </div>
  </div>
 
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
 
  <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e){
      e.preventDefault();
      const form = e.target;
      let valid = true;
     
      // Clear previous errors and invalid classes
      document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
      document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
     
      const data = new FormData(form);
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
     
      if (!valid) {
        toastr.error('Please correct the errors above.', 'Validation Error');
        return;
      }
     
      const res = await fetch('vendor_dashboard/api/_login_user.php', {
        method: 'POST',
        body: data
      });
      const result = await res.json();
     
      if (result.success) {
        toastr.success('Login successful. Redirecting to dashboard...', 'Success');
        setTimeout(() => window.location = 'vendor_dashboard/dashboard', 1500);
      } else {
        toastr.error(result.error || 'Login failed.', 'Error');
      }
    });
   
    // Password show/hide logic
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });
  </script>
</body>
</html>
