<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$wh_id=$_GET['wh_id'];

$query="SELECT DISTINCT
itminfo_tab.itm_id as pkId,
itminfo_tab.itm_name as description
FROM
itminfo_tab
";

$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);

// example: http://localhost/lmis/ws/locations.php?ID=4
?>