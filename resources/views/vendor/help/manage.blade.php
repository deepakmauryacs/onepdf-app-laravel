@extends('vendor.layouts.app')

@section('title', 'Help Requests')

@push('styles')
<style>
  :root{
    --bg:#f6f7fb; --surface:#fff; --text:#0f172a; --muted:#64748b; --line:#eaeef3;
    --radius:14px;
  }

  .top-band{
    background:
      radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
      linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{display:flex;align-items:center;gap:.5rem;font-size:.95rem;color:#64748b}
  .crumb a{color:#0f172a;text-decoration:none}

  .card{border:1px solid var(--line); border-radius:var(--radius)}
  .card-header{background:#fff; border-bottom:1px solid var(--line)}

  /* Toolbar (like Files page) */
  .files-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
  .files-left{display:flex;align-items:center;gap:10px;flex:1 1 auto;min-width:260px}
  .files-left .folder{font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px}
  .files-left .count{display:inline-flex;min-width:26px;height:26px;padding:0 8px;border-radius:999px;
    background:#f0f2f7;color:#111;align-items:center;justify-content:center;font-size:.85rem;font-weight:700}
  .search-wrap{position:relative;flex:1 1 420px}
  .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted)}
  .search-input{padding-left:36px;border-radius:12px;border:1px solid var(--line);height:42px}

  /* Table */
  .table td,.table th{vertical-align:middle}
  thead th{color:#475569;font-weight:700;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}
  .col-subject{display:flex;align-items:center;gap:12px}
  .file-chip{display:inline-flex;width:36px;height:36px;border-radius:10px;background:#eef2ff;color:#334155;
    align-items:center;justify-content:center;font-size:18px;border:1px solid #e5e7eb}
  .status-pill{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;
    background:#f8fafc;color:#0f172a;border:1px solid #e5e7eb;height:32px;padding:0 14px;font-weight:600}

  /* === Buttons: BLACK & WHITE theme === */
  .btn-primary{
    background:#111 !important; border-color:#111 !important; color:#fff !important;
    border-radius:12px; font-weight:700;
  }
  .btn-primary:hover{ background:#000 !important; border-color:#000 !important }
  .btn-primary:focus{ box-shadow:0 0 0 .2rem rgba(0,0,0,.15) !important }

  .btn-outline-primary{
    color:#111 !important; border-color:#111 !important; background:#fff !important;
    border-radius:12px; font-weight:600;
  }
  .btn-outline-primary:hover{ background:#f2f4f7 !important; color:#111 !important; border-color:#111 !important }
  .btn-outline-primary:focus{ box-shadow:0 0 0 .2rem rgba(0,0,0,.08) !important }

  /* Pagination (modern, centered) */
  .pagination-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination-modern{gap:8px}
  .pagination-modern .page-link{
    border:1px solid var(--line); background:#fff; color:#111;
    border-radius:12px; min-width:42px; height:42px; padding:0 12px;
    display:flex; align-items:center; justify-content:center; font-weight:700;
    box-shadow:0 2px 6px rgba(0,0,0,.04)
  }
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}

  /* Modal — neutral like your permissions modal */
  .help-modal .modal-content{border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 24px 60px rgba(2,6,23,.15)}
  .help-modal .modal-header{background:#f8fafc;border-bottom:1px solid #e5e7eb;border-top-left-radius:16px;border-top-right-radius:16px}
  .help-modal .modal-title{display:flex;align-items:center;gap:.6rem;font-weight:700;color:#0f172a}
  .help-modal .title-ico{width:28px;height:28px;border-radius:999px;display:grid;place-items:center;background:#eef2ff;border:1px solid #e5e7eb;color:#334155}
  .help-modal .modal-footer{background:#fff;border-top:1px solid #e5e7eb;border-bottom-left-radius:16px;border-bottom-right-radius:16px}
  .help-modal .btn-cancel{background:#fff;border:1px solid #e5e7eb;border-radius:12px;font-weight:600}
  .help-modal .btn-cancel:hover{background:#f3f4f6}
  .help-modal .btn-save{background:#111;color:#fff;border:1px solid #111;border-radius:12px;font-weight:700}
  .help-modal .btn-save:hover{background:#000}
  .help-modal .form-control,.help-modal .form-select{border:1px solid #e5e7eb;border-radius:12px}
  .help-modal .form-control:focus{border-color:#111;box-shadow:0 0 0 .15rem rgba(0,0,0,.08)}
</style>
@endpush

@section('content')
<div class="top-band">
  <div class="container py-3">
    <nav class="crumb">
      <a href="{{ route('vendor.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>Help</span>
    </nav>
  </div>
</div>

<div class="container py-3">
  <div class="card">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="files-left">
          <div class="folder"><i class="bi bi-life-preserver"></i> Help <span id="countBadge" class="count">0</span></div>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input id="searchInput" type="text" class="form-control search-input" placeholder="Search requests...">
          </div>
        </div>
        <button id="btnNew" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Request</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table mb-0 align-middle" id="helpTable">
        <thead>
          <tr>
            <th style="width:42px;"><input class="form-check-input" type="checkbox" disabled></th>
            <th>Subject</th>
            <th style="width:180px;">Message</th>
            <th style="width:140px;">Status</th>
            <th style="width:180px;">Created</th>
            <th style="width:100px;">Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div id="empty" class="p-3 text-center text-muted d-none">No requests found.</div>
    </div>
  </div>

  <nav class="mt-3 pagination-wrap" aria-label="Help pagination">
    <ul class="pagination pagination-modern justify-content-center" id="pager"></ul>
    <div id="pagerSummary" class="pager-summary d-none"></div>
  </nav>
</div>

<!-- New Request Modal -->
<div class="modal fade help-modal" id="requestModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="helpForm" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title"><span class="title-ico"><i class="bi bi-life-preserver"></i></span> New Help Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input name="subject" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-1">
          <label class="form-label">Attachment (optional)</label>
          <input name="attachment" type="file" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-save" id="btnSaveReq">Create</button>
      </div>
    </form>
  </div>
</div>

<!-- View Request Modal -->
<div class="modal fade help-modal" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><span class="title-ico"><i class="bi bi-eye"></i></span> View Help Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <div id="viewSubject"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <div id="viewMessage"></div>
        </div>
        <div class="mb-0">
          <label class="form-label">Status</label>
          <div id="viewStatus"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const routes = {
    list: @json(route('vendor.help.manage.list')),
    store: @json(route('vendor.help.store')),
  };

  let page = 1, q = '';
  const tbody = document.querySelector('#helpTable tbody');
  const empty = document.getElementById('empty');
  const pager = document.getElementById('pager');
  const pagerSummary = document.getElementById('pagerSummary');
  const countBadge = document.getElementById('countBadge');

  const requestModal = new bootstrap.Modal('#requestModal');
  const viewModal = new bootstrap.Modal('#viewModal');

  function escapeHtml(s){return (s??'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
  function escapeAttr(s){return escapeHtml(s).replace(/"/g,'&quot;').replace(/'/g,'&#39;');}

  function statusPill(s){
    const label = (s||'').toString().trim() || '—';
    return `<span class="status-pill">${escapeHtml(label)}</span>`;
  }

  function rowTemplate(r){
    const msg = (r.message || '').toString();
    const shortMsg = msg.length > 15 ? msg.slice(0,15) + '...' : msg;
    return `<tr>
      <td><input class="form-check-input" type="checkbox" disabled></td>
      <td>
        <div class="col-subject">
          <span class="file-chip"><i class="bi bi-chat-dots"></i></span>
          <div class="text-truncate" title="${escapeHtml(r.subject)}">${escapeHtml(r.subject)}</div>
        </div>
      </td>
      <td>${escapeHtml(shortMsg)} <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="${escapeAttr(msg)}"></i></td>
      <td>${statusPill(r.status)}</td>
      <td>${escapeHtml(r.created_at || '')}</td>
      <td><button class="btn btn-sm btn-outline-primary view-btn"
                  data-subject="${encodeURIComponent(r.subject || '')}"
                  data-message="${encodeURIComponent(msg)}"
                  data-status="${encodeURIComponent(r.status || '')}">
            <i class="bi bi-eye"></i>
          </button></td>
    </tr>`;
  }

  function initRowHandlers(){
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('viewSubject').textContent = decodeURIComponent(btn.dataset.subject || '');
        document.getElementById('viewMessage').textContent = decodeURIComponent(btn.dataset.message || '');
        document.getElementById('viewStatus').textContent = decodeURIComponent(btn.dataset.status || '');
        viewModal.show();
      });
    });
  }

  function renderRows(list,total){
    tbody.innerHTML='';
    if(!list || !list.length){
      empty.classList.remove('d-none');
      countBadge.textContent = '0';
      return;
    }
    empty.classList.add('d-none');
    tbody.innerHTML = list.map(rowTemplate).join('');
    initRowHandlers();
    countBadge.textContent = total ?? list.length;
  }

  function renderPager(current,last,total=null,perPage=null){
    pager.innerHTML='';
    if(total!=null && perPage!=null){
      const start=(current-1)*perPage+1;
      const end=Math.min(total,current*perPage);
      pagerSummary.classList.remove('d-none');
      pagerSummary.textContent=`Showing ${start.toLocaleString()}–${end.toLocaleString()} of ${total.toLocaleString()}`;
    }else{
      pagerSummary.classList.add('d-none');
    }
    if(last<=1) return;

    for(let i=1;i<=last;i++){
      const li=document.createElement('li');
      li.className='page-item'+(i===current?' active':'');
      li.innerHTML=`<a class="page-link" href="#" data-page="${i}">${i}</a>`;
      pager.appendChild(li);
    }
  }

  async function load(p=1){
    page=p;
    const url = new URL(routes.list, window.location.origin);
    url.searchParams.set('page', p);
    url.searchParams.set('search', q);
    const res = await fetch(url.toString());
    const data = await res.json();
    renderRows(data.requests||[], data.total||0);
    renderPager(data.current_page||1, data.last_page||1, data.total||null, data.per_page||null);
  }

  pager.addEventListener('click', e=>{
    const a=e.target.closest('a[data-page]'); if(!a) return;
    e.preventDefault();
    load(parseInt(a.dataset.page,10));
  });

  document.getElementById('searchInput').addEventListener('input', e=>{
    q = e.target.value || '';
    load(1);
  });

  document.getElementById('btnNew').addEventListener('click', ()=> {
    document.getElementById('helpForm').reset();
    requestModal.show();
  });

  document.getElementById('helpForm').addEventListener('submit', async e=>{
    e.preventDefault();
    const btn = document.getElementById('btnSaveReq');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving';
    try{
      const res = await fetch(routes.store, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:new FormData(e.target)
      });
      if(!res.ok) throw 0;
      requestModal.hide();
      await load(page);
    }finally{
      btn.disabled = false;
      btn.innerHTML = 'Create';
    }
  });

  load();
})();
</script>
@endpush
