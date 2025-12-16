<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<h4 class="mb-3">Profil Saya</h4>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body text-center">
        <?php $avatar = !empty($row['avatar']) ? '/uploads/avatars/'.$row['avatar'] : 'https://via.placeholder.com/160'; ?>
        <img src="<?= esc($avatar) ?>" class="rounded-circle mb-3" width="140" height="140" style="object-fit:cover;">
        <div class="fw-semibold"><?= esc(auth()->user()->username) ?></div>
        <div class="text-muted small">No Anggota: <?= esc($row['member_no'] ?? '-') ?></div>

        <hr>

        <form method="post" action="/profile/avatar" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <input type="file" name="avatar" class="form-control form-control-sm mb-2" accept="image/*" required>
          <button class="btn btn-primary btn-sm w-100">Upload Foto</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-body">
        <form method="post" action="/profile">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label">No. Telp</label>
            <input class="form-control" name="phone" value="<?= esc($row['phone'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="address" rows="4"><?= esc($row['address'] ?? '') ?></textarea>
          </div>

          <button class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
