<?php
session_start();
include_once('../../backend/koneksi.php');

// create new database object
$database = new Database();
$db = $database->getConnection();

// check if form is submitted

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // verify password using password_verify
    $sql = "INSERT INTO tb_user (username, password, role) VALUES ('$username', '$password', 'customer')";
    $result = $db->query($sql);
    if ($result) {
        echo "Register berhasil";
    } else {
        echo "Register gagal";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css" />
</head>

<body>
    <!-- change to center login -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center">Register</h3>
                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    Sudah punya akun? <a href="/jwd/page/auth/login.php">Login</a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-lg btn-primary">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>