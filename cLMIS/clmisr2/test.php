
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/Charts/fusioncharts.js"></SCRIPT>
<?php
include("html/adminhtml.inc.php");
include("FusionCharts/Code/PHP/Includes/FusionCharts.php");

$db_name="r2";
$host="192.168.1.72";
$UserID="vlmis";
$Password="v123lmis";

/*$UserID ='testr2clmis';
$Password='VU3jo5mofV';
$host='localhost';
$db_name='testr2clmis';
*/
$connection=mysql_connect("$host","$UserID","$Password") or die("Could not connect to server");
$db=mysql_select_db($db_name,$connection) or die("Could not select database");

// Ajax Call
if ( $_REQUEST['param'] )
{
	$dataArr = explode('|', $_REQUEST['param']);
	$provId = $dataArr[0];
	$itemId = $dataArr[1];
	$date = $dataArr[2];
	$type = $dataArr[3];
	if ( $type == 1 )
	{
		$caption = 'Reported Status';
		$qry = "SELECT
					B.districtId,
					B.districtName,
					ROUND(((A.reported / B.totalWH) * 100)) AS perVal
				FROM
					(
						SELECT
							District.pk_id AS districtId,
							COUNT(DISTINCT UC.pk_id) AS reported
						FROM
							locations AS District
						INNER JOIN locations AS UC ON District.pk_id = UC.district_id
						INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
						INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id = 6
						AND District.province_id = $provId
						AND warehouses_data.reporting_start_date = '$date'
						AND warehouses_data.issue_balance IS NOT NULL
						GROUP BY
							District.pk_id
						ORDER BY
							districtId ASC
					) A
				RIGHT JOIN (
					SELECT
						District.pk_id AS districtId,
						District.location_name AS districtName,
						COUNT(DISTINCT UC.pk_id) AS totalWH
					FROM
						locations AS District
					INNER JOIN locations AS UC ON District.pk_id = UC.district_id
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					WHERE
						stakeholders.geo_level_id = 6
					AND District.province_id = $provId
					GROUP BY
						District.pk_id
					ORDER BY
						districtId ASC
				) B ON A.districtId = B.districtId";
	}
	else
	{
		$caption = 'Non-reporting Status';
		$qry = "SELECT
					B.districtId,
					B.districtName,
					ROUND((((B.totalWH - A.reported) / B.totalWH) * 100)) AS perVal
				FROM
					(
						SELECT
							District.pk_id AS districtId,
							COUNT(DISTINCT UC.pk_id) AS reported
						FROM
							locations AS District
						INNER JOIN locations AS UC ON District.pk_id = UC.district_id
						INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
						INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
						INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
						WHERE
							stakeholders.geo_level_id = 6
						AND District.province_id = $provId
						AND warehouses_data.reporting_start_date = '$date'
						AND warehouses_data.issue_balance IS NOT NULL
						GROUP BY
							District.pk_id
						ORDER BY
							districtId ASC
					) A
				RIGHT JOIN (
					SELECT
						District.pk_id AS districtId,
						District.location_name AS districtName,
						COUNT(DISTINCT UC.pk_id) AS totalWH
					FROM
						locations AS District
					INNER JOIN locations AS UC ON District.pk_id = UC.district_id
					INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
					INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
					WHERE
						stakeholders.geo_level_id = 6
					AND District.province_id = $provId
					GROUP BY
						District.pk_id
					ORDER BY
						districtId ASC
				) B ON A.districtId = B.districtId";
	}
	$xmlstore = "<chart yAxisMaxValue='100' exportEnabled='1' exportAction='Download' caption='$caption' exportFileName='Reporting Status ".date('Y-m-d H:i:s')."' numberSuffix='%' showValues='1'>";	
	$qryRes = mysql_query($qry);
	while ( $row = mysql_fetch_array($qryRes) )
	{
		$xmlstore .= "<set label='$row[districtName]' value='$row[perVal]' />";
	}
	$xmlstore .= "</chart>";
	
	FC_SetRenderer('javascript');
	echo renderChart("FusionCharts/Charts/Column2D.swf", "", $xmlstore, 'districtRRStatus', 450, 395, false, false);
	
	
	exit;
}

$date = '2014-07-01';
$provId = 1;
$itemId = 6;
$qry = "SELECT
				B.province_id,
				ROUND((A.reportedUC / B.totalUC) * 100) AS reportedPer,
				ROUND(((B.totalUC - A.reportedUC) / B.totalUC) * 100) AS nonReportedPer,
				A.item_pack_size_id
			FROM
				(
					SELECT
						COUNT(DISTINCT locations.pk_id) AS reportedUC,
						locations.province_id,
						warehouses_data.item_pack_size_id
					FROM
						locations
					INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
					INNER JOIN warehouses_data ON warehouses.pk_id = warehouses_data.warehouse_id
					WHERE
						locations.province_id = $provId
					AND locations.geo_level_id = 6
					AND warehouses_data.item_pack_size_id = $itemId
					AND DATE_FORMAT(
						warehouses_data.reporting_start_date,
						'%Y-%m-%d'
					) = '$date'
				) A
			RIGHT JOIN (
				SELECT
					COUNT(locations.pk_id) AS totalUC,
					locations.province_id
				FROM
					locations
				WHERE
					locations.geo_level_id = 6
				AND locations.province_id = $provId
			) B ON A.province_id = B.province_id";
$qryRes = mysql_query($qry);
$row = mysql_fetch_array($qryRes);
$reported = $row['reportedPer'];
$nonReported = $row['nonReportedPer'];
$provinceId = $row['province_id'];
$itemPackId = $row['item_pack_size_id'];

$param = $provinceId.'|'.$itemPackId.'|'.$date;

$xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Reporting Status' exportFileName='Reporting Status ".date('Y-m-d H:i:s')."' numberSuffix='%' showValues='1'>";
$xmlstore .= "<set label='Reported' value='$reported' link=\"JavaScript:showData('$param|1');\" />";
$xmlstore .= "<set label='Non Reported' value='$nonReported' link=\"JavaScript:showData('$param|2');\" />";
$xmlstore .= "</chart>";

FC_SetRenderer('javascript');
echo renderChart("FusionCharts/Charts/Pie3D.swf", "", $xmlstore, 'rrStatus', 450, 395, false, false);
?>
<div id="data"></div>
<script src="assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script>
function showData(param){
	$('#data').html('');
	$.ajax({
		type: "POST",
		url: 'test.php',
		data: {param: param},
		success: function(data) {
			$('#data').html(data);
		}
	});
}
</script>