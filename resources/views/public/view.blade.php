{{-- resources/views/public/view.blade.php --}}
@php
  use Illuminate\Support\Str;
  $allowDownload  = !empty($perms['download']);
  $allowPrint     = !empty($perms['print']);
  $allowAnalytics = !empty($perms['analytics']);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>PDF Viewer</title>

<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">
<link rel="shortcut icon" href="{{ asset('assets/favicon_io/favicon.ico') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.css"/>

<style>
  :root{
    --ui-bg:#111315; --ui-bar:#2b3034; --ui-bar-darker:#23272b;
    --ui-ink:#ffffff; --ui-muted:#9aa4b2; --ui-border:#3d434b; --ui-accent:#4c8bf5; --side-w:260px;
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0; height:100vh; height:100dvh; overflow:hidden;
    background:var(--ui-bg); color:var(--ui-ink);
    font-family:"DM Sans",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
  }

  .topbar{ height:48px; background:var(--ui-bar); display:flex; align-items:center; gap:10px; padding:0 16px; border-bottom:1px solid var(--ui-border); }
  .tb{ height:32px; min-width:32px; border:0; color:var(--ui-ink); background:transparent; border-radius:4px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; cursor:pointer }
  .tb:hover{ background:rgba(255,255,255,0.1) }
  .tb i{ font-size:16px }
  .spacer{flex:1 1 auto}

  .zoom-wrap{position:relative}
  .zoom-btn{display:inline-flex;align-items:center;gap:6px;font-weight:600;color:var(--ui-ink)}
  .zoom-menu{ position:absolute; top:38px; left:0; min-width:180px; background:rgba(43,48,52,.9); border:1px solid var(--ui-border); border-radius:8px; padding:6px; display:none; z-index:40 }
  .zoom-menu .item{ display:flex; align-items:center; height:34px; padding:0 10px; border-radius:8px; color:var(--ui-ink); cursor:pointer; white-space:nowrap }
  .zoom-menu .item:hover{ background:rgba(35,39,43,.8) }

  .sheet{ display:grid; grid-template-columns: var(--side-w) minmax(0,1fr); height: calc(100vh - 48px); height: calc(100dvh - 48px); }
  .sheet.hide-side{ grid-template-columns: 0 minmax(0,1fr); }
  .sidebar{ border-right:1px solid var(--ui-border); background:rgba(17,19,21,.9); display:flex; flex-direction:column; min-width:0; overflow:hidden; }
  .sheet.hide-side .sidebar{ display:none; }

  .side-tabs{ display:flex; gap:8px; padding:10px; border-bottom:1px solid var(--ui-border) }
  .side-tabs .tb{ background:var(--ui-bar-darker) }
  .side-tabs .tb.active{ outline:2px solid var(--ui-accent); outline-offset:-2px }

  #thumbnailView{ padding:12px; overflow:auto; flex:1; display:none }
  #outlineView{ padding:12px; overflow:auto; flex:1; font-size:14px; display:none }
  #outlineView ul{ list-style:none; margin:0; padding-left:0 }
  .outline-item{ display:flex; align-items:center; gap:6px; padding:6px 4px; cursor:pointer; color:var(--ui-ink); border-radius:6px; }
  .outline-item:hover{ background:rgba(255,255,255,.06); color:var(--ui-accent) }
  .outline-children{ padding-left:16px }

  .thumb{ border:1px solid var(--ui-border); border-radius:10px; background:rgba(17,19,21,.92); margin-bottom:12px; padding:6px; cursor:pointer }
  .thumb.selected{ outline:2px solid var(--ui-accent); outline-offset:2px }
  .thumb canvas{ width:100%; display:block }

  .viewer{ position:relative; background:rgba(17,19,21,.8); height:100%; min-width:0; }
  #viewerContainer{ position:absolute; inset:0; overflow:auto; width:100%; height:100%; min-width:0; }
  #viewer{ min-width:0; }
  .pdfViewer .page{ margin:10px auto; background:#fff; border-radius:10px; border:1px solid #d7dae0 }

  ::-webkit-scrollbar{ width:10px; height:10px }
  ::-webkit-scrollbar-thumb{ background:#5a616a; border-radius:999px }
  ::-webkit-scrollbar-track{ background:#20252a }

  .page-info{ display:flex; align-items:center; gap:6px; color:var(--ui-ink) }
  .page-info input{ width:40px; height:26px; border:1px solid var(--ui-border); background:var(--ui-bar-darker); color:var(--ui-ink); text-align:center; border-radius:4px }

  #viewerContainer, #viewer, .pdfViewer .textLayer{
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select:none; user-select:none;
  }
  
  /* Print styles */
  @media print {
    body, html, .sheet {
      width: 100% !important;
      height: auto !important;
      overflow: visible !important;
    }
    
    .topbar, .sidebar {
      display: none !important;
    }
    
    .viewer, #viewerContainer, #viewer {
      position: static !important;
      width: 100% !important;
      height: auto !important;
      overflow: visible !important;
    }
    
    .pdfViewer .page {
      margin: 0 !important;
      border: none !important;
      border-radius: 0 !important;
      box-shadow: none !important;
      page-break-after: always;
      page-break-inside: avoid;
    }
  }
</style>
</head>
<body>

<div class="topbar">
  <button class="tb" id="toggleSidebar" title="Toggle sidebar"><i class="bi bi-layout-sidebar"></i></button>

  <button class="tb" id="prevPage" title="Previous page"><i class="bi bi-chevron-left"></i></button>
  <div class="page-info">
    <input type="text" id="pageNumber" value="1" />
    <span id="pageCount">0</span>
  </div>
  <button class="tb" id="nextPage" title="Next page"><i class="bi bi-chevron-right"></i></button>

  <div class="spacer"></div>

  <button class="tb" id="zoomOut" title="Zoom out"><i class="bi bi-dash-lg"></i></button>

  <div class="zoom-wrap">
    <button class="tb zoom-btn" id="zoomBtn"><span id="zoomLabel">100%</span> <i class="bi bi-caret-down-fill"></i></button>
    <div class="zoom-menu" id="zoomMenu">
      <div class="item" data-scale="page-width">Fit to Width</div>
      <div class="item" data-scale="page-fit">Fit to Page</div>
      <div class="item" data-scale="auto">Auto Zoom</div>
      <div class="item" data-scale="page-actual">Actual Size</div>
      <div class="item" data-scale="0.5">50%</div>
      <div class="item" data-scale="0.75">75%</div>
      <div class="item" data-scale="1">100%</div>
      <div class="item" data-scale="1.25">125%</div>
      <div class="item" data-scale="1.5">150%</div>
      <div class="item" data-scale="2">200%</div>
      <div class="item" data-scale="3">300%</div>
      <div class="item" data-scale="4">400%</div>
    </div>
  </div>

  <button class="tb" id="zoomIn" title="Zoom in"><i class="bi bi-plus-lg"></i></button>

  <div class="spacer"></div>

  @if($allowPrint)
    <button class="tb" id="printBtn" title="Print"><i class="bi bi-printer"></i></button>
  @endif
  @if($allowDownload)
    <a class="tb" id="downloadBtn" title="Download" href="{{ $downloadUrl }}" download>
      <i class="bi bi-download"></i>
    </a>
  @endif
</div>

<div class="sheet" id="sheet">
  <aside class="sidebar" id="sidebar">
    <div class="side-tabs">
      <button class="tb" id="thumbTab" title="Thumbnails"><i class="bi bi-grid-3x3-gap"></i></button>
      <button class="tb" id="outlineTab" title="Outlines"><i class="bi bi-list-task"></i></button>
    </div>
    <div id="thumbnailView"></div>
    <div id="outlineView"></div>
  </aside>

  <main class="viewer">
    <div id="viewerContainer">
      <div id="viewer" class="pdfViewer"></div>
    </div>
  </main>
</div>

@if($leadEnabled)
<div id="leadModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);align-items:center;justify-content:center;z-index:1000;">
  <div style="background:#fff;color:#000;padding:20px;border-radius:8px;width:90%;max-width:400px;">
    <h5 style="margin-top:0;margin-bottom:15px;">Please leave your details</h5>
    <form id="leadForm">
      @csrf
      @foreach($leadFields as $field)
        @php $name = $field['name'] ?? Str::slug($field['label'] ?? 'field', '_'); @endphp
        <div style="margin-bottom:10px;">
          @switch($field['type'])
            @case('textarea')
              <textarea name="{{ $name }}" placeholder="{{ $field['label'] ?? '' }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;"></textarea>
              @break
            @case('select')
              <select name="{{ $name }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                @foreach($field['options'] ?? [] as $opt)
                  <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
              </select>
              @break
            @case('radio')
              @foreach($field['options'] ?? [] as $opt)
                <label style="display:block;margin-bottom:4px;"><input type="radio" name="{{ $name }}" value="{{ $opt }}"> {{ $opt }}</label>
              @endforeach
              @break
            @case('checkbox')
              <label><input type="checkbox" name="{{ $name }}"> {{ $field['label'] ?? '' }}</label>
              @break
            @default
              <input type="{{ $field['type'] }}" name="{{ $name }}" placeholder="{{ $field['label'] ?? '' }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
          @endswitch
        </div>
      @endforeach
      <button type="submit" style="width:100%;padding:10px;border:0;background:#111;color:#fff;border-radius:4px;">Submit</button>
    </form>
  </div>
</div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.js"></script>

    <script>
        window.analyticsEndpoint = "{{ route('analytics.track') }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('assets/analytics.js') }}"></script>
<script>
(function(){
  pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

  var pdfUrl       = @json($pdfUrl);
  var slug         = @json($slug);
  var allowPrint   = @json($allowPrint);

  var pageNumber   = document.getElementById('pageNumber');
  var pageCount    = document.getElementById('pageCount');
  var sheet        = document.getElementById('sheet');
  var viewContainer= document.getElementById('viewerContainer');

  var eventBus     = new pdfjsViewer.EventBus();
  var linkSvc      = new pdfjsViewer.PDFLinkService({ eventBus: eventBus });
  var pdfHistory   = new pdfjsViewer.PDFHistory({ eventBus: eventBus, linkService: linkSvc });
  linkSvc.setHistory(pdfHistory);

  var pdfViewer    = new pdfjsViewer.PDFViewer({
    container: viewContainer,
    eventBus: eventBus,
    linkService: linkSvc,
    maxCanvasPixels: 0,
    textLayerMode: 2,
    removePageBorders: true
  });
  linkSvc.setViewer(pdfViewer);

  var pdfDoc = null;

  var thumbTab    = document.getElementById('thumbTab');
  var outlineTab  = document.getElementById('outlineTab');
  var thumbView   = document.getElementById('thumbnailView');
  var outlineView = document.getElementById('outlineView');

  pdfjsLib.getDocument(pdfUrl).promise.then(function(doc){
    pdfDoc = doc;
    linkSvc.setDocument(doc, null);

    // Avoid optional chaining for compat
    var fp = (doc.fingerprints && doc.fingerprints.length) ? doc.fingerprints[0] : String(Date.now());
    pdfHistory.initialize({ fingerprint: fp });

    pdfViewer.setDocument(doc);
    pdfViewer.currentScaleValue = window.matchMedia('(max-width: 600px)').matches ? 'page-fit' : 'page-width';
    updateZoomLabel();

    buildThumbnails(doc).then(function(){
      doc.getOutline().then(function(outline){
        buildOutline(outline);

        if (outline && outline.length) {
          outlineTab.classList.add('active');  thumbTab.classList.remove('active');
          outlineView.style.display = 'block'; thumbView.style.display  = 'none';
        } else {
          thumbTab.classList.add('active');    outlineTab.classList.remove('active');
          thumbView.style.display  = 'block';  outlineView.style.display = 'none';
        }
        pageCount.textContent = String(doc.numPages);
      });
    });

    // Initialize analytics session for this PDF
    initPdfAnalytics(@json($d_id), "{{ hash_hmac('sha256', (string) $d_id, config('app.key')) }}");

  }).catch(function(err){
    console.error(err);
    alert('Failed to load PDF.');
  });

  /* ----------- Modified PRINT function (same tab/window) ----------- */
  function printPdf() {
    // Use the browser's native print functionality
    window.print();
  }
  
  var printBtn = document.getElementById('printBtn');
  if (printBtn && allowPrint) { 
    printBtn.addEventListener('click', printPdf); 
  }

  /* Tabs */
  thumbTab.onclick = function(){
    thumbTab.classList.add('active');
    outlineTab.classList.remove('active');
    thumbView.style.display = 'block';
    outlineView.style.display = 'none';
  };
  outlineTab.onclick = function(){
    outlineTab.classList.add('active');
    thumbTab.classList.remove('active');
    outlineView.style.display = 'block';
    thumbView.style.display = 'none';
  };

  /* Thumbnails */
  function buildThumbnails(doc){
    thumbView.innerHTML = '';
    var maxW = 220;
    var p = Promise.resolve();
    for (var i=1;i<=doc.numPages;i++){
      (function(pageNo){
        p = p.then(function(){
          return doc.getPage(pageNo).then(function(page){
            var v0 = page.getViewport({ scale:1 });
            var scale = maxW / v0.width;
            var viewport = page.getViewport({ scale: scale });

            var wrap = document.createElement('div');
            wrap.className = 'thumb';
            wrap.setAttribute('data-page', String(pageNo));

            var cv = document.createElement('canvas');
            cv.width  = Math.floor(viewport.width);
            cv.height = Math.floor(viewport.height);
            var ctx = cv.getContext('2d', { alpha:false });

            wrap.appendChild(cv);
            thumbView.appendChild(wrap);

            page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function(){
              if (page.cleanup) page.cleanup();
            });
            wrap.onclick = function(){ pdfViewer.currentPageNumber = pageNo; };
          });
        });
      })(i);
    }
    return p.then(function(){ selectThumb(1, true); });
  }
  function selectThumb(n, center){
    var nodes = thumbView.querySelectorAll('.thumb');
    for (var i=0;i<nodes.length;i++){
      var el = nodes[i];
      var isSel = parseInt(el.getAttribute('data-page'),10) === parseInt(n,10);
      if (isSel) el.classList.add('selected'); else el.classList.remove('selected');
    }
    if (center) {
      var el2 = thumbView.querySelector('.thumb[data-page="' + n + '"]');
      if (el2) el2.scrollIntoView({ block:'nearest' });
    }
  }
  eventBus.on('pagechanging', function(e){
    var n = e.pageNumber || pdfViewer.currentPageNumber;
    selectThumb(n, true);
    pageNumber.value = String(n);

    // console.log('Page change: ' + n);
    // Send analytics
    if (typeof onPdfPageChange === "function") {
        onPdfPageChange(n);
    }
  });

  /* Outline navigation */
  function buildOutline(outline){
    outlineView.innerHTML = '';
    if (!outline){ outlineView.textContent = 'No outline available'; return; }
    function render(items, parent){
      var ul = document.createElement('ul');
      parent.appendChild(ul);
      for (var i=0;i<items.length;i++){
        var it = items[i];
        var li = document.createElement('li');
        ul.appendChild(li);
        var row = document.createElement('div');
        row.className = 'outline-item';
        li.appendChild(row);
        var text = document.createElement('span');
        text.textContent = it.title || '';
        text.style.flex = '1';
        row.appendChild(text);
        row.addEventListener('click', function(item){
          return function(){
            if (item.url) window.open(item.url, '_blank', 'noopener,noreferrer');
            if (item.dest) linkSvc.goToDestination(item.dest);
          };
        }(it));
        if (it.items && it.items.length){
          var child = document.createElement('div');
          child.className = 'outline-children';
          li.appendChild(child);
          render(it.items, child);
        }
      }
    }
    render(outline, outlineView);
  }

  /* Zoom */
  var zoomBtn   = document.getElementById('zoomBtn');
  var zoomMenu  = document.getElementById('zoomMenu');
  var zoomLabel = document.getElementById('zoomLabel');

  document.addEventListener('click', function(){ zoomMenu.style.display='none'; });
  zoomBtn.onclick = function(e){ e.stopPropagation(); zoomMenu.style.display = (zoomMenu.style.display==='block'?'none':'block'); };
  var items = zoomMenu.querySelectorAll('.item');
  for (var i=0;i<items.length;i++){
    items[i].onclick = function(){
      var v = this.getAttribute('data-scale');
      if (v==='page-width' || v==='page-fit' || v==='page-actual' || v==='auto') pdfViewer.currentScaleValue = v;
      else pdfViewer.currentScale = Math.max(0.25, Math.min(4, parseFloat(v)));
      zoomMenu.style.display='none'; updateZoomLabel();
    };
  }

  document.getElementById('zoomIn').onclick  = function(){ pdfViewer.currentScale = Math.min(4, (pdfViewer.currentScale||1)*1.1); updateZoomLabel(); };
  document.getElementById('zoomOut').onclick = function(){ pdfViewer.currentScale = Math.max(0.25, (pdfViewer.currentScale||1)/1.1); updateZoomLabel(); };
  eventBus.on('scalechanging', updateZoomLabel);
  function updateZoomLabel(){
    var scale = pdfViewer.currentScale || 1;
    zoomLabel.textContent = String(Math.round(scale*100)) + '%';
  }

  /* Page nav */
  document.getElementById('prevPage').onclick = function(){ if (pdfViewer.currentPageNumber > 1) pdfViewer.currentPageNumber--; };
  document.getElementById('nextPage').onclick = function(){ if (pdfViewer.currentPageNumber < (pdfDoc ? pdfDoc.numPages : 1)) pdfViewer.currentPageNumber++; };
  pageNumber.addEventListener('keydown', function(e){
    if (e.key==='Enter' && pdfDoc){
      var v = parseInt(pageNumber.value,10);
      if (v>=1 && v<=pdfDoc.numPages) pdfViewer.currentPageNumber = v;
    }
  });

  // Sidebar toggle + reflow
  var sidebar = document.getElementById('sidebar');
  var open = false;
  sidebar.style.display = 'none';
  sheet.style.gridTemplateColumns = '1fr';
  document.getElementById('toggleSidebar').onclick = function(){
    open = !open;
    sidebar.style.display = open ? '' : 'none';
    sheet.style.gridTemplateColumns = open ? '260px 1fr' : '1fr';
    requestAnimationFrame(function(){ eventBus.dispatch('resize', { source: window }); });
  };

  new ResizeObserver(function(){ eventBus.dispatch('resize', { source: window }); }).observe(viewContainer);
  window.addEventListener('resize', function(){ eventBus.dispatch('resize', { source: window }); });

  // Block selection / copy
  document.addEventListener('keydown', function(e){
    var k = (e.key || '').toLowerCase();
    if ((e.ctrlKey || e.metaKey) && (k === 'a' || k === 'c')) {
      e.preventDefault();
    }
  });
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); });

  @if($leadEnabled)
  var modal = document.getElementById('leadModal');
  setTimeout(function(){ modal.style.display='flex'; }, 5000);
  document.getElementById('leadForm').addEventListener('submit', function(e){
    e.preventDefault();
    var fd = new FormData(this);
    fd.append('slug', slug);
    fetch('{{ route('public.lead.store') }}', {
      method:'POST',
      headers:{'X-CSRF-TOKEN': @json(csrf_token())},
      body: fd
    }).then(function(){ modal.style.display='none'; });
  });
  @endif
})();
</script>
</body>
</html>