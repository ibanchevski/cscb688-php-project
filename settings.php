<?php
session_start();
require_once('controllers/User.php');

$user = Controllers\User::getById($_SESSION['userid']);
?>
<!DOCTYPE html>
<html>
    <?php $CURR_TITLE = 'Settings'; require_once("head.php"); ?>
    <body>
        <?php require_once("navbar.php"); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-4 offset-lg-4 text-center">
                    <h1 class="page-title">Edit settings</h1>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 offset-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Profile settings</h4>
                            <form method="POST" action="utils/api.php">
                                <input type="hidden" name="action" value="updateUser">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control" name="name" value="<?php echo $user['name'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" value="<?php echo $user['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="password">New password</label>
                                    <input type="password" id="password" class="form-control" name="password" value="">
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 offset-lg-4">
                    <form method="POST" action="utils/api.php">
                        <input type="hidden" name="action" value="deleteAccount">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger shadow-sm"><i class="fas fa-trash-alt"></i>&nbsp; Delete account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
