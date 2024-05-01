<?php
session_start();
ob_start();
?>

<div class="row container mt-5 dataPariwisataBanyuwangi"></div>

<script>
    // get data from backend
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "/jwd/backend/wisata.php",
            success: function(response) {
                const data = response
                let html = ''
                if (data.length > 0) {
                    data.forEach(pariwisata => {
                        html += `
            <div class="col-md-4 d-flex align-items-stretch">
              <div class="card flex-grow-1 m-2" style="width: 18rem;">
                <img src="/jwd/backend/assets/img/${pariwisata?.img}" class="card-img-top" alt="${pariwisata?.nama}" />
                <div class="card-body">
                  <h5 class="card-title">${pariwisata?.nama}</h5>
                  <a href="/jwd/page/customer/form.php" class="btn btn-success">Pesan Sekarang</a>
                  <a href="${pariwisata?.link}" target="_blank" class="btn btn-primary">Lihat Detail</a>
                </div>
              </div>
            </div>
            `;
                    });
                } else {
                    html = '<h1 class="text-center">Data Kosong</h1>'
                }
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

<?php
$content = ob_get_clean();
require './page/components/index.php';
?>