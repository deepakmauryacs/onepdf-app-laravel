@extends('vendor.layouts.app')

@section('title', 'Lead Forms')

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

  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{display:flex;align-items:center;gap:.5rem;font-size:.95rem;color:#64748b}
  .crumb a{color:#0f172a;text-decoration:none}
  .crumb i{opacity:.6}

  /* Controls (same height) */
  .search-wrap{position:relative;flex:1 1 420px}
  .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted)}
  .search-input{padding-left:36px;border-radius:12px;border:1px solid var(--line);height:42px}

  .control-42{
    height:42px !important;
    padding:0 14px !important;
    border-radius:12px !important;
    line-height:1 !important;
    display:inline-flex;align-items:center;gap:8px;
    transition:background .15s ease, border-color .15s ease, box-shadow .15s ease, transform .15s ease;
  }

  .forms-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
  .forms-left{display:flex;align-items:center;gap:10px;flex:1 1 auto;min-width:260px}
  .forms-left .folder{font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px}
  .forms-left .count{display:inline-flex;min-width:26px;height:26px;padding:0 8px;border-radius:999px;background:#f0f2f7;color:#111;align-items:center;justify-content:center;font-size:.85rem;font-weight:600}

  .btn-danger-soft{background:#ffecec;border:1px solid #ffd0d0;color:#b42318;border-radius:12px}
  .btn-danger-soft:hover{background:#ffdede !important;border-color:#ffbcbc !important;color:#9a1b12 !important;transform:translateY(-1px)}
  .btn-danger-soft:disabled{opacity:.6;cursor:not-allowed}

  /* Ghost button */
  .btn-ghost,.btn-icon{display:inline-flex;align-items:center;justify-content:center;height:42px;border-radius:12px;line-height:1;font-weight:600;text-decoration:none}
  .btn-ghost{border:1px solid var(--line);background:#fff;padding:0 14px}
  .btn-ghost .bi{margin-right:8px}
  .btn-ghost:hover,
  .btn-ghost:focus{background:#f7f9fc !important;border-color:#dfe3ea !important;box-shadow:0 2px 10px rgba(2,6,23,.06) !important;transform:translateY(-1px)}

  table.table{margin:0}
  .table td, .table th{vertical-align:middle}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff;padding:14px 16px}
  tbody td{border-color:var(--line);padding:14px 16px}

  /* Force visible row hover */
  .table-hover>tbody>tr:hover>td,
  .table-hover>tbody>tr:hover>th{
    background-color:#f7f9fc !important;
  }

  .form-name{font-weight:600;color:var(--text)}
  .actions-cell{display:flex;flex-direction:column;justify-content:center;gap:8px}
  .actions{display:flex;align-items:center;gap:8px;flex-wrap:nowrap;white-space:nowrap}

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
    transition:transform .15s ease, background .15s ease;
  }
  .pagination-modern .page-link:hover{background:#f2f4f7 !important;transform:translateY(-1px)}
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
</style>
@endpush

@section('content')
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between">
      <nav class="crumb">
        <a href="{{ route('vendor.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Lead Forms</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  <!-- Quick add -->
  <form id="addForm" method="post" action="{{ route('vendor.lead_forms.store') }}" class="mb-3 d-flex gap-2">
    @csrf
    <input type="text" name="name" class="form-control control-42" placeholder="Form name" required>
    <button class="btn btn-primary control-42" type="submit"><i class="bi bi-plus-lg"></i> Add</button>
  </form>

  <form method="post" action="{{ route('vendor.lead_forms.destroy') }}" id="deleteForm">
    @csrf
    @method('DELETE')
    <div class="card">
      <div class="card-header">
        <div class="forms-toolbar">
          <div class="forms-left">
            <div class="folder"><i class="bi bi-list-task"></i> Forms <span id="formCount" class="count">{{ $forms->count() }}</span></div>
            <div class="search-wrap">
              <i class="bi bi-search"></i>
              <input id="searchInput" type="text" class="form-control search-input" placeholder="Search forms...">
            </div>
          </div>
          <button type="submit" id="btnDeleteSelected" class="btn btn-danger-soft control-42" disabled>
            <i class="bi bi-trash"></i> <span class="d-none d-sm-inline">Delete selected</span>
          </button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle" id="formsTable">
          <thead>
            <tr>
              <th style="width:46px;"><input id="checkAll" class="form-check-input" type="checkbox" /></th>
              <th>Name</th>
              <th style="width:180px;">Created</th>
              <th style="width:140px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($forms as $form)
              <tr>
                <td><input type="checkbox" class="form-check-input row-check" name="ids[]" value="{{ $form->id }}" /></td>
                <td class="form-name">{{ $form->name }}</td>
                <td>{{ $form->created_at->format('Y-m-d H:i') }}</td>
                <td class="actions-cell">
                  <div class="actions">
                    <a href="{{ route('vendor.lead_forms.edit', $form) }}" class="btn btn-ghost control-42"><i class="bi bi-pencil"></i>Edit</a>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="emptyRow">
                <td colspan="4" class="text-center py-4">No forms found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const searchInput = document.getElementById('searchInput');
  const rows = Array.from(document.querySelectorAll('#formsTable tbody tr'));
  const countSpan = document.getElementById('formCount');
  const emptyRow = document.getElementById('emptyRow');

  function filter(){
    const q = (searchInput?.value || '').toLowerCase();
    let visible = 0;
    rows.forEach(row => {
      if (row === emptyRow) return;
      const nameCell = row.querySelector('.form-name');
      if (!nameCell) return;
      const show = nameCell.textContent.toLowerCase().includes(q);
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    if (emptyRow){ emptyRow.style.display = visible ? 'none' : ''; }
    if (countSpan){ countSpan.textContent = visible; }
  }
  searchInput?.addEventListener('input', filter);
  filter();

  // Bulk select + enable/disable delete button
  const checkAll = document.getElementById('checkAll');
  const deleteBtn = document.getElementById('btnDeleteSelected');
  const rowChecks = () => Array.from(document.querySelectorAll('#formsTable tbody .row-check'));

  function updateDeleteState(){
    const anyChecked = rowChecks().some(cb => cb.checked);
    if (deleteBtn) deleteBtn.disabled = !anyChecked;
  }

  checkAll?.addEventListener('change', () => {
    rowChecks().forEach(cb => cb.checked = checkAll.checked);
    updateDeleteState();
  });
  rowChecks().forEach(cb => cb.addEventListener('change', updateDeleteState));
  updateDeleteState();
})();
</script>
@endpush
