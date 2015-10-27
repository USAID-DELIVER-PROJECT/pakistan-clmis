<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');


$query="SELECT
itminfo_tab.itm_id as pk_id,
itminfo_tab.itm_name as item_name,
itminfo_tab.itm_type as units,
itminfo_tab.qty_carton,
itminfo_tab.itm_des as description,
itminfo_tab.itm_status as status,
itminfo_tab.frmindex as list_rank,
'2' as item_category,
itminfo_tab.itm_id as item_id
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