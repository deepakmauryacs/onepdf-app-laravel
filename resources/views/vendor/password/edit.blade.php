@extends('vendor.layouts.app')

@section('title', 'Change Password')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --surface: #ffffff;
    --muted: #6b7280;
    --line: #eaecef;
    --ring: #d9dde3;
    --chip: #f4f6f8;
    --text: #0f172a;
    --radius: 16px;
    --shadow: 0 10px 30px rgba(2,6,23,.08);
  }
  *{ font-family: "DM Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }

  /* Top band */
  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg, #f6f7fb 0%, #f6f7fb 60%, transparent 100%);
    border-bottom: 1px solid var(--line);
  }

  .crumb{
    display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b;
  }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  .chip{
    display:inline-flex; align-items:center; gap:.5rem;
    background: var(--chip);
    border:1px solid var(--ring);
    border-radius: 999px;
    padding: .45rem .8rem;
    font-weight:500; color:#0f172a;
  }
  .chip .bi{ opacity:.7; }

  .card-xl{
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.5rem;
  }

  .form-label{ font-weight:600; color:#111827; }
  .form-control{
    border-radius: 12px;
    border:1px solid var(--ring);
    background:#fff;
  }
  .form-control:focus{
    border-color:#9db7f9;
    box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
  }
  .form-text{ color:#94a3b8; }

  .btn-dark {
    background:#000; color:#fff; border:none;
    border-radius:12px; padding:.6rem 1.1rem; font-weight:600;
  }
  .btn-dark:hover { background:#222; color:#fff; }

  .btn-light {
    background:#f9fafb; color:#111;
    border:1px solid #d1d5db;
    border-radius:12px; padding:.6rem 1.1rem; font-weight:600;
  }
  .btn-light:hover { background:#e5e7eb; color:#111; }

  .section-line{ border-top:1px solid var(--line); margin:1.25rem 0 1.5rem; }

  .input-group-password { position:relative; }
  .toggle-password { position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; }
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
      @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
      @endif

      <h5 class="mb-3" style="font-weight:700;color:#111827;">Change Password</h5>

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Current Password</label>
            <div class="input-group-password">
              <input type="password" name="current_password" id="current_password" class="form-control" required>
              <i class="bi bi-eye toggle-password" data-target="current_password"></i>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">New Password</label>
            <div class="input-group-password">
              <input type="password" name="password" id="password" class="form-control" required>
              <i class="bi bi-eye toggle-password" data-target="password"></i>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Confirm Password</label>
            <div class="input-group-password">
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
              <i class="bi bi-eye toggle-password" data-target="password_confirmation"></i>
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
  document.querySelectorAll('.toggle-password').forEach(function(btn){
    btn.addEventListener('click', function(){
      const target = document.getElementById(this.dataset.target);
      const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
      target.setAttribute('type', type);
      this.classList.toggle('bi-eye');
      this.classList.toggle('bi-eye-slash');
    });
  });
</script>
@endpush
