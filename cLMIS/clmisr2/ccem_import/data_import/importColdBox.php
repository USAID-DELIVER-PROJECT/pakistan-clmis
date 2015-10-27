<?php
include('../config.php');

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 2
		OR ccm_asset_types.pk_id = 2";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '200001';
$assetID = '2'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$qry1 = "SELECT
			import_cold_boxes.ft_facility_code,
			import_cold_boxes.ft_library_id,
			import_cold_boxes.ft_model,
			import_cold_boxes.fn_net_storage,
			import_cold_boxes.fi_qty_present,
			import_cold_boxes.fi_qty_not_working,
			import_cold_boxes.fi_cold_life,
			import_cold_boxes.MakeID,
			import_cold_boxes.box_type AS pk_id
		FROM
			import_cold_boxes";

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

        //$tot = round($row1['gross4'] + $row1['gross20'] + $row1['net4'] + $row1['net20'], 0);
        $warehouse_id = $row2['pk_id'];
		if (!empty($warehouse_id)){
			$qry3 = "SELECT
					ccm_models.pk_id
					FROM
					ccm_models
					WHERE
					ccm_models.ccm_model_name = '" . $row1['ft_model'] . "' AND
					ccm_models.catalogue_id = '" . $row1['ft_library_id'] . "' ";
	
			$qryRes3 = mysql_query($qry3);
	
			if (mysql_num_rows($qryRes3) > 0) {
				while ($row3 = mysql_fetch_array($qryRes3)) {
					$modelID = $row3['pk_id'];
				}
			} else {
				if ($row1['ft_model'] != NULL || $row1['ft_model'] != '') {
					$qry4 = "INSERT INTO `ccm_models` (
								ccm_model_name,
								gross_capacity_4,
								net_capacity_4,
								`status`,
								catalogue_id,
								ccm_make_id,
								ccm_asset_type_id
						)
						VALUES
								(
										'".$row1['ft_model']. "',
										'".$row1['fn_net_storage']."',
										'".$row1['fn_net_storage']."',
										1,
										'".$row1['ft_library_id']."',
										'".$row1['MakeID']."',
										2
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
			$estLife = ($row1['fi_cold_life'] == '') ? 15 : $row1['fi_cold_life'];
		  $sql5 = "Insert into cold_chain(
						auto_asset_id,
						estimate_life,
						`status`,
						ccm_asset_type_id,
						ccm_model_id,
						source_id,
						warehouse_id,
						quantity,
						created_by)
						VALUES ('".$assetID."', '".$estLife."', 1, 2, '".$modelID."', 1, '".$warehouse_id."', '".$row1['fi_qty_present']."', '".$user_id."' )";
		  
		 
		 $rs = mysql_query($sql5);
			if ($rs) {
				$assetID++;
			}
			$ccemId = mysql_insert_id();
			if ($ccemId != NULL && $ccemId != 0) {
				$sql6 = "INSERT into ccm_history(
						quantity,
						action,
						warehouse_id,
						ccm_id)
						VALUES ('".$row1['fi_qty_not_working']."', '10', '".$warehouse_id."', '".$ccemId."' )";
				mysql_query($sql6);
	
				$sql7 = "INSERT into ccm_status_history(
						working_quantity,
						warehouse_id,
						ccm_id,
						ccm_asset_type_id,
						utilization_id,
						ccm_status_list_id)
						VALUES ('".$row1['fi_qty_present']."', '".$warehouse_id."', '".$ccemId."', 2, 8, 1)";
				mysql_query($sql7);
				$historyId = mysql_insert_id();
				// print mysql_insert_id() . "<br />";
	
				mysql_query("UPDATE cold_chain SET ccm_status_history_id = '".$historyId."' WHERE pk_id = ".$ccemId." ");
			}
		}
	}
}

echo "Execution Completed. ";
?>
<a href="ImportPack.php">Next</a>