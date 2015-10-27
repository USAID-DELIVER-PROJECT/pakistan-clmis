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
	
	$startDate = date('Y-m-d', strtotime("-3 month", strtotime(dateToDbFormat($fromDate))));
	$endDate = dateToDbFormat($fromDate);
}
else
{
	$toDate = date('d/m/Y');
	$fromDate = date('d/m/Y', strtotime("-3 month", strtotime(dateToDbFormat($toDate))));
	
	$startDate = $startDate = date('Y-m-d', strtotime("-3 month", strtotime(dateToDbFormat($fromDate))));
	$endDate = dateToDbFormat($fromDate);
}
$mosDate = dateToDbFormat($toDate);

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

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!--<div class="pageLoader"></div>-->
<!-- BEGIN HEADER -->
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>
<style>
input[type="radio"].radio{margin-top:0px !important;}
</style>
<div class="page-container">
<?php include "../plmis_inc/common/_top.php";?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabsbar">
                        <ul>
                            <li><a href="shipment.php"> <b>Distribution and SOH</b></a></li>
                            <li><a href="expiry_schedule.php"> <b>Expiry Status</b></a></li>
                            <li><a href="stock_summary.php"> <b>Product Summary</b></a></li>
                            <li class="active"><a href="#"> <b>Month of Stock</b></a></li>
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
                                <button type="submit" id="search" value="search" class="btn btn-primary input-sm">Go</button>
                            </div>
                        </div>
                	</div>
				</form>
            </div>
            <div class="row">
            	<div class="col-md-12">
                	<div class="widget widget-tabs">
                    	<div class="widget-body">
                        	<?php 
							$qry = "SELECT
										itminfo_tab.itm_name,
										itminfo_tab.itm_type,
										ROUND(A.CB / A.avgCons, 1) AS MOS
									FROM
										(
											SELECT
												itminfo_tab.itm_id,
												SUM(tbl_stock_detail.Qty) AS CB,
												(
													SELECT
														ROUND(SUM(ABS(tbl_stock_detail.Qty)) / 3)
													FROM
														tbl_stock_master
													INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
													INNER JOIN stock_batch ON stock_batch.batch_id = tbl_stock_detail.BatchID
													WHERE
														tbl_stock_master.TranTypeID = 2
													AND tbl_stock_master.TranDate BETWEEN '$startDate' AND '$endDate'
													AND tbl_stock_master.WHIDFrom = $whId
													AND stock_batch.item_id = itminfo_tab.itm_id
												) AS avgCons
											FROM
												itminfo_tab
											INNER JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
											INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
											INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
											INNER JOIN tbl_trans_type ON tbl_stock_master.TranTypeID = tbl_trans_type.trans_id
											WHERE
												DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') < '$mosDate'
											AND (tbl_stock_master.WHIDFrom = $whId OR tbl_stock_master.WHIDTo = $whId)
											GROUP BY
												itminfo_tab.itm_id
											ORDER BY
												itminfo_tab.frmindex ASC
										) A
									RIGHT JOIN itminfo_tab ON itminfo_tab.itm_id = A.itm_id
									INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
									WHERE 
										itminfo_tab.itm_category = 1
									AND stakeholder_item.stkid = 1
									ORDER BY
										itminfo_tab.frmindex";
							$qryRes = mysql_query($qry);
							$xmlstore = "<chart theme='fint' exportEnabled='1' labelDisplay='rotate' slantLabels='1' exportAction='Download' caption='MOS at the end of selected time period (All Products)' subCaption='($fromDate to $toDate)' exportFileName='MOS " . date('Y-m-d H:i:s') . "' yAxisName='No. of Months' xAxisName='Products' showValues='1'>";
							while ( $row = mysql_fetch_array($qryRes) )
							{
								$xmlstore .= "<set label='$row[itm_name]' value='$row[MOS]' />";
							}
							$xmlstore .= "</chart>";
							
							FC_SetRenderer('javascript');
							echo renderChart("../FusionCharts/Charts/Column2D.swf", "", $xmlstore, 'quarterlyMOS', '100%', 500, false, false);
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
</body>
<!-- END BODY -->
</html>