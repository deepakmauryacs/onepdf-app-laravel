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

  <!-- Page CSS -->
  <style>
    :root{
      --ink:#0a0a0a; --muted:#6b7280; --line:#e6e7eb; --panel:#ffffff;
      --radius:14px; --shadow-lg:0 16px 40px rgba(0,0,0,.35);
    }
    *{ font-family:'Inter',system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; }
    body{ background:#000; color:var(--ink); min-height:100vh; }

    .card-container{
      max-width:560px; margin:0 auto; background:var(--panel);
      border:1px solid var(--line); border-radius:var(--radius);
      box-shadow:var(--shadow-lg); padding:2.25rem; animation:fadeIn .6s ease;
    }
    h3{ font-weight:700; }
    a{ color:#111; text-decoration:underline; text-underline-offset:2px; }
    a:hover{ opacity:.85; }

    .form-control,.form-select{
      border-radius:12px; border:1px solid var(--line);
      padding:1rem 1rem; height:calc(3.5rem + 2px); transition:all .2s ease;
    }
    .form-control:focus,.form-select:focus{ border-color:#111; box-shadow:0 0 0 .2rem rgba(0,0,0,.08); }
    .is-invalid{ border-color:#dc2626 !important; }
    .error-message{ color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1rem; }

    /* Left icons inside floating fields */
    .with-icon{ position:relative; }
    .with-icon .fi{
      position:absolute; left:12px; top:50%; transform:translateY(-50%);
      color:#9ca3af; z-index:3; pointer-events:none; font-size:1.1rem;
    }
    .with-icon .form-control,
    .with-icon .form-select{ padding-left:2.6rem; }
    .with-icon > label{ padding-left:2.6rem; color:var(--muted); }

    /* Select arrow (native) */
    .form-select{
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%230a0a0a' viewBox='0 0 16 16'%3E%3Cpath d='M8 12L2 6h12L8 12z'/%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 1rem center; background-size:16px 12px;
      -webkit-appearance:none; -moz-appearance:none; appearance:none; padding-right:2.5rem; cursor:pointer;
    }
    .form-select option{ padding:12px; }
    .form-select option:checked{ background:#f5f5f5; font-weight:600; }
    .form-floating>.form-select{ padding-top:1.625rem; padding-bottom:.625rem; }
    .form-floating>.form-select~label{
      opacity:.65; transform:scale(.85) translateY(-.5rem) translateX(.15rem);
    }
    .form-floating>.form-select:focus~label,
    .form-floating>.form-select:not(:placeholder-shown)~label{ opacity:1; color:#111; font-weight:600; }

    /* Password field: keep right-eye space */
    .password-floating .form-control{ padding-right:2.25rem; }
    .password-floating .toggle-password{
      position:absolute; right:12px; top:50%; transform:translateY(-50%);
      cursor:pointer; color:#9ca3af; z-index:3;
    }
    .password-floating .toggle-password:hover{ color:#111; }

    .btn-primary{
      background:#111; border-color:#111; font-weight:700; border-radius:12px;
      transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
    }
    .btn-primary:hover{ background:#000; border-color:#000; transform:translateY(-1px); }

    .form-check-input{ border-radius:6px; }
    .form-check-input:focus{ box-shadow:0 0 0 .2rem rgba(17,17,17,.08); border-color:#111; }
    .form-check-input:checked{ background-color:#111; border-color:#111; }

    #password-strength{ margin-top:8px; display:flex; align-items:center; gap:.5rem; }
    .progress{ height:8px; background:#f1f1f1; flex:1; }
    .progress-bar{ background:#111 !important; }
    #strength-text{ color:#6b7280; min-width:64px; text-align:right; }

    .toast-container{ z-index:1080; }
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

            <div class="form-floating mb-1 with-icon">
              <i class="bi bi-globe fi"></i>
              <select name="country" class="form-select" id="country" required>
                <option value="" disabled selected>Select Country</option>
                @foreach($countries as $country)
                  <option value="{{ $country->name }}" data-iso="{{ $country->iso }}">{{ $country->name }}</option>
                @endforeach
              </select>
              <label for="country">Country</label>
            </div>
            <div id="country_error" class="error-message"></div>

            <div class="form-floating mb-1 with-icon">
              <i class="bi bi-building fi"></i>
              <input type="text" name="company" class="form-control" id="company" placeholder="Company" required>
              <label for="company">Company</label>
            </div>
            <div id="company_error" class="error-message"></div>

            <div class="form-floating mb-1 with-icon">
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
              <label for="plan">Plan</label>
            </div>
            <div id="plan_id_error" class="error-message"></div>

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
                <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
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

  <script>
    // Toast helper
    function showToast(message, title='Notice', variant='primary', delayMs=4000, onClick=null){
      const container=document.getElementById('bsToasts');
      const icons={primary:'info-circle',success:'check-circle',danger:'exclamation-octagon',warning:'exclamation-triangle',info:'info-circle',dark:'bell',light:'bell'};
      const icon=icons[variant]||'info-circle';
      const el=document.createElement('div');
      el.className=`toast text-bg-${variant} border-0 shadow`;
      el.setAttribute('role','alert'); el.setAttribute('aria-live','assertive'); el.setAttribute('aria-atomic','true');
      el.innerHTML=`
        <div class="toast-header ${variant==='light'?'':'text-bg-'+variant}">
          <i class="bi bi-${icon} me-2"></i>
          <strong class="me-auto">${title}</strong>
          <small class="opacity-75">now</small>
          <button type="button" class="btn-close ${variant==='light'?'':'btn-close-white'} ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">${message}</div>`;
      if(typeof onClick==='function'){
        el.style.cursor='pointer';
        el.addEventListener('click',e=>{ if(!e.target.classList.contains('btn-close')) onClick(); });
      }
      container.appendChild(el);
      const t=new bootstrap.Toast(el,{autohide:true,delay:delayMs}); t.show();
      el.addEventListener('hidden.bs.toast',()=>el.remove());
    }

    // Validation helpers
    function setErr(inputId, errorId, msg){
      const i=document.getElementById(inputId), e=document.getElementById(errorId);
      if(i) i.classList.add('is-invalid');
      if(e) e.textContent = msg || '';
    }
    function clearAllErrors(){
      document.querySelectorAll('.error-message').forEach(el=>el.textContent='');
      document.querySelectorAll('.form-control, .form-select, .form-check-input').forEach(el=>el.classList.remove('is-invalid'));
    }

    const countrySelect=document.getElementById('country');
    const planSelect=document.getElementById('plan');

    // Dynamic INR/USD plan labels
    function updatePlanOptions(){
      if(!planSelect) return;

      const hasCountry=countrySelect && countrySelect.value.trim()!=='';
      planSelect.disabled=!hasCountry;
      planSelect.setAttribute('aria-disabled', planSelect.disabled ? 'true' : 'false');
      if(!hasCountry){
        planSelect.value='';
        planSelect.classList.remove('is-invalid');
        const planError=document.getElementById('plan_id_error');
        if(planError) planError.textContent='';
      }

      let iso='';
      let countryValue='';
      if(hasCountry && countrySelect){
        const selectedOption=countrySelect.options[countrySelect.selectedIndex];
        if(selectedOption && selectedOption.dataset && selectedOption.dataset.iso){
          iso=selectedOption.dataset.iso.toUpperCase();
        }
        countryValue=countrySelect.value.trim().toLowerCase();
      }
      const isIndia = iso==='IN' || countryValue.includes('india');
      const locale = isIndia ? 'en-IN' : 'en-US';
      const currency = isIndia ? 'INR' : 'USD';

      planSelect.querySelectorAll('option[data-plan]').forEach(function(option){
        const name=option.dataset.name || option.textContent;
        const billing=option.dataset.billing || '';
        const priceValue=isIndia ? option.dataset.inrPrice : option.dataset.usdPrice;
        const priceNumber=parseFloat(priceValue);
        let suffix=''; if(billing==='month') suffix='/month'; else if(billing==='year') suffix='/year';
        if(isFinite(priceNumber) && priceNumber>0){
          const hasCents=Math.abs(priceNumber-Math.round(priceNumber))>0.001;
          const digits=hasCents?2:0;
          const formatted=priceNumber.toLocaleString(locale,{style:'currency',currency,minimumFractionDigits:digits,maximumFractionDigits:digits});
          option.textContent=`${name} - ${formatted}${suffix}`;
        }else{
          option.textContent=`${name} - Free`;
        }
      });
    }
    if(countrySelect && planSelect){
      countrySelect.addEventListener('change', updatePlanOptions);
      document.addEventListener('DOMContentLoaded', updatePlanOptions);
      updatePlanOptions();
    }

    // Captcha refresh
    const refreshBtn=document.getElementById('refreshCaptcha');
    if(refreshBtn){
      refreshBtn.addEventListener('click', async ()=>{
        try{
          const res=await fetch(@json(route('register.captcha')),{
            method:'POST',
            headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
          });
          const json=await res.json();
          if(json.captcha_a && json.captcha_b){
            const label=document.getElementById('captcha_label');
            if(label) label.textContent=`What is ${json.captcha_a} + ${json.captcha_b}?`;
            const input=document.getElementById('captcha'); if(input) input.value='';
          }
        }catch(_){}
      });
    }

    // Submit
    document.getElementById('registerForm').addEventListener('submit', async function(e){
      e.preventDefault();
      clearAllErrors();

      const data=new FormData(e.target);
      let valid=true;

      if(!data.get('first_name')){ setErr('firstName','first_name_error','First name is required.'); valid=false; }
      if(!data.get('last_name')) { setErr('lastName','last_name_error','Last name is required.'); valid=false; }
      if(!data.get('country'))   { setErr('country','country_error','Country is required.'); valid=false; }
      if(!data.get('company'))   { setErr('company','company_error','Company is required.'); valid=false; }
      const isPlanDisabled = planSelect ? planSelect.disabled : false;
      if(!isPlanDisabled && !data.get('plan_id'))   { setErr('plan','plan_id_error','Plan is required.'); valid=false; }

      const email=data.get('email');
      if(!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)){
        setErr('email','email_error','A valid email is required.'); valid=false;
      }

      const password=data.get('password');
      if(!password || password.length<6){
        setErr('password','password_error','Password must be at least 6 characters.'); valid=false;
      }

      if(!data.get('agreed_terms')){ setErr('terms','agreed_terms_error','You must agree to the terms.'); valid=false; }
      if(!data.get('captcha'))     { setErr('captcha','captcha_error','Captcha is required.'); valid=false; }

      if(!valid){ showToast('Please correct the errors above.','Validation Error','danger'); return; }

      try{
        const res=await fetch(@json(route('register.store')),{ method:'POST',
          headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),'Accept':'application/json'},
          body:data
        });
        let result={}; try{ result=await res.json(); }catch(_){}

        if(res.status===422){
          const map={first_name:'firstName',last_name:'lastName',country:'country',company:'company',plan_id:'plan',email:'email',password:'password',agreed_terms:'terms',captcha:'captcha'};
          Object.entries(result.errors||{}).forEach(([k,v])=>{
            const id=map[k]||k; setErr(id,`${k}_error`,Array.isArray(v)?v[0]:v);
          });
          showToast('Please correct the errors above.','Validation Error','danger'); return;
        }

        if(res.ok && result.success){
          showToast('Registration successful. Redirecting to login...','Success','success');
          setTimeout(()=> window.location=@json(route('login')),1200);
        }else{
          const msg=result.error||'Registration failed.'; showToast(msg,'Error','danger');
          if((msg||'').toLowerCase().includes('email')){
            showToast('Already have an account? Click here to log in.','Login','info',4000,()=> window.location=@json(route('login')));
          }
        }
      }catch(_){
        showToast('Something went wrong. Please try again.','Error','danger');
      }
    });

    // Password strength + toggle
    document.getElementById('password').addEventListener('input', function(){
      const pw=this.value, bar=document.querySelector('#password-strength .progress-bar'), text=document.getElementById('strength-text');
      let s=0; if(pw.length>=8) s+=25; if(/[A-Z]/.test(pw)) s+=25; if(/[0-9]/.test(pw)) s+=25; if(/[!@#\$%\^&\*]/.test(pw)) s+=25;
      bar.style.width=s+'%'; text.textContent=!pw?'':(s<50?'Weak':(s<100?'Medium':'Strong'));
    });
    (function(){
      const toggle=document.getElementById('togglePassword'), input=document.getElementById('password');
      function togglePw(){ input.type = (input.type==='password') ? 'text' : 'password'; toggle.classList.toggle('bi-eye'); toggle.classList.toggle('bi-eye-slash'); }
      toggle.addEventListener('click',togglePw);
      toggle.addEventListener('keypress',e=>{ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); togglePw(); }});
    })();
  </script>
</body>
</html>
