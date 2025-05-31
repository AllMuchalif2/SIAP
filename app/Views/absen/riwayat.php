<?= view('parts/header'); ?>
<?= view('parts/sidebar'); ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Riwayat Perubahan Absensi</h3>
            </div>
            <!-- <div class="col-12 col-md-6 order-md-2 order-first">
                <nav class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Absensi</li>
                    </ol>
                </nav>
            </div> -->
        </div>
    </div>

    <section class="section">
        <form method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text">Pilih Tanggal</span>
                <input type="date" name="tanggal" value="<?= esc($tanggal) ?>" class="form-control">
                <button class="btn btn-outline-primary" type="submit">Filter</button>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h4>Riwayat Perubahan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>ID Siswa</th>
                                <th>User</th>
                                <th>Ket. Lama</th>
                                <th>Ket. Baru</th>
                                <th>Berangkat Lama</th>
                                <th>Berangkat Baru</th>
                                <th>Pulang Lama</th>
                                <th>Pulang Baru</th>
                                <th>Waktu Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($log): ?>
                                <?php $no = 1; foreach ($log as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($row['nama_siswa']) ?></td>
                                        <td><?= esc($row['id_siswa']) ?></td>
                                        <td><?= esc($row['username']) ?></td>
                                        <td><span class="badge bg-secondary"><?= esc($row['keterangan_lama']) ?></span></td>
                                        <td><span class="badge bg-info"><?= esc($row['keterangan_baru']) ?></span></td>
                                        <td><?= $row['waktu_lama'] ?: '-' ?></td>
                                        <td><?= $row['waktu_baru'] ?: '-' ?></td>
                                        <td><?= $row['waktu_pulang_lama'] ?: '-' ?></td>
                                        <td><?= $row['waktu_pulang_baru'] ?: '-' ?></td>
                                        <td><?= date('d-m-Y H:i:s', strtotime($row['updated_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?= view('parts/footer'); ?>
