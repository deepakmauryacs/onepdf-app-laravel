<?php
require_once __DIR__ . '/../config.php';
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<style>
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
    <h1 class="h3 mb-0 text-gray-800">Managed Files</h1>
  </div>

  <div class="card shadow-sm files-card">
    <div class="card-header">
      <div class="mf-header">
        <div class="mf-left">
          <i class="bi bi-folder2-open mr-2"></i>
          <span class="font-weight-bold">Files</span>
          <span class="badge badge-primary ml-1" id="countBadge">0</span>
        </div>
        <div class="mf-actions">
          <!-- Fixed search bar -->
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

<!-- Permissions Modal (polished like Upgrade modal) -->
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

        <!-- Bootstrap Toggle switches -->
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
            <i class="bi bi-water mr-2 text-secondary"></i>
            <input id="permAnalytics" type="checkbox" checked
                   data-toggle="toggle" data-on="ON" data-off="OFF"
                   data-onstyle="primary" data-offstyle="light" data-size="sm">
            <span class="label-inline">Add watermark</span>
          </div>

          <div class="toggle-text">
            <div class="sub mt-2">These settings apply to the generated link only.</div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
          <i class="bi bi-x-circle mr-1"></i> Cancel
        </button>
        <button type="button" class="btn btn-primary" id="createLink">
          <i class="bi bi-magic mr-1"></i> Create link
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/gh/minhur/bootstrap-toggle@2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/minhur/bootstrap-toggle@2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
(function($){
  const $tbody   = $('#filesTable tbody');
  const $count   = $('#countBadge');
  const $empty   = $('#emptyState');
  const $bulkBtn = $('#bulkDelete');
  const $checkAll= $('#checkAll');
  let genBtn = null;
  let linkHolder = null;

  $('#permModal').on('shown.bs.modal', function(){
    $('#permDownload, #permSearch, #permAnalytics').bootstrapToggle('destroy').bootstrapToggle();
  });

  function humanSize(bytes){
    if (bytes === 0 || bytes == null) return '—';
    const u = ['B','KB','MB','GB']; let i=0, n=bytes;
    while(n >= 1024 && i < u.length-1){ n/=1024; i++; }
    return (i ? n.toFixed(2) : n.toFixed(0)) + ' ' + u[i];
  }
  function iconFor(name){
    const ext = (name.split('.').pop() || '').toLowerCase();
    if (ext === 'pdf') return '<div class="file-icon text-danger" title="PDF"><i class="bi bi-file-earmark-pdf"></i></div>';
    if (['png','jpg','jpeg','gif','webp','bmp','svg'].includes(ext))
      return '<div class="file-icon" title="Image"><i class="bi bi-file-image"></i></div>';
    return '<div class="file-icon" title="File"><i class="bi bi-file-earmark"></i></div>';
  }
  function showAlert(msg, type){
    $('#alert').html('<div class="alert alert-'+type+'">'+msg+'</div>');
    setTimeout(()=> $('#alert .alert').fadeOut(250, function(){ $(this).remove(); }), 2200);
  }

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

  // Init
  loadFiles();
})(jQuery);
</script>

<?php include 'includes/footer.php'; ?>
