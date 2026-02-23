<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Absensi</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Absensi</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <form method="GET" class="mb-4">
            <?php $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d'); ?>
            <div class="input-group">
                <span class="input-group-text"><label for="tanggal">Pilih Tanggal:</label></span>
                <input type="date" name="tanggal" value="<?= $tanggal; ?>" class="form-control" id="tanggal">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Daftar Absensi</h4>
                <div>
                    <span class="badge bg-success me-2">Hadir: <?= $count_hadir ?? 0 ?></span>
                    <span class="badge bg-warning me-2">Izin: <?= $count_izin ?? 0 ?></span>
                    <span class="badge bg-danger me-2">Sakit: <?= $count_sakit ?? 0 ?></span>
                    <span class="badge bg-secondary">Alpa: <?= $count_alpa ?? 0 ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Sekolah</th>
                                <th>Waktu Berangkat</th>
                                <th>Waktu Pulang</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($kehadiran as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($item['id_siswa']) ?></td>
                                    <td><?= esc($item['nama']) ?></td>
                                    <td><?= esc($item['sekolah']) ?></td>
                                    <td><?= $item['waktu'] ? esc($item['waktu']) : '-' ?></td>
                                    <td><?= $item['waktu_pulang'] ? esc($item['waktu_pulang']) : '-' ?></td>
                                    <td class="text-center">
                                        <span
                                            class="badge <?= $item['keterangan'] === 'Hadir' ? 'bg-success' : ($item['keterangan'] === 'Izin' ? 'bg-warning' : ($item['keterangan'] === 'Sakit' ? 'bg-danger' : 'bg-secondary')) ?>">
                                            <?= esc($item['keterangan']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editModal" data-id="<?= $item['id_siswa'] ?>"
                                            data-nama="<?= esc($item['nama']) ?>" data-tanggal="<?= esc($tanggal) ?>"
                                            data-keterangan="<?= esc($item['keterangan']) ?>">
                                            <i class="bi bi-pencil"></i> Ubah
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </section>
</div>

<!-- Modal Edit Absensi -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?= base_url('absensi/update') ?>" id="formEditAbsensi">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="tanggal" id="edit_tanggal">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Ubah Keterangan Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="edit_nama" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" value="<?= esc($tanggal) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <select class="form-select" name="keterangan" id="edit_keterangan" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alpa">Alpa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const tanggal = button.getAttribute('data-tanggal');
            const keterangan = button.getAttribute('data-keterangan');

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_tanggal').value = tanggal;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_keterangan').value = keterangan;
        });
    });
</script>




<?= view('parts/footer'); ?>