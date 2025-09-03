@extends('layouts.app')

@section('title', 'Features - PDFOneLink')

@push('styles')
  {{-- FEATURES PAGE CSS (Black & White) --}}
  <style>
    /* ===========================
       FEATURES PAGE (Black & White)
       =========================== */

    /* Hero */
    .features-hero{
      padding:72px 0 56px;
      position:relative;
      overflow:hidden;
      background:
        radial-gradient(900px 420px at 85% -120px, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        radial-gradient(700px 380px at -10% 110%, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        linear-gradient(180deg, #fbfbfc 0%, #f6f7f8 38%, #ffffff 100%);
    }
    .features-hero .lead{ color:var(--muted); }

    /* Sections */
    .features-section{ padding:64px 0; }
    .section-title.centered{ text-align:center; }
    .section-subtitle{
      color:var(--muted);
      text-align:center;
      margin-bottom:1.5rem;
    }

    /* Feature cards */
    .feature-card{
      background:#fff;
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:22px;
      height:100%;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .feature-card:hover{
      transform:translateY(-4px);
      box-shadow:var(--shadow-lg);
      border-color:#dcdcdc;
    }
    .feature-icon{
      width:52px; height:52px;
      display:grid; place-items:center;
      border-radius:12px;
      border:1px solid var(--border);
      background:#fff; color:#000;
      margin-bottom:.75rem;
      font-size:1.35rem;
    }
    .feature-list{
      list-style:none;
      padding-left:0;
      margin:14px 0 0;
    }
    .feature-list li{
      display:flex; align-items:center;
      gap:.6rem;
      margin:.35rem 0;
    }
    .feature-list li i{ color:#111; }

    /* Demo */
    .demo-section{
      padding:64px 0;
      background:linear-gradient(to bottom, #f7f7f7, #ffffff);
      position:relative; overflow:hidden;
    }
    .demo-card{
      background:#fff;
      border:1px solid var(--border);
      border-radius:var(--radius);
      box-shadow:var(--shadow-lg);
      padding:20px;
    }

    /* Tabs (nav-pills) – monochrome */
    .nav-pills .nav-link{
      border:1px solid var(--border);
      background:#fff;
      color:var(--text);
      margin:0 .25rem;
    }
    .nav-pills .nav-link:hover{ background:var(--lighter); }
    .nav-pills .nav-link.active{
      background:#111 !important;
      color:#fff !important;
      border-color:#111 !important;
    }

    /* Upload / Security / Sharing right-panels */
    .border.rounded-3.bg-white{
      border:1px solid var(--border) !important;
      background:#fff !important;
    }
    .border-primary{ border-color:#111 !important; }   /* toggled via JS */
    .text-primary{ color:#111 !important; }            /* icon tint in panels */

    /* Inputs and small buttons in demo areas */
    .btn-outline-secondary{
      border-color:var(--border);
      color:var(--text);
      background:#fff;
    }
    .btn-outline-secondary:hover{
      background:var(--lighter);
      border-color:#d4d4d4;
      color:#000;
    }
    .form-check-input:checked{
      background-color:#111;
      border-color:#111;
    }

    /* Progress bars – mono */
    .progress{
      height:5px;
      background-color:#efefef;
      border-radius:999px;
    }
    .progress-bar{ background-color:#111 !important; }

    /* Use Cases */
    .use-cases{
      padding:64px 0;
      background:var(--section);
      border-top:1px solid var(--line);
      border-bottom:1px solid var(--line);
    }
    .use-case-card{
      background:#fff;
      border:1px solid var(--border);
      border-radius:var(--radius);
      padding:20px;
      height:100%;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .use-case-card:hover{
      transform:translateY(-4px);
      box-shadow:var(--shadow-lg);
      border-color:#dcdcdc;
    }
    .use-case-icon{
      width:48px; height:48px;
      display:grid; place-items:center;
      border-radius:12px;
      border:1px solid var(--border);
      background:#fff; color:#000;
      margin-bottom:.6rem;
      font-size:1.25rem;
    }

    /* Integrations */
    .integration-section{ padding:64px 0; }
    .integration-logo{
      height:26px;
      margin:8px 16px;
      filter:grayscale(1);
      opacity:.85;
      transition:opacity .2s ease, filter .2s ease, transform .2s ease;
    }
    .integration-logo:hover{
      opacity:1; filter:grayscale(0); transform:translateY(-2px);
    }

    /* CTA */
    .features-section .btn-brand{ font-weight:700; }

    /* Responsive tweaks */
    @media (max-width: 992px){
      .features-hero{ text-align:center; }
    }
    @media (max-width: 768px){
      .demo-card{ padding:16px; }
      .features-section, .demo-section, .use-cases, .integration-section{ padding:48px 0; }
    }
  </style>
@endpush

@section('content')
  {{-- HERO --}}
  <section class="features-hero">
    <div class="container position-relative">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h1 class="display-5 fw-bold mb-3">Powerful Features for Secure PDF Sharing</h1>
          <p class="lead text-muted mb-4">Everything you need to share, protect, and track your PDF documents with confidence.</p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="#features" class="btn btn-brand btn-lg">Explore Features</a>
            <a href="#demo" class="btn btn-ghost btn-lg">See Live Demo</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- FEATURES --}}
  <section id="features" class="features-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Complete PDF Security & Sharing Solution</h2>
        <p class="section-subtitle">All the tools you need to share documents securely and track their performance</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-cloud-arrow-up"></i></div>
            <h3>Easy Upload & Organization</h3>
            <p>Quickly upload PDFs with drag-and-drop functionality and keep them organized with folders and tags.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>Drag & drop upload</li>
              <li><i class="bi bi-check2"></i>Folder organization</li>
              <li><i class="bi bi-check2"></i>File versioning</li>
              <li><i class="bi bi-check2"></i>Batch operations</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
            <h3>Advanced Security Controls</h3>
            <p>Protect your documents with granular permission settings and access controls.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>View-only mode</li>
              <li><i class="bi bi-check2"></i>Download prevention</li>
              <li><i class="bi bi-check2"></i>Print restriction</li>
              <li><i class="bi bi-check2"></i>Password protection</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <h3>Detailed Analytics</h3>
            <p>Gain insights into how your documents are being viewed and engaged with.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>View tracking</li>
              <li><i class="bi bi-check2"></i>Time-on-page analytics</li>
              <li><i class="bi bi-check2"></i>Geographic data</li>
              <li><i class="bi bi-check2"></i>Device information</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-link-45deg"></i></div>
            <h3>Smart Link Management</h3>
            <p>Create and manage shareable links with advanced controls and customization.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>Expiring links</li>
              <li><i class="bi bi-check2"></i>Access revocation</li>
              <li><i class="bi bi-check2"></i>Password protection</li>
              <li><i class="bi bi-check2"></i>Custom domains</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-code-slash"></i></div>
            <h3>Seamless Embedding</h3>
            <p>Easily embed PDF viewers directly into your website or application.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>Responsive iframes</li>
              <li><i class="bi bi-check2"></i>Custom styling</li>
              <li><i class="bi bi-check2"></i>White-label options</li>
              <li><i class="bi bi-check2"></i>API access</li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-gear"></i></div>
            <h3>Advanced Configuration</h3>
            <p>Customize the viewing experience with advanced settings and options.</p>
            <ul class="feature-list">
              <li><i class="bi bi-check2"></i>Custom watermarks</li>
              <li><i class="bi bi-check2"></i>Branding options</li>
              <li><i class="bi bi-check2"></i>Domain restrictions</li>
              <li><i class="bi bi-check2"></i>IP allowlisting</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- DEMO --}}
  <section class="demo-section" id="demo">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">See It In Action</h2>
        <p class="section-subtitle">Explore how PDFOneLink works with our interactive demo</p>
      </div>

      <div class="demo-card">
        <ul class="nav nav-pills mb-4 justify-content-center" id="demoTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload" type="button" role="tab">Upload</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">Security</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="sharing-tab" data-bs-toggle="pill" data-bs-target="#sharing" type="button" role="tab">Sharing</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab">Analytics</button>
          </li>
        </ul>

        <div class="tab-content" id="demoTabsContent">
          {{-- Upload --}}
          <div class="tab-pane fade show active" id="upload" role="tabpanel" aria-labelledby="upload-tab">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h3>Simple Drag & Drop Upload</h3>
                <p>Upload your PDFs in seconds with our intuitive interface. Organize them into folders and add tags for easy management.</p>
                <ul class="feature-list">
                  <li><i class="bi bi-check2"></i>Support for large files</li>
                  <li><i class="bi bi-check2"></i>Bulk upload capabilities</li>
                  <li><i class="bi bi-check2"></i>Automatic OCR for scanned documents</li>
                  <li><i class="bi bi-check2"></i>File preview before publishing</li>
                </ul>
              </div>
              <div class="col-lg-6 text-center">
                <div class="border rounded-3 p-5 bg-white">
                  <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3"></i>
                  <p class="text-muted">Drag your PDF here or click to browse</p>
                  <button class="btn btn-sm btn-ghost">Select Files</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Security --}}
          <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h3>Advanced Security Controls</h3>
                <p>Protect your documents with granular permission settings. Control exactly what viewers can and cannot do with your PDFs.</p>
                <ul class="feature-list">
                  <li><i class="bi bi-check2"></i>Disable downloading and printing</li>
                  <li><i class="bi bi-check2"></i>Add dynamic watermarks</li>
                  <li><i class="bi bi-check2"></i>Set expiration dates</li>
                  <li><i class="bi bi-check2"></i>Restrict access by domain or IP</li>
                </ul>
              </div>
              <div class="col-lg-6 text-center">
                <div class="border rounded-3 p-5 bg-white">
                  <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                  <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="downloadSwitch" checked>
                    <label class="form-check-label" for="downloadSwitch">Allow downloading</label>
                  </div>
                  <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="printSwitch">
                    <label class="form-check-label" for="printSwitch">Allow printing</label>
                  </div>
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="watermarkSwitch" checked>
                    <label class="form-check-label" for="watermarkSwitch">Add watermark</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Sharing --}}
          <div class="tab-pane fade" id="sharing" role="tabpanel" aria-labelledby="sharing-tab">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h3>Flexible Sharing Options</h3>
                <p>Share your documents with specific people or make them publicly accessible. Generate secure links or embed codes.</p>
                <ul class="feature-list">
                  <li><i class="bi bi-check2"></i>Generate shareable links</li>
                  <li><i class="bi bi-check2"></i>Create embeddable viewers</li>
                  <li><i class="bi bi-check2"></i>Set password protection</li>
                  <li><i class="bi bi-check2"></i>Track who accessed your documents</li>
                </ul>
              </div>
              <div class="col-lg-6 text-center">
                <div class="border rounded-3 p-4 bg-white">
                  <h5 class="mb-3">Share Document</h5>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" value="https://pdfonelink.com/doc/abc123" readonly>
                    <button class="btn btn-outline-secondary" type="button"><i class="bi bi-clipboard"></i></button>
                  </div>
                  <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="expirySwitch">
                    <label class="form-check-label" for="expirySwitch">Set expiration</label>
                  </div>
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="passwordSwitch" checked>
                    <label class="form-check-label" for="passwordSwitch">Password protect</label>
                  </div>
                  <button class="btn btn-brand w-100">Copy Link</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Analytics --}}
          <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h3>Detailed View Analytics</h3>
                <p>Track how your documents are performing with comprehensive analytics. See who viewed your PDFs and how they engaged with them.</p>
                <ul class="feature-list">
                  <li><i class="bi bi-check2"></i>View count and unique visitors</li>
                  <li><i class="bi bi-check2"></i>Time spent per page</li>
                  <li><i class="bi bi-check2"></i>Geographic location data</li>
                  <li><i class="bi bi-check2"></i>Device and browser information</li>
                </ul>
              </div>
              <div class="col-lg-6 text-center">
                <div class="border rounded-3 p-4 bg-white">
                  <h5 class="mb-3">Document Analytics</h5>
                  <div class="d-flex justify-content-between mb-3">
                    <div><div class="fw-bold">142</div><div class="text-muted small">Total Views</div></div>
                    <div><div class="fw-bold">87</div><div class="text-muted small">Unique Views</div></div>
                    <div><div class="fw-bold">3:42</div><div class="text-muted small">Avg. Time</div></div>
                  </div>
                  <div class="bg-light rounded-3 p-2 mb-3">
                    <div class="d-flex justify-content-between small"><span>Page 1</span><span>92% viewed</span></div>
                    <div class="progress mb-2"><div class="progress-bar" role="progressbar" style="width:92%"></div></div>
                    <div class="d-flex justify-content-between small"><span>Page 2</span><span>78% viewed</span></div>
                    <div class="progress mb-2"><div class="progress-bar" role="progressbar" style="width:78%"></div></div>
                    <div class="d-flex justify-content-between small"><span>Page 3</span><span>64% viewed</span></div>
                    <div class="progress"><div class="progress-bar" role="progressbar" style="width:64%"></div></div>
                  </div>
                  <button class="btn btn-ghost w-100">View Full Report</button>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.tab-content -->
      </div><!-- /.demo-card -->
    </div>
  </section>

  {{-- USE CASES --}}
  <section class="use-cases">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Perfect For Every Use Case</h2>
        <p class="section-subtitle">PDFOneLink helps professionals across industries share documents securely</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="use-case-card">
            <div class="use-case-icon"><i class="bi bi-briefcase"></i></div>
            <h4>Business Proposals</h4>
            <p>Share sensitive business proposals while preventing unauthorized distribution.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="use-case-card">
            <div class="use-case-icon"><i class="bi bi-mortarboard"></i></div>
            <h4>Educational Materials</h4>
            <p>Distribute course materials while maintaining control over your intellectual property.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="use-case-card">
            <div class="use-case-icon"><i class="bi bi-file-text"></i></div>
            <h4>Legal Documents</h4>
            <p>Share legal contracts and documents securely with clients and colleagues.</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="use-case-card">
            <div class="use-case-icon"><i class="bi bi-clipboard-check"></i></div>
            <h4>Reports & Whitepapers</h4>
            <p>Distribute valuable content while tracking engagement and interest.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- INTEGRATIONS --}}
  <section class="integration-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Works With Your Tools</h2>
        <p class="section-subtitle">PDFOneLink integrates seamlessly with the platforms you already use</p>
      </div>

      <div class="d-flex flex-wrap justify-content-center align-items-center">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/slack/slack-original.svg" class="integration-logo" alt="Slack">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/google/google-original.svg" class="integration-logo" alt="Google">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/microsoft/microsoft-original.svg" class="integration-logo" alt="Microsoft">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/salesforce/salesforce-original.svg" class="integration-logo" alt="Salesforce">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/wordpress/wordpress-original.svg" class="integration-logo" alt="WordPress">
        <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/zapier/zapier-original.svg" class="integration-logo" alt="Zapier">
      </div>

      <div class="text-center mt-5">
        <a href="{{ url('/integrations') }}" class="btn btn-brand">View All Integrations</a>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="features-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mb-4">Ready to try PDFOneLink?</h2>
          <p class="text-muted mb-4">Start sharing your PDFs securely today. No credit card required.</p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/registration') }}" class="btn btn-brand btn-lg">Get Started for Free</a>
            <a href="javascript:void(0)" class="btn btn-ghost btn-lg">Schedule a Demo</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

  // Demo interactivity: toggle card border on switches
  document.querySelectorAll('.form-check-input').forEach(input => {
    input.addEventListener('change', function(){
      const card = this.closest('.border');
      if(!card) return;
      if(this.checked){ card.classList.add('border-primary'); }
      else{ card.classList.remove('border-primary'); }
    });
  });
</script>
@endpush
