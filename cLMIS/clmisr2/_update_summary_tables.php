<?php
$UserID ='lmispcgo';
$Password='3#UsZ412y9T';
$host='localhost';
$db_name='clmis';


/*$db_name="clmis";
$host="localhost";
$UserID="lmispcgo";
$Password="3#UsZ412y9T";*/

$connection = mysql_connect("$host","$UserID","$Password") or die("Could not connect to server1");
$db = mysql_select_db($db_name,$connection) or die("Could not select database1");

// Get all districts
$qry = "SELECT
			tbl_wh_data.wh_id,
			tbl_wh_data.item_id,
			tbl_wh_data.report_month,
			tbl_wh_data.report_year,
			tbl_warehouse.stkid,
			tbl_warehouse.dist_id
		FROM
			tbl_wh_data
		INNER JOIN tbl_warehouse ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
		WHERE
			tbl_wh_data.is_calculated = 0
		GROUP BY
			tbl_wh_data.report_month,
			tbl_wh_data.report_year,
			tbl_wh_data.item_id,
			tbl_warehouse.dist_id,
			tbl_warehouse.stkid";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$whId = $row['wh_id'];
	$itmId = $row['item_id'];
	$month = $row['report_month'];
	$year = $row['report_year'];
	$stkid = $row['stkid'];
	$dist_id = $row['dist_id'];
	
	$addSummary = "CALL REPUpdateSummaryDistrict($whId, '$itmId', $month, $year);";
	mysql_query($addSummary);
	
	$updateQry = "UPDATE
					tbl_wh_data, tbl_warehouse
				SET tbl_wh_data.is_calculated = 1
				WHERE
					tbl_warehouse.wh_id = tbl_wh_data.wh_id
				AND tbl_wh_data.item_id = '$itmId'
				AND tbl_wh_data.report_month = $month
				AND tbl_wh_data.report_year = $year
				AND tbl_warehouse.stkid = $stkid
				AND tbl_warehouse.dist_id = $dist_id";
	mysql_query($updateQry);
}