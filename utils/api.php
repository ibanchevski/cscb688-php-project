<?php
session_start();
require_once("../controllers/User.php");
require_once("../controllers/Category.php");

$action = $_POST['action'];

switch ($action) {
case 'login':
    $userid = NULL;

    try {
        $userid = Controllers\User::authenticate($_POST['email'],$_POST['password']);
        $_SESSION['userid'] = $userid;
    } catch (Controllers\UserException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header('location:../index.php');
    break;

case 'register':
    $user = array(
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $_post['password']
    );

    try {
        $userid = Controllers\User::register($user);
        $_SESSION['userid'] = $userid;
    } catch (Controllers\UserException $e) {
        $_SESSION['error'] = $e->getMessage();
        header('location:../register.php');
        break;
    }
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
case 'newEntry':
    $entry = array(
        "amount" => floatval($_POST["amount"]),
        "description" => $_POST["description"],
        "categoryid" => intval($_POST["category"])
    );

    Controllers\Category::addExpense($entry);
    Controllers\User::addExpenses($_SESSION['userid'], $entry["amount"]);

    header('location:../dashboard.php');
    break;
}
