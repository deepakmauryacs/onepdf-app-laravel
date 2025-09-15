<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'OneLinkPDF — Secure PDF Sharing & Analytics in One Link')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- Vendor CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

  {{-- Favicons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">
  <link rel="shortcut icon" href="{{ asset('assets/favicon_io/favicon.ico') }}">

  {{-- Base layout CSS (optional) --}}
  <link rel="stylesheet" href="{{ asset('assets/webapp/css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/webapp/css/style.css') }}">

  @stack('styles')
</head>
<body>

  @include('layouts.partials.header')

  <main>
    @yield('content')
  </main>

  @include('layouts.partials.footer')

  {{-- Vendor JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- App JS --}}
  <script src="{{ asset('assets/webapp/js/main.js') }}"></script>

    <script>
        window.analyticsEndpoint = "{{ route('analytics.track') }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('assets/analytics.js') }}"></script>

  @stack('scripts')
</body>
</html>
