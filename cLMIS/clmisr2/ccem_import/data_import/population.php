<?php
include('../config.php');

$qry = "SELECT
			warehouses.pk_id,
			import_facilities.fi_target_births,
			import_facilities.fi_target_pw,
			import_facilities.fi_tot_pop,
			import_facilities.fi_cba
		FROM
			import_facilities
		INNER JOIN warehouses ON import_facilities.ft_facility_code = warehouses.ccem_id";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	// Check if popultion already exists
	$qryPop = "SELECT
					COUNT(warehouse_population.pk_id) AS num
				FROM
					warehouse_population
				WHERE
					warehouse_population.warehouse_id = '".$row['pk_id']."'";
	$qryPopRes = mysql_fetch_array(mysql_query($qryPop));
	$num = $qryPopRes['num'];
	if ( $num == 0 )
	{
		$insQry = "INSERT INTO warehouse_population
					SET
						warehouse_population.facility_total_pouplation = '".$row['fi_tot_pop']."',
						warehouse_population.live_births_per_year = '".$row['fi_target_births']."',
						warehouse_population.pregnant_women_per_year = '".$row['fi_target_pw']."',
						warehouse_population.women_of_child_bearing_age = '".$row['fi_cba']."',
						warehouse_population.estimation_year = '2014',
						warehouse_population.warehouse_id = '".$row['pk_id']."',
						warehouse_population.created_by = '1',
						warehouse_population.created_date = NOW(),
						warehouse_population.modified_by = 1,
						warehouse_population.modified_date = NOW() ";
		mysql_query($insQry);
	}
	else
	{
		$updateQry = "UPDATE warehouse_population
					SET
						warehouse_population.facility_total_pouplation = '".$row['fi_tot_pop']."',
						warehouse_population.live_births_per_year = '".$row['fi_target_births']."',
						warehouse_population.pregnant_women_per_year = '".$row['fi_target_pw']."',
						warehouse_population.women_of_child_bearing_age = '".$row['fi_cba']."',
						warehouse_population.estimation_year = '2014',
						warehouse_population.warehouse_id = '".$row['pk_id']."',
						warehouse_population.created_by = '1',
						warehouse_population.created_date = NOW(),
						warehouse_population.modified_by = 1,
						warehouse_population.modified_date = NOW()
					WHERE 
						warehouse_population.warehouse_id = '".$row['pk_id']."' ";
		mysql_query($updateQry);
	}
}

echo "Execution Completed. ";
?>
<a href="warehouse_functions.php">Next</a>