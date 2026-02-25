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

    <div class="row g-3">

        <!-- KOLOM KIRI -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body text-center py-4">

                    <!-- Tanggal & Jam -->
                    <p class="text-muted mb-1 small"><?= $namaHari ?>,
                        <?= date('d') . ' ' . $namaBulan . ' ' . date('Y') ?>
                    </p>
                    <h2 class="fw-bold mb-3" id="liveClock">00:00:00</h2>

                    <!-- Toggle tema -->
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                            <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                    opacity=".3"></path>
                                <g transform="translate(-210 -1)">
                                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                    <circle cx="220.5" cy="11.5" r="4"></circle>
                                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="toggle-dark" role="switch">
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                            preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                            </path>
                        </svg>
                    </div>

                    <!-- Form -->
                    <form action="<?= site_url('absen/absen') ?>" method="post" id="formAbsen">
                        <?= csrf_field() ?>
                        <input type="text" name="id_siswa" id="id_siswa" class="form-control text-center mb-2"
                            placeholder="Masukkan ID Siswa" autocomplete="off" required autofocus>
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Absen
                        </button>
                    </form>

                    <!-- Tombol Scan QR -->
                    <button class="btn btn-outline-secondary w-100" id="btnToggleScan">
                        <i class="bi bi-qr-code-scan me-1"></i> Scan QR Code
                    </button>
                    <div id="qrScanArea" class="mt-3" style="display:none;">
                        <div class="mb-2" id="cameraChooserWrap" style="display:none;">
                            <select id="cameraChooser" class="form-select form-select-sm"></select>
                        </div>
                        <div id="qrReader" style="width:100%; border-radius: 8px; overflow: hidden; background: #eee;">
                        </div>
                        <p id="scanStatus" class="text-muted small mt-2 mb-0 text-center">Arahkan kamera ke QR Code
                            siswa</p>
                    </div>

                    <!-- Mini stat -->
                    <div class="row g-2 mt-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="fw-bold text-success fs-5" id="countBerangkatMini"><?= $jmlBerangkat ?>
                                </div>
                                <small class="text-muted">Berangkat</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <div class="fw-bold text-danger fs-5" id="countPulangMini"><?= $jmlPulang ?></div>
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
                    <span class="fw-semibold">Daftar Berangkat</span>
                    <span class="badge bg-success ms-auto" id="countBerangkatBadge"><?= $jmlBerangkat ?></span>
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
                        <tbody id="tbodyBerangkat">
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
                    <span class="fw-semibold">Daftar Pulang</span>
                    <span class="badge bg-danger ms-auto" id="countPulangBadge"><?= $jmlPulang ?></span>
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
                        <tbody id="tbodyPulang">
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

<!-- html5-qrcode CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    window.AbsenInputConfig = {
        rootUrl: '<?= site_url('/') ?>',
        refreshInterval: 10000
    };
</script>
<script src="<?= base_url('assets/static/js/app/absen-input-core.js') ?>"></script>
<script src="<?= base_url('assets/static/js/app/absen-input-scanner.js') ?>"></script>

<?= view('parts/footer') ?>