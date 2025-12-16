<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
  <div>
    <h4 class="mb-0">Kelola Pengguna</h4>
    <div class="text-muted small">Manajemen anggota/user SiPustaka</div>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <a href="/admin" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="text-muted small">Total Pengguna</div>
        <div class="fs-3 fw-bold"><?= (int)$totalUsers ?></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="text-muted small">Pengguna Aktif</div>
        <div class="fs-3 fw-bold"><?= (int)$activeUsers ?></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="text-muted small">Admin</div>
        <div class="fs-3 fw-bold"><?= (int)$adminUsers ?></div>
      </div>
    </div>
  </div>
</div>

<form class="card shadow-sm border-0 mb-3" method="get" action="/admin/users">
  <div class="card-body">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-5">
        <label class="form-label small">Cari</label>
        <input class="form-control" name="q" value="<?= esc($q) ?>" placeholder="Username / No Anggota / Email / No Telp">
      </div>

      <div class="col-6 col-md-3">
        <label class="form-label small">Role</label>
        <select class="form-select" name="role">
          <option value="all" <?= $role==='all'?'selected':'' ?>>Semua</option>
          <option value="admin" <?= $role==='admin'?'selected':'' ?>>Admin</option>
          <option value="user" <?= $role==='user'?'selected':'' ?>>User</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <label class="form-label small">Status</label>
        <select class="form-select" name="active">
          <option value="all" <?= $active==='all'?'selected':'' ?>>Semua</option>
          <option value="1" <?= $active==='1'?'selected':'' ?>>Aktif</option>
          <option value="0" <?= $active==='0'?'selected':'' ?>>Nonaktif</option>
        </select>
      </div>

      <div class="col-12 col-md-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Filter</button>
        <a class="btn btn-outline-secondary w-100" href="/admin/users">Reset</a>
      </div>
    </div>
  </div>
</form>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:60px;">#</th>
          <th>Pengguna</th>
          <th>No Anggota</th>
          <th>No Telp</th>
          <th>Role</th>
          <th>Status</th>
          <th style="width:240px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pengguna.</td></tr>
        <?php endif; ?>

        <?php foreach ($users as $i => $u): ?>
          <?php
            $avatar = (!empty($u['avatar']) && is_file(FCPATH.'uploads/avatars/'.$u['avatar']))
              ? '/uploads/avatars/'.$u['avatar']
              : 'https://via.placeholder.com/60?text=User';
          ?>
          <tr>
            <td><?= $i+1 ?></td>

            <td>
              <div class="d-flex gap-2 align-items-center">
                <img src="<?= esc($avatar) ?>" class="rounded-circle border"
                     style="width:42px; height:42px; object-fit:cover;">
                <div>
                  <div class="fw-semibold"><?= esc($u['username'] ?? '-') ?></div>
                  <div class="text-muted small"><?= esc($u['email'] ?? '-') ?></div>
                </div>
              </div>
            </td>

            <td class="text-muted"><?= esc($u['member_no'] ?? '-') ?></td>
            <td class="text-muted"><?= esc($u['phone'] ?? '-') ?></td>

            <td>
              <span class="badge <?= (($u['role'] ?? 'user')==='admin') ? 'bg-dark' : 'bg-primary' ?>">
                <?= esc($u['role'] ?? 'user') ?>
              </span>
            </td>

            <td>
              <span class="badge <?= ((int)($u['active'] ?? 0)===1) ? 'bg-success' : 'bg-secondary' ?>">
                <?= ((int)($u['active'] ?? 0)===1) ? 'Aktif' : 'Nonaktif' ?>
              </span>
            </td>

            <td class="d-flex flex-wrap gap-2">
              <a class="btn btn-outline-primary btn-sm" href="/admin/users/<?= (int)$u['id'] ?>/edit">Edit</a>

              <form method="post" action="/admin/users/<?= (int)$u['id'] ?>/toggle">
                <?= csrf_field() ?>
                <button class="btn btn-outline-warning btn-sm" type="submit">
                  <?= ((int)($u['active'] ?? 0)===1) ? 'Nonaktifkan' : 'Aktifkan' ?>
                </button>
              </form>

              <form method="post" action="/admin/users/<?= (int)$u['id'] ?>/role">
                <?= csrf_field() ?>
                <input type="hidden" name="role" value="<?= (($u['role'] ?? 'user')==='admin') ? 'user' : 'admin' ?>">
                <button class="btn btn-outline-dark btn-sm" type="submit">
                  Jadikan <?= (($u['role'] ?? 'user')==='admin') ? 'User' : 'Admin' ?>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>
