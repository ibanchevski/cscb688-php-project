<?php

namespace Controllers;

require_once("../utils/database.php");
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

    public static function getCategories($userid) {
        $db = (new DBConnector())->getConnection();
        $getQuery = $db->prepare("select id,name from user_categories where userid=?");
        $getQuery->execute([$userid]);

        $categories = $getQuery->fetch(\PDO::FETCH_ASSOC);

        if (!$findUserQuery) {
            // TODO: Throw exception
            return [];
        }

        if (!password_verify($password, $user['password'])) {
            // TODO: Throw exception
            return [];
        }

        return $categories;
    }
}
