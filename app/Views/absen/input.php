<?= view('parts/header'); ?>

<?php
$hariArr = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];
$bulanArr = [
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
$namaHari = $hariArr[date('l')];
$namaBulan = $bulanArr[date('F')];
$jmlBerangkat = count(array_filter($absensi ?? [], fn($a) => !empty($a['waktu'])));
$jmlPulang = count(array_filter($absensi ?? [], fn($a) => !empty($a['waktu_pulang'])));
?>

<div class="container py-4">

    <!-- Flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">

        <!-- KOLOM KIRI -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body text-center py-4">

                    <!-- Tanggal & Jam -->
                    <p class="text-muted mb-1 small"><?= $namaHari ?>,
                        <?= date('d') . ' ' . $namaBulan . ' ' . date('Y') ?></p>
                    <h2 class="fw-bold mb-3" id="liveClock">00:00:00</h2>

                    <!-- Toggle tema -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                        <i class="bi bi-sun-fill text-warning"></i>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="toggle-dark" role="switch">
                        </div>
                        <i class="bi bi-moon-stars-fill text-secondary"></i>
                    </div>

                    <!-- Form -->
                    <form action="<?= site_url('absen/absen') ?>" method="post" id="formAbsen">
                        <?= csrf_field() ?>
                        <input type="text" name="id_siswa" id="id_siswa" class="form-control text-center mb-2"
                            placeholder="Masukkan ID Siswa" autocomplete="off" required autofocus>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Absen
                        </button>
                    </form>

                    <!-- Mini stat -->
                    <div class="row g-2 mt-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="fw-bold text-success fs-5"><?= $jmlBerangkat ?></div>
                                <small class="text-muted">Berangkat</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="fw-bold text-danger fs-5"><?= $jmlPulang ?></div>
                                <small class="text-muted">Pulang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN -->
        <div class="col-12 col-lg-8">

            <!-- Tabel Berangkat -->
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-box-arrow-in-right text-success me-2"></i>
                    <span class="fw-semibold">Daftar Berangkat</span>
                    <span class="badge bg-success ms-auto"><?= $jmlBerangkat ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Sekolah</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $ada = false; ?>
                            <?php foreach ($absensi ?? [] as $item): ?>
                                <?php if (!empty($item['waktu'])):
                                    $ada = true; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($item['nama']) ?></td>
                                        <td class="text-muted small"><?= esc($item['sekolah']) ?></td>
                                        <td><span class="badge bg-success"><?= esc($item['waktu']) ?></span></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (!$ada): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada yang berangkat</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Pulang -->
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-box-arrow-right text-danger me-2"></i>
                    <span class="fw-semibold">Daftar Pulang</span>
                    <span class="badge bg-danger ms-auto"><?= $jmlPulang ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Sekolah</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $ada = false; ?>
                            <?php foreach ($absensi ?? [] as $item): ?>
                                <?php if (!empty($item['waktu_pulang'])):
                                    $ada = true; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($item['nama']) ?></td>
                                        <td class="text-muted small"><?= esc($item['sekolah']) ?></td>
                                        <td><span class="badge bg-danger"><?= esc($item['waktu_pulang']) ?></span></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (!$ada): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada yang pulang</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Jam live
    function tick() {
        const n = new Date(), p = x => String(x).padStart(2, '0');
        document.getElementById('liveClock').textContent = p(n.getHours()) + ':' + p(n.getMinutes()) + ':' + p(n.getSeconds());
    }
    tick(); setInterval(tick, 1000);

    // Dark mode
    (function () {
        const t = document.getElementById('toggle-dark');
        if (!t) return;
        if (localStorage.getItem('theme') === 'dark') { document.documentElement.setAttribute('data-bs-theme', 'dark'); t.checked = true; }
        t.addEventListener('change', function () {
            if (this.checked) { document.documentElement.setAttribute('data-bs-theme', 'dark'); localStorage.setItem('theme', 'dark'); }
            else { document.documentElement.removeAttribute('data-bs-theme'); localStorage.setItem('theme', 'light'); }
        });
    })();

    // Clear setelah submit
    document.getElementById('formAbsen').addEventListener('submit', function () {
        setTimeout(() => { const i = document.getElementById('id_siswa'); i.value = ''; i.focus(); }, 200);
    });
</script>

<?= view('parts/footer') ?>