<?php
$path = trim(service('uri')->getPath(), '/');

// Anggap semua halaman katalog & detail buku = menu "Katalog"
$isCatalog = ($path === '' || $path === 'books' || str_starts_with($path, 'books/'));
$isProfile = ($path === 'profile' || str_starts_with($path, 'profile/'));
?>

<nav class="navbar bg-white border-top fixed-bottom">
  <div class="container d-flex justify-content-around py-1">

    <a href="<?= base_url('/') ?>"
       class="text-decoration-none text-center <?= $isCatalog ? 'text-primary fw-semibold' : 'text-muted' ?>">
      <div style="font-size:18px; line-height:1;">📚</div>
      <div class="small">Katalog</div>
    </a>

    <a href="<?= base_url('profile') ?>"
       class="text-decoration-none text-center <?= $isProfile ? 'text-primary fw-semibold' : 'text-muted' ?>">
      <div style="font-size:18px; line-height:1;">👤</div>
      <div class="small">Profil</div>
    </a>

  </div>
</nav>
