<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data User</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <a href="/user/create" class="btn btn-success">
                + Tambah User
            </a>
        </div>
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
                        <?php if (!empty($userList)): ?>
                            <?php foreach ($userList as $key => $row): ?>
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
                                            <a href="<?= base_url('user/reset/' . esc($row['id'])); ?>"
                                                class="btn btn-sm btn-primary swal-confirm" data-title="Reset Password"
                                                data-text="Password user '<?= esc($row['name']) ?>' akan direset ke default."
                                                data-icon="question" data-confirm="Ya, Reset!" data-color="#0d6efd"
                                                data-toggle="tooltip" title="reset password">
                                                <i class="fa fa-key"></i>
                                            </a>
                                            <a href="<?= base_url('user/edit/' . esc($row['id'])); ?>"
                                                class="btn btn-sm btn-success" data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('user/delete/' . esc($row['id'])); ?>"
                                                class="btn btn-sm btn-danger swal-confirm" data-title="Hapus User"
                                                data-text="User '<?= esc($row['name']) ?>' akan dihapus permanen!"
                                                data-icon="warning" data-confirm="Ya, Hapus!" data-color="#d33"
                                                data-toggle="tooltip" title="Hapus">
                                                <i class="fa fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
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