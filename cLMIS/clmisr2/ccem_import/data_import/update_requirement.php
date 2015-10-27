<?php
include('../config.php');

$qryRes = mysql_query("SELECT DISTINCT
	cold_chain.warehouse_id
FROM
	cold_chain
WHERE
	cold_chain.warehouse_id IS NOT NULL");
	
while ( $row = mysql_fetch_array($qryRes) )
{
	mysql_query("SELECT REPUpdateRequirement($row[warehouse_id]) FROM DUAL");
}
echo "Warehouse required Capacity is updated. ";
?>