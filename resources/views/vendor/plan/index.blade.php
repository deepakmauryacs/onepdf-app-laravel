@extends('vendor.layouts.app')

@section('title', 'Plan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{--bg:#f6f7fb;--surface:#fff;--text:#0f172a;--muted:#64748b;--line:#eaecef;}
  .top-band{background:radial-gradient(1200px 220px at 50% -140px,rgba(59,130,246,.18)0%,rgba(59,130,246,0)60%),linear-gradient(180deg,#f6f7fb0%,#f6f7fb60%,transparent100%);border-bottom:1px solid var(--line);}
  .crumb{display:flex;align-items:center;gap:.5rem;color:var(--muted);}
  .crumb a{color:var(--text);text-decoration:none;}
  .crumb i{opacity:.6;}
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
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#upgradeModal">
          <i class="bi bi-arrow-up-circle me-1"></i> Upgrade Plan
        </button>
      </div>
    </div>
  </div>

  <div class="modal fade" id="upgradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <form id="planForm" class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i> Upgrade Your Plan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">
            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
            Choose a plan that suits your needs.
          </p>
          <div class="mb-3">
            <label for="planSelect" class="form-label fw-bold">
              <i class="bi bi-gem text-info me-1"></i> Available Plans
            </label>
            <select class="form-select" id="planSelect" name="plan_id" required>
              <option value="">-- Select a plan --</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->usd_price }} / {{ $plan->billing_cycle }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </button>
          <button type="submit" id="btnPlanSave" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i> Save & Upgrade
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

    const res = await fetch('{{ route('vendor.plan.update') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: new FormData(this)
    });

    if (res.ok) {
      location.reload();
    } else {
      alert('Failed to update plan');
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Save & Upgrade';
    }
  });
</script>
@endpush
