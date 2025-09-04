@extends('vendor.layouts.app')

@section('title', 'Edit Lead Form')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{ --surface:#ffffff; --muted:#6b7280; --line:#e5e7eb; --ring:#d9dde3; --text:#0f172a; --radius:12px; }
  *{ font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; }

  /* ===== top-band / breadcrumb (same as Upload Files) ===== */
  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{ display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b; }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  /* ===== Cards & sections ===== */
  .cardish{ border:1px solid var(--line); border-radius:12px; background:var(--surface); box-shadow:0 6px 16px rgba(0,0,0,.06); }
  .section-title{ font-weight:700; color:#111; }

  /* ===== Palette (left column) ===== */
  .palette{ border:1px solid var(--line); border-radius:12px; background:#fff; }
  .palette-header{ padding:12px 16px; border-bottom:1px solid var(--line); font-weight:700; }
  .palette-body{ padding:14px; }
  .draggable-field{ 
    border:1px solid var(--line); 
    background:#f8fafc; 
    color:#111;
    border-radius:12px; 
    padding:10px 12px; 
    font-weight:600;
    display:flex; align-items:center; gap:8px; 
    transition:transform .06s ease, background .2s ease; 
    cursor:grab;
  }
  .draggable-field:hover{ background:#f3f4f6; transform:translateY(-1px); }
  .draggable-field i{ color:#6b7280; }

  /* ===== Builder canvas (right column) ===== */
  #builder-canvas{
    min-height:280px; 
    border:2px dashed #d1d5db; 
    border-radius:12px; 
    padding:16px; 
    background:#f9fafb;
  }
  #builder-canvas.is-dragover{ border-color:#8b5cf6; background:#f5f3ff; }
  .canvas-empty{
    color:#6b7280; text-align:center; padding:36px;
  }

  /* Field chips inside canvas */
  .field-item{
    border:1px solid var(--line); border-radius:12px; background:#fff; padding:12px; 
    display:flex; align-items:center; gap:10px; 
  }
  .field-handle{ 
    width:34px; height:34px; border-radius:10px; display:grid; place-items:center; 
    background:#eef2ff; color:#4338ca; flex:0 0 auto;
    cursor:grab;
  }
  .field-meta{ flex:1; min-width:0; }
  .field-actions{ display:flex; gap:8px; }
  .btn-icon{
    display:inline-flex; align-items:center; justify-content:center;
    width:40px; height:40px; padding:0; border-radius:12px; 
    border:1px solid #ffd7d7; background:#fff5f5; color:#b42318;
  }
  .btn-icon:hover{ background:#ffe8e8; }

  /* Ghost buttons match Upload Files page */
  .btn-ghost{
    display:inline-flex; align-items:center; justify-content:center;
    height:44px; border-radius:12px; font-weight:700; 
    background:#fff; border:1px solid var(--line); padding:0 16px; color:#111;
  }
  .btn-ghost .bi{ margin-right:8px; }
  .btn-ghost:hover{ background:#f7f9fc; }
  .btn-ghost:disabled{ opacity:.5; cursor:not-allowed; }

  /* Toasts (colored) */
  .toast-success { background:#16a34a !important; color:#fff !important; }
  .toast-error   { background:#dc2626 !important; color:#fff !important; }
  .toast-info    { background:#2563eb !important; color:#fff !important; }
  .toast-warning { background:#d97706 !important; color:#fff !important; }
  .toast .toast-header{ background:transparent !important; color:inherit !important; border-bottom:0 !important; }
  .toast .btn-close{ filter: invert(1); }

  /* Modal polish */
  .modal-content{ border:0; border-radius:16px; box-shadow:0 20px 60px rgba(2,6,23,.14); }
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
          <a href="{{ route('vendor.lead_forms.index') }}">Lead Forms</a>
          <i class="bi bi-chevron-right"></i>
          <span>Edit: {{ $form->name }}</span>
        </nav>
      </div>
    </div>
  </div>

  <div class="container py-3">

    {{-- Toast (same block as Upload Files) --}}
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

    <div class="d-flex align-items-center mb-3">
      <h1 class="h4 mb-0">Edit Lead Form</h1>
    </div>

    <div class="row g-3">
      <!-- Palette -->
      <div class="col-lg-4"> 
        <div class="palette">
          <div class="palette-header d-flex align-items-center">
            <i class="bi bi-ui-checks-grid me-2"></i> Field Library
          </div>
          <div class="palette-body" id="field-palette">
            <div class="draggable-field mb-2" draggable="true" data-type="text"><i class="bi bi-input-cursor-text"></i> Text</div>
            <div class="draggable-field mb-2" draggable="true" data-type="email"><i class="bi bi-envelope"></i> Email</div>
            <div class="draggable-field mb-2" draggable="true" data-type="textarea"><i class="bi bi-card-text"></i> Textarea</div>
            <div class="draggable-field mb-2" draggable="true" data-type="number"><i class="bi bi-123"></i> Number</div>
            <div class="draggable-field mb-2" draggable="true" data-type="date"><i class="bi bi-calendar-event"></i> Date</div>
            <div class="draggable-field mb-2" draggable="true" data-type="select"><i class="bi bi-caret-down-square"></i> Dropdown</div>
            <div class="draggable-field mb-2" draggable="true" data-type="radio"><i class="bi bi-record-circle"></i> Radio</div>
            <div class="draggable-field"      draggable="true" data-type="checkbox"><i class="bi bi-check2-square"></i> Checkbox</div>
          </div>
        </div>
      </div>

      <!-- Canvas -->
      <div class="col-lg-8">
        <div class="cardish">
          <div class="p-3 border-bottom" style="border-color:var(--line)!important;">
            <div class="d-flex align-items-center justify-content-between">
              <div class="section-title d-flex align-items-center gap-2">
                <i class="bi bi-layout-wtf"></i> Form Preview
              </div>
              <div class="d-flex gap-2">
                <button id="btnClearCanvas" type="button" class="btn-ghost"><i class="bi bi-eraser"></i>Clear</button>
                <button id="btnReorder" type="button" class="btn-ghost"><i class="bi bi-cursor"></i>Reorder</button>
              </div>
            </div>
          </div>
          <div class="p-3">
            <form id="builder-canvas">
              <div class="canvas-empty" id="canvas-empty">
                <div class="mb-2"><i class="bi bi-mouse2"></i></div>
                Drag fields here to build your form.
              </div>
            </form>
          </div>
        </div>

        <!-- Actions -->
        <form method="post" action="{{ route('vendor.lead_forms.update', $form) }}" class="d-flex flex-wrap gap-2 mt-3">
          @csrf
          @method('put')
          <input type="hidden" name="fields" id="fields-input">
          <button class="btn-ghost" type="submit"><i class="bi bi-save2"></i>Save changes</button>
          <a href="{{ route('vendor.lead_forms.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i>Back to list</a>
        </form>
      </div>
    </div>

    <!-- Field modal -->
    <div class="modal fade" id="fieldModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-sliders2-vertical me-2"></i>Field Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Label</label>
              <input type="text" class="form-control" id="field-label" placeholder="e.g., Full Name" />
            </div>
            <div class="mb-3" id="options-wrapper">
              <label class="form-label">Options (comma separated)</label>
              <input type="text" class="form-control" id="field-options" placeholder="Option A, Option B, Option C" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-ghost"><i class="bi bi-check2-circle"></i>Save</button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
  // Reusable toast (same behavior as Upload Files page)
  (function(){
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
    window.uiToast = toast; // expose to your builder JS if needed
  })();

  // Small UX touches to mirror upload UI feel
  (function(){
    const canvas = document.getElementById('builder-canvas');
    const empty  = document.getElementById('canvas-empty');

    // If your lead-form-builder.js dispatches events, these keep the visuals in sync:
    window.addEventListener('lfb:canvas:changed', function(e){
      const hasFields = (e.detail && e.detail.count>0);
      if(hasFields) empty?.classList.add('d-none'); else empty?.classList.remove('d-none');
    });

    // Optional: visual dragover feedback if your builder supports native DnD
    ['dragenter','dragover'].forEach(ev => canvas.addEventListener(ev, (e)=>{ e.preventDefault(); canvas.classList.add('is-dragover'); }));
    ['dragleave','drop'].forEach(ev => canvas.addEventListener(ev, (e)=>{ e.preventDefault(); canvas.classList.remove('is-dragover'); }));

    // Clear button (let your builder handle actual data clearing via a custom event)
    document.getElementById('btnClearCanvas')?.addEventListener('click', ()=>{
      window.dispatchEvent(new CustomEvent('lfb:action:clear'));
      window.uiToast && window.uiToast('Canvas cleared.','info');
    });

    // Reorder toggle (if supported)
    document.getElementById('btnReorder')?.addEventListener('click', ()=>{
      window.dispatchEvent(new CustomEvent('lfb:action:toggleReorder'));
      window.uiToast && window.uiToast('Reorder mode toggled.','info');
    });
  })();
</script>

{{-- Keep your main builder logic (unchanged) --}}
<script>
  window.existingFields = @json($form->fields ?? []);
</script>
@vite('resources/js/lead-form-builder.js')
@endpush
