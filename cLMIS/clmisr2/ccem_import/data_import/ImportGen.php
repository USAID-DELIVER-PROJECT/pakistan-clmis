<?php
include('../config.php');

//Get Max auto asset Id
$qry = "SELECT
			MAX(cold_chain.auto_asset_id) + 1
		FROM
			cold_chain
		INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
		WHERE
			ccm_asset_types.parent_id = 6
		OR ccm_asset_types.pk_id = 6";
$qryRes = mysql_fetch_array(mysql_query($qry));
$assetID = !empty($qryRes['assetId']) ? $qryRes['assetId'] : '600001';
$assetID = '6'.str_pad(substr($assetID, 1), 7, '0', STR_PAD_LEFT);

$qry1 = "SELECT
			import_generators.ft_facility_code,
			import_generators.ft_model_name,
			import_generators.ft_serial_num,
			import_generators.fi_supply_year,
			import_generators.fi_number_phases,
			import_generators.MakeID,
			import_generators.ft_power_rating,
			import_generators.fi_auto_start,
			import_generators.energy_source,
			IF(import_generators.opt_fridgeuses_refrigerators = 'FALSE', 0, 1) AS opt_fridgeuses_refrigerators,
			IF(import_generators.opt_fridgeuses_coldrooms = 'FALSE', 0, 1) AS opt_fridgeuses_coldrooms,
			IF(import_generators.opt_fridgeuses_lighting = 'FALSE', 0, 1) AS opt_fridgeuses_lighting,
			IF(import_generators.opt_fridgeuses_other = 'FALSE', 0, 1) AS opt_fridgeuses_other
		FROM
        	import_generators";

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

        $warehouse_id = $row2['pk_id'];
		if (!empty($warehouse_id)){
			$qry3 = "SELECT
						ccm_models.pk_id
					FROM
						ccm_models
					WHERE
					ccm_models.ccm_model_name = '" . $row1['ft_model_name'] . "' AND
					ccm_models.ccm_make_id=" . $row1['MakeID'];
	
			$qryRes3 = mysql_query($qry3);
	
			if (mysql_num_rows($qryRes3)>0) {
				while ($row3 = mysql_fetch_array($qryRes3)) {
					$modelID = $row3['pk_id'];
				}
			} else {
				if($row1['ft_model_name'] != NULL || $row1['ft_model_name'] != '')
				{
					$qry4 = "INSERT INTO `ccm_models` (
									ccm_model_name,
									no_of_phases,
									`status`,
									ccm_make_id,
									ccm_asset_type_id
							)
							VALUES
									(
											'".$row1['ft_model_name']. "',
											'".$row1['fi_number_phases']."',
											1,
											'".$row1['MakeID']."',
											6
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
					VALUES ('".$assetID."' , '".$row1['ft_serial_num']."', 15, '".$row1['fi_supply_year']."-01-01', 1, 6, '".$modelID."' ,1 , '".$warehouse_id."', '".$user_id."' )";
	
			$rs = mysql_query($sql5);
			if ($rs)
			{
				$assetID++;
				$coldchainID = mysql_insert_id();
				$genQry = "INSERT INTO ccm_generators
						SET
							ccm_generators.power_source = '".$row1['energy_source']."',
							ccm_generators.power_rating = '".$row1['ft_power_rating']."',
							ccm_generators.automatic_start_mechanism = '".$row1['fi_auto_start']."',
							ccm_generators.ccm_id = '".$coldchainID."' ";
				mysql_query($genQry);
				$ccmGenID = mysql_insert_id();
				
				/*if ( $row1['opt_fridgeuses_refrigerators'] == 1 )
				{
					$genUseQry = "INSERT INTO ccm_import_generators_use
							SET
								ccm_import_generators_use.ccm_generator_id = '".$ccmGenID."',
								ccm_import_generators_use.use_for = 89 ";
					mysql_query($genUseQry);
				}
				if ( $row1['opt_fridgeuses_coldrooms'] == 1 )
				{
					$genUseQry = "INSERT INTO ccm_import_generators_use
							SET
								ccm_import_generators_use.ccm_generator_id = '".$ccmGenID."',
								ccm_import_generators_use.use_for = 90 ";
					mysql_query($genUseQry);
				}
				if ( $row1['opt_fridgeuses_lighting'] == 1 )
				{
					$genUseQry = "INSERT INTO ccm_import_generators_use
							SET
								ccm_import_generators_use.ccm_generator_id = '".$ccmGenID."',
								ccm_import_generators_use.use_for = 92 ";
					mysql_query($genUseQry);
				}
				if ( $row1['opt_fridgeuses_other'] == 1 )
				{
					$genUseQry = "INSERT INTO ccm_import_generators_use
							SET
								ccm_import_generators_use.ccm_generator_id = '".$ccmGenID."',
								ccm_import_generators_use.use_for = 129 ";
					mysql_query($genUseQry);
				}*/
			}
			
			if($coldchainID != NULL && $coldchainID != 0){
				$sql7 = "INSERT into ccm_status_history(
							warehouse_id,
							ccm_id,
							ccm_asset_type_id,
							utilization_id,
							ccm_status_list_id)
							VALUES ( ".$warehouse_id.", '".$coldchainID."', 6, 8, 1)";
				mysql_query($sql7);
				$historyId = mysql_insert_id();
			   
			   mysql_query("UPDATE cold_chain SET ccm_status_history_id = '".$historyId."' WHERE pk_id = ".$coldchainID."");
			}
		}
	}
}


echo "Execution Completed. ";
?>
<a href="ImportTrans.php">Next</a>