<?php
session_start();

if (isset($_POST) && count($_POST)) {
  echo "Login!!!";
  var_dump($_POST);
  header('location:register.php');
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
                  <input type="email" class="form-control" name="email" id="email">
                </div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text text-muted"><i class="fas fa-key"></i></span>
                  <input type="password" class="form-control" name="password" id="password">
                </div>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="rememberMe" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
              </div>
              <div class="mb-3">
                <p class="text-muted">Don't have an account? <a class="link-secondary" href="register.php">Register</a></p>
              </div>
              <div class="d-grid gap-2 col-8 mx-auto">
                <button type="submit" class="btn btn-block btn-primary">Login&nbsp;<i class="fas fa-sign-in-alt"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>