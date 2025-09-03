<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ asset('assets/logo/onelinkpdf-logo.png') }}" alt="OneLinkPDF" style="height:36px; width:auto;" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"
            aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="{{ url('/features') }}">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/how-it-works') }}">How It Works</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">Pricing</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
        <li class="nav-item ms-lg-3"><a class="btn btn-ghost btn-sm" href="{{ url('/login') }}">Log in</a></li>
        <li class="nav-item ms-2"><a class="btn btn-brand btn-sm" href="{{ url('/register') }}">Start free</a></li>
      </ul>
    </div>
  </div>
</nav>
