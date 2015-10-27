<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$strSQL="delete from wh_user";
print mysql_query($strSQL) or die(print mysql_error());

$query="SELECT sysuser_tab.whrec_id,sysuser_tab.UserID, tbl_warehouse.wh_id FROM sysuser_tab Inner Join tbl_warehouse ON tbl_warehouse.dist_id = sysuser_tab.whrec_id";

//dist_id contains district warehouse and wh_id contains Field warehouse

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$Fldwh_id=$r['wh_id'];
		$Diswh_id=$r['whrec_id'];
		$U_id=$r['UserID'];

		
		$strSQL="INSERT INTO wh_user(sysusrrec_id,wh_id) VALUES (".$U_id.",".$Diswh_id.")";
		print mysql_query($strSQL) or die(print mysql_error());

		$strSQL1="INSERT INTO wh_user(sysusrrec_id,wh_id) VALUES (".$U_id.",".$Fldwh_id.")";
		print "-".mysql_query($strSQL1) or die(print mysql_error())."<br>";

		
	}
?>