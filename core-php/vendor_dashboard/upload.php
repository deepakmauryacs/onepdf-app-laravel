<?php
require_once __DIR__ . '/../config.php';
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>

<!-- Page-only styles (light, boxy, dashed drop zone, progress rows) -->
<style>
  .upload-card { border: 1px solid #e5e7eb; border-radius: 12px; }
  .upload-drop {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    background: #f8fafc;
    padding: 36px;
    transition: all .2s ease;
  }
  .upload-drop.is-dragover { border-color: #8b5cf6; background: #f5f3ff; }
  .upload-drop .cloud { font-size: 40px; opacity: .7; }
  .upload-drop .browse { color: #6d28d9; text-decoration: underline; cursor: pointer; }
  .muted { color: #6b7280; }

  /* Uploading list (progress rows) */
  .upload-row {
    display: grid; grid-template-columns: 56px 1fr 110px 150px; gap: 12px;
    align-items: center; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 10px 12px; background: #fff;
  }
  .file-badge {
    width: 40px; height: 40px; border-radius: 10px; display: grid; place-items: center;
    background: #eef2ff; font-weight: 700; color: #4338ca; font-size: .8rem;
  }
  .progress { height: 8px; border-radius: 999px; }
  .progress-bar { background: linear-gradient(90deg,#7c3aed,#3b82f6); }

  /* ---------- Card & table polish ---------- */
  .files-card{ border:1px solid #e5e7eb; border-radius:12px; }
  .table thead th{ border-bottom:1px solid #e5e7eb; font-weight:700; color:#374151; }
  .table tbody td{ vertical-align:middle; }
  .file-name{ max-width:520px; }
  .file-icon{ width:36px; height:36px; display:grid; place-items:center; border-radius:10px; background:#eef2ff; color:#4338ca; }

  /* ---------- Header tools layout ---------- */
  .mf-header{ display:flex; align-items:center; gap:12px; justify-content:flex-start; }
  .mf-left{ flex:0 0 auto; }
  .mf-actions{ flex:1 1 auto; display:flex; align-items:center; gap:12px; flex-wrap:nowrap; margin-left:auto; }

  /* --- Fixed Search Bar (icon inside input) --- */
  .mf-search{ flex:1 1 auto; min-width:260px; max-width:none; position:relative; }
  .mf-search .mf-search-input{
    height:40px; padding:.45rem .75rem .45rem 2.1rem;
    border-radius:10px; box-shadow:none !important;
    border:1px solid #e5e7eb;
  }
  .mf-search .mf-search-icon{
    position:absolute; left:10px; top:50%; transform:translateY(-50%);
    pointer-events:none; color:#6b7280; font-size:14px;
  }

  .mf-actions .btn{
    height:40px; padding:0 .9rem; border-radius:10px;
    display:inline-flex; align-items:center; gap:.35rem; line-height:1;
  }
  .badge-secure{ background:#16a34a; color:#fff; border-radius:999px; padding:.2rem .5rem; font-weight:600; }
  .btn-icon{ display:inline-flex; align-items:center; gap:.35rem; }
  .empty{ padding:32px; text-align:center; color:#6b7280; border-top:1px dashed #e5e7eb; border-bottom-left-radius:12px; border-bottom-right-radius:12px; }
  .card-header{ padding:.85rem 1.1rem; }
  @media (max-width: 768px){
    .mf-header{ flex-wrap:wrap; }
    .mf-actions{ width:100%; margin-left:0; }
    .mf-search{ min-width:0; }
  }

  /* ---- Modern Permissions Modal (Bootstrap 4) ---- */
  .rounded-lg{ border-radius:16px !important; }
  .perm-modal .modal-content{ border:0; border-radius:16px; box-shadow:0 20px 60px rgba(2,6,23,.18); }
  .perm-modal .modal-header{ border:0; padding:16px 20px; }
  .perm-modal .modal-body{ padding:16px 20px 20px; }
  .perm-modal .modal-footer{ border:0; padding:12px 20px 20px; }
  .perm-hero{
    display:flex; align-items:center; justify-content:center; gap:12px;
    flex-direction:column; margin:8px 0 18px;
  }
  .perm-hero .shield{ width:60px; height:60px; border-radius:16px; background:#eef2ff; display:grid; place-items:center; }
  .perm-hero .shield i{ font-size:28px; color:#3b82f6; }
  .toggle-list{ display:grid; gap:16px; margin-top:6px; }
  .toggle-row{ display:flex; align-items:center; gap:12px; }
  .toggle-text .title{ font-weight:600; color:#1f2937; }
  .toggle-text .sub{ font-size:.875rem; color:#6b7280; margin-top:2px; }
  .toggle-row .label-inline{ margin-left:8px; font-weight:600; color:#1f2937; }
  .perm-modal .btn-primary,
  .perm-modal .btn-outline-secondary{ border-radius:10px; }
  .perm-modal .btn-primary{ font-weight:600; }
</style>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800">Upload Your Files</h1>
  </div>

  <!-- Upload card -->
  <div class="card shadow-sm upload-card mb-4">
    <div class="card-body">
      <p class="mb-2 muted">to attach to the project</p>

      <!-- Drop zone -->
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
    <div class="card-header font-weight-bold">Uploading</div>
    <div class="card-body">
      <div id="uploadList" class="d-grid" style="gap:12px;"></div>
    </div>
  </div>

  <!-- Managed files -->
  <div class="card shadow-sm files-card mb-5">
    <div class="card-header">
      <div class="mf-header">
        <div class="mf-left">
          <i class="bi bi-folder2-open mr-2"></i>
          <span class="font-weight-bold">Files</span>
          <span class="badge badge-primary ml-1" id="countBadge">0</span>
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

  <div id="alert" class="mt-3"></div>
</div>

<!-- Permissions Modal -->
<div class="modal fade perm-modal" id="permModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0 rounded-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title mb-0">
          <i class="bi bi-shield-lock mr-2"></i> Link Permissions
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p class="text-muted mb-3">
          <i class="bi bi-lightning-charge-fill text-warning mr-1"></i>
          Choose what viewers can do before generating the link.
        </p>

        <div class="perm-hero text-center">
          <div class="shield"><i class="bi bi-shield-check"></i></div>
          <div>
            <div class="h6 mb-0">Control what viewers can do</div>
            <div class="text-muted small">Toggle options before generating the link</div>
          </div>
        </div>

        <div class="toggle-list">

          <div class="toggle-row">
            <i class="bi bi-download mr-2 text-secondary"></i>
            <input id="permDownload" type="checkbox" checked
                   data-toggle="toggle" data-on="ON" data-off="OFF"
                   data-onstyle="primary" data-offstyle="light" data-size="sm">
            <span class="label-inline">Allow downloading</span>
          </div>

          <div class="toggle-row">
            <i class="bi bi-printer mr-2 text-secondary"></i>
            <input id="permSearch" type="checkbox"
                   data-toggle="toggle" data-on="ON" data-off="OFF"
                   data-onstyle="primary" data-offstyle="light" data-size="sm">
            <span class="label-inline">Allow printing</span>
          </div>

          <div class="toggle-row">
            <i class="bi bi-bar-chart-line mr-2 text-secondary"></i>
            <input id="permAnalytics" type="checkbox" checked
                   data-toggle="toggle" data-on="ON" data-off="OFF"
                   data-onstyle="primary" data-offstyle="light" data-size="sm">
            <span class="label-inline">Allow analytics tracking</span>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-primary" id="createLink">
          <i class="bi bi-magic mr-1"></i> Create link
        </button>
      </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/gh/minhur/bootstrap-toggle@2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/minhur/bootstrap-toggle@2.2.2/js/bootstrap-toggle.min.js"></script>

<!-- Uses jQuery from your template footer -->
<script>
(function($){
  const MAX_SIZE = 50 * 1024 * 1024; // 50MB
  const drop = $('#dropArea');
  const input = $('#fileInput');
  const browse = $('#browseLink');
  const uploadingBox = $('#uploadingBox');
  const uploadList = $('#uploadList');

  const $tbody   = $('#filesTable tbody');
  const $count   = $('#countBadge');
  const $empty   = $('#emptyState');
  const $bulkBtn = $('#bulkDelete');
  const $checkAll= $('#checkAll');
  let genBtn = null;
  let linkHolder = null;

  function humanSize(bytes){
    if (bytes === 0 || bytes === undefined || bytes === null) return '—';
    const units = ['B','KB','MB','GB'];
    let i = 0; let num = bytes;
    while (num >= 1024 && i < units.length-1){ num /= 1024; i++; }
    return num.toFixed(i ? 2 : 0) + ' ' + units[i];
  }
  function extBadge(filename){
    const ext = (filename.split('.').pop() || '').toUpperCase();
    return '<div class="file-badge">'+ (ext || 'FILE') +'</div>';
  }
  function iconFor(name){
    const ext = (name.split('.').pop() || '').toLowerCase();
    if (ext === 'pdf') return '<div class="file-icon text-danger" title="PDF"><i class="bi bi-file-earmark-pdf"></i></div>';
    if (['png','jpg','jpeg','gif','webp','bmp','svg'].includes(ext))
      return '<div class="file-icon" title="Image"><i class="bi bi-file-image"></i></div>';
    return '<div class="file-icon" title="File"><i class="bi bi-file-earmark"></i></div>';
  }

  // --- Uploading UI row
  function makeUploadRow(file){
    const $row = $('<div class="upload-row"></div>');
    $row.append(extBadge(file.name));
    $row.append('<div class="text-truncate">'+file.name+'</div>');
    $row.append('<div class="muted">'+humanSize(file.size)+'</div>');
    const prog = $(`
      <div>
        <div class="progress mb-1">
          <div class="progress-bar" role="progressbar" style="width:0%"></div>
        </div>
        <div class="d-flex justify-content-between small muted">
          <span class="pct">0%</span><span class="done text-success d-none">100% done</span>
        </div>
      </div>
    `);
    $row.append(prog);
    uploadList.append($row);
    uploadingBox.removeClass('d-none');
    return {row:$row, bar:prog.find('.progress-bar'), pct:prog.find('.pct'), done:prog.find('.done')};
  }

  // --- Upload function (one file per request -> supports multiple)
  function uploadFiles(fileList){
    const files = Array.from(fileList || []);
    if(!files.length) return;

    // Validate and filter
    const valid = files.filter(f=>{
      const typeOk = /^application\/pdf$/.test(f.type);
      const sizeOk = f.size <= MAX_SIZE;
      if(!typeOk){
        showAlert('Only PDF files are allowed: ' + f.name, 'danger');
      }else if(!sizeOk){
        showAlert('File too large (max 50MB): ' + f.name, 'danger');
      }
      return typeOk && sizeOk;
    });
    if(!valid.length) return;

    valid.forEach(function(file){
      const ui = makeUploadRow(file);

      const fd = new FormData();
      // server expects "pdf" earlier — support both "file" and "pdf"
      fd.append('file', file);
      fd.append('pdf', file); // in case your upload_file.php reads 'pdf'

      $.ajax({
        url: 'api/upload_file.php',
        type: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        xhr: function(){
          const xhr = $.ajaxSettings.xhr();
          if(xhr.upload){
            xhr.upload.addEventListener('progress', function(e){
              if(e.lengthComputable){
                const pct = Math.round((e.loaded / e.total) * 100);
                ui.bar.css('width', pct + '%');
                ui.pct.text(pct + '%');
              }
            });
          }
          return xhr;
        },
        success: function(res){
          ui.bar.css('width','100%');
          ui.pct.text('100%');
          ui.done.removeClass('d-none');
          setTimeout(()=> { ui.row.fadeOut(300, function(){ $(this).remove(); if(!uploadList.children().length){ uploadingBox.addClass('d-none'); } }); }, 800);
          if(res && res.success){
            showAlert('File uploaded successfully.', 'success');
          }
          loadFiles();
        },
        error: function(xhr){
          const msg = xhr.responseJSON ? (xhr.responseJSON.error || 'Upload failed') : 'Upload failed';
          showAlert(msg, 'danger');
        }
      });
    });
  }

  // --- Drop events
  drop.on('drag dragstart dragend dragover dragenter dragleave drop', function(e){ e.preventDefault(); e.stopPropagation(); });
  drop.on('dragover dragenter', function(){ drop.addClass('is-dragover'); });
  drop.on('dragleave dragend drop', function(){ drop.removeClass('is-dragover'); });
  drop.on('drop', function(e){ uploadFiles(e.originalEvent.dataTransfer.files); });

  // Browse link
  browse.on('click', function(){ input.trigger('click'); });
  input.on('change', function(){ uploadFiles(this.files); this.value=''; });

  $('#permModal').on('shown.bs.modal', function(){
    $('#permDownload, #permSearch, #permAnalytics').bootstrapToggle('destroy').bootstrapToggle();
  });

  function renderRows(files){
    $tbody.empty();
    if(!files || !files.length){
      $empty.removeClass('d-none'); $count.text('0'); return;
    }
    $empty.addClass('d-none'); $count.text(files.length);

    files.forEach(f=>{
      const size = humanSize(f.size);
      const mod  = f.modified || f.updated_at || '';
      const url  = f.url || '';

      const $tr = $('<tr>');
      $tr.append('<td><input type="checkbox" class="row-check" data-id="'+f.id+'"></td>');
      $tr.append(`
        <td class="text-truncate" title="${f.filename}">
          <div class="d-flex align-items-center" style="gap:10px;">
            ${iconFor(f.filename)}
            <span class="text-truncate">${f.filename}</span>
          </div>
        </td>
      `);
      $tr.append('<td>'+size+'</td>');
      $tr.append('<td>'+mod+'</td>');
      $tr.append('<td><span class="badge-secure">Secure</span></td>');
      $tr.append(`
        <td>
          <div class="d-flex align-items-center" style="gap:8px;">
            <button class="btn btn-outline-primary btn-sm btn-icon generate" data-id="${f.id}">
              <i class="bi bi-link-45deg"></i> Generate
            </button>
            <button class="btn btn-outline-secondary btn-sm btn-icon copy" data-url="${url}" ${url ? '' : 'disabled'}>
              <i class="bi bi-clipboard"></i> Copy
            </button>
            <button class="btn btn-outline-info btn-sm btn-icon embed" data-url="${url}" ${url ? '' : 'disabled'}>
              <i class="bi bi-code-slash"></i> Embed
            </button>
            <button class="btn btn-outline-danger btn-sm delete" data-id="${f.id}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <div class="small text-muted mt-1 link-holder">${url ? `<code>${url}</code>` : ''}</div>
        </td>
      `);
      $tbody.append($tr);
    });
    syncBulkBtn();
  }

  function loadFiles(){
    $.get('api/list_files.php', function(res){
      renderRows((res && res.files) ? res.files : []);
      $checkAll.prop('checked', false);
    }, 'json').fail(()=> showAlert('Failed to load files.', 'danger'));
  }

  // Search filter
  $('#searchInput').on('input', function(){
    const q = this.value.toLowerCase();
    $('#filesTable tbody tr').each(function(){
      const name = $(this).children().eq(1).text().toLowerCase();
      $(this).toggle(name.indexOf(q) !== -1);
    });
    $checkAll.prop('checked', false);
    syncBulkBtn();
  });

  // Single delete
  $tbody.on('click', '.delete', function(){
    const id = $(this).data('id');
    if(!confirm('Delete this file?')) return;
    $.post('api/delete_file.php', { id }, loadFiles)
     .fail(()=> showAlert('Delete failed.', 'danger'));
  });

  // Open permissions modal
  $tbody.on('click', '.generate', function(){
    genBtn = $(this);
    linkHolder = genBtn.closest('td').find('.link-holder');
    $('#permModal').data('id', genBtn.data('id')).modal('show');
  });

  // Create link with permissions
  $('#createLink').on('click', function(){
    const id = $('#permModal').data('id');
    const perms = {
      download:  $('#permDownload').prop('checked'),
      search:    $('#permSearch').prop('checked'),
      analytics: $('#permAnalytics').prop('checked')
    };
    $('#permModal').modal('hide');
    genBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-1"></span>Generating');
    $.post('api/generate_link.php', { id, permissions: JSON.stringify(perms) }, function(res){
      const url = (res && res.url) ? res.url : '';
      if(url){
        linkHolder.html('<code>'+url+'</code>');
        genBtn.closest('td').find('.copy').prop('disabled', false).data('url', url);
      }else{
        linkHolder.html('<span class="text-danger">No URL returned</span>');
      }
    }, 'json').fail(function(){
      linkHolder.html('<span class="text-danger">Failed to generate link</span>');
    }).always(function(){
      genBtn.prop('disabled', false).html('<i class="bi bi-link-45deg"></i> Generate');
    });
  });

  // Copy
  $tbody.on('click', '.copy', function(){
    const url = $(this).data('url') || '';
    if(!url) return;
    const ta = document.createElement('textarea');
    ta.value = url; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
    showAlert('Link copied to clipboard.', 'success');
  });

  // Embed
  $tbody.on('click', '.embed', function(){
    const url = $(this).data('url') || '';
    if(!url) return;
    window.open('embed.php?url=' + encodeURIComponent(url), '_blank');
  });

  // Bulk select
  $('#checkAll').on('change', function(){
    $('.row-check:visible').prop('checked', this.checked);
    syncBulkBtn();
  });
  $tbody.on('change', '.row-check', syncBulkBtn);

  function syncBulkBtn(){
    const checked = $('.row-check:checked:visible').length;
    $bulkBtn.prop('disabled', checked === 0);
  }

  // Bulk delete
  $bulkBtn.on('click', function(){
    const ids = $('.row-check:checked:visible').map(function(){ return $(this).data('id'); }).get();
    if(!ids.length) return;
    if(!confirm('Delete '+ids.length+' selected file(s)?')) return;
    let done=0, failed=0;
    const next = () => {
      if(!ids.length){
        loadFiles();
        showAlert((failed? failed+' failed, ' : '')+done+' deleted.', failed?'danger':'success');
        return;
      }
      const id = ids.pop();
      $.post('api/delete_file.php', { id })
        .done(()=>{ done++; next(); })
        .fail(()=>{ failed++; next(); });
    };
    next();
  });

  function showAlert(msg, type){
    $('#alert').html('<div class="alert alert-'+type+'">'+msg+'</div>');
    setTimeout(()=> $('#alert .alert').fadeOut(250, function(){ $(this).remove(); }), 2200);
  }

  // Init
  loadFiles();

})(jQuery);
</script>

<?php include 'includes/footer.php'; ?>
