<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <a href="/" class="btn btn-outline-secondary btn-sm">← Kembali</a>
  <?php if (function_exists('auth') && auth()->loggedIn()): ?>
    <a href="/my-loans" class="btn btn-outline-primary btn-sm">Peminjaman Saya</a>
  <?php endif; ?>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <div class="row g-3">
      <!-- Cover -->
      <div class="col-12 col-md-4 col-lg-3 text-center text-md-start">
        <?php
          $cover = !empty($book['cover'])
            ? '/uploads/covers/' . $book['cover']
            : 'https://via.placeholder.com/420x600?text=No+Cover';
        ?>
        <img src="<?= esc($cover) ?>"
          class="rounded border"
          alt="<?= esc($book['title']) ?>"
          style="width:100%; max-width:260px; height:360px; object-fit:cover; object-position:center;">
      </div>

      <!-- Info -->
      <div class="col-12 col-md-8 col-lg-9">
        <div class="d-flex justify-content-between gap-2">
          <div>
            <h4 class="mb-1"><?= esc($book['title']) ?></h4>
            <div class="text-muted">Penulis: <?= esc($book['author']) ?></div>

            <div class="mt-2 d-flex flex-wrap gap-2">
              <?php if (!empty($book['category'])): ?>
                <span class="badge bg-light text-dark border">
                  <?= esc($book['category']) ?>
                </span>
              <?php endif; ?>

              <?php if (!empty($book['year'])): ?>
                <span class="badge bg-light text-dark border">
                  Tahun: <?= esc($book['year']) ?>
                </span>
              <?php endif; ?>

              <?php if (!empty($book['isbn'])): ?>
                <span class="badge bg-light text-dark border">
                  ISBN: <?= esc($book['isbn']) ?>
                </span>
              <?php endif; ?>
            </div>
          </div>

          <div class="text-end">
            <span class="badge <?= ((int)$book['stock'] > 0 ? 'bg-success' : 'bg-secondary') ?> fs-6">
              Stok: <?= (int)$book['stock'] ?>
            </span>
          </div>
        </div>

        <?php if (!empty($book['description'])): ?>
          <hr class="my-3">
          <div class="text-muted">Deskripsi</div>
          <p class="mb-0"><?= esc($book['description']) ?></p>
        <?php endif; ?>

        <hr class="my-3">

        <!-- Borrow Form -->
        <?php if (function_exists('auth') && auth()->loggedIn()): ?>
          <?php
            helper('dateindo'); // kalau sudah autoload boleh dihapus
            $today = \CodeIgniter\I18n\Time::today();
            $defaultDue = $today->addDays(7);
          ?>

          <div class="p-3 rounded border bg-light">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div class="fw-semibold">Atur Tanggal Peminjaman</div>
              <div class="text-muted small">Format: Hari, dd/mm/yyyy</div>
            </div>

            <form method="post" action="/borrow/<?= (int)$book['id'] ?>">
              <?= csrf_field() ?>

              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <label class="form-label small">Tanggal Peminjaman</label>
                  <input type="date"
                         class="form-control"
                         name="borrow_date"
                         value="<?= $today->format('Y-m-d') ?>">
                  <div class="form-text"><?= format_tanggal_indo($today) ?></div>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label small">Rencana Pengembalian</label>
                  <input type="date"
                         class="form-control"
                         name="due_date"
                         value="<?= $defaultDue->format('Y-m-d') ?>">
                  <div class="form-text"><?= format_tanggal_indo($defaultDue) ?></div>
                </div>

                <div class="col-12 mt-1">
                  <button class="btn btn-primary w-100"
                          <?= ((int)$book['stock'] <= 0 ? 'disabled' : '') ?>>
                    Pinjam Buku
                  </button>

                  <?php if ((int)$book['stock'] <= 0): ?>
                    <div class="text-danger small mt-2">Stok habis. Tidak bisa dipinjam.</div>
                  <?php endif; ?>
                </div>
              </div>
            </form>
          </div>

        <?php else: ?>
          <a class="btn btn-primary" href="/login">Masuk untuk pinjam</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
