<?php

namespace Controllers;

require_once($_SERVER['DOCUMENT_ROOT']."/utils/database.php");
use Utils\DBConnector;

class UserException extends \Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class User {
    private $id;
    private $email;
    private $name;

    /**
     * Authenticates user with email and password and returns
     * user id.
     * @param string email
     * @param string password
     * @return string id User id
     */
    public static function authenticate($email, $password) {
        $db = (DBConnector::getInstance())->getConnection();
        $findUserQuery = $db->prepare("select id,email,password from users where email=?");
        $findUserQuery->execute([$email]);

        $user = $findUserQuery->fetch(\PDO::FETCH_ASSOC);

        if (!$findUserQuery) {
            throw new UserException("Invalid email or password!");
        }

        if (!password_verify($password, $user['password'])) {
            throw new UserException("Invalid email or password!");
        }

        return $user['id'];
    }

    public static function register($user) {
        $db = (DBConnector::getInstance())->getConnection();

        $getUser = $db->prepare("select email from users where email=? limit 1");
        $getUser->execute([$user["email"]]);

        if (count($getUser->fetchAll()) > 0) {
            throw new UserException("User with this email already exists!");
        }
        
        $insertUser = $db->prepare("insert into users(name, email, password) values (?,?,?)");
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
        $insertUser->execute([$user['name'], $user['email'], $hashedPassword]);

        return $db->lastInsertId();
    }

    public static function getById($id) {
        $db = (DBConnector::getInstance())->getConnection();

        $findUserQuery = $db->prepare("select name,email,total_expenses from users where id=?");
        $findUserQuery->execute([$id]);

        $user = $findUserQuery->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }


    public static function validate($userId) {
        $db = (DBConnector::getInstance())->getConnection();
        $existQuery = $db->prepare('select exists(select id from users where id=? limit 1) as "user_exists"');
        $existQuery->execute([$userId]);

        if ($existQuery->fetch(\PDO::FETCH_ASSOC)['user_exists']) {
            return 'valid';
        }
        return 'invalid';
    }

    public static function getCategories($userId, $search="") {
        $db = (DBConnector::getInstance())->getConnection();

        $statement = "select user_categories.id, name, expenses.id as entryid, description,amount,date from user_categories left join expenses on categoryid=user_categories.id where userid=?";
        $params = [$userId];

        if ($search != "") {
            $statement .= " and (name like ? or description like ? or amount like ?)";
            $rawSearch = $search;
            $search = '%'.$search.'%';
            $params = [$userId, $search, $search, $rawSearch];
        }

        $getQuery = $db->prepare($statement);
        $getQuery->execute($params);

        $categoriesEntries = $getQuery->fetchAll(\PDO::FETCH_ASSOC);
        $categories = array();

        foreach ($categoriesEntries as $entry) {
            if (!isset($categories[$entry["name"]])) {
                $categories[$entry["name"]] = array(
                    "catid" => $entry["id"],
                    "expenses" => []
                );
            }

            if ($entry["entryid"] === NULL) { continue; }

            $categories[$entry["name"]]["expenses"][] = array (
                "id" => $entry["entryid"],
                "description" => $entry["description"],
                "amount" => $entry["amount"],
                "date" => $entry["date"],
            );
        }

        return $categories;
    }

    public static function update($userId, $newUser) {
        $db = (DBConnector::getInstance())->getConnection();
        $updateStm = "update users set name=?,email=?";
        $params = [$newUser['name'], $newUser['email']];

        if ($newUser["password"] !== "" && $newUser["password"] !== " ") {
            $newUserPassword = password_hash($newUser['password'], PASSWORD_BCRYPT);
            $updateStm .= ",password = ?";
            $params[] = $newUserPassword;
        }

        $updateStm.= " where id=?";
        $params[] = $userId;

        $updateQ = $db->prepare($updateStm);
        $updateQ->execute($params);
    }

    public function addExpenses($userId, $amount) {
        $db = (DBConnector::getInstance())->getConnection();
        $updateQ = $db->prepare("update users set total_expenses = total_expenses + ? where id=?");
        $updateQ->execute([$amount, $userId]);
    }
}
