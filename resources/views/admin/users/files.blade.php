@extends('admin.layouts.app')

@section('title', 'User Files')

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
  .table td, .table th{vertical-align:middle}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}
  .col-file{display:flex;align-items:center;gap:12px}
  .file-chip{display:inline-flex;width:36px;height:36px;border-radius:10px;background:#f3f6fb;color:#0b5ed7;align-items:center;justify-content:center;font-size:18px}
  .file-name a{color:#0f172a;text-decoration:none;font-weight:600}
  .status-pill{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:#eff8ff;color:#0b5ed7;border:1px solid #d7e6ff;height:34px;padding:0 14px;font-weight:600}

  /* ===== Modern centered pagination ===== */
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
  .pagination-modern .ellipsis > .page-link{pointer-events:none}
  .pagination-modern .page-link .bi{margin:0;font-size:16px}
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
        <a href="{{ route('admin.users.index') }}">Users</a>
        <i class="bi bi-chevron-right"></i>
        <span>{{ $user->name }}</span>
        <i class="bi bi-chevron-right"></i>
        <span>Files</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  <div class="card">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="files-left">
          <div class="folder"><i class="bi bi-folder2-open"></i> Files <span id="fileCount" class="count">0</span></div>
          <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input id="searchInput" type="text" class="form-control search-input" placeholder="Search files...">
          </div>
        </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table align-middle" id="filesTable">
          <thead>
            <tr>
              <th>File Name</th>
              <th style="width:140px;">Size</th>
              <th style="width:180px;">Modified</th>
              <th style="width:140px;">Status</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div id="emptyState" class="p-4 text-center text-muted d-none">No files found.</div>
    </div>
  </div>

  <!-- Centered pagination -->
  <nav class="mt-4 pagination-wrap" aria-label="Files pagination">
    <div id="pagerSummary" class="pager-summary d-none"></div>
    <ul class="pagination pagination-modern justify-content-center" id="pager"></ul>
  </nav>
</div>

@endsection

@push('scripts')
<script>
  (function(){
    const routes = {
      list: @json(route('admin.users.files.list', $user)),
    };

    let page = 1, q = '';
    const tbody = document.querySelector('#filesTable tbody');
    const empty = document.getElementById('emptyState');
    const pager = document.getElementById('pager');
    const pagerSummary = document.getElementById('pagerSummary');
    const searchInput = document.getElementById('searchInput');
    const fileCountEl = document.getElementById('fileCount');

    function humanSize(bytes){ if(!bytes && bytes!==0) return '—'; const u=['B','KB','MB','GB']; let i=0,n=+bytes; while(n>=1024&&i<u.length-1){n/=1024;i++;} return (i? n.toFixed(2):n)+' '+u[i]; }
    function iconByExt(name){ const ext=(name.split('.').pop()||'').toLowerCase(); if(ext==='pdf') return 'bi-filetype-pdf'; if(['doc','docx'].includes(ext)) return 'bi-file-earmark-text'; if(ext==='csv') return 'bi-filetype-csv'; return 'bi-file-earmark'; }
    function escapeHtml(s){ return (s??'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    function rowTemplate(f){
      const id=f.id||''; const filename=f.filename||'—'; const size=humanSize(f.size); const modified=f.modified||''; const timePart=f.time||''; const status=f.status||'Secure';
      return `<tr data-id="${id}">
        <td><div class="col-file"><span class="file-chip"><i class="bi ${iconByExt(filename)}"></i></span><div class="file-name">${escapeHtml(filename)}</div></div></td>
        <td>${size}</td>
        <td><div>${escapeHtml(modified)}</div><small class="text-muted">${escapeHtml(timePart)}</small></td>
        <td><span class="status-pill">${escapeHtml(status)}</span></td>
      </tr>`;
    }

  function renderRows(files, total){
    tbody.innerHTML='';
    if(!files.length){ empty.classList.remove('d-none'); fileCountEl.textContent='0'; return; }
    empty.classList.add('d-none');
    tbody.innerHTML=files.map(rowTemplate).join('');
    fileCountEl.textContent = (total ?? files.length);
  }

  function renderPager(current,last,total=null,perPage=null){
    pager.innerHTML='';
    if(total!=null && perPage!=null){
      const start = (current-1)*perPage + 1;
      const end = Math.min(total, current*perPage);
      pagerSummary.classList.remove('d-none');
      pagerSummary.textContent = `Showing ${start.toLocaleString()}–${end.toLocaleString()} of ${total.toLocaleString()}`;
    }else{
      pagerSummary.classList.add('d-none');
    }

    if(last<=1) return;

    const maxWindow = 5;
    let start = Math.max(1, current - Math.floor(maxWindow/2));
    let end   = Math.min(last, start + maxWindow - 1);
    if(end - start + 1 < maxWindow) start = Math.max(1, end - maxWindow + 1);

    const add = (p, html, disabled=false, active=false, extra='')=>{
      const li=document.createElement('li');
      li.className=`page-item ${extra}${disabled?' disabled':''}${active?' active':''}`.trim();
      li.innerHTML=`<a class="page-link" href="#" data-page="${p}">${html}</a>`;
      pager.appendChild(li);
    };
    const dots = ()=> add(current, '…', true, false, 'ellipsis');

    add(1, `<i class="bi bi-chevron-double-left"></i>`, current===1);
    add(current-1, `<i class="bi bi-chevron-left"></i>`, current===1);

    if(start>1){ add(1,'1',false,current===1); if(start>2) dots(); }
    for(let i=start;i<=end;i++) add(i, String(i), false, i===current);
    if(end<last){ if(end<last-1) dots(); add(last, String(last), false, current===last); }

    add(current+1, `<i class="bi bi-chevron-right"></i>`, current===last);
    add(last, `<i class="bi bi-chevron-double-right"></i>`, current===last);
  }

  async function load(p=1){
    page=p;
    const params=new URLSearchParams({page:p, search:q});
    const res=await fetch(routes.list+'?'+params.toString());
    const data=await res.json();
    renderRows(data.files||[], data.total ?? undefined);
    renderPager(data.current_page||1, data.last_page||1, data.total ?? null, data.per_page ?? null);
  }

  searchInput.addEventListener('input',()=>{ q=searchInput.value; load(1); });
  pager.addEventListener('click',function(e){
    const a=e.target.closest('a[data-page]'); if(!a) return;
    e.preventDefault();
    const p=parseInt(a.dataset.page,10);
    if(p>0) load(p);
  });

  load();
})();
</script>
@endpush

