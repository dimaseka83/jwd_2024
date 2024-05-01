<!-- This PHP script handles user authentication by processing form data, querying the database for the user's credentials, and verifying the provided password against the hashed password stored in the database.
 If the username is found and the password matches, the user's session is initialized with the username and role, redirecting them to their respective dashboard based on their role (admin or customer).
  Error messages are generated if the username is not found or if the password is incorrect, providing feedback to the user. Additionally, output buffering is initiated to manage output effectively. 
Overall, this script encapsulates the authentication process, ensuring secure access to different sections of the website based on user roles. -->
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

ob_start();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <?php
                    if (!empty($error)) {
                        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
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
<?php
$content = ob_get_clean();
require '../../page/components/index.php';
?>