<?php
include('plmis_inc/common/CnnDb.php');

$qry = "SELECT DISTINCT
			summary_national.item_id,
			summary_national.stakeholder_id,
			summary_national.reporting_date
		FROM
			summary_national";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$itmId = $row['item_id'];
	$stkid = $row['stakeholder_id'];
	$rptDate = $row['reporting_date'];
	$month = $row['prtMonth'];
	$year = $row['prtYear'];
	
	$getData = "SELECT
					ROUND(AVG(summary_district.reporting_rate)) AS RRPer,
					SUM(summary_district.total_health_facilities) AS TotalWH
				FROM
					summary_district
				WHERE
					summary_district.stakeholder_id = $stkid
				AND summary_district.item_id = '$itmId'
				AND summary_district.reporting_date = '$rptDate' ";
	$getDataRes = mysql_fetch_array(mysql_query($getData));
	
	$updateQry = "UPDATE summary_national
				SET summary_national.reporting_rate = ".$getDataRes['RRPer'].",
				 summary_national.total_health_facilities = ".$getDataRes['TotalWH']."
				WHERE
					summary_national.item_id = '$itmId'
				AND summary_national.stakeholder_id = $stkid
				AND summary_national.reporting_date = '$rptDate'";
	mysql_query($updateQry);
}
$from = "support@lmis.gov.pk";
$to = "waqas@deliver-pk.org";
$subject = "Update national summary tables";
$message = "National summary tables are updated";
$headers = "From:" . $from;
mail($to,$subject,$message, $headers);