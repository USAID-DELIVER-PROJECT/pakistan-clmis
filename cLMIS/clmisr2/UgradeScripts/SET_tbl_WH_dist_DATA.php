<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
tbl_warehouse";
//print $ID.$YYYY.$MM;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$d_id=$r['dist_id'];
		$d_id=(int) substr($d_id,strrpos($d_id, "-")+1,strlen($d_id)-strrpos($d_id, "-"));
		$strSQL="update tbl_warehouse set locid=$d_id+10 where wh_id='".$r['wh_id']."'";
		print "<p>".mysql_query($strSQL) or die(print mysql_error())."</p>";
	}
	
	
	///Insert into stakeholder(stkcode,stkname) Select wh_type_id as stkcode,wh_desc as stkname from tbl_wh_type
?>