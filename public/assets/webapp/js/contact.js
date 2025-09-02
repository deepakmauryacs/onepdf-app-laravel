// Enhanced form validation and submission for the contact page
document.getElementById('contactForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    let valid = true;

    // Clear previous errors
    document.querySelectorAll('#contactForm .error-message').forEach(el => el.textContent = '');
    document.querySelectorAll('#contactForm .form-control, #contactForm .form-select, #contactForm textarea').forEach(el => el.classList.remove('is-invalid'));

    const data = new FormData(form);

    if (!data.get('firstName')) {
        document.getElementById('firstName_error').textContent = 'First name is required.';
        document.getElementById('firstName').classList.add('is-invalid');
        valid = false;
    }
    if (!data.get('lastName')) {
        document.getElementById('lastName_error').textContent = 'Last name is required.';
        document.getElementById('lastName').classList.add('is-invalid');
        valid = false;
    }
    const email = data.get('email');
    if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        document.getElementById('email_error').textContent = 'A valid email is required.';
        document.getElementById('email').classList.add('is-invalid');
        valid = false;
    }
    if (!data.get('subject')) {
        document.getElementById('subject_error').textContent = 'Subject is required.';
        document.getElementById('subject').classList.add('is-invalid');
        valid = false;
    }
    if (!data.get('message')) {
        document.getElementById('message_error').textContent = 'Message is required.';
        document.getElementById('message').classList.add('is-invalid');
        valid = false;
    }

    if (!valid) {
        alert('Please correct the errors above.');
        return;
    }

    try {
        const res = await fetch('contact.php', {
            method: 'POST',
            body: data
        });
        const result = await res.json();
        if (result.success) {
            alert('Thank you for your message! We will get back to you soon.');
            form.reset();
        } else {
            alert(result.error || 'There was a problem sending your message.');
        }
    } catch (err) {
        alert('There was a problem sending your message.');
    }
});

