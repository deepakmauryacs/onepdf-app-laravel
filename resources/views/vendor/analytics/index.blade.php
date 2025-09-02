@extends('vendor.layouts.app')

@section('title', 'Analytics')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#f6f7fb; --surface:#fff; --text:#0f172a; --muted:#64748b; --line:#eaecef;
    --radius:14px; --shadow:0 10px 30px rgba(2,6,23,.06);
  }
  *{font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
  body{background:var(--bg)}

  /* Top band / crumbs (same across pages) */
  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{display:flex;align-items:center;gap:.5rem;color:var(--muted)}
  .crumb a{color:var(--text);text-decoration:none}
  .crumb i{opacity:.6}

  /* Cards */
  .bw-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow)}
  .bw-card .card-header{background:var(--surface);border-bottom:1px solid var(--line);border-top-left-radius:var(--radius);border-top-right-radius:var(--radius)}
  .stat{padding:18px}
  .stat .label{font-size:.75rem;font-weight:700;letter-spacing:.03em;color:#111;text-transform:uppercase}
  .stat .value{font-size:1.6rem;font-weight:800;color:var(--text);line-height:1.2;margin-top:4px}

  /* Table */
  .table thead th{border-bottom:1px solid var(--line);color:#475569;font-weight:700}
  .table tbody td{vertical-align:middle;border-color:var(--line)}
  .empty{padding:28px;text-align:center;color:var(--muted)}

  /* Neutral buttons (shared) */
  .btn-neutral{
    background:#fff;border:1px solid var(--line);color:var(--text);border-radius:12px;
    padding:.5rem .8rem;font-weight:700;display:inline-flex;align-items:center;gap:.4rem;
  }
  .btn-neutral:hover{background:#f3f4f6}

  .dropdown-menu{border:1px solid var(--line);border-radius:12px}
</style>
@endpush

@section('content')
  <!-- Top band / breadcrumb -->
  <div class="top-band">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between">
        <nav class="crumb">
          <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
          <i class="bi bi-chevron-right"></i>
          <span>Analytics</span>
        </nav>
      </div>
    </div>
  </div>

  <div class="container py-4">
    <!-- KPI row -->
    <div class="row g-3 mb-2">
      <div class="col-xl-4 col-md-6">
        <div class="bw-card stat h-100">
          <div class="label">Visits (Last 7 Days)</div>
          <div class="value">{{ $visits }}</div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="bw-card stat h-100">
          <div class="label">Average Reading Time</div>
          <div class="value">{{ $avgTime ?? 'N/A' }}</div>
        </div>
      </div>
      <div class="col-xl-4 col-md-6">
        <div class="bw-card stat h-100">
          <div class="label">Total Reading Time</div>
          <div class="value">{{ $totalTime ?? 'N/A' }}</div>
        </div>
      </div>
    </div>

    <!-- Tables row -->
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="bw-card h-100">
          <div class="card-header py-3">
            <h6 class="m-0 fw-bold">Top 5 Documents</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table mb-0">
                <thead>
                  <tr>
                    <th>Document</th>
                    <th style="width:120px;">Views</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($topDocs as $doc)
                  <tr>
                    <td class="text-truncate" title="{{ $doc->filename }}">{{ $doc->filename }}</td>
                    <td>{{ $doc->views }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="2" class="empty"><i class="bi bi-inbox me-1"></i>No data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="bw-card h-100">
          <div class="card-header py-3">
            <h6 class="m-0 fw-bold">Top 5 Visitor Cities</h6>
          </div>
          <div class="card-body">
            <div class="empty"><i class="bi bi-geo-alt me-1"></i>Visitor location analytics are not available.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
