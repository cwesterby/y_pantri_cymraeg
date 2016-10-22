<?php

session_start();

$assets = __DIR__;

require_once "assets/class/db.php";
require_once "assets/class/build.php";

if (file_exists('assets/local/login.php')) {
  require_once "assets/local/login.php";
} else {
  require_once "assets/class/login.php";
}



$database = new Database($host, $db, $username, $password);
$build = new Build($database);

?>
