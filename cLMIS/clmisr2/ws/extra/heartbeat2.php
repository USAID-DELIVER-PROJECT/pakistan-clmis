<?php
include("../plmis_inc/common/CnnDb.php");   //Include Database Connection File
include('../auth.php');

$ID =  $_REQUEST['ID'];

 function getProdName($ID){
	$query = "SELECT itm_name FROM itminfo_tab Where itm_id=".$ID;
	$rs = mysql_query($query) or die(mysql_error());
	return $rs;

}

$result=getProdName($ID);
$row = mysql_fetch_array($result);
echo $row[0];


//echo $ID;
?>