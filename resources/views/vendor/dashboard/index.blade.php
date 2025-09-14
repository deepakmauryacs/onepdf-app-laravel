@extends('vendor.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
  /* ===== Modern centered pagination (black & white) ===== */
  .pagination-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination-modern{gap:8px}
  .pagination-modern .page-link{
    border:1px solid #eaeef3;background:#fff;color:#111;border-radius:12px;
    min-width:42px;height:42px;padding:0 12px;display:flex;align-items:center;justify-content:center;
    font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,.04);
  }
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
  .pagination-modern .ellipsis>.page-link{pointer-events:none}
  .pagination-modern .page-link .bi{margin:0;font-size:16px}
</style>
@endpush

@section('content')
  <!-- Breadcrumb band -->
  <div class="bw-band">
    <ol class="bw-crumb">
      <li><a href="#"><i class="bi bi-house-door me-1"></i>Home</a></li>
      <li>Dashboard</li>
    </ol>
      <div class="d-flex gap-2">
        <div class="dropdown">
          @php
            $label = [
              7  => 'Last 7 days',
              15 => 'Last 15 days',
              30 => 'Last 30 days',
            ][$days] ?? 'Last 7 days';
          @endphp
          <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-clock-history me-1"></i> {{ $label }}</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('vendor.dashboard', ['days' => 7]) }}">Last 7 days</a></li>
            <li><a class="dropdown-item" href="{{ route('vendor.dashboard', ['days' => 15]) }}">Last 15 days</a></li>
            <li><a class="dropdown-item" href="{{ route('vendor.dashboard', ['days' => 30]) }}">Last 30 days</a></li>
          </ul>
        </div>
        <a href="{{ route('vendor.files.index') }}" class="btn btn-dark"><i class="bi bi-card-list me-1"></i> View All Time</a>
      </div>
  </div>

  <!-- Files table card -->
  <div class="card mb-4">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="files-title">
          <i class="bi bi-folder2-open"></i> Files
          <span class="count">{{ $files->total() }}</span>
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
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) { $bytes /= 1024; }
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
@php $parts = explode(' ', $file['modified'] ?? ''); @endphp
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

    @if ($files->hasPages())
@php
  $current = $files->currentPage();
  $last    = $files->lastPage();
  $total   = $files->total();
  $perPage = $files->perPage();
  $from    = ($current - 1) * $perPage + 1;
  $to      = min($total, $current * $perPage);
  $window  = 5;
  $start   = max(1, $current - intdiv($window,2));
  $end     = min($last, $start + $window - 1);
  if (($end - $start + 1) < $window) { $start = max(1, $end - $window + 1); }
  $params  = request()->except('page');
@endphp
    <div class="card-footer">
      <nav class="pagination-wrap" aria-label="Files pagination">
        @if($total > 0)
        <div class="pager-summary">Showing {{ number_format($from) }}–{{ number_format($to) }} of {{ number_format($total) }}</div>
        @endif
        <ul class="pagination pagination-modern justify-content-center mb-0">
          <li class="page-item {{ $current===1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $current===1 ? '#' : $files->appends($params)->url(1) }}"><i class="bi bi-chevron-double-left"></i></a>
          </li>
          <li class="page-item {{ $current===1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $current===1 ? '#' : $files->appends($params)->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
          </li>

          @if($start > 1)
            <li class="page-item"><a class="page-link" href="{{ $files->appends($params)->url(1) }}">1</a></li>
            @if($start > 2)
              <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
            @endif
          @endif

          @for($i=$start; $i<=$end; $i++)
            <li class="page-item {{ $i===$current ? 'active' : '' }}">
              <a class="page-link" href="{{ $i===$current ? '#' : $files->appends($params)->url($i) }}">{{ $i }}</a>
            </li>
          @endfor

          @if($end < $last)
            @if($end < $last-1)
              <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
            @endif
            <li class="page-item"><a class="page-link" href="{{ $files->appends($params)->url($last) }}">{{ $last }}</a></li>
          @endif

          <li class="page-item {{ $current===$last ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $current===$last ? '#' : $files->appends($params)->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
          </li>
          <li class="page-item {{ $current===$last ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $current===$last ? '#' : $files->appends($params)->url($last) }}"><i class="bi bi-chevron-double-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>
    @endif
  </div>
@endsection

@push('scripts')
<script>
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
    setTimeout(function(){ btn.innerHTML = '<i class="bi bi-clipboard me-1"></i>Copy'; }, 2000);
  });
});
</script>
@endpush
