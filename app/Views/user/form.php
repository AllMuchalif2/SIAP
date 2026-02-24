<?= view('parts/header') ?>
<?= view('parts/sidebar') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= isset($user) ? 'Edit' : 'Tambah' ?> User</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/user">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= isset($user) ? 'Edit' : 'Tambah' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="page-content">



    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= isset($user) ? 'Edit' : 'Tambah' ?> User</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= isset($user) ? '/user/update/' . $user['id'] : '/user/save' ?>">
                <?= csrf_field() ?>

                <br>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        value="<?= old('username', $user['username'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?= old('name', $user['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Jabatan</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="admin" <?= old('role', $user['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin
                        </option>
                        <option value="asisten" <?= old('role', $user['role'] ?? '') == 'asisten' ? 'selected' : '' ?>>
                            Asisten</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary"><?= isset($user) ? 'Update' : 'Simpan' ?></button>
            </form>
        </div>
    </div>
</div>
</div>

<?= view('parts/footer') ?>