<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Data Siswa</h2>
        <?php if (session('role') === 'admin') : ?>

            <a href="/siswa/create" class="btn btn-success">
                + Tambah Siswa
            </a>
        <?php endif; ?>
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
                        <th>ID Siswa</th>
                        <th>Nama</th>
                        <th>Sekolah</th>
                        <th>Absen (%)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php if (!empty($siswaList)) : ?>
                        <?php foreach ($siswaList as $key => $row) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['id_siswa']; ?></td>
                                <td><?= $row['nama']; ?></td>
                                <td><?= $row['sekolah']; ?></td>
                                <td>
                                    <?php
                                    $val = min($row['attendance_percentage'], 100);
                                    echo number_format($val, 2);
                                    ?>%
                                </td>
                                <!-- <td><?= $row['attendance_percentage'] ?> %</td> -->
                                <td class="text-center">
                                    <?php if ($row['status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= base_url('siswa/absensi/' . esc($row['id_siswa'])); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Lihat">
                                            <i class="fa fa-calendar-check"></i>
                                        </a>
                                        <?php if (session('role') === 'admin') : ?>
                                            <a href="<?= base_url('siswa/edit/' . esc($row['id_siswa'])); ?>" class="btn btn-sm btn-success" data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('siswa/delete/' . esc($row['id_siswa'])); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" data-toggle="tooltip" title="Hapus">
                                                <i class="fa fa-trash-alt"></i>
                                            </a>
                                        <?php endif; ?>
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