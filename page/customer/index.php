<?php
session_start();
// proteksi session jika user belum login
if (!isset($_SESSION['role'])) {
  header('Location: /jwd');
}

if ($_SESSION['role'] != 'customer') {
  header('Location: /jwd/page/customer/index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="../../css/bootstrap.min.css" />
  <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
</head>

<body>
  <nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
      <a href="#" class="navbar-brand">Wisata Banyuwangi</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="#" aria-current="page">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/jwd/page/customer/form.php">Form Pemesanan</a>
          </li>
        </ul>
        <a class="nav-link" href="../../backend/controller/logout.php">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="row container mt-5 dataPariwisataBanyuwangi"></div>

  <script>
    $(document).ready(function() {
      $.ajax({
        type: "GET",
        url: "/jwd/backend/wisata.php",
        success: function(response) {
          const data = response
          let html = ''
          data.forEach(pariwisata => {
            html += `
            <div class="col-md-4">
          <div class="card m-2" style="width: 18rem;">
            <img src="/jwd/backend/assets/img/${pariwisata.img}" class="card-img-top" alt="..." />
            <div class="card-body">
              <h5 class="card-title">${pariwisata.nama}</h5>
              <a href="${pariwisata.link}" target="_blank" class="btn btn-primary">Lihat Detail</a>
            </div>
          </div>
        </div>
            `;
          });
          $('.dataPariwisataBanyuwangi').html(html)
        }
      });
    });
  </script>

  <style>
    /* card hover */
    .card:hover {
      transform: scale(1.05);
      transition: transform 0.5s;
    }
  </style>

  <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>