<?php
/**
 * shipment_main_dash
 * @package dashboard
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

//Including files
include("../includes/classes/Configuration.inc.php");
include(APP_PATH."includes/classes/db.php");
include(PUBLIC_PATH."/FusionCharts/Code/PHP/includes/FusionCharts.php");

if ( $_POST['year'] )
{
	$where = '';
        //Getting year
	$year = $_POST['year'];
        //Getting month
	$month = $_POST['month'];
	$startDate = $year.'-'.$month.'-01';
        //end date
	$endDate = date('Y-m-t', strtotime($startDate));
	$proFilter = $_POST['proFilter'];
	
	if ($proFilter == 2)
	{
		$proFilterText = "All Products Without Condom";
		$proFilter = " AND itminfo_tab.itmrec_id != 'IT-001' ";
	}
	else
	{
		$proFilterText = "All Products";
		$proFilter = "";
	}
}
$whId = 123;
$stkId = 1;
//Chart heading
$heading = date('M Y', strtotime($startDate));
//Chart caption
$caption = "Central Warehouse Distribution and Stock on Hand(SOH)";
//Chart heading sub Caption
$subCaption = $proFilterText.'('.$heading.')';
//download File Name
$downloadFileName = $caption . ' - ' . $subCaption . ' - ' . date('Y-m-d H:i:s');
//chart_id
$chart_id = 'distributionAndSOH';
?>
<div class="widget widget-tabs">    
    <div class="widget-body">
    <a href="javascript:exportChart('<?php echo $chart_id;?>', '<?php echo $downloadFileName;?>')" style="float:right;"><img class="export_excel" src="<?php echo PUBLIC_URL;?>images/excel-16.png" alt="Export" /></a>
	<?php 
        //Query for shipment main dashboard
	$qry = "SELECT
				itminfo_tab.itm_name,
				tbl_wh_data.item_id AS itm_id,
				SUM(tbl_wh_data.wh_issue_up) AS Issue,
				SUM(tbl_wh_data.wh_cbl_a) AS CB
			FROM
				tbl_wh_data
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				tbl_wh_data.report_month = $month
			AND tbl_wh_data.report_year = $year
			AND tbl_wh_data.wh_id = $whId
			$proFilter
			GROUP BY
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";
    //Query result
    $qryRes = mysql_query($qry);
    //xml for chart
    $xmlstore = "<chart xAxisNamePadding='0' yAxisNamePadding='0' chartLeftMargin='0' chartRightMargin='0' chartTopMargin='0' chartBottomMargin='0' theme='fint' numberScaleValue='1000,1000,1000' numberScaleUnit='K,M,B' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$caption' subCaption='$subCaption' exportFileName='$downloadFileName' yAxisName='Units' xAxisName='Products' showValues='1'>";
    //Populate xml
    while ( $row = mysql_fetch_array($qryRes) )
    {
        //name
        $data[$row['itm_id']]['name'] = $row['itm_name'];
        //issue
        $data[$row['itm_id']]['issue'] = (is_null($row['Issue'])) ? 0 : $row['Issue'];
        //CB
        $data[$row['itm_id']]['CB'] = (is_null($row['CB'])) ? 0 : $row['CB'];
    }
    
    $xmlstore .= "<categories>";
    foreach( $data as $key=>$name )
    {
        //name
        $xmlstore .= "<category label='$name[name]' />";
    }
    $xmlstore .= "</categories>";
    
    $xmlstore .= "<dataset seriesName='Issue'>";
    foreach( $data as $key=>$name )
    {
        //issue
        $xmlstore .= "<set value='$name[issue]' />";
    }
    $xmlstore .= "</dataset>";
    
    $xmlstore .= "<dataset seriesName='Stock on Hand'>";
    foreach( $data as $key=>$name )
    {
        //CB
        $xmlstore .= "<set value='$name[CB]' />";
    }
    $xmlstore .= "</dataset>";
    //end chart
    $xmlstore .= "</chart>";
    //Render chart
    FC_SetRenderer('javascript');
    echo renderChart(PUBLIC_URL."FusionCharts/Charts/MSColumn2D.swf", "", $xmlstore, $chart_id, '100%', 350, false, false);
    ?>
	</div>
</div>