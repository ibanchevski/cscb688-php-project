<?php

namespace Controllers;

require_once($_SERVER['DOCUMENT_ROOT']."/utils/database.php");
use Utils\DBConnector;

class Category {
    /**
     * Deletes category with all its expenses and returns them
     * @param int categoryid
     * @return array expenses
     */
    public static function delete($categoryId) {
        $conn = DBConnector::getInstance()->getConnection();

        $getExpenses = $conn->prepare('select amount from expenses where categoryid=?');
        $getExpenses->execute([$categoryId]);
        $expenses = $getExpenses->fetchAll(\PDO::FETCH_ASSOC);

        $deleteExpenses = $conn->prepare('delete from expenses where categoryid=?');
        $deleteExpenses->execute([$categoryId]);

        $deleteCategory = $conn->prepare('delete from user_categories where id=?');
        $deleteCategory->execute([$categoryId]);

        return $expenses;
    }

    /**
     * Creates new category with given name
     * @param string categoryName
     * @param int userid
     */
    public static function create($categoryName, $userId) {
        $conn = DBConnector::getInstance()->getConnection();
        $createQuery = $conn->prepare('insert into user_categories(name,userid) values (?,?)');
        $categoryName = htmlspecialchars($categoryName, ENT_QUOTES);
        $createQuery->execute([$categoryName, $userId]);
    }

    /**
     * Renames category
     * @param string newName
     * @param int categoryId
     */
    public static function rename($newName, $categoryId) {
        $conn = DBConnector::getInstance()->getConnection();
        $renameQuery = $conn->prepare('update user_categories set name=? where id=?');
        $newName = htmlspecialchars($newName, ENT_QUOTES);
        $renameQuery->execute([$newName, $categoryId]);
    }

    /**
     * Adds expense to the category
     * @param array expense
     */
    public static function addExpense($expense) {
        $conn = (DBConnector::getInstance())->getConnection();
        $addQuery = $conn->prepare('insert into expenses(amount,description,categoryid,date) values(?,?,?, NOW())');
        $expense['description'] = htmlspecialchars($expense['description'], ENT_QUOTES);
        $addQuery->execute([$expense['amount'], $expense['description'], $expense['categoryid']]);
    }

    /**
     * Deletes expense and returns it
     * @param int expenseId
     * @return array expense
     */
    public static function deleteExpense($expenseId) {
        $conn = (DBConnector::getInstance())->getConnection();

        $find = $conn->prepare('select amount from expenses where id=?');
        $find->execute([$expenseId]);
        $deletedExpense = $find->fetch(\PDO::FETCH_ASSOC);

        $deleteQuery = $conn->prepare('delete from expenses where id=?');
        $deleteQuery->execute([$expenseId]);

        return $deletedExpense;
    }

    /**
     * Get all user's categories with their expenses
     * With optional term search if provided
     * @param int userId
     * @param string search (Optional)
     * @return array categories
     */
    public static function getCategories($userId, $search = "")
    {
        $db = (DBConnector::getInstance())->getConnection();

        $statement = "select user_categories.id, name, expenses.id as entryid, description,amount,date from user_categories left join expenses on categoryid=user_categories.id where userid=?";
        $params = [$userId];

        if ($search != "") {
            $statement .= " and (name like ? or description like ? or amount like ?)";
            $rawSearch = $search;
            $search = '%' . $search . '%';
            $params = [$userId, $search, $search, $rawSearch];
        }

        $getQuery = $db->prepare($statement);
        $getQuery->execute($params);

        $categoriesEntries = $getQuery->fetchAll(\PDO::FETCH_ASSOC);
        $categories = array();

        // Group categories by name and build their expenses objects
        foreach ($categoriesEntries as $entry) {
            if (!isset($categories[$entry["name"]])) {
                $categories[$entry["name"]] = array(
                    "catid" => $entry["id"],
                    "expenses" => []
                );
            }

            if ($entry["entryid"] === NULL) {
                continue;
            }

            $categories[$entry["name"]]["expenses"][] = array(
                "id" => $entry["entryid"],
                "description" => $entry["description"],
                "amount" => $entry["amount"],
                "date" => $entry["date"],
            );
        }

        return $categories;
    }
}
