<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
sysuser_tab";

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$d_id=$r['sysusrrec_id'];
		$d_id=(int) substr($d_id,strrpos($d_id, "-")+1,strlen($d_id)-strrpos($d_id, "-"));
		$strSQL="update sysuser_tab set UserID=$d_id where sysusrrec_id='".$r['sysusrrec_id']."'";
		print "<p>".mysql_query($strSQL) or die(print mysql_error())."</p>";
	}
?>