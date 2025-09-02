@extends('layouts.app')

@section('title', 'Contact Us - PDFOneLink')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/webapp/css/contact.css') }}">
  <style>
    .error-message { color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1em; }
    .is-invalid { border-color:#dc2626 !important; }
    .form-success { color:#16a34a; }
    .form-error-global { color:#dc2626; margin-bottom:10px; display:none; }
  </style>
@endpush

@section('content')

<!-- Hero Section -->
<section class="contact-hero">
  <div class="container position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <h1 class="display-5 fw-bold mb-3">Get in Touch</h1>
        <p class="lead text-muted mb-4">We'd love to hear from you. Our team is always ready to help with any questions about PDFOneLink.</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7">
        <div class="contact-card">
          <h3 class="section-title mb-4">Send us a message</h3>

          <!-- global form error (e.g., DB error) -->
          <div id="form_global_error" class="form-error-global"></div>

          <form id="contactForm" novalidate>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="firstName" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstName" name="firstName">
                  <div id="firstName_error" class="error-message"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastName" name="lastName">
                  <div id="lastName_error" class="error-message"></div>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email" name="email">
              <div id="email_error" class="error-message"></div>
            </div>

            <div class="mb-3">
              <label for="company" class="form-label">Company (Optional)</label>
              <input type="text" class="form-control" id="company" name="company">
              <div id="company_error" class="error-message"></div>
            </div>

            <div class="mb-3">
              <label for="subject" class="form-label">Subject</label>
              <select class="form-select" id="subject" name="subject">
                <option value="" selected disabled>Select a subject</option>
                <option value="sales">Sales Inquiry</option>
                <option value="support">Technical Support</option>
                <option value="billing">Billing Question</option>
                <option value="partnership">Partnership Opportunity</option>
                <option value="other">Other</option>
              </select>
              <div id="subject_error" class="error-message"></div>
            </div>

            <div class="mb-4">
              <label for="message" class="form-label">Message</label>
              <textarea class="form-control" id="message" name="message" rows="5"></textarea>
              <div id="message_error" class="error-message"></div>
            </div>

            <button type="submit" class="btn btn-brand btn-lg w-100">Send Message</button>
            <div id="form_success" class="mt-3 form-success" style="display:none;">Thank you for your message! We will get back to you soon.</div>
          </form>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="contact-card">
          <h3 class="section-title mb-4">Contact Information</h3>

          <div class="contact-info-item">
            <div class="contact-icon"><i class="bi bi-envelope"></i></div>
            <div>
              <h5>Email Us</h5>
              <p class="text-muted mb-0">support@pdfonelink.com</p>
              <p class="text-muted">sales@pdfonelink.com</p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon"><i class="bi bi-telephone"></i></div>
            <div>
              <h5>Call Us</h5>
              <p class="text-muted mb-0">+91 7081000740 (Sales/Support)</p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
              <h5>Visit Us</h5>
              <p class="text-muted mb-0">68, Gijhore, Sector 53</p>
              <p class="text-muted">Noida, Uttar Pradesh 201307</p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon"><i class="bi bi-clock"></i></div>
            <div>
              <h5>Office Hours</h5>
              <p class="text-muted mb-0">Monday - Friday: 9AM - 6PM PST</p>
              <p class="text-muted">Weekend: Emergency support only</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title centered">Common Questions</h2>
      <p class="section-subtitle">Quick answers to frequently asked questions</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="faq-item">
          <h6 class="faq-question"><i class="bi bi-question-circle"></i>What's your average response time?</h6>
          <p class="text-muted mb-0">We typically respond to all inquiries within 24 hours on business days. For urgent matters, please call our support line.</p>
        </div>
        <div class="faq-item">
          <h6 class="faq-question"><i class="bi bi-question-circle"></i>Do you offer custom enterprise solutions?</h6>
          <p class="text-muted mb-0">Yes, we offer customized enterprise plans with advanced features, dedicated support, and SLA guarantees. Contact our sales team for more information.</p>
        </div>
        <div class="faq-item">
          <h6 class="faq-question"><i class="bi bi-question-circle"></i>How can I request a feature?</h6>
          <p class="text-muted mb-0">We welcome feature requests! Please email us at feedback@pdfonelink.com with your suggestions. Our product team reviews all requests regularly.</p>
        </div>
        <div class="faq-item">
          <h6 class="faq-question"><i class="bi bi-question-circle"></i>Do you have an affiliate program?</h6>
          <p class="text-muted mb-0">Yes, we have an affiliate program that offers commissions for referrals. Contact partnerships@pdfonelink.com for more details.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="contactSuccessModal" tabindex="-1" aria-labelledby="contactSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">
        <div class="mb-3">
          <i class="bi bi-check-circle" style="font-size:3rem;"></i>
        </div>
        <h4 id="contactSuccessModalLabel" class="mb-2">Message sent!</h4>
        <p class="text-muted mb-4">Thanks for reaching out. Our team will get back to you shortly.</p>
        <button type="button" class="btn btn-brand px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
/**
 * Enhanced form validation & AJAX submission
 * - Per-field error rendering
 * - Disables button + shows spinner while sending
 * - On success: resets form and shows Bootstrap modal (fallback to inline message)
 */
(function(){
  const form = document.getElementById('contactForm');
  const globalError = document.getElementById('form_global_error');
  const successMsg  = document.getElementById('form_success'); // kept as fallback
  const fields = ['firstName','lastName','email','company','subject','message'];
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalBtnHtml = submitBtn.innerHTML;

  function clearErrors(){
    globalError.style.display = 'none';
    globalError.textContent = '';
    successMsg.style.display = 'none';
    fields.forEach(id => {
      const input = document.getElementById(id);
      if (input) input.classList.remove('is-invalid');
      const err = document.getElementById(id + '_error');
      if (err) err.textContent = '';
    });
  }

  function setFieldError(id, msg){
    const input = document.getElementById(id);
    const err   = document.getElementById(id + '_error');
    if (input) input.classList.add('is-invalid');
    if (err)   err.textContent = msg || '';
  }

  function setLoading(loading){
    if (loading) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
    } else {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnHtml;
    }
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    clearErrors();

    let ok = true;
    const data = new FormData(form);

    if (!data.get('firstName')) { setFieldError('firstName','First name is required.'); ok = false; }
    if (!data.get('lastName'))  { setFieldError('lastName','Last name is required.'); ok = false; }
    const email = data.get('email');
    if (!email) {
      setFieldError('email','Email is required.'); ok = false;
    } else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
      setFieldError('email','Please enter a valid email.'); ok = false;
    }
    if (!data.get('subject'))   { setFieldError('subject','Subject is required.'); ok = false; }
    if (!data.get('message'))   { setFieldError('message','Message is required.'); ok = false; }

    if (!ok) return;

    setLoading(true);
    try{
      const res = await fetch("{{ route('contact.store') }}", {
        method:'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:data
      });

      // Laravel validation errors
      if (res.status === 422) {
        const j = await res.json();
        const map = { firstName:'firstName', lastName:'lastName', email:'email', company:'company', subject:'subject', message:'message' };
        Object.entries(j.errors || {}).forEach(([k,v]) => {
          const id = map[k] ?? k;
          setFieldError(id, Array.isArray(v) ? v[0] : v);
        });
        setLoading(false);
        return;
      }

      const json = await res.json();

      if (json.success) {
        form.reset();
        // Prefer modal if Bootstrap is available
        const modalEl = document.getElementById('contactSuccessModal');
        if (window.bootstrap && modalEl) {
          new bootstrap.Modal(modalEl).show();
        } else {
          successMsg.style.display = 'block';
        }
        setLoading(false);
        return;
      }

      if (json.errors) {
        Object.keys(json.errors).forEach(k => {
          if (k === '_form') {
            globalError.textContent = json.errors[k];
            globalError.style.display = 'block';
          } else {
            setFieldError(k, json.errors[k]);
          }
        });
      } else {
        globalError.textContent = json.error || 'There was a problem sending your message.';
        globalError.style.display = 'block';
      }
    } catch(err){
      globalError.textContent = 'There was a problem sending your message.';
      globalError.style.display = 'block';
    } finally {
      setLoading(false);
    }
  });
})();
</script>
@endpush
