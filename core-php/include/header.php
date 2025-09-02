<?php
// Common header
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$web_base_url = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= isset($page_title) ? $page_title : 'PDFOneLink'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $web_base_url ?>assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $web_base_url ?>assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $web_base_url ?>assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="<?= $web_base_url ?>assets/favicon_io/site.webmanifest">
    <link rel="shortcut icon" href="<?= $web_base_url ?>assets/favicon_io/favicon.ico">
    <link rel="stylesheet" href="assets/webapp/css/layout.css">
    <?php if (!empty($page_css)): ?>
    <link rel="stylesheet" href="<?= $page_css ?>">
    <?php endif; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= $web_base_url ?>"><i class="bi bi-file-earmark-pdf"></i>PDFOneLink</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= $current_page === 'features.php' ? 'active' : '' ?>" href="features">Features</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page === 'how-it-works.php' ? 'active' : '' ?>" href="how-it-works">How It Works</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page === 'pricing.php' ? 'active' : '' ?>" href="pricing">Pricing</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page === 'contact.php' ? 'active' : '' ?>" href="contact">Contact</a></li>
        <li class="nav-item ms-lg-3"><a class="btn btn-ghost btn-sm <?= $current_page === 'login' ? 'active' : '' ?>" href="login">Log in</a></li>
        <li class="nav-item ms-2"><a class="btn btn-brand btn-sm <?= $current_page === 'registration' ? 'active' : '' ?>" href="registration">Start free</a></li>
      </ul>
    </div>
  </div>
</nav>

