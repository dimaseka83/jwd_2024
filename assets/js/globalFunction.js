
// Kumpulan variabel global

let totalHrgPerjalanan = 0;

let today = new Date();

let dd = String(today.getDate()).padStart(2, "0");

let mm = String(today.getMonth() + 1).padStart(2, "0");

let yyyy = today.getFullYear();

today = yyyy + "-" + mm + "-" + dd;

// inisialisasi tgl checkIn minimum hari

$(".checkIn").attr("min", today);

// phone number input only number
$("input[type='number']").keypress(function (e) {
  var keyCode = e.keyCode || e.which;
  var regex = /^[0-9]+$/;
  var isValid = regex.test(String.fromCharCode(keyCode));
  if (!isValid) {
    e.preventDefault();
    return false;
  }
});


// calculate tagihan
const kalkulasiTagihan = () => {

  const cekNulled =
    $("input[name='jmlHari']").val() && $("select[name='kamar']").val();

  if (!cekNulled) {
    Swal.fire("Peringatan", "Data yang diisi tidak boleh kosong", "warning");
    return;
  }

  const hari = parseInt($("input[name='jmlHari']").val());

  const kamar = $("select[name='kamar']").val();

  const kamarPrice = dataKamar.find((item) => item.id == kamar).harga;

  let totalTagihan = 0

  const isBreakfast = $("input[name='breakfast']").is(":checked");

  if (isBreakfast) {
    totalTagihan = (kamarPrice * hari) + (80000 * hari);
  } else {
    totalTagihan = kamarPrice * hari;
  }

  if (hari > 3) {
    const diskon = totalTagihan * 0.1;
    totalTagihan = totalTagihan - diskon;
  }

  $(".totalTagihan").val(`Rp. ${totalTagihan.toLocaleString()}`);
};


// reset form menjadi kosong

const resetForm = () => {
  $(".formPemesanan").trigger("reset");

};

