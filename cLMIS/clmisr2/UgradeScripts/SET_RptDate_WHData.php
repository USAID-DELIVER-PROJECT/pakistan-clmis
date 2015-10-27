<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
tbl_wh_data";

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$d_m=$r['report_month'];
		$d_y=$r['report_year'];
		$datestr=$d_y."-".$d_m."-1";
		
		$strSQL="update tbl_wh_data set RptDate='$datestr' where w_id='".$r['w_id']."'";
		//print $strSQL;
		print "<br />".mysql_query($strSQL) or die(print mysql_error());
	}
?>