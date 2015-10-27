<?php
include_once("DBCon.php");          // Include Database Connection File

$ID =  $_REQUEST['ID'];

$query="Select PkLocID, LocName,ParentID,LocType,LocLvl  From tbl_locations";

if(!empty($ID))
{
	$query=$query." WHERE PkLocID ='$ID'";
}

$query .= " ORDER BY LocName";
//print $query;
$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);

// example: http://localhost/lmis/ws/locations.php?ID=4
?>