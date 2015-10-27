<?php
include('../config.php');

$utilization = array(
	1 => 8,
	2 => 9,
	3 => 10
);

$qry = "SELECT
			import_refrigerators.ft_facility_code,
			import_refrigerators.ft_model_name,
			import_refrigerators.fi_work_status AS utilization,
			import_refrigerators.fn_not_work_spare_parts,
			import_refrigerators.fn_not_work_finance,
			import_refrigerators.fn_not_work_fuel,
			import_refrigerators.fn_not_work_boarded,
			import_refrigerators.MakeID,
			import_refrigerators.ref_sub_type,
			import_refrigerators.fi_operating_cond AS workingStatus
		FROM
			warehouses
		INNER JOIN import_refrigerators ON warehouses.ccem_id = import_refrigerators.ft_facility_code";
		
$rows = mysql_query($qry);
while ( $row = mysql_fetch_array($rows) )
{
	
}