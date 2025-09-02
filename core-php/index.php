<?php
$page_title = 'PDFOneLink — Secure PDF Sharing & Analytics in One Link';
$page_css = '';
$page_js = 'assets/webapp/js/index.js';
include 'include/header.php';
?>

  <header class="hero">
    <div class="container position-relative">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <span class="badge"><i class="bi bi-shield-lock me-1"></i> Secure by default</span>
          <h1 class="display-5 fw-bold mb-4">Share & track PDFs with <span class="text-primary">one secure link</span></h1>
          <p class="lead text-muted mb-4">
            Upload PDFs, control permissions (view-only, watermark, expiry), and get real-time analytics on opens, location, device, and time-on-page. Embed anywhere with a simple iframe.
          </p>
          <div class="d-flex flex-wrap gap-3 mb-4">
            <a href="registration.php" class="btn btn-brand btn-lg"><i class="bi bi-rocket-takeoff me-2"></i>Start free</a>
            <a href="#demo" class="btn btn-ghost btn-lg"><i class="bi bi-play-circle me-2"></i>See demo</a>
          </div>
          <div class="d-flex flex-wrap gap-4 text-muted small">
            <span><i class="bi bi-check2-circle me-1 check"></i>No-code embed</span>
            <span><i class="bi bi-check2-circle me-1 check"></i>Disable download/print</span>
            <span><i class="bi bi-check2-circle me-1 check"></i>Analytics & webhooks</span>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="code-card">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 toolbar">
              <div class="d-flex align-items-center gap-2">
                <span class="badge text-bg-secondary">Embed Snippet</span>
              </div>
              <button class="btn btn-sm btn-outline-light" id="copySnippet"><i class="bi bi-clipboard"></i> Copy</button>
            </div>
<pre><code id="snippet">&lt;iframe
  src="https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN"
  width="100%" height="600"
  style="border:none;border-radius:12px;"
  allow="clipboard-write"&gt;&lt;/iframe&gt;</code></pre>
          </div>
          <div class="text-center text-muted small mt-3">Replace <code>YOUR_DOC_TOKEN</code> with your secure link.</div>
        </div>
      </div>
    </div>
  </header>

  <!-- Features -->
  <section id="features" class="section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Everything you need to share PDFs safely</h2>
        <p class="section-subtitle">Security, control, and visibility — without plugins or complex setup.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-cloud-upload"></i></div>
            <h5>Upload & organize</h5>
            <p class="text-muted mb-0">Fast uploads, foldering, and versioning to keep your documents tidy.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-link-45deg"></i></div>
            <h5>Share via one link</h5>
            <p class="text-muted mb-0">Generate time-limited links with domain/IP lock or password protection.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
            <h5>Permission control</h5>
            <p class="text-muted mb-0">View-only mode, disable download/print, add dynamic watermarks.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <h5>PDF analytics</h5>
            <p class="text-muted mb-0">Track opens, location, device, page-by-page time, and search terms.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-code-slash"></i></div>
            <h5>Embed anywhere</h5>
            <p class="text-muted mb-0">Drop-in iframe works with any website, CMS, or app.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-patch-check"></i></div>
            <h5>APIs & webhooks</h5>
            <p class="text-muted mb-0">Automate uploads, permissions, and analytics export to your stack.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How it works -->
  <section id="how" class="section bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">How it works</h2>
        <p class="section-subtitle">Set up in minutes — no code required.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">1</div>
            <div class="feature-icon mx-auto mb-3" style="background:#ecfeff;color:#155e75;"><i class="bi bi-person-plus"></i></div>
            <h6>Create your account</h6>
            <p class="text-muted mb-0 small">Sign up and verify email. Add your brand logo & domain rules.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">2</div>
            <div class="feature-icon mx-auto mb-3" style="background:#fef9c3;color:#854d0e;"><i class="bi bi-file-earmark-arrow-up"></i></div>
            <h6>Upload PDFs</h6>
            <p class="text-muted mb-0 small">Drag & drop. We process for search, thumbnails, and OCR (optional).</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">3</div>
            <div class="feature-icon mx-auto mb-3" style="background:#dcfce7;color:#166534;"><i class="bi bi-sliders2-vertical"></i></div>
            <h6>Set permissions</h6>
            <p class="text-muted mb-0 small">Choose view-only, disable download/print, add watermarks & expiry.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="step-card">
            <div class="step-number">4</div>
            <div class="feature-icon mx-auto mb-3" style="background:#fae8ff;color:#6b21a8;"><i class="bi bi-graph-up"></i></div>
            <h6>Share & track</h6>
            <p class="text-muted mb-0 small">Share one link. See opens, location, device, and page engagement.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- NEW: Demo Section -->
  <section id="demo" class="section demo-section">
    <div class="container demo-container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Live Embed Demo</h2>
        <p class="section-subtitle">See how PDFOneLink works with this interactive demo</p>
      </div>
      
      <div class="row g-5 align-items-center">
        <div class="col-lg-6">
          <div class="demo-card">
            <div class="demo-header">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <span class="bg-danger rounded-circle d-inline-block" style="width: 12px; height: 12px;"></span>
                  <span class="bg-warning rounded-circle d-inline-block mx-2" style="width: 12px; height: 12px;"></span>
                  <span class="bg-success rounded-circle d-inline-block" style="width: 12px; height: 12px;"></span>
                </div>
                <div class="text-muted small">https://pdfonelink.com/view?doc=DEMO_TOKEN</div>
              </div>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  <i class="bi bi-gear"></i>
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Refresh</a></li>
                  <li><a class="dropdown-item" href="#">Open in new tab</a></li>
                </ul>
              </div>
            </div>
            
            <div class="demo-browser">
              <iframe src="https://pdfonelink.com/view?doc=DEMO_TOKEN" title="PDFOneLink Demo Viewer" allow="clipboard-write"></iframe>
            </div>
            
            <div class="demo-controls">
              <div class="input-group">
                <span class="input-group-text">Token</span>
                <input type="text" class="form-control token-input" value="DEMO_TOKEN" readonly>
                <button class="btn btn-outline-secondary" type="button" id="copyToken">
                  <i class="bi bi-clipboard"></i>
                </button>
              </div>
              
              <div class="demo-feature-grid mt-3">
                <div class="demo-feature">
                  <div class="demo-feature-icon">
                    <i class="bi bi-eye"></i>
                  </div>
                  <div>
                    <div class="fw-medium">View-only mode</div>
                    <div class="text-muted small">Prevent downloads</div>
                  </div>
                </div>
                
                <div class="demo-feature">
                  <div class="demo-feature-icon">
                    <i class="bi bi-shield"></i>
                  </div>
                  <div>
                    <div class="fw-medium">Secure access</div>
                    <div class="text-muted small">Token-based security</div>
                  </div>
                </div>
                
                <div class="demo-feature">
                  <div class="demo-feature-icon">
                    <i class="bi bi-graph-up"></i>
                  </div>
                  <div>
                    <div class="fw-medium">Real-time analytics</div>
                    <div class="text-muted small">Track viewer engagement</div>
                  </div>
                </div>
                
                <div class="demo-feature">
                  <div class="demo-feature-icon">
                    <i class="bi bi-clock"></i>
                  </div>
                  <div>
                    <div class="fw-medium">Expiry control</div>
                    <div class="text-muted small">Set access duration</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <h3 class="section-title">Embed anywhere in seconds</h3>
          <p class="text-muted mb-4">Our secure iframe embed works with any website, CMS, or application. Control access with expiring tokens, domain restrictions, and IP allowlisting.</p>
          
          <div class="d-flex flex-column gap-3">
            <div class="d-flex align-items-start">
              <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 mt-1">
                <i class="bi bi-code-slash text-primary"></i>
              </div>
              <div>
                <h6 class="mb-1">Copy & paste embedding</h6>
                <p class="text-muted small mb-0">Just copy the iframe code and add it to your HTML</p>
              </div>
            </div>
            
            <div class="d-flex align-items-start">
              <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 mt-1">
                <i class="bi bi-sliders text-primary"></i>
              </div>
              <div>
                <h6 class="mb-1">Customize permissions</h6>
                <p class="text-muted small mb-0">Control download, print, and access expiration</p>
              </div>
            </div>
            
            <div class="d-flex align-items-start">
              <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 mt-1">
                <i class="bi bi-bar-chart text-primary"></i>
              </div>
              <div>
                <h6 class="mb-1">Track engagement</h6>
                <p class="text-muted small mb-0">See who viewed your PDF and for how long</p>
              </div>
            </div>
          </div>
          
          <div class="d-flex gap-3 mt-4">
            <a href="registration" class="btn btn-brand"><i class="bi bi-rocket-takeoff me-2"></i>Get started</a>
            <a href="/docs" class="btn btn-ghost"><i class="bi bi-journal-text me-2"></i>View docs</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing -->
  <section id="pricing" class="section pricing-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Simple, transparent pricing</h2>
        <p class="section-subtitle">Start free. Upgrade when you're ready.</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="plan">
            <h5 class="plan-title">Free</h5>
            <div class="plan-price">$0<span class="fs-6 text-muted">/mo</span></div>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>500 MB storage</li>
              <li><i class="bi bi-check2 check"></i>Basic analytics</li>
              <li><i class="bi bi-check2 check"></i>Embed viewer</li>
              <li><i class="bi bi-x text-muted"></i>Permission controls</li>
              <li><i class="bi bi-x text-muted"></i>Custom branding</li>
            </ul>
            <a href="registration" class="btn btn-ghost w-100">Start free</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="plan featured">
            <h5 class="plan-title">Pro</h5>
            <div class="plan-price">$12<span class="fs-6 text-muted">/mo</span></div>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>10 GB storage</li>
              <li><i class="bi bi-check2 check"></i>Advanced analytics</li>
              <li><i class="bi bi-check2 check"></i>Disable download/print</li>
              <li><i class="bi bi-check2 check"></i>Custom watermark</li>
              <li><i class="bi bi-check2 check"></i>Link expiry & revocation</li>
            </ul>
            <a href="registration" class="btn btn-brand w-100">Choose Pro</a>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="plan">
            <h5 class="plan-title">Business</h5>
            <div class="plan-price">$29<span class="fs-6 text-muted">/mo</span></div>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>Unlimited viewers</li>
              <li><i class="bi bi-check2 check"></i>SSO, API & webhooks</li>
              <li><i class="bi bi-check2 check"></i>OCR & full-text search</li>
              <li><i class="bi bi-check2 check"></i>Domain/IP allowlists</li>
              <li><i class="bi bi-check2 check"></i>Priority support</li>
            </ul>
            <a href="/contact" class="btn btn-ghost w-100">Talk to sales</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Frequently asked questions</h2>
        <p class="section-subtitle">Everything you need to know about PDFOneLink.</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-6">
          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-shield-lock"></i>How secure are my documents?</h6>
            <p class="text-muted mb-0">Your PDFs are served via signed tokens. You can set expiry, disable download/print, and watermark with viewer identity.</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-graph-up"></i>What analytics do I get?</h6>
            <p class="text-muted mb-0">Opens, location (approx.), device, referrer, time-on-page, and search terms — exportable via CSV/api.</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-code-slash"></i>Can I embed the viewer?</h6>
            <p class="text-muted mb-0">Yes, paste the iframe snippet anywhere. You can also lock by domain to prevent misuse.</p>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-people"></i>Do you support teams?</h6>
            <p class="text-muted mb-0">Role-based access, organization workspaces, and audit logs are available on Business plans.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="section">
    <div class="container">
      <div class="cta-section">
        <h2 class="fw-bold mb-3">Start sharing secure, trackable PDFs today</h2>
        <p class="text-muted mb-4">It only takes a minute to set up. No credit card required.</p>
        <a href="registration" class="btn btn-brand btn-lg"><i class="bi bi-rocket-takeoff me-2"></i>Create your free account</a>
      </div>
    </div>
  </section>

<?php include 'include/footer.php'; ?>

