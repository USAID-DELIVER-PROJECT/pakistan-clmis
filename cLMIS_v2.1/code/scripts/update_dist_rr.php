<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");

$qry = "SELECT DISTINCT
			summary_district.item_id,
			summary_district.stakeholder_id,
			summary_district.reporting_date,
			MONTH(summary_district.reporting_date) AS prtMonth,
			YEAR(summary_district.reporting_date) AS prtYear,
			summary_district.province_id,
			summary_district.district_id
		FROM
			summary_district";
$qryRes = mysql_query($qry);

while ( $row = mysql_fetch_array($qryRes) )
{
	$itmId = $row['item_id'];
	$stkid = $row['stakeholder_id'];
	$rptDate = $row['reporting_date'];
	$month = $row['prtMonth'];
	$year = $row['prtYear'];
	$provId = $row['province_id'];
	$distId = $row['district_id'];
	
	if ($rptDate >= '2015-01-01')
	{
		$lvl = " IN (3, 4, 7)";
	}
	else
	{
		$lvl = " <=4";
	}
	
	$getData = "SELECT
					A.TotalWH,
					ROUND(((COALESCE(B.RptWH, NULL, 0) / A.TotalWH) * 100), 2) AS RRPer
				FROM
					(
						SELECT
							tbl_locations.PkLocID,
							tbl_locations.LocName,
							COUNT(tbl_warehouse.wh_id) AS TotalWH
						FROM
							tbl_locations
						INNER JOIN tbl_warehouse ON tbl_locations.PkLocID = tbl_warehouse.dist_id
						INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
						WHERE tbl_warehouse.dist_id = $distId
						AND tbl_warehouse.prov_id = $provId
						AND tbl_warehouse.stkid = $stkid
						AND stakeholder.lvl $lvl
					) A
				LEFT JOIN (
					SELECT
						tbl_locations.PkLocID,
						COUNT(tbl_wh_data.wh_id) AS RptWH
					FROM
						tbl_locations
					INNER JOIN tbl_warehouse ON tbl_locations.PkLocID = tbl_warehouse.dist_id
					INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_wh_data.report_month = $month
					AND tbl_wh_data.report_year = $year
					AND tbl_wh_data.item_id = '$itmId'
					AND tbl_warehouse.dist_id = $distId
					AND tbl_warehouse.prov_id = $provId
					AND tbl_warehouse.stkid = $stkid
					AND stakeholder.lvl $lvl
				) B ON A.PkLocID = B.PkLocID ";
	$getDataRes = mysql_fetch_array(mysql_query($getData));
	
	$updateQry = "UPDATE summary_district
				SET summary_district.reporting_rate = '".$getDataRes['RRPer']."',
				 summary_district.total_health_facilities = ".$getDataRes['TotalWH']."
				WHERE
					summary_district.item_id = '$itmId'
				AND summary_district.stakeholder_id = $stkid
				AND summary_district.reporting_date = '$rptDate'
				AND summary_district.province_id = $provId
				AND summary_district.district_id = $distId";
	mysql_query($updateQry);
}
$from = "support@lmis.gov.pk";
$to = "waqas@deliver-pk.org";
$subject = "Update district Reporting Rate";
$message = "Reporting Rate is updated";
$headers = "From:" . $from;
mail($to,$subject,$message, $headers);