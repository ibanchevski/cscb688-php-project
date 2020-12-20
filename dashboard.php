<?php
session_start();
require_once("utils/database.php");
$userid = $_SESSION['userid'];
$q = $db->query("select email,name from users where id=$userid");
$username = $q->fetch_assoc()['name'];
?>

<!DOCTYPE html>
<html lang="en">
<?php
$CURR_TITLE = 'Dashboard';
require_once('head.php');
?>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm">
        <?php
        echo "<h4>Hello, $username</h4>";
        ?>
      </div>
    </div>
  </div>
</body>
</html>
