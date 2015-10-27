<?php
include('../config.php');

$qry = "SELECT
			warehouses.pk_id,
			import_facilities.fi_cc_delivery,
			import_facilities.fi_cc_outreach,
			import_facilities.fi_mode,
			import_facilities.fi_electricity,
			import_facilities.fn_icepack_20_rout,
			import_facilities.fn_icepack_20_supp
		FROM
			import_facilities
		INNER JOIN warehouses ON warehouses.ccem_id = import_facilities.ft_facility_code";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	
	if ( $row['fi_cc_delivery'] == '-1' )
	{
		// Check if data already exists
		$num = mysql_num_rows(mysql_query("SELECT
								warehouses_service_types.pk_id
							FROM
								warehouses_service_types
							WHERE
								warehouses_service_types.warehouse_id = ".$row['pk_id']." AND warehouses_service_types.service_type_id = 113" ));
		if ( $num == 0 )
		{
			$insQry = "INSERT INTO warehouses_service_types
						SET
							warehouses_service_types.service_type_id = 113,
							warehouses_service_types.warehouse_id = ".$row['pk_id']." ";
			mysql_query($insQry);
		}
	}
	else
	{
		mysql_query("DELETE FROM warehouses_service_types WHERE warehouses_service_types.service_type_id = 113 AND warehouses_service_types.warehouse_id = ".$row['pk_id']." ");
	}
	
	if ( $row['fi_cc_outreach'] == '-1' )
	{
		// Check if data already exists
		$num = mysql_num_rows(mysql_query("SELECT
								warehouses_service_types.pk_id
							FROM
								warehouses_service_types
							WHERE
								warehouses_service_types.warehouse_id = ".$row['pk_id']." AND warehouses_service_types.service_type_id = 112" ));
		if ( $num == 0 )
		{
			$insQry = "INSERT INTO warehouses_service_types
						SET
							warehouses_service_types.service_type_id = 112,
							warehouses_service_types.warehouse_id = ".$row['pk_id']." ";
			mysql_query($insQry);
		}
	}
	else
	{
		mysql_query("DELETE FROM warehouses_service_types WHERE warehouses_service_types.service_type_id = 112 AND warehouses_service_types.warehouse_id = ".$row['pk_id']." ");
	}
	
	$mode = array(
		1 => 108,
		2 => 109,
		3 => 110,
		4 => 111
	);
	$energyMode = array(
		0 => 94,
		1 => 103,
		2 => 105,
		3 => 106
	);
	
	$insQry = "INSERT INTO ccm_warehouses
				SET
					ccm_warehouses.vaccine_supply_mode = ".$mode[$row['fi_mode']].",
					ccm_warehouses.electricity_availability_id = ".$energyMode[$row['fi_electricity']].",
					ccm_warehouses.routine_immunization_icepack_requirments = '".$row['fn_icepack_20_rout']."',
					ccm_warehouses.campaign_icepack_requirments = '".$row['fn_icepack_20_supp']."',
					ccm_warehouses.warehouse_id = ".$row['pk_id']." ";
	mysql_query($insQry);
}

echo "Execution Completed. ";
?>
<a href="update_capacity.php">Next</a>