<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");

$qry = "SELECT
			tbl_hf_mother_care.warehouse_id,
			tbl_hf_mother_care.reporting_date
		FROM
			tbl_hf_mother_care";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$whId = $row['warehouse_id'];
	$rptDate = $row['reporting_date'];
	
	$addSummary = "CALL REPUpdateHFTypeMotherCareData($whId, '$rptDate');";
	mysql_query($addSummary);
}
$msg = "Data uploaded";
mail("waqas@deliver-pk.org", "Mothercare Cases", $msg);