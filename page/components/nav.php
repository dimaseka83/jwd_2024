<?php
session_start(); // Starting the session

?>

<nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
        <a href="#" class="navbar-brand">Wisata Banyuwangi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            // Checking if session role is set
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == 'customer') {
                    // If role is customer, show customer links
                    echo '
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Form Pemesanan</a>
                        </li>
                    </ul>
                    <a class="nav-link" href="../../backend/controller/logout.php">
                        Logout
                    </a>';
                } else {
                    // If role is not customer, assuming it's admin, show admin links
                    echo '
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/jwd/page/admin/pesanan.php">Daftar Pesanan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Daftar Pariwisata</a>
                        </li>
                    </ul>
                    <a class="nav-link" href="../../backend/controller/logout.php">
                        Logout
                    </a>';
                }
            } else {
                // If session role is not set, show login link
                echo '<a class="nav-link" href="login.php">Login</a>';
            }
            ?>
        </div>
    </div>
</nav>