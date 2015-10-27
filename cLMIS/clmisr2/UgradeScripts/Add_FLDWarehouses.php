<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT
tbl_warehouse.wh_id,
tbl_warehouse.wh_name,
tbl_warehouse.prov_id,
tbl_warehouse.stkid,
tbl_warehouse.locid,
tbl_warehouse.stkofficeid
FROM
tbl_warehouse";

//Redundent dist_id,wh_type_id

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$wh_id=$r['wh_id'];
		$wh_name=$r['wh_name'];
		$prov_id=$r['prov_id'];
		$stkid=$r['stkid'];
		$locid=$r['locid'];
		$stkofficeid="57";

		$strSQL="Insert into tbl_warehouse (wh_name,prov_id,stkid,locid,stkofficeid,dist_id) Values('".$wh_name."',".$prov_id.",".$stkid.",".$locid.",".$stkofficeid.",'".$wh_id."')";
		print "<br>".mysql_query($strSQL) or die(print mysql_error());
	}
?>