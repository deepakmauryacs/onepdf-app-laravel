@extends('vendor.layouts.app')

@php
  $titleText = $isImage ? 'Embed Image' : 'Embed PDF Viewer';
  $iconClass = $isImage ? 'bi-card-image text-info' : 'bi-file-earmark-pdf text-danger';
@endphp

@section('title', $titleText)

@push('styles')
<style>
  /* Page header row */
  .page-header { gap: 1rem; padding: .5rem 0; }
  .page-header h1 { display:flex; align-items:center; gap:.5rem; }

  /* Code block card */
  .code-card { position:relative; overflow:hidden; }
  .code-toolbar { display:flex; gap:.5rem; align-items:center; justify-content:flex-end; padding:.5rem .75rem; border-bottom:1px solid #e9ecef; background:#f8f9fa; }
  pre { background:#0f172a; color:#e2e8f0; margin:0; padding:1rem 1.25rem; border-radius:0 0 .5rem .5rem; font-size:.9rem; overflow:auto; }
  pre code { white-space:pre; }

  /* Viewer */
  .viewer-wrap iframe { width:100%; height:600px; border:1px solid #e5e7eb; border-radius:8px; background:#0b1220; }
  .viewer-wrap img { max-width:100%; border:1px solid #e5e7eb; border-radius:8px; background:#0b1220; }
  @media (max-width:768px){ .viewer-wrap iframe{ height:70vh; } .viewer-wrap img{ max-height:70vh; } }

  /* Small helpers */
  .muted-note { color:#64748b; }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-3 page-header">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="{{ $iconClass }}"></i>
      <span>{{ $titleText }}</span>
    </h1>
    @if($url)
    <div class="d-flex align-items-center gap-2" style="gap:5px;">
      <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
        <i class="bi bi-box-arrow-up-right"></i><span>Open Link</span>
      </a>
      <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" id="copyUrlBtn" data-bs-toggle="tooltip" data-bs-title="Copy the file URL">
        <i class="bi bi-link-45deg"></i><span>Copy URL</span>
      </button>
    </div>
    @endif
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      @if($url)
        <div class="mb-3 muted-note small">Use this snippet to embed your viewer anywhere.</div>
        <div class="code-card border rounded">
          <div class="code-toolbar">
            <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" id="copyCodeBtn" data-bs-toggle="tooltip" data-bs-title="Copy the embed code">
              <i class="bi bi-clipboard"></i><span>Copy</span>
            </button>
          </div>
          <pre class="mb-0"><code id="embedCode">{{ $snippet }}</code></pre>
        </div>
        <div class="mt-4 viewer-wrap">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-display"></i><span>Live Preview</span>
            </h6>
            <div class="muted-note small">{{ $isImage ? 'Image scales to fit container.' : 'Height auto-adjusts on mobile.' }}</div>
          </div>
          @if($isImage)
            <img src="{{ $url }}" alt="">
          @else
            <iframe src="{{ $url }}" allow="clipboard-write"></iframe>
          @endif
        </div>
      @else
        <p class="mb-0">No URL provided.</p>
      @endif
    </div>
  </div>
</div>

<!-- Toast for copy feedback -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:1055">
  <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg">Copied!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const copyText = async (text) => {
    try { await navigator.clipboard.writeText(text); showToast('Copied to clipboard'); }
    catch(e){ showToast('Copy failed'); }
  };

  const showToast = (msg) => {
    const el=document.getElementById('copyToast');
    const msgEl=document.getElementById('toastMsg');
    if(!el||!msgEl) return;
    msgEl.textContent=msg;
    const t=bootstrap.Toast.getOrCreateInstance(el,{delay:1600});
    t.show();
  };

  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el=>{ new bootstrap.Tooltip(el); });

  const copyCodeBtn=document.getElementById('copyCodeBtn');
  const copyUrlBtn=document.getElementById('copyUrlBtn');

  if(copyCodeBtn){ copyCodeBtn.addEventListener('click',()=>{ const code=document.getElementById('embedCode').innerText; copyText(code); }); }
  if(copyUrlBtn){ copyUrlBtn.addEventListener('click',()=>{ copyText(@json($url)); }); }
})();
</script>
@endpush
