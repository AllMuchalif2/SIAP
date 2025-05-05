<?= view('parts/header') ?>
<?= view('parts/sidebar') ?>

<div class="container mt-4">

    <div class="mb-3">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= isset($siswa) ? 'Edit' : 'Tambah' ?> Siswa</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= isset($siswa) ? '/siswa/update/' . $siswa['id_siswa'] : '/siswa/save' ?>">
                <?= csrf_field() ?>

                <br>
                <div class="mb-3">
                    <label for="id_siswa" class="form-label">ID Siswa</label>
                    <input type="text" id="id_siswa" name="id_siswa" class="form-control" value="<?= old('id_siswa', $siswa['id_siswa'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Siswa</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= old('nama', $siswa['nama'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="sekolah" class="form-label">Asal Sekolah</label>
                    <input type="text" id="sekolah" name="sekolah" class="form-control" value="<?= old('sekolah', $siswa['sekolah'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="active" <?= old('status', $siswa['status'] ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= old('status', $siswa['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('siswa') ?>" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary"><?= isset($siswa) ? 'Update' : 'Simpan' ?></button>
            </form>
        </div>
    </div>
</div>

<?= view('parts/footer') ?>