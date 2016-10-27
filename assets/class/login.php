<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$host = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

// $host = 'us-cdbr-iron-east-04.cleardb.net';
// $db = 'heroku_6d45d6c1715d1f6';
// $username = 'bf992f4c41b84b';
// $password = '1afd34fa';

?>
