<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");

$qry = "SELECT
			tbl_hf_data.item_id,
			tbl_hf_data.reporting_date,
			tbl_hf_data.warehouse_id
		FROM
			tbl_hf_data
		INNER JOIN tbl_warehouse ON tbl_hf_data.warehouse_id = tbl_warehouse.wh_id
		WHERE
			tbl_warehouse.prov_id = 2
		AND tbl_warehouse.stkid = 9
		GROUP BY
			tbl_warehouse.dist_id,
			tbl_warehouse.stkid,
			tbl_hf_data.item_id,
			tbl_hf_data.reporting_date";
$qryRes = mysql_query($qry);

$count = 1;
while ( $row = mysql_fetch_array($qryRes) )
{
	$whId = $row['warehouse_id'];
	$itmId = $row['item_id'];
	$rptDate = $row['reporting_date'];
	
	$addSummary = "CALL REPUpdateHFData($whId, '$itmId', '$rptDate');";
	mysql_query($addSummary);
	
	$addSummary1 = "CALL REPUpdateHFTypeFromHF($whId, '$itmId', '$rptDate');";
	mysql_query($addSummary1);
	
	echo $count++.'<br>';
	ob_flush();
}
$msg = "Data uploaded";
mail("waqas@deliver-pk.org", "tbl_hf_data to tbl_wh_data", $msg);