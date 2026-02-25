(() => {
    'use strict';

    const config = {
        rootUrl: window.AbsenInputConfig?.rootUrl || '/',
        refreshInterval: Number(window.AbsenInputConfig?.refreshInterval || 10000),
    };

    const elements = {
        liveClock: document.getElementById('liveClock'),
        toggleDark: document.getElementById('toggle-dark'),
        formAbsen: document.getElementById('formAbsen'),
        inputIdSiswa: document.getElementById('id_siswa'),
        countBerangkatMini: document.getElementById('countBerangkatMini'),
        countPulangMini: document.getElementById('countPulangMini'),
        countBerangkatBadge: document.getElementById('countBerangkatBadge'),
        countPulangBadge: document.getElementById('countPulangBadge'),
        tbodyBerangkat: document.getElementById('tbodyBerangkat'),
        tbodyPulang: document.getElementById('tbodyPulang'),
        btnToggleScan: document.getElementById('btnToggleScan'),
        qrScanArea: document.getElementById('qrScanArea'),
        qrReader: document.getElementById('qrReader'),
        scanStatus: document.getElementById('scanStatus'),
        cameraChooserWrap: document.getElementById('cameraChooserWrap'),
        cameraChooser: document.getElementById('cameraChooser'),
    };

    if (!elements.formAbsen) {
        return;
    }

    function initCore() {
        initClock();
        initThemeToggle();
        initAjaxForm();
        setInterval(refreshAbsensiData, config.refreshInterval);
    }

    function initClock() {
        if (!elements.liveClock) return;

        const tick = () => {
            const now = new Date();
            const pad = (value) => String(value).padStart(2, '0');
            elements.liveClock.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        };

        tick();
        setInterval(tick, 1000);
    }

    function initThemeToggle() {
        if (!elements.toggleDark) return;

        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            elements.toggleDark.checked = true;
        }

        elements.toggleDark.addEventListener('change', function () {
            if (this.checked) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                return;
            }

            document.documentElement.removeAttribute('data-bs-theme');
            localStorage.setItem('theme', 'light');
        });
    }

    function initAjaxForm() {
        elements.formAbsen.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitButton = elements.formAbsen.querySelector('button[type="submit"]');
            const initialLabel = submitButton ? submitButton.innerHTML : '';

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses...';
            }

            try {
                const response = await fetch(elements.formAbsen.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(elements.formAbsen),
                });

                const payload = await response.json();
                updateCsrfToken(payload.csrf);

                const type = payload.success ? 'success' : 'error';
                const message = payload.message || 'Terjadi kesalahan saat memproses absensi.';
                showToast(type, message);

                if (payload.data?.absensi) {
                    renderAbsensiRows(payload.data.absensi);
                } else {
                    await refreshAbsensiData();
                }
            } catch (error) {
                showToast('error', 'Tidak bisa terhubung ke server. Coba lagi.');
                console.error(error);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = initialLabel;
                }

                elements.inputIdSiswa.value = '';
                elements.inputIdSiswa.focus();
            }
        });
    }

    async function refreshAbsensiData() {
        try {
            const response = await fetch(config.rootUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            const payload = await response.json();
            if (!response.ok || !payload.success) return;

            updateCsrfToken(payload.csrf);
            renderAbsensiRows(payload.data?.absensi || []);
        } catch (error) {
            console.warn('Gagal memuat data absensi terbaru:', error);
        }
    }

    function renderAbsensiRows(absensi = []) {
        const berangkat = absensi.filter((item) => item.waktu);
        const pulang = absensi.filter((item) => item.waktu_pulang);

        setText(elements.countBerangkatMini, berangkat.length);
        setText(elements.countPulangMini, pulang.length);
        setText(elements.countBerangkatBadge, berangkat.length);
        setText(elements.countPulangBadge, pulang.length);

        if (elements.tbodyBerangkat) {
            elements.tbodyBerangkat.innerHTML = berangkat.length
                ? berangkat
                    .map(
                        (item, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(item.nama)}</td>
                        <td class="text-muted small">${escapeHtml(item.sekolah)}</td>
                        <td><span class="badge bg-success">${escapeHtml(item.waktu)}</span></td>
                    </tr>`
                    )
                    .join('')
                : '<tr><td colspan="4" class="text-center text-muted py-3">Belum ada yang berangkat</td></tr>';
        }

        if (elements.tbodyPulang) {
            elements.tbodyPulang.innerHTML = pulang.length
                ? pulang
                    .map(
                        (item, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(item.nama)}</td>
                        <td class="text-muted small">${escapeHtml(item.sekolah)}</td>
                        <td><span class="badge bg-danger">${escapeHtml(item.waktu_pulang)}</span></td>
                    </tr>`
                    )
                    .join('')
                : '<tr><td colspan="4" class="text-center text-muted py-3">Belum ada yang pulang</td></tr>';
        }
    }

    function updateCsrfToken(csrf) {
        if (!csrf?.token || !csrf?.hash) return;

        const csrfInput = elements.formAbsen.querySelector(`input[name="${csrf.token}"]`);
        if (csrfInput) {
            csrfInput.value = csrf.hash;
        }
    }

    function showToast(type, message) {
        const icon = type === 'success' ? 'success' : 'error';

        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon,
                html: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
            });
            return;
        }

        alert(message);
    }

    function setText(element, value) {
        if (element) {
            element.textContent = String(value);
        }
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function setScanStatus(text) {
        if (elements.scanStatus) {
            elements.scanStatus.textContent = text;
        }
    }

    window.AbsenInputApp = {
        elements,
        config,
        initCore,
        refreshAbsensiData,
        showToast,
        setScanStatus,
    };

    initCore();
})();
