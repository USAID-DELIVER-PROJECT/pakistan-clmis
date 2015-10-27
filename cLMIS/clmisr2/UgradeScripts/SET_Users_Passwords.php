<?php

//echo base64_decode('UVdSdGFXNXA=');

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File


$query="SELECT UserID, usrlogin_id FROM sysuser_tab";

//dist_id contains district warehouse and wh_id contains Field warehouse

	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$NewPass=substr(base64_encode($r['usrlogin_id']),0,8) ;
		$NewPass1=base64_encode($NewPass);
		$U_id=$r['UserID'];

		
		$strSQL="Update sysuser_tab SET sysusr_pwd='".$NewPass1."', extra='".$NewPass."' where UserID=".$U_id;
		print $strSQL."<br />";
		mysql_query($strSQL) or die(print mysql_error());


		
	}
?>