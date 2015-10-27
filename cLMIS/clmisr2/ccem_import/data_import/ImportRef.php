<?php
include('../config.php');

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 1
		OR ccm_asset_types.pk_id = 1";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '100001';
$assetID = '1'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$utilization = array(
	1 => 8,
	2 => 9,
	3 => 10
);

$qry1 = "SELECT
            import_refrigerators.ft_facility_code,
            import_refrigerators.ft_library_id,
            import_refrigerators.ft_model_name,
            import_refrigerators.ft_item_type,
            import_refrigerators.ft_serial_num,
            import_refrigerators.fi_cfc_free,
            IFNULL(import_refrigerators.fn_gross_volume_4deg,0) as gross4,
            IFNULL(import_refrigerators.fn_net_volume_4deg,0) as net4,
            IFNULL(import_refrigerators.fn_gross_volume_20deg,0) as gross20,
            IFNULL(import_refrigerators.fn_net_volume_20deg,0) as net20,
            IFNULL(import_refrigerators.fi_supply_year,'1947') as fi_supply_year,
            import_refrigerators.fi_supply_source,
            import_refrigerators.fi_work_status,
            import_refrigerators.MakeID,
			import_refrigerators.ref_sub_type,
			import_refrigerators.fi_work_status AS utilization,
			import_refrigerators.fn_not_work_spare_parts,
			import_refrigerators.fn_not_work_finance,
			import_refrigerators.fn_not_work_fuel,
			import_refrigerators.fn_not_work_boarded,
			import_refrigerators.fi_operating_cond AS workingStatus
        FROM
            import_refrigerators";

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
						ccm_models.cfc_free = '" . $row1['fi_cfc_free'] . "' and
						ccm_models.ccm_make_id=" . $row1['MakeID'] . "
						and (round(IFNULL(ccm_models.gross_capacity_20,0) +
						IFNULL(ccm_models.gross_capacity_4,0) +
						IFNULL(ccm_models.net_capacity_20,0) +
						IFNULL(ccm_models.net_capacity_4,0),0))=" . $tot;
	
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
								cfc_free,
								`status`,
								catalogue_id,
								ccm_make_id,
								ccm_asset_type_id
						)
						VALUES
								(
									'". $row1['ft_model_name']."',
									'". $row1['gross20']."',
									'". $row1['gross4']."',
									'". $row1['net20']."',
									'". $row1['net4']."',
									'". $row1['fi_cfc_free']."',
									1,
									'". $row1['ft_library_id']."',
									'". $row1['MakeID']."',
									1
								)";
					mysql_query($qry4);
					$modelID = mysql_insert_id();
				}
			}
	
			//user_id 
			$qry_user = "Select user_id from warehouse_users where warehouse_id='$warehouse_id' ";
	
	
			$qryRes_user = mysql_query($qry_user);
			if ( mysql_num_rows(mysql_query($qry_user)) > 0 )
			{
				$row_user = mysql_fetch_array($qryRes_user);
				$user_id = $row_user['user_id'];
			}
			else 
			{
				$user_id = 0;
			}
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
					VALUES ('".$assetID."', '".$row1['ft_serial_num']."', 15, '".$row1['fi_supply_year']."-01-01',1,'".$row1['ref_sub_type']."', '".$modelID."', 1, '".$warehouse_id."', '".$user_id."' )";
			$rs = mysql_query($sql5);
			$ccemId = mysql_insert_id();
			if ($rs) {
				$assetID++;
				$coldchainID = mysql_insert_id();
				$placementQry = "INSERT INTO placement_locations 
					SET
						location_type = 99,
						location_barcode = '$assetID',
						location_id = $coldchainID ";
				mysql_query($placementQry);
			}
			//print mysql_insert_id() . "<br />";
			//print mysql_insert_id() . "<br />";
			if ($ccemId != NULL && $ccemId != 0) {
				$reasonId = '';
				if ($row1['workingStatus'] == 3)				
				{
					if ( $row1['fn_not_work_spare_parts'] == '-1' ){
						$reasonId = 4;
					}else if ( $row1['fn_not_work_finance'] == '-1' ){
						$reasonId = 5;
					}else if ( $row1['fn_not_work_fuel'] == '-1' ){
						$reasonId = 6;
					}else if ( $row1['fn_not_work_boarded'] == '-1' ){
						$reasonId = 7;
					}
				}
				
				$sql7 = "INSERT into ccm_status_history(
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id,
						reason_id)
						VALUES ( '".$warehouse_id."', '".$ccemId."', ".$row1['ref_sub_type'].", '".$utilization[$row1['utilization']]."', '".$row1['workingStatus']."', '".$reasonId."')";
				mysql_query($sql7);
				$historyId = mysql_insert_id();
				// print mysql_insert_id() . "<br />";
	
				mysql_query("UPDATE cold_chain SET ccm_status_history_id = '" . $historyId . "' WHERE pk_id = " . $ccemId . "");
			}
		}
	}
}

echo "Execution Completed. ";
?>
<a href="importColdBox.php">Next</a>