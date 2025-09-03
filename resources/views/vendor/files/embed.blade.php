@extends('vendor.layouts.app')

@php
  $titleText = $isImage ? 'Embed Image' : 'Embed PDF Viewer';
  $iconClass = $isImage ? 'bi-card-image text-info' : 'bi-file-earmark-pdf text-danger';
  $embedSrc  = $url ?: 'https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN';
@endphp

@section('title', $titleText)

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

  /* top-band / breadcrumb (matches Manage Files) */
  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{ display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b; }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  .page-header { gap: 1rem; padding: .25rem 0; }
  .page-header h1 { display:flex; align-items:center; gap:.5rem; margin:0; }

  .btn-ghost{
    border:1px solid var(--line);background:#fff;padding:.5rem .85rem;border-radius:12px;
    display:inline-flex;align-items:center;font-weight:600;gap:.4rem;
  }
  .btn-ghost:hover{ background:#f7f9fc }

  /* Code card + tabs */
  .code-card { position:relative; overflow:hidden; border:1px solid var(--line); border-radius:12px; }
  .code-toolbar {
    display:flex; align-items:center; justify-content:space-between;
    padding:.5rem .75rem; border-bottom:1px solid var(--line); background:#f0f2f7;
  }
  .code-tabs { display:flex; gap:.4rem; flex-wrap:wrap; }
  .code-tab {
    border:1px solid var(--line); background:#fff; color:#0f172a; font-weight:700;
    border-radius:999px; padding:.35rem .75rem; line-height:1; cursor:pointer;
  }
  .code-tab[aria-selected="true"]{ background:#111; color:#fff; border-color:#111; }
  .code-actions{ display:flex; gap:.5rem; align-items:center; }

  pre { background:#0f172a; color:#e2e8f0; margin:0; padding:1rem 1.25rem; border-radius:0 0 12px 12px; font-size:.9rem; overflow:auto; }
  pre code { white-space:pre; }

  /* Viewer */
  .viewer-wrap iframe { width:100%; height:600px; border:1px solid var(--line); border-radius:12px; background:#0b1220; }
  .viewer-wrap img { max-width:100%; border:1px solid var(--line); border-radius:12px; background:#0b1220; }
  @media (max-width:768px){ .viewer-wrap iframe{ height:70vh; } .viewer-wrap img{ max-height:70vh; } }

  .muted-note { color:#64748b; }
</style>
@endpush

@section('content')
<!-- breadcrumb -->
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between">
      <nav class="crumb">
        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('vendor.files.manage') }}">Manage Files</a>
        <i class="bi bi-chevron-right"></i>
        <span>{{ $titleText }}</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  <div class="d-flex align-items-center justify-content-between mb-3 page-header">
    <h1 class="h5 text-dark mb-0">
      <i class="{{ $iconClass }}"></i>
      <span>{{ $titleText }}</span>
    </h1>

    @if($url)
    <div class="d-flex align-items-center" style="gap:8px;">
      <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-primary d-flex align-items-center" style="gap:6px;border-radius:12px;">
        <i class="bi bi-box-arrow-up-right"></i><span>Open Link</span>
      </a>
      <button class="btn btn-sm btn-ghost" id="copyUrlBtn" data-bs-toggle="tooltip" data-bs-title="Copy the file URL">
        <i class="bi bi-link-45deg"></i><span>Copy URL</span>
      </button>
    </div>
    @endif
  </div>

  <div class="card">
    <div class="card-body">
      @if($url)
        <div class="mb-3 muted-note small"><i class="bi bi-lightning-charge-fill me-1"></i>Use this snippet to embed your viewer anywhere.</div>

        <!-- Code card with tabs -->
        <div class="code-card mb-4">
          <div class="code-toolbar">
            <div class="code-tabs" role="tablist" aria-label="Embed Snippet">
              <button class="code-tab" role="tab" aria-selected="true" data-target="html">HTML</button>
              <button class="code-tab" role="tab" aria-selected="false" data-target="react">React</button>
              <button class="code-tab" role="tab" aria-selected="false" data-target="angular">Angular</button>
              <button class="code-tab" role="tab" aria-selected="false" data-target="vue">Vue</button>
            </div>
            <div class="code-actions">
              <span class="badge rounded-pill text-bg-light">Embed Snippet</span>
              <button class="btn btn-sm btn-ghost" id="copyCodeBtn" data-bs-toggle="tooltip" data-bs-title="Copy the embed code">
                <i class="bi bi-clipboard"></i><span>Copy</span>
              </button>
            </div>
          </div>
          <pre class="mb-0"><code id="embedCode"></code></pre>
        </div>

        <div class="viewer-wrap">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0 d-flex align-items-center" style="gap:8px;">
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

<!-- Copy toast -->
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
{{-- 1) Pass dynamic values BEFORE the verbatim block so Blade can render them --}}
<script>
  window.EMBED_SRC = @json($embedSrc);
  window.FILE_URL  = @json($url);
</script>

@verbatim
<script>
(function(){
  const EMBED_SRC = window.EMBED_SRC || 'https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN';

  const SNIPPETS = {
    html:
`<iframe
  src="${EMBED_SRC}"
  width="100%" height="600"
  style="border:none;border-radius:12px;"
  allow="clipboard-write"></iframe>`,

    react:
`export default function PdfViewer(){
  return (
    <iframe
      src="${EMBED_SRC}"
      width="100%" height="600"
      style={{ border: 'none', borderRadius: '12px' }}
      allow="clipboard-write"
    />
  );
}`,

    angular:
`<!-- component template -->
<iframe
  [src]="'${EMBED_SRC}'"
  width="100%" height="600"
  style="border:none;border-radius:12px;"
  allow="clipboard-write">
</iframe>
<!-- Tip: If Angular sanitizes the URL, inject DomSanitizer and use bypassSecurityTrustResourceUrl(). -->`,

    vue:
`<!-- Vue 3 SFC -->
<template>
  <iframe
    :src="src"
    width="100%" height="600"
    style="border:none;border-radius:12px;"
    allow="clipboard-write" />
</template>

<script setup>
const src = '${EMBED_SRC}';
<\/script>`
  };

  const codeEl = document.getElementById('embedCode');
  const setSnippet = (key) => { 
    codeEl.textContent = SNIPPETS[key] || SNIPPETS.html; 
  };

  // initial
  setSnippet('html');

  // tabs
  document.querySelectorAll('.code-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.code-tab').forEach(b => b.setAttribute('aria-selected', 'false'));
      btn.setAttribute('aria-selected', 'true');
      setSnippet(btn.dataset.target);
    });
  });

  // helpers
  const showToast = (msg) => {
    const el = document.getElementById('copyToast');
    const msgEl = document.getElementById('toastMsg');
    if(!el || !msgEl) return;
    msgEl.textContent = msg;
    const t = bootstrap.Toast.getOrCreateInstance(el, {delay: 1600});
    t.show();
  };
  
  const copyText = async (text) => {
    try { 
      await navigator.clipboard.writeText(text); 
      showToast('Copied to clipboard'); 
    }
    catch(e){ 
      showToast('Copy failed'); 
    }
  };

  // tooltips & copy buttons
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => { 
    new bootstrap.Tooltip(el); 
  });
  
  document.getElementById('copyCodeBtn')?.addEventListener('click', () => copyText(codeEl.textContent));
  
  document.getElementById('copyUrlBtn')?.addEventListener('click', () => {
    if (window.FILE_URL) copyText(window.FILE_URL); 
    else showToast('No URL available');
  });
})();
</script>
@endverbatim
@endpush