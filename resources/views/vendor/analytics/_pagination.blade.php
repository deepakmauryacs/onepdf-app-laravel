{{-- resources/views/vendor/analytics/_pagination.blade.php --}}
@if(method_exists($documents, 'total') && $documents->lastPage() > 1)
  @php
    $current = $documents->currentPage();
    $last    = $documents->lastPage();
    $perPage = $documents->perPage();
    $total   = $documents->total();
    $startNo = ($current - 1) * $perPage + 1;
    $endNo   = min($total, $current * $perPage);

    $maxWindow = 5;
    $winStart = max(1, $current - intdiv($maxWindow,2));
    $winEnd   = min($last, $winStart + $maxWindow - 1);
    if(($winEnd - $winStart + 1) < $maxWindow){
      $winStart = max(1, $winEnd - $maxWindow + 1);
    }
    function pageUrl($n){ return request()->fullUrlWithQuery(['page'=>$n]); }
  @endphp
  <nav class="mt-3 mb-4 pagination-wrap" aria-label="All documents pagination" id="ajaxPagination">
    <!-- <div class="pager-summary" id="pagerSummary">Showing {{ number_format($startNo) }}–{{ number_format($endNo) }} of {{ number_format($total) }}</div> -->
    <ul class="pagination pagination-modern justify-content-center mb-0" id="paginationLinks">
      {{-- First / Prev --}}
      <li class="page-item {{ $current==1?'disabled':'' }}">
        <a class="page-link" href="{{ $current==1 ? '#' : pageUrl(1) }}" data-page="1"><i class="bi bi-chevron-double-left"></i></a>
      </li>
      <li class="page-item {{ $current==1?'disabled':'' }}">
        <a class="page-link" href="{{ $current==1 ? '#' : pageUrl($current-1) }}" data-page="{{ $current-1 }}"><i class="bi bi-chevron-left"></i></a>
      </li>
      {{-- Left edge + ellipsis --}}
      @if($winStart > 1)
        <li class="page-item {{ 1==$current?'active':'' }}"><a class="page-link" href="{{ pageUrl(1) }}" data-page="1">1</a></li>
        @if($winStart > 2)
          <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
        @endif
      @endif
      {{-- Window --}}
      @for($i=$winStart; $i<=$winEnd; $i++)
        <li class="page-item {{ $i==$current?'active':'' }}">
          <a class="page-link" href="{{ $i==$current ? '#' : pageUrl($i) }}" data-page="{{ $i }}">{{ $i }}</a>
        </li>
      @endfor
      {{-- Right ellipsis + edge --}}
      @if($winEnd < $last)
        @if($winEnd < $last-1)
          <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
        @endif
        <li class="page-item {{ $last==$current?'active':'' }}"><a class="page-link" href="{{ pageUrl($last) }}" data-page="{{ $last }}">{{ $last }}</a></li>
      @endif
      {{-- Next / Last --}}
      <li class="page-item {{ $current==$last?'disabled':'' }}">
        <a class="page-link" href="{{ $current==$last ? '#' : pageUrl($current+1) }}" data-page="{{ $current+1 }}"><i class="bi bi-chevron-right"></i></a>
      </li>
      <li class="page-item {{ $current==$last?'disabled':'' }}">
        <a class="page-link" href="{{ $current==$last ? '#' : pageUrl($last) }}" data-page="{{ $last }}"><i class="bi bi-chevron-double-right"></i></a>
      </li>
    </ul>
  </nav>
@endif
