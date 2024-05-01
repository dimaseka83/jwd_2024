<?php
session_start();
include_once('../../backend/koneksi.php');

// create new database object
$database = new Database();
$db = $database->getConnection();

$error = '';

// check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // verify password using password_verify
    $sql = "SELECT * FROM tb_user WHERE username = '$username'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            if ($row['role'] == 'admin') {
                header('Location: /jwd/page/admin/pesanan.php');
            } else {
                header('Location: /jwd/page/customer/form.php');
            }
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Username tidak ditemukan";
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
                        <?php 
                        if(!empty($error)){
                            echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                        }
                        ?>
                        <h3 class="text-center">Login</h3>
                        <form action="login.php" method="POST">
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
                                    Belum punya akun? <a href="/jwd/page/auth/register.php">Register</a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-lg btn-primary">Login</button>
                                </div>
                            </div>
                        </form>
                        <!-- Akhir Tombol Register -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>