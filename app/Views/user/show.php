<?= view('parts/header') ?>
<?= view('parts/sidebar') ?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail User</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/user">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= esc($user['username']) ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header <?= $user['role'] == 'admin' ? 'bg-primary' : 'bg-success'; ?> text-white">
                <h5>Detail User</h5>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        <?php
                        if (is_array(session()->getFlashdata('error'))) {
                            foreach (session()->getFlashdata('error') as $error) {
                                echo esc($error) . '<br>';
                            }
                        } else {
                            echo esc(session()->getFlashdata('error'));
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="row mb-4 mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="disabledInput">Username</label>
                            <p class="form-control-static" id="staticInput"><?= esc($user['username']); ?></p>
                        </div>
                        <div class="form-group">
                            <label for="disabledInput">Nama</label>
                            <p class="form-control-static" id="staticInput"><?= esc($user['name']); ?></p>
                        </div>
                        <div class="form-group">
                            <label for="disabledInput">Role</label>
                            <p class="form-control-static" id="staticInput">
                                <?= esc($user['role']) == 'admin' ? 'Admin' : 'Asisten'; ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form action="<?= base_url('user/newpass/' . $user['username']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="old_password">Password saat ini</label>
                                <input type="text" class="form-control" id="old_password" name="old_password" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <input type="text" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Password</label>
                                <input type="text" class="form-control" id="confirm_password" name="confirm_password"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?= view('parts/footer') ?>