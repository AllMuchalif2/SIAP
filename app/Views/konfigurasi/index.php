<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Konfigurasi Sistem</h3>
                <p class="text-subtitle text-muted">Kelola pengaturan sistem di sini.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Konfigurasi</li>
                    </ol>
                </nav>
            </div>
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

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Konfigurasi</h4>
            </div>
            <div class="card-body">
                <?php foreach ($konfigurasi as $row): ?>
                    <?php if ($row['slug'] === 'ip_allowed'): ?>
                        <form action="/konfigurasi/update" method="post" class="mb-4 p-4 border rounded shadow-sm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold d-block mb-2">Daftar IP yang Diperbolehkan</label>
                                <p class="text-muted small mb-3"><?= esc($row['description']) ?></p>
                                
                                <div id="ip-container">
                                    <?php 
                                    $ips = array_map('trim', explode(',', $row['value']));
                                    foreach ($ips as $ip): if(empty($ip)) continue;
                                    ?>
                                        <div class="input-group mb-2 ip-row">
                                            <input type="text" name="value[]" class="form-control" value="<?= esc($ip) ?>" placeholder="Contoh: 192.168.1.1">
                                            <button type="button" class="btn btn-outline-danger btn-remove-ip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <button type="button" id="btn-add-ip" class="btn btn-outline-success btn-sm mt-2">
                                    <i class="bi bi-plus-circle"></i> Tambah IP Baru
                                </button>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i> Simpan Semua Konfigurasi
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <form action="/konfigurasi/update" method="post" class="mb-4 p-3 border rounded shadow-sm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="form-group mb-3">
                                <label for="value_<?= $row['id'] ?>" class="form-label fw-bold"><?= esc($row['slug']) ?></label>
                                <input type="text" class="form-control" id="value_<?= $row['id'] ?>" name="value" value="<?= esc($row['value']) ?>">
                                <small class="text-muted"><?= esc($row['description']) ?></small>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ip-container');
    const btnAdd = document.getElementById('btn-add-ip');

    if (btnAdd) {
        // Tambah input baru
        btnAdd.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'input-group mb-2 ip-row';
            row.innerHTML = `
                <input type="text" name="value[]" class="form-control" placeholder="Contoh: 192.168.1.1">
                <button type="button" class="btn btn-outline-danger btn-remove-ip">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            container.appendChild(row);
        });

        // Hapus input (Event Delegation)
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-ip');
            if (btn) {
                const row = btn.closest('.ip-row');
                if (container.querySelectorAll('.ip-row').length > 1) {
                    row.remove();
                } else {
                    row.querySelector('input').value = '';
                }
            }
        });
    }
});
</script>

<?= view('parts/footer'); ?>