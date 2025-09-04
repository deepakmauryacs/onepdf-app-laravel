@extends('vendor.layouts.app')

@section('title', 'Leads')

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
  .crumb{display:flex;align-items:center;gap:.5rem;font-size:.95rem;color:#64748b}
  .crumb a{color:#0f172a;text-decoration:none}
  .crumb i{opacity:.6}

  /* Toolbar */
  .leads-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
  .leads-left{display:flex;align-items:center;gap:10px;flex:1 1 auto;min-width:260px}
  .leads-left .folder{font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px}
  .leads-left .count{display:inline-flex;min-width:26px;height:26px;padding:0 8px;border-radius:999px;background:#f0f2f7;color:#111;align-items:center;justify-content:center;font-size:.85rem;font-weight:600}
  .search-wrap{position:relative;flex:1 1 420px}
  .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted)}
  .search-input{padding-left:36px;border-radius:12px;border:1px solid var(--line);height:42px}

  table.table{margin:0}
  .table td, .table th{vertical-align:middle}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}

  /* S.No column */
  .col-sno{width:76px;text-align:center}
  .td-sno{text-align:center;font-weight:600;color:#0f172a}

  /* Date column */
  .col-date{width:180px;white-space:nowrap}

  /* Pagination */
  .pagination-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination{gap:8px}
  .pagination .page-link{
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
  .pagination .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
</style>
@endpush

@section('content')
<!-- top-band breadcrumb -->
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between">
      <nav class="crumb">
        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Leads</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  <div class="card">
    <div class="card-header">
      <div class="leads-toolbar">
        <div class="leads-left">
          <div class="folder"><i class="bi bi-people"></i> Leads <span class="count">{{ $leads->total() }}</span></div>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <form id="searchForm">
              <input id="searchInput" name="search" value="{{ $search }}" type="text" class="form-control search-input" placeholder="Search leads...">
            </form>
          </div>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <button id="exportBtn" class="btn btn-outline-primary btn-sm">Export</button>
          <button id="bulkDeleteBtn" form="bulkDeleteForm" class="btn btn-outline-danger btn-sm">Delete Selected</button>
        </div>
      </div>
      <div id="exportProgress" class="progress mt-2 d-none">
        <div class="progress-bar" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>

    <div class="table-responsive">
      <form id="bulkDeleteForm" method="POST" action="{{ route('vendor.leads.bulkDestroy') }}">
        @csrf
        @method('DELETE')
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th class="col-sno">S.No</th>
              <th>Name</th>
              <th>Email</th>
              <th>Document</th>
              <th>Form</th>
              <th class="col-date">Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($leads as $lead)
              <tr>
                <td><input type="checkbox" class="row-check" name="ids[]" value="{{ $lead->id }}"></td>
                <td class="td-sno">
                  {{ ($leads->firstItem() ?? 0) + $loop->index }}
                </td>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->email }}</td>
                <td>{{ optional($lead->document)->filename }}</td>
                <td>{{ optional($lead->leadForm)->name }}</td>
                <td>{{ $lead->created_at->format('Y-m-d H:i') }}</td>
                <td>
                  <form method="POST" action="{{ route('vendor.leads.destroy', $lead) }}" onsubmit="return confirm('Delete this lead?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm text-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-4">No leads found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </form>
    </div>
  </div>

  <nav class="mt-4 pagination-wrap" aria-label="Leads pagination">
    @if($leads->total())
      <div class="pager-summary">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</div>
    @endif
    <div>
      {{ $leads->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  </nav>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('searchInput').addEventListener('input', function(){
  document.getElementById('searchForm').submit();
});

document.getElementById('selectAll').addEventListener('change', function(){
  document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});

document.getElementById('exportBtn').addEventListener('click', function(e){
  e.preventDefault();
  const barWrap = document.getElementById('exportProgress');
  const bar = barWrap.querySelector('.progress-bar');
  bar.style.width = '0%';
  barWrap.classList.remove('d-none');
  let progress = 0;
  const timer = setInterval(() => {
    progress = Math.min(progress + 10, 90);
    bar.style.width = progress + '%';
  }, 200);

  fetch('{{ route('vendor.leads.export') }}')
    .then(r => r.blob())
    .then(blob => {
      clearInterval(timer);
      bar.style.width = '100%';
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'leads.csv';
      document.body.appendChild(a);
      a.click();
      a.remove();
      setTimeout(() => {
        barWrap.classList.add('d-none');
      }, 500);
    });
});
</script>
@endpush
