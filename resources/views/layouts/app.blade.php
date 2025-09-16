<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <title>@yield('title', 'OneLinkPDF — Secure PDF Sharing & Analytics in One Link')</title>
  <meta name="description" content="@yield('meta_description', 'OneLinkPDF lets you share secure, trackable PDFs with one link—expiry, watermark, view-only, and real-time analytics. Capture leads before access to turn every PDF view into a new business opportunity.')">
  <meta name="keywords" content="PDF sharing, secure PDF, track PDFs, PDF analytics, OneLinkPDF, document security, watermark PDF, share PDF online, view-only PDF, PDF expiry, PDF SaaS, lead capture, gated content, lead generation">
  <meta name="author" content="ONELINKPDF">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="theme-color" content="#0b5ed7">

  {{-- Canonical --}}
  <link rel="canonical" href="https://www.onelinkpdf.com/">

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.onelinkpdf.com/">
  <meta property="og:title" content="@yield('og_title', 'OneLinkPDF — Share & Track PDFs with One Secure Link')">
  <meta property="og:description" content="@yield('og_description', 'Upload PDFs, control permissions (view-only, watermark, expiry), get real-time analytics—and capture leads before access to grow your pipeline.')">
  <meta property="og:image" content="{{ asset('assets/seo/og-image.jpg') }}">
  <meta property="og:image:alt" content="OneLinkPDF — Secure PDF Sharing, Analytics, and Lead Capture">
  <meta property="og:site_name" content="OneLinkPDF">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="https://www.onelinkpdf.com/">
  <meta name="twitter:title" content="@yield('twitter_title', 'OneLinkPDF — Secure PDF Sharing & Analytics')">
  <meta name="twitter:description" content="@yield('twitter_description', 'Share secure PDFs with expiry & watermark, track opens & time-on-page, and capture leads before access.')">
  <meta name="twitter:image" content="{{ asset('assets/seo/twitter-card.jpg') }}">
  <meta name="twitter:image:alt" content="OneLinkPDF — Secure PDF Sharing, Analytics, and Lead Capture">
  <meta name="twitter:site" content="&#64;onelinkpdf">
  <meta name="twitter:creator" content="&#64;onelinkpdf">

  {{-- Favicons --}}
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon_io/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon_io/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon_io/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('assets/favicon_io/site.webmanifest') }}">
  <link rel="shortcut icon" href="{{ asset('assets/favicon_io/favicon.ico') }}">

  {{-- Vendor CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  {{-- Base layout CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/webapp/css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/webapp/css/style.css') }}">

  {{-- JSON-LD: build as PHP arrays to avoid Blade parsing @context/@type --}}
  @php
    $softwareLd = [
      '@context' => 'https://schema.org',
      '@type' => 'SoftwareApplication',
      'name' => 'OneLinkPDF',
      'operatingSystem' => 'Web',
      'applicationCategory' => 'BusinessApplication',
      'applicationSuite' => 'OneLinkPDF',
      'softwareVersion' => '1.0',
      'url' => 'https://www.onelinkpdf.com/',
      'image' => 'https://www.onelinkpdf.com/assets/seo/og-image.jpg',
      'publisher' => [ '@type' => 'Organization', 'name' => 'ONELINKPDF' ],
      'description' => 'OneLinkPDF is a secure PDF sharing and analytics platform with expiry, watermark, view-only mode, and real-time insights. Capture leads before access to turn every PDF view into a new business opportunity.',
      'offers' => [ '@type' => 'Offer', 'price' => '0.00', 'priceCurrency' => 'USD' ],
    ];

    $siteLd = [
      '@context' => 'https://schema.org',
      '@type' => 'WebSite',
      'name' => 'OneLinkPDF',
      'url' => 'https://www.onelinkpdf.com/',
      'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => 'https://www.onelinkpdf.com/search?q={query}',
        'query-input' => 'required name=query'
      ],
      'inLanguage' => 'en',
    ];
  @endphp

  <script type="application/ld+json">@json($softwareLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>
  <script type="application/ld+json">@json($siteLd, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)</script>

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

  @stack('scripts')
  
</body>
</html>