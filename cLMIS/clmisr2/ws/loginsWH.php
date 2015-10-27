<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');


 $InLgID =  $_REQUEST['ID'];
 

if(!empty($InLgID))
	{
		$EnLgPW=base64_encode($InLgPW); 
		$query="SELECT
				wh_user.wh_id
				FROM
				wh_user
				where sysusrrec_id='$InLgID'";
		$rs = mysql_query($query) or die(print mysql_error());
		$rows = array();
		while($r = mysql_fetch_assoc($rs)) {
			$rows[] = $r;
		}
		print json_encode($rows);
  }
  else
  print "-1";

// http://localhost/lmisn/ws/loginsWH.php?ID=164
?>

