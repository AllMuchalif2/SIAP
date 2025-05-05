<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <h3>Dashboard</h3>
</div>
<div class="page-content">
    <section class="row ">
        <div class="col-12 col-lg-9">
            <div class="row">

                <!-- Card Siswa Belum Hadir -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-x-circle-fill" style="font-size: 2rem; color: #dc3545;"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Belum Hadir</h6>
                                    <h6 class="font-extrabold mb-0"><?= $count_alpa ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Siswa Hadir -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-check-circle-fill" style="font-size: 2rem; color: #28a745;"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Siswa Hadir</h6>
                                    <h6 class="font-extrabold mb-0"><?= $count_hadir ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Siswa Ijin -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-envelope-fill" style="font-size: 2rem; color: #28a745;"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Siswa Ijin</h6>
                                    <h6 class="font-extrabold mb-0"><?= $count_izin ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Siswa Sakit -->
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-clipboard2-pulse-fill" style="font-size: 2rem; color: #28a745;"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Siswa Sakit</h6>
                                    <h6 class="font-extrabold mb-0"><?= $count_sakit ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4-5 px-4">
                    <div class="d-flex align-items-center justify-content-between flex-column ">
                        <?php
                        $hari = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        ];

                        $bulan = [
                            'January' => 'Januari',
                            'February' => 'Februari',
                            'March' => 'Maret',
                            'April' => 'April',
                            'May' => 'Mei',
                            'June' => 'Juni',
                            'July' => 'Juli',
                            'August' => 'Agustus',
                            'September' => 'September',
                            'October' => 'Oktober',
                            'November' => 'November',
                            'December' => 'Desember'
                        ];

                        $hariIni = date('l');
                        $bulanIni = date('F');
                        ?>

                        <h5 class="font-bold"><?= $hari[$hariIni] ?></h5>
                        <h6 class="text-muted"><?= date('d') . ' ' . $bulan[$bulanIni] . ' ' . date('Y') ?></h6>

                        <?php if (session('role') === 'admin') : ?>
                            <form action="<?= base_url('input') ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="bi bi-plus-circle"></i> Input Absensi Hari Ini
                                </button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <!-- app/Views/dashboard.php -->

        </div>


        <div class="col-12">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Absensi hari ini</h4>
                </div>
                <div class="card-body">

                    <div>
                        <table class="table table-striped" id="table1">
                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Sekolah</th>
                                    <th>Waktu Absen</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($absensi)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($absensi as $item): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= esc($item['nama']); ?></td>
                                            <td><?= esc($item['sekolah']); ?></td>
                                            <td><?= esc($item['waktu']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data absensi untuk hari ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>

<script>
    document.querySelector('form[action="<?= base_url('input') ?>"]').addEventListener('submit', function(e) {
        if (!confirm('Yakin ingin menginput absensi untuk semua siswa hari ini?')) {
            e.preventDefault();
        }
    });
</script>





<?= view('parts/footer'); ?>