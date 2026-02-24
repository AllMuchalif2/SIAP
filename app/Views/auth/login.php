<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UPT Komputer</title>


    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/app-dark.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/compiled/css/auth.css') ?>">
</head>

<body>
    <script src="./assets/static/js/initTheme.js"></script>
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <!-- <div class="auth-logo">
                        <a href="index.html"><img src="./assets/compiled/svg/logo.svg" alt="Logo"></a>
                    </div> -->
                    <hr>

                    <form action="/attempt-login" method="POST">
                        <?= csrf_field() ?>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="text" class="form-control form-control-xl" placeholder="Username"
                                name="username" required>
                            <div class="form-control-icon">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left mb-4">
                            <input type="password" class="form-control form-control-xl" placeholder="Password"
                                name="password" required>
                            <div class="form-control-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-lg shadow-lg ">Log in</button>
                    </form>
                    <div class="text-center mt-5 text-lg fs-4">
                        <p class="text-gray-600">Lupa Password? <span class="text-bold">telpon admin</span></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">

                </div>
            </div>
        </div>

    </div>

    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets/extensions/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        <?php if (session()->getFlashdata('error')): ?>
            Toast.fire({
                icon: 'error',
                title: '<?= session()->getFlashdata('error') ?>'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('success') ?>'
            });
        <?php endif; ?>
    </script>
</body>

</html>