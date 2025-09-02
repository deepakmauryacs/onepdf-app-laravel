@extends('vendor.layouts.app')

@section('title', 'Manage Files')

@section('content')
<div class="container py-3">
  <h1 class="h4 mb-3">Manage Files</h1>
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-end">
      <input id="searchInput" type="text" class="form-control" placeholder="Search files..." style="max-width:260px;">
    </div>
    <div class="table-responsive">
      <table class="table mb-0" id="filesTable">
        <thead>
          <tr>
            <th>File Name</th>
            <th style="width:120px;">Size</th>
            <th style="width:160px;">Modified</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div id="emptyState" class="p-3 text-center text-muted d-none">No files found.</div>
    </div>
  </div>
  <nav class="mt-3">
    <ul class="pagination" id="pager"></ul>
  </nav>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const routes = { list: @json(route('vendor.files.manage.list')) };
  let page = 1;
  let q = '';
  const tbody = document.querySelector('#filesTable tbody');
  const empty = document.getElementById('emptyState');
  const pager = document.getElementById('pager');

  function humanSize(bytes){
    if(!bytes && bytes!==0) return '\u2014';
    const u=['B','KB','MB','GB']; let i=0; let n=+bytes;
    while(n>=1024 && i<u.length-1){ n/=1024; i++; }
    return (i? n.toFixed(2):n)+' '+u[i];
  }

  function renderRows(files){
    tbody.innerHTML='';
    if(!files.length){ empty.classList.remove('d-none'); return; }
    empty.classList.add('d-none');
    for(const f of files){
      const tr=document.createElement('tr');
      tr.innerHTML=`<td>${f.filename}</td><td>${humanSize(f.size)}</td><td>${f.modified||''}</td>`;
      tbody.appendChild(tr);
    }
  }

  function renderPager(current,last){
    pager.innerHTML='';
    if(last<=1) return;
    const prev=document.createElement('li');
    prev.className='page-item'+(current===1?' disabled':'');
    prev.innerHTML=`<a class="page-link" href="#" data-page="${current-1}">&laquo;</a>`;
    pager.appendChild(prev);
    for(let i=1;i<=last;i++){
      const li=document.createElement('li');
      li.className='page-item'+(i===current?' active':'');
      li.innerHTML=`<a class="page-link" href="#" data-page="${i}">${i}</a>`;
      pager.appendChild(li);
    }
    const next=document.createElement('li');
    next.className='page-item'+(current===last?' disabled':'');
    next.innerHTML=`<a class="page-link" href="#" data-page="${current+1}">&raquo;</a>`;
    pager.appendChild(next);
  }

  async function load(p=1){
    page=p;
    const params=new URLSearchParams({page:p, search:q});
    const res=await fetch(routes.list+'?'+params.toString());
    const data=await res.json();
    renderRows(data.files||[]);
    renderPager(data.current_page,data.last_page);
  }

  document.getElementById('searchInput').addEventListener('input',function(){
    q=this.value;
    load(1);
  });

  pager.addEventListener('click',function(e){
    const a=e.target.closest('a[data-page]');
    if(!a) return;
    e.preventDefault();
    const p=parseInt(a.dataset.page,10);
    if(p>0) load(p);
  });

  load();
})();
</script>
@endpush
