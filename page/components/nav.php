<?php
?>
<!-- this code sets up the basic structure of an HTML document, includes a navigation component, and allows for dynamic content rendering. 
            Additionally, it includes the necessary JavaScript file for Bootstrap functionality. -->
<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a href="/" class="navbar-brand">Hotel ABC</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?php
            // this code segment dynamically generates navigation links based on the user's role. 
            // It provides different sets of links for customers and admins,
            //  ensuring appropriate navigation options are presented based on the user's role and authentication status.

            // Checking if session role is set
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == 'customer') {
                    // If role is customer, show customer links
                    echo '
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/page/customer/form.php">Form Pemesanan</a>
                        </li>
                    </ul>
                    <a class="nav-link" href="/backend/controller/logout.php">
                        Logout
                    </a>';
                } else {
                    // If role is not customer, assuming it's admin, show admin links
                    echo '
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/page/admin/pesanan.php">Daftar Pesanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/page/admin/kamar.php">Daftar Kamar</a>
                        </li>
                    </ul>
                    <a class="nav-link" href="/backend/controller/logout.php">
                        Logout
                    </a>';
                }
            } else {
                // If session role is not set, show login link
                echo '
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"></a>
                    </li>
                </ul>
                <div class="d-flex "><a class="nav-link" href="/page/auth/login.php">Login</a></div>
                ';
            }
            ?>
        </div>
    </div>
</nav>

<script>
    // add nav link active when url is same as href
    $(document).ready(function() {
        $('a[href="' + location.pathname + '"]').addClass('active')
    })
</script>