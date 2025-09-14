@extends('vendor.layouts.app')

@section('title', 'Analytics')

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

  /* Metrics */
  .metric{display:flex;gap:14px;align-items:flex-start;padding:18px}
  .metric .icon{
    width:44px;height:44px;border-radius:12px;
    display:inline-flex;align-items:center;justify-content:center;
    background:#111;color:#fff;font-size:18px;
  }
  .metric .meta{flex:1}
  .metric .label{font-size:.78rem;font-weight:700;color:#000;text-transform:uppercase}
  .metric .value{font-size:1.8rem;font-weight:800;color:#000;margin-top:4px}
  .metric .sub{font-size:.85rem;color:var(--muted)}

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
  .bar-wrap{background:var(--bar-bg);border-radius:99px;overflow:hidden;height:8px}
  .bar{background:var(--bar);height:100%}

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
          <span>Analytics</span>
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
              <li><a class="dropdown-item" href="?range=Today">Today</a></li>
              <li><a class="dropdown-item" href="?range=Last 7 days">Last 7 days</a></li>
              <li><a class="dropdown-item" href="?range=Last 30 days">Last 30 days</a></li>
              <li><a class="dropdown-item" href="?range=This month">This month</a></li>
              <li><a class="dropdown-item" href="?range=This year">This year</a></li>
            </ul>
          </div>
          <a class="btn-neutral" href="{{ route('vendor.analytics.documents') }}">
            <i class="bi bi-list-ul"></i> All Documents
          </a>

        </div>
      </div>
    </div>

  <div class="container py-4">
    <!-- KPI row -->
    <div class="row g-3 mb-3">
      <div class="col-xl-4 col-md-6">
        <div class="bw-card metric h-100">
          <div class="icon"><i class="bi bi-eye"></i></div>
          <div class="meta">
            <div class="label">Visits</div>
            <div class="value">{{ number_format((int)$visits) }}</div>
            <div class="sub">Unique sessions that opened a document</div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="bw-card metric h-100">
          <div class="icon"><i class="bi bi-stopwatch"></i></div>
          <div class="meta">
            <div class="label">Average Reading Time</div>
            <div class="value">{{ $avgTime ?? 'N/A' }}</div>
            <div class="sub">Per session</div>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="bw-card metric h-100">
          <div class="icon"><i class="bi bi-hourglass-split"></i></div>
          <div class="meta">
            <div class="label">Total Reading Time</div>
            <div class="value">{{ $totalTime ?? 'N/A' }}</div>
            <div class="sub">Across selected range</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Documents -->
    <div class="row g-3">
      <div class="col-lg-7">
        <div class="bw-card h-100">
          <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 section-title">Top Documents</h6>
            <span class="chip"><i class="bi bi-trophy"></i> Top 5</span>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Document</th>
                    <th style="width:120px;">Views</th>
                    <th style="width:160px;">Engagement</th>
                  </tr>
                </thead>
                <tbody>
                  @php $maxViews = max(1, (int)($topDocs->max('views') ?? 1)); @endphp
                  @forelse ($topDocs as $doc)
                    @php $pct = min(100, intval(($doc->views / $maxViews) * 100)); @endphp
                    <tr>
                      <td>
                        <div class="row-title">
                          <div class="doc-ico"><i class="bi bi-file-earmark-text"></i></div>
                          <div class="d-flex flex-column">
                            <div class="doc-name text-truncate" title="{{ $doc->filename }}">{{ $doc->filename }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="views-pill"><i class="bi bi-eye"></i>{{ number_format((int)$doc->views) }}</span>
                      </td>
                      <td>
                        <div class="bar-wrap"><div class="bar" style="width: {{ $pct }}%"></div></div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="3" class="empty">
                        <div class="mb-1"><i class="bi bi-inbox"></i></div>
                        No document analytics yet.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Visitor Cities (placeholder) -->
      <div class="col-lg-5">
        <div class="bw-card h-100">
          <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 section-title">Visitor Cities</h6>
            <span class="chip"><i class="bi bi-geo-alt"></i> Locations</span>
          </div>
          <div class="card-body">
            <div class="empty">
              <div class="mb-2"><span class="badge">Analytics</span></div>
              <div class="mb-1 fw-bold">Location insights unavailable</div>
              <div class="mb-3">Enable IP geolocation collection to see city-level breakdowns.</div>
              <a href="#" class="btn-neutral"><i class="bi bi-gear"></i> Configure</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
