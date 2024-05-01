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

<?php
include('../components/nav.php')
?>

<body>

  <div class="resultSimpan container mt-5"></div>
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
        <a class="btn btn-danger" type="button" href="/jwd" onclick="resetForm()">
          Batal
        </a>
      </div>
    </form>
  </div>

  <script>
    let resultSave = null;
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

    const changeDatetoLocale = (date) => {
      let newDate = new Date(date);
      return newDate.toLocaleDateString();
    };

    const paketPerjalanan = (item) => {

      let paket = [];
      if (item.paket_inap == "1") {
        paket.push("Paket Inap");
      }
      if (item.paket_transport == "1") {
        paket.push("Paket Transport");
      }
      if (item.paket_makan == "1") {
        paket.push("Paket Makan");
      }

      if (paket.length == 0) {
        return "Tidak ada paket";
      }

      return paket.join(", ");
    };

    const pricePackage = (item) => {
      let harga = 0;
      if (item.paket_inap == "1") {
        harga += 1000000;
      }
      if (item.paket_transport == "1") {
        harga += 1200000;
      }
      if (item.paket_makan == "1") {
        harga += 500000;
      } else {
        harga += 0;
      }

      return {
        int: harga,
        string: `Rp ${harga.toLocaleString()}`
      };
    };

    const calculateTotalDaysItem = (item) => {
      let tglBerangkat = new Date(item.tgl_berangkat);
      let tglPulang = new Date(item.tgl_pulang);
      let diffTime = Math.abs(tglPulang - tglBerangkat);
      let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    };

    const calculateTotalItem = (item) => {
      let totalDays = calculateTotalDaysItem(item);

      if (calculateTotalDaysItem(item) == 0) {
        totalDays = 1;
      }

      const totalTagihan =
        pricePackage(item).int * totalDays * item.jumlah_org;

      if (item.jumlah_org > 5) {
        const diskon = totalTagihan * 0.1;

        return `Rp ${totalTagihan.toLocaleString()} (Diskon 10% = Rp ${(
              totalTagihan - diskon
            ).toLocaleString()})`;
      } else {
        return `Rp ${totalTagihan.toLocaleString()}`;
      }
    };

    const showResultResponse = (result) => {
      let html = "";
      html += `
      <div class="alert alert-success" role="alert">
        ${result.message}
      </div>
      <table class="table mt-2">
      <thead>
        <tr>
          <th>Nama Pemesan</th>
          <th>Nomor Telp / HP</th>
          <th>Email</th>
          <th>Jumlah Orang</th>
          <th>Tanggal Berangkat</th>
          <th>Tanggal Pulang</th>
          <th>Pelayanan Paket Perjalanan</th>
          <th>Harga Paket Perjalanan</th>
          <th>Jumlah Tagihan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>${result.data.nama}</td>
          <td>${result.data.phone}</td>
          <td>${result.data.email}</td>
          <td>${result.data.jumlah_org}</td>
          <td>${changeDatetoLocale(result.data.tgl_berangkat)}</td>
          <td>${changeDatetoLocale(result.data.tgl_pulang)}</td>
          <td>${paketPerjalanan(result.data)}</td>
          <td>${pricePackage(result.data).string}</td>
          <td>${calculateTotalItem(result.data)}</td>
        </tr>
      </tbody>
    </table>
      `

      $(".resultSimpan").html(html);
    };
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
          resultSave = result;
          showResultResponse(result);
          resetForm();
        },
      });
    };
  </script>
  <script src="../../js/globalFunction.js"></script>
  <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>