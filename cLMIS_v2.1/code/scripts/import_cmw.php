<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");

$qry = "SELECT
			tbl_locations.PkLocID,
			wh_user.sysusrrec_id,
			cmw_master.cmwcode,
			cmw_master.cmw_name
		FROM
			tbl_locations
		INNER JOIN tbl_warehouse ON tbl_locations.PkLocID = tbl_warehouse.dist_id
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
		INNER JOIN cmw_master ON tbl_locations.dhis_code = cmw_master.distcode
		WHERE
			tbl_locations.ParentID = 1
		AND stakeholder.lvl = 3
		AND tbl_warehouse.stkid = 73";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$districtId = $row['PkLocID'];
	$userId = $row['sysusrrec_id'];
	$cmwCode = $row['cmwcode'];
	$cmwName = $row['cmw_name'];
	
	// Check if the facility exists
	$checkHF = "SELECT
					COUNT(tbl_warehouse.wh_id) AS num
				FROM
					tbl_warehouse
				WHERE
					tbl_warehouse.dhis_code = '$cmwCode' ";
	$checkHFRes = mysql_fetch_array(mysql_query($checkHF));
	if ( $checkHFRes['num'] == 0 ) // If not exists then add health facility
	{
		$qry = "INSERT INTO tbl_warehouse
			SET	 
				 tbl_warehouse.wh_name = '".$cmwName."',
				 tbl_warehouse.dist_id = ".$districtId.",
				 tbl_warehouse.prov_id = 1,
				 tbl_warehouse.stkid = 73,
				 tbl_warehouse.locid = ".$districtId.",
				 tbl_warehouse.stkofficeid = 111,
				 tbl_warehouse.hf_type_id = 19,
				 tbl_warehouse.dhis_code = '$cmwCode'";
		mysql_query($qry);
		$wh_id = mysql_insert_id();
		$qry = "INSERT INTO wh_user
				SET
					wh_user.sysusrrec_id = $userId,
					wh_user.wh_id = $wh_id";
		mysql_query($qry);
	}
}
$msg = "Data uploaded";
mail("waqas@deliver-pk.org", "Import CMWs", $msg);