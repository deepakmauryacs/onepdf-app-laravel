{{-- resources/views/layouts/partials/footer.blade.php --}}
@php
  // Helper that prefers a named route, otherwise falls back to a URL
  use Illuminate\Support\Facades\Route;
  $link = fn (string $route, string $fallback = '/') => Route::has($route) ? route($route) : url($fallback);
@endphp

<footer class="footer">
  <div class="container">
    <div class="row g-5">
      {{-- Brand / About --}}
      <div class="col-md-6 col-lg-3">
       <h5 class="mb-4 d-flex align-items-center">
        <img src="{{ asset('assets/logo/onelinkpdf-logo.png') }}" alt="PDFOneLink Logo" class="me-2" style="height:28px;">
      </h5>

        <p class="mb-3">Secure PDF upload, sharing, permissions, and analytics — all in one link.</p>
        <p class="small text-secondary mb-4">
          Token-based access, view-only controls, watermarking, and detailed analytics help you share confidently.
        </p>
        <div class="social-links d-flex gap-2">
          <a href="#" aria-label="Twitter" target="_blank" rel="noopener"><i class="bi bi-twitter"></i></a>
          <a href="#" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="GitHub" target="_blank" rel="noopener"><i class="bi bi-github"></i></a>
          <a href="#" aria-label="Facebook" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
        </div>
      </div>

      {{-- Quick links --}}
      <div class="col-6 col-lg-2">
        <h6 class="mb-4">Quick links</h6>
        <ul>
          <li><a href="{{ route('features') }}">Features</a></li>
          <li><a href="{{ route('pricing') }}">Pricing</a></li>
          <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
        </ul>
      </div>

      {{-- Resources (new) --}}
      <div class="col-6 col-lg-2">
        <h6 class="mb-4">Resources</h6>
        <ul>
          {{-- Docs link exists on the site; fall back to /docs if you haven’t named the route yet --}}
          <li><a href="{{ Route::has('docs') ? route('docs') : url('/docs') }}">Documentation</a></li>
          {{-- API & webhooks are highlighted on the homepage; link your preferred docs path --}}
          <li><a href="{{ url('/docs#api') }}">API &amp; Webhooks</a></li>
          {{-- Deep-link to your live embed demo section on the homepage --}}
          <li><a href="{{ route('home') }}#demo">Embed Demo</a></li>
          {{-- FAQ lives on the homepage section --}}
          <li><a href="{{ route('home') }}#faq">FAQ</a></li>
        </ul>
      </div>

      {{-- Contact --}}
      <div class="col-6 col-lg-2">
        <h6 class="mb-4">Contact Us</h6>
        <ul>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          <li><a href="{{ route('partnerships') }}">Partnerships</a></li>
          <li><a href="{{ route('contact') }}">General Inquiry</a></li>
        </ul>
        <address class="small text-secondary mt-3 mb-0">
          <div>support@pdfonelink.com</div>
          <div>+1 (555) 123-4567</div>
        </address>
      </div>

      {{-- Newsletter --}}
      <div class="col-6 col-lg-3">
        <h6 class="mb-4">Newsletter</h6>
        <p class="small mb-3">Get the latest updates, tips, and guides.</p>

        <form id="newsletterForm" class="d-flex gap-2 mb-2" method="post" action="{{ route('subscribe') }}" novalidate>
          @csrf
          <input type="email" class="form-control form-control-sm" name="email" placeholder="Email address" required>
          <button class="btn btn-brand btn-sm" type="submit">
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>

        <div id="newsletterFeedback" class="small mb-2"></div>
        <div class="small text-secondary">No spam. Unsubscribe anytime.</div>
      </div>
    </div>

    <div class="footer-bottom">
      <p class="copyright mb-0">© {{ now()->year }} PDFOneLink. All rights reserved.</p>
      <div class="legal-links">
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <span class="divider">•</span>
        <a href="{{ route('terms') }}">Terms of Service</a>
        <span class="divider">•</span>
        {{-- Optional extras if/when available: --}}
        @if (Route::has('security'))
          <a href="{{ route('security') }}">Security</a>
          <span class="divider">•</span>
        @endif
        <a href="{{ url('/sitemap.xml') }}">Sitemap</a>
      </div>
    </div>
  </div>
</footer>
