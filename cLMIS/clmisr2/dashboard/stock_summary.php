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
	$fundingSource = $_REQUEST['funding_source'];
	if ( $fundingSource != 'all' ){
		$fundingSrcFilter = " AND stock_batch.funding_source = $fundingSource";
	}
	
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

$qry = "SELECT
			itminfo_tab.itm_name,
			itminfo_tab.itm_type,
			SUM(IF (DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') < '$startDate', tbl_stock_detail.Qty, 0)) AS OB,
			SUM(IF (DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate' AND tbl_stock_master.TranTypeID = 1, tbl_stock_detail.Qty, 0)) AS Rcv,
			SUM(IF (DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate' AND tbl_stock_master.TranTypeID = 2, ABS(tbl_stock_detail.Qty), 0)) AS Issue,
			SUM(IF (DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate' AND tbl_stock_master.TranTypeID > 2 AND tbl_trans_type.trans_nature = '+', tbl_stock_detail.Qty, 0)) AS AdjPos,
			ABS(Sum(IF (DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate' AND tbl_stock_master.TranTypeID > 2 AND tbl_trans_type.trans_nature = '-', tbl_stock_detail.Qty, 0))) AS AdjNeg,
			SUM(tbl_stock_detail.Qty) AS CB
		FROM
			itminfo_tab
		INNER JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
		LEFT JOIN tbl_warehouse ON stock_batch.funding_source = tbl_warehouse.wh_id
		INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
		INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
		INNER JOIN tbl_trans_type ON tbl_stock_master.TranTypeID = tbl_trans_type.trans_id
		WHERE
			DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m-%d') <= '$endDate'
		AND (tbl_stock_master.WHIDFrom = $whId OR tbl_stock_master.WHIDTo = $whId)
		$fundingSrcFilter
		GROUP BY
			itminfo_tab.itm_id
		ORDER BY
			itminfo_tab.frmindex ASC";
$qryRes = mysql_query($qry);
$num = mysql_num_rows($qryRes);
$xmlstore = '<?xml version="1.0"?><rows>';
$i = 1;
while ( $row = mysql_fetch_array($qryRes) )
{
	$itemName = $row['itm_name'];
	$xmlstore .= '<row>';
	$xmlstore .= '<cell>'.$i++.'</cell>';
	$xmlstore .= '<cell>'.$row['itm_name'].'</cell>';
	$xmlstore .= '<cell>'.$row['itm_type'].'</cell>';
	$xmlstore .= '<cell>'.number_format($row['OB']).'</cell>';
	$xmlstore .= '<cell>'.number_format($row['Rcv']).'</cell>';
	$xmlstore .= '<cell>'.number_format($row['Issue']).'</cell>';
	$xmlstore .= '<cell>'.number_format($row['AdjPos']).'</cell>';
	$xmlstore .= '<cell>'.number_format($row['AdjNeg']).'</cell>';
	$xmlstore .= '<cell>'.number_format($row['CB']).'</cell>';
	$xmlstore .= '</row>';
}
$xmlstore .= '</rows>';
?>
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
<style>
.objbox{overflow-x:hidden !important;}
</style>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>

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
                            <li class="active"><a href="#"> <b>Product Summary</b></a></li>
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
                            <label>Funding Source</label>
                            <div class="form-group">
                                <select name="funding_source" id="funding_source" class="form-control input-sm">
                                	<option value="all">All</option>
                                    <?php
									$qry = "SELECT
												tbl_warehouse.wh_id,
												tbl_warehouse.wh_name
											FROM
												stakeholder
											INNER JOIN tbl_warehouse ON stakeholder.stkid = tbl_warehouse.stkofficeid
											WHERE
												stakeholder.stk_type_id = 2
											ORDER BY
												stakeholder.stkorder ASC";
									$qryRes = mysql_query($qry);
									while ($row = mysql_fetch_array($qryRes))
									{
										$selected = ($row['wh_id'] == $fundingSource) ? 'selected' : '';
										echo "<option value=\"$row[wh_id]\" $selected>$row[wh_name]</option>";
									}
									?>
                                </select>
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
                	<div class="widget-head" style="padding:0px !important;">
                    <?php
					if ($num > 0)
					{?>
                        <table width="100%">
                            <tr>
                                <td style="text-align:right;">
                                    <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                    <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="mygrid_container" style="width:100%; height:360px;"></div>
                                </td>
                            </tr>
                        </table>
                    <?php
					}else{
						echo "No record found.";
					}
					?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../plmis_inc/common/footer.php";?>
<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold;'>Stock Summary (<?php echo $fromDate . '  to' . $toDate;?>)</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<span>Sr. No.</span>,<span>Product</span>,<span>Unit</span>,<span>Opening Quantity</span>,<span>Received Quantity</span>,<span>Issued Quantity</span>,<div style='text-align:center;'>Adjustments</div>,#cspan,<span>Closing Quantity</span>");
		mygrid.attachHeader("#rspan,#rspan,#rspan,#rspan,#rspan,#rspan,<div style='text-align:center;'>(+)</div>, <div style='text-align:center !important;'>(-)</div>,#rspan");
        mygrid.setInitWidths("60,*,80,130,130,130,100,100,130");
        mygrid.setColAlign("center,left,left,right,right,right,right,right,right");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro");

        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>')
    }

</script>
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