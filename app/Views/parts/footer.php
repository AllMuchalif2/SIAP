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



<script src="./assets/static/js/components/dark.js"></script>
<script src="./assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="./assets/compiled/js/app.js"></script>



<!-- Need: Apexcharts -->
<script src="./assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="./assets/static/js/pages/dashboard.js"></script>
<script src="./assets/extensions/jquery/jquery.min.js"></script>

<!-- datatables -->
<script src="./assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="./assets/static/js/pages/simple-datatables.js"></script>

<script>
    setTimeout(function() {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Allow fade-out animation before removing
        }
    }, 3000);

    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

</body>

</html>