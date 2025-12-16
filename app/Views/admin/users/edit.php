<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<a href="/admin/users" class="btn btn-outline-secondary btn-sm mb-3">← Kembali</a>

<div class="card shadow-sm border-0">
  <div class="card-body">
    <div class="d-flex align-items-center gap-3 mb-3">
      <img src="<?= esc($avatarUrl) ?>" class="rounded-circle border"
           style="width:72px; height:72px; object-fit:cover;">
      <div>
        <div class="fw-semibold fs-5"><?= esc($user['username'] ?? '-') ?></div>
        <div class="text-muted small"><?= esc($user['email'] ?? '-') ?></div>
        <div class="text-muted small">No Anggota: <?= esc($user['member_no'] ?? '-') ?></div>
      </div>
      <div class="ms-auto">
        <span class="badge <?= (($user['role'] ?? 'user')==='admin') ? 'bg-dark' : 'bg-primary' ?>">
          <?= esc($user['role'] ?? 'user') ?>
        </span>
      </div>
    </div>

    <form method="post" action="/admin/users/<?= (int)$user['id'] ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">No Telp</label>
          <input class="form-control" name="phone" value="<?= esc(old('phone') ?? ($user['phone'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Foto Profil (Avatar)</label>
          <input type="file" class="form-control" name="avatar" accept="image/*">
          <div class="form-text">jpg/png/webp max 2MB</div>
        </div>

        <div class="col-12">
          <label class="form-label">Alamat</label>
          <textarea class="form-control" name="address" rows="3"><?= esc(old('address') ?? ($user['address'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="mt-3 d-flex gap-2 justify-content-end">
        <button class="btn btn-primary">Simpan</button>
        <a class="btn btn-outline-secondary" href="/admin/users">Batal</a>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
