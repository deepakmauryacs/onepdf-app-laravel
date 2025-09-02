<?php
require_once __DIR__ . '/config.php';

$slug = $_GET['code'] ?? '';
if (!$slug) {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (preg_match('~/file/([a-zA-Z0-9]+)~', $uri, $m)) { $slug = $m[1]; }
}
if (!$slug) { echo 'Missing link code'; exit; }

// Fetch link information and permissions
$stmt = $mysqli->prepare("SELECT l.id, l.permissions FROM links l WHERE l.slug=?");
$stmt->bind_param('s', $slug);
$stmt->execute();
$stmt->bind_result($linkId, $permJson);
if (!$stmt->fetch()) { echo 'Invalid link'; exit; }
$stmt->close();

$basePath   = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
$pdfUrl     = $basePath . '/get_pdf.php?code=' . urlencode($slug);
$downloadUrl = $pdfUrl . '&download=1';

$perms = json_decode($permJson, true) ?? [];
if (!empty($perms['analytics'])) {
    $ins = $mysqli->prepare("INSERT INTO link_analytics (link_id, event) VALUES (?, 'view')");
    $ins->bind_param('i', $linkId);
    $ins->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Premium PDF Viewer</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.css"/>

<link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
<link rel="manifest" href="assets/favicon_io/site.webmanifest">
<link rel="shortcut icon" href="assets/favicon_io/favicon.ico">

<style>
  :root{
    --ui-bg:#111315; --ui-bar:#2b3034; --ui-bar-darker:#23272b;
    --ui-ink:#ffffff; --ui-muted:#9aa4b2; --ui-border:#3d434b; --ui-accent:#4c8bf5; --side-w:260px;
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{ margin:0; height:100vh; height:100dvh; overflow:hidden; background:var(--ui-bg); color:var(--ui-ink); font-family:"DM Sans",system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif; }

  .topbar{ height:48px; background:var(--ui-bar); display:flex; align-items:center; gap:10px; padding:0 16px; border-bottom:1px solid var(--ui-border); }
  .tb{ height:32px; min-width:32px; border:0; color:var(--ui-ink); background:transparent; border-radius:4px; display:inline-flex; align-items:center; justify-content:center; padding:0 8px; cursor:pointer }
  .tb:hover{ background:rgba(255,255,255,0.1) }
  .tb i{ font-size:16px }
  .spacer{flex:1 1 auto}

  .zoom-wrap{position:relative}
  .zoom-btn{display:inline-flex;align-items:center;gap:6px;font-weight:600;color:var(--ui-ink)}
  .zoom-menu{ position:absolute; top:38px; left:0; min-width:180px; background:color-mix(in srgb, var(--ui-bar) 70%, black 10%); border:1px solid var(--ui-border); border-radius:8px; padding:6px; display:none; z-index:40 }
  .zoom-menu .item{ display:flex; align-items:center; height:34px; padding:0 10px; border-radius:8px; color:var(--ui-ink); cursor:pointer; white-space:nowrap }
  .zoom-menu .item:hover{ background:color-mix(in srgb, var(--ui-bar) 50%, black 10%) }

  /* GRID: second column must be minmax(0,1fr) */
  .sheet{ display:grid; grid-template-columns: var(--side-w) minmax(0,1fr); height: calc(100vh - 48px); height: calc(100dvh - 48px); }
  .sheet.hide-side{ grid-template-columns: 0 minmax(0,1fr); }

  .sidebar{ border-right:1px solid var(--ui-border); background:color-mix(in srgb, var(--ui-bg) 80%, black 10%); display:flex; flex-direction:column; min-width:0; overflow:hidden; }
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

  .thumb{ border:1px solid var(--ui-border); border-radius:10px; background:color-mix(in srgb, var(--ui-bg) 92%, black 8%); margin-bottom:12px; padding:6px; cursor:pointer }
  .thumb.selected{ outline:2px solid var(--ui-accent); outline-offset:2px }
  .thumb canvas{ width:100%; display:block }

  /* CRITICAL: min-width:0 on flex/grid children so they can shrink/grow */
  .viewer{ position:relative; background:color-mix(in srgb, var(--ui-bg) 70%, black 10%); height:100%; min-width:0; }
  #viewerContainer{ position:absolute; inset:0; overflow:auto; width:100%; height:100%; min-width:0; }
  #viewer{ min-width:0; }
  .pdfViewer .page{ margin:10px auto; background:#fff; border-radius:10px; border:1px solid #d7dae0 }

  ::-webkit-scrollbar{ width:10px; height:10px }
  ::-webkit-scrollbar-thumb{ background:color-mix(in srgb, var(--ui-border) 40%, var(--ui-ink) 40%); border-radius:999px }
  ::-webkit-scrollbar-track{ background:color-mix(in srgb, var(--ui-bg) 70%, black 15%) }

  .page-info{ display:flex; align-items:center; gap:6px; color:var(--ui-ink) }
  .page-info input{ width:40px; height:26px; border:1px solid var(--ui-border); background:var(--ui-bar-darker); color:var(--ui-ink); text-align:center; border-radius:4px }

  /* Prevent text selection inside the PDF viewer */
  #viewerContainer, #viewer, .pdfViewer .textLayer{
    -webkit-user-select:none;
    -moz-user-select:none;
    -ms-user-select:none;
    user-select:none;
  }

  @media (max-width: 600px){
    .topbar{ padding:0 8px; gap:6px; }
    .tb{ height:28px; min-width:28px; }
    .page-info input{ width:32px; height:24px; }
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

  <?php if (!empty($perms['download'])): ?>
    <a class="tb" id="downloadBtn" title="Download" href="<?php echo htmlspecialchars($downloadUrl); ?>" download>
      <i class="bi bi-download"></i>
    </a>
  <?php endif; ?>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf_viewer.min.js"></script>
<script>
(() => {
  pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";
  const pdfUrl = <?php echo json_encode($pdfUrl); ?>;
  const slug   = <?php echo json_encode($slug); ?>;

  const pageNumber = document.getElementById('pageNumber');
  const pageCount  = document.getElementById('pageCount');
  const sheet      = document.getElementById('sheet');
  const container  = document.getElementById('viewerContainer');

  const eventBus   = new pdfjsViewer.EventBus();

  const linkSvc    = new pdfjsViewer.PDFLinkService({ eventBus });
  const pdfHistory = new pdfjsViewer.PDFHistory({ eventBus, linkService: linkSvc });
  linkSvc.setHistory(pdfHistory);

  const pdfViewer  = new pdfjsViewer.PDFViewer({
    container, eventBus, linkService: linkSvc,
    maxCanvasPixels: 0, textLayerMode: 2, removePageBorders: true
  });
  linkSvc.setViewer(pdfViewer);

  let pdfDoc = null;
  const thumbTab    = document.getElementById('thumbTab');
  const outlineTab  = document.getElementById('outlineTab');
  const thumbView   = document.getElementById('thumbnailView');
  const outlineView = document.getElementById('outlineView');

  pdfjsLib.getDocument(pdfUrl).promise.then(async (doc) => {
    pdfDoc = doc;
    linkSvc.setDocument(doc, null);
    pdfHistory.initialize({ fingerprint: doc.fingerprints?.[0] || String(Date.now()) });
    pdfViewer.setDocument(doc);
    // Use page-fit on small screens to show the whole page by default
    pdfViewer.currentScaleValue = window.matchMedia('(max-width: 600px)').matches ? 'page-fit' : 'page-width';
    updateZoomLabel();

    await buildThumbnails(doc);
    const outline = await doc.getOutline();
    buildOutline(outline);

    if (outline && outline.length) {
      outlineTab.classList.add('active');
      thumbTab.classList.remove('active');
      outlineView.style.display = 'block';
      thumbView.style.display = 'none';
    } else {
      thumbTab.classList.add('active');
      outlineTab.classList.remove('active');
      thumbView.style.display = 'block';
      outlineView.style.display = 'none';
    }
    pageCount.textContent = doc.numPages;
  }).catch(err => {
    console.error(err);
    alert('Failed to load PDF.');
  });

  /* Tabs */
  thumbTab.onclick = () => {
    thumbTab.classList.add('active');
    outlineTab.classList.remove('active');
    thumbView.style.display = 'block';
    outlineView.style.display = 'none';
  };
  outlineTab.onclick = () => {
    outlineTab.classList.add('active');
    thumbTab.classList.remove('active');
    outlineView.style.display = 'block';
    thumbView.style.display = 'none';
  };

  /* Thumbnails */
  async function buildThumbnails(doc){
    thumbView.innerHTML = '';
    const maxW = 220;
    for (let i=1;i<=doc.numPages;i++){
      const page = await doc.getPage(i);
      const v0 = page.getViewport({ scale:1 });
      const scale = maxW / v0.width;
      const viewport = page.getViewport({ scale });

      const wrap = document.createElement('div');
      wrap.className = 'thumb'; wrap.dataset.page = i;

      const cv = document.createElement('canvas');
      cv.width  = Math.floor(viewport.width);
      cv.height = Math.floor(viewport.height);
      const ctx = cv.getContext('2d', { alpha:false });

      wrap.appendChild(cv);
      thumbView.appendChild(wrap);

      page.render({ canvasContext: ctx, viewport }).promise.then(()=> page.cleanup?.());
      wrap.onclick = () => { pdfViewer.currentPageNumber = i; };
    }
    selectThumb(1, true);
  }
  function selectThumb(n, center=false){
    [...thumbView.querySelectorAll('.thumb')].forEach(el => el.classList.toggle('selected', +el.dataset.page === +n));
    const el = thumbView.querySelector(`.thumb[data-page="${n}"]`);
    if (center && el) el.scrollIntoView({ block:'nearest' });
  }
  eventBus.on('pagechanging', (e) => {
    const n = e.pageNumber || pdfViewer.currentPageNumber;
    selectThumb(n, true);
    pageNumber.value = n;
    fetch('track.php', {
      method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'event=page&slug='+encodeURIComponent(slug)+'&page='+n
    }).catch(()=>{});
  });

  /* Outline navigation */
  function buildOutline(outline){
    outlineView.innerHTML = '';
    if (!outline){ outlineView.textContent = 'No outline available'; return; }
    const render = (items, parent) => {
      const ul = document.createElement('ul');
      parent.appendChild(ul);
      items.forEach(it => {
        const li = document.createElement('li');
        ul.appendChild(li);
        const row = document.createElement('div');
        row.className = 'outline-item';
        li.appendChild(row);
        const text = document.createElement('span');
        text.textContent = it.title || '';
        text.style.flex = '1';
        row.appendChild(text);
        row.addEventListener('click', ()=>{
          if (it.url) window.open(it.url, '_blank', 'noopener,noreferrer');
          if (it.dest) linkSvc.goToDestination(it.dest);
        });
        if (it.items && it.items.length){
          const child = document.createElement('div');
          child.className = 'outline-children';
          li.appendChild(child);
          render(it.items, child);
        }
      });
    };
    render(outline, outlineView);
  }

  /* Zoom */
  const zoomBtn   = document.getElementById('zoomBtn');
  const zoomMenu  = document.getElementById('zoomMenu');
  const zoomLabel = document.getElementById('zoomLabel');

  document.addEventListener('click', ()=>{ zoomMenu.style.display='none'; });
  zoomBtn.onclick = (e)=>{ e.stopPropagation(); zoomMenu.style.display = (zoomMenu.style.display==='block'?'none':'block'); };
  zoomMenu.querySelectorAll('.item').forEach(it=>{
    it.onclick = ()=>{
      const v = it.dataset.scale;
      if (['page-width','page-fit','page-actual','auto'].includes(v)) pdfViewer.currentScaleValue = v;
      else pdfViewer.currentScale = Math.max(0.25, Math.min(4, parseFloat(v)));
      zoomMenu.style.display='none'; updateZoomLabel();
    };
  });

  document.getElementById('zoomIn').onclick  = ()=>{ pdfViewer.currentScale = Math.min(4, (pdfViewer.currentScale||1)*1.1); updateZoomLabel(); };
  document.getElementById('zoomOut').onclick = ()=>{ pdfViewer.currentScale = Math.max(0.25, (pdfViewer.currentScale||1)/1.1); updateZoomLabel(); };
  eventBus.on('scalechanging', updateZoomLabel);
  function updateZoomLabel(){
    zoomLabel.textContent = Math.round((pdfViewer.currentScale||1)*100) + '%';
  }

  /* Page navigation */
  document.getElementById('prevPage').onclick = ()=>{ if (pdfViewer.currentPageNumber > 1) pdfViewer.currentPageNumber--; };
  document.getElementById('nextPage').onclick = ()=>{ if (pdfViewer.currentPageNumber < (pdfDoc?.numPages||1)) pdfViewer.currentPageNumber++; };
  pageNumber.addEventListener('keydown', (e)=>{
    if (e.key==='Enter' && pdfDoc){
      const v = parseInt(pageNumber.value,10);
      if (v>=1 && v<=pdfDoc.numPages) pdfViewer.currentPageNumber = v;
    }
  });

  /* ===== Sidebar toggle: hard reflow to avoid blank page ===== */
  const relayout = () => eventBus.dispatch('resize', { source: window });
  function relayoutHard(){
    requestAnimationFrame(()=> {
      const prev = pdfViewer.currentScaleValue || String(pdfViewer.currentScale || 'page-width');
      pdfViewer.currentScaleValue = 'auto'; // force measure
      requestAnimationFrame(()=> {
        pdfViewer.currentScaleValue = prev; // restore
        relayout();
        // nudge scroll (fixes rare black canvas top)
        const t = container.scrollTop; container.scrollTop = t + 1; container.scrollTop = t;
      });
    });
  }

  /* ---------- Sidebar toggle ---------- */
  const sidebar = document.getElementById('sidebar');
  let open = false;
  sidebar.style.display = 'none';
  sheet.style.gridTemplateColumns = '1fr';
  document.getElementById('toggleSidebar').onclick = ()=>{
    open = !open;
    sidebar.style.display = open ? '' : 'none';
    sheet.style.gridTemplateColumns = open ? '260px 1fr' : '1fr';
    // Avoid setting an invalid scale value when the sidebar toggles.
    // Using the harder relayout routine ensures the viewer reflows
    // without attempting to apply a "null" zoom level.
    setTimeout(relayoutHard, 50);
  };

  // Keep stable on any future container size change
  new ResizeObserver(()=> relayout()).observe(container);
  window.addEventListener('resize', relayout);

  // Disable common copy/selection shortcuts and context menu
  document.addEventListener('keydown', (e) => {
    if (e.ctrlKey || e.metaKey) {
      const key = e.key.toLowerCase();
      if (key === 'a' || key === 'c') {
        e.preventDefault();
      }
    }
  });
  document.addEventListener('contextmenu', (e) => e.preventDefault());
})();
</script>
</body>
</html>
