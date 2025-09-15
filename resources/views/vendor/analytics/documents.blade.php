@extends('vendor.layouts.app')

@section('title', 'All Documents Analytics')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#f6f7fb; --surface:#fff; --text:#111; --muted:#666; --line:#e5e7eb;
    --radius:14px; --shadow:0 6px 16px rgba(0,0,0,.06);
    --chip:#f3f4f6; --bar:#222; --bar-bg:#e5e7eb;
  }
  *{font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
  body{background:var(--bg);color:var(--text)}

  /* Top band / crumbs */
  .top-band{ background:#f9f9f9; border-bottom:1px solid var(--line); }
  .crumb{display:flex;align-items:center;gap:.5rem;color:var(--muted);font-size:.9rem}
  .crumb a{color:var(--text);text-decoration:none}
  .crumb i{opacity:.6}

  /* Cards */
  .bw-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
  .bw-card .card-header{background:var(--surface);border-bottom:1px solid var(--line)}
  .section-title{font-weight:700;color:var(--text)}

  /* Toolbar */
  .toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
  .btn-neutral{
    background:#fff;border:1px solid var(--line);color:var(--text);border-radius:12px;
    padding:.5rem .8rem;font-weight:600;display:inline-flex;align-items:center;gap:.4rem;
  }
  .btn-neutral:hover{background:#f2f2f2}
  .chip{
    display:inline-flex;align-items:center;gap:.4rem;
    background:var(--chip);border:1px solid var(--line);border-radius:999px;
    padding:.35rem .7rem;font-weight:600;color:var(--text);font-size:.85rem;
  }

  /* Tables */
  .table thead th{border-bottom:1px solid var(--line);color:#222;font-weight:700}
  .table tbody td{vertical-align:middle;border-color:var(--line)}
  .row-title{display:flex;align-items:center;gap:10px}
  .doc-ico{
    width:36px;height:36px;border-radius:8px;background:#f3f4f6;border:1px solid var(--line);
    display:inline-flex;align-items:center;justify-content:center;color:#444;font-size:16px;
  }
  .doc-name{font-weight:600;color:#000;max-width:420px}
  .doc-name:hover{text-decoration:underline}
  .muted{color:var(--muted)}
  .views-pill{
    display:inline-flex;align-items:center;gap:.35rem;
    border:1px solid var(--line);background:#fff;border-radius:999px;
    padding:.25rem .6rem;font-weight:700;color:#000
  }

  /* Empty state */
  .empty{padding:36px;text-align:center;color:var(--muted);font-size:.95rem}
  .empty .badge{background:#111;color:#fff;font-size:.8rem;border-radius:12px;padding:.3rem .8rem}

  /* ===== Modern centered pagination (black & white) ===== */
  .pagination-wrap{
    display:flex;flex-direction:column;align-items:center;gap:8px;
    margin-bottom:1.25rem; /* bottom space */
  }
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination-modern{gap:8px}
  .pagination-modern .page-link{
    border:1px solid var(--line);
    background:#fff;
    color:#111;
    border-radius:12px;
    min-width:42px;height:42px;
    padding:0 12px;
    display:flex;align-items:center;justify-content:center;
    font-weight:700;
    box-shadow:0 2px 6px rgba(0,0,0,.04);
  }
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
  .pagination-modern .ellipsis > .page-link{pointer-events:none}
  .pagination-modern .page-link .bi{margin:0;font-size:16px}
</style>
@endpush

@section('content')
  <!-- Top band / breadcrumb -->
  <div class="top-band">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between">
        <nav class="crumb">
          <a href="{{ route('vendor.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
          <i class="bi bi-chevron-right"></i>
          <a href="{{ route('vendor.analytics.index') }}">Analytics</a>
          <i class="bi bi-chevron-right"></i>
          <span>All Documents</span>
        </nav>
        <div class="toolbar">
          <div class="chip"><i class="bi bi-calendar3"></i>
            <span>{{ request('range','Last 7 days') }}</span>
          </div>
          <div class="dropdown">
            <button class="btn-neutral dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-sliders"></i> Range
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              @foreach (['Today', 'Last 7 days', 'Last 30 days', 'This month', 'This year'] as $option)
                  <li><a class="dropdown-item {{ $range === $option ? 'active' : '' }}" href="?range={{$option}}">{{$option}}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="row g-3 mt-1">
      <div class="col-12">
        <div class="bw-card h-100">
          <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 section-title">All Documents</h6>
            @if(isset($allDocs) && $allDocs->total())
              <span class="chip"><i class="bi bi-list-ul"></i> Page {{ $allDocs->currentPage() }} of {{ $allDocs->lastPage() }}</span>
            @endif
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Document</th>
                    <th style="width:120px;">Views</th>
                    <th style="width:120px;">Sessions</th>
                    <th style="width:180px;">Last View</th>
                    <th style="width:110px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($documents as $doc)
                    <tr>
                      <td>
                        <div class="row-title">
                          <div class="doc-ico"><i class="bi bi-file-earmark-text"></i></div>
                          <div class="d-flex flex-column">
                            <a class="doc-name text-truncate" href="{{ route('vendor.analytics.document', $doc->id) }}"
                               title="{{ $doc->filename }}">{{ $doc->filename }}</a>
                            <span class="muted small">ID: {{ $doc->id }}</span>
                          </div>
                        </div>
                      </td>
                      <td><span class="views-pill"><i class="bi bi-eye"></i>{{ number_format((int)$doc->views) }}</span></td>
                      <td>{{ number_format((int)($doc->sessions ?? 0)) }}</td>
                      <td>
                        {{ $doc->last_view ?? '-' }}
                      </td>
                      <td>
                        <a class="btn-neutral" href="{{ route('vendor.analytics.document', $doc->id) }}">
                          <i class="bi bi-box-arrow-up-right"></i> View
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="empty">
                        <div class="mb-1"><i class="bi bi-inbox"></i></div>
                        No documents in this range.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            {{-- Centered pagination with bottom margin --}}
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

              <nav class="mt-3 mb-4 pagination-wrap" aria-label="All documents pagination">
                <div class="pager-summary">Showing {{ number_format($startNo) }}–{{ number_format($endNo) }} of {{ number_format($total) }}</div>
                <ul class="pagination pagination-modern justify-content-center mb-0">
                  {{-- First / Prev --}}
                  <li class="page-item {{ $current==1?'disabled':'' }}">
                    <a class="page-link" href="{{ $current==1 ? '#' : pageUrl(1) }}"><i class="bi bi-chevron-double-left"></i></a>
                  </li>
                  <li class="page-item {{ $current==1?'disabled':'' }}">
                    <a class="page-link" href="{{ $current==1 ? '#' : pageUrl($current-1) }}"><i class="bi bi-chevron-left"></i></a>
                  </li>

                  {{-- Left edge + ellipsis --}}
                  @if($winStart > 1)
                    <li class="page-item"><a class="page-link" href="{{ pageUrl(1) }}">1</a></li>
                    @if($winStart > 2)
                      <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
                    @endif
                  @endif

                  {{-- Window --}}
                  @for($i=$winStart; $i<=$winEnd; $i++)
                    <li class="page-item {{ $i==$current?'active':'' }}">
                      <a class="page-link" href="{{ $i==$current ? '#' : pageUrl($i) }}">{{ $i }}</a>
                    </li>
                  @endfor

                  {{-- Right ellipsis + edge --}}
                  @if($winEnd < $last)
                    @if($winEnd < $last-1)
                      <li class="page-item ellipsis"><a class="page-link" href="#">…</a></li>
                    @endif
                    <li class="page-item"><a class="page-link" href="{{ pageUrl($last) }}">{{ $last }}</a></li>
                  @endif

                  {{-- Next / Last --}}
                  <li class="page-item {{ $current==$last?'disabled':'' }}">
                    <a class="page-link" href="{{ $current==$last ? '#' : pageUrl($current+1) }}"><i class="bi bi-chevron-right"></i></a>
                  </li>
                  <li class="page-item {{ $current==$last?'disabled':'' }}">
                    <a class="page-link" href="{{ $current==$last ? '#' : pageUrl($last) }}"><i class="bi bi-chevron-double-right"></i></a>
                  </li>
                </ul>
              </nav>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
