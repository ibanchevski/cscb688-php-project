<?php

namespace Controllers;

require_once("utils/database.php");

class User {
    private $id;
    private $email;
    private $name;
    
    /**
     * Authenticates user with email and password and returns
     * user id, null if user not found.
     * @param string email
     * @param string password
     */
    public static function authenticate($email, $passwrod) {
        return null;
    }

    public static function register($name, $email, $password) {
        $find_user = $conn->prepare('count users where email=:email', ["email"=>$email]);
        var_dump($conn->query($find_user));
        
    }
}