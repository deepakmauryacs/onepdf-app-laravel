(function () {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const toastIcons = {
    primary: 'info-circle',
    success: 'check-circle',
    danger: 'exclamation-octagon',
    warning: 'exclamation-triangle',
    info: 'info-circle',
    dark: 'bell',
    light: 'bell'
  };

  function showToast(message, options = {}) {
    const {
      title = 'Notice',
      variant = 'primary',
      delayMs = 4000,
      onClick = null
    } = options;

    const container = document.getElementById('bsToasts');
    if (!container || typeof bootstrap === 'undefined' || !bootstrap.Toast) {
      return;
    }

    const icon = toastIcons[variant] || toastIcons.primary;
    const toast = document.createElement('div');
    toast.className = `toast text-bg-${variant} border-0 shadow`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    const headerClass = variant === 'light' ? '' : `text-bg-${variant}`;
    toast.innerHTML = `
      <div class="toast-header ${headerClass}">
        <i class="bi bi-${icon} me-2"></i>
        <strong class="me-auto">${title}</strong>
        <small class="opacity-75">now</small>
        <button type="button" class="btn-close ${variant === 'light' ? '' : 'btn-close-white'} ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">${message}</div>
    `;

    if (typeof onClick === 'function') {
      toast.style.cursor = 'pointer';
      toast.addEventListener('click', (event) => {
        if (!event.target.classList.contains('btn-close')) {
          onClick();
        }
      });
    }

    container.appendChild(toast);
    const instance = new bootstrap.Toast(toast, { autohide: true, delay: delayMs });
    instance.show();
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
  }

  function setupPasswordToggles() {
    document.querySelectorAll('.password-floating').forEach((group) => {
      const toggle = group.querySelector('.toggle-password');
      const input = group.querySelector('input');
      if (!toggle || !input) {
        return;
      }

      const togglePassword = () => {
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        toggle.classList.toggle('bi-eye');
        toggle.classList.toggle('bi-eye-slash');
      };

      toggle.addEventListener('click', togglePassword);
      toggle.addEventListener('keypress', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          togglePassword();
        }
      });
    });
  }

  function initLoginForm() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) {
      return;
    }

    const emailError = document.getElementById('email_error');
    const passwordError = document.getElementById('password_error');
    const redirectUrl = loginForm.dataset.redirect || '';
    const homeUrl = document.body?.dataset?.homeUrl;

    if (homeUrl) {
      document.addEventListener('keydown', (event) => {
        const key = event.key || '';
        if (key === 'Escape' || (event.altKey && key.toLowerCase() === 'h')) {
          window.location = homeUrl;
        }
      });
    }

    loginForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      [emailError, passwordError].forEach((el) => {
        if (el) {
          el.textContent = '';
        }
      });
      loginForm.querySelectorAll('.form-control').forEach((input) => input.classList.remove('is-invalid'));

      const emailInput = loginForm.querySelector('input[name="email"]');
      const passwordInput = loginForm.querySelector('input[name="password"]');

      let valid = true;
      const email = emailInput?.value.trim();
      const password = passwordInput?.value || '';

      if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        if (emailError) {
          emailError.textContent = 'A valid email is required.';
        }
        if (emailInput) {
          emailInput.classList.add('is-invalid');
        }
        valid = false;
      }

      if (!password) {
        if (passwordError) {
          passwordError.textContent = 'Password is required.';
        }
        if (passwordInput) {
          passwordInput.classList.add('is-invalid');
        }
        valid = false;
      }

      if (!valid) {
        showToast('Please correct the errors above.', {
          title: 'Validation Error',
          variant: 'danger'
        });
        return;
      }

      const actionUrl = loginForm.getAttribute('action') || loginForm.dataset.action;
      if (!actionUrl) {
        return;
      }

      const formData = new FormData(loginForm);

      try {
        const response = await fetch(actionUrl, {
          method: 'POST',
          headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
          body: formData
        });

        let result = {};
        try {
          result = await response.json();
        } catch (error) {
          result = {};
        }

        if (response.ok && result.success) {
          showToast('Login successful. Redirecting...', {
            title: 'Success',
            variant: 'success'
          });

          const target = result.redirect || redirectUrl;
          if (target) {
            setTimeout(() => {
              window.location = target;
            }, 1000);
          }
        } else {
          showToast(result.error || 'Login failed.', {
            title: 'Error',
            variant: 'danger'
          });
        }
      } catch (error) {
        showToast('Something went wrong. Please try again.', {
          title: 'Error',
          variant: 'danger'
        });
      }
    });
  }

  function initRegisterForm() {
    const registerForm = document.getElementById('registerForm');
    if (!registerForm) {
      return;
    }

    const storeUrl = registerForm.dataset.storeUrl;
    const captchaUrl = registerForm.dataset.captchaUrl;
    const loginUrl = registerForm.dataset.loginUrl;

    const countrySelect = document.getElementById('country');
    const planSelect = document.getElementById('plan');

    const setError = (inputId, errorId, message) => {
      const input = document.getElementById(inputId);
      const error = document.getElementById(errorId);
      if (input) {
        input.classList.add('is-invalid');
      }
      if (error) {
        error.textContent = message || '';
      }
    };

    const clearErrors = () => {
      registerForm.querySelectorAll('.error-message').forEach((el) => {
        el.textContent = '';
      });
      registerForm.querySelectorAll('.form-control, .form-select, .form-check-input').forEach((el) => {
        el.classList.remove('is-invalid');
      });
    };

    const updatePlanOptions = () => {
      if (!planSelect) {
        return;
      }

      const hasCountry = countrySelect && countrySelect.value.trim() !== '';
      planSelect.disabled = !hasCountry;
      planSelect.setAttribute('aria-disabled', planSelect.disabled ? 'true' : 'false');

      if (!hasCountry) {
        planSelect.value = '';
        planSelect.classList.remove('is-invalid');
        const planError = document.getElementById('plan_id_error');
        if (planError) {
          planError.textContent = '';
        }
      }

      let iso = '';
      let countryValue = '';
      if (hasCountry && countrySelect) {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        if (selectedOption && selectedOption.dataset && selectedOption.dataset.iso) {
          iso = selectedOption.dataset.iso.toUpperCase();
        }
        countryValue = countrySelect.value.trim().toLowerCase();
      }

      const isIndia = iso === 'IN' || countryValue.includes('india');
      const locale = isIndia ? 'en-IN' : 'en-US';
      const currency = isIndia ? 'INR' : 'USD';

      planSelect.querySelectorAll('option[data-plan]').forEach((option) => {
        const name = option.dataset.name || option.textContent;
        const billing = option.dataset.billing || '';
        const priceValue = isIndia ? option.dataset.inrPrice : option.dataset.usdPrice;
        const priceNumber = parseFloat(priceValue);

        let suffix = '';
        if (billing === 'month') {
          suffix = '/month';
        } else if (billing === 'year') {
          suffix = '/year';
        }

        if (Number.isFinite(priceNumber) && priceNumber > 0) {
          const hasCents = Math.abs(priceNumber - Math.round(priceNumber)) > 0.001;
          const digits = hasCents ? 2 : 0;
          const formatter = new Intl.NumberFormat(locale, {
            style: 'currency',
            currency,
            minimumFractionDigits: digits,
            maximumFractionDigits: digits
          });
          option.textContent = `${name} - ${formatter.format(priceNumber)}${suffix}`;
        } else {
          option.textContent = `${name} - Free`;
        }
      });
    };

    if (countrySelect) {
      countrySelect.addEventListener('change', updatePlanOptions);
    }
    updatePlanOptions();

    const refreshButton = document.getElementById('refreshCaptcha');
    if (refreshButton && captchaUrl) {
      refreshButton.addEventListener('click', async () => {
        try {
          const response = await fetch(captchaUrl, {
            method: 'POST',
            headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}
          });
          const json = await response.json();
          if (json && json.captcha_a && json.captcha_b) {
            const label = document.getElementById('captcha_label');
            if (label) {
              label.textContent = `What is ${json.captcha_a} + ${json.captcha_b}?`;
            }
            const input = document.getElementById('captcha');
            if (input) {
              input.value = '';
            }
          }
        } catch (error) {
          // ignore refresh failures
        }
      });
    }

    registerForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      clearErrors();

      const data = new FormData(registerForm);
      let valid = true;

      if (!data.get('first_name')) {
        setError('firstName', 'first_name_error', 'First name is required.');
        valid = false;
      }
      if (!data.get('last_name')) {
        setError('lastName', 'last_name_error', 'Last name is required.');
        valid = false;
      }
      if (!data.get('country')) {
        setError('country', 'country_error', 'Country is required.');
        valid = false;
      }
      if (!data.get('company')) {
        setError('company', 'company_error', 'Company is required.');
        valid = false;
      }

      const isPlanDisabled = planSelect ? planSelect.disabled : false;
      if (!isPlanDisabled && !data.get('plan_id')) {
        setError('plan', 'plan_id_error', 'Plan is required.');
        valid = false;
      }

      const email = data.get('email');
      if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        setError('email', 'email_error', 'A valid email is required.');
        valid = false;
      }

      const password = data.get('password');
      if (!password || password.length < 6) {
        setError('password', 'password_error', 'Password must be at least 6 characters.');
        valid = false;
      }

      if (!data.get('agreed_terms')) {
        setError('terms', 'agreed_terms_error', 'You must agree to the terms.');
        valid = false;
      }

      if (!data.get('captcha')) {
        setError('captcha', 'captcha_error', 'Captcha is required.');
        valid = false;
      }

      if (!valid) {
        showToast('Please correct the errors above.', {
          title: 'Validation Error',
          variant: 'danger'
        });
        return;
      }

      if (!storeUrl) {
        return;
      }

      try {
        const response = await fetch(storeUrl, {
          method: 'POST',
          headers: Object.assign({}, csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}, { Accept: 'application/json' }),
          body: data
        });

        let result = {};
        try {
          result = await response.json();
        } catch (error) {
          result = {};
        }

        if (response.status === 422) {
          const fieldMap = {
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

          Object.entries(result.errors || {}).forEach(([field, messages]) => {
            const inputId = fieldMap[field] || field;
            const message = Array.isArray(messages) ? messages[0] : messages;
            setError(inputId, `${field}_error`, message);
          });

          showToast('Please correct the errors above.', {
            title: 'Validation Error',
            variant: 'danger'
          });
          return;
        }

        if (response.ok && result.success) {
          showToast('Registration successful. Redirecting to login...', {
            title: 'Success',
            variant: 'success'
          });
          if (loginUrl) {
            setTimeout(() => {
              window.location = loginUrl;
            }, 1200);
          }
        } else {
          const message = result.error || 'Registration failed.';
          showToast(message, {
            title: 'Error',
            variant: 'danger'
          });

          if (loginUrl && (message || '').toLowerCase().includes('email')) {
            showToast('Already have an account? Click here to log in.', {
              title: 'Login',
              variant: 'info',
              delayMs: 4000,
              onClick: () => {
                window.location = loginUrl;
              }
            });
          }
        }
      } catch (error) {
        showToast('Something went wrong. Please try again.', {
          title: 'Error',
          variant: 'danger'
        });
      }
    });

    const passwordInput = document.getElementById('password');
    const strengthWrapper = document.getElementById('password-strength');
    const strengthBar = strengthWrapper ? strengthWrapper.querySelector('.progress-bar') : null;
    const strengthText = document.getElementById('strength-text');

    if (passwordInput && strengthBar && strengthText) {
      passwordInput.addEventListener('input', () => {
        const value = passwordInput.value || '';
        let score = 0;
        if (value.length >= 8) {
          score += 25;
        }
        if (/[A-Z]/.test(value)) {
          score += 25;
        }
        if (/[0-9]/.test(value)) {
          score += 25;
        }
        if (/[!@#\$%\^&\*]/.test(value)) {
          score += 25;
        }
        strengthBar.style.width = `${score}%`;
        strengthText.textContent = !value ? '' : (score < 50 ? 'Weak' : (score < 100 ? 'Medium' : 'Strong'));
      });
    }
  }

  setupPasswordToggles();
  initLoginForm();
  initRegisterForm();
})();
