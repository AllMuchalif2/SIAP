<?= view('parts/header'); ?>

<div class="card text-center container mt-5 my-auto" style="width: 50%;">
    <h2 class="mt-4 ">Form Absensi Siswa</h2>
    <hr>
    <form action="<?= site_url('absen/absen'); ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group px-3">
            <label for="id_siswa" class="">Masukkan id user</label>
            <input type="text" name="id_siswa" class="form-control" id="id_siswa" required>

            <div class="d-grid gap-2 my-4">
                <button type="submit" class="btn btn-outline-secondary">Absen</button>
            </div>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" id="alert-box">
                    <?= session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" id="alert-box">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <hr>
</div>

<?= view("parts/footer") ?>