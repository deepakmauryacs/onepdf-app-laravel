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
    <a href="{{ route('vendor.analytics.document', $doc->id) }}">Analytics</a>
    <i class="bi bi-chevron-right"></i>
    <span>{{ $doc->filename }}</span>
  </nav>

  <div class="bw-card p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between">
      <div class="doc-title">{{ $doc->filename }}</div>
      <span class="chip"><i class="bi bi-calendar3"></i>{{ request('range','Last 7 days') }}</span>
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
          @forelse ($sources as $row)
          <tr>
            <td>{{ $row->source }}</td>
            <td>{{ number_format((int)$row->views) }}</td>
            <td>{{ number_format((int)($row->sessions ?? 0)) }}</td>
          </tr>
          @empty
          <tr><td colspan="3" class="text-muted">No source data in this range.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3 d-flex justify-content-end">
      {{ $sources->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection
