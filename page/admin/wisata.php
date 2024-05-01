<?php
session_start();
// proteksi session jika user belum login
if (!isset($_SESSION['role'])) {
    header('Location: /jwd');
}

if ($_SESSION['role'] != 'admin') {
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
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js
"></script>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css
" rel="stylesheet" />
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/jwd/page/admin/pesanan.php">Daftar Pesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Daftar Pariwisata</a>
                    </li>
                </ul>
                <a class="nav-link" href="../../backend/controller/logout.php">
                    Logout
                </a>
            </div>
        </div>
    </nav>


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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary simpanData">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // variable
        let allDataWisata = [];
        let wisataSelected = null;

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

        // function tambah data

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

        // function edit data and show modal
        const editData = (id) => {
            const index = allDataWisata.findIndex(item => item.id == id);
            wisataSelected = allDataWisata[index];

            $('input[name=nama]').val(wisataSelected.nama);
            $('input[name=link]').val(wisataSelected.link);
            // show modal
            $('#staticBackdrop').modal('show');
        }

        // function delete data from database
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

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>