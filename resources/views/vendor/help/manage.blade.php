@extends('vendor.layouts.app')

@section('title', 'Help Requests')

@push('styles')
<style>
  .top-band{background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%), linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%); border-bottom:1px solid #eaeef3;}
  .crumb{display:flex;align-items:center;gap:.5rem;font-size:.95rem;color:#64748b;}
  .crumb a{color:#0f172a;text-decoration:none;}
</style>
@endpush

@section('content')
<div class="top-band">
  <div class="container py-3">
    <nav class="crumb">
      <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
      <i class="bi bi-chevron-right"></i>
      <span>Help</span>
    </nav>
  </div>
</div>

<div class="container py-3">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Help Requests</h5>
      <button id="btnNew" class="btn btn-primary">New Request</button>
    </div>
    <div class="table-responsive">
      <table class="table mb-0" id="helpTable">
        <thead>
          <tr>
            <th>Subject</th>
            <th>Status</th>
            <th>Agent</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div id="empty" class="p-3 text-center d-none">No requests found.</div>
    </div>
    <div class="card-body d-none" id="formWrap">
      <form id="helpForm" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
          <label class="form-label">Subject</label>
          <input name="subject" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Message</label>
          <textarea name="message" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Attachment</label>
          <input name="attachment" type="file" class="form-control">
        </div>
        <button class="btn btn-success" type="submit">Submit</button>
      </form>
    </div>
  </div>
  <nav class="mt-3">
    <ul class="pagination pagination-modern justify-content-center" id="pager"></ul>
    <div id="pagerSummary" class="pager-summary text-center"></div>
  </nav>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const routes = {
    list: '{{ route('vendor.help.manage.list') }}',
    store: '{{ route('vendor.help.store') }}'
  };
  let page = 1; let q = '';
  const tbody = document.querySelector('#helpTable tbody');
  const empty = document.getElementById('empty');
  const pager = document.getElementById('pager');
  const pagerSummary = document.getElementById('pagerSummary');

  function rowTemplate(r){
    return `<tr><td>${r.subject}</td><td>${r.status}</td><td>${r.agent_name ?? ''}</td><td>${r.created_at}</td></tr>`;
  }
  function renderRows(list,total){
    tbody.innerHTML='';
    if(!list.length){ empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');
    tbody.innerHTML=list.map(rowTemplate).join('');
  }
  function renderPager(current,last,total=null,perPage=null){
    pager.innerHTML='';
    if(total!=null && perPage!=null){
      const start=(current-1)*perPage+1;
      const end=Math.min(total,current*perPage);
      pagerSummary.textContent=`Showing ${start}–${end} of ${total}`;
    }else pagerSummary.textContent='';
    if(last<=1) return;
    for(let i=1;i<=last;i++){
      const li=document.createElement('li');
      li.className='page-item'+(i===current?' active':'');
      li.innerHTML=`<a class="page-link" href="#" data-page="${i}">${i}</a>`;
      pager.appendChild(li);
    }
  }
  async function load(p=1){
    page=p;
    const res=await fetch(routes.list+'?page='+p+'&search='+encodeURIComponent(q));
    const data=await res.json();
    renderRows(data.requests||[], data.total);
    renderPager(data.current_page||1, data.last_page||1, data.total, data.per_page);
  }
  pager.addEventListener('click',e=>{
    const a=e.target.closest('a[data-page]');
    if(!a) return;
    e.preventDefault();
    load(parseInt(a.dataset.page,10));
  });
  document.getElementById('btnNew').addEventListener('click',()=>{
    document.getElementById('formWrap').classList.toggle('d-none');
  });
  document.getElementById('helpForm').addEventListener('submit',async e=>{
    e.preventDefault();
    const form=e.target;
    const fd=new FormData(form);
    const res=await fetch(routes.store,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},body:fd});
    if(res.ok){ form.reset(); document.getElementById('formWrap').classList.add('d-none'); load(); }
  });
  load();
})();
</script>
@endpush
