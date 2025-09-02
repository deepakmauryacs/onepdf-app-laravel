@extends('vendor.layouts.app')

@section('title', 'Profile')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --surface:#ffffff; --muted:#6b7280; --line:#eaecef; --ring:#d9dde3; --chip:#f4f6f8;
    --text:#0f172a; --radius:16px; --shadow:0 10px 30px rgba(2,6,23,.08);
  }
  *{ font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; }

  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{ display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b; }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  .card-xl{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow); padding:1.5rem; }
  .avatar{ width:64px;height:64px;border-radius:50%;display:grid;place-items:center;color:#fff;font-weight:700;font-size:1.25rem;
           background:linear-gradient(180deg,#111827 0%,#0b0f1a 100%);border:3px solid #1f2937; }
  .meta{ color:#6b7280; }

  .form-label{ font-weight:600; color:#111827; }
  .form-control{ border-radius:12px; border:1px solid var(--ring); background:#fff; }
  .form-control:focus{ border-color:#9db7f9; box-shadow:0 0 0 .2rem rgba(59,130,246,.15); }

  .input-group-text{
    background:#f7f8fb; border:1px solid var(--ring);
  }
  .input-group .form-control{ border-left:0; }
  .input-group .input-icon-start{ border-right:0; border-top-left-radius:12px; border-bottom-left-radius:12px; }
  .input-group .input-icon-end{ border-left:0; border-top-right-radius:12px; border-bottom-right-radius:12px; }

  /* invalid styles */
  .is-invalid{ border-color:#dc2626 !important; box-shadow:none !important; }
  .input-group .input-icon-start.is-invalid,
  .input-group .input-icon-end.is-invalid{ border-color:#dc2626 !important; color:#dc2626 !important; }
  .invalid-feedback{ display:block; color:#dc2626; font-size:.875rem; }

  /* buttons */
  .btn-dark{ background:#000;color:#fff;border:none;border-radius:12px;padding:.6rem 1.1rem;font-weight:600; }
  .btn-dark:hover{ background:#222; }
  .btn-light{ background:#f9fafb;color:#111;border:1px solid #d1d5db;border-radius:12px;padding:.6rem 1.1rem;font-weight:600; }
  .btn-light:hover{ background:#e5e7eb; }

  .section-line{ border-top:1px solid var(--line); margin:1.25rem 0 1.5rem; }

  /* Toast background colors */
  .toast-success { background:#16a34a; color:#fff; }
  .toast-error   { background:#dc2626; color:#fff; }
  .toast-info    { background:#2563eb; color:#fff; }
  .toast-warning { background:#d97706; color:#fff; }
  .toast .toast-header { background:transparent; color:inherit; border-bottom:0; }
  .toast .btn-close { filter: invert(1); }
</style>
@endpush

@section('content')
  <div class="top-band">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between">
        <nav class="crumb">
          <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
          <i class="bi bi-chevron-right"></i>
          <span>Profile</span>
        </nav>
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="card-xl mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="avatar">
          {{ \Illuminate\Support\Str::of(auth()->user()->first_name)->substr(0,1) }}
          {{ \Illuminate\Support\Str::of(auth()->user()->last_name)->substr(0,1) }}
        </div>
        <div>
          <h3 class="mb-1" style="font-weight:700;color:var(--text);">
            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
          </h3>
          <div class="meta">
            {{ auth()->user()->email }}<br>
            {{ auth()->user()->company ?: '—' }} • {{ auth()->user()->country ?: '—' }}
          </div>
        </div>
      </div>

      <div class="section-line"></div>

      {{-- Toast container --}}
      <div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
        <div id="app-toast" class="toast border-0 shadow text-white" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
            <i id="toast-icon" class="bi me-2"></i>
            <strong id="toast-title" class="me-auto">Notification</strong>
            <small id="toast-time">just now</small>
            <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body" id="toast-body">Message goes here.</div>
        </div>
      </div>

      <h5 class="mb-3" style="font-weight:700;color:#111827;">Edit Profile</h5>

      <form id="profile-form" method="POST" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
          <!-- First Name -->
          <div class="col-md-6">
            <label class="form-label" for="first_name">First Name</label>
            <div class="input-group">
              <span class="input-group-text input-icon-start" data-for="first_name"><i class="bi bi-person"></i></span>
              <input id="first_name" type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()->first_name) }}">
              <span class="input-group-text input-icon-end end-icon d-none" data-for="first_name"><i class="bi bi-exclamation-circle"></i></span>
            </div>
            <div class="form-text">Enter your given name.</div>
            <div class="invalid-feedback" data-for="first_name"></div>
          </div>

          <!-- Last Name -->
          <div class="col-md-6">
            <label class="form-label" for="last_name">Last Name</label>
            <div class="input-group">
              <span class="input-group-text input-icon-start" data-for="last_name"><i class="bi bi-person-badge"></i></span>
              <input id="last_name" type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
              <span class="input-group-text input-icon-end end-icon d-none" data-for="last_name"><i class="bi bi-exclamation-circle"></i></span>
            </div>
            <div class="invalid-feedback" data-for="last_name"></div>
          </div>

          <!-- Company -->
          <div class="col-12">
            <label class="form-label" for="company">Company</label>
            <div class="input-group">
              <span class="input-group-text input-icon-start" data-for="company"><i class="bi bi-building"></i></span>
              <input id="company" type="text" name="company" class="form-control" value="{{ old('company', auth()->user()->company) }}">
              <span class="input-group-text input-icon-end end-icon d-none" data-for="company"><i class="bi bi-exclamation-circle"></i></span>
            </div>
            <div class="invalid-feedback" data-for="company"></div>
          </div>

          <!-- Country -->
          <div class="col-12">
            <label class="form-label" for="country">Country</label>
            <div class="input-group">
              <span class="input-group-text input-icon-start" data-for="country"><i class="bi bi-flag"></i></span>
              <input id="country" type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country) }}">
              <span class="input-group-text input-icon-end end-icon d-none" data-for="country"><i class="bi bi-exclamation-circle"></i></span>
            </div>
            <div class="invalid-feedback" data-for="country"></div>
          </div>

          <!-- Email (disabled) -->
          <div class="col-12">
            <label class="form-label" for="email">Email</label>
            <div class="input-group">
              <span class="input-group-text input-icon-start"><i class="bi bi-envelope"></i></span>
              <input id="email" type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="reset" class="btn btn-light">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </button>
          <button type="submit" class="btn btn-dark">
            <i class="bi bi-check2 me-1"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
(() => {
  const form = document.getElementById('profile-form');
  const fields = ['first_name','last_name','company','country'];

  function showToast(message,type='error'){
    const toastEl=document.getElementById('app-toast');
    const toastBody=document.getElementById('toast-body');
    const toastTitle=document.getElementById('toast-title');
    const toastIcon=document.getElementById('toast-icon');
    const toastTime=document.getElementById('toast-time');

    toastBody.textContent=message;
    toastTime.textContent='just now';

    toastEl.classList.remove('toast-success','toast-error','toast-info','toast-warning');

    switch(type){
      case 'success':
        toastTitle.textContent='Success';
        toastIcon.className='bi bi-check-circle-fill me-2';
        toastEl.classList.add('toast-success');
        break;
      case 'info':
        toastTitle.textContent='Info';
        toastIcon.className='bi bi-info-circle-fill me-2';
        toastEl.classList.add('toast-info');
        break;
      case 'warning':
        toastTitle.textContent='Warning';
        toastIcon.className='bi bi-exclamation-triangle-fill me-2';
        toastEl.classList.add('toast-warning');
        break;
      default:
        toastTitle.textContent='Error';
        toastIcon.className='bi bi-x-circle-fill me-2';
        toastEl.classList.add('toast-error');
    }

    const toast=bootstrap.Toast.getOrCreateInstance(toastEl,{delay:4000});
    toast.show();
  }

  function setError(name,msg='Required.'){
    const input=form.querySelector(`[name="${name}"]`);
    const fb=form.querySelector(`.invalid-feedback[data-for="${name}"]`);
    const startIcon=form.querySelector(`.input-icon-start[data-for="${name}"]`);
    const endIcon=form.querySelector(`.end-icon[data-for="${name}"]`);
    if(!input) return;
    input.classList.add('is-invalid');
    if(fb) fb.textContent=msg;
    if(startIcon) startIcon.classList.add('is-invalid');
    if(endIcon){ endIcon.classList.remove('d-none'); endIcon.classList.add('is-invalid'); }
  }

  function clearError(name){
    const input=form.querySelector(`[name="${name}"]`);
    const fb=form.querySelector(`.invalid-feedback[data-for="${name}"]`);
    const startIcon=form.querySelector(`.input-icon-start[data-for="${name}"]`);
    const endIcon=form.querySelector(`.end-icon[data-for="${name}"]`);
    if(!input) return;
    input.classList.remove('is-invalid');
    if(fb) fb.textContent='';
    if(startIcon) startIcon.classList.remove('is-invalid');
    if(endIcon){ endIcon.classList.add('d-none'); endIcon.classList.remove('is-invalid'); }
  }

  fields.forEach(name=>{
    const el=form.querySelector(`[name="${name}"]`);
    if(el) el.addEventListener('input',()=>clearError(name));
  });

  form.addEventListener('reset',()=>fields.forEach(clearError));

  form.addEventListener('submit',async e=>{
    e.preventDefault();
    let hasError=false;
    fields.forEach(name=>{
      const val=(form[name]?.value||'').trim();
      if(!val){ setError(name); hasError=true; }
    });
    if(hasError){ showToast('Please fix the highlighted fields.','error'); return; }

    const formData=new FormData(form);
    try{
      const res=await fetch(form.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:formData});
      const data=await res.json().catch(()=>({}));

      if(res.ok){
        fields.forEach(clearError);
        showToast(data.status||'Profile updated successfully.','success');
      }else if(res.status===422 && data?.errors){
        Object.entries(data.errors).forEach(([name,msgs])=>setError(name,msgs?.[0]||'Invalid.'));
        showToast('Please fix the highlighted fields.','error');
      }else{
        showToast((data?.message)||'An error occurred.','error');
      }
    }catch(err){
      showToast('An error occurred.','error');
    }
  });
})();
</script>
@endpush
