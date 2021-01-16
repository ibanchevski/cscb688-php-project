<?php

namespace Controllers;

require_once($_SERVER['DOCUMENT_ROOT']."/utils/database.php");
use Utils\DBConnector;

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
            // TODO: Throw exception
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            // TODO: Throw exception
            return null;
        }

        return $user['id'];
    }

    public static function register($user) {
        // TODO: Check if user exits
        $db = (DBConnector::getInstance())->getConnection();
        $insertUser = $db->prepare("insert into users(name, email, password) values (?,?,?)");
        $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
        $insertUser->execute([$user['name'], $user['email'], $hashedPassword]);

        return $db->lastInsertId();
    }

    public static function getById($id) {
        $db = (DBConnector::getInstance())->getConnection();

        $findUserQuery = $db->prepare("select name,email from users where id=?");
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

    public static function getCategories($userId) {
        $db = (DBConnector::getInstance())->getConnection();
        $getQuery = $db->prepare("select id,name from user_categories where userid=?");
        $getQuery->execute([$userId]);

        $categories = $getQuery->fetchAll(\PDO::FETCH_ASSOC);

        return $categories;
    }

    public static function update($userId, $newUser) {
        $db = (DBConnector::getInstance())->getConnection();
        $updateQ = $db->prepare("update users set name=?,email=? where id=?");
        $updateQ->execute([$newUser['name'], $newUser['email'], $userId]);
    }
}
