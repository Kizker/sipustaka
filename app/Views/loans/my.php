<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h4 class="mb-3">Peminjaman Saya</h4>

<?php if (empty($loans)): ?>
  <div class="alert alert-info">Belum ada peminjaman.</div>
<?php else: ?>
  <div class="list-group">
    <?php foreach ($loans as $l): ?>
      <div class="list-group-item">
        <div class="d-flex justify-content-between">
          <div>
            <div class="fw-semibold"><?= esc($l['title']) ?></div>
            <div class="text-muted small">Penulis: <?= esc($l['author']) ?></div>
            <div class="text-muted small">Pinjam: <?= format_tanggal_indo($l['borrowed_at']) ?></div>
            <div class="text-muted small">Rencana kembali: <?= format_tanggal_indo($l['due_at']) ?></div>
            <div class="text-muted small">Status: <?= esc($l['status']) ?></div>
          </div>

          <?php if ($l['status'] === 'borrowed'): ?>
            <form method="post" action="/return/<?= (int)$l['id'] ?>">
              <?= csrf_field() ?>
              <button class="btn btn-success btn-sm">Kembalikan</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
