<?php
session_start();
require_once('utils/database.php');
if (isset($_POST) && count($_POST)) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $name = $_POST['name'];

  $cnt = $db->query("select count(id) as userscnt from users where email='$email'");
  $cnt = (int)$cnt->fetch_array()['userscnt'];

  if ($cnt !== 0) {
    $error = 'User already exists!';
  } else {
      $password = password_hash($password, PASSWORD_BCRYPT);
      $db->query("insert into users(name, email, password) values('$name','$email','$password')");
      $_SESSION['userid'] = $db->insert_id;
      header('location:dashboard.php');
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
  $CURR_TITLE = 'Register';
  require_once("./head.php");
?>

<body>
  <div class="container d-flex flex-column justify-content-center" style="height: 100vh">
    <div class="row">
      <div class="col-sm-4 offset-lg-4">
        <?php
          if (isset($error)) {
          echo "<div class='alert alert-danger'>$error</div>";
          }
        ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 offset-lg-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="card-title text-center">
              <a href="/" class="btn btn-sm btn-outline-secondary float-start"><i class="fas fa-arrow-left"></i></a>
              <h2>Register</h2>
            </div>
            <form name="register" method="POST">
              <div class="mb-3">
                <label for="name">Full name</label>
                <input type="text" class="form-control" name="name" id="name">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password">
              </div>
              <div class="mb-3">
                <label for="rpassword">Repeat password</label>
                <input type="password" class="form-control" id="rpassword">
              </div>
              <div class="d-grid gap-2 col-12 mx-auto">
                <button type="submit" class="btn btn-block btn-lg btn-primary">Register</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
