@extends('layouts.app')

@section('title', 'How It Works - PDFOneLink')

@push('styles')
  {{-- Page CSS: How It Works (Black & White) --}}
  <style>
    /* ===============  HOW IT WORKS — B&W  =============== */

    /* Hero */
    .hero{
      padding:72px 0 56px;
      position:relative; overflow:hidden;
      background:
        radial-gradient(900px 420px at 85% -120px, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        radial-gradient(700px 380px at -10% 110%, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        linear-gradient(180deg, #fbfbfc 0%, #f6f7f8 38%, #ffffff 100%);
    }
    .hero .lead{ color:var(--muted); }

    /* Sections */
    .section{ padding:64px 0; }
    .section-title.centered{ text-align:center; }
    .section-subtitle{ color:var(--muted); text-align:center; margin-bottom:1.25rem; }

    /* Steps */
    .step-card{
      background:#fff;
      border:1px solid var(--line);
      border-radius:var(--radius);
      padding:22px; height:100%;
      text-align:center;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .step-card:hover{ transform:translateY(-4px); box-shadow:var(--shadow-lg); border-color:#dcdcdc; }
    .step-number{
      width:40px; height:40px; border-radius:999px;
      display:grid; place-items:center;
      background:#fff; border:1px solid var(--line);
      font-weight:700; margin:0 auto .75rem;
    }
    .feature-icon{
      width:52px; height:52px; border-radius:12px;
      display:grid; place-items:center;
      border:1px solid var(--line);
      background:#fff; color:#000;
      font-size:1.35rem;
    }
    .step-card .feature-icon{ background:#fff !important; color:#000 !important; }

    /* Detail section */
    .detail-section{ padding:64px 0; background:#fff; }
    .detail-card{
      background:#fff;
      border:1px solid var(--line);
      border-radius:var(--radius);
      padding:22px;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      height:100%;
    }
    .detail-card:hover{ transform:translateY(-3px); box-shadow:var(--shadow-lg); border-color:#dcdcdc; }

    .detail-card .list-unstyled li i{ color:#111 !important; }
    .detail-card .text-success,
    .detail-card .text-warning,
    .detail-card .text-primary,
    .detail-card .text-info{ color:#111 !important; }

    .detail-card .bg-light{
      background:#f6f7f8 !important;
      border:1px solid var(--line);
    }
    .detail-card h6{ font-weight:700; }

    .text-muted{ color:var(--muted) !important; }

    @media (max-width: 992px){
      .hero{ text-align:center; }
    }
    @media (max-width: 768px){
      .section, .detail-section{ padding:48px 0; }
    }
  </style>
@endpush

@section('content')
  {{-- HERO --}}
  <header class="hero">
    <div class="container position-relative">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h1 class="display-5 fw-bold mb-3">How PDFOneLink Works</h1>
          <p class="lead text-muted mb-4">Set up in minutes — upload, share, and track your PDFs with ease.</p>
        </div>
      </div>
    </div>
  </header>

  {{-- 4 STEPS --}}
  <section class="section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">4 simple steps</h2>
        <p class="section-subtitle">Understand the workflow from upload to analytics</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">1</div>
            <div class="feature-icon mx-auto mb-3" style="background:#ecfeff;color:#155e75;">
              <i class="bi bi-person-plus"></i>
            </div>
            <h6>Create your account</h6>
            <p class="text-muted mb-0 small">Sign up and verify email. Add your brand logo &amp; domain rules.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">2</div>
            <div class="feature-icon mx-auto mb-3" style="background:#fef9c3;color:#854d0e;">
              <i class="bi bi-file-earmark-arrow-up"></i>
            </div>
            <h6>Upload PDFs</h6>
            <p class="text-muted mb-0 small">Drag &amp; drop. We process for search, thumbnails, and OCR (optional).</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">3</div>
            <div class="feature-icon mx-auto mb-3" style="background:#dcfce7;color:#166534;">
              <i class="bi bi-sliders2-vertical"></i>
            </div>
            <h6>Set permissions</h6>
            <p class="text-muted mb-0 small">Choose view-only, disable download/print, add watermarks &amp; expiry.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">4</div>
            <div class="feature-icon mx-auto mb-3" style="background:#fae8ff;color:#6b21a8;">
              <i class="bi bi-graph-up"></i>
            </div>
            <h6>Share &amp; track</h6>
            <p class="text-muted mb-0 small">Share one link. See opens, location, device, and page engagement.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- DETAILS --}}
  <section class="detail-section">
    <div class="container">
      <h2 class="section-title centered">Detailed Workflow Explanation</h2>
      <p class="section-subtitle">Learn how each step works in practice</p>

      <div class="row g-4 mb-5">
        <div class="col-lg-6">
          <div class="detail-card">
            <h3 class="mb-4">Step 1: Account Creation &amp; Setup</h3>
            <p>Getting started with PDFOneLink is designed to be quick and straightforward:</p>
            <ul class="list-unstyled">
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Sign up</strong> with your email address or social account</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Verify your email</strong> to activate your account</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Complete your profile</strong> with company details and branding</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Set up security preferences</strong> and default permissions</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Invite team members</strong> (on business plans)</li>
            </ul>
            <div class="mt-4 p-3 bg-light rounded">
              <h6 class="d-flex align-items-center"><i class="bi bi-lightbulb me-2 text-warning"></i> Pro Tip</h6>
              <p class="small mb-0">Upload your logo and set brand colors during setup to have all your shared PDFs automatically branded.</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="detail-card">
            <h3 class="mb-4">Step 2: PDF Upload &amp; Processing</h3>
            <p>Our system automatically optimizes your documents for secure sharing:</p>
            <ul class="list-unstyled">
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Drag and drop</strong> interface for easy uploading</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Batch processing</strong> for multiple files</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Automatic OCR</strong> for scanned documents (optional)</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Thumbnail generation</strong> for easy identification</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Secure encryption</strong> during transfer and storage</li>
            </ul>
            <div class="mt-4 p-3 bg-light rounded">
              <h6 class="d-flex align-items-center"><i class="bi bi-info-circle me-2 text-primary"></i> Did You Know?</h6>
              <p class="small mb-0">We support PDFs up to 500MB in size, with no daily upload limits on paid plans.</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="detail-card">
            <h3 class="mb-4">Step 3: Security &amp; Permission Settings</h3>
            <p>Granular control over who can access your content and how:</p>
            <ul class="list-unstyled">
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Password protection</strong> for added security</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Download/print restrictions</strong> to protect your IP</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Expiration dates</strong> for temporary access</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Domain restrictions</strong> to limit access to certain organizations</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Watermarking</strong> with user identifiers</li>
            </ul>
            <div class="mt-4 p-3 bg-light rounded">
              <h6 class="d-flex align-items-center"><i class="bi bi-shield-check me-2 text-success"></i> Security Note</h6>
              <p class="small mb-0">All documents are encrypted at rest and in transit using industry-standard AES-256 encryption.</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="detail-card">
            <h3 class="mb-4">Step 4: Sharing &amp; Analytics</h3>
            <p>Share securely and gain valuable insights into viewer engagement:</p>
            <ul class="list-unstyled">
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Custom branded links</strong> for professional sharing</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Email notifications</strong> when documents are viewed</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Detailed analytics</strong> on viewer engagement</li>
              <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Geographic data</strong> on where documents are accessed</li>
              <li><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Exportable reports</strong> for client presentations</li>
            </ul>
            <div class="mt-4 p-3 bg-light rounded">
              <h6 class="d-flex align-items-center"><i class="bi bi-graph-up me-2 text-info"></i> Analytics Insight</h6>
              <p class="small mb-0">Track time spent per page, which sections get the most attention, and when viewers drop off.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
