<?php
session_start();
// proteksi session jika user belum login
if (!isset($_SESSION['role'])) {
  header('Location: /jwd/page/auth/login.php');
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
  <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js
"></script>
  <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css
" rel="stylesheet" />
</head>

<body>
  <!-- html navigation -->
  <nav class="navbar navbar-expand-lg shadow">
    <div class="container-fluid">
      <a href="#" class="navbar-brand">Wisata Banyuwangi</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link " href="index.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" aria-current="page">Form Pemesanan</a>
          </li>
        </ul>
        <a class="nav-link" href="../../backend/controller/logout.php">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <!-- form -->
  <div class="container mt-5">
    <h1 class="display-6">Form Pemesanan Paket Wisata</h1>

    <form class="row g-3 formPemesanan">
      <div class="col-md-4">
        <div class="form-group">
          <label for="">Nama Pemesan</label>
          <input type="text" name="nama" class="form-control" name="" />
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="">Nomor Telp / HP</label>
          <input type="number" name="phone" class="form-control" />
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="">Email</label>
          <input type="email" name="email" class="form-control" />
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="">Jumlah Orang</label>
          <input type="number" name="jumlah_org" class="form-control jmlOrg" />
          <small class="form-text"><span class="text-danger">*</span>Jika Pemesanan lebih dari 5
            orang, maka anda berhak mendapatkan diskon 10%</small>
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="">Tanggal Berangkat</label>
          <input type="date" name="tgl_berangkat" class="form-control checkIn" />
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="">Tanggal Pulang</label>
          <input type="date" name="tgl_pulang" class="form-control checkOut" disabled />
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label for="">Pelayanan Paket Perjalanan</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1000000" id="flexCheckDefault" />
            <label class="form-check-label" for="flexCheckDefault">
              Penginapan (Rp. 1.000.000)
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1200000" id="flexCheckDefault" />
            <label class="form-check-label" for="flexCheckDefault">
              Transportasi (Rp.1.200.000)
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="500000" id="flexCheckDefault" />
            <label class="form-check-label" for="flexCheckDefault">
              Makanan (Rp. 500.000)
            </label>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="">Harga Paket Perjalanan</label>
          <input type="text" class="form-control hargaPaketPerjalanan" readonly />
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="">Jumlah Tagihan</label>
          <input type="text" class="form-control jumlahTagihan" />
        </div>
      </div>

      <div class="col-12">
        <button class="btn btn-success" type="button" onclick="kalkulasiTagihan()">
          Hitung
        </button>
        <button class="btn btn-success mx-2" type="button" onclick="simpan()">Simpan</button>
        <button class="btn btn-danger" type="button" onclick="resetForm()">
          Batal
        </button>
      </div>
    </form>
  </div>

  <script>
    // function simpan to save data
    const simpan = () => {
      const cekForm = $(".formPemesanan").serializeArray();

      let cekFormLength = cekForm.length;

      for (let i = 0; i < cekFormLength; i++) {
        if (cekForm[i].value == "") {
          Swal.fire("Peringatan", "Data yang diisi tidak boleh kosong", "warning");
          return;
        }
      }

      Swal.fire({
        title: "Apakah anda yakin?",
        text: "Data yang sudah disimpan tidak dapat diubah",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, simpan!",
      }).then((result) => {
        if (result.isConfirmed) {
          // call function sendData
          sendData();
        }
      });
    }

    // function send data to database via api
    const sendData = () => {
      const data = $(".formPemesanan").serializeArray();
      const formData = {}

      data.forEach((item) => {
        formData[item.name] = item.value;
      });

      // get value from checkbox

      formData["paket_inap"] = $("input[type='checkbox']")[0].checked;
      formData["paket_transport"] = $("input[type='checkbox']")[1].checked;
      formData["paket_makan"] = $("input[type='checkbox']")[2].checked;

      console.log(formData);

      // send data to backend
      $.ajax({
        url: "/jwd/backend/form.php",
        type: "POST",
        data: JSON.stringify(formData),
        success: function(result) {
          Swal.fire("Data berhasil disimpan!", "", "success");
          window.location.href = "/jwd/page/admin/pesanan.php";
          resetForm();
        },
      });
    };
  </script>
  <script src="../../js/globalFunction.js"></script>
  <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>