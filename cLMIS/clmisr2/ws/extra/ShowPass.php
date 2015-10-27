<?php
 include_once("../../plmis_inc/common/CnnDb.php");          // Include Database Connection File
 include_once("../../plmis_inc/common/FunctionLib.php");    // Include Global Function File
include('auth.php');

 $InLgID =  $_REQUEST['ID'];


if(!empty($InLgID))
	{	
echo  $InLgID;
		$query="select sysusr_pwd
				from sysuser_tab 
				where usrlogin_id='$InLgID' 				  
				  and sysusr_status='Active'";
		$rs = mysql_query($query) or die(print mysql_error());
		
		$r = mysql_fetch_assoc($rs);
		print base64_decode($r['sysusr_pwd']);
  }
  else
  print "-1";

// http://localhost/lmis/ws/login.php?ID=DPIU_Barkhan&PW=123
?>

