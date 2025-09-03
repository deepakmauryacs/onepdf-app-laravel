@extends('admin.layouts.app')

@section('title', 'Users')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --surface:#fff; --bg:#f6f7fb; --text:#0f172a; --muted:#64748b; --line:#eaeef3;
    --radius:14px; --shadow:0 10px 30px rgba(2,6,23,.06);
  }
  *{font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
  body{background:var(--bg)}
  .card{border:0;border-radius:var(--radius);box-shadow:var(--shadow)}
  .card-header{background:#fff;border-bottom:1px solid var(--line);padding:14px 16px}

  /* ===== top-band / breadcrumb ===== */
  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{ display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b; }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  .files-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
  .files-left{display:flex;align-items:center;gap:10px;flex:1 1 auto;min-width:260px}
  .files-left .folder{font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px}
  .files-left .count{display:inline-flex;min-width:26px;height:26px;padding:0 8px;border-radius:999px;background:#f0f2f7;color:#111;align-items:center;justify-content:center;font-size:.85rem;font-weight:600}
  .search-wrap{position:relative;flex:1 1 420px}
  .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted)}
  .search-input{padding-left:36px;border-radius:12px;border:1px solid var(--line);height:42px}

  table.table{margin:0}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}

  /* Pagination */
  .pagination-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination-modern{gap:8px}
  .pagination-modern .page-link{
    border:1px solid var(--line);
    background:#fff;
    color:#111;
    border-radius:12px;
    min-width:42px;height:42px;
    padding:0 12px;
    display:flex;align-items:center;justify-content:center;
    font-weight:700;
    box-shadow:0 2px 6px rgba(0,0,0,.04);
  }
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
</style>
@endpush

@section('content')
<!-- top-band breadcrumb -->
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between">
      <nav class="crumb">
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Users</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  <div class="card">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="files-left">
          <div class="folder"><i class="bi bi-people"></i> Users <span class="count">{{ $users->total() }}</span></div>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <form id="searchForm">
              <input id="searchInput" name="search" value="{{ $search }}" type="text" class="form-control search-input" placeholder="Search users...">
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th style="width:120px;">Files</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->company }}</td>
            <td><a href="{{ route('admin.users.files', $user) }}" class="btn btn-ghost btn-sm">View</a></td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted">No users found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <nav class="mt-4 pagination-wrap" aria-label="Users pagination">
    @if($users->total())
      <div class="pager-summary">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</div>
    @endif
    <div>
      {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  </nav>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function(){
  document.getElementById('searchForm').submit();
});
</script>
@endpush

