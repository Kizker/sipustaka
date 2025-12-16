<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Tambah Buku</h4>
  <a class="btn btn-outline-secondary btn-sm" href="/admin/books">← Kembali</a>
</div>

<?php $errors = session('errors') ?? []; ?>
<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form class="card shadow-sm" method="post" action="/admin/books" enctype="multipart/form-data">
  <div class="card-body">
    <?= csrf_field() ?>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">ISBN</label>
        <input class="form-control" name="isbn" value="<?= esc(old('isbn') ?? '') ?>">
      </div>

      <div class="col-md-8">
        <label class="form-label">Judul *</label>
        <input class="form-control" name="title" value="<?= esc(old('title') ?? '') ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Penulis *</label>
        <input class="form-control" name="author" value="<?= esc(old('author') ?? '') ?>" required>
      </div>

      <div class="col-md-6">
        <label class="form-label">Penerbit</label>
        <input class="form-control" name="publisher" value="<?= esc(old('publisher') ?? '') ?>">
      </div>

      <div class="col-md-3">
        <label class="form-label">Tahun</label>
        <input class="form-control" name="year" value="<?= esc(old('year') ?? '') ?>">
      </div>

      <div class="col-md-5">
        <label class="form-label">Kategori</label>
        <input class="form-control" name="category" value="<?= esc(old('category') ?? '') ?>">
      </div>

      <div class="col-md-4">
        <label class="form-label">Stok *</label>
        <input type="number" class="form-control" name="stock" value="<?= esc(old('stock') ?? '0') ?>" min="0" required>
      </div>

      <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="4"><?= esc(old('description') ?? '') ?></textarea>
      </div>

      <div class="col-12">
        <label class="form-label">Cover Buku</label>
        <input type="file" class="form-control" name="cover" accept="image/*">
        <div class="form-text">jpg/png/webp max 2MB</div>
      </div>
    </div>
  </div>

  <div class="card-footer d-flex justify-content-end gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a class="btn btn-outline-secondary" href="/admin/books">Batal</a>
  </div>
</form>

<?= $this->endSection() ?>
