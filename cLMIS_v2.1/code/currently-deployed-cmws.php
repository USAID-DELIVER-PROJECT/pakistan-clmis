#!/usr/local/bin/php -q
<?php
include("application/includes/classes/Configuration.inc.php");
include("application/includes/classes/db.php");

$url = "http://mnch.pk/cmw_db/getcmwlist.php";
if (!function_exists('curl_init')){
	die('Sorry CURL is not installed.');
}

//Temporary Drop the trigger
$temp_del_trigger = "DROP TRIGGER IF EXISTS `AddWarehouseStatusOnUpdate`";
mysql_query($temp_del_trigger);
$temp_del_trigger = "DROP TRIGGER IF EXISTS `UpdateActiveWHHistory`";
mysql_query($temp_del_trigger);

$cmwList;
$cmwsArr;
$old;
$oldActive;
$inActive;

// Get cLMIS CMWs
$qry = "SELECT
			tbl_warehouse.dhis_code,
			tbl_warehouse.is_active
		FROM
			tbl_warehouse
		WHERE
			tbl_warehouse.stkid = 73
		AND tbl_warehouse.stkofficeid = 111";
$qryRes = mysql_query($qry);
while ($row = mysql_fetch_array($qryRes))
{
	$old[] = $row['dhis_code'];
	if($row['is_active'] == 1)
	{
		$oldActive[] = $row['dhis_code'];
	}
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$output = curl_exec($ch);
curl_close($ch);
$deployedCMWs = json_decode($output);

foreach($deployedCMWs as $cmw)
{
	$cmwList[] = $cmw->cmwcode;
	$cmwsArr[$cmw->districtname][] = $cmw->cmwcode;
	
	// New CMWs
	if(!in_array($cmw->cmwcode, $old))
	{
		$cmwCode = $cmw->cmwcode;
		$cmwName = $cmw->cmwname;
		$districtId = $cmw->districtcode;
		$provinceId = $cmw->provincecode;
		
		$insWHQry = "INSERT INTO tbl_warehouse
				SET	 
					 tbl_warehouse.wh_name = '".$cmwName."',
					 tbl_warehouse.dist_id = REPgetLmisLocationCode($districtId),
					 tbl_warehouse.prov_id = REPgetLmisLocationCode($provinceId),
					 tbl_warehouse.stkid = 73,
					 tbl_warehouse.locid = REPgetLmisLocationCode($districtId),
					 tbl_warehouse.stkofficeid = 111,
					 tbl_warehouse.hf_type_id = 19,
					 tbl_warehouse.reporting_start_month = DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL -1 MONTH), '%Y-%m-01'),
					 tbl_warehouse.dhis_code = '$cmwCode'";
		mysql_query($insWHQry);
		$wh_id = mysql_insert_id();
		/*$insert_status = "INSERT INTO warehouse_status_history
					SET
						warehouse_status_history.warehouse_id = ".$wh_id.",
						warehouse_status_history.`status` = 1,
						warehouse_status_history.reporting_month = DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL -1 MONTH), '%Y-%m-01'),
						warehouse_status_history.created_date = NOW()";
		mysql_query($insert_status);*/
		
		// Get User ID
		$whUserQry = "SELECT
						wh_user.sysusrrec_id
					FROM
						tbl_warehouse
					INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
					INNER JOIN sysuser_tab ON wh_user.sysusrrec_id = sysuser_tab.UserID
					WHERE
						tbl_warehouse.stkid = 73
					AND tbl_warehouse.dist_id = REPgetLmisLocationCode($districtId)
						LIMIT 1";
		$qryRes = mysql_fetch_array(mysql_query($whUserQry));
		$userId = $qryRes['sysusrrec_id'];
		// Assign warehosue to the user
		$insWHUserQry = "INSERT INTO wh_user
					SET
						wh_user.sysusrrec_id = $userId,
						wh_user.wh_id = $wh_id";
		mysql_query($insWHUserQry);
	}
}

// Set all to in-active
$cmws = "UPDATE tbl_warehouse
		SET tbl_warehouse.is_active = 0
		WHERE
			tbl_warehouse.stkid = 73
		AND tbl_warehouse.stkofficeid = 111";
mysql_query($cmws);

//Update 
$updateCMWs = "UPDATE tbl_warehouse
			SET tbl_warehouse.is_active = 1
			WHERE
				tbl_warehouse.stkid = 73
			AND tbl_warehouse.stkofficeid = 111
			AND tbl_warehouse.dhis_code IN ("."'" . implode("','", $cmwList) . "'".")";
mysql_query($updateCMWs);

// Insert all inactive into status history
$status_history = "INSERT INTO warehouse_status_history (
						warehouse_status_history.warehouse_id,
						warehouse_status_history.`status`,
						warehouse_status_history.reporting_month,
						warehouse_status_history.created_date
					) SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.is_active,
						DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL -1 MONTH), '%Y-%m-01'),
						NOW()
					FROM
						tbl_warehouse
					WHERE
						tbl_warehouse.is_active = 0";
mysql_query($status_history);
if(!empty($cmwList))
{
	$delQry = "DELETE
			FROM
				warehouses_by_month
			WHERE
				warehouses_by_month.reporting_date = DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL -1 MONTH), '%Y-%m-01')";
	mysql_query($delQry);
	
	// Update Total Warehouse Table
	$whByMonth = "INSERT INTO warehouses_by_month (
				warehouses_by_month.total_stores,
				warehouses_by_month.`level`,
				warehouses_by_month.stakeholder_id,
				warehouses_by_month.district_id,
				warehouses_by_month.reporting_date
			) SELECT
				COUNT(DISTINCT A.wh_id) AS totalStores,
				A.lvl,
				A.stkid,
				A.dist_id,
				DATE_FORMAT(DATE_ADD(CURDATE(),INTERVAL -1 MONTH), '%Y-%m-01') AS rptDate
			FROM
				(
					SELECT DISTINCT
						tbl_warehouse.stkid,
						tbl_warehouse.dist_id,
						stakeholder.lvl,
						tbl_warehouse.wh_id
					FROM
						tbl_warehouse
					INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						stakeholder.lvl = 7
					AND tbl_warehouse.is_active = 1
					ORDER BY
						stakeholder.lvl ASC
				) A
			GROUP BY
				A.dist_id,
				A.stkid,
				A.lvl
			ORDER BY
				A.lvl";
	mysql_query($whByMonth);
}

// Lock Data Entry if data is not entered on-time
$HFLockQry = "SELECT
			tbl_warehouse.wh_id,
			tbl_warehouse.stkid,
			tbl_warehouse.prov_id,
			tbl_warehouse.is_lock_data_entry,
			Max(tbl_hf_data.reporting_date) AS lastReported,
			tbl_warehouse.reporting_start_month,
			TIMESTAMPDIFF(MONTH, Max(tbl_hf_data.reporting_date), CURDATE()) AS diff1,
			TIMESTAMPDIFF(MONTH, tbl_warehouse.reporting_start_month, CURDATE()) AS diff2
		FROM
			tbl_warehouse
		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		LEFT JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
		WHERE
			stakeholder.lvl = 7 AND
			tbl_warehouse.prov_id = 2 AND
			tbl_warehouse.stkid = 1
		GROUP BY
			tbl_warehouse.wh_id";
$qryRes = mysql_query($HFLockQry);
while ( $row = mysql_fetch_array($qryRes) )
{
	$wh_Id = $row['wh_id'];
	if ((empty($row['diff1']) && $row['diff2'] >= 2) || $row['diff1'] > 2 ){
		$qry = "UPDATE tbl_warehouse
			SET
				is_lock_data_entry = 1
			WHERE
				tbl_warehouse.wh_id = " . $wh_Id;
		mysql_query($qry);
	}
}

$distStoreLockQry = "SELECT
			tbl_warehouse.wh_id,
			tbl_warehouse.stkid,
			tbl_warehouse.prov_id,
			tbl_warehouse.is_lock_data_entry,
			Max(tbl_wh_data.RptDate) AS lastReported,
			tbl_warehouse.reporting_start_month,
			TIMESTAMPDIFF(MONTH, Max(tbl_wh_data.RptDate), CURDATE()) AS diff1,
			TIMESTAMPDIFF(MONTH, tbl_warehouse.reporting_start_month, CURDATE()) AS diff2
		FROM
			tbl_warehouse
		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		LEFT JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
		WHERE
			stakeholder.lvl = 3 AND
			tbl_warehouse.prov_id = 2 AND
			tbl_warehouse.stkid = 1
		GROUP BY
			tbl_warehouse.wh_id";
$qryRes = mysql_query($distStoreLockQry);
while ( $row = mysql_fetch_array($qryRes) )
{
	$wh_Id = $row['wh_id'];
	if ((empty($row['diff1']) && $row['diff2'] >= 2) || $row['diff1'] > 2 ){
		$qry = "UPDATE tbl_warehouse
			SET
				is_lock_data_entry = 1
			WHERE
				tbl_warehouse.wh_id = " . $wh_Id;
		mysql_query($qry);
		
	}
}


// Create the trigger again
$create_trigger_again = "DELIMITER $$
						CREATE DEFINER=`clmisuser`@`localhost` TRIGGER `AddWarehouseStatusOnUpdate` AFTER UPDATE ON `tbl_warehouse` FOR EACH ROW BEGIN
							IF(NEW.is_active != OLD.is_active) THEN
								INSERT INTO warehouse_status_history(warehouse_id, status, reporting_month) VALUES (OLD.wh_id, NEW.is_active, DATE_FORMAT(CURDATE(), '%Y-%m-01'));
							END IF;
						END$$";
mysql_query($create_trigger_again);

// Create the trigger again
$create_trigger_again = "DELIMITER $$
						CREATE DEFINER=`clmisuser`@`localhost` TRIGGER `UpdateActiveWHHistory` AFTER UPDATE ON `warehouse_status_history` FOR EACH ROW BEGIN
							CALL REPUpdateActiveWHSummary(NEW.warehouse_id, NEW.reporting_month);
						END$$";
mysql_query($create_trigger_again);

$msg = "District wise deployed CMW list <pre>" . print_r($cmwsArr, true) . '</pre>';
// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg, 150);
// send email
mail("waqas@deliver-pk.org","Active CMW's List",$msg);