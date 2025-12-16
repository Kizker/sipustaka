<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h4 class="mb-0">Data Peminjaman</h4>
    <div class="text-muted small">Pantau semua transaksi peminjaman buku.</div>
  </div>
  <a class="btn btn-outline-secondary btn-sm" href="/admin">← Dashboard</a>
</div>

<form class="row g-2 mb-3" method="get" action="/admin/loans">
  <div class="col-12 col-md-4">
    <select class="form-select" name="status">
      <option value="all"     <?= $status==='all'?'selected':'' ?>>Semua</option>
      <option value="borrowed"<?= $status==='borrowed'?'selected':'' ?>>Borrowed (Aktif)</option>
      <option value="returned"<?= $status==='returned'?'selected':'' ?>>Returned (Selesai)</option>
    </select>
  </div>
  <div class="col-12 col-md-auto">
    <button class="btn btn-outline-primary">Filter</button>
    <a class="btn btn-outline-secondary" href="/admin/loans">Reset</a>
  </div>
</form>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:80px;">ID</th>
          <th>Buku</th>
          <th>Peminjam</th>
          <th style="width:140px;">Pinjam</th>
          <th style="width:140px;">Jatuh Tempo</th>
          <th style="width:140px;">Kembali</th>
          <th style="width:120px;">Status</th>
          <th style="width:160px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($loans)): ?>
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              Belum ada data peminjaman untuk filter ini.
            </td>
          </tr>
        <?php endif; ?>

        <?php foreach ($loans as $l): ?>
          <tr>
            <td><?= (int)$l['id'] ?></td>

            <td>
              <div class="fw-semibold"><?= esc($l['book_title'] ?? '-') ?></div>
              <div class="text-muted small"><?= esc($l['book_author'] ?? '') ?></div>
            </td>

            <td>
              <div class="fw-semibold"><?= esc($l['username'] ?? '-') ?></div>
              <div class="text-muted small"><?= esc($l['email'] ?? '-') ?></div>
            </td>

            <td class="small"><?= esc($l['borrowed_at'] ?? '-') ?></td>
            <td class="small"><?= esc($l['due_at'] ?? '-') ?></td>
            <td class="small"><?= esc($l['returned_at'] ?? '-') ?></td>

            <td>
              <?php if (($l['status'] ?? '') === 'borrowed'): ?>
                <span class="badge bg-warning text-dark">borrowed</span>
              <?php else: ?>
                <span class="badge bg-success">returned</span>
              <?php endif; ?>
            </td>

            <td>
              <?php if (($l['status'] ?? '') === 'borrowed'): ?>
                <form method="post" action="/admin/loans/<?= (int)$l['id'] ?>/return"
                      onsubmit="return confirm('Tandai transaksi ini sebagai dikembalikan?');">
                  <?= csrf_field() ?>
                  <button class="btn btn-success btn-sm">Tandai Kembali</button>
                </form>
              <?php else: ?>
                <span class="text-muted small">-</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if (isset($pager) && $pager): ?>
  <div class="mt-3">
    <?= $pager->links() ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
