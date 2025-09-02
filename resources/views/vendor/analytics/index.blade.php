@extends('vendor.layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Analytics</h1>
  </div>

  <div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Visits (Last 7 Days)</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visits }}</div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Average Reading Time</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Reading Time</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">N/A</div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6 mb-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Top 5 Documents</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered mb-0">
              <thead>
                <tr>
                  <th>Document</th>
                  <th>Views</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($topDocs as $doc)
                <tr>
                  <td>{{ $doc->filename }}</td>
                  <td>{{ $doc->views }}</td>
                </tr>
                @empty
                <tr>
                  <td colspan="2" class="text-center">No data</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Top 5 Visitor Cities</h6>
        </div>
        <div class="card-body">
          <p class="mb-0">Visitor location analytics are not available.</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

