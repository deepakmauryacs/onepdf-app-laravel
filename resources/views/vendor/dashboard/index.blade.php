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
          <span class="count">3</span>
        </div>
        <div class="files-search">
          <i class="bi bi-search"></i>
          <input type="text" class="form-control" placeholder="Search files...">
        </div>
        <button class="btn btn-del">
          <span class="btn-del-text"><i class="bi bi-trash me-1"></i> Delete selected</span>
          <span class="btn-del-icon"><i class="bi bi-trash"></i></span>
        </button>
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
        <tbody>
          <tr>
            <td><input class="form-check-input" type="checkbox" /></td>
            <td>
              <div class="col-file">
                <span class="file-chip"><i class="bi bi-filetype-pdf"></i></span>
                <div class="file-name"><a href="#">E-Book_Selling_Platform_Documentation.pdf</a></div>
              </div>
            </td>
            <td>85.71 KB</td>
            <td>
              <div>2025-08-30</div>
              <small class="text-muted">05:58:08</small>
            </td>
            <td><span class="status-pill">Secure</span></td>
            <td class="text-nowrap">
              <div class="d-flex gap-2 mb-2">
                <button class="btn btn-ghost flex-fill"><i class="bi bi-link-45deg me-1"></i>Generate</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-clipboard me-1"></i>Copy</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-code-slash me-1"></i>Embed</button>
                <button class="btn-icon"><i class="bi bi-trash"></i></button>
              </div>
              <div class="small text-break text-muted">https://pdflink.com/view?doc=0f41da405d</div>
            </td>
          </tr>

          <tr>
            <td><input class="form-check-input" type="checkbox" /></td>
            <td>
              <div class="col-file">
                <span class="file-chip"><i class="bi bi-file-earmark-text"></i></span>
                <div class="file-name"><a href="#">Quarterly_Report_Q2.docx</a></div>
              </div>
            </td>
            <td>212 KB</td>
            <td>
              <div>2025-08-18</div>
              <small class="text-muted">11:12:44</small>
            </td>
            <td><span class="status-pill">Secure</span></td>
            <td class="text-nowrap">
              <div class="d-flex gap-2">
                <button class="btn btn-ghost flex-fill"><i class="bi bi-link-45deg me-1"></i>Generate</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-clipboard me-1"></i>Copy</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-code-slash me-1"></i>Embed</button>
                <button class="btn-icon"><i class="bi bi-trash"></i></button>
              </div>
            </td>
          </tr>

          <tr>
            <td><input class="form-check-input" type="checkbox" /></td>
            <td>
              <div class="col-file">
                <span class="file-chip"><i class="bi bi-filetype-csv"></i></span>
                <div class="file-name"><a href="#">customers_export_2025-08.csv</a></div>
              </div>
            </td>
            <td>1.8 MB</td>
            <td>
              <div>2025-08-01</div>
              <small class="text-muted">09:05:03</small>
            </td>
            <td><span class="status-pill">Secure</span></td>
            <td class="text-nowrap">
              <div class="d-flex gap-2">
                <button class="btn btn-ghost flex-fill"><i class="bi bi-link-45deg me-1"></i>Generate</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-clipboard me-1"></i>Copy</button>
                <button class="btn btn-ghost flex-fill"><i class="bi bi-code-slash me-1"></i>Embed</button>
                <button class="btn-icon"><i class="bi bi-trash"></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection