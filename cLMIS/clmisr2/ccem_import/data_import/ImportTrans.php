<?php
include('../config.php');

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 7
		OR ccm_asset_types.pk_id = 7";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '70000001';
$assetID = '7'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$qry1 = "SELECT
			import_transport.ft_facility_code,
			import_transport.ft_model,
			import_transport.ft_year,
			import_transport.MakeID,
			import_transport.ft_epi,
			import_transport.transport_type,
			import_transport.fuel_type,
			IF(import_transport.ft_number = '', 1, import_transport.ft_number) AS ft_number
		FROM
			import_transport";

$qryRes1 = mysql_query($qry1);
$modelID = 0;
while ($row1 = mysql_fetch_array($qryRes1)) {
	
	for( $i=0; $i<$row1['ft_number']; $i++)
	{
		$qry2 = "SELECT
					warehouses.pk_id,
					warehouses.ccem_id
				FROM
					warehouses
				Where ccem_id='" . $row1['ft_facility_code'] . "'";
		$qryRes2 = mysql_query($qry2);
		$warehouse_id = 0;
		while ($row2 = mysql_fetch_array($qryRes2)) {
	
			$warehouse_id = $row2['pk_id'];
			if (!empty($warehouse_id)){
				$qry3 = "SELECT
							ccm_models.pk_id
						FROM
							ccm_models
						WHERE
						ccm_models.ccm_model_name = '" . $row1['ft_model'] . "' AND
						ccm_models.ccm_make_id=" . $row1['MakeID'];
		
				$qryRes3 = mysql_query($qry3);
		
				if (mysql_num_rows($qryRes3)>0) {
					while ($row3 = mysql_fetch_array($qryRes3)) {
						$modelID = $row3['pk_id'];
					}
				} else {
					if($row1['ft_model'] != NULL || $row1['ft_model'] != '')
					{
					$qry4 = "INSERT INTO `ccm_models` (
									ccm_model_name,
									`status`,
									ccm_make_id,
									ccm_asset_type_id
							)
							VALUES
									(
											'" . $row1['ft_model'] . "',
											1,
											" . $row1['MakeID'] . ",
											7
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
				
				//insert cold chain
				//
				$sql5 = "Insert into cold_chain(
							auto_asset_id,                    
							estimate_life,
							working_since,
							`status`,
							ccm_asset_type_id,
							ccm_model_id,
							source_id,
							warehouse_id,
							created_by)
						VALUES ('".$assetID."', 15, '".$row1['ft_year']."-01-01', 1, 7, '".$modelID."', 1, '".$warehouse_id."', '".$user_id."')";
				$rs = mysql_query($sql5);
				$ccemId = mysql_insert_id();
				if ($rs)
				{
					$assetID++;
					$coldchainID = mysql_insert_id();
					$transQry = "INSERT INTO ccm_vehicles
							SET
								ccm_vehicles.ccm_id = ".$coldchainID.",
								ccm_vehicles.used_for_epi = '".$row1['ft_epi']."',
								ccm_vehicles.ccm_asset_sub_type_id = '".$row1['import_transport_type']."',
								ccm_vehicles.fuel_type_id = '".$row1['fuel_type']."' ";
					mysql_query($transQry);
				}
				if($ccemId != NULL && $ccemId != 0){
					$sql7 = "INSERT into ccm_status_history(
							warehouse_id,
							ccm_id,
							ccm_asset_type_id,
							utilization_id,
							ccm_status_list_id)
							VALUES ( ".$warehouse_id.", '".$ccemId."', 7, 8, 1)";
					mysql_query($sql7);
					$historyId = mysql_insert_id();
					
					mysql_query("UPDATE cold_chain SET ccm_status_history_id = '".$historyId."' WHERE pk_id = ".$ccemId."");
				}
			}
		}
	}
}

echo "Execution Completed. ";
?>
<a href="ImportColdroom.php">Next</a>