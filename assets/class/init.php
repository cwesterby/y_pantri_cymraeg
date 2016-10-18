<?php

session_start();

$assets = __DIR__;

require_once "assets/class/db.php";
require_once "assets/class/build.php";

if (file_exists('assets/local/local_login.php')) {
  require_once "assets/local/local_login.php";
} else {
  require_once "assets/class/login.php";
}



$database = new Database($host, $db, $username, $password);
$build = new Build($database);

?>
