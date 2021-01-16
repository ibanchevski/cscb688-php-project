<?php
session_start();
require_once("../controllers/User.php");

$action = $_POST['action'];
echo $action;

switch ($action) {
case 'login':
    $userid = Controllers\User::authenticate($_POST['email'],$_POST['password']);

    if ($userid === null) {
        $_SESSION['errormsg'] = 'Invalid email or password!';
        header('location:../index.php');
    } else {
        $_SESSION['userid'] = $userid;
        header('location:../dashboard.php');
    }

    break;
case 'register':
    $user = array(
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $_post['password']
    );
    $userid = Controllers\User::register($user);
    $_SESSION['userid'] = $userid;
    header('location:../dashboard.php');

    break;
case 'updateUser':
    $user = array(
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $_post['password']
    );
    Controllers\User::update($_SESSION['userid'], $user);
    header('location:../settings.php');
    break;
}
