<?php
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");   //Include Global Function File  
include("FusionCharts/Code/PHP/Includes/FusionCharts.php");

$varArr = explode('|', base64_decode($_REQUEST['param']));

$startDate = $varArr[0];
$endDate = $varArr[1];
$whId = $varArr[2];
$whName = $varArr[3];


$begin = new DateTime( $startDate );
$end = new DateTime( $endDate );
$diff = $begin->diff($end);
$totalMonths = (($diff->format('%y') * 12) + $diff->format('%m')) + 1; // +1 to include the current Month
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);

$fileSaveName = $whName.' Shipment '.$begin->format('M Y')." to ".$end->format('M Y');
?>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/Code/FusionCharts/FusionCharts.js"></SCRIPT>
<script type="text/javascript">
	function exportChart(exportFormat, chartID)
	{
		// checks if exportChart function is present and call exportChart function
		if ( FusionCharts(chartID).exportChart )
			FusionCharts(chartID).exportChart( { "exportFormat" : exportFormat } );
		else
			alert ( "Please wait till the chart completes rendering..." );
	}
</script>

<div style="width:900px; text-align:right;">
    <img src="../../images/PDF.png" onClick="JavaScript:exportChart('PDF', 'abc')" style="cursor:pointer;" />
    <img src="../../images/JPG.png" onClick="JavaScript:exportChart('JPG', 'abc')" style="cursor:pointer;" />
    <img src="../../images/PNG.png" onClick="JavaScript:exportChart('PNG', 'abc')" style="cursor:pointer;" />
</div>
<?php
# lineColor='8E9D51'
$xmlstore = "<chart exportFileName='$fileSaveName' labelPadding='3' canvasPadding='30' exportEnabled='1' exportAction='Download' caption='$whName Shipment Report' subCaption='' yAxisName='Number of Transactions' xAxisName='".$begin->format('M Y')." to ".$end->format('M Y')."' showValues='1' formatNumberScale='0' exportHandler='FusionCharts/Code/PHP/ExportHandler/FCExporter.php' exportAtClient='0' >\n";
$xmlstore .="<categories>\n";
foreach ( $period as $date )
{
	$xmlstore .="\t<category label='".$date->format( "M" )."'/>\n";
}
$xmlstore .="</categories>\n";
$xmlstore1 ="<dataset seriesName='Receive'>\n";
$xmlstore2 ="<dataset seriesName='Issue'>\n";

foreach ( $period as $date )
{
	$newQry = "SELECT 
					wh_id,
					wh_name,
					SUM(stockIssue) AS stockIssue,
					SUM(stockRcv) AS stockRcv
				FROM (
					SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
						SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
						tbl_stock_master.TranNo
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
					WHERE
						tbl_warehouse.wh_id = ".$whId."
						AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m' ) = '".$date->format( "Y-m" )."'
					GROUP BY
						tbl_warehouse.wh_id,
						tbl_stock_master.TranNo
					)AS A 
				GROUP BY
					wh_id
				ORDER BY
					wh_name";
	
	$qryRes = mysql_fetch_array(mysql_query($newQry));
	$rcv = (!empty($qryRes['stockRcv'])) ? $qryRes['stockRcv'] : 0;
	$issue = (!empty($qryRes['stockIssue'])) ? $qryRes['stockIssue'] : 0;
	$xmlstore1 .= "\t<set value='" .$rcv. "' />\n";
	$xmlstore2 .= "\t<set value='" .$issue. "' />\n";
}
$xmlstore1 .="</dataset>\n";
$xmlstore2 .="</dataset>\n";

$xmlstore .= $xmlstore1.$xmlstore2;
$xmlstore .= "</chart>";

// Call chart function to plot graph
echo renderChart("FusionCharts/Charts/StackedColumn3D.swf", "", $xmlstore, "abc", 900, 450, false, false);
			
?>