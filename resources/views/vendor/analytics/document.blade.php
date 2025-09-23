@extends('vendor.layouts.app')

@section('title', 'Document Analytics')

@push('styles')
<style>
  :root{ --line:#e5e7eb; --text:#111; --muted:#666; --radius:14px; }
  .bw-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius)}
  .crumb{display:flex;align-items:center;gap:.5rem;color:var(--muted)}
  .crumb a{color:var(--text);text-decoration:none}
  .doc-title{font-weight:700;color:#000}
  .table thead th{border-bottom:1px solid var(--line);color:#222;font-weight:700}
  .table tbody td{border-color:var(--line);vertical-align:middle}
  .chip{display:inline-flex;align-items:center;gap:.4rem;background:#f3f4f6;border:1px solid var(--line);border-radius:999px;padding:.35rem .7rem;font-weight:600;color:#111}
</style>
@endpush

@section('content')
<div class="container py-3">
  <nav class="crumb mb-2">
    <a href="{{ route('vendor.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('vendor.analytics.documents') }}">Analytics</a>
    <i class="bi bi-chevron-right"></i>
    <span>{{ $pdf->filename }}</span>
  </nav>

  <div class="bw-card p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between">
      <div class="doc-title">{{ $pdf->filename }}</div>
      <!-- <span class="chip"><i class="bi bi-calendar3"></i>{{ request('range','Last 7 days') }}</span> -->
       <div class="toolbar">
          <div class="chip"><i class="bi bi-calendar3"></i>
            <span>{{ request('range','Last 7 days') }}</span>
          </div>
          <div class="dropdown">
            <button class="btn-neutral dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-sliders"></i> Range
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              @php
                $staticRanges = ['Today', 'Last 7 days', 'Last 30 days', 'This month', 'This year'];
                $userId = auth()->id();
                $firstDoc = \App\Models\Document::where('user_id', $userId)->orderBy('created_at')->first();
                $firstYear = $firstDoc ? (int)($firstDoc->created_at->format('Y')) : (int)date('Y');
                $currentYear = (int)date('Y');
              @endphp
              @foreach ($staticRanges as $option)
                <li><a class="dropdown-item {{ $range === $option ? 'active' : '' }}" href="?range={{$option}}">{{$option}}</a></li>
              @endforeach
              @for ($y = $currentYear - 1; $y >= $firstYear; $y--)
                <li><a class="dropdown-item {{ $range == $y ? 'active' : '' }}" href="?range={{$y}}">{{$y}}</a></li>
              @endfor
            </ul>
          </div>
        </div>
    </div>
  </div>

  <div class="bw-card p-3">
    <h6 class="mb-3">Traffic Sources</h6>
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Source</th>
            <th style="width:120px;">Views</th>
            <th style="width:120px;">Sessions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($trafficSources as $src)
          <tr>
            <td>{{ $src->platform }}/{{ $src->browser }}</td>
            <td>{{ $src->views }}</td>
            <td>{{ $src->sessions }}</td>
          </tr>
          @empty
          <tr><td colspan="3" class="text-muted">No source data in this range.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      @if(0)
      {{ $trafficSources->withQueryString()->links() }}
      @endif
    </div>
  </div>
</div>
@endsection
