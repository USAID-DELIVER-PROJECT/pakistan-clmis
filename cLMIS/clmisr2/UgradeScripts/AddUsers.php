<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$strSQL="delete from sysuser_tab";
print mysql_query($strSQL) or die(print mysql_error());

$strSQL0="INSERT INTO sysuser_tab(sysusr_type,whrec_id,usrlogin_id,sysusr_pwd,sysusr_name,sysusr_email,sysusr_status,stkid,province) 
		VALUES ('UT-001',0,'Administrator','MTIz','None','abc@abc.com','Active',1,1)";
		print $strSQL0;
		mysql_query($strSQL0) or die(print mysql_error());
		
		
$strSQL="delete from wh_user";
print mysql_query($strSQL) or die(print mysql_error());


//getting district users
$query="SELECT wh_id,wh_name,dist_id,prov_id,stkid FROM tbl_warehouse where stkofficeid=56";
print $query;
	$rs = mysql_query($query) or die(print mysql_error());

	while($r = mysql_fetch_assoc($rs))
	{
		$Diswh_id=$r['wh_id'];
		$wh_name=$r['wh_name'];
		$dist_id=$r['dist_id'];
		$prov_id=$r['prov_id'];
		$stkid=$r['stkid'];
		
		//adding District User
		$strSQL0="INSERT INTO sysuser_tab(sysusr_type,whrec_id,usrlogin_id,sysusr_pwd,sysusr_name,sysusr_email,sysusr_status,stkid,province) 
		VALUES ('UT-002',".$Diswh_id.",'".$wh_name."','MTIz','".$wh_name."','abc@abc.com','Active',".$stkid.",".$prov_id.")";
		print $strSQL0;
		mysql_query($strSQL0) or die(print mysql_error());
		
		$query="SELECT UserID FROM sysuser_tab where usrlogin_id='".$wh_name."'";
		print $query;
		$rs0 = mysql_query($query) or die(print mysql_error());
		while($r0 = mysql_fetch_assoc($rs0))
		{
			$U_id=$r0['UserID'];
		}

		//adding District WarehouseUser linkage
		$strSQL="INSERT INTO wh_user(sysusrrec_id,wh_id) VALUES (".$U_id.",".$Diswh_id.")";
		print $strSQL;
		mysql_query($strSQL) or die(print mysql_error());

		$query1="SELECT wh_id,wh_name FROM tbl_warehouse where dist_id='".$r['dist_id']."' and stkofficeid=57";
		print $query1; 
		$rs1 = mysql_query($query1) or die(print mysql_error());
		//$rows1 = array();
		while($r1 = mysql_fetch_assoc($rs1))
		{
		//adding Field WarehouseUser linkage
			$Fldwh_id=$r1['wh_id'];
			$strSQL1="INSERT INTO wh_user(sysusrrec_id,wh_id) VALUES (".$U_id.",".$Fldwh_id.")";
			print $strSQL1;
			mysql_query($strSQL1) or die(print mysql_error())."<br>";
		}	
	}
?>