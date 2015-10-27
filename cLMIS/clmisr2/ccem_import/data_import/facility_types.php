<?php
include('../config.php');

$qry = "SELECT
			import_facilities.ft_facility_code,
			import_facilities.ft_facility_type,
			warehouses.ccem_id
		FROM
			import_facilities
		INNER JOIN warehouses ON import_facilities.ft_facility_code = warehouses.ccem_id";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$updateQry = "UPDATE warehouses SET warehouse_type_id = '".$row['ft_facility_type']."' WHERE ccem_id = '".$row['ft_facility_code']."' ";
	mysql_query($updateQry);
}

echo "Execution Completed";