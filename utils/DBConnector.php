<?php

include "config.php";

$host = $DB_CONFIG['host'];
$db   = $DB_CONFIG['dbname'];
$user = $DB_CONFIG['user'];
$pass = $DB_CONFIG['password'];

$conn = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);

