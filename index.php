<?php
session_start();
require_once('utils/database.php');

if (isset($_POST) && count($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $findUserQuery = $db->query("select id,email,password from users where email='$email'");

    $foundUser = $findUserQuery->fetch_array();

    if ($foundUser['email'] === $email) {
        if (password_verify($password, $foundUser['password'])) {
            $_SESSION['userid'] = $foundUser['id'];
            header('location:dashboard.php');
        } else {
            $_SESSION['errormsg'] = 'Invalid email or password!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$CURR_TITLE = 'Login';
require_once('./head.php');
?>
    <body>
        <div class="container d-flex flex-column justify-content-center" style="height: 100vh">
            <div class="row">
                <div class="col-sm-4 offset-lg-4">
                    <?php

                    if (isset($_SESSION['errormsg'])) {
                        echo "<div class='alert alert-danger'>" . $_SESSION['errormsg'] . "</div>";
                        $_SESSION['errormsg'] = null;
                    }

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 offset-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="card-title text-center">
                                <h2>Login</h2>
                            </div>
                            <form name="login" method="POST">
                                <input type="hidden" name="auth_type" value="login">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted"><i class="fas fa-at"></i></span>
                                        <input type="email" class="form-control" name="email" id="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="rememberMe" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <div class="d-grid gap-2 col-12 mx-auto mb-3">
                                    <button type="submit" class="btn btn-block btn-lg btn-primary">Login&nbsp;<i class="fas fa-sign-in-alt"></i></button>
                                </div>
                                <div>
                                    <p class="text-muted text-center mb-0">Do not have an account? <a class="link-secondary" href="register.php">Register</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
