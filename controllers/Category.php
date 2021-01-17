<?php

namespace Controllers;

require_once($_SERVER['DOCUMENT_ROOT']."/utils/database.php");
use Utils\DBConnector;

class Category {
    public static function delete($categoryId) {
        $conn = DBConnector::getInstance()->getConnection();
        $deleteQuery = $conn->prepare('delete from user_categories where id=?');
        $deleteQuery->execute([$categoryId]);
    }

    public static function create($categoryName, $userId) {
        $conn = DBConnector::getInstance()->getConnection();
        $createQuery = $conn->prepare('insert into user_categories(name,userid) values (?,?)');
        $createQuery->execute([$categoryName, $userId]);
    }

    public static function rename($newName, $categoryId) {
        $conn = DBConnector::getInstance()->getConnection();
        $renameQuery = $conn->prepare('update user_categories set name=? where id=?');
        $renameQuery->execute([$newName, $categoryId]);
    }

    public static function addExpense($expense) {
        $conn = (DBConnector::getInstance())->getConnection();
        $addQuery = $conn->prepare('insert into expenses(amount,description,categoryid,date) values(?,?,?, NOW())');
        $addQuery->execute([$expense['amount'], $expense['description'], $expense['categoryid']]);
    }
}
