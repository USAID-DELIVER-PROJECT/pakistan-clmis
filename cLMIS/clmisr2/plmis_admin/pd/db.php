<?php

$strUserName = 'root';
$strPassword = '';
$strHost = 'localhost';
$strDatabase = 'clmis';


//connection to the database
$dbhandle = mysql_connect($strHost, $strUserName, $strPassword) or die("Unable to connect to MySQL");

$strDB = mysql_select_db($strDatabase);
if (!$strDB)
    return "Database not found.";
return true;
?>