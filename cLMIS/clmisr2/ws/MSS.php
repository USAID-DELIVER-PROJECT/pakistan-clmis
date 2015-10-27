<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

// $InLgID =  $_REQUEST['ID'];
 

//if(!empty($InLgID))
	{
		//$EnLgPW=base64_encode($InLgPW); 
		$query="SELECT sysuser_tab.sysusr_pwd,sysuser_tab.usrlogin_id
				FROM sysuser_tab WHERE sysuser_tab.stkid = 6 order by sysuser_tab.usrlogin_id";
		$rs = mysql_query($query) or die(print mysql_error());
		$i=1;
		while($r = mysql_fetch_array($rs)) 
		{
			//$rows=$r;
			print $i++." ".$r[1]."        ".base64_decode($r[0])."<br>"; 
		}
  }
  /*else
  print "-1";*/

// http://localhost/lmisn/ws/loginsWH.php?ID=164
?>

