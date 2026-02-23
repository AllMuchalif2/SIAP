<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Absensi Siswa</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/siswa">Siswa</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= isset($siswa['nama']) ? esc($siswa['nama']) : 'Detail Absensi' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header text-white <?= ($summary->alpa ?? 0) > 10 ? 'bg-danger' : 'bg-primary'; ?>">
                <h5>Detail Absensi Siswa</h5>
            </div>
            <div class="card-body">
                <?php if ($siswa): ?>
                    <div class="row mb-4 mt-4">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">ID Siswa</th>
                                    <td><?= esc($siswa['id_siswa']); ?></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td><?= esc($siswa['nama']); ?></td>
                                </tr>
                                <tr>
                                    <th>Sekolah</th>
                                    <td><?= esc($siswa['sekolah']); ?></td>
                                </tr>
                                <tr>
                                    <th>Total Absensi</th>
                                    <td><?= $summary->total_records ?? 0; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">

                                <tr>
                                    <th>Total Hadir</th>
                                    <td><?= $summary->hadir ?? 0; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Sakit</th>
                                    <td><?= $summary->sakit ?? 0; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Izin</th>
                                    <td><?= $summary->izin ?? 0; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Alpa</th>
                                    <td><?= $summary->alpa ?? 0; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if (($summary->alpa ?? 0) > 10): ?>
                        <div class="card bg-danger text-center">
                            <p> Siswa ini Alpa lebih dari 10 kali
                            </p>
                        </div>
                    <?php endif; ?>


                    <h5 class="mb-3">Riwayat Absensi</h5>
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Waktu Berangkat</th>
                                    <th>Waktu Pulang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($absensi)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($absensi as $row): ?>
                                        <tr class="<?= getStatusClass($row['keterangan']); ?>">
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                            <td><?= esc($row['keterangan']); ?></td>
                                            <td><?= esc($row['waktu']); ?></td>
                                            <td><?= esc($row['waktu_pulang']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('siswa'); ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        Data siswa tidak ditemukan
                    </div>
                    <a href="<?= base_url('siswa'); ?>" class="btn btn-secondary">Kembali</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    // Helper function for row colors
    function getStatusClass($keterangan)
    {
        switch ($keterangan) {
            case 'Hadir':
                return 'table-success';
            case 'Sakit':
                return 'table-warning';
            case 'Izin':
                return 'table-info';
            case 'Alpa':
                return 'table-danger';
            default:
                return '';
        }
    }
    ?>
    <?= view('parts/footer'); ?>