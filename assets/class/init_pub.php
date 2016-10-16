<?php

session_start();

$assets = __DIR__;

require_once "assets/class/db.php";
require_once "assets/class/build.php";

$host = 'localhost';
$db = 'ypantricymraeg';
$username = 'root';
$password = '';

$database = new Database($host, $db, $username, $password);
$build = new Build($database);

?>
