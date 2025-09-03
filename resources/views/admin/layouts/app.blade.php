<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Dashboard')</title>

  {{-- Favicons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">
  <link rel="shortcut icon" href="{{ asset('assets/favicon_io/favicon.ico') }}">

  <!-- DM Sans + Bootstrap + Icons -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="{{ asset('assets/vendorapp/css/style.css') }}" rel="stylesheet">
  
  @stack('styles')
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    @include('admin.layouts.partials.sidebar')

    <div class="overlay" id="overlay"></div>

    <!-- Main -->
    <main class="main" id="mainContent">
      <!-- Topbar -->
      @include('admin.layouts.partials.header')

      <!-- Content -->
      @yield('content')

      <!-- Footer -->
      @include('admin.layouts.partials.footer')
    </main>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/vendorapp/js/main.js') }}"></script>
  
  @stack('scripts')
</body>
</html>