<?php
require_once('controllers/User.php');

// Note: This file is always included and there
// will always be an active session.
$user = Controllers\User::getById($_SESSION['userid']);
?>
<nav class="navbar navbar-expand-lg navbar-dark mb-5" style="background-color: #4c85f2;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Expenses manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="/dashboard.php" class="nav-link"><i class="fas fa-home"></i>&nbsp;Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/settings.php"><i class="fas fa-user-cog"></i>&nbsp;Settings</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-lg-auto mb-2 mb-lg-0">
                <li class="nav-item me-4">
                    <a class="nav-link active" aria-current="page" href="#">
                        <i class="fas fa-user-alt"></i>&nbsp;<?php echo $user['email'] ?>
                    </a>
                </li>
                <li class="nav-item">
                    <button type="button" class="btn btn-light shadow-sm" id="logoutBtn">
                        <i class="fas fa-sign-out-alt"></i>
                        Log out
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
