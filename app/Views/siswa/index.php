<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Siswa</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Siswa</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <?php if (session('role') === 'admin'): ?>

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
                        <?php if (!empty($siswaList)): ?>
                            <?php foreach ($siswaList as $key => $row): ?>
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
                                            <a href="<?= base_url('siswa/absensi/' . esc($row['id_siswa'])); ?>"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip" title="Lihat">
                                                <i class="fa fa-calendar-check"></i>
                                            </a>
                                            <?php if (session('role') === 'admin'): ?>
                                                <a href="<?= base_url('siswa/edit/' . esc($row['id_siswa'])); ?>"
                                                    class="btn btn-sm btn-success" data-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-siswa"
                                                    data-id="<?= esc($row['id_siswa']) ?>" data-nama="<?= esc($row['nama']) ?>"
                                                    data-absen="<?= (int) ($row['absen_count'] ?? 0) ?>"
                                                    data-url="<?= base_url('siswa/delete/' . esc($row['id_siswa'])) ?>"
                                                    data-toggle="tooltip" title="Hapus">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-hapus-siswa').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const nama = this.dataset.nama;
                    const absenCount = parseInt(this.dataset.absen) || 0;
                    const url = this.dataset.url;

                    if (absenCount > 0) {
                        // Step 1 — peringatan ada data absen
                        Swal.fire({
                            title: 'Siswa Memiliki Data Absen!',
                            html: `Siswa <strong>${nama}</strong> memiliki <strong>${absenCount} data absensi</strong>.<br>
                           Jika dihapus, seluruh data absensinya juga akan ikut dihapus.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Lanjutkan, Hapus Semua!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Step 2 — konfirmasi terakhir
                                Swal.fire({
                                    title: 'Yakin?',
                                    text: `Siswa "${nama}" dan ${absenCount} data absensinya akan dihapus permanen!`,
                                    icon: 'error',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#6c757d',
                                    confirmButtonText: 'Ya, Hapus Sekarang!',
                                    cancelButtonText: 'Batal',
                                    reverseButtons: true
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        window.location.href = url + '?force=1';
                                    }
                                });
                            }
                        });
                    } else {
                        // Tidak ada absen — konfirmasi biasa
                        Swal.fire({
                            title: 'Hapus Siswa',
                            text: `Data siswa "${nama}" akan dihapus!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    }
                });
            });
        });
    </script>

    <?= view('parts/footer'); ?>