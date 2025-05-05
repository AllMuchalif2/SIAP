<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data User</h2>

        <a href="/user/create" class="btn btn-success">
            + Tambah User
        </a>
    </div>
</div>
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
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mt-2" id="table1">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if (!empty($userList)) : ?>
                        <?php foreach ($userList as $key => $row) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['username']; ?></td>
                                <td><?= $row['name']; ?></td>
                                <td class="text-center">
                                    <?php if ($row['role'] == 'admin'): ?>
                                        <span class="badge bg-primary">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Asisten</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url('user/reset/' . esc($row['id'])); ?>" class="btn btn-sm btn-primary" onclick="return confirm('Apakah Anda yakin ingin reset password user ini?');" data-toggle="tooltip" title="reset password">
                                            <i class="fa fa-key"></i>
                                        </a>
                                        <a href="<?= base_url('user/edit/' . esc($row['id'])); ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('user/delete/' . esc($row['id'])); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-toggle="tooltip" title="Hapus">
                                            <i class="fa fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= view('parts/footer'); ?>