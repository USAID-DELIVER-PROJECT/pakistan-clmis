<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

function getProdName(){
	$query = "SELECT itm_name FROM itminfo_tab LIMIT 1";
	$rs = mysql_query($query) or die(mysql_error());
	return $rs;
}

$result=getProdName();
$row = mysql_fetch_array($result);
if (strlen($row[0]) > 0)
echo 1;
else
echo 0;
?>