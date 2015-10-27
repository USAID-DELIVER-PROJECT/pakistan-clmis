<?php
include('plmis_inc/common/CnnDb.php');

$qry = "SELECT DISTINCT
			summary_province.item_id,
			summary_province.stakeholder_id,
			summary_province.province_id,
			summary_province.reporting_date,
			MONTH(summary_province.reporting_date) AS rptMonth, 
			YEAR(summary_province.reporting_date) AS rptYear
		FROM
			summary_province";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$itmId = $row['item_id'];
	$stkid = $row['stakeholder_id'];
	$rptDate = $row['reporting_date'];
	$month = $row['prtMonth'];
	$year = $row['prtYear'];
	$provId = $row['province_id'];
	
	$getData = "SELECT
					ROUND(AVG(summary_district.reporting_rate)) AS RRPer,
					SUM(summary_district.total_health_facilities) AS TotalWH
				FROM
					summary_district
				WHERE
					summary_district.stakeholder_id = $stkid
				AND summary_district.item_id = '$itmId'
				AND summary_district.province_id = $provId
				AND summary_district.reporting_date = '$rptDate'";
	$getDataRes = mysql_fetch_array(mysql_query($getData));
	
	$updateQry = "UPDATE summary_province
				SET summary_province.reporting_rate = ".$getDataRes['RRPer'].",
				 summary_province.total_health_facilities = ".$getDataRes['TotalWH']."
				WHERE
					summary_province.item_id = '$itmId'
				AND summary_province.stakeholder_id = $stkid
				AND summary_province.reporting_date = '$rptDate'
				AND summary_province.province_id = $provId";
	mysql_query($updateQry);
}
$from = "support@lmis.gov.pk";
$to = "waqas@deliver-pk.org";
$subject = "Update province summary tables";
$message = "Province summary tables are updated";
$headers = "From:" . $from;
mail($to,$subject,$message, $headers);