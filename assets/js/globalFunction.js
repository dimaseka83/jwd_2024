
// Kumpulan variabel global

let totalHrgPerjalanan = 0;

let today = new Date();

let dd = String(today.getDate()).padStart(2, "0");

let mm = String(today.getMonth() + 1).padStart(2, "0");

let yyyy = today.getFullYear();

today = yyyy + "-" + mm + "-" + dd;

// inisialisasi tgl checkIn minimum hari

$(".checkIn").attr("min", today);

// jika checkin diinput maka jalan sebuah function
$(".checkIn").change(function () {
  // remove disabled
  $(".checkOut").removeAttr("disabled");
  // tgl checkout min date
  $(".checkOut").attr("min", $(".checkIn").val());
});

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

// calculate price paket perjalanan every checkbox checked
let checkboxes = document.querySelectorAll(".formPemesanan input[type='checkbox']");

checkboxes.forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    let total = 0;

    checkboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        total += parseInt(checkbox.value);
      }
    });

    totalHrgPerjalanan = total;

    // change to Rupiah
    document.querySelector(
      ".hargaPaketPerjalanan"
    ).value = `Rp. ${total.toLocaleString()}`;
  });
});

// calculate total days
const calculateTotalDays = () => {
  const checkIn = new Date($(".checkIn").val());
  const checkOut = new Date($(".checkOut").val());

  const diffTime = Math.abs(checkOut - checkIn);

  const totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return totalDays;
};

// calculate tagihan
const kalkulasiTagihan = () => {
  const jumlahOrg = parseInt($(".jmlOrg").val());
  // check if jumlahOrg is not a number
  const cekJmlOrg = isNaN(jumlahOrg);

  let totalDays = 0;

  if ($(".checkIn").val() && $(".checkOut").val()) {
    // calculate total day
    totalDays = calculateTotalDays();
  } else {
    Swal.fire("Peringatan", "Data yang diisi tidak boleh kosong", "warning");
    return;
  }

  // total days 0
  if (totalDays == 0) {
    totalDays = 1;
  }

  // validation
  if (cekJmlOrg || totalHrgPerjalanan == 0 || totalDays == 0) {
    Swal.fire("Peringatan", "Data yang diisi tidak boleh kosong", "warning");
    return;
  }

  // calculate total tagihan
  const totalTagihan = totalHrgPerjalanan * jumlahOrg * totalDays;

// discount 10% if jumlahOrg > 5
  if (jumlahOrg > 5) {
    const diskon = totalTagihan * 0.1;

    $(".jumlahTagihan").val(`Rp. ${(totalTagihan - diskon).toLocaleString()}`);
  } else {
    $(".jumlahTagihan").val(`Rp. ${totalTagihan.toLocaleString()}`);
  }
};

// reset form menjadi kosong

const resetForm = () => {
  $(".formPemesanan").trigger("reset");

  totalHrgPerjalanan = 0;

  $(".checkOut").attr("disabled", true);
};
