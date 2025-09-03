@extends('vendor.layouts.app')

@section('title', 'Change Password')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --surface:#ffffff; --muted:#6b7280; --line:#eaecef; --ring:#d9dde3; --chip:#f4f6f8;
    --text:#0f172a; --radius:12px; --shadow:0 10px 30px rgba(2,6,23,.08);
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

  .card-xl{ background:var(--surface); border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow); padding:1.5rem; }

  .form-label{ font-weight:600; color:#111827; }
  .form-control{ border-radius:var(--radius); border:1px solid var(--ring); background:#fff; }
  .form-control:focus{ border-color:#9db7f9; box-shadow:0 0 0 .2rem rgba(59,130,246,.15); }

  /* input group: only LEFT segment is real; right side stays free so radius remains */
  .input-group-text{ background:#f7f8fb; border:1px solid var(--ring); }
  .input-group .input-icon-start{ border-right:0; border-top-left-radius:var(--radius); border-bottom-left-radius:var(--radius); }
  .input-group .form-control{ border-left:0; } /* keep left seam clean */

  /* overlay icons on the right (no extra segments) */
  .field-rel{ position:relative; }
  .password-eye, .invalid-icon{
    position:absolute; top:50%; transform:translateY(-50%); z-index:2;
    font-size:1rem; line-height:1; user-select:none;
  }
  .invalid-icon{ right:.6rem; color:#dc2626; display:none; }
  .password-eye{ right:.6rem; color:#6b7280; cursor:pointer; }
  .password-eye.showing{ color:#0f172a; }

  /* when an error is visible, move the eye left so both fit */
  .field-rel.has-error .invalid-icon{ display:block; }
  .field-rel.has-error .password-eye{ right:2.1rem; }

  /* invalid styles */
  .is-invalid{ border-color:#dc2626 !important; box-shadow:none !important; }
  .input-icon-start.is-invalid{ border-color:#dc2626 !important; color:#dc2626 !important; }
  .invalid-feedback{ display:block; color:#dc2626; font-size:.875rem; }

  /* buttons */
  .btn-dark{ background:#000;color:#fff;border:none;border-radius:12px;padding:.6rem 1.1rem;font-weight:600; }
  .btn-dark:hover{ background:#222; }
  .btn-light{ background:#f9fafb;color:#111;border:1px solid #d1d5db;border-radius:12px;padding:.6rem 1.1rem;font-weight:600; }
  .btn-light:hover{ background:#e5e7eb; }

  /* colored toast */
  .toast-success { background:#16a34a; color:#fff; }
  .toast-error   { background:#dc2626; color:#fff; }
  .toast-info    { background:#2563eb; color:#fff; }
  .toast-warning { background:#d97706; color:#fff; }
  .toast .toast-header{ background:transparent; color:inherit; border-bottom:0; }
  .toast .btn-close{ filter: invert(1); }
</style>
@endpush

@section('content')
  <div class="top-band">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between">
        <nav class="crumb">
          <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
          <i class="bi bi-chevron-right"></i>
          <span>Change Password</span>
        </nav>
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="card-xl mb-4">
      <h5 class="mb-3 d-flex align-items-center" style="font-weight:700;color:#111827;">
        <i class="bi bi-shield-lock me-2"></i> Change Password
      </h5>

      {{-- Toast --}}
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

      <form id="password-form" method="POST" action="{{ route('vendor.password.update') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-3">
          <!-- Current -->
          <div class="col-12">
            <label class="form-label" for="current_password">Current Password</label>
            <div class="field-rel">
              <div class="input-group">
                <span class="input-group-text input-icon-start" data-for="current_password"><i class="bi bi-lock"></i></span>
                <input type="password" name="current_password" id="current_password" class="form-control" autocomplete="current-password">
              </div>
              <i class="bi bi-eye password-eye" data-target="current_password" title="Show/Hide"></i>
              <i class="bi bi-exclamation-circle invalid-icon" data-for="current_password"></i>
            </div>
            <div class="invalid-feedback" data-for="current_password"></div>
          </div>

          <!-- New -->
          <div class="col-12">
            <label class="form-label" for="password">New Password</label>
            <div class="field-rel">
              <div class="input-group">
                <span class="input-group-text input-icon-start" data-for="password"><i class="bi bi-shield-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
              </div>
              <i class="bi bi-eye password-eye" data-target="password" title="Show/Hide"></i>
              <i class="bi bi-exclamation-circle invalid-icon" data-for="password"></i>
            </div>
            <div class="invalid-feedback" data-for="password"></div>
          </div>

          <!-- Confirm -->
          <div class="col-12">
            <label class="form-label" for="password_confirmation">Confirm Password</label>
            <div class="field-rel">
              <div class="input-group">
                <span class="input-group-text input-icon-start" data-for="password_confirmation"><i class="bi bi-check2-square"></i></span>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
              </div>
              <i class="bi bi-eye password-eye" data-target="password_confirmation" title="Show/Hide"></i>
              <i class="bi bi-exclamation-circle invalid-icon" data-for="password_confirmation"></i>
            </div>
            <div class="invalid-feedback" data-for="password_confirmation"></div>
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
  const form = document.getElementById('password-form');
  const fields = ['current_password','password','password_confirmation'];

  // Toast
  function showToast(message,type='error'){
    const el=document.getElementById('app-toast');
    const body=document.getElementById('toast-body');
    const title=document.getElementById('toast-title');
    const icon=document.getElementById('toast-icon');
    const time=document.getElementById('toast-time');

    body.textContent=message; time.textContent='just now';
    el.classList.remove('toast-success','toast-error','toast-info','toast-warning');
    switch(type){
      case 'success': title.textContent='Success'; icon.className='bi bi-check-circle-fill me-2'; el.classList.add('toast-success'); break;
      case 'info':    title.textContent='Info';    icon.className='bi bi-info-circle-fill me-2';  el.classList.add('toast-info');    break;
      case 'warning': title.textContent='Warning'; icon.className='bi bi-exclamation-triangle-fill me-2'; el.classList.add('toast-warning'); break;
      default:        title.textContent='Error';   icon.className='bi bi-x-circle-fill me-2';     el.classList.add('toast-error');
    }
    bootstrap.Toast.getOrCreateInstance(el,{delay:4000}).show();
  }

  // error helpers (add/remove .has-error on wrapper so eye shifts)
  function setError(name,msg='Required.'){
    const input=form.querySelector(`[name="${name}"]`);
    const fb=form.querySelector(`.invalid-feedback[data-for="${name}"]`);
    const startIcon=form.querySelector(`.input-icon-start[data-for="${name}"]`);
    const wrapper=input?.closest('.field-rel');
    const invalidIcon=form.querySelector(`.invalid-icon[data-for="${name}"]`);
    if(!input) return;

    input.classList.add('is-invalid');
    if(fb) fb.textContent=msg;
    if(startIcon) startIcon.classList.add('is-invalid');
    if(wrapper) wrapper.classList.add('has-error');
    if(invalidIcon) invalidIcon.style.display='block';
  }
  function clearError(name){
    const input=form.querySelector(`[name="${name}"]`);
    const fb=form.querySelector(`.invalid-feedback[data-for="${name}"]`);
    const startIcon=form.querySelector(`.input-icon-start[data-for="${name}"]`);
    const wrapper=input?.closest('.field-rel');
    const invalidIcon=form.querySelector(`.invalid-icon[data-for="${name}"]`);
    if(!input) return;

    input.classList.remove('is-invalid');
    if(fb) fb.textContent='';
    if(startIcon) startIcon.classList.remove('is-invalid');
    if(wrapper) wrapper.classList.remove('has-error');
    if(invalidIcon) invalidIcon.style.display='none';
  }

  fields.forEach(name=>{
    const el=form.querySelector(`[name="${name}"]`);
    if(el) el.addEventListener('input',()=>clearError(name));
  });

  // show/hide eye
  document.querySelectorAll('.password-eye').forEach(eye=>{
    eye.addEventListener('click',()=>{
      const id=eye.getAttribute('data-target');
      const input=document.getElementById(id);
      if(!input) return;
      const isPwd=input.type==='password';
      input.type = isPwd ? 'text' : 'password';
      eye.classList.toggle('showing',isPwd);
      eye.classList.toggle('bi-eye');
      eye.classList.toggle('bi-eye-slash');
    });
  });

  form.addEventListener('reset',()=>fields.forEach(clearError));

  form.addEventListener('submit', async e=>{
    e.preventDefault();

    let hasError=false;
    const cur=(form.current_password.value||'').trim();
    const pwd=(form.password.value||'').trim();
    const conf=(form.password_confirmation.value||'').trim();

    if(!cur){ setError('current_password','Current password is required.'); hasError=true; }
    if(!pwd){ setError('password','New password is required.'); hasError=true; }
    if(!conf){ setError('password_confirmation','Please confirm your new password.'); hasError=true; }
    if(pwd && pwd.length<8){ setError('password','Password must be at least 8 characters.'); hasError=true; }
    if(pwd && conf && pwd!==conf){ setError('password_confirmation','Passwords do not match.'); hasError=true; }

    if(hasError){ showToast('Please fix the highlighted fields.','error'); return; }

    const formData=new FormData(form);
    try{
      const res=await fetch(form.action,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:formData});
      const data=await res.json().catch(()=>({}));
      if(res.ok){
        fields.forEach(clearError);
        form.reset();
        showToast(data.status||data.message||'Password updated successfully.','success');
      }else if(res.status===422 && data?.errors){
        Object.entries(data.errors).forEach(([n,msgs])=>setError(n, Array.isArray(msgs)?msgs[0]:String(msgs)));
        showToast('Please fix the highlighted fields.','error');
      }else if(res.status===401 || res.status===403){
        showToast(data?.message||'You are not authorized.','error');
      }else{
        showToast(data?.message||'Something went wrong.','error');
      }
    }catch(_){
      showToast('Network error. Please try again.','error');
    }
  });
})();
</script>
@endpush
