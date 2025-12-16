<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
  <div class="text-center py-5">
    <h4>Kamu sedang offline</h4>
    <p class="text-muted">Coba nyalakan koneksi internet untuk akses fitur lengkap.</p>
    <a class="btn btn-primary" href="/">Kembali</a>
  </div>
<?= $this->endSection() ?>
