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
    if ( $_POST["password"] !== $_POST["rpassword"] ) {
        $_SESSION['error'] = "Passwords do not match!";
        header('location:../register.php');
        break;
    }

    $user = array(
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => $_POST['password']
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
        "password" => $_POST['password']
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

case 'deleteAccount':
    $userCategories = Controllers\User::delete($_SESSION['userid']);

    foreach ($userCategories as $category) {
        Controllers\Category::delete($category["id"]);
    }

    header('location:../index.php');
    break;

case 'newCategory':
    Controllers\Category::create($_POST['newCategory'], $_SESSION['userid']);
    header('location:../dashboard.php');
    break;
case 'newCategoryName':
    Controllers\Category::rename($_POST['newCategoryName'], $_POST['categoryId']);
    header('location:../dashboard.php');
    break;
case 'deleteExpense':
    $deletedExpense = Controllers\Category::deleteExpense($_POST["deleteExpense"]);
    $expenseAmount = floatval($deletedExpense["amount"]) * -1;
    Controllers\User::addExpenses($_SESSION["userid"], $expenseAmount);
    header('location:../dashboard.php');
    break;
case 'deleteCategory':
    $categoryExpenses = Controllers\Category::delete($_POST['deleteCategory']);
    $total = 0;

    foreach ($categoryExpenses as $expense) {
        $total += floatval($expense["amount"]);
    }

    $total *= -1;
    Controllers\User::addExpenses($_SESSION["userid"], $total);
    
    header('location:../dashboard.php');
    break;
}
