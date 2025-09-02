@extends('vendor.layouts.app')

@section('title', 'Profile')

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

  .avatar{
    width:64px; height:64px; border-radius:50%;
    display:grid; place-items:center; color:#fff; font-weight:700; font-size:1.25rem;
    background: linear-gradient(180deg, #111827 0%, #0b0f1a 100%);
    border: 3px solid #1f2937;
  }

  .meta{ color:#6b7280; }

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

  /* Black & White buttons */
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

      @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
      @endif

      <h5 class="mb-3" style="font-weight:700;color:#111827;">Edit Profile</h5>

      <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control"
                   value="{{ old('first_name', auth()->user()->first_name) }}">
            <div class="form-text">Enter your given name.</div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control"
                   value="{{ old('last_name', auth()->user()->last_name) }}">
          </div>

          <div class="col-12">
            <label class="form-label">Company</label>
            <input type="text" name="company" class="form-control"
                   value="{{ old('company', auth()->user()->company) }}">
          </div>

          <div class="col-12">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control"
                   value="{{ old('country', auth()->user()->country) }}">
          </div>

          <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
          </div>
        </div>

        <!-- Buttons aligned right -->
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
