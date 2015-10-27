<?php
include('../config.php');

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 3
		OR ccm_asset_types.pk_id = 3";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '300001';
$assetID = '3'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$qry1 = "SELECT
            import_cold_room.ft_facility_code,
            import_cold_room.ft_library_id,
            import_cold_room.ft_model_name,
            import_cold_room.ft_serial_num,
            import_cold_room.fi_number_phases,
            import_cold_room.fn_gross_volume_4deg AS gross4,
            import_cold_room.fn_net_volume_4deg AS net4,
            import_cold_room.fn_gross_volume_20deg AS gross20,
            import_cold_room.fn_net_volume_20deg AS net20,
            import_cold_room.fi_supply_year,
            import_cold_room.fi_type_cold_room,
            import_cold_room.MakeID,
            import_cold_room.gas_type,
            import_cold_room.fi_temp_record_type,
			import_cold_room.fi_num_cooling_system,
			import_cold_room.fi_has_stabilizer,
			import_cold_room.asset_sub_type,
			import_cold_room.fi_temp_record,
			import_cold_room.temp_record_type,
			import_cold_room.gas_type,
			import_cold_room.backup_generator
        FROM
            import_cold_room";

$qryRes1 = mysql_query($qry1);
$modelID = 0;
while ($row1 = mysql_fetch_array($qryRes1)) {
    $qry2 = "SELECT
                warehouses.pk_id,
                warehouses.ccem_id
            FROM
                warehouses
            Where ccem_id='" . $row1['ft_facility_code'] . "'";
    $qryRes2 = mysql_query($qry2);
    $warehouse_id = 0;
    while ($row2 = mysql_fetch_array($qryRes2)) {

        $tot = round($row1['gross4'] + $row1['gross20'] + $row1['net4'] + $row1['net20'], 0);
        $warehouse_id = $row2['pk_id'];
		if (!empty($warehouse_id)){
			$qry3 = "SELECT
					ccm_models.pk_id
					FROM
					ccm_models
					WHERE
					ccm_models.ccm_model_name = '" . $row1['ft_model_name'] . "' AND
					ccm_models.catalogue_id = '" . $row1['ft_library_id'] . "' AND
					ccm_models.ccm_make_id=" . $row1['MakeID'] . "
					and round(IFNULL(ccm_models.gross_capacity_20,0) +
					IFNULL(ccm_models.gross_capacity_4,0) +
					IFNULL(ccm_models.net_capacity_20,0) +
					IFNULL(ccm_models.net_capacity_4,0),0)=" . $tot * 1000;
	
			$qryRes3 = mysql_query($qry3);
	
			if (mysql_num_rows($qryRes3) > 0) {
				while ($row3 = mysql_fetch_array($qryRes3)) {
					$modelID = $row3['pk_id'];
				}
			} else {
				if ($row1['ft_model_name'] != NULL || $row1['ft_model_name'] != '') {
					$qry4 = "INSERT INTO `ccm_models` (
								ccm_model_name,
								gross_capacity_20,
								gross_capacity_4,
								net_capacity_20,
								net_capacity_4,
								gas_type,
								no_of_phases,
								temperature_type,
								`status`,
								catalogue_id,
								ccm_make_id,
								ccm_asset_type_id
						)
						VALUES
								(
										'" . $row1['ft_model_name'] . "',
										" . $row1['gross20'] * 1000 . ",
										" . $row1['gross4'] * 1000 . ",
										" . $row1['net20'] * 1000 . ",
										" . $row1['net4'] * 1000 . ",
										" . $row1['gas_type'] . ",
										" . $row1['fi_number_phases'] . ",
										" . $row1['fi_temp_record_type'] . ",
										1,
										'" . $row1['ft_library_id'] . "',
										" . $row1['MakeID'] . ",
										3
								)";
					mysql_query($qry4);
					$modelID = mysql_insert_id();
				}
			}
			//user_id 
			$qry_user = "Select user_id from warehouse_users where warehouse_id='$warehouse_id' ";
	
	
			$qryRes_user = mysql_query($qry_user);
			$row_user = mysql_fetch_array($qryRes_user);
			$user_id = $row_user['user_id'];
			//
			//insert cold chain
			//
			$sql5 = "Insert into cold_chain(
						auto_asset_id,
						serial_number,
						estimate_life,
						working_since,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "','" . $row1['ft_serial_num'] . "',15,'" . $row1['fi_supply_year'] . "-01-01',1, '".$row1['asset_sub_type']."' ," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
			$rs = mysql_query($sql5);
			if ($rs) {
				$assetID++;
				$coldchainID = mysql_insert_id();
				
				$coldRoomSql = "INSERT INTO ccm_cold_rooms
					SET
						ccm_cold_rooms.has_voltage = '".$row1['fi_has_stabilizer']."',
						ccm_cold_rooms.ccm_asset_sub_type_id = '".$row1['asset_sub_type']."',
						ccm_cold_rooms.ccm_id = '".$coldchainID."',
						ccm_cold_rooms.temperature_recording_system = '".$row1['fi_temp_record']."',
						ccm_cold_rooms.type_recording_system = '".$row1['temp_record_type']."',
						ccm_cold_rooms.refrigerator_gas_type = '".$row1['gas_type']."',
						ccm_cold_rooms.backup_generator = '".$row1['backup_generator']."',
						ccm_cold_rooms.cooling_system = '".$row1['fi_num_cooling_system']."' ";
				mysql_query($coldRoomSql);
				
				$placementQry = "INSERT INTO placement_locations 
					SET
						location_type = 99,
						location_barcode = '$assetID',
						location_id = $coldchainID ";
				mysql_query($placementQry);
			}
			
			if ($coldchainID != NULL && $coldchainID != 0) {
				$sql7 = "INSERT into ccm_status_history(
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ( " . $warehouse_id . ", '" . $coldchainID . "', 3, 8, 1)";
				mysql_query($sql7);
				$historyId = mysql_insert_id();
	
				mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $coldchainID . "");
			}
		}
	}
}


echo "Execution Completed. ";
?>
<a href="population.php">Next</a>