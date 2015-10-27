<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT wh_id,dist_id FROM tbl_warehouse where stkofficeid=57";

//dist_id contains district warehouse and wh_id contains Field warehouse

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$Fldwh_id=$r['wh_id'];
		$Diswh_id=$r['dist_id'];
		$strSQL="UPDATE tbl_wh_data SET wh_id=".$Fldwh_id." WHERE WH_ID=".$Diswh_id." AND lvl=4";
		print "<br>".mysql_query($strSQL) or die(print mysql_error());
	}
?>