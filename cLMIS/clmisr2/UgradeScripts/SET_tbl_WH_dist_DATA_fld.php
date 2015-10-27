<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
tbl_warehouse where stkofficeid=57";
//print $ID.$YYYY.$MM;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$strSQL="update tbl_warehouse set wh_name = CONCAT(wh_name, ' Field Office') where wh_id='".$r['wh_id']."'";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
	}
	
	
	///Insert into stakeholder(stkcode,stkname) Select wh_type_id as stkcode,wh_desc as stkname from tbl_wh_type
?>