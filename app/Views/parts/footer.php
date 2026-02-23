<footer class="mt-auto py-4  text-muted text-center">
    <span><?= date('Y') ?> &copy; UPT Komputer</span>
    <span>
        Made with <i class="bi bi-heart-fill text-danger"></i> by
        <a href="https://github.com/AllMuchalif2" class="text-primary text-decoration-none">AllMuchalif2</a>
    </span>
    </div>
</footer>
</div>
</div>



<script src="<?= base_url('assets/static/js/components/dark.js') ?>"></script>
<script src="<?= base_url('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') ?>"></script>
<script src="<?= base_url('assets/compiled/js/app.js') ?>"></script>



<!-- Need: Apexcharts -->
<script src="<?= base_url('assets/extensions/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/dashboard.js') ?>"></script>
<script src="<?= base_url('assets/extensions/jquery/jquery.min.js') ?>"></script>

<!-- datatables -->
<script src="<?= base_url('assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script src="<?= base_url('assets/static/js/pages/simple-datatables.js') ?>"></script>

<!-- SweetAlert2 -->
<script src="<?= base_url('assets/extensions/sweetalert2/sweetalert2.all.min.js') ?>"></script>

<script>
    setTimeout(function () {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Allow fade-out animation before removing
        }
    }, 3000);

    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Global SweetAlert2 confirm untuk link dengan class swal-confirm
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.swal-confirm').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                const title = this.dataset.title || 'Apakah Anda yakin?';
                const text = this.dataset.text || 'Tindakan ini tidak dapat dibatalkan.';
                const icon = this.dataset.icon || 'warning';
                const confirmText = this.dataset.confirm || 'Ya, lanjutkan!';
                const confirmColor = this.dataset.color || '#d33';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    });
</script>

</body>

</html>