<?php
error_reporting(0);

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'ccem_test';
$conn = mysql_connect($host, $user, $pass) or die(mysql_error());
mysql_select_db($db, $conn);