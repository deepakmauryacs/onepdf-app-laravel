@extends('vendor.layouts.app')

@section('title', 'Upload Files')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{ --surface:#ffffff; --muted:#6b7280; --line:#e5e7eb; --ring:#d9dde3; --text:#0f172a; --radius:12px; }
  *{ font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; }

  .upload-card{ border:1px solid var(--line); border-radius:12px; }
  .upload-drop{ border:2px dashed #d1d5db; border-radius:12px; background:#f8fafc; padding:36px; transition:all .2s; }
  .upload-drop.is-dragover{ border-color:#8b5cf6; background:#f5f3ff; }
  .upload-drop .cloud{ font-size:40px; opacity:.7; }
  .upload-drop .browse{ color:#6d28d9; text-decoration:underline; cursor:pointer; }
  .muted{ color:#6b7280; }

  .upload-row{ display:grid; grid-template-columns:56px 1fr 110px 150px; gap:12px; align-items:center; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; background:#fff; }
  .file-badge{ width:40px; height:40px; border-radius:10px; display:grid; place-items:center; background:#eef2ff; font-weight:700; color:#4338ca; font-size:.8rem; }
  .progress{ height:8px; border-radius:999px; }
  .progress-bar{ background:linear-gradient(90deg,#7c3aed,#3b82f6); }

  .files-card{ border:1px solid #e5e7eb; border-radius:12px; }
  .table thead th{ border-bottom:1px solid #e5e7eb; font-weight:700; color:#374151; }
  .file-name{ max-width:520px; }
  .file-icon{ width:36px; height:36px; display:grid; place-items:center; border-radius:10px; background:#eef2ff; color:#4338ca; }
  .empty{ padding:32px; text-align:center; color:#6b7280; border-top:1px dashed #e5e7eb; border-bottom-left-radius:12px; border-bottom-right-radius:12px; }

  .mf-header{ display:flex; align-items:center; gap:12px; }
  .mf-actions{ margin-left:auto; display:flex; align-items:center; gap:12px; }
  .mf-search{ position:relative; min-width:260px; flex:1; }
  .mf-search-input{ height:40px; padding:.45rem .75rem .45rem 2.1rem; border-radius:10px; border:1px solid #e5e7eb; }
  .mf-search-icon{ position:absolute; left:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:#6b7280; font-size:14px; }
  .badge-secure{ background:#16a34a; color:#fff; border-radius:999px; padding:.2rem .5rem; font-weight:600; }

  /* Toast bg colors */
  .toast-success{ background:#16a34a; color:#fff; }
  .toast-error{ background:#dc2626; color:#fff; }
  .toast-info{ background:#2563eb; color:#fff; }
  .toast-warning{ background:#d97706; color:#fff; }
  .toast .toast-header{ background:transparent; color:inherit; border-bottom:0; }
  .toast .btn-close{ filter: invert(1); }
</style>
@endpush

@section('content')
  <div class="container py-3">

    {{-- Toast --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index:1080">
      <div id="app-toast" class="toast border-0 shadow text-white" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <i id="toast-icon" class="bi me-2"></i>
          <strong id="toast-title" class="me-auto">Notification</strong>
          <small id="toast-time">just now</small>
          <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-body">Message</div>
      </div>
    </div>

    <h1 class="h4 mb-3">Upload Your Files</h1>

    <!-- Upload card -->
    <div class="card shadow-sm upload-card mb-4">
      <div class="card-body">
        <p class="mb-2 muted">to attach to the project</p>
        <div id="dropArea" class="upload-drop text-center">
          <div class="cloud mb-2"><i class="bi bi-cloud-upload"></i></div>
          <div class="h5 mb-1">Drag & drop your files here or <span id="browseLink" class="browse">Browse</span></div>
          <div class="muted">50 MB max file size • PDF files only</div>
          <input id="fileInput" type="file" class="d-none" multiple accept="application/pdf">
        </div>
      </div>
    </div>

    <!-- Uploading list -->
    <div id="uploadingBox" class="card shadow-sm mb-4 d-none">
      <div class="card-header fw-bold">Uploading</div>
      <div class="card-body">
        <div id="uploadList" class="d-grid" style="gap:12px;"></div>
      </div>
    </div>

    <!-- Files -->
    <div class="card shadow-sm files-card mb-5">
      <div class="card-header">
        <div class="mf-header">
          <div class="mf-left">
            <i class="bi bi-folder2-open me-2"></i>
            <span class="fw-bold">Files</span>
            <span class="badge bg-primary ms-1" id="countBadge">0</span>
          </div>
          <div class="mf-actions">
            <div class="mf-search">
              <i class="bi bi-search mf-search-icon"></i>
              <input id="searchInput" type="text" class="form-control mf-search-input" placeholder="Search files..." autocomplete="off">
            </div>
            <button id="bulkDelete" class="btn btn-outline-danger" disabled>
              <i class="bi bi-trash"></i> Delete selected
            </button>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table mb-0" id="filesTable">
          <thead>
          <tr>
            <th style="width:36px;"><input type="checkbox" id="checkAll"></th>
            <th class="file-name">File Name</th>
            <th style="width:120px;">Size</th>
            <th style="width:160px;">Modified</th>
            <th style="width:110px;">Status</th>
            <th style="width:260px;">Actions</th>
          </tr>
          </thead>
          <tbody></tbody>
        </table>
        <div id="emptyState" class="empty d-none">
          <div class="mb-2"><i class="bi bi-inbox"></i></div>
          No files yet. Upload some to see them here.
        </div>
      </div>
    </div>

  </div>

  {{-- Permissions Modal --}}
  <div class="modal fade" id="permModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 rounded-4 shadow-lg">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title mb-0"><i class="bi bi-shield-lock me-2"></i>Link Permissions</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3 text-muted">
            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
            Choose what viewers can do before generating the link.
          </div>

          <div class="d-grid gap-3">
            <label class="d-flex align-items-center gap-2">
              <i class="bi bi-download text-secondary"></i>
              <input id="permDownload" class="form-check-input ms-1" type="checkbox" checked>
              <span class="ms-2">Allow downloading</span>
            </label>

            <label class="d-flex align-items-center gap-2">
              <i class="bi bi-printer text-secondary"></i>
              <input id="permSearch" class="form-check-input ms-1" type="checkbox">
              <span class="ms-2">Allow printing</span>
            </label>

            <label class="d-flex align-items-center gap-2">
              <i class="bi bi-bar-chart-line text-secondary"></i>
              <input id="permAnalytics" class="form-check-input ms-1" type="checkbox" checked>
              <span class="ms-2">Allow analytics tracking</span>
            </label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="createLink"><i class="bi bi-magic me-1"></i>Create link</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
(function(){
  const MAX_SIZE = 50 * 1024 * 1024; // 50MB
  const routes = {
    list   : @json(route('vendor.files.list')),
    upload : @json(route('vendor.files.upload')),
    del    : @json(route('vendor.files.delete')),
    gen    : @json(route('vendor.files.generate')),
  };
  const csrf = @json(csrf_token());

  // Toast
  function toast(msg,type='success'){
    const el = document.getElementById('app-toast');
    const body = document.getElementById('toast-body');
    const title= document.getElementById('toast-title');
    const icon = document.getElementById('toast-icon');
    const time = document.getElementById('toast-time');

    body.textContent = msg; time.textContent = 'just now';
    el.classList.remove('toast-success','toast-error','toast-info','toast-warning');
    if(type==='success'){ el.classList.add('toast-success'); title.textContent='Success'; icon.className='bi bi-check-circle-fill me-2'; }
    else if(type==='error'){ el.classList.add('toast-error'); title.textContent='Error'; icon.className='bi bi-x-circle-fill me-2'; }
    else if(type==='warning'){ el.classList.add('toast-warning'); title.textContent='Warning'; icon.className='bi bi-exclamation-triangle-fill me-2'; }
    else { el.classList.add('toast-info'); title.textContent='Info'; icon.className='bi bi-info-circle-fill me-2'; }
    bootstrap.Toast.getOrCreateInstance(el,{delay:3000}).show();
  }

  // Elements
  const drop = document.getElementById('dropArea');
  const input = document.getElementById('fileInput');
  const browse = document.getElementById('browseLink');
  const uploadingBox = document.getElementById('uploadingBox');
  const uploadList = document.getElementById('uploadList');
  const tbody = document.querySelector('#filesTable tbody');
  const countBadge = document.getElementById('countBadge');
  const emptyState = document.getElementById('emptyState');
  const bulkBtn = document.getElementById('bulkDelete');
  const checkAll = document.getElementById('checkAll');

  // Helpers
  function humanSize(bytes){
    if(!bytes && bytes!==0) return '—';
    const u=['B','KB','MB','GB']; let i=0; let n=+bytes;
    while(n>=1024 && i<u.length-1){ n/=1024; i++; }
    return (i? n.toFixed(2):n) + ' ' + u[i];
  }
  function extBadge(name){
    const ext=(name.split('.').pop()||'').toUpperCase();
    return `<div class="file-badge">${ext||'FILE'}</div>`;
  }
  function iconFor(name){
    const ext=(name.split('.').pop()||'').toLowerCase();
    if(ext==='pdf') return `<div class="file-icon text-danger" title="PDF"><i class="bi bi-file-earmark-pdf"></i></div>`;
    if(['png','jpg','jpeg','gif','webp','bmp','svg'].includes(ext))
      return `<div class="file-icon" title="Image"><i class="bi bi-file-image"></i></div>`;
    return `<div class="file-icon" title="File"><i class="bi bi-file-earmark"></i></div>`;
  }

  function makeUploadRow(file){
    const row=document.createElement('div'); row.className='upload-row';
    row.innerHTML = `${extBadge(file.name)}
      <div class="text-truncate">${file.name}</div>
      <div class="muted">${humanSize(file.size)}</div>
      <div>
        <div class="progress mb-1"><div class="progress-bar" role="progressbar" style="width:0%"></div></div>
        <div class="d-flex justify-content-between small muted"><span class="pct">0%</span><span class="done text-success d-none">100% done</span></div>
      </div>`;
    uploadList.appendChild(row);
    uploadingBox.classList.remove('d-none');
    return {row, bar:row.querySelector('.progress-bar'), pct:row.querySelector('.pct'), done:row.querySelector('.done')};
  }

  async function uploadFiles(list){
    const files = Array.from(list||[]);
    if(!files.length) return;

    const valid = files.filter(f=>{
      const okType = (f.type === 'application/pdf');
      const okSize = (f.size <= MAX_SIZE);
      if(!okType) toast('Only PDF files are allowed: '+f.name,'error');
      else if(!okSize) toast('File too large (max 50MB): '+f.name,'error');
      return okType && okSize;
    });
    if(!valid.length) return;

    for (const file of valid){
      const ui = makeUploadRow(file);
      const fd = new FormData(); fd.append('file', file); fd.append('_token', csrf);

      await fetch(routes.upload, {
        method: 'POST',
        body: fd,
      }).then(async (r)=>{
        if(!r.ok){ throw new Error((await r.json().catch(()=>({}))).message || 'Upload failed'); }
        return r.json();
      }).then(()=>{
        ui.bar.style.width='100%'; ui.pct.textContent='100%'; ui.done.classList.remove('d-none');
        setTimeout(()=>{ ui.row.remove(); if(!uploadList.children.length) uploadingBox.classList.add('d-none'); }, 700);
        toast('File uploaded successfully.','success');
        loadFiles();
      }).catch(e=>{
        toast(e.message || 'Upload failed','error');
      });
    }
  }

  // Drag & Drop
  ['drag','dragstart','dragend','dragover','dragenter','dragleave','drop'].forEach(ev=>{
    drop.addEventListener(ev,(e)=>{ e.preventDefault(); e.stopPropagation(); });
  });
  ['dragover','dragenter'].forEach(ev=>drop.addEventListener(ev,()=>drop.classList.add('is-dragover')));
  ['dragleave','dragend','drop'].forEach(ev=>drop.addEventListener(ev,()=>drop.classList.remove('is-dragover')));
  drop.addEventListener('drop',(e)=> uploadFiles(e.dataTransfer.files));

  // Browse
  browse.addEventListener('click',()=> input.click());
  input.addEventListener('change',function(){ uploadFiles(this.files); this.value=''; });

  // List + render
  function renderRows(files){
    tbody.innerHTML='';
    if(!files || !files.length){
      emptyState.classList.remove('d-none'); countBadge.textContent='0'; return;
    }
    emptyState.classList.add('d-none'); countBadge.textContent=files.length;

    for (const f of files){
      const tr=document.createElement('tr');
      tr.innerHTML = `
        <td><input type="checkbox" class="row-check" data-id="${f.id}"></td>
        <td class="text-truncate" title="${f.filename}">
          <div class="d-flex align-items-center" style="gap:10px;">${iconFor(f.filename)}<span class="text-truncate">${f.filename}</span></div>
        </td>
        <td>${humanSize(f.size)}</td>
        <td>${f.modified || ''}</td>
        <td><span class="badge-secure">Secure</span></td>
        <td>
          <div class="d-flex align-items-center" style="gap:8px;">
            <button class="btn btn-outline-primary btn-sm generate" data-id="${f.id}"><i class="bi bi-link-45deg"></i> Generate</button>
            <button class="btn btn-outline-secondary btn-sm copy" ${f.url?'':'disabled'} data-url="${f.url||''}"><i class="bi bi-clipboard"></i> Copy</button>
            <button class="btn btn-outline-info btn-sm embed" ${f.url?'':'disabled'} data-url="${f.url||''}"><i class="bi bi-code-slash"></i> Embed</button>
            <button class="btn btn-outline-danger btn-sm delete" data-id="${f.id}"><i class="bi bi-trash"></i></button>
          </div>
          <div class="small text-muted mt-1 link-holder">${f.url?`<code>${f.url}</code>`:''}</div>
        </td>`;
      tbody.appendChild(tr);
    }
    syncBulkBtn();
  }

  async function loadFiles(){
    try {
      const res = await fetch(routes.list);
      const data = await res.json();
      renderRows(data.files||[]);
      checkAll.checked=false;
    } catch {
      toast('Failed to load files.','error');
    }
  }

  // Search
  document.getElementById('searchInput').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    Array.from(tbody.querySelectorAll('tr')).forEach(tr=>{
      const name = tr.children[1].innerText.toLowerCase();
      tr.style.display = name.includes(q) ? '' : 'none';
    });
    checkAll.checked=false; syncBulkBtn();
  });

  // Row actions
  tbody.addEventListener('click', async (e)=>{
    const btn = e.target.closest('button'); if(!btn) return;

    // delete
    if(btn.classList.contains('delete')){
      if(!confirm('Delete this file?')) return;
      const fd = new FormData(); fd.append('id', btn.dataset.id); fd.append('_token', csrf);
      try{
        const r=await fetch(routes.del,{method:'POST',body:fd}); if(!r.ok) throw 0;
        await r.json(); toast('File deleted.','success'); loadFiles();
      }catch{ toast('Delete failed.','error'); }
    }

    // generate link
    if(btn.classList.contains('generate')){
      // store target row
      document.getElementById('permModal').dataset.id = btn.dataset.id;
      const modal = new bootstrap.Modal('#permModal'); modal.show();
      document.getElementById('permModal')._genBtn = btn;
      document.getElementById('permModal')._holder = btn.closest('td').querySelector('.link-holder');
    }

    // copy
    if(btn.classList.contains('copy')){
      const url = btn.dataset.url || '';
      if(!url) return;
      navigator.clipboard.writeText(url).then(()=> toast('Link copied to clipboard.','success'));
    }

    // embed
    if(btn.classList.contains('embed')){
      const url = btn.dataset.url || '';
      if(url) window.open(url, '_blank');
    }
  });

  // bulk select
  function syncBulkBtn(){
    const checked = [...tbody.querySelectorAll('.row-check')].filter(x=>x.checked && x.closest('tr').style.display!=='none').length;
    bulkBtn.disabled = checked===0;
  }
  checkAll.addEventListener('change',()=>{
    [...tbody.querySelectorAll('.row-check')].forEach(cb=>{
      if(cb.closest('tr').style.display!=='none') cb.checked = checkAll.checked;
    });
    syncBulkBtn();
  });
  tbody.addEventListener('change', (e)=>{ if(e.target.classList.contains('row-check')) syncBulkBtn(); });

  // bulk delete
  bulkBtn.addEventListener('click', async ()=>{
    const ids = [...tbody.querySelectorAll('.row-check:checked')].map(cb=>cb.dataset.id);
    if(!ids.length || !confirm('Delete '+ids.length+' selected file(s)?')) return;

    let done=0, failed=0;
    for (const id of ids){
      const fd=new FormData(); fd.append('id',id); fd.append('_token',csrf);
      try{ const r=await fetch(routes.del,{method:'POST',body:fd}); if(!r.ok) throw 0; await r.json(); done++; }
      catch{ failed++; }
    }
    loadFiles();
    toast(`${done} deleted${failed?`, ${failed} failed`:''}.`, failed?'error':'success');
  });

  // permissions -> create link
  document.getElementById('createLink').addEventListener('click', async ()=>{
    const modalEl = document.getElementById('permModal');
    const id = modalEl.dataset.id;
    const fd = new FormData(); fd.append('id', id); fd.append('_token', csrf);
    // (you can also pass perms to server if you want to save)
    const modal = bootstrap.Modal.getInstance('#permModal'); modal.hide();

    const btn = modalEl._genBtn; const holder = modalEl._holder;
    btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Generating';

    try{
      const r=await fetch(routes.gen,{method:'POST',body:fd}); const data=await r.json();
      if(data.url){ holder.innerHTML = `<code>${data.url}</code>`; btn.parentElement.querySelector('.copy').disabled=false; btn.parentElement.querySelector('.copy').dataset.url=data.url; toast('Link generated.','success'); }
      else{ holder.innerHTML = '<span class="text-danger">No URL returned</span>'; toast('Failed to generate link.','error'); }
    }catch{
      toast('Failed to generate link.','error');
    }finally{
      btn.disabled=false; btn.innerHTML = '<i class="bi bi-link-45deg"></i> Generate';
    }
  });

  // init
  loadFiles();
})();
</script>
@endpush
