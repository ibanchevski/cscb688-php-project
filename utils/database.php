<?php
namespace Utils;
require_once('config.php');

class DBConnector {
    private static $instance = null;
    private $conn = null;

    public function __construct() {
        global $DB_CONFIG;

        $host = $DB_CONFIG['host'];
        $port = $DB_CONFIG['port'];
        $db   = $DB_CONFIG['dbname'];
        $user = $DB_CONFIG['user'];
        $pass = $DB_CONFIG['password'];

        try {
            $this->conn = new \PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        } catch (\PDOException $e) {
            exit ($e->getMessage());
        }
    }


    public function getConnection() {
        return $this->conn;
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DBConnector();
        }
        return self::$instance;
    }
}
