<?php

$hostname = 'localhost';
$username = 'app';
$password = 'app';
$dbname = 'shop_db';

$conn = mysqli_connect($hostname, $username, $password, $dbname) or die('connection failed');

?>