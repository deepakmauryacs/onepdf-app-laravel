@extends('layouts.app')

@section('title', 'Partner With Us - PDFOneLink')


@section('content')
<!-- Hero Section -->
<section class="contact-hero">
  <div class="container position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <h1 class="display-5 fw-bold mb-3">Partner With Us</h1>
        <p class="lead text-muted mb-4">Let’s collaborate and grow together. Fill out the form and our partnerships team will reach out.</p>
      </div>
    </div>
  </div>
</section>

<!-- Partnership Form Section -->
<section class="contact-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="contact-card">
          <h3 class="section-title mb-4">Send us a Partnership Inquiry</h3>

          <div id="form_global_error" class="form-error-global"></div>

          {{-- Normal POST fallback works even if JS is disabled --}}
          <form id="partnershipForm" novalidate action="{{ route('partnerships.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-floating mb-1">
                  <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                         id="firstName" name="firstName" autocomplete="given-name" maxlength="100" placeholder="First Name"
                         value="{{ old('firstName') }}">
                  <label for="firstName">First Name</label>
                </div>
                <div id="firstName_error" class="error-message">@error('firstName'){{ $message }}@enderror</div>
              </div>
              <div class="col-md-6">
                <div class="form-floating mb-1">
                  <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                         id="lastName" name="lastName" autocomplete="family-name" maxlength="100" placeholder="Last Name"
                         value="{{ old('lastName') }}">
                  <label for="lastName">Last Name</label>
                </div>
                <div id="lastName_error" class="error-message">@error('lastName'){{ $message }}@enderror</div>
              </div>
            </div>

            <div class="form-floating mb-1">
              <input type="email" class="form-control @error('email') is-invalid @enderror"
                     id="email" name="email" autocomplete="email" maxlength="150" placeholder="Email Address"
                     value="{{ old('email') }}">
              <label for="email">Email Address</label>
            </div>
            <div id="email_error" class="error-message">@error('email'){{ $message }}@enderror</div>

            <div class="form-floating mb-1">
              <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                     id="contact_number" name="contact_number" autocomplete="tel" maxlength="32" inputmode="tel" placeholder="Contact Number"
                     value="{{ old('contact_number') }}">
              <label for="contact_number">Contact Number</label>
            </div>
            <div id="contact_number_error" class="error-message">@error('contact_number'){{ $message }}@enderror</div>

            <div class="form-floating mb-1">
              <textarea class="form-control @error('message') is-invalid @enderror"
                        id="message" name="message" maxlength="10000" placeholder="Message" style="height: 150px">{{ old('message') }}</textarea>
              <label for="message">Message</label>
            </div>
            <div id="message_error" class="error-message mb-4">@error('message'){{ $message }}@enderror</div>

            <button type="submit" class="btn btn-brand btn-lg w-100">Send Inquiry</button>
            <div id="form_success" class="mt-3 form-success"
                 @if(!session('partnership_success')) style="display:none;" @endif>
              {{ session('partnership_success') ?? 'Thank you for reaching out! Our partnerships team will contact you soon.' }}
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="partnershipSuccessModal" tabindex="-1" aria-labelledby="partnershipSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-5 text-center">
        <div class="mb-3">
          <i class="bi bi-check-circle" style="font-size:3rem;"></i>
        </div>
        <h4 id="partnershipSuccessModalLabel" class="mb-2">Inquiry sent!</h4>
        <p class="text-muted mb-4">Thanks for showing interest in partnering with us.</p>
        <button type="button" class="btn btn-brand px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const form = document.getElementById('partnershipForm');
  if (!form) return;

  const globalError = document.getElementById('form_global_error');
  const successMsg  = document.getElementById('form_success');
  const fields = ['firstName','lastName','email','contact_number','message'];
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalBtnHtml = submitBtn.innerHTML;

  function clearErrors(){
    if (globalError){ globalError.style.display='none'; globalError.textContent=''; }
    if (successMsg){ successMsg.style.display='none'; }
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
    if (!submitBtn) return;
    if (loading) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...';
    } else {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnHtml;
    }
  }

  form.addEventListener('submit', async function(e){
    // Comment the next line if you prefer full-page POST/redirect instead of AJAX.
    e.preventDefault();

    clearErrors();

    let ok = true;
    const data = new FormData(form);

    if (!data.get('firstName')) { setFieldError('firstName','First name is required.'); ok = false; }
    if (!data.get('lastName'))  { setFieldError('lastName','Last name is required.');  ok = false; }
    const email = data.get('email');
    if (!email) { setFieldError('email','Email is required.'); ok = false; }
    else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) { setFieldError('email','Please enter a valid email.'); ok = false; }
    if (!data.get('contact_number')) { setFieldError('contact_number','Contact number is required.'); ok = false; }
    if (!data.get('message'))        { setFieldError('message','Message is required.'); ok = false; }

    if (!ok) return;

    setLoading(true);
    try{
      const res = await fetch(form.action, {
        method:'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': @json(csrf_token())
        },
        body:data
      });

      if (res.status === 422) {
        const j = await res.json();
        Object.entries(j.errors || {}).forEach(([k,v]) => setFieldError(k, Array.isArray(v) ? v[0] : v));
        setLoading(false);
        return;
      }

      const json = await res.json();
      if (json.success) {
        form.reset();
        const modalEl = document.getElementById('partnershipSuccessModal');
        if (window.bootstrap && modalEl) new bootstrap.Modal(modalEl).show();
        else if (successMsg) successMsg.style.display = 'block';
      } else {
        if (globalError){
          globalError.textContent = json.error || 'There was a problem sending your inquiry.';
          globalError.style.display = 'block';
        }
      }
    } catch(err){
      if (globalError){
        globalError.textContent = 'There was a problem sending your inquiry.';
        globalError.style.display = 'block';
      }
    } finally {
      setLoading(false);
    }
  });
})();
</script>
@endpush
