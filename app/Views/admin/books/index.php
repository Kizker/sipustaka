<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h4 class="mb-0">Kelola Buku</h4>
    <div class="text-muted small">Tambah, edit, hapus, dan cari buku.</div>
  </div>
  <a class="btn btn-primary btn-sm" href="/admin/books/new">+ Tambah Buku</a>
</div>

<form class="row g-2 mb-3" method="get" action="/admin/books">
  <div class="col-12 col-md-6">
    <input type="text" class="form-control" name="q" value="<?= esc($q) ?>" placeholder="Cari judul / penulis / ISBN...">
  </div>
  <div class="col-12 col-md-auto">
    <button class="btn btn-outline-primary">Cari</button>
    <a class="btn btn-outline-secondary" href="/admin/books">Reset</a>
  </div>
</form>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px;">ID</th>
          <th>Buku</th>
          <th>Penulis</th>
          <th style="width:120px;">Stok</th>
          <th style="width:200px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($books)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data buku.</td></tr>
        <?php endif; ?>

        <?php foreach ($books as $b): ?>
          <tr>
            <td><?= (int)$b['id'] ?></td>

            <td>
              <div class="d-flex gap-2 align-items-center">
                <?php
                  $thumb = (!empty($b['cover']) && is_file(FCPATH.'uploads/covers/'.$b['cover']))
                    ? '/uploads/covers/'.$b['cover']
                    : 'https://via.placeholder.com/60x80?text=No';
                ?>
                <img src="<?= esc($thumb) ?>"
                  class="rounded border"
                  style="width:50px; height:70px; object-fit:cover; object-position:center;">
                <div>
                  <div class="fw-semibold"><?= esc($b['title']) ?></div>
                  <div class="text-muted small">ISBN: <?= esc($b['isbn'] ?? '-') ?></div>
                </div>
              </div>
            </td>

            <td><?= esc($b['author']) ?></td>

            <td>
              <span class="badge <?= ((int)$b['stock'] > 0 ? 'bg-success' : 'bg-secondary') ?>">
                <?= (int)$b['stock'] ?>
              </span>
            </td>

            <td class="d-flex gap-2">
              <a class="btn btn-outline-primary btn-sm" href="/admin/books/<?= (int)$b['id'] ?>/edit">Edit</a>

              <form method="post" action="/admin/books/<?= (int)$b['id'] ?>/delete"
                    onsubmit="return confirm('Hapus buku ini?');">
                <?= csrf_field() ?>
                <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if (isset($pager) && $pager): ?>
  <div class="mt-3"><?= $pager->links() ?></div>
<?php endif; ?>

<?= $this->endSection() ?>
