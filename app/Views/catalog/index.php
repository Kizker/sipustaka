<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h4 class="mb-0">Katalog Buku</h4>
    <div class="text-muted small">Cari dan pinjam buku dari HP dengan cepat.</div>
  </div>
</div>

<?php if (function_exists('auth') && auth()->loggedIn()): ?>
  <div class="alert alert-light border d-flex align-items-start gap-2 py-2">
    <div class="small text-muted mb-0">
      Tips: klik <b>Detail</b> untuk melihat cover, deskripsi, serta mengatur tanggal peminjaman & pengembalian.
    </div>
  </div>
<?php endif; ?>

<div class="row g-3">
  <?php foreach ($books as $b): ?>
    <?php
      $coverFile = $b['cover'] ?? '';
      $coverPath = FCPATH . 'uploads/covers/' . $coverFile;
      $coverUrl  = (!empty($coverFile) && is_file($coverPath))
        ? '/uploads/covers/' . $coverFile
        : 'https://via.placeholder.com/240x320?text=No+Cover';

      $stock = (int)($b['stock'] ?? 0);
    ?>

    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm border-0">
        <div class="card-body">
          <div class="d-flex gap-3">
            <!-- Cover -->
            <div class="rounded overflow-hidden border bg-light" style="width:72px; height:96px; flex:0 0 auto;">
              <img
                src="<?= esc($coverUrl) ?>"
                alt="<?= esc($b['title']) ?>"
                style="width:100%; height:100%; object-fit:cover; object-position:center; display:block;"
              >
            </div>

            <!-- Info -->
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-start gap-2">
                <div>
                  <div class="fw-semibold" style="line-height:1.2;">
                    <?= esc($b['title']) ?>
                  </div>
                  <div class="text-muted small mt-1"><?= esc($b['author']) ?></div>
                </div>

                <span class="badge <?= ($stock > 0 ? 'bg-success' : 'bg-secondary') ?>">
                  <?= ($stock > 0) ? 'Stok: '.$stock : 'Habis' ?>
                </span>
              </div>

              <div class="d-flex flex-wrap gap-2 mt-2">
                <?php if (!empty($b['category'])): ?>
                  <span class="badge bg-light text-dark border"><?= esc($b['category']) ?></span>
                <?php endif; ?>

                <?php if (!empty($b['year'])): ?>
                  <span class="badge bg-light text-dark border"><?= esc($b['year']) ?></span>
                <?php endif; ?>

                <?php if (!empty($b['isbn'])): ?>
                  <span class="badge bg-light text-dark border">ISBN</span>
                <?php endif; ?>
              </div>

              <div class="mt-2 d-flex align-items-center justify-content-between">
                <div class="text-muted small">
                  <?= ($stock > 0) ? 'Siap dipinjam' : 'Tidak tersedia' ?>
                </div>

                <!-- Satu tombol detail saja -->
                <a class="btn btn-primary btn-sm" href="/books/<?= (int)$b['id'] ?>">
                  Detail
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Accent kecil biar lebih menarik -->
        <div class="card-footer bg-white border-0 pt-0">
          <div class="progress" style="height:4px;">
            <div class="progress-bar" role="progressbar"
                 style="width: <?= min(100, max(8, $stock * 12)) ?>%;"
                 aria-valuenow="<?= $stock ?>" aria-valuemin="0" aria-valuemax="10"></div>
          </div>
          <div class="text-muted small mt-2">
            <?= ($stock > 0) ? 'Atur tanggal peminjaman di halaman detail.' : 'Coba lagi nanti atau pilih buku lain.' ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if (isset($pager) && $pager): ?>
  <div class="mt-3">
    <?= $pager->links() ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
