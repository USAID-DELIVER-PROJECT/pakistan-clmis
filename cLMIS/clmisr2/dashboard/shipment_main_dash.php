<?php
include("../html/adminhtml.inc.php");
Login();

include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");

if ( $_POST['year'] )
{
	$where = '';
	$year = $_POST['year'];
	$month = $_POST['month'];
	$startDate = $year.'-'.$month.'-01';
	$endDate = date('Y-m-t', strtotime($startDate));
	$proFilter = $_POST['proFilter'];
	
	if ($proFilter == 2)
	{
		$proFilter = " AND itminfo_tab.itmrec_id != 'IT-001' ";
	}
	else
	{
		$proFilter = "";
	}
}
$whId = 123;
$stkId = 1;
//$whId = $_SESSION['userdata'][5];
//$stkId = $_SESSION['stkid'];

$heading = date('M Y', strtotime($startDate));
?>
<div class="widget widget-tabs">    
    <div class="widget-body">
	<?php 
    /*$qry = "SELECT
				B.itm_id,
				B.itm_name,
				A.CB,
				A.Issue
			FROM
			(
				SELECT
					stock_batch.item_id,
					SUM(
						IF (
							tbl_stock_detail.temp = 0
							AND tbl_stock_master.WHIDFrom = $whId
							OR tbl_stock_master.WHIDTo = $whId,
							(tbl_stock_detail.Qty),
							0
						)
					) AS CB,
					SUM(		
						IF (
							tbl_stock_master.TranTypeID = 2
							AND tbl_stock_detail.temp = 0
							AND tbl_stock_master.WHIDFrom = $whId,
							ABS(tbl_stock_detail.Qty),
							0
						)
					) AS Issue
				FROM
					itminfo_tab
				INNER JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
				INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
				INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
				WHERE
					tbl_stock_master.TranDate BETWEEN '$startDate' AND '$endDate'
				GROUP BY
					stock_batch.item_id
			) A
		RIGHT JOIN (
			SELECT
				itminfo_tab.itm_id,
				itminfo_tab.itm_name
			FROM
				itminfo_tab
			INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
			WHERE
				stakeholder_item.stkid = $stkId
			ORDER BY
				itminfo_tab.frmindex
		) B ON A.item_id = B.itm_id
		";*/
	
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
				tbl_wh_data.item_id";
    $qryRes = mysql_query($qry);
    $xmlstore = "<chart theme='fint' numberScaleValue='1000,1000,1000' numberScaleUnit='K,M,B' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Central Warehouse Distribution and Stock on Hand(SOH)' subCaption='All Products ($heading)' exportFileName='Product wise Shipment " . date('Y-m-d H:i:s') . "' yAxisName='Units' xAxisName='Products' showValues='1'>";
    while ( $row = mysql_fetch_array($qryRes) )
    {
        $data[$row['itm_id']]['name'] = $row['itm_name'];
        $data[$row['itm_id']]['issue'] = (is_null($row['Issue'])) ? 0 : $row['Issue'];
        $data[$row['itm_id']]['CB'] = (is_null($row['CB'])) ? 0 : $row['CB'];
    }
    
    $xmlstore .= "<categories>";
    foreach( $data as $key=>$name )
    {
        $xmlstore .= "<category label='$name[name]' />";
    }
    $xmlstore .= "</categories>";
    
    $xmlstore .= "<dataset seriesName='Issue'>";
    foreach( $data as $key=>$name )
    {
        $xmlstore .= "<set value='$name[issue]' />";
    }
    $xmlstore .= "</dataset>";
    
    $xmlstore .= "<dataset seriesName='Stock on Hand'>";
    foreach( $data as $key=>$name )
    {
        $xmlstore .= "<set value='$name[CB]' />";
    }
    $xmlstore .= "</dataset>";
    
    $xmlstore .= "</chart>";
    
    FC_SetRenderer('javascript');
    echo renderChart("../FusionCharts/Charts/MSColumn2D.swf", "", $xmlstore, 'shipmentStatus', '100%', 380, false, false);
    ?>
	</div>
</div>