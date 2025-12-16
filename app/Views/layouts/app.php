<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= esc($title ?? 'SiPustaka') ?></title>

  <!-- PWA -->
  <link rel="manifest" href="/manifest.webmanifest">
  <meta name="theme-color" content="#0d6efd">

  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { padding-bottom: 70px; }
  </style>
</head>
<body class="bg-light">

<?php
  $path = trim(service('uri')->getPath(), '/');
  $isAdminArea = str_starts_with($path, 'admin');
  $isAuthPage  = in_array($path, ['login', 'register', 'forgot-password'], true);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">SiPustaka</a>
    <div class="navbar-text text-white-50 d-none d-md-block">Layanan Busa Pustaka</div>

    <div class="ms-auto d-flex gap-2">
      <?php if (function_exists('auth') && auth()->loggedIn()): ?>
        <a class="btn btn-outline-light btn-sm" href="/my-loans">Peminjaman Saya</a>
        <?php if (auth()->user()->inGroup('admin')): ?>
          <a class="btn btn-light btn-sm" href="/admin">Admin</a>
        <?php endif; ?>
        <a class="btn btn-danger btn-sm" href="/logout">Keluar</a>
      <?php else: ?>
        <?php if ($isAuthPage): ?>
          <a class="btn btn-light btn-sm" href="/">← Katalog</a>
        <?php endif; ?>

        <a class="btn btn-outline-light btn-sm" href="/login">Masuk</a>
        <a class="btn btn-light btn-sm" href="/register">Daftar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
  <?php endif; ?>

  <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Register Service Worker (PWA)
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js');
  }
</script>

<?php if (function_exists('auth') && auth()->loggedIn() && !$isAdminArea && !$isAuthPage): ?>
  <?= view('layouts/_bottom_nav') ?>
<?php endif; ?>

</body>
</html>
