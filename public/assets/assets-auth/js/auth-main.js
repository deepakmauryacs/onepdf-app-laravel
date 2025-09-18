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
    const cashfreeEnabled = registerForm.dataset.cashfreeEnabled === '1';
    const cashfreeMode = registerForm.dataset.cashfreeMode || 'sandbox';
    const cashfreeOrderUrl = registerForm.dataset.cashfreeOrderUrl || '';
    const cashfreeVerifyUrl = registerForm.dataset.cashfreeVerifyUrl || '';
    const cashfreeSection = document.getElementById('cashfreePaymentSection');
    const cashfreeButtons = cashfreeSection ? cashfreeSection.querySelectorAll('[data-cashfree-currency]') : [];
    const cashfreePlanSummary = document.getElementById('cashfreePlanSummary');
    const cashfreeStatus = document.getElementById('cashfreePaymentStatus');
    const cashfreeOrderInput = document.getElementById('cashfreeOrderId');
    const cashfreeCurrencyInput = document.getElementById('cashfreeCurrency');
    const cashfreeAmountInput = document.getElementById('cashfreeAmount');
    const cashfreeError = document.getElementById('cashfree_error');
    let cashfreeInstance = null;
    let cashfreeState = {
      planId: null,
      paid: false,
      orderId: '',
      currency: '',
      amount: 0,
    };

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

    const formatCurrency = (amount, currency) => {
      const numeric = Number.parseFloat(amount);
      if (!Number.isFinite(numeric)) {
        return '';
      }
      const digits = Math.abs(numeric - Math.round(numeric)) > 0.001 ? 2 : 0;
      const locale = currency === 'INR' ? 'en-IN' : 'en-US';
      try {
        return new Intl.NumberFormat(locale, {
          style: 'currency',
          currency,
          minimumFractionDigits: digits,
          maximumFractionDigits: digits
        }).format(numeric);
      } catch (error) {
        return `${currency} ${numeric.toFixed(digits)}`;
      }
    };

    const getSelectedPlanOption = () => {
      if (!planSelect) {
        return null;
      }
      const index = planSelect.selectedIndex;
      if (typeof index !== 'number' || index < 0) {
        return null;
      }
      return planSelect.options[index] || null;
    };

    const requiresCashfreePayment = (option) => {
      if (!option || !option.dataset) {
        return false;
      }

      if (typeof option.dataset.cashfreeRequired !== 'undefined') {
        return option.dataset.cashfreeRequired === '1';
      }

      const billing = option.dataset.billing || '';
      const name = (option.dataset.name || '').toLowerCase().trim();
      const inr = Number.parseFloat(option.dataset.inrPrice || '0');
      const usd = Number.parseFloat(option.dataset.usdPrice || '0');
      if (billing !== 'month') {
        return false;
      }
      if (!['pro', 'business'].includes(name)) {
        return false;
      }
      return (Number.isFinite(inr) && inr > 0) || (Number.isFinite(usd) && usd > 0);
    };

    const resetCashfreeFields = () => {
      cashfreeState = {
        planId: null,
        paid: false,
        orderId: '',
        currency: '',
        amount: 0,
      };
      if (cashfreeOrderInput) {
        cashfreeOrderInput.value = '';
      }
      if (cashfreeCurrencyInput) {
        cashfreeCurrencyInput.value = '';
      }
      if (cashfreeAmountInput) {
        cashfreeAmountInput.value = '';
      }
    };

    const setCashfreeStatus = (message, tone = 'muted') => {
      if (!cashfreeStatus) {
        return;
      }
      const tones = ['text-muted', 'text-success', 'text-danger', 'text-info', 'text-warning'];
      cashfreeStatus.textContent = message || '';
      cashfreeStatus.classList.remove(...tones);
      const toneClass = tone === 'success' ? 'text-success'
        : tone === 'danger' ? 'text-danger'
        : tone === 'info' ? 'text-info'
        : tone === 'warning' ? 'text-warning'
        : 'text-muted';
      cashfreeStatus.classList.add(toneClass);
    };

    const ensureCashfreeInstance = () => {
      if (!cashfreeEnabled) {
        return null;
      }
      if (cashfreeInstance) {
        return cashfreeInstance;
      }
      if (window.Cashfree) {
        try {
          cashfreeInstance = new window.Cashfree({ mode: cashfreeMode === 'production' ? 'production' : 'sandbox' });
        } catch (error) {
          cashfreeInstance = null;
        }
      }
      return cashfreeInstance;
    };

    const updateCashfreePlanSummary = (option, needsPayment) => {
      if (!cashfreePlanSummary) {
        return;
      }

      if (!needsPayment || !option || !option.dataset) {
        cashfreePlanSummary.textContent = '';
        cashfreePlanSummary.classList.add('d-none');
        return;
      }

      const planName = option.dataset.name || option.textContent || '';
      const billing = (option.dataset.billing || '').toLowerCase();
      const inrPrice = Number.parseFloat(option.dataset.inrPrice || '0');
      const usdPrice = Number.parseFloat(option.dataset.usdPrice || '0');

      const billingLabel = billing === 'month'
        ? 'monthly'
        : (billing === 'year' ? 'yearly' : billing);

      const amounts = [];
      if (Number.isFinite(inrPrice) && inrPrice > 0) {
        amounts.push(formatCurrency(inrPrice, 'INR'));
      }
      if (Number.isFinite(usdPrice) && usdPrice > 0) {
        amounts.push(formatCurrency(usdPrice, 'USD'));
      }

      const amountText = amounts.length > 0
        ? `Complete the payment of ${amounts.join(' or ')} via Cashfree.`
        : 'Continue to Cashfree to complete your activation.';

      const parts = [];
      if (planName) {
        parts.push(`You're activating the ${planName} plan`);
      }
      if (billingLabel) {
        parts.push(`(${billingLabel})`);
      }

      const prefix = parts.length > 0 ? `${parts.join(' ')}. ` : '';

      cashfreePlanSummary.textContent = `${prefix}${amountText}`.trim();
      cashfreePlanSummary.classList.remove('d-none');
    };

    const updateCashfreeButtons = (option) => {
      if (!cashfreeSection) {
        return;
      }
      const inrPrice = option ? Number.parseFloat(option.dataset.inrPrice || '0') : 0;
      const usdPrice = option ? Number.parseFloat(option.dataset.usdPrice || '0') : 0;

      cashfreeButtons.forEach((button) => {
        const currency = (button.dataset.cashfreeCurrency || '').toUpperCase();
        const amount = currency === 'INR' ? inrPrice : usdPrice;
        const amountLabel = button.querySelector('[data-amount-currency]');
        if (Number.isFinite(amount) && amount > 0) {
          button.classList.remove('d-none');
          button.disabled = !cashfreeEnabled;
          if (amountLabel) {
            amountLabel.textContent = formatCurrency(amount, currency);
          }
        } else {
          button.classList.add('d-none');
        }
      });
    };

    const updateCashfreeSection = () => {
      if (!cashfreeSection) {
        return;
      }

      const option = getSelectedPlanOption();
      const needsPayment = requiresCashfreePayment(option);

      if (!needsPayment) {
        cashfreeSection.classList.add('d-none');
        resetCashfreeFields();
        setCashfreeStatus('');
        if (cashfreeError) {
          cashfreeError.textContent = '';
        }
        updateCashfreePlanSummary(option, needsPayment);
        return;
      }

      updateCashfreeButtons(option);
      updateCashfreePlanSummary(option, needsPayment);

      const planId = option ? option.value : null;
      if (cashfreeState.planId !== planId) {
        resetCashfreeFields();
        cashfreeState.planId = planId;
      }

      cashfreeSection.classList.remove('d-none');

      if (!cashfreeEnabled) {
        setCashfreeStatus('Payment gateway is temporarily unavailable. Please contact support.', 'danger');
        cashfreeButtons.forEach((button) => {
          button.disabled = true;
        });
        return;
      }

      if (cashfreeState.paid) {
        const message = `Payment confirmed (${formatCurrency(cashfreeState.amount, cashfreeState.currency)}).`;
        setCashfreeStatus(message, 'success');
      } else {
        setCashfreeStatus('Please complete your Cashfree payment to activate this plan.', 'muted');
      }
    };

    const requestJson = async (url, payload) => {
      const headers = csrfToken
        ? { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        : { 'Content-Type': 'application/json', 'Accept': 'application/json' };
      const response = await fetch(url, {
        method: 'POST',
        headers,
        body: JSON.stringify(payload || {}),
      });
      const json = await response.json().catch(() => ({}));
      return { response, json };
    };

    const verifyCashfreeOrder = async (orderId, attempts = 4) => {
      if (!orderId || !cashfreeVerifyUrl) {
        return null;
      }

      for (let attempt = 0; attempt < attempts; attempt += 1) {
        try {
          const { response, json } = await requestJson(cashfreeVerifyUrl, { order_id: orderId });
          if (response.ok && json && json.success) {
            return json;
          }
        } catch (error) {
          // ignore and retry
        }

        await new Promise((resolve) => setTimeout(resolve, 800));
      }

      return null;
    };

    const handleCashfreeSuccess = (planId, orderId, currency, amount) => {
      cashfreeState = {
        planId,
        paid: true,
        orderId,
        currency,
        amount,
      };

      if (cashfreeOrderInput) {
        cashfreeOrderInput.value = orderId;
      }
      if (cashfreeCurrencyInput) {
        cashfreeCurrencyInput.value = currency;
      }
      if (cashfreeAmountInput) {
        cashfreeAmountInput.value = Number.isFinite(amount) ? amount.toFixed(2) : '';
      }

      const formatted = formatCurrency(amount, currency);
      setCashfreeStatus(`Payment confirmed (${formatted}). You can finish registration.`, 'success');
      showToast('Payment successful. You can now complete your registration.', {
        title: 'Payment received',
        variant: 'success',
      });
    };

    const handleCashfreeFailure = (message) => {
      cashfreeState.paid = false;
      cashfreeState.orderId = '';
      cashfreeState.currency = '';
      cashfreeState.amount = 0;
      if (cashfreeOrderInput) {
        cashfreeOrderInput.value = '';
      }
      if (cashfreeCurrencyInput) {
        cashfreeCurrencyInput.value = '';
      }
      if (cashfreeAmountInput) {
        cashfreeAmountInput.value = '';
      }
      if (message) {
        setCashfreeStatus(message, 'danger');
      }
    };

    const initiateCashfreePayment = async (currency) => {
      const option = getSelectedPlanOption();
      if (!option) {
        showToast('Please select a plan before initiating payment.', { title: 'Select plan', variant: 'warning' });
        return;
      }

      if (!requiresCashfreePayment(option)) {
        showToast('The selected plan does not require a payment.', { title: 'Payment not required', variant: 'info' });
        return;
      }

      const planId = option.value;
      const firstName = registerForm.querySelector('input[name="first_name"]')?.value.trim();
      const lastName = registerForm.querySelector('input[name="last_name"]')?.value.trim();
      const email = registerForm.querySelector('input[name="email"]')?.value.trim();
      const company = registerForm.querySelector('input[name="company"]')?.value.trim();
      const countryValue = countrySelect?.value || '';

      if (!firstName || !lastName || !email) {
        showToast('Please fill in your name and email before proceeding to payment.', {
          title: 'Details required',
          variant: 'warning',
        });
        return;
      }

      if (!cashfreeEnabled || !cashfreeOrderUrl) {
        showToast('Cashfree payments are currently unavailable.', {
          title: 'Payment unavailable',
          variant: 'danger',
        });
        return;
      }

      const button = Array.from(cashfreeButtons).find((btn) => (btn.dataset.cashfreeCurrency || '').toUpperCase() === currency);
      if (button) {
        button.disabled = true;
        button.classList.add('disabled');
      }

      setCashfreeStatus('Redirecting to secure Cashfree checkout...', 'info');

      try {
        const { response, json } = await requestJson(cashfreeOrderUrl, {
          plan_id: planId,
          currency,
          first_name: firstName,
          last_name: lastName,
          email,
          country: countryValue,
          company,
        });

        if (!response.ok || !json || !json.success) {
          const message = json?.message
            || (json?.errors && Object.values(json.errors).flat().join(' '))
            || json?.error
            || 'Unable to initiate Cashfree payment.';
          setCashfreeStatus(message, 'danger');
          showToast(message, { title: 'Payment failed', variant: 'danger' });
          return;
        }

        const { payment_session_id: sessionId, order_id: orderId, order_amount: orderAmount } = json;
        const instance = ensureCashfreeInstance();

        if (!instance || !sessionId || !orderId) {
          setCashfreeStatus('Unable to open Cashfree checkout. Please try again.', 'danger');
          showToast('Unable to open Cashfree checkout. Please try again.', {
            title: 'Payment error',
            variant: 'danger',
          });
          return;
        }

        await instance.checkout({
          paymentSessionId: sessionId,
          redirectTarget: '_modal',
        }).catch((error) => {
          setCashfreeStatus('Payment window was closed before completion.', 'danger');
          showToast(error?.message || 'Payment was cancelled.', {
            title: 'Payment cancelled',
            variant: 'danger',
          });
        });

        const verification = await verifyCashfreeOrder(orderId);

        if (verification && typeof verification.order_status === 'string') {
          const status = verification.order_status.toUpperCase();
          if (status === 'PAID') {
            const amountFromVerification = Number.parseFloat(verification.order_amount ?? '');
            const amountFromResponse = Number.parseFloat(orderAmount ?? '');
            const confirmedAmount = Number.isFinite(amountFromVerification)
              ? amountFromVerification
              : (Number.isFinite(amountFromResponse) ? amountFromResponse : 0);
            const finalCurrency = (verification.order_currency || currency || '').toUpperCase() || currency;
            handleCashfreeSuccess(planId, orderId, finalCurrency, confirmedAmount);
            return;
          }

          if (['FAILED', 'CANCELLED'].includes(status)) {
            handleCashfreeFailure('Payment was not completed. Please try again.');
            showToast('Cashfree reported the payment as incomplete.', { title: 'Payment incomplete', variant: 'danger' });
            return;
          }
        }

        handleCashfreeFailure('Payment could not be verified yet. Please try again in a moment.');
        showToast('We could not confirm the payment. If the amount was deducted, please contact support.', {
          title: 'Verification pending',
          variant: 'warning',
        });
      } catch (error) {
        handleCashfreeFailure('Unable to process the payment right now.');
        showToast('Unable to reach the payment gateway. Please try again.', {
          title: 'Payment error',
          variant: 'danger',
        });
      } finally {
        if (button) {
          button.disabled = false;
          button.classList.remove('disabled');
        }
        updateCashfreeSection();
      }
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

      updateCashfreeSection();
    };

    if (countrySelect) {
      countrySelect.addEventListener('change', updatePlanOptions);
    }
    updatePlanOptions();

    if (planSelect) {
      planSelect.addEventListener('change', () => {
        if (cashfreeError) {
          cashfreeError.textContent = '';
        }
        updateCashfreeSection();
      });
    }

    if (cashfreeSection) {
      cashfreeButtons.forEach((button) => {
        button.addEventListener('click', () => {
          const currency = (button.dataset.cashfreeCurrency || '').toUpperCase();
          if (currency) {
            void initiateCashfreePayment(currency);
          }
        });
      });
    }

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

      const selectedPlanOption = getSelectedPlanOption();
      const paymentRequired = requiresCashfreePayment(selectedPlanOption);

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

      if (paymentRequired && cashfreeState.paid) {
        if (cashfreeOrderInput) {
          cashfreeOrderInput.value = cashfreeState.orderId;
        }
        if (cashfreeCurrencyInput) {
          cashfreeCurrencyInput.value = cashfreeState.currency;
        }
        if (cashfreeAmountInput) {
          cashfreeAmountInput.value = Number.isFinite(cashfreeState.amount)
            ? cashfreeState.amount.toFixed(2)
            : cashfreeState.amount || '';
        }
      } else if (!paymentRequired) {
        resetCashfreeFields();
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
          const redirectTarget = result.redirect_url || loginUrl;
          const message = redirectTarget && redirectTarget !== loginUrl
            ? 'Registration successful. Redirecting to payment...'
            : 'Registration successful. Redirecting to login...';
          showToast(message, {
            title: 'Success',
            variant: 'success'
          });
          if (redirectTarget) {
            setTimeout(() => {
              window.location = redirectTarget;
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
