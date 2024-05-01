<?php
session_start();
// proteksi session jika user belum login
if (!isset($_SESSION['role'])) {
  header('Location: /jwd');
}

if ($_SESSION['role'] != 'admin') {
  header('Location: /jwd/page/customer/index.php');
}
ob_start();
?>
<div class="container mt-5">
  <h1 class="display-6">Daftar Pemesanan</h1>
  <table class="table">
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
              <small class="form-text"><span class="text-danger">*</span>Jika Pemesanan lebih dari
                5 orang, maka anda berhak mendapatkan diskon 10%</small>
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
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="button" onclick="kalkulasiTagihan()">
          Hitung
        </button>
        <button class="btn btn-success mx-2" type="button" onclick="simpan()">
          Simpan
        </button>
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>

<script src="../../js/globalFunction.js"></script>
<script>
  // variable
  let formDataSelected = null;
  let tablePemesanan = null;
  // The provided JavaScript code initializes upon document ready, executing an AJAX GET request to retrieve data from the backend PHP script.
  // Upon success, it populates a table with the fetched data, iterating through each item and appending corresponding HTML table rows.
  // Additionally, several helper functions are defined within the script to facilitate tasks such as formatting dates, calculating total package prices, and determining the total number of days
  // for a trip.Each table row includes buttons to edit or delete the corresponding data entry.If the fetched data is empty, a message indicating no data is displayed within the table.
  $(document).ready(function() {

    // function paketPerjalanan to check and show in array
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

    // function hargaPaket to calculate the price

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

    // function calculateTotalDays to calculate the total days

    const calculateTotalDays = (item) => {
      let tglBerangkat = new Date(item.tgl_berangkat);
      let tglPulang = new Date(item.tgl_pulang);
      let diffTime = Math.abs(tglPulang - tglBerangkat);
      let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    };

    // function changeDatetoLocale to change the date to locale
    const changeDatetoLocale = (date) => {
      let newDate = new Date(date);
      return newDate.toLocaleDateString();
    };

    // function calculateTotalItem to calculate the total item
    const calculateTotalItem = (item) => {
      let totalDays = calculateTotalDays(item);

      if (calculateTotalDays(item) == 0) {
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

    // get data from database

    $.ajax({
      url: "/jwd/backend/form.php",
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
                        <td>${item?.phone}</td>
                        <td>${item?.email}</td>
                        <td>${item?.jumlah_org} Orang</td>
                        <td>${changeDatetoLocale(item?.tgl_berangkat)}</td>
                        <td>${changeDatetoLocale(item?.tgl_pulang)}</td>
                        <td>${paketPerjalanan(item)}</td>
                        <td>${pricePackage(item).string}</td>
                        <td>${calculateTotalItem(item)}</td>
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
          url: "/jwd/backend/form.php?id=" + id,
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
    $(".formPemesanan input[name=phone]").val(data.phone);
    $(".formPemesanan input[name=email]").val(data.email);
    $(".formPemesanan input[name=jumlah_org]").val(data.jumlah_org);
    $(".formPemesanan input[name=tgl_berangkat]").val(data.tgl_berangkat);
    $(".formPemesanan input[name=tgl_pulang]").val(data.tgl_pulang);
    $(".formPemesanan input[type=checkbox]").prop("checked", false);
    if (data.paket_inap == "1") {
      $(".formPemesanan input[value=1000000]").prop("checked", true);
    }
    if (data.paket_transport == "1") {
      $(".formPemesanan input[value=1200000]").prop("checked", true);
    }
    if (data.paket_makan == "1") {
      $(".formPemesanan input[value=500000]").prop("checked", true);
    }

    $(".formPemesanan input[name=tgl_pulang]").attr(
      "min",
      data.tgl_berangkat
    );
    $(".formPemesanan input[name=tgl_pulang]").prop("disabled", false);

    let checkboxes = document.querySelectorAll(
      ".formPemesanan input[type='checkbox']"
    );

    checkboxes.forEach((item) => {
      let total = 0;
      checkboxes.forEach((item) => {
        if (item.checked) {
          total += parseInt(item.value);
        }
      });

      totalHrgPerjalanan = total;

      $(".hargaPaketPerjalanan").val(`Rp ${total.toLocaleString()}`);
    });

    kalkulasiTagihan();

    // show modal
    $("#exampleModal").modal("show");
  };

  The `simpan`
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
    formData["paket_inap"] = $(".formPemesanan input[type='checkbox']")[0].checked;
    formData["paket_transport"] = $(".formPemesanan input[type='checkbox']")[1].checked;
    formData["paket_makan"] = $(".formPemesanan input[type='checkbox']")[2].checked;

    console.log(formData);

    // kirim ke database
    $.ajax({
      url: "/jwd/backend/form.php",
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