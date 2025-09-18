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
  const ensureCashfreeInstance = () => {
    if (!cashfreeActive) {
      return null;
    }

    if (cashfreeInstance) {
      return cashfreeInstance;
    }

    if (typeof window !== 'undefined' && window.Cashfree) {
      try {
        cashfreeInstance = new window.Cashfree({ mode: cashfreeMode === 'production' ? 'production' : 'sandbox' });
      } catch (error) {
        cashfreeInstance = null;
      }
    }

    return cashfreeInstance;
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

    toggleButtons(true, button);
    setStatus('Preparing secure payment...', 'info');
    const sanitizedMobile = (mobile || '').trim();

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
      const instance = ensureCashfreeInstance();

      if (!instance || !sessionId || !orderId) {
        setStatus('Unable to open the payment gateway. Please try again.', 'danger');
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
