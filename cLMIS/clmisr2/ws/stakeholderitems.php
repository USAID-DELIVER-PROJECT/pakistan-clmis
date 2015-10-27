<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$ID =  $_REQUEST['ID'];

$query="SELECT stk_id,stkid,stk_item FROM stakeholder_item";

if(!empty($ID))
{
	$query=$query." WHERE stkid ='$ID'";
}

//print $query;

$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);

// http://localhost/lmis/ws/stakeholderitems.php?ID=9
?>