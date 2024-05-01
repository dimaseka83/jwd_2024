<!-- This PHP code segment initiates user registration functionality by starting a session and establishing a database connection.
It verifies the form submission, retrieves user input, and securely hashes the password before inserting user details into the database with a defined role. 
Error handling is implemented to manage registration success or failure, updating the `$error` variable accordingly. Finally, output buffering is enabled to streamline output management. 
In essence, this script encapsulates the entire process of user registration, ensuring secure storage of user credentials and providing clear feedback to the user upon registration completion or failure. -->
<?php
session_start();
include_once('../../backend/koneksi.php');

// create new database object
$database = new Database();
$db = $database->getConnection();

// check if form is submitted
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // verify password using password_verify
    $sql = "INSERT INTO tb_user (username, password, role) VALUES ('$username', '$password', 'customer')";
    $result = $db->query($sql);
    if ($result) {
        $error = 'Register berhasil';
        header('Location: /jwd/page/auth/login.php');
    } else {
        $error = 'Register gagal';
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
<?php
$content = ob_get_clean();
require '../../page/components/index.php';
?>