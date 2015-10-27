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

#Coldroom
$qry = "DELETE cold_chain.*, ccm_cold_rooms.*, ccm_status_history.*
	FROM
		cold_chain,
		ccm_cold_rooms,
		ccm_status_history,
		ccm_models
	WHERE
		cold_chain.pk_id = ccm_cold_rooms.ccm_id
	AND cold_chain.ccm_model_id = ccm_models.pk_id
	AND cold_chain.pk_id = ccm_status_history.ccm_id
	AND cold_chain.ccm_asset_type_id = 3
	AND cold_chain.created_date = '0000-00-00 00-00-00'
	AND ccm_cold_rooms.created_date = '0000-00-00 00-00-00'";
mysql_query($qry);

#CCM History
$qry = "DELETE
		FROM
			ccm_history
		WHERE
			ccm_history.created_date = '0000-00-00 00:00:00'";
mysql_query($qry);

#Generator
$qry = "DELETE cold_chain.*, ccm_models.*, ccm_generators.*
	FROM
		cold_chain,
		ccm_generators,
		ccm_models
	WHERE
		ccm_generators.ccm_id = cold_chain.pk_id
	AND cold_chain.created_date = '0000-00-00 00:00:00'
	AND cold_chain.ccm_model_id = ccm_models.pk_id
	AND cold_chain.ccm_asset_type_id = 6";
mysql_query($qry);

$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 6";
mysql_query($qry);

#Vehicle
$qry = "DELETE
		ccm_vehicles.*, cold_chain.*, ccm_models.*
	FROM
		ccm_vehicles,
		cold_chain,
		ccm_models
	WHERE
		ccm_vehicles.ccm_id = cold_chain.pk_id
	AND cold_chain.ccm_model_id = ccm_models.pk_id
	AND cold_chain.created_date = '0000-00-00 00:00:00'
	AND cold_chain.ccm_asset_type_id = 7";
mysql_query($qry);


$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 7";
mysql_query($qry);


#Regulator/Stablizer
$qry = "DELETE cold_chain.*, ccm_voltage_regulators.*, ccm_models.*
	FROM
		cold_chain,
		ccm_voltage_regulators,
		ccm_models
	WHERE
		ccm_voltage_regulators.ccm_id = cold_chain.pk_id
	AND cold_chain.ccm_model_id = ccm_models.pk_id
	AND cold_chain.created_date = '0000-00-00 00:00:00' ";
mysql_query($qry);

$qry = "DELETE ccm_models.*, cold_chain.*
	FROM
		cold_chain
	INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
	WHERE
		cold_chain.ccm_asset_type_id = 5";
mysql_query($qry);

$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 5";
mysql_query($qry);

#Icepack
$qry = "DELETE cold_chain.*
	FROM
		cold_chain
	WHERE
		cold_chain.ccm_asset_type_id = 4
	AND cold_chain.created_date = '0000-00-00 00:00:00'";
mysql_query($qry);

$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 4";
mysql_query($qry);

#ColdBox
$qry = "DELETE cold_chain.*, ccm_status_history.*
	FROM
		cold_chain,
		ccm_status_history
	WHERE
		cold_chain.ccm_status_history_id = ccm_status_history.pk_id
	AND cold_chain.ccm_asset_type_id = 2
	AND cold_chain.created_date = '0000-00-00 00:00:00'";
mysql_query($qry);

$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 2";
mysql_query($qry);

$qry = "DELETE ccm_models.*
	FROM
		ccm_models
	WHERE
		ccm_models.ccm_asset_type_id = 2;
	
	DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id = 2";
mysql_query($qry);

#Refrigerator
$qry = "DELETE
		cold_chain.*, ccm_models.*
	FROM
		cold_chain
	INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
	WHERE
		cold_chain.ccm_asset_type_id IN (
			1,
			8,
			9,
			10,
			11,
			12,
			13,
			14,
			17,
			18,
			19,
			20,
			21,
			22,
			23,
			24,
			25,
			26
		)
	AND cold_chain.created_date = '0000-00-00 00:00:00'
	AND cold_chain.pk_id NOT IN (
		SELECT
		*
	FROM
		(
			SELECT
				cold_chain.pk_id
			FROM
				cold_chain
			WHERE
				cold_chain.ccm_asset_type_id IN (
					8,
					9,
					10,
					11,
					12,
					13,
					14,
					17,
					18,
					19,
					20,
					21,
					22,
					23,
					24,
					25,
					26
				)
			AND cold_chain.created_date != '0000-00-00 00:00:00'
		) A
	)";
mysql_query($qry);

$qry = "DELETE ccm_status_history.*
	FROM
		ccm_status_history
	WHERE
		ccm_status_history.ccm_asset_type_id IN (
					1,
					8,
					9,
					10,
					11,
					12,
					13,
					14,
					17,
					18,
					19,
					20,
					21,
					22,
					23,
					24,
					25,
					26
				)
	AND ccm_status_history.pk_id NOT IN (
		SELECT
		*
	FROM
		(
			SELECT
				ccm_status_history.pk_id
			FROM
				cold_chain
			INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
			WHERE
				cold_chain.ccm_asset_type_id IN (
					8,
					9,
					10,
					11,
					12,
					13,
					14,
					17,
					18,
					19,
					20,
					21,
					22,
					23,
					24,
					25,
					26
				)
			AND cold_chain.created_date != '0000-00-00 00:00:00'
		) A
	)";
mysql_query($qry);
?>
Old data is deleted. 
<a href="./coldroom">Next</a>