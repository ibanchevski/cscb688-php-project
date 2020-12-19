<?php
require_once('config.php');
$db = new mysqli($DB_CONFIG['host'], $DB_CONFIG['user'], $DB_CONFIG['password'], $DB_CONFIG['dbname']);

if(!$db) {
	die("Database connection error");
};
$db->set_charset("UTF8");
