<?php
include_once("plmis_inc/common/CnnDb.php");

$qry = "SELECT
			tbl_hf_data.warehouse_id,
			tbl_hf_data.item_id,
			tbl_hf_data.reporting_date
		FROM
			tbl_hf_data";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$whId = $row['warehouse_id'];
	$itmId = $row['item_id'];
	$rptDate = $row['reporting_date'];
	
	$addSummary = "CALL REPUpdateHFTypeFromHF($whId, '$itmId', '$rptDate');";
	mysql_query($addSummary);
}
$msg = "Data uploaded";
mail("waqas@deliver-pk.org", "tbl_hf_data", $msg);