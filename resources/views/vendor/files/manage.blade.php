@extends('vendor.layouts.app')

@section('title', 'Manage Files')

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

  /* ===== top-band / breadcrumb (same as other pages) ===== */
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
  .btn-danger-soft{background:#ffecec;border:1px solid #ffd0d0;color:#b42318;border-radius:10px}

  table.table{margin:0}
  .table td, .table th{vertical-align:middle}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}
  .col-file{display:flex;align-items:center;gap:12px}
  .file-chip{display:inline-flex;width:36px;height:36px;border-radius:10px;background:#f3f6fb;color:#0b5ed7;align-items:center;justify-content:center;font-size:18px}
  .file-name a{color:#0f172a;text-decoration:none;font-weight:600}
  .status-pill{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;background:#eff8ff;color:#0b5ed7;border:1px solid #d7e6ff;height:34px;padding:0 14px;font-weight:600}

  /* Actions */
  .actions-cell{display:flex;flex-direction:column;justify-content:center;gap:8px}
  .actions{display:flex;align-items:center;gap:8px;flex-wrap:nowrap;white-space:nowrap}
  .btn-ghost,.btn-icon{display:inline-flex;align-items:center;justify-content:center;height:40px;border-radius:12px;line-height:1;font-weight:600}
  .btn-ghost{border:1px solid var(--line);background:#fff;padding:0 14px}
  .btn-ghost .bi{margin-right:8px}
  .btn-ghost:hover{background:#f7f9fc}
  .btn-icon{width:40px;padding:0;border:1px solid #ffd7d7;background:#fff5f5;color:#b42318}
  .btn-icon:hover{background:#ffe8e8}
  .small-link{color:#64748b;margin-top:2px}
  .small-link a{color:#0b5ed7;text-decoration:none}

  /* ===== Modern centered pagination (black & white) + summary ===== */
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
        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Manage Files</span>
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
        <button id="btnDeleteSelected" class="btn btn-danger-soft"><i class="bi bi-trash"></i> Delete selected</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle" id="filesTable">
        <thead>
          <tr>
            <th style="width:46px;"><input id="checkAll" class="form-check-input" type="checkbox" /></th>
            <th>File Name</th>
            <th style="width:140px;">Size</th>
            <th style="width:180px;">Modified</th>
            <th style="width:140px;">Status</th>
            <th style="width:320px;">Actions</th>
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
    list: @json(route('vendor.files.manage.list')),
    generate: @json(route('vendor.files.generate')),
    delete: @json(route('vendor.files.delete')),
    detailBase: @json(url('vendor/files/manage')),
    embed: @json(route('vendor.files.embed')),
  };

  let page = 1, q = '';
  const tbody = document.querySelector('#filesTable tbody');
  const empty = document.getElementById('emptyState');
  const pager = document.getElementById('pager');
  const pagerSummary = document.getElementById('pagerSummary');
  const searchInput = document.getElementById('searchInput');
  const fileCountEl = document.getElementById('fileCount');
  const checkAll = document.getElementById('checkAll');
  const btnDeleteSelected = document.getElementById('btnDeleteSelected');

  function humanSize(bytes){ if(!bytes && bytes!==0) return '—'; const u=['B','KB','MB','GB']; let i=0,n=+bytes; while(n>=1024&&i<u.length-1){n/=1024;i++;} return (i? n.toFixed(2):n)+' '+u[i]; }
  function iconByExt(name){ const ext=(name.split('.').pop()||'').toLowerCase(); if(ext==='pdf') return 'bi-filetype-pdf'; if(['doc','docx'].includes(ext)) return 'bi-file-earmark-text'; if(ext==='csv') return 'bi-filetype-csv'; return 'bi-file-earmark'; }
  function escapeHtml(s){ return (s??'').toString().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  function toast(msg){ const n=document.createElement('div'); n.textContent=msg; Object.assign(n.style,{position:'fixed',right:'16px',bottom:'16px',background:'#111',color:'#fff',padding:'10px 14px',borderRadius:'10px',zIndex:1060}); document.body.appendChild(n); setTimeout(()=>n.remove(),1600); }

  function rowTemplate(f){
    const id=f.id||''; const filename=f.filename||'—'; const size=humanSize(f.size); const modified=f.modified||''; const timePart=f.time||''; const status=f.status||'Secure'; const url=f.public_url||''; const detail=routes.detailBase+'/'+id;
    return `<tr data-id="${id}">
      <td><input class="form-check-input row-check" type="checkbox"/></td>
      <td><div class="col-file"><span class="file-chip"><i class="bi ${iconByExt(filename)}"></i></span><div class="file-name"><a href="${detail}">${escapeHtml(filename)}</a></div></div></td>
      <td>${size}</td>
      <td><div>${escapeHtml(modified)}</div><small class="text-muted">${escapeHtml(timePart)}</small></td>
      <td><span class="status-pill">${escapeHtml(status)}</span></td>
      <td>
        <div class="actions-cell">
          <div class="actions">
            <button class="btn btn-ghost btn-generate"><i class="bi bi-link-45deg"></i>Generate</button>
            <button class="btn btn-ghost btn-copy"><i class="bi bi-clipboard"></i>Copy</button>
            <button class="btn btn-ghost btn-embed"><i class="bi bi-code-slash"></i>Embed</button>
            <button class="btn-icon btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
          </div>
          <div class="small small-link text-break">${url?`<a href="${url}" target="_blank">${escapeHtml(url)}</a>`:'—'}</div>
        </div>
      </td>
    </tr>`;
  }

  function renderRows(files, total){
    tbody.innerHTML='';
    if(!files.length){ empty.classList.remove('d-none'); fileCountEl.textContent='0'; return; }
    empty.classList.add('d-none');
    tbody.innerHTML=files.map(rowTemplate).join('');
    fileCountEl.textContent = (total ?? files.length);
  }

  // Centered, modern pagination with ellipses + summary
  function renderPager(current,last,total=null,perPage=null){
    pager.innerHTML='';
    // summary (optional if API provides numbers)
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

  document.getElementById('searchInput').addEventListener('input',()=>{ q=searchInput.value; load(1); });
  document.getElementById('pager').addEventListener('click',function(e){
    const a=e.target.closest('a[data-page]'); if(!a) return;
    e.preventDefault();
    const p=parseInt(a.dataset.page,10);
    if(p>0) load(p);
  });
  document.getElementById('checkAll').addEventListener('change',()=>{
    document.querySelectorAll('.row-check').forEach(cb=>cb.checked=checkAll.checked);
  });

  // actions
  tbody.addEventListener('click',async e=>{
    const tr=e.target.closest('tr'); if(!tr) return; const id=tr.dataset.id;
    if(e.target.closest('.btn-generate')){
      const r=await fetch(routes.generate,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({id})});
      const j=await r.json(); const link=j.url||'';
      if(link){ tr.querySelector('.small-link').innerHTML=`<a href="${link}" target="_blank">${link}</a>`; toast('Link generated'); }
      return;
    }
    if(e.target.closest('.btn-copy')){
      const link=tr.querySelector('.small-link a')?.href; if(link){ await navigator.clipboard.writeText(link); toast('Copied'); }
      return;
    }
    if(e.target.closest('.btn-embed')){
      const link=tr.querySelector('.small-link a')?.href; if(!link){ toast('Generate first'); return; }
      window.open(routes.embed+'?url='+encodeURIComponent(link),'_blank');
      return;
    }
    if(e.target.closest('.btn-delete')){
      if(!confirm('Delete this file?')) return;
      await fetch(routes.delete,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({ids:[id]})});
      tr.remove(); toast('Deleted');
    }
  });

  document.getElementById('btnDeleteSelected').addEventListener('click',async()=>{
    const ids=[...document.querySelectorAll('.row-check:checked')].map(cb=>cb.closest('tr').dataset.id);
    if(!ids.length) return toast('No files');
    if(!confirm('Delete selected?')) return;
    await fetch(routes.delete,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({ids})});
    ids.forEach(id=>tbody.querySelector(`tr[data-id="${id}"]`)?.remove());
    toast('Deleted');
  });

  load();
})();
</script>
@endpush
