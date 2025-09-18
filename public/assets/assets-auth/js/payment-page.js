(() => {
  const container = document.getElementById('paymentApp');
  if (!container) {
    return;
  }

  const {
    planId = '',
    firstName = '',
    lastName = '',
    email = '',
    mobile = '',
    company = '',
    country = '',
    cashfreeEnabled = '0',
    cashfreeMode = 'sandbox',
    cashfreeOrderUrl = '',
    completeUrl = '',
    loginUrl = '',
    requiresPayment = '0',
  } = container.dataset;

  const needsPayment = requiresPayment === '1';
  const cashfreeActive = cashfreeEnabled === '1';
  const statusElement = container.querySelector('[data-payment-status]');
  const statusBaseClass = statusElement ? (statusElement.dataset.baseClass || statusElement.className || 'alert') : 'alert';
  const currencyButtons = container.querySelectorAll('[data-pay-currency]');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

  const setStatus = (message, variant = 'info') => {
    if (!statusElement) {
      return;
    }

    statusElement.className = statusBaseClass;

    if (!message) {
      statusElement.classList.add('d-none');
      statusElement.textContent = '';
      return;
    }

    statusElement.classList.remove('d-none');
    statusElement.classList.remove('alert-primary', 'alert-secondary', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info', 'alert-light', 'alert-dark');
    statusElement.classList.add(`alert-${variant}`);
    statusElement.textContent = message;
  };

  const toggleButtons = (disabled, activeButton = null) => {
    currencyButtons.forEach((button) => {
      button.disabled = disabled;
      if (disabled) {
        button.classList.add('disabled');
      } else {
        button.classList.remove('disabled');
      }
    });

    if (disabled && activeButton) {
      activeButton.classList.add('disabled');
    }
  };

  const requestJson = async (url, payload) => {
    const headers = { Accept: 'application/json', 'Content-Type': 'application/json' };
    if (csrfToken) {
      headers['X-CSRF-TOKEN'] = csrfToken;
    }

    const response = await fetch(url, {
      method: 'POST',
      headers,
      body: JSON.stringify(payload || {}),
    });

    const json = await response.json().catch(() => ({}));
    return { response, json };
  };

  let cashfreeInstance = null;
  let cashfreeInstancePromise = null;

  const resolveCashfreeConstructor = () => {
    if (typeof window === 'undefined') {
      return null;
    }

    let ctor = window.Cashfree || window.cashfree || null;
    if (ctor && typeof ctor === 'object' && typeof ctor.Cashfree === 'function') {
      ctor = ctor.Cashfree;
    }

    return typeof ctor === 'function' ? ctor : null;
  };

  const attemptInstantiateCashfree = async () => {
    const ctor = resolveCashfreeConstructor();
    if (!ctor) {
      return null;
    }

    const options = { mode: cashfreeMode === 'production' ? 'production' : 'sandbox' };

    const tryBuild = (factory, useNew = false) => {
      try {
        const instance = useNew ? new factory(options) : factory(options);
        return instance;
      } catch (error) {
        const message = typeof error?.message === 'string' ? error.message : '';
        if (!useNew && /class constructor/i.test(message)) {
          return tryBuild(factory, true);
        }

        return null;
      }
    };

    let candidate = tryBuild(ctor, false);
    if (!candidate) {
      candidate = tryBuild(ctor, true);
    }

    if (candidate && typeof candidate.then === 'function') {
      try {
        const awaited = await candidate;
        return awaited && typeof awaited.checkout === 'function' ? awaited : null;
      } catch (error) {
        return null;
      }
    }

    return candidate && typeof candidate.checkout === 'function' ? candidate : null;
  };

  const ensureCashfreeInstance = async () => {
    if (!cashfreeActive) {
      return null;
    }

    if (cashfreeInstance && typeof cashfreeInstance.checkout === 'function') {
      return cashfreeInstance;
    }

    if (cashfreeInstancePromise) {
      return cashfreeInstancePromise;
    }

    const loadInstance = async () => {
      for (let attempt = 0; attempt < 4; attempt += 1) {
        const instance = await attemptInstantiateCashfree();
        if (instance && typeof instance.checkout === 'function') {
          return instance;
        }

        await wait(200 * (attempt + 1));
      }

      return null;
    };

    cashfreeInstancePromise = loadInstance().then((instance) => {
      cashfreeInstance = instance && typeof instance.checkout === 'function' ? instance : null;
      return cashfreeInstance;
    }).finally(() => {
      cashfreeInstancePromise = null;
    });

    return cashfreeInstancePromise;
  };

  if (!needsPayment) {
    toggleButtons(true);
    setStatus('', 'info');
    return;
  }

  if (!cashfreeActive || !cashfreeOrderUrl || !completeUrl) {
    toggleButtons(true);
    setStatus('Online payments are currently unavailable. Please contact support to complete your subscription.', 'danger');
    return;
  }

  const handleCheckout = async (rawCurrency, button) => {
    const currency = (rawCurrency || '').toUpperCase();
    if (!currency) {
      return;
    }

    const sanitizedMobile = (mobile || '').replace(/\D+/g, '').slice(0, 15);
    if (sanitizedMobile.length < 6) {
      setStatus('A valid mobile number is required before continuing to payment.', 'danger');
      return;
    }

    toggleButtons(true, button);
    setStatus('Preparing secure payment...', 'info');

    try {
      const { response, json } = await requestJson(cashfreeOrderUrl, {
        plan_id: planId,
        currency,
        first_name: firstName,
        last_name: lastName,
        email,
        mobile: sanitizedMobile,
        country,
        company,
      });

      if (!response.ok || !json || !json.success) {
        const message = json?.message
          || (json?.errors && Object.values(json.errors).flat().join(' '))
          || json?.error
          || 'Unable to initiate the payment. Please try again.';
        setStatus(message, 'danger');
        return;
      }

      const sessionId = json.payment_session_id;
      const orderId = json.order_id;
      const instance = await ensureCashfreeInstance();

      if (!instance || !sessionId || !orderId) {
        setStatus('Unable to load the payment gateway right now. Please refresh and try again.', 'danger');
        return;
      }

      let checkoutCancelled = false;
      await instance.checkout({
        paymentSessionId: sessionId,
        redirectTarget: '_modal',
      }).catch((error) => {
        checkoutCancelled = true;
        const message = error?.message || 'Payment was cancelled before completion.';
        setStatus(message, 'danger');
      });

      if (checkoutCancelled) {
        return;
      }

      setStatus('Verifying payment...', 'info');
      const completion = await requestJson(completeUrl, { order_id: orderId });

      if (!completion.response.ok || !completion.json || !completion.json.success) {
        const message = completion.json?.message || 'Payment could not be confirmed. Please try again.';
        setStatus(message, 'danger');
        return;
      }

      const redirectTarget = completion.json.redirect_url || loginUrl;
      setStatus('Payment successful! Redirecting...', 'success');
      if (redirectTarget) {
        setTimeout(() => {
          window.location = redirectTarget;
        }, 1500);
      }
    } catch (error) {
      setStatus(error?.message || 'Unable to process the payment. Please try again.', 'danger');
    } finally {
      toggleButtons(false);
    }
  };

  currencyButtons.forEach((button) => {
    button.addEventListener('click', () => {
      handleCheckout(button.dataset.payCurrency || '', button);
    });
  });
})();
