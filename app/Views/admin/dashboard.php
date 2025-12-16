<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<?php
  // Filter period (default: 7 hari)
  $range = $range ?? (string)(service('request')->getGet('range') ?? '7');
  $rangeLabel = [
    '7'  => '7 Hari Terakhir',
    '30' => '30 Hari Terakhir',
    '90' => '90 Hari Terakhir',
    'all'=> 'Semua Data',
  ][$range] ?? '7 Hari Terakhir';

  // Optional data (fallback aman)
  $newLoansInRange      = (int)($newLoansInRange ?? 0);
  $returnedInRange      = (int)($returnedInRange ?? 0);
  $overdueLoans         = (int)($overdueLoans ?? 0);
  $recentLoans          = $recentLoans ?? [];
  $topBorrowedBooks     = $topBorrowedBooks ?? [];
  $dailyBorrowStats     = $dailyBorrowStats ?? []; // contoh: [['date'=>'2025-12-16','total'=>3], ...]
?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
  <div>
    <h4 class="mb-0">Dashboard Admin</h4>
    <div class="text-muted small">SiPustaka — Layanan Busa Pustaka</div>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <form method="get" class="d-flex gap-2">
      <select name="range" class="form-select form-select-sm" style="max-width:190px;">
        <option value="7"  <?= $range==='7'?'selected':'' ?>>7 Hari Terakhir</option>
        <option value="30" <?= $range==='30'?'selected':'' ?>>30 Hari Terakhir</option>
        <option value="90" <?= $range==='90'?'selected':'' ?>>90 Hari Terakhir</option>
        <option value="all"<?= $range==='all'?'selected':'' ?>>Semua Data</option>
      </select>
      <button class="btn btn-outline-primary btn-sm">Terapkan</button>
    </form>

    <a href="/admin/books" class="btn btn-primary btn-sm">Kelola Buku</a>
    <a href="/admin/loans" class="btn btn-outline-primary btn-sm">Data Peminjaman</a>
  </div>
</div>

<!-- STAT CARDS -->
<div class="row g-3">
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Total Buku</div>
            <div class="fs-3 fw-bold"><?= (int)$totalBooks ?></div>
          </div>
          <div class="badge bg-light text-dark border">📚</div>
        </div>
        <div class="text-muted small mt-2">Jumlah judul buku terdaftar.</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Total Stok</div>
            <div class="fs-3 fw-bold"><?= (int)$totalStock ?></div>
          </div>
          <div class="badge bg-light text-dark border">📦</div>
        </div>
        <div class="text-muted small mt-2">Akumulasi stok seluruh buku.</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Total Pengguna</div>
            <div class="fs-3 fw-bold"><?= (int)$totalUsers ?></div>
          </div>
          <div class="badge bg-light text-dark border">👥</div>
        </div>
        <div class="text-muted small mt-2">Jumlah anggota yang terdaftar.</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Peminjaman Aktif</div>
            <div class="fs-3 fw-bold"><?= (int)$activeLoans ?></div>
          </div>
          <div class="badge bg-warning text-dark">⏳</div>
        </div>
        <div class="text-muted small mt-2">Status: borrowed</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Peminjaman Selesai</div>
            <div class="fs-3 fw-bold"><?= (int)$returnedLoans ?></div>
          </div>
          <div class="badge bg-success">✅</div>
        </div>
        <div class="text-muted small mt-2">Status: returned</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Terlambat</div>
            <div class="fs-3 fw-bold"><?= $overdueLoans ?></div>
          </div>
          <div class="badge bg-danger">⚠️</div>
        </div>
        <div class="text-muted small mt-2">Borrowed melewati due date.</div>
      </div>
    </div>
  </div>
</div>

<!-- PERIOD SUMMARY + MINI CHART -->
<div class="row g-3 mt-1">
  <div class="col-12 col-lg-7">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <h6 class="mb-0">Ringkasan Periode</h6>
            <div class="text-muted small"><?= esc($rangeLabel) ?></div>
          </div>
          <span class="badge bg-light text-dark border">📈</span>
        </div>

        <div class="row g-3">
          <div class="col-6 col-md-4">
            <div class="p-3 rounded border bg-light">
              <div class="text-muted small">Peminjaman Baru</div>
              <div class="fs-4 fw-bold"><?= $newLoansInRange ?></div>
            </div>
          </div>
          <div class="col-6 col-md-4">
            <div class="p-3 rounded border bg-light">
              <div class="text-muted small">Dikembalikan</div>
              <div class="fs-4 fw-bold"><?= $returnedInRange ?></div>
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="p-3 rounded border bg-light">
              <div class="text-muted small">Rasio Selesai</div>
              <?php
                $ratio = ($newLoansInRange > 0) ? round(($returnedInRange / $newLoansInRange) * 100) : 0;
              ?>
              <div class="fs-4 fw-bold"><?= $ratio ?>%</div>
              <div class="progress mt-2" style="height:6px;">
                <div class="progress-bar" role="progressbar" style="width: <?= $ratio ?>%;"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Mini chart (bar sederhana tanpa library) -->
        <div class="mt-3">
          <div class="text-muted small mb-2">Tren Peminjaman Harian</div>

          <?php if (!empty($dailyBorrowStats)): ?>
            <div class="d-flex align-items-end gap-1" style="height:70px;">
              <?php
                $max = 1;
                foreach ($dailyBorrowStats as $d) $max = max($max, (int)$d['total']);
              ?>
              <?php foreach ($dailyBorrowStats as $d): ?>
                <?php
                  $val = (int)$d['total'];
                  $h = (int)round(($val / $max) * 70);
                ?>
                <div class="flex-grow-1">
                  <div class="bg-primary rounded" style="height: <?= $h ?>px;"></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-between text-muted small mt-1">
              <span><?= esc($dailyBorrowStats[0]['date'] ?? '') ?></span>
              <span><?= esc(end($dailyBorrowStats)['date'] ?? '') ?></span>
            </div>
          <?php else: ?>
            <div class="text-muted small">Belum ada data tren untuk periode ini.</div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>

  <!-- TOP BOOKS -->
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
            <h6 class="mb-0">Buku Paling Sering Dipinjam</h6>
            <div class="text-muted small">Berdasarkan periode</div>
          </div>
          <span class="badge bg-light text-dark border">🏆</span>
        </div>

        <?php if (!empty($topBorrowedBooks)): ?>
          <div class="list-group list-group-flush">
            <?php foreach ($topBorrowedBooks as $i => $t): ?>
              <div class="list-group-item px-0 d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-semibold"><?= ($i+1) ?>. <?= esc($t['title'] ?? '-') ?></div>
                  <div class="text-muted small"><?= esc($t['author'] ?? '') ?></div>
                </div>
                <span class="badge bg-primary"><?= (int)($t['total'] ?? 0) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-muted small">Belum ada data buku teratas untuk periode ini.</div>
        <?php endif; ?>

        <div class="mt-3 d-flex gap-2 flex-wrap">
          <a class="btn btn-outline-primary btn-sm" href="/admin/books">Kelola Buku</a>
          <a class="btn btn-outline-secondary btn-sm" href="/admin/loans">Lihat Peminjaman</a>
          <a class="btn btn-outline-dark btn-sm" href="/">Lihat Katalog</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- RECENT LOANS TABLE -->
<div class="card shadow-sm border-0 mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <h6 class="mb-0">Aktivitas Terbaru</h6>
        <div class="text-muted small">Peminjaman terakhir (ringkas)</div>
      </div>
      <a href="/admin/loans" class="btn btn-primary btn-sm">Detail Data</a>
    </div>

    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Peminjam</th>
            <th>Buku</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentLoans)): ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($recentLoans as $i => $r): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <div class="fw-semibold"><?= esc($r['username'] ?? '-') ?></div>
                <div class="text-muted small"><?= esc($r['email'] ?? '') ?></div>
              </td>
              <td>
                <div class="fw-semibold"><?= esc($r['book_title'] ?? '-') ?></div>
                <div class="text-muted small"><?= esc($r['book_author'] ?? '') ?></div>
              </td>
              <td>
                <?php $st = $r['status'] ?? ''; ?>
                <span class="badge <?= $st==='borrowed' ? 'bg-warning text-dark' : 'bg-success' ?>">
                  <?= esc($st) ?>
                </span>
              </td>
              <td class="text-muted small">
                <?= esc($r['borrowed_at'] ?? '-') ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
