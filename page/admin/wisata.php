<!-- This PHP script initiates a session and provides session protection by redirecting users who haven't logged in to the homepage.
 If the session role is not set or if the role is not 'admin', users are redirected to the appropriate page: either the homepage for unauthenticated users or the customer index page for non-administrative users. 
 Output buffering is activated to manage output efficiently. 
 In summary, this script ensures that only authenticated admin users can access the content of the page, redirecting others to relevant destinations based on their role and login status. -->
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
    <h1 class="display-6">Daftar Pariwisata Banyuwangi</h1>
    <button class="btn btn-primary" onclick="addData()">Tambah</button>
    <table class="table tableGambar">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Link</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Button trigger modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal Tambah / Edit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="wisata">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama Tempat Wisata</label>
                                <input type="text" name="nama" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Link</label>
                                <input type="text" name="link" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">File</label>
                                <input type="file" name="img" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary simpanData">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // variable
    let allDataWisata = [];
    let wisataSelected = null;

    // This JavaScript code snippet utilizes jQuery to perform an AJAX request to retrieve data from the "wisata.php"
    // backend endpoint.Upon successful retrieval, it dynamically generates HTML content based on the received data.For each item in the dataset, it creates a table row(`<tr>`) containing an image, name, link, and buttons
    // for editing and deleting.If no data is found, it displays a message indicating the absence of data.Finally, the generated HTML content is inserted into the table body of an element with the class "tableGambar".Overall, this script dynamically populates a table with data fetched from the backend, providing options
    // for editing and deleting each entry.
    $(document).ready(function() {
        $.ajax({
            url: "/jwd/backend/wisata.php",
            type: 'GET',
            success: function(response) {
                allDataWisata = response
                let html = '';
                if (allDataWisata.length > 0) {
                    allDataWisata.forEach(item => {
                        html += `
                        <tr>
                            <td><img src="/jwd/backend/assets/img/${item?.img}" alt="${item?.nama}" width="100" /></td>
                            <td>${item?.nama}</td>
                            <td><a href="${item?.link}" target="_blank">Kunjungi</a></td>
                            <td>
                                <button class="btn btn-primary" onclick="editData(${item.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteData(${item.id})">Hapus</button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html += `
                        <tr>
                            <td colspan="4" class="text-center">Data tidak ditemukan</td>
                        </tr>
                        `;
                }
                $('.tableGambar tbody').html(html);
            }
        });
    });

    // This JavaScript code manages the addition of data through a form modal.When triggered, the `addData()`
    // function displays a modal and resets the form fields.Upon clicking the "simpanData"
    // button, it validates the input fields for "nama"
    // and "link", showing an error if they are empty.It then constructs a FormData object from the form data, appending additional data such as ID and image files
    // if available.Subsequently, it sends a POST request to the backend endpoint "/jwd/backend/wisata.php"
    // with the FormData.Upon successful submission, it displays a success message and reloads the page after a brief delay.

    const addData = () => {
        // show modal
        $('#staticBackdrop').modal('show');
        wisataSelected = null;
        $('.wisata')[0].reset();
    }

    $('.simpanData').click(function() {
        const cekNotEmpty = $('input[name=nama]').val() && $('input[name=link]').val();
        if (!cekNotEmpty) {
            Swal.fire("Data tidak boleh kosong!", "", "error");
            return;
        }

        let formData = new FormData($('.wisata')[0]);

        if (wisataSelected) {
            formData.append('id', wisataSelected.id);
        }

        formData.append('nama', $('input[name=nama]').val());
        formData.append('link', $('input[name=link]').val());
        // cek apakah ada file yang diupload
        if ($('input[name=img]')[0].files.length > 0) {
            formData.append('img', $('input[name=img]')[0].files[0]);
            console.log('ada file');
        } else {
            formData.append('img', wisataSelected.img);
            console.log('tidak ada file');
        }

        $.ajax({
            url: wisataSelected ? "/jwd/backend/wisata.php?id=" + wisataSelected.id : "/jwd/backend/wisata.php",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire("Data berhasil disimpan!", "", "success");
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        });
    });

    // This JavaScript
    // function, `editData(id)`, facilitates the editing of data by retrieving the details of a specific item identified by its unique ID.It first searches
    // for the index of the item within the `allDataWisata`
    // array using the provided ID.Once found, it assigns the selected item 's details to the `wisataSelected` variable. 
    // Subsequently, it populates the input fields with the retrieved name and link values, preparing the modal for editing. 
    // Finally, it displays the modal with the id "staticBackdrop" to allow users to modify the selected item's information.
    const editData = (id) => {
        const index = allDataWisata.findIndex(item => item.id == id);
        wisataSelected = allDataWisata[index];

        $('input[name=nama]').val(wisataSelected.nama);
        $('input[name=link]').val(wisataSelected.link);
        // show modal
        $('#staticBackdrop').modal('show');
    }

    // The JavaScript function `deleteData(id)`
    // enables the deletion of a specific data entry identified by its unique ID.It triggers a confirmation dialog using SweetAlert, informing the user about the irreversible nature of the action.
    // If the user confirms deletion, an AJAX DELETE request is sent to the backend endpoint "/jwd/backend/wisata.php?id="
    // appended with the ID of the data to be deleted.Upon successful deletion, a success message is displayed, and the page is reloaded after a brief delay to reflect the changes.
    const deleteData = (id) => {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/jwd/backend/wisata.php?id=" + id,
                    type: 'DELETE',
                    success: function(response) {
                        Swal.fire("Data berhasil dihapus!", "", "success");
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                });
            }
        });
    }
</script>
<?php
$content = ob_get_clean();
require '../../page/components/index.php';
?>