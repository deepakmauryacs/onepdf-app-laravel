{{-- resources/views/index.blade.php --}}
@extends('layouts.app')

@section('title', 'PDFOneLink — Secure PDF Sharing & Analytics in One Link')

@push('styles')
  {{-- Optional page-level CSS --}}
  {{-- <link rel="stylesheet" href="{{ asset('assets/webapp/css/style.css') }}"> --}}
@endpush
@push('styles')
<style>
/* ===== HERO (top banner) ===== */
.hero-pro{
  overflow:hidden;
  /* light gradient wash matching landing */
  background:
    radial-gradient(900px 420px at 85% -120px, rgba(99,102,241,.18) 0%, rgba(99,102,241,0) 60%),
    radial-gradient(700px 380px at -10% 110%, rgba(56,189,248,.18) 0%, rgba(56,189,248,0) 60%),
    linear-gradient(180deg, #f7f9ff 0%, #f5faff 38%, #ffffff 100%);
}

/* Gradient word inside H1 */
.text-gradient{
  background: linear-gradient(90deg, #4f46e5, #06b6d4);
  -webkit-background-clip:text; background-clip:text; color:transparent;
}

/* Key points (chips) under CTA */
.pill-list{ display:flex; flex-wrap:wrap; gap:.5rem; }
.pill{
  background: rgba(99,102,241,.08);
  border:1px solid rgba(99,102,241,.22);
  color:#4338ca; padding:.35rem .6rem; border-radius:999px; font-size:.85rem;
}

/* Star rating + logo strip */
.rating i{ color:#fbbf24; }
.logo-strip{ display:flex; gap:14px; align-items:center; opacity:.9; }
.logo-strip img{
  height:22px; filter:grayscale(1); opacity:.85;
  transition:opacity .2s, filter .2s;
}
.logo-strip img:hover{ opacity:1; filter:grayscale(0); }

/* ===== Code glass card (right side) ===== */
.glass-card{
  border-radius:16px;
  overflow:hidden;                            /* ensure inner edges clip correctly */
  background:rgba(255,255,255,.6);
  backdrop-filter: blur(8px);
  border:1px solid rgba(15,23,42,.10);
}
.glass-card .toolbar{
  border-bottom:1px solid rgba(15,23,42,.10);
  background: linear-gradient(180deg, rgba(15,23,42,.65), rgba(15,23,42,.55));
  color:#e2e8f0;
  border-top-left-radius:16px;                /* top corners rounded on header */
  border-top-right-radius:16px;
}
.glass-card .badge,
.glass-card .btn{ color:#e2e8f0; }
.glass-card .code-wrap{ position:relative; }
.glass-card pre{
  margin:0;
  max-height:260px; overflow:auto; padding:1rem 1.25rem;
  background:#0f172a; color:#e2e8f0;
  border-radius:0;                              /* reset all first */
  border-bottom-left-radius:16px;               /* only bottom corners rounded */
  border-bottom-right-radius:16px;
}

/* Snippet language toggle (HTML / React) */
.snippet-tabs .btn{
  --bs-btn-color:#e2e8f0;
  --bs-btn-border-color:rgba(255,255,255,.25);
  padding:.25rem .6rem;
  line-height:1;
}
.snippet-tabs .btn.active{ background:rgba(255,255,255,.15); color:#fff; }
</style>
@endpush


@section('content')

{{-- HERO (Bootstrap 5 + Blade-safe React snippet) --}}
{{-- HERO (Bootstrap 5 + Blade-safe React snippet; no demo preview) --}}
{{-- HERO (light gradient background; no preview block) --}}
{{-- ===== HERO (top banner) ===== --}}
<header class="hero hero-pro py-5 py-lg-6 position-relative">
  <div class="container position-relative">
    <div class="row align-items-center g-5">
      {{-- Left: pitch --}}
      <div class="col-lg-6">
        <div class="d-inline-flex align-items-center gap-2 mb-3">
          <span class="badge bg-success-subtle text-success-emphasis">
            <i class="bi bi-shield-lock me-1"></i> Secure by default
          </span>
          <span class="badge bg-primary-subtle text-primary-emphasis">
            <i class="bi bi-lightning-charge me-1"></i> No-code embed
          </span>
        </div>

        <h1 class="display-5 fw-bold mb-3">
          Share & track PDFs with
          <span class="text-gradient">one secure link</span>
        </h1>

        <p class="lead text-muted mb-4">
          Upload PDFs, control permissions (view-only, watermark, expiry), and get real-time analytics on opens,
          location, device, and time-on-page. Embed anywhere with a simple iframe.
        </p>

        <div class="d-flex flex-wrap gap-3 mb-2">
          <a href="{{ route('register') }}" class="btn btn-brand btn-lg">
            <i class="bi bi-rocket-takeoff me-2"></i>Start free
          </a>
          <a href="#demo" class="btn btn-ghost btn-lg">
            <i class="bi bi-play-circle me-2"></i>See demo
          </a>
        </div>
        <div class="text-muted small mb-4">No credit card required • Cancel anytime</div>

        <div class="pill-list mb-4">
          <span class="pill"><i class="bi bi-check2-circle me-1"></i>Disable download/print</span>
          <span class="pill"><i class="bi bi-check2-circle me-1"></i>Dynamic watermark</span>
          <span class="pill"><i class="bi bi-check2-circle me-1"></i>Domain/IP lock</span>
          <span class="pill"><i class="bi bi-check2-circle me-1"></i>Webhooks & API</span>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3">
          <div class="rating d-inline-flex align-items-center">
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
            <span class="ms-2 small text-muted">4.8/5 from 1,200+ users</span>
          </div>
          <div class="vr d-none d-md-block"></div>
          <div class="small text-muted">Trusted by 2,500+ teams</div>
        </div>

        <div class="logo-strip mt-4">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/slack/slack-original.svg" alt="Slack">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/google/google-original.svg" alt="Google">
          <img src="https://www.svgrepo.com/show/132023/microsoft.svg" alt="Microsoft">
          <img src="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/wordpress/wordpress-original.svg" alt="WordPress">
          <img src="https://www.svgrepo.com/show/354596/zapier-icon.svg" alt="Zapier">
        </div>
      </div>

      {{-- Right: code card with HTML/React/Angular tabs --}}
      <div class="col-lg-6">
        <div class="glass-card shadow-lg">
          <div class="d-flex justify-content-between align-items-center px-3 py-2 toolbar">
            <div class="d-flex align-items-center gap-2">
              <span class="badge text-bg-secondary">Embed Snippet</span>
            </div>

            <div class="btn-group btn-group-sm snippet-tabs" role="tablist" aria-label="Snippet language tabs">
              <button class="btn btn-outline-light active" data-snippet-tab="html" type="button" aria-pressed="true">HTML</button>
              <button class="btn btn-outline-light" data-snippet-tab="react" type="button" aria-pressed="false">React</button>
              <button class="btn btn-outline-light" data-snippet-tab="angular" type="button" aria-pressed="false">Angular</button>
            </div>

            <button class="btn btn-sm btn-outline-light" id="heroCopyBtn">
              <i class="bi bi-clipboard"></i> Copy
            </button>
          </div>

          <div class="code-wrap">
            @verbatim
<pre class="m-0" id="snippetHtml"><code>&lt;iframe
  src="https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN"
  width="100%" height="600"
  style="border:none;border-radius:12px;"
  allow="clipboard-write"&gt;&lt;/iframe&gt;</code></pre>

<pre class="m-0 d-none" id="snippetReact"><code>import React from 'react';

export default function PdfOneLinkEmbed() {
  return (
    &lt;iframe
      src="https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN"
      width="100%" height="600"
      style={{ border: 'none', borderRadius: 12 }}
      allow="clipboard-write"
      title="PDFOneLink Viewer"
    /&gt;
  );
}</code></pre>

<pre class="m-0 d-none" id="snippetAngular"><code>import { Component } from '@angular/core';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';

@Component({
  selector: 'app-pdfonelink-embed',
  template: `
    &lt;iframe
      [src]="docUrl"
      width="100%" height="600"
      style="border:none;border-radius:12px;"
      allow="clipboard-write"
      title="PDFOneLink Viewer"&gt;&lt;/iframe&gt;
  `
})
export class PdfOneLinkEmbedComponent {
  docUrl: SafeResourceUrl;
  constructor(private sanitizer: DomSanitizer) {
    this.docUrl = this.sanitizer.bypassSecurityTrustResourceUrl(
      'https://pdfonelink.com/view?doc=YOUR_DOC_TOKEN'
    );
  }
}</code></pre>
            @endverbatim
          </div>
        </div>
      </div>
    </div>
  </div>
</header>





{{-- Features --}}
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

{{-- How it works --}}
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
          <div class="feature-icon mx-auto mb-3" style="background:#ecfeff;color:#155e75;">
            <i class="bi bi-person-plus"></i>
          </div>
          <h6>Create your account</h6>
          <p class="text-muted mb-0 small">Sign up and verify email. Add your brand logo & domain rules.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-number">2</div>
          <div class="feature-icon mx-auto mb-3" style="background:#fef9c3;color:#854d0e;">
            <i class="bi bi-file-earmark-arrow-up"></i>
          </div>
          <h6>Upload PDFs</h6>
          <p class="text-muted mb-0 small">Drag & drop. We process for search, thumbnails, and OCR (optional).</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-number">3</div>
          <div class="feature-icon mx-auto mb-3" style="background:#dcfce7;color:#166534;">
            <i class="bi bi-sliders2-vertical"></i>
          </div>
          <h6>Set permissions</h6>
          <p class="text-muted mb-0 small">Choose view-only, disable download/print, add watermarks & expiry.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="step-card">
          <div class="step-number">4</div>
          <div class="feature-icon mx-auto mb-3" style="background:#fae8ff;color:#6b21a8;">
            <i class="bi bi-graph-up"></i>
          </div>
          <h6>Share & track</h6>
          <p class="text-muted mb-0 small">Share one link. See opens, location, device, and page engagement.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Demo --}}
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
                <li><a class="dropdown-item" href="#" target="_blank">Open in new tab</a></li>
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
                <div class="demo-feature-icon"><i class="bi bi-eye"></i></div>
                <div>
                  <div class="fw-medium">View-only mode</div>
                  <div class="text-muted small">Prevent downloads</div>
                </div>
              </div>

              <div class="demo-feature">
                <div class="demo-feature-icon"><i class="bi bi-shield"></i></div>
                <div>
                  <div class="fw-medium">Secure access</div>
                  <div class="text-muted small">Token-based security</div>
                </div>
              </div>

              <div class="demo-feature">
                <div class="demo-feature-icon"><i class="bi bi-graph-up"></i></div>
                <div>
                  <div class="fw-medium">Real-time analytics</div>
                  <div class="text-muted small">Track viewer engagement</div>
                </div>
              </div>

              <div class="demo-feature">
                <div class="demo-feature-icon"><i class="bi bi-clock"></i></div>
                <div>
                  <div class="fw-medium">Expiry control</div>
                  <div class="text-muted small">Set access duration</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /left -->

      <div class="col-lg-6">
        <h3 class="section-title">Embed anywhere in seconds</h3>
        <p class="text-muted mb-4">
          Our secure iframe embed works with any website, CMS, or application. Control access with expiring tokens,
          domain restrictions, and IP allowlisting.
        </p>

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
          <a href="{{ url('registration') }}" class="btn btn-brand">
            <i class="bi bi-rocket-takeoff me-2"></i>Get started
          </a>
          <a href="{{ url('docs') }}" class="btn btn-ghost">
            <i class="bi bi-journal-text me-2"></i>View docs
          </a>
        </div>
      </div><!-- /right -->
    </div>
  </div>
</section>

{{-- Pricing --}}
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
          <a href="{{ url('registration') }}" class="btn btn-ghost w-100">Start free</a>
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
          <a href="{{ url('registration') }}" class="btn btn-brand w-100">Choose Pro</a>
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
          <a href="{{ url('contact') }}" class="btn btn-ghost w-100">Talk to sales</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
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

{{-- CTA --}}
<section class="section">
  <div class="container">
    <div class="cta-section">
      <h2 class="fw-bold mb-3">Start sharing secure, trackable PDFs today</h2>
      <p class="text-muted mb-4">It only takes a minute to set up. No credit card required.</p>
      <a href="{{ url('registration') }}" class="btn btn-brand btn-lg">
        <i class="bi bi-rocket-takeoff me-2"></i>Create your free account
      </a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
  {{-- Page-level JS --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      /* ========= HERO: Snippet Tabs (HTML / React / Angular) ========= */
      const tabs = document.querySelectorAll('.snippet-tabs [data-snippet-tab]');
      const panes = {
        html:    document.getElementById('snippetHtml'),
        react:   document.getElementById('snippetReact'),
        angular: document.getElementById('snippetAngular'),
      };
      const copyBtn = document.getElementById('heroCopyBtn');

      function hasPane(key){ return panes[key] && panes[key] instanceof HTMLElement; }

      // Initial active tab: prefer .active button, else first available pane, fallback 'html'
      let active = (function () {
        const btnActive = document.querySelector('.snippet-tabs [data-snippet-tab].active');
        if (btnActive) return btnActive.dataset.snippetTab;
        if (hasPane('html')) return 'html';
        if (hasPane('react')) return 'react';
        if (hasPane('angular')) return 'angular';
        return 'html';
      })();

      function setActive(which){
        active = which;

        // show/hide panes
        Object.entries(panes).forEach(([key, el]) => {
          if (!el) return;
          el.classList.toggle('d-none', key !== which);
        });

        // update buttons + aria
        tabs.forEach(btn => {
          const isCurrent = btn.dataset.snippetTab === which;
          btn.classList.toggle('active', isCurrent);
          btn.setAttribute('aria-pressed', isCurrent ? 'true' : 'false');
        });
      }

      // Mouse click handlers
      tabs.forEach(btn => btn.addEventListener('click', () => setActive(btn.dataset.snippetTab)));

      // Keyboard nav: ← → Home End
      const tabsContainer = document.querySelector('.snippet-tabs');
      if (tabsContainer) {
        tabsContainer.addEventListener('keydown', (e) => {
          const list = Array.from(tabs);
          if (!list.length) return;

          const cur = list.findIndex(b => b.classList.contains('active'));
          if (cur === -1) return;

          let next = cur;
          if (e.key === 'ArrowRight') next = (cur + 1) % list.length;
          else if (e.key === 'ArrowLeft') next = (cur - 1 + list.length) % list.length;
          else if (e.key === 'Home') next = 0;
          else if (e.key === 'End') next = list.length - 1;
          else return;

          e.preventDefault();
          list[next].click();
          list[next].focus();
        });
      }

      // Init default
      setActive(active);

      // Copy current snippet
      function getActiveCodeText() {
        const el = panes[active];
        return el ? (el.textContent || el.innerText || '') : '';
      }
      function flash(btn, html) {
        const prev = btn.innerHTML;
        btn.innerHTML = html;
        setTimeout(() => (btn.innerHTML = prev), 1400);
      }

      copyBtn?.addEventListener('click', async () => {
        const txt = getActiveCodeText();
        try {
          if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(txt);
          } else {
            // Fallback
            const ta = document.createElement('textarea');
            ta.value = txt;
            ta.style.position = 'fixed';
            ta.style.top = '-1000px';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
          }
          flash(copyBtn, '<i class="bi bi-check2"></i> Copied');
        } catch (err) {
          console.error('Copy failed:', err);
          flash(copyBtn, '<i class="bi bi-x-circle"></i> Failed');
        }
      });
    });

    /* ========= Other copy helpers (optional, safe if elements missing) ========= */
    (function() {
      const copySnippetBtn = document.getElementById('copySnippet');
      const copyTokenBtn   = document.getElementById('copyToken');

      if (copySnippetBtn) {
        copySnippetBtn.addEventListener('click', () => {
          const el = document.getElementById('snippet');
          if (!el) return;
          const txt = el.textContent || el.innerText;
          navigator.clipboard.writeText(txt).then(() => {
            const prev = copySnippetBtn.innerHTML;
            copySnippetBtn.innerHTML = '<i class="bi bi-check2"></i> Copied';
            setTimeout(() => (copySnippetBtn.innerHTML = prev), 1500);
          });
        });
      }

      if (copyTokenBtn) {
        copyTokenBtn.addEventListener('click', () => {
          const input = document.querySelector('.token-input');
          if (!input) return;
          navigator.clipboard.writeText(input.value).then(() => {
            const prev = copyTokenBtn.innerHTML;
            copyTokenBtn.innerHTML = '<i class="bi bi-check2"></i>';
            setTimeout(() => (copyTokenBtn.innerHTML = prev), 1500);
          });
        });
      }

      // Footer year
      const y = document.getElementById('y');
      if (y) y.textContent = new Date().getFullYear();
    })();
  </script>
@endpush
