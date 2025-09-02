@extends('vendor.layouts.app')

@section('title', 'Dashboard')

@section('content')
  <!-- Breadcrumb band -->
  <div class="bw-band">
    <ol class="bw-crumb">
      <li><a href="#"><i class="bi bi-house-door me-1"></i>Home</a></li>
      <li>Dashboard</li>
    </ol>
    <div class="d-flex gap-2">
      <div class="dropdown">
        <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-clock-history me-1"></i> Previous Year</button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Last 7 days</a></li>
          <li><a class="dropdown-item" href="#">Last 30 days</a></li>
          <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
      </div>
      <button class="btn btn-dark"><i class="bi bi-graph-up-arrow me-1"></i> View All Time</button>
    </div>
  </div>

  <!-- Files table card -->
  <div class="card mb-4">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="files-title">
          <i class="bi bi-folder2-open"></i> Files
          <span class="count">{{ $total }}</span>
        </div>
        <div class="files-search">
          <i class="bi bi-search"></i>
          <input type="text" class="form-control" placeholder="Search files..." id="searchInput">
        </div>
        <a href="{{ route('vendor.files.index') }}" class="btn btn-del">
          <span class="btn-del-text"><i class="bi bi-upload me-1"></i> Upload new</span>
          <span class="btn-del-icon"><i class="bi bi-upload"></i></span>
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:40px;"><input class="form-check-input" type="checkbox" /></th>
            <th>File Name</th>
            <th style="width:140px;">Size</th>
            <th style="width:180px;">Modified</th>
            <th style="width:140px;">Status</th>
            <th style="width:280px;">Actions</th>
          </tr>
        </thead>
        <tbody id="filesTbody">
@php
if (!function_exists('human_size')) {
    function human_size($bytes) {
        $units = ['B','KB','MB','GB','TB'];
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, $i ? 2 : 0) . ' ' . $units[$i];
    }
}
@endphp
@forelse ($files as $file)
          <tr data-name="{{ strtolower($file['filename']) }}">
            <td><input class="form-check-input" type="checkbox" /></td>
            <td>
              <div class="col-file">
                <span class="file-chip"><i class="bi bi-file-earmark"></i></span>
                <div class="file-name"><a href="{{ $file['url'] ?: '#' }}" target="_blank">{{ $file['filename'] }}</a></div>
              </div>
            </td>
            <td>{{ human_size($file['size']) }}</td>
            <td>
              @php($parts = explode(' ', $file['modified'] ?? ''))
              <div>{{ $parts[0] ?? '' }}</div>
              <small class="text-muted">{{ $parts[1] ?? '' }}</small>
            </td>
            <td><span class="status-pill">{{ $file['url'] ? 'Secure' : '—' }}</span></td>
            <td class="text-nowrap">
              @if($file['url'])
              <div class="d-flex gap-2 mb-2">
                <button class="btn btn-ghost flex-fill copy-btn" data-url="{{ $file['url'] }}"><i class="bi bi-clipboard me-1"></i>Copy</button>
                <a href="{{ $file['url'] }}" target="_blank" class="btn btn-ghost flex-fill"><i class="bi bi-box-arrow-up-right me-1"></i>Open</a>
              </div>
              <div class="small text-break text-muted">{{ $file['url'] }}</div>
              @else
              <a href="{{ route('vendor.files.manage') }}" class="btn btn-ghost flex-fill"><i class="bi bi-link-45deg me-1"></i>Generate</a>
              @endif
            </td>
          </tr>
@empty
          <tr>
            <td colspan="6" class="text-center text-muted">No files found.</td>
          </tr>
@endforelse
        </tbody>
      </table>
    </div>
    @if($last_page > 1)
    <div class="card-footer text-center">
      <button class="btn btn-dark" id="loadMoreBtn">Load More</button>
    </div>
    @endif
  </div>
@endsection

@push('scripts')
<script>
let currentPage = {{ $current_page ?? 1 }};
const lastPage = {{ $last_page ?? 1 }};
const listUrl = "{{ route('dashboard.list') }}";
const manageUrl = "{{ route('vendor.files.manage') }}";

function human_size(bytes) {
  const units = ['B','KB','MB','GB','TB'];
  let i = 0;
  while (bytes >= 1024 && i < units.length - 1) {
    bytes /= 1024;
    i++;
  }
  return (i ? bytes.toFixed(2) : bytes) + ' ' + units[i];
}

document.getElementById('searchInput').addEventListener('input', function(){
  const q = this.value.toLowerCase();
  document.querySelectorAll('#filesTbody tr').forEach(function(tr){
    const name = tr.dataset.name || '';
    tr.style.display = name.includes(q) ? '' : 'none';
  });
});

document.addEventListener('click', function(e){
  const btn = e.target.closest('.copy-btn');
  if(!btn) return;
  navigator.clipboard.writeText(btn.dataset.url).then(function(){
    btn.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied';
    setTimeout(function(){
      btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copy';
    }, 2000);
  });
});

function renderRow(file){
  const parts = (file.modified || '').split(' ');
  const date = parts[0] || '';
  const time = parts[1] || '';
  return `
    <tr data-name="${file.filename.toLowerCase()}">
      <td><input class="form-check-input" type="checkbox" /></td>
      <td>
        <div class="col-file">
          <span class="file-chip"><i class="bi bi-file-earmark"></i></span>
          <div class="file-name"><a href="${file.url || '#'}" target="_blank">${file.filename}</a></div>
        </div>
      </td>
      <td>${human_size(file.size)}</td>
      <td><div>${date}</div><small class="text-muted">${time}</small></td>
      <td><span class="status-pill">${file.url ? 'Secure' : '—'}</span></td>
      <td class="text-nowrap">
        ${file.url ? `
          <div class="d-flex gap-2 mb-2">
            <button class="btn btn-ghost flex-fill copy-btn" data-url="${file.url}"><i class="bi bi-clipboard me-1"></i>Copy</button>
            <a href="${file.url}" target="_blank" class="btn btn-ghost flex-fill"><i class="bi bi-box-arrow-up-right me-1"></i>Open</a>
          </div>
          <div class="small text-break text-muted">${file.url}</div>
        ` : `
          <a href="${manageUrl}" class="btn btn-ghost flex-fill"><i class="bi bi-link-45deg me-1"></i>Generate</a>
        `}
      </td>
    </tr>
  `;
}

const loadMoreBtn = document.getElementById('loadMoreBtn');
if(loadMoreBtn){
  if(currentPage >= lastPage){
    loadMoreBtn.style.display = 'none';
  }
  loadMoreBtn.addEventListener('click', function(){
    fetch(listUrl + '?page=' + (currentPage + 1))
      .then(res => res.json())
      .then(data => {
        data.files.forEach(file => {
          document.getElementById('filesTbody').insertAdjacentHTML('beforeend', renderRow(file));
        });
        currentPage = data.current_page;
        if(currentPage >= data.last_page){
          loadMoreBtn.style.display = 'none';
        }
      });
  });
}
</script>
@endpush
