<?php
include_once("DBCon.php");          // Include Database Connection File

$ID =  $_REQUEST['ID'];

$query="SELECT
			tbl_warehouse.wh_id,
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_type_id,
			tbl_warehouse.dist_id,
			tbl_warehouse.prov_id,
			tbl_warehouse.stkid,
			tbl_warehouse.locid,
			tbl_warehouse.stkofficeid,
			stakeholder.stkname
		FROM
			tbl_warehouse
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid";

if(!empty($ID))
{
	$query=$query." WHERE wh_id ='$ID'";
}
$query .= " ORDER BY tbl_warehouse.wh_name";
// AND tbl_warehouse.prov_id = 7
//print $query;
$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);

// example: http://localhost/lmis/ws/warehouse.php?ID=4
?>