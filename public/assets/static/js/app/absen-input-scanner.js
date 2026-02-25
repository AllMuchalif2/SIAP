(() => {
    'use strict';

    const app = window.AbsenInputApp;
    if (!app || !app.elements || !app.elements.formAbsen) {
        return;
    }

    const { elements, showToast, setScanStatus } = app;

    const scannerState = {
        html5QrCode: null,
        availableCameras: [],
        selectedCameraId: null,
        scanFailCount: 0,
        hasTriedCompatMode: false,
        scannerMode: 'standard',
        fallbackTimer: null,
        hasSubmittedFromScan: false,
        fallbackCanvas: null,
        fallbackCtx: null,
    };

    initScanner();

    function initScanner() {
        if (!elements.btnToggleScan || !elements.qrReader || !elements.qrScanArea) return;

        if (elements.cameraChooser) {
            elements.cameraChooser.addEventListener('change', async function () {
                if (!this.value) return;

                setScanStatus('Mengganti kamera...');
                try {
                    await restartScannerWith(this.value);
                } catch (error) {
                    console.error('Gagal mengganti kamera:', error);
                    setScanStatus('Gagal mengganti kamera.');
                }
            });
        }

        elements.btnToggleScan.addEventListener('click', () => {
            if (!scannerState.html5QrCode || !scannerState.html5QrCode.isScanning) {
                openScanner();
                return;
            }

            stopScanner();
        });
    }

    function openScanner() {
        elements.qrScanArea.style.display = 'block';
        elements.btnToggleScan.innerHTML = '<i class="bi bi-x-circle me-1"></i> Tutup Scanner';
        elements.btnToggleScan.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        setScanStatus('Menyalakan kamera...');
        startScanner();
    }

    async function startScanner() {
        try {
            if (!window.Html5Qrcode) {
                showToast('error', 'Library QR scanner belum termuat.');
                return;
            }

            if (!scannerState.html5QrCode) {
                scannerState.html5QrCode = new Html5Qrcode('qrReader');
            }

            if (!scannerState.availableCameras.length) {
                await loadCameras();
            }

            const configScanner = scannerState.scannerMode === 'standard'
                ? {
                    fps: 10,
                    rememberLastUsedCamera: true,
                    disableFlip: false,
                }
                : {
                    fps: 6,
                    qrbox: { width: 280, height: 280 },
                    rememberLastUsedCamera: true,
                    disableFlip: true,
                };

            scannerState.scanFailCount = 0;
            const cameraId = scannerState.selectedCameraId || pickBestCamera(scannerState.availableCameras);

            await scannerState.html5QrCode.start(
                cameraId,
                configScanner,
                onScanSuccess,
                onScanFailure
            );

            startJsQrFallback();
            setScanStatus(
                scannerState.scannerMode === 'standard'
                    ? 'Scanner aktif. Arahkan QR ke dalam frame.'
                    : 'Mode kompatibilitas aktif. Arahkan QR ke dalam frame.'
            );
        } catch (error) {
            console.error('Scanner gagal dijalankan:', error);
            showToast('error', 'Gagal mengakses kamera. Pastikan izin kamera aktif dan gunakan HTTPS/localhost.');
            stopScanner();
        }
    }

    async function stopScanner() {
        stopJsQrFallback();

        if (scannerState.html5QrCode && scannerState.html5QrCode.isScanning) {
            try {
                await scannerState.html5QrCode.stop();
            } catch (error) {
                console.warn('Gagal menghentikan scanner:', error);
            }
        }

        elements.qrScanArea.style.display = 'none';
        elements.btnToggleScan.innerHTML = '<i class="bi bi-qr-code-scan me-1"></i> Scan QR Code';
        elements.btnToggleScan.classList.replace('btn-outline-danger', 'btn-outline-secondary');

        if (elements.cameraChooserWrap) {
            elements.cameraChooserWrap.style.display = 'none';
        }

        setScanStatus('Arahkan kamera ke QR Code siswa');
        scannerState.hasTriedCompatMode = false;
        scannerState.scannerMode = 'standard';
        scannerState.scanFailCount = 0;
        scannerState.hasSubmittedFromScan = false;
        elements.qrReader.innerHTML = '';
    }

    async function loadCameras() {
        scannerState.availableCameras = await Html5Qrcode.getCameras();

        if (!scannerState.availableCameras.length) {
            throw new Error('Kamera tidak ditemukan');
        }

        scannerState.selectedCameraId = scannerState.selectedCameraId || pickBestCamera(scannerState.availableCameras);

        if (elements.cameraChooser) {
            elements.cameraChooser.innerHTML = scannerState.availableCameras
                .map((camera, index) => {
                    const label = camera.label || `Kamera ${index + 1}`;
                    const selected = camera.id === scannerState.selectedCameraId ? 'selected' : '';
                    return `<option value="${camera.id}" ${selected}>${label}</option>`;
                })
                .join('');
        }

        if (elements.cameraChooserWrap) {
            elements.cameraChooserWrap.style.display = scannerState.availableCameras.length > 1 ? 'block' : 'none';
        }
    }

    async function restartScannerWith(cameraId) {
        scannerState.selectedCameraId = cameraId;

        if (scannerState.html5QrCode && scannerState.html5QrCode.isScanning) {
            try {
                await scannerState.html5QrCode.stop();
            } catch (error) {
                console.warn('Stop sebelum restart gagal:', error);
            }
        }

        await startScanner();
    }

    function pickBestCamera(cameras) {
        if (!cameras || !cameras.length) return null;

        const backCamera = cameras.find((camera) => /rear|back|environment|belakang/i.test(camera.label || ''));
        return (backCamera || cameras[0]).id;
    }

    function onScanSuccess(decodedText) {
        if (scannerState.hasSubmittedFromScan) return;

        const parsedId = extractSiswaId(decodedText);
        if (!parsedId) {
            setScanStatus('QR terbaca tapi format ID tidak valid.');
            return;
        }

        scannerState.hasSubmittedFromScan = true;
        elements.inputIdSiswa.value = parsedId;
        setScanStatus('QR terdeteksi, mengirim absensi...');
        stopScanner();

        if (typeof elements.formAbsen.requestSubmit === 'function') {
            elements.formAbsen.requestSubmit();
        } else {
            elements.formAbsen.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
    }

    function onScanFailure(errorMessage) {
        scannerState.scanFailCount += 1;

        if (!scannerState.hasTriedCompatMode && scannerState.scannerMode === 'standard' && scannerState.scanFailCount >= 120) {
            scannerState.hasTriedCompatMode = true;
            scannerState.scannerMode = 'compat';
            setScanStatus('QR sulit dibaca, beralih ke mode kompatibilitas...');

            restartScannerWith(scannerState.selectedCameraId).catch((error) => {
                console.error('Gagal masuk mode kompatibilitas:', error);
            });
            return;
        }

        if (scannerState.scanFailCount % 20 === 0) {
            setScanStatus('Mencoba membaca QR...');
        }

        if (scannerState.scanFailCount % 50 === 0 && errorMessage) {
            console.debug('Scan failure info:', errorMessage);
        }
    }

    function startJsQrFallback() {
        if (!window.jsQR) {
            return;
        }

        stopJsQrFallback();
        scannerState.fallbackCanvas = document.createElement('canvas');
        scannerState.fallbackCtx = scannerState.fallbackCanvas.getContext('2d', { willReadFrequently: true });

        scannerState.fallbackTimer = setInterval(() => {
            if (scannerState.hasSubmittedFromScan) return;
            if (elements.qrScanArea.style.display === 'none') return;

            const video = elements.qrReader.querySelector('video');
            if (!video || video.readyState < 2) return;

            const width = video.videoWidth || 0;
            const height = video.videoHeight || 0;
            if (!width || !height) return;

            scannerState.fallbackCanvas.width = width;
            scannerState.fallbackCanvas.height = height;
            scannerState.fallbackCtx.drawImage(video, 0, 0, width, height);

            const imageData = scannerState.fallbackCtx.getImageData(0, 0, width, height);
            const result = jsQR(imageData.data, width, height, { inversionAttempts: 'attemptBoth' });

            if (result?.data) {
                onScanSuccess(result.data);
            }
        }, 220);
    }

    function stopJsQrFallback() {
        if (scannerState.fallbackTimer) {
            clearInterval(scannerState.fallbackTimer);
            scannerState.fallbackTimer = null;
        }

        scannerState.fallbackCanvas = null;
        scannerState.fallbackCtx = null;
    }

    function extractSiswaId(rawText) {
        const text = normalizeRawQrText(rawText);
        if (!text) return '';

        const prefixedMatch = text.match(/(?:^|\s)ABSEN\s*:\s*([^\s]+)/i);
        if (prefixedMatch?.[1]) {
            return sanitizeId(prefixedMatch[1]);
        }

        if (/^https?:\/\//i.test(text)) {
            try {
                const url = new URL(text);
                const params = url.searchParams;
                const fromQuery = params.get('id_siswa') || params.get('id') || params.get('siswa');
                if (fromQuery) {
                    return sanitizeId(fromQuery);
                }

                const lastPathSegment = (url.pathname || '').split('/').filter(Boolean).pop() || '';
                if (lastPathSegment) {
                    return sanitizeId(lastPathSegment);
                }
            } catch (error) {
                console.warn('QR URL parse gagal:', error);
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
        return match ? match[0] : '';
    }
})();
