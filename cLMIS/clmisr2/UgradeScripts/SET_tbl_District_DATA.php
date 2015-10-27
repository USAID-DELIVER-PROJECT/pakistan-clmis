<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
tbl_districts";
//print $ID.$YYYY.$MM;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$d_id=$r['whrec_id'];
		$d_id=(int) substr($d_id,strrpos($d_id, "-")+1,strlen($d_id)-strrpos($d_id, "-"));
		$strSQL="update tbl_districts set dist_id=$d_id where whrec_id='".$r['whrec_id']."'";
		print "<p>".mysql_query($strSQL) or die(print mysql_error())."</p>";
	}
?>