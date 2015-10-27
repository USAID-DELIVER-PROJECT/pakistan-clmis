<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$ID =  $_REQUEST['ID'];

$query="SELECT itmrec_id,itm_id,itm_name,itm_type,itm_category, frmindex FROM itminfo_tab";

if(!empty($ID))
{
	$query=$query." WHERE itm_id in ($ID)";
}
$query=$query." ORDER BY itm_name";
//print $query;

$rs = mysql_query($query) or die(print mysql_error());
$rows = array();

while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}

print json_encode($rows);

//http://localhost/lmis/ws/items.php?ID=1,2,3;

?>