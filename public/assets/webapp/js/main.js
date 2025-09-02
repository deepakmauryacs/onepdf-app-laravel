const yearEl = document.getElementById('y');
if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}

const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
  newsletterForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const emailInput = newsletterForm.querySelector('input[name="email"]');
    const feedback = document.getElementById('newsletterFeedback');
    const email = emailInput.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      if (feedback) {
        feedback.textContent = 'Please enter a valid email address.';
        feedback.classList.remove('text-success');
        feedback.classList.add('text-danger');
      }
      return;
    }

    try {
      const res = await fetch(newsletterForm.action, {
        method: 'POST',
        body: new FormData(newsletterForm)
      });
      const data = await res.json();
      if (data.success) {
        if (feedback) {
          feedback.textContent = 'Subscribed successfully!';
          feedback.classList.remove('text-danger');
          feedback.classList.add('text-success');
        }
        emailInput.value = '';
      } else {
        if (feedback) {
          feedback.textContent = data.error || 'Subscription failed.';
          feedback.classList.remove('text-success');
          feedback.classList.add('text-danger');
        }
      }
    } catch (err) {
      if (feedback) {
        feedback.textContent = 'Subscription failed.';
        feedback.classList.remove('text-success');
        feedback.classList.add('text-danger');
      }
    }
  });
}
