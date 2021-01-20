<?php

namespace Controllers;

require_once($_SERVER['DOCUMENT_ROOT']."/utils/database.php");
use Utils\DBConnector;

class UserException extends \Exception {
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class User {
    /**
     * Authenticates user with email and password
     * and returns user id.
     * @param string email
     * @param string password
     * @return string id User's id
     * @throws UserException
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

    /**
     * Creates new user with given name, email, password
     * @param array user Associative array containing: name,email,password
     * @return string id User's id
     * @throws UserException
     */
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

    /**
     * Finds user by given user id
     * @param int id
     * @return array user
     */
    public static function getById($id) {
        $db = (DBConnector::getInstance())->getConnection();

        $findUserQuery = $db->prepare("select name,email,total_expenses from users where id=?");
        $findUserQuery->execute([$id]);

        $user = $findUserQuery->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Validates user id
     * @param int id User's id
     * @return string [valid | invalid]
     */
    public static function validate($userId) {
        $db = (DBConnector::getInstance())->getConnection();
        $existQuery = $db->prepare('select exists(select id from users where id=? limit 1) as "user_exists"');
        $existQuery->execute([$userId]);

        if ($existQuery->fetch(\PDO::FETCH_ASSOC)['user_exists']) {
            return 'valid';
        }
        return 'invalid';
    }


    /**
     * Updates user with provided user object
     * @param int userid
     * @param array newUser Assoc. array with the new user datata
     */
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

    /**
     * Adds expenses to the user's total expenses
     * @param int userid
     * @param float amount
     */
    public static function addExpenses($userId, $amount) {
        $db = (DBConnector::getInstance())->getConnection();
        $updateQ = $db->prepare("update users set total_expenses = total_expenses + ? where id=?");
        $updateQ->execute([$amount, $userId]);
    }

    /**
     * Deletes the user account and returns their categories
     * @param int userid
     * @return array categories
     */
    public function delete($userid) {
        $db = (DBConnector::getInstance())->getConnection();
        $getCategories = $db->prepare("select id from user_categories where userid=?");
        $getCategories->execute([$userid]);
        $categories = $getCategories->fetchAll(\PDO::FETCH_ASSOC);

        $deleteUser = $db->prepare("delete from users where id=?");
        $deleteUser->execute([$userid]);

        return $categories;
    }
}
