@extends('admin.layouts.app')

@section('title', 'User Plans')

@section('content')
<div class="container py-3">
  <h1 class="h3 mb-4">User Plans</h1>
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="fw-semibold"><i class="bi bi-gem"></i> All Plans</span>
      <form id="searchForm" class="d-flex" style="max-width:300px;">
        <input id="searchInput" name="search" value="{{ $search }}" type="text" class="form-control form-control-sm" placeholder="Search...">
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>User</th>
            <th>Plan</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($userPlans as $up)
          <tr>
            <td>{{ $up->user?->name }}</td>
            <td>{{ $up->plan?->name }}</td>
            <td>{{ $up->start_date }}</td>
            <td>{{ $up->end_date ?? '-' }}</td>
            <td>{{ $up->status == 1 ? 'Active' : 'Inactive' }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted">No plans found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <nav class="mt-3" aria-label="User plans pagination">
    {{ $userPlans->onEachSide(1)->links('pagination::bootstrap-5') }}
  </nav>
</div>

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function(){
  document.getElementById('searchForm').submit();
});
</script>
@endpush
@endsection

