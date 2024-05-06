<?php
session_start();
// proteksi session jika user belum login
if (!isset($_SESSION['role'])) {
  header('Location: /page/auth/login.php');
} else if ($_SESSION['role'] != 'admin') {
  header('Location: /page/customer/form.php');
}
ob_start();
?>
<div class="container mt-5">
  <h1 class="display-6">Daftar Pemesanan</h1>
  <table class="table">
    <thead>
      <tr>
        <th>Nama Pemesan</th>
        <th>Nomor Identitas</th>
        <th>Jenis Kelamin</th>
        <th>Kamar</th>
        <th>Harga</th>
        <th>Tanggal Pemesanan</th>
        <th>Jumlah Hari</th>
        <th>Termasuk Breakfast</th>
        <th>Jumlah Tagihan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Modal</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 formPemesanan">
          <div class="col-md-6">
            <div class="form-group">
              <label for="">Nama Pemesan</label>
              <input type="text" name="nama" id="" class="form-control" placeholder="" aria-describedby="helpId">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Nomor Identitas</label>
              <input type="number" name="identitas" id="" class="form-control" placeholder="" aria-describedby="helpId">
              <div class="invalid-feedback">Isian Salah Harus 16 Digit</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Jenis Kelamin</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="kelamin" id="exampleRadios1" value="laki" checked>
                <label class="form-check-label" for="exampleRadios1">
                  Laki-laki
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="kelamin" id="exampleRadios2" value="perempuan">
                <label class="form-check-label" for="exampleRadios2">
                  Perempuan
                </label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Tipe Kamar</label>
              <select name="kamar" id="" class="form-control"></select>
            </div>
          </div>

          <div class="col-md 6">
            <div class="form-group">
              <label for="">Harga</label>
              <input type="text" class="form-control priceKamar" disabled placeholder="" aria-describedby="helpId">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Tanggal Pesan</label>
              <input type="date" name="tglpesan" id="" class="form-control checkIn" placeholder="" aria-describedby="helpId">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Durasi</label>
              <div class="input-group mb-3">
                <input type="text" name="jmlHari" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2">
                <span class="input-group-text" id="basic-addon2">Hari</span>
                <div class="invalid-feedback">Harus isi angka</div>
              </div>
              <span class="text-muted"><span class="text-danger">*</span>Diskon 10% jika lebih dari 3 hari</span>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="">Termasuk Breakfast</label>
              <div class="form-check">
                <input class="form-check-input" name="breakfast" type="checkbox" value="0" id="breakFastCheck">
                <label class="form-check-label" for="breakFastCheck">
                  Tidak
                </label>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="">Total Bayar</label>
              <input type="text" class="form-control totalTagihan" disabled>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="button" onclick="kalkulasiTagihan()">
          Hitung
        </button>
        <button class="btn btn-success mx-2" type="button" onclick="simpan()">Simpan</button>
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>

<script src="/assets/js/globalFunction.js"></script>
<script>
  // variable
  let formDataSelected = null;
  let tablePemesanan = null;
  let dataKamar = null;

  // checkbox breakfast
  $("#breakFastCheck").change(function() {
    if (this.checked) {
      $(this).val("1");
      $('label[for="breakFastCheck"]').text('Ya');
    } else {
      $(this).val("0");
      $('label[for="breakFastCheck"]').text('Tidak');
    }
  });


  $("select[name='kamar']").change(function() {
    let idKamar = $(this).val();
    let data = dataKamar.find((item) => item.id == idKamar);
    $(".formPemesanan .priceKamar").val(currencyRupiah(data.harga));
  });

  // jmlHari input must number
  $("input[name='jmlHari']").on("input", function() {
    let value = $(this).val();
    if (isNaN(value)) {
      $(this).addClass("is-invalid");
    } else {
      $(this).removeClass("is-invalid");
    }
  });

  // isian identitas must 16 digit
  $("input[name='identitas']").on("input", function() {
    let value = $(this).val();
    if (value.length != 16) {
      $(this).addClass("is-invalid");
    } else {
      $(this).removeClass("is-invalid");
    }
  });

  // The provided JavaScript code initializes upon document ready, executing an AJAX GET request to retrieve data from the backend PHP script.
  // Upon success, it populates a table with the fetched data, iterating through each item and appending corresponding HTML table rows.
  // Additionally, several helper functions are defined within the script to facilitate tasks such as formatting dates, calculating total package prices, and determining the total number of days
  // for a trip.Each table row includes buttons to edit or delete the corresponding data entry.If the fetched data is empty, a message indicating no data is displayed within the table.
  $(document).ready(function() {

    // function paketPerjalanan to check and show in array
    const jenisKelamin = (data) => {
      if (data.kelamin == "laki") {
        return "Laki-laki";
      } else {
        return "Perempuan";
      }
    };

    const getNamaKamar = (data) => {
      let kamar = dataKamar.find((item) => item.id == data?.kamar);

      if (!kamar) {
        return {
          nama: "Tidak Diketahui",
          harga: 0
        }
      }
      return {
        namaKamar: kamar?.nama,
        harga: kamar?.harga
      }
    };

    const changeDatetoLocale = (data) => {
      let date = new Date(data);
      return date.toLocaleDateString();
    };

    const isBreakfast = (data) => {
      if (data.breakfast == "1") {
        return "Ya";
      } else {
        return "Tidak";
      }
    };

    const calculateTagihan = (item) => {
      const hari = item.jmlHari;

      const kamarPrice = getNamaKamar(item).harga;

      let totalTagihan = 0

      const isBreakfast = item.breakfast == "1" ? true : false;

      if (isBreakfast) {
        totalTagihan = (kamarPrice * hari) + (80000 * hari)
      } else {
        totalTagihan = kamarPrice * hari;
      }

      if (hari > 3) {
        totalTagihan = totalTagihan - (totalTagihan * 0.1)
        return `${currencyRupiah(totalTagihan)} (Diskon 10%)`
      }

      return currencyRupiah(totalTagihan);
    }

    // get data from database

    $.ajax({
      type: "GET",
      url: "/backend/kamar.php",
      success: function(response) {
        dataKamar = response;
        getDataForm();
      }
    });

    const getDataForm = () => {
      $.ajax({
        url: "/backend/form.php",
        type: "GET",
        success: function(result) {
          let data = result;
          tablePemesanan = result;
          let table = $("table tbody");
          if (data.length > 0) {
            data.forEach((item) => {
              table.append(`
                    <tr>
                        <td>${item?.nama}</td>
                        <td>${item?.identitas}</td>
                        <td>${jenisKelamin(item)}</td>
                        <td>${getNamaKamar(item)?.namaKamar}</td>
                        <td>${currencyRupiah(getNamaKamar(item)?.harga)}</td>
                        <td>${changeDatetoLocale(item?.tglpesan)}</td>
                        <td>${item.jmlHari}</td>
                        <td>${isBreakfast(item)}</td>
                        <td>${calculateTagihan(item)}</td>
                        <td>
                          <button class="btn btn-warning" onclick="editData(${
                            item.id
                          })">Edit</button>
                            <button class="btn btn-danger" onclick="deleteData(${
                              item.id
                            })">Hapus</button>
                        </td>
                    </tr>
                `);
            });
          } else {
            table.append(`
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
                </tr>
            `);

          }
        }
      });
    }
  });

  // function delete data from database
  // The provided JavaScript
  // function `deleteData`
  // is triggered when a delete action is initiated on a specific data entry.It utilizes the Swal(SweetAlert) library to prompt the user with a confirmation dialog, asking
  // if they are sure about the deletion.Upon confirmation, an AJAX DELETE request is sent to the backend PHP script with the corresponding data entry ID.
  // If the deletion is successful, another Swal alert notifies the user, and the page is reloaded to reflect the updated data.
  const deleteData = (id) => {
    Swal.fire({
      title: "Apakah Anda Yakin?",
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, Hapus!"
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "/backend/form.php?id=" + id,
          type: "DELETE",
          success: function(result) {
            Swal.fire("Terhapus!", "Data berhasil dihapus.", "success");
            // reload page
            location.reload();
          }
        });
      }
    });
  };

  // The `editData`
  // function is responsible
  // for populating a form with existing data
  // for editing.It retrieves the specific data entry from the `tablePemesanan`
  // array based on the provided ID.Then, it updates the form fields with the corresponding values, including name, phone, email, number of people, travel dates, and selected package options.Additionally, it ensures that the end date input field is enabled and has a minimum value set based on the start date.Finally, it calculates and displays the total price of the selected travel packages, triggers a calculation of the total bill, and displays the modal
  // for editing.
  const editData = (id) => {
    let data = tablePemesanan.find((item) => item.id == id);
    formDataSelected = data;
    $(".formPemesanan input[name=nama]").val(data.nama);
    $(".formPemesanan input[name=identitas]").val(data.identitas);
    $(".formPemesanan input[name=kelamin][value=" + data.kelamin + "]").prop("checked", true);
    // option selected kamar
    $(".formPemesanan select[name=kamar]").empty();
    dataKamar.forEach((item) => {
      $(".formPemesanan select[name=kamar]").append(`
            <option value="${item.id}">${item.nama}</option>
        `);
    });
    $(".formPemesanan select[name=kamar]").val(data.kamar);
    $(".formPemesanan .priceKamar").val(currencyRupiah(dataKamar.find((item) => item.id == data.kamar).harga));
    $(".formPemesanan input[name=jmlHari]").val(data.jmlHari);
    $(".formPemesanan input[name=tglpesan]").val(data.tglpesan);
    $(".formPemesanan input[name=breakfast]").prop("checked", data.breakfast == "1" ? true : false);
    $(".formPemesanan label[for=breakFastCheck]").text(data.breakfast == "1" ? "Ya" : "Tidak");

    kalkulasiTagihan();
    // show modal
    $("#exampleModal").modal("show");
  };

  // The `simpan`
  // function validates the form fields to ensure that no data is left empty.It serializes the form data and iterates through each field to check
  // if any value is empty.If an empty field is found, it displays a warning message using Swal(SweetAlert) indicating that all fields must be filled.
  // If all fields are filled, it triggers a confirmation dialog using Swal, asking the user to confirm whether they want to proceed with saving the data.If the user confirms, it calls the `sendData`
  // function to send the form data to the backend
  // for processing.
  const simpan = () => {
    const cekForm = $(".formPemesanan").serializeArray();

    let cekFormLength = cekForm.length;

    for (let i = 0; i < cekFormLength; i++) {
      if (cekForm[i].value == "") {
        Swal.fire("Peringatan", "Data yang diisi tidak boleh kosong", "warning");
        return;
      }
    }

    // check nomor identitas is 16 digit
    if ($("input[name='identitas']").val().length != 16) {
      Swal.fire("Peringatan", "Nomor identitas harus 16 digit", "warning");
      return;
    }

    // check jmlHari is number
    if (isNaN($("input[name='jmlHari']").val())) {
      Swal.fire("Peringatan", "Jumlah hari harus angka", "warning");
      return;
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
        sendData();
      }
    });
  }

  // The `sendData` function serializes the form data using jQuery 's `serializeArray` method and then constructs an object `formData` from the serialized data. 
  // It adds additional properties such as `id`, `paket_inap`, `paket_transport`, and `paket_makan` to `formData`. 
  // These properties are derived from the form inputs, particularly checkboxes, to determine whether certain options are selected or not.
  // It then sends the data to the backend using an AJAX POST request to the specified URL. Upon successful submission, it displays a success message using Swal (SweetAlert), reloads the page, and resets the form fields.
  const sendData = () => {
    const data = $(".formPemesanan").serializeArray();
    const formData = {}

    data.forEach((item) => {
      formData[item.name] = item.value;
    });

    formData["id"] = formDataSelected.id;
    formData["harga"] = dataKamar.find((item) => item.id == formData.kamar).harga;


    console.log(formData);

    // kirim ke database
    $.ajax({
      url: "/backend/form.php",
      type: "POST",
      data: JSON.stringify(formData),
      success: function(result) {
        Swal.fire("Data berhasil disimpan!", "", "success");
        location.reload();
        resetForm();
      },
    });
  };
</script>
<?php
$content = ob_get_clean();
require '../../page/components/index.php';
?>