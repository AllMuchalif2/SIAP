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
                        <?= date('d') . ' ' . $namaBulan . ' ' . date('Y') ?>
                    </p>
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
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            t.checked = true;
        }
        t.addEventListener('change', function () {
            if (this.checked) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-bs-theme');
                localStorage.setItem('theme', 'light');
            }
        });
    })();

    // Clear & refocus setelah submit
    document.getElementById('formAbsen').addEventListener('submit', function () {
        setTimeout(() => {
            const i = document.getElementById('id_siswa');
            i.value = '';
            i.focus();
        }, 200);
    });
</script>

<!-- html5-qrcode CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    let html5QrCode = null;
    let availableCameras = [];
    let selectedCameraId = null;
    let scanFailCount = 0;
    let hasTriedCompatMode = false;
    let scannerMode = 'standard';
    let fallbackTimer = null;
    let hasSubmittedFromScan = false;
    let fallbackCanvas = null;
    let fallbackCtx = null;
    const btnToggle = document.getElementById('btnToggleScan');
    const scanArea = document.getElementById('qrScanArea');
    const qrReader = document.getElementById('qrReader');
    const scanStatus = document.getElementById('scanStatus');
    const cameraChooserWrap = document.getElementById('cameraChooserWrap');
    const cameraChooser = document.getElementById('cameraChooser');

    function pickBestCamera(cameras) {
        if (!cameras || !cameras.length) return null;
        const backCam = cameras.find(c => /rear|back|environment|belakang/i.test(c.label || ''));
        return (backCam || cameras[0]).id;
    }

    async function loadCameras() {
        availableCameras = await Html5Qrcode.getCameras();
        if (!availableCameras.length) throw new Error('Kamera tidak ditemukan');

        selectedCameraId = selectedCameraId || pickBestCamera(availableCameras);

        if (cameraChooser) {
            cameraChooser.innerHTML = availableCameras
                .map((cam, idx) => {
                    const label = cam.label || `Kamera ${idx + 1}`;
                    const selected = cam.id === selectedCameraId ? 'selected' : '';
                    return `<option value="${cam.id}" ${selected}>${label}</option>`;
                })
                .join('');
        }
        if (cameraChooserWrap) cameraChooserWrap.style.display = availableCameras.length > 1 ? 'block' : 'none';
    }

    async function restartScannerWith(cameraId) {
        selectedCameraId = cameraId;
        if (html5QrCode && html5QrCode.isScanning) {
            try {
                await html5QrCode.stop();
            } catch (e) {
                console.warn('Stop before restart failed:', e);
            }
        }
        await mulaiScanner();
    }

    function startJsQrFallback() {
        stopJsQrFallback();
        fallbackCanvas = document.createElement('canvas');
        fallbackCtx = fallbackCanvas.getContext('2d', { willReadFrequently: true });

        fallbackTimer = setInterval(() => {
            if (hasSubmittedFromScan) return;
            if (!scanArea || scanArea.style.display === 'none') return;

            const video = qrReader.querySelector('video');
            if (!video || video.readyState < 2) return;

            const width = video.videoWidth || 0;
            const height = video.videoHeight || 0;
            if (!width || !height) return;

            fallbackCanvas.width = width;
            fallbackCanvas.height = height;
            fallbackCtx.drawImage(video, 0, 0, width, height);

            const imageData = fallbackCtx.getImageData(0, 0, width, height);
            const result = jsQR(imageData.data, width, height, {
                inversionAttempts: 'attemptBoth'
            });

            if (result && result.data) {
                console.log('QR detected by jsQR fallback:', result.data);
                onScanSuccess(result.data);
            }
        }, 220);
    }

    function stopJsQrFallback() {
        if (fallbackTimer) {
            clearInterval(fallbackTimer);
            fallbackTimer = null;
        }
        fallbackCanvas = null;
        fallbackCtx = null;
    }

    if (cameraChooser) {
        cameraChooser.addEventListener('change', async function () {
            if (!this.value) return;
            if (scanStatus) scanStatus.textContent = 'Mengganti kamera...';
            try {
                await restartScannerWith(this.value);
            } catch (err) {
                console.error('Gagal mengganti kamera:', err);
                if (scanStatus) scanStatus.textContent = 'Gagal mengganti kamera.';
            }
        });
    }

    btnToggle.addEventListener('click', function () {
        console.log("Scanner toggle clicked");
        if (!html5QrCode || !html5QrCode.isScanning) {
            scanArea.style.display = 'block';
            btnToggle.innerHTML = '<i class="bi bi-x-circle me-1"></i> Tutup Scanner';
            btnToggle.classList.replace('btn-outline-secondary', 'btn-outline-danger');
            if (scanStatus) scanStatus.textContent = 'Menyalakan kamera...';

            mulaiScanner();
        } else {
            stopScanner();
        }
    });

    async function mulaiScanner() {
        console.log("Starting scanner...");
        try {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qrReader");
            }
            if (!availableCameras.length) {
                await loadCameras();
            }

            const config = scannerMode === 'standard'
                ? {
                    fps: 10,
                    rememberLastUsedCamera: true,
                    disableFlip: false
                }
                : {
                    fps: 6,
                    qrbox: { width: 280, height: 280 },
                    rememberLastUsedCamera: true,
                    disableFlip: true
                };

            scanFailCount = 0;
            const cameraIdToUse = selectedCameraId || pickBestCamera(availableCameras);
            await html5QrCode.start(
                cameraIdToUse,
                config,
                onScanSuccess,
                onScanFailure
            );
            startJsQrFallback();
            console.log("Scanner started with cameraId:", cameraIdToUse);
            if (scanStatus) {
                scanStatus.textContent = scannerMode === 'standard'
                    ? 'Scanner aktif. Arahkan QR ke dalam frame.'
                    : 'Mode kompatibilitas aktif. Arahkan QR ke dalam frame.';
            }
        } catch (err) {
            console.error("Scanner failed to start:", err);
            alert("Gagal mengakses kamera. Pastikan izin kamera diaktifkan dan gunakan HTTPS/localhost.");
            if (scanStatus) scanStatus.textContent = 'Gagal mengakses kamera.';
            stopScanner();
        }
    }

    function onScanSuccess(decodedText) {
        if (hasSubmittedFromScan) return;
        console.log("QR Code detected:", decodedText);
        const parsedId = extractSiswaId(decodedText);
        if (!parsedId) {
            if (scanStatus) scanStatus.textContent = 'QR terbaca tapi format ID tidak valid.';
            return;
        }
        hasSubmittedFromScan = true;
        document.getElementById('id_siswa').value = parsedId;
        if (scanStatus) scanStatus.textContent = 'QR terdeteksi, mengirim absensi...';
        stopScanner();
        document.getElementById('formAbsen').submit();
    }

    function extractSiswaId(rawText) {
        const text = normalizeRawQrText(rawText);
        if (!text) return '';

        const prefixedMatch = text.match(/(?:^|\s)ABSEN\s*:\s*([^\s]+)/i);
        if (prefixedMatch && prefixedMatch[1]) {
            return sanitizeId(prefixedMatch[1]);
        }

        if (/^https?:\/\//i.test(text)) {
            try {
                const url = new URL(text);
                const params = url.searchParams;
                const idFromUrl = params.get('id_siswa') || params.get('id') || params.get('siswa');
                if (idFromUrl) return sanitizeId(idFromUrl);
                const lastPath = (url.pathname || '').split('/').filter(Boolean).pop() || '';
                if (lastPath) return sanitizeId(lastPath);
            } catch (e) {
                console.warn('QR URL parse gagal:', e);
            }
        }

        const firstLine = text.split(/\r?\n/)[0] || text;
        return sanitizeId(firstLine);
    }

    function normalizeRawQrText(value) {
        return String(value || '')
            .replace(/\u200B|\u200C|\u200D|\uFEFF/g, '')
            .trim();
    }

    function sanitizeId(value) {
        const cleaned = normalizeRawQrText(value);
        if (!cleaned) return '';
        const match = cleaned.match(/[A-Za-z0-9._\/-]{1,20}/);
        if (!match) return '';
        return match[0];
    }

    function onScanFailure(errorMessage) {
        scanFailCount++;

        if (!hasTriedCompatMode && scannerMode === 'standard' && scanFailCount >= 120) {
            hasTriedCompatMode = true;
            scannerMode = 'compat';
            if (scanStatus) scanStatus.textContent = 'QR sulit dibaca, beralih ke mode kompatibilitas...';
            restartScannerWith(selectedCameraId).catch(err => {
                console.error('Gagal masuk mode kompatibilitas:', err);
            });
            return;
        }

        if (scanStatus && scanFailCount % 20 === 0) {
            scanStatus.textContent = 'Mencoba membaca QR...';
        }
        if (scanFailCount % 50 === 0 && errorMessage) {
            console.debug('Scan failure info:', errorMessage);
        }
    }

    async function stopScanner() {
        console.log("Stopping scanner...");
        stopJsQrFallback();
        if (html5QrCode && html5QrCode.isScanning) {
            try {
                await html5QrCode.stop();
                console.log("Scanner stopped.");
            } catch (err) {
                console.warn("Failed to stop scanner cleanly:", err);
            }
        }
        scanArea.style.display = 'none';
        btnToggle.innerHTML = '<i class="bi bi-qr-code-scan me-1"></i> Scan QR Code';
        btnToggle.classList.replace('btn-outline-danger', 'btn-outline-secondary');
        if (scanStatus) scanStatus.textContent = 'Arahkan kamera ke QR Code siswa';
        if (cameraChooserWrap) cameraChooserWrap.style.display = 'none';
        hasTriedCompatMode = false;
        scannerMode = 'standard';
        scanFailCount = 0;
        hasSubmittedFromScan = false;
        qrReader.innerHTML = ""; // Clear any leftover video elements
    }
</script>

<?= view('parts/footer') ?>