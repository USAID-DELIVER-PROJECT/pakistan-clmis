<?php
include('config.php');
#Placement Locations
$qry = "DELETE placement_locations.*
		FROM
			placement_locations
		LEFT JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
		WHERE
			placement_locations.pk_id NOT IN (
				SELECT
					*
				FROM
					(
						SELECT
							placement_locations.pk_id
						FROM
							placement_locations
						INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
						WHERE
							cold_chain.created_date != '0000-00-00 00:00:00'
					) A
			)";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_id NOT IN (
			SELECT
				cold_chain.pk_id
			FROM
				cold_chain
			WHERE
				cold_chain.created_date != '0000-00-00 00-00-00'
		)";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_cold_rooms
	WHERE
		ccm_cold_rooms.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_generators
	WHERE
		ccm_generators.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_history
	WHERE
		ccm_history.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_vehicles
	WHERE
		ccm_vehicles.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

$qry = "DELETE
	FROM
		ccm_voltage_regulators
	WHERE
		ccm_voltage_regulators.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

$qry = "DELETE cold_chain.*, ccm_models.*
	FROM
		cold_chain,
		ccm_models
	WHERE
		cold_chain.ccm_model_id = ccm_models.pk_id
	AND cold_chain.created_date = '0000-00-00 00-00-00'
	AND ccm_models.pk_id NOT IN (
		SELECT
			*
		FROM
			(
				SELECT DISTINCT
					ccm_models.pk_id
				FROM
					cold_chain
				INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
				WHERE
					cold_chain.created_date != '0000-00-00 00:00:00'
			) A
	)";
mysql_query($qry);

$qry = "DELETE
	FROM
		cold_chain
	WHERE
		cold_chain.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

?>
Old data is deleted. 
<a href="./coldroom">Next</a>