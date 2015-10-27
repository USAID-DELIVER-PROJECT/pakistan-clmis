<?php
include('../config.php');

$qry = "INSERT INTO `ccem_test`.`ccm_models` (`pk_id`, `ccm_model_name`, `asset_dimension_length`, `asset_dimension_width`, `asset_dimension_height`, `gross_capacity_20`, `gross_capacity_4`, `net_capacity_20`, `net_capacity_4`, `cfc_free`, `gas_type`, `no_of_phases`, `status`, `reasons`, `utilizations`, `temperature_type`, `catalogue_id`, `ccm_make_id`, `ccm_asset_type_id`, `created_by`, `created_date`, `modified_by`, `modified_date`, `cold_life`, `product_price`, `power_source`, `internal_dimension_length`, `internal_dimension_width`, `internal_dimension_height`, `storage_dimension_length`, `storage_dimension_width`, `storage_dimension_height`, `is_pqs`) VALUES 
('2102', '0.2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 15:43:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2103', '0.3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2104', '0.4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2105', '0.5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2107', '0.7', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2108', '0.8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2109', '0.9', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '', NULL, NULL, NULL, '591', '4', '0', '0000-00-00 00:00:00', '0', '2014-10-29 16:05:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)
";
mysql_query($qry);

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 4
		OR ccm_asset_types.pk_id = 4";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '400001';
$assetID = '4'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$qry1 = "SELECT
			import_ice_packs.ft_facility_code,
			import_ice_packs.fi_number_02,
			import_ice_packs.fi_number_03,
			import_ice_packs.fi_number_04,
			import_ice_packs.fi_number_05,
			import_ice_packs.fi_number_06,
			import_ice_packs.fi_number_07,
			import_ice_packs.fi_number_08,
			import_ice_packs.fi_number_09,
			import_ice_packs.fi_number_10,
			import_ice_packs.fi_number_11
		FROM
			import_ice_packs";

$qryRes1 = mysql_query($qry1);
$modelID = 0;

while ($row1 = mysql_fetch_array($qryRes1)) {

    $qry2 = "SELECT
                warehouses.pk_id,
                warehouses.ccem_id
            FROM
                warehouses
            Where ccem_id='" . $row1['ft_facility_code'] . "' ";
    $qryRes2 = mysql_query($qry2);
    $warehouse_id = 0;
    if ($qryRes2) {

        while ($row2 = mysql_fetch_array($qryRes2)) {
            $warehouse_id = $row2['pk_id'];
			if (!empty($warehouse_id)){
				//user_id 
				$qry_user = "Select user_id from warehouse_users where warehouse_id='$warehouse_id' ";
		
		
				$qryRes_user = mysql_query($qry_user);
				$row_user = mysql_fetch_array($qryRes_user);
				$user_id = $row_user['user_id'];
				if ($row1['fi_number_02'] != NULL || $row1['fi_number_02'] != '') {
	
					$modelID = 2102;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('".$assetID."', 1, 4, '".$modelID."', 1, '".$warehouse_id."', '".$user_id."' )";
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						$sql6 = "INSERT into ccm_history(
							action,
							warehouse_id,
							ccm_id)
							VALUES ( '10', '".$warehouse_id."', '".$ccemId."' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
							working_quantity,
							warehouse_id,
							ccm_id,
							ccm_asset_type_id,
							utilization_id,
							ccm_status_list_id)
							VALUES ('".$row1['fi_number_02']."', '".$warehouse_id."', '".$ccemId."', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '".$historyId."' WHERE pk_id = ".$ccemId." ");
					}
				}
				if ($row1['fi_number_03'] != NULL || $row1['fi_number_03'] != '') {
	
					$modelID = 2103;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('".$assetID."', 1, 4, '".$modelID."', 1, '".$warehouse_id."' , '".$user_id."')";
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '".$warehouse_id."', '".$ccemId."' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('".$row1['fi_number_03']."', '".$warehouse_id."', '".$ccemId."', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_04'] != NULL || $row1['fi_number_04'] != '') {
	
					$modelID = 2104;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1," . $warehouse_id . "," . $user_id . ")";
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('".$row1['fi_number_04']."', '".$warehouse_id."', '".$ccemId."', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '".$historyId."' WHERE pk_id = ".$ccemId."");
					}
				}
				if ($row1['fi_number_05'] != NULL || $row1['fi_number_05'] != '') {
					$modelID = 2105;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('".$assetID."', 1, 4, '".$modelID."', 1, '".$warehouse_id."', '".$user_id."')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '".$warehouse_id."', '".$ccemId."' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('".$row1['fi_number_05']."', '".$warehouse_id."', '".$ccemId."', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = '".$ccemId."'");
					}
				}
				if ($row1['fi_number_06'] != NULL || $row1['fi_number_06'] != '') {
					$modelID = 2106;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
					   `status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1'," . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_06'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_07'] != NULL || $row1['fi_number_07'] != '') {
					$modelID = 2107;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_07'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_08'] != NULL || $row1['fi_number_08'] != '') {
					$modelID = 2108;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_08'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_09'] != NULL || $row1['fi_number_09'] != '') {
					$modelID = 2109;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_09'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_10'] != NULL || $row1['fi_number_10'] != '') {
					$modelID = 2110;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',,1,4," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_10'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
				if ($row1['fi_number_11'] != NULL || $row1['fi_number_11'] != '') {
					$modelID = 2111;
					$qry3 = "Insert into cold_chain(
						auto_asset_id,
					   `status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						created_by)
					VALUES ('" . $assetID . "',1,4," . $modelID . ",1,'" . $warehouse_id . "','" . $user_id . "')";
	
					$qrs = mysql_query($qry3);
					if ($qrs) {
						$assetID++;
						$ccemId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
						$sql6 = "INSERT into ccm_history(
						action,
						warehouse_id,
						ccm_id)
						VALUES ( '10', '" . $warehouse_id . "', '" . $ccemId . "' )";
						mysql_query($sql6);
	
						$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('" . $row1['fi_number_11'] . "', '" . $warehouse_id . "', '" . $ccemId . "', 4, 8, 1)";
						mysql_query($sql7);
						$historyId = mysql_insert_id();
						// print mysql_insert_id() . "<br />";
	
						mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
					}
				}
			}
        }
    }
}


echo "Execution Completed. ";
?>
<a href="ImportReg.php">Next</a>