<?php
include("../html/adminhtml.inc.php");
Login();

include "../plmis_inc/common/_header.php";
include "../plmis_inc/common/top_im.php";
include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");
include "../plmis_admin/Includes/functions.php";

if ( isset($_REQUEST['from_date']) && isset($_REQUEST['to_date']) )
{
	$fromDate = $_REQUEST['from_date'];
	$toDate = $_REQUEST['to_date'];
	
	$endDate = dateToDbFormat($_REQUEST['to_date']);
	$startDate = dateToDbFormat($_REQUEST['from_date']);
}
else
{
	$toDate = date('d/m/Y');
	$fromDate = date('01/m/Y', strtotime("-2 month", strtotime(date('Y-m-d'))));
	
	$endDate = date('Y-m-d');
	$startDate = date('Y-m-01', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
$whId = $_SESSION['userdata'][5];
$stkId = $_SESSION['stkid'];
?>
<!--[if IE]>
<style type="text/css">
    .box { display: block; }
    #box { overflow: hidden;position: relative; }
    b { position: absolute; top: 0px; right: 0px; width:1px; height: 251px; overflow: hidden; text-indent: -9999px; }
</style>

</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
<!--<div class="pageLoader"></div>-->
<!-- BEGIN HEADER -->
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>

<div class="page-container">
<?php include "../plmis_inc/common/_top.php";?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabsbar">
                        <ul>
                            <li class="active"><a href="#"> <b>Distribution and SOH</b></a></li>
                            <li><a href="expiry_schedule.php"> <b>Expiry Status</b></a></li>
                            <li><a href="stock_summary.php"> <b>Product Summary</b></a></li>
                            <li><a href="mos.php"> <b>Month of Stock</b></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
            	<form name="frm" id="frm" action="" method="post">
                	<div class="col-md-12">
                    	<div class="col-md-2">
                            <label>Date From</label>
                            <div class="form-group">
                                <input name="from_date" id="from_date" class="form-control input-sm" readonly value="<?php echo $fromDate;?>" />
                            </div>
                        </div>
                    	<div class="col-md-2">
                            <label>Date To</label>
                            <div class="form-group">
                                <input name="to_date" id="to_date" class="form-control input-sm" readonly value="<?php echo $toDate;?>" />
                            </div>
                        </div>
                    	<div class="col-md-2">
                            <label>&nbsp;</label>
                            <div class="form-group">
                                <button type="submit" id="expiry_search" value="search" class="btn btn-primary input-sm">Go</button>
                            </div>
                        </div>
                	</div>
				</form>
            </div>
            <div class="row">
            	<div class="col-md-12">
                	<div class="widget widget-tabs">
                    	<div class="widget-body">
                        	<a href="javascript:exportChart('shipmentStatus')" style="float:right;"><img style="position:absolute; z-index:1; height:18px; margin-left:-50px; margin-top:5px;" src="../images/excel-16.png" alt="Export" /></a>
                        	<?php 
							$qry = "SELECT
										B.itm_id,
										B.itm_name,
										ROUND(A.CB / B.qty_carton) AS CB,
										ROUND(A.Issue / B.qty_carton) AS Issue,
										ROUND(A.Rcv / B.qty_carton) AS Rcv
									FROM
									(
										SELECT
											stock_batch.item_id,
											SUM(
												IF (
													tbl_stock_detail.temp = 0
													AND (tbl_stock_master.WHIDFrom = $whId
													OR tbl_stock_master.WHIDTo = $whId),
													(tbl_stock_detail.Qty),
													0
												)
											) AS CB,
											SUM(		
												IF (
													tbl_stock_master.TranTypeID = 2
													AND tbl_stock_detail.temp = 0
													AND tbl_stock_master.WHIDFrom = $whId
													AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate',
													ABS(tbl_stock_detail.Qty),
													0
												)
											) AS Issue,
											SUM(		
												IF (
													tbl_stock_master.TranTypeID = 1
													AND tbl_stock_detail.temp = 0
													AND tbl_stock_master.WHIDTo = $whId
													AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') BETWEEN '$startDate' AND '$endDate',
													ABS(tbl_stock_detail.Qty),
													0
												)
											) AS Rcv
										FROM
											itminfo_tab
										INNER JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
										INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
										INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
										WHERE
											DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate'
										GROUP BY
											stock_batch.item_id
									) A
								RIGHT JOIN (
									SELECT
										itminfo_tab.itm_id,
										itminfo_tab.itm_name,
										itminfo_tab.qty_carton
									FROM
										itminfo_tab
									INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
									WHERE
										stakeholder_item.stkid = $stkId
									AND itminfo_tab.itm_category = 1
									ORDER BY
										itminfo_tab.frmindex
								) B ON A.item_id = B.itm_id";
							$qryRes = mysql_query($qry);
							$xmlstore = "<chart theme='fint' formatNumberScale='0' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Distribution and Stock on Hand(SOH) (All Products)' subCaption='($fromDate to $toDate)' exportFileName='Product wise Shipment " . date('Y-m-d H:i:s') . "' yAxisName='No. of Cartons' xAxisName='Products' showValues='1'>";
							while ( $row = mysql_fetch_array($qryRes) )
							{
								$data[$row['itm_id']]['name'] = $row['itm_name'];
								$data[$row['itm_id']]['issue'] = $row['Issue'];
								$data[$row['itm_id']]['rcv'] = $row['Rcv'];
								$data[$row['itm_id']]['CB'] = $row['CB'];
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
							
							$xmlstore .= "<dataset seriesName='Receive'>";
							foreach( $data as $key=>$name )
							{
								$xmlstore .= "<set value='$name[rcv]' />";
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
							echo renderChart("../FusionCharts/Charts/MSColumn2D.swf", "", $xmlstore, 'shipmentStatus', '100%', 500, false, false);
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../plmis_inc/common/footer.php";?>

<script type="text/javascript">
	$(function(){
		$("#from_date, #to_date").datepicker({
			dateFormat: 'dd/mm/yy',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	})
</script>
<SCRIPT LANGUAGE="JavaScript">
	var win = window,
	doc = win.document,
	encode = win.encodeURIComponent || win.escape;
	function exportChart(chartId)
	{
		if (chartId=="shipmentStatus")
		{
			var chartObj = FusionCharts(chartId);  
			var csvData = chartObj.getDataAsCSV();
			//window.alert(chartObj.getDataAsCSV());
			
			var exportFormat = 'csv',
			temporaryElement,
			obj,
			key;
			
			if (exportFormat === 'csv')
			{
				temporaryElement = doc.createElement('a');
				// We set the attributes of the temporary anchor element that
				// in such fashion as clicking on it induces download of the
				// CSV data from the chart.
				for (key in (obj = {
					href: 'data:attachment/csv,' + encode(csvData),
					target: '_blank',
					download: chartId+'.csv'
				}))
				{
					temporaryElement.setAttribute(key, obj[key]);
				}
				doc.body.appendChild(temporaryElement);
				
				// We emulate clicking by calling the click event handler and
				// post that get rid of the anchor to save very precious memory.
				temporaryElement.click();
				temporaryElement.parentNode.removeChild(temporaryElement);
				temporaryElement = null;
			}
			
			return;
		}
	}
</SCRIPT>
</body>
<!-- END BODY -->
</html>