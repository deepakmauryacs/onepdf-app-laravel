@extends('layouts.app')

@section('title', 'Contact Us - PDFOneLink')

@push('styles')
  <!-- Page CSS: Contact (Black & White) -->
  <style>
    :root{
      --ink:#0a0a0a;
      --muted:#6b7280;
      --line:#e6e7eb;
      --panel:#ffffff;
      --chip:#f3f4f6;
      --section:#f6f7f8;
      --radius:14px;
      --shadow:0 6px 24px rgba(0,0,0,.06);
      --shadow-lg:0 10px 24px rgba(0,0,0,.10);
    }
    /* Hero */
    .contact-hero{
      overflow:hidden;
      padding:72px 0 56px;
      background:
        radial-gradient(900px 420px at 85% -120px, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        radial-gradient(700px 380px at -10% 110%, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        linear-gradient(180deg, #fbfbfc 0%, #f8f9fb 38%, #ffffff 100%);
    }
    .contact-hero .lead{ color:var(--muted); }

    /* Sections & cards */
    .contact-section{ padding:64px 0; }
    .section-title.centered{ text-align:center; }
    .section-subtitle{ color:var(--muted); text-align:center; }
    .contact-card{
      background:var(--panel);
      border:1px solid var(--line);
      border-radius:var(--radius);
      padding:22px;
      box-shadow:var(--shadow);
      height:100%;
    }

    /* Form */
    .form-label{ font-weight:600; }
    .form-control,.form-select{
      border:1px solid var(--line);
      border-radius:10px;
    }
    .form-control:focus,.form-select:focus{
      border-color:#cfd2d8;
      box-shadow:0 0 0 .2rem rgba(0,0,0,.05);
    }
    .error-message { color:#dc2626; font-size:.875rem; margin-top:.25rem; min-height:1em; }
    .is-invalid { border-color:#dc2626 !important; }
    .form-success { color:#16a34a; }
    .form-error-global { color:#dc2626; margin-bottom:10px; display:none; }

    /* Contact info list */
    .contact-info-item{
      display:flex; gap:14px; align-items:flex-start; padding:14px 0; border-bottom:1px solid var(--line);
    }
    .contact-info-item:last-child{ border-bottom:none; }
    .contact-icon{
      width:42px; height:42px; border-radius:999px;
      display:grid; place-items:center;
      background:#fff; border:1px solid var(--line); color:#111;
      flex:0 0 auto;
    }
    .text-muted{ color:var(--muted)!important; }

    /* Social links */
    .social-links a{
      display:inline-flex; align-items:center; justify-content:center;
      width:36px; height:36px; border-radius:999px;
      background:rgba(0,0,0,.04); border:1px solid var(--line); color:#111; margin-right:8px;
      transition:background .2s ease, transform .2s ease;
    }
    .social-links a:hover{ background:#f3f4f6; transform:translateY(-2px); }

    /* Map */
    .map-container{
      height:380px; border:1px solid var(--line); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); margin-bottom:64px;
    }
    .map-container iframe{ width:100%; height:100%; border:0; display:block; }

    /* FAQ */
    .faq-section{ padding:64px 0; }
    .faq-item{
      background:#fff; border:1px solid var(--line); border-radius:var(--radius);
      padding:18px; margin-bottom:12px; box-shadow:var(--shadow);
    }
    .faq-question{ font-weight:700; display:flex; align-items:center; gap:.6rem; }
    .faq-question i{ color:#111; }

    /* Responsive tweaks */
    @media (max-width: 768px){
      .contact-hero{ padding:56px 0 40px; text-align:center; }
      .map-container{ height:260px; margin-bottom:40px; }
    }
  </style>
@endpush

@section('content')
  {{-- HERO --}}
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

  {{-- CONTACT --}}
  <section class="contact-section">
    <div class="container">
      <div class="row g-5">
        {{-- Form --}}
        <div class="col-lg-7">
          <div class="contact-card">
            <h3 class="section-title mb-4">Send us a message</h3>

            {{-- Global form error (e.g., server error) --}}
            <div id="form_global_error" class="form-error-global"></div>

            <form id="contactForm" novalidate action="#" method="POST">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="{{ old('firstName') }}">
                    <div id="firstName_error" class="error-message">@error('firstName'){{ $message }}@enderror</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="{{ old('lastName') }}">
                    <div id="lastName_error" class="error-message">@error('lastName'){{ $message }}@enderror</div>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                <div id="email_error" class="error-message">@error('email'){{ $message }}@enderror</div>
              </div>

              <div class="mb-3">
                <label for="company" class="form-label">Company (Optional)</label>
                <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                <div id="company_error" class="error-message">@error('company'){{ $message }}@enderror</div>
              </div>

              <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <select class="form-select" id="subject" name="subject">
                  <option value="" @selected(old('subject')==='') disabled>Select a subject</option>
                  <option value="sales" @selected(old('subject')==='sales')>Sales Inquiry</option>
                  <option value="support" @selected(old('subject')==='support')>Technical Support</option>
                  <option value="billing" @selected(old('subject')==='billing')>Billing Question</option>
                  <option value="partnership" @selected(old('subject')==='partnership')>Partnership Opportunity</option>
                  <option value="other" @selected(old('subject')==='other')>Other</option>
                </select>
                <div id="subject_error" class="error-message">@error('subject'){{ $message }}@enderror</div>
              </div>

              <div class="mb-4">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5">{{ old('message') }}</textarea>
                <div id="message_error" class="error-message">@error('message'){{ $message }}@enderror</div>
              </div>

              <button type="submit" class="btn btn-brand btn-lg w-100">Send Message</button>
              <div id="form_success" class="mt-3 form-success" @if(!session('contact_success'))style="display:none;"@endif>
                {{ session('contact_success') ?? 'Thank you for your message! We will get back to you soon.' }}
              </div>
            </form>
          </div>
        </div>

        {{-- Contact info --}}
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
                <p class="text-muted mb-0">+1 (555) 123-4567 (Sales)</p>
                <p class="text-muted">+1 (555) 987-6543 (Support)</p>
              </div>
            </div>

            <div class="contact-info-item">
              <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
              <div>
                <h5>Visit Us</h5>
                <p class="text-muted mb-0">123 Tech Boulevard</p>
                <p class="text-muted">San Francisco, CA 94107</p>
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

            <div class="mt-4">
              <h5 class="mb-3">Follow Us</h5>
              <div class="social-links">
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </section>

@endsection

@push('scripts')
  <script>
    // Enhanced form UX (client-side validation + optional AJAX)
    (function(){
      const form = document.getElementById('contactForm');
      if (!form) return;

      const globalError = document.getElementById('form_global_error');
      const successMsg  = document.getElementById('form_success');
      const fields = ['firstName','lastName','email','company','subject','message'];

      function clearErrors(){
        if (globalError){ globalError.style.display='none'; globalError.textContent=''; }
        if (successMsg) successMsg.style.display='none';
        fields.forEach(id=>{
          const input=document.getElementById(id);
          if (input) input.classList.remove('is-invalid');
          const err=document.getElementById(id+'_error');
          if (err) err.textContent='';
        });
      }
      function setFieldError(id,msg){
        const input=document.getElementById(id);
        const err=document.getElementById(id+'_error');
        if (input) input.classList.add('is-invalid');
        if (err) err.textContent=msg||'';
      }

      // If you want AJAX submit, uncomment below and ensure route returns JSON.
      form.addEventListener('submit', async function(e){
        // Remove this return block to enable AJAX (and prevent full-page POST):
        return; // keep normal Laravel POST/redirect/flash flow
        // ---- AJAX mode (optional) ----
        /*
        e.preventDefault();
        clearErrors();

        let ok=true;
        const data=new FormData(form);

        if (!data.get('firstName')) { setFieldError('firstName','First name is required.'); ok=false; }
        if (!data.get('lastName'))  { setFieldError('lastName','Last name is required.'); ok=false; }
        const email=data.get('email');
        if (!email){ setFieldError('email','Email is required.'); ok=false; }
        else if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)){ setFieldError('email','Please enter a valid email.'); ok=false; }
        if (!data.get('subject')) { setFieldError('subject','Subject is required.'); ok=false; }
        if (!data.get('message')) { setFieldError('message','Message is required.'); ok=false; }
        if (!ok) return;

        try{
          const res=await fetch(form.action, { method:'POST', headers: {'X-Requested-With':'XMLHttpRequest'}, body:data });
          const json=await res.json();
          if (json.success){
            form.reset();
            if (successMsg) { successMsg.textContent=json.message || 'Thank you for your message! We will get back to you soon.'; successMsg.style.display='block'; }
            return;
          }
          if (json.errors){
            Object.keys(json.errors).forEach(k=>{
              if (k==='_form'){
                if (globalError){ globalError.textContent=json.errors[k]; globalError.style.display='block'; }
              }else{
                setFieldError(k,json.errors[k]);
              }
            });
          } else {
            if (globalError){ globalError.textContent=json.error||'There was a problem sending your message.'; globalError.style.display='block'; }
          }
        } catch(err){
          if (globalError){ globalError.textContent='There was a problem sending your message.'; globalError.style.display='block'; }
        }
        */
      });
    })();
  </script>
@endpush
