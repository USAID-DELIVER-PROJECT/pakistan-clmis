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
		$d_id=$r['sysusr_name'];
		if(strrpos($d_id, " ")>0) 
		{
			
		
		//$d_id=substr($d_id,strrpos($d_id, "DPWO ")+1,strlen($d_id)-strrpos($d_id, " "));
		
		$d_id=str_replace("DPWO ","",$d_id);
		$strSQL="update sysuser_tab set sysusr_name='$d_id' where sysusrrec_id='".$r['sysusrrec_id']."'";
		//print $strSQL;
		print "<p>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		}
	}
?>