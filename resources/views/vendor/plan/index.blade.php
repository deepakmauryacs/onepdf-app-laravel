@extends('vendor.layouts.app')

@section('title', 'Plan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#f6f7fb; --surface:#ffffff; --text:#0f172a; --muted:#6b7280; --line:#e5e7eb;
    --radius:12px;
  }
  *{font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}

  /* Top band */
  .top-band{background:linear-gradient(180deg,var(--bg) 0%,var(--bg) 60%,transparent 100%);border-bottom:1px solid var(--line)}
  .crumb{display:flex;align-items:center;gap:.5rem;color:var(--muted)}
  .crumb a{color:var(--text);text-decoration:none}
  .crumb i{opacity:.6}

  /* Card */
  .card{border:1px solid var(--line); border-radius:var(--radius)}

  /* Select */
  .form-select{border-radius:12px; border:1px solid var(--line)}
  .form-select:focus{border-color:#111; box-shadow:0 0 0 .15rem rgba(0,0,0,.08)}

  /* ===== Modal (match permissions screenshot style) ===== */
  .plan-modal .modal-content{
    border-radius:16px; border:1px solid #e5e7eb;
    box-shadow:0 24px 60px rgba(2,6,23,.15);
  }
  .plan-modal .modal-header{
    background:#f8fafc; border-bottom:1px solid #e5e7eb;
    border-top-left-radius:16px; border-top-right-radius:16px;
  }
  .plan-modal .modal-title{
    display:flex; align-items:center; gap:.5rem; font-weight:700; color:#0f172a;
  }
  .plan-modal .title-ico{
    width:28px;height:28px;border-radius:999px;display:grid;place-items:center;
    background:#eef2ff; color:#334155; border:1px solid #e5e7eb; font-size:14px;
  }
  .plan-modal .btn-close{opacity:.6}
  .plan-modal .btn-close:hover{opacity:1}

  .plan-modal .modal-footer{
    background:#fff; border-top:1px solid #e5e7eb;
    border-bottom-left-radius:16px; border-bottom-right-radius:16px;
  }
  .plan-modal .btn-cancel{
    background:#fff; color:#0f172a; border:1px solid #e5e7eb; border-radius:12px;
    font-weight:600; padding:.5rem 1rem;
  }
  .plan-modal .btn-cancel:hover{background:#f3f4f6}

  .plan-modal .btn-save{
    background:#fff; color:#0f172a; border:1px solid #e5e7eb; border-radius:12px;
    font-weight:700; padding:.5rem 1rem; display:inline-flex; align-items:center; gap:.5rem;
  }
  .plan-modal .btn-save:hover{background:#f3f4f6}
  .plan-modal .spark{
    width:22px;height:22px;border-radius:999px;display:inline-grid;place-items:center;
    background:#eef2ff; border:1px solid #dbeafe; color:#4f46e5; font-size:12px;
  }
</style>
@endpush

@section('content')
  <div class="top-band">
    <div class="container py-3">
      <nav class="crumb">
        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Plan</span>
      </nav>
    </div>
  </div>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">
        @if($currentPlan)
          <p>Your current plan: <strong>{{ $currentPlan->plan->name }}</strong></p>
          <p>Price: ${{ $currentPlan->plan->usd_price }} / {{ $currentPlan->plan->billing_cycle }}</p>
          @if($currentPlan->end_date)
            <p>Expires on: {{ $currentPlan->end_date }}</p>
          @endif
        @else
          <p>You are currently on the <strong>Free</strong> plan.</p>
        @endif
        <button class="btn btn-outline-dark mt-3" data-bs-toggle="modal" data-bs-target="#upgradeModal">
          <i class="bi bi-arrow-up-circle me-1"></i> Upgrade Plan
        </button>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade plan-modal" id="upgradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="planForm" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <span class="title-ico"><i class="bi bi-box-seam"></i></span>
            Upgrade Your Plan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <p class="text-muted mb-3">Choose a plan that suits your needs.</p>

          <div class="mb-3">
            <label for="planSelect" class="form-label fw-bold">
              <i class="bi bi-gem me-1"></i> Available Plans
            </label>
            <select class="form-select" id="planSelect" name="plan_id" required>
              <option value="">-- Select a plan --</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->usd_price }} / {{ $plan->billing_cycle }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="btnPlanSave" class="btn-save">
            <span class="spark"><i class="bi bi-stars"></i></span>
            Save & Upgrade
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  document.getElementById('planForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnPlanSave');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...';

    try{
      const res = await fetch('{{ route('vendor.plan.update') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: new FormData(this)
      });
      if (res.ok) {
        location.reload();
      } else {
        throw new Error();
      }
    }catch{
      alert('Failed to update plan');
      btn.disabled = false;
      btn.innerHTML = '<span class="spark"><i class="bi bi-stars"></i></span> Save & Upgrade';
    }
  });
</script>
@endpush
