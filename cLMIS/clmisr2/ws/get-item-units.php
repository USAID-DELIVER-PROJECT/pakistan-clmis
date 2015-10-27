<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');


$query="SELECT DISTINCT
tbl_itemunits.UnitType as item_unit_name,
tbl_itemunits.pkUnitID as pk_id

FROM
tbl_itemunits
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