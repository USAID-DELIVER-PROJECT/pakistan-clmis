<?php
/**
 * shipment
 * @package dashboard
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include Configuration
include("../application/includes/classes/Configuration.inc.php");
//login
//Login();

//include db
include(APP_PATH . "includes/classes/db.php");

// Show Graphs
?>
<?php
if (isset($_POST['funding_source']) && isset($_POST['product'])) {
	
	//include fusion chart
	include(PUBLIC_PATH . "FusionCharts/Code/PHP/includes/FusionCharts.php");

	$downloadFileName = 'StockExpiryTrend';
	$chart_id = 'ExpiryStockChartId';
	
	$funding_source = implode(',', $_POST['funding_source']);
	$item_id = $_POST['product'];
	if($funding_source){
		$funding_filter = " AND stock_batch.funding_source IN (".$funding_source.")";
		$qry = "SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name
				FROM
					stakeholder
				INNER JOIN tbl_warehouse ON stakeholder.stkid = tbl_warehouse.stkofficeid
				WHERE
					tbl_warehouse.wh_id IN (".$funding_source.")
				ORDER BY
					stakeholder.stkorder ASC";
		$qryRes = mysql_query($qry);
		while ($row = mysql_fetch_array($qryRes)){
			$fundingSourceArr[$row['wh_id']] = $row['wh_name'];
		}
		$fundingSource = implode(', ', $fundingSourceArr);
	}
	if($item_id != ''){
		$product_filter = ' AND stock_batch.item_id = '.$item_id;
		$qry = "SELECT
					itminfo_tab.itm_name
				FROM
					itminfo_tab
				WHERE
					itminfo_tab.itm_id = ".$item_id;
		$qryRes = mysql_fetch_array(mysql_query($qry));
		$productName = $qryRes['itm_name'];
	}
	
	$caption = 'Stock Status After Each Expiry';
	$subCaption = "Products: $productName and Funding Source:$fundingSource";
	
	$qry = "SELECT DISTINCT
				stock_batch.batch_expiry Expiry
			FROM
				stock_batch
			INNER JOIN tbl_warehouse ON stock_batch.wh_id = tbl_warehouse.wh_id
			INNER JOIN tbl_warehouse funding_source ON stock_batch.funding_source = funding_source.wh_id
			WHERE
				stock_batch.batch_expiry > CURDATE()
			AND stock_batch.Qty > 0
			".$funding_filter."
			".$product_filter."
			GROUP BY
				stock_batch.batch_expiry
			ORDER BY
				stock_batch.batch_expiry ASC";
	$qryRes = mysql_query($qry);
	
	$xmlstore = "<chart theme='fint' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$caption' subCaption='$subCaption' exportFileName='$downloadFileName' yAxisName='Quantity' xAxisName='Expiry Date' showValues='1'>";
	$xmlstore .= "<categories>";
	$xmlstore .= "<category label='Stock on current date' />";
	
	while ( $row = mysql_fetch_array($qryRes) )
	{
		// Zero Fills
		foreach($_POST['funding_source'] as $funding_source_id){
			$expiry = date('d/m/Y', strtotime($row['Expiry']));
			$dataArr[$funding_source_id][$expiry]['StartingQty'] = 0;
			$dataArr[$funding_source_id][$expiry]['QtyExpiring'] = 0;
		}
		$expiry = date('d/m/Y', strtotime($row['Expiry']));
		$xmlstore .= "<category label=\"".$expiry."\" />";
	}
	$xmlstore .= "</categories>";
	
	$qry = "SELECT
				A.funding_source,
				A.funding_source_name,
				A.Expiry,
				A.Qty QtyExpiring,
				(
					SELECT
						SUM(stock_batch.Qty)
					FROM
						stock_batch
					INNER JOIN tbl_warehouse ON stock_batch.wh_id = tbl_warehouse.wh_id
					WHERE
						stock_batch.batch_expiry >= A.Expiry
					AND stock_batch.funding_source = A.funding_source
					".$product_filter."
				) StartingQty
			FROM
				(
					SELECT
						stock_batch.batch_expiry Expiry,
						SUM(stock_batch.Qty) Qty,
						stock_batch.funding_source,
						funding_source.wh_name funding_source_name
					FROM
						stock_batch
					INNER JOIN tbl_warehouse ON stock_batch.wh_id = tbl_warehouse.wh_id
					INNER JOIN tbl_warehouse funding_source ON stock_batch.funding_source = funding_source.wh_id
					WHERE
						stock_batch.batch_expiry > CURDATE()
					AND stock_batch.Qty > 0
					".$funding_filter."
					".$product_filter."
					GROUP BY
						stock_batch.batch_expiry,
						stock_batch.funding_source
					ORDER BY
						stock_batch.funding_source,
						stock_batch.batch_expiry ASC
				) A";
	$qryRes = mysql_query($qry);
	$funding_source = '';
	while ($row = mysql_fetch_array($qryRes)) {
		if($row['funding_source'] != $funding_source){
			$strting_qty[$row['funding_source']] = $row['StartingQty'];
			$funding_source = $row['funding_source'];
		}
		
		$expiry = date('d/m/Y', strtotime($row['Expiry']));
		$dataArr[$row['funding_source']][$expiry]['StartingQty'] = $row['StartingQty'];
		$dataArr[$row['funding_source']][$expiry]['QtyExpiring'] = $row['QtyExpiring'];
	}
	
	foreach ($dataArr as $funding_source_id => $subArr) {
		$xmlstore .= "<dataset seriesName=\"".$fundingSourceArr[$funding_source_id]."\">";
		$counter = $quantity = 0;
		foreach ($subArr as $key => $row) {
			if($counter == 0){
				$quantity = $strting_qty[$funding_source_id];
				$xmlstore .= "<set value='".$quantity."' />";
				$counter++;
			}
			$quantity = $quantity - $row['QtyExpiring'];
			$xmlstore .= "<set value='".($quantity)."' />";
		}
		$xmlstore .= "</dataset>";
	}
	
	// Get Avg. Monthly Consumption
	$qryAMC = "SELECT
				A.item_id AS itm_id,
				A.itm_name,
				ROUND(AVG(A.Qty)) AS Qty
			FROM
				(
					SELECT
						stock_batch.item_id,
						itminfo_tab.itm_name,
						ABS(SUM(tbl_stock_detail.Qty)) AS Qty,
						DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m') AS TranDate
					FROM
						tbl_stock_master
					INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
					INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
					INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
					WHERE
						tbl_stock_master.TranTypeID = 2
					AND tbl_stock_master.WHIDFrom = 123
					".$product_filter."
					AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m') <= CURDATE()
					GROUP BY
						itminfo_tab.itm_id,
						DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m')
					ORDER BY
						itminfo_tab.frmindex ASC,
						tbl_stock_master.TranDate DESC
					LIMIT 3
				) A";
	$qryAMCRes = mysql_fetch_array(mysql_query($qryAMC));
	$amc = $qryAMCRes['Qty'];
	
	$xmlstore .= '<trendLines><line startValue="'.$amc.'" color="6baa01" displayvalue="Average Monthly Issuance" valueonright="1" showvalue="1" showontop="1" thickness="2"/></trendLines>';
	
	$xmlstore .= "</chart>";

	// Render Chart
	FC_SetRenderer('javascript');
	?>
	<a href="javascript:exportChart('<?php echo $chart_id; ?>', '<?php echo $downloadFileName; ?>')" style="float:right;"><img class="export_excel" src="<?php echo PUBLIC_URL; ?>images/excel-16.png" alt="Export" /></a>
	<?php
	echo renderChart(PUBLIC_URL . "FusionCharts/Charts/MSLine.swf", "", $xmlstore, $chart_id, '100%', 500, false, false);
	exit;
}
//include header
include(PUBLIC_PATH . "html/header.php");
?>
<!--[if IE]>
<style type="text/css">
    .box { display: block; }
    #box { overflow: hidden;position: relative; }
    b { position: absolute; top: 0px; right: 0px; width:1px; height: 251px; overflow: hidden; text-indent: -9999px; }
</style>

</head>
<!-- END HEAD -->
<style>
.page-content{margin-left:0px !important;}
.control-group label{margin:0 !important; margin-top:2px !important;}
</style>
<body class="page-header-fixed page-quick-sidebar-over-content">
    <!--<div class="pageLoader"></div>-->
    <!-- BEGIN HEADER -->
    <SCRIPT LANGUAGE="Javascript" SRC="<?php echo PUBLIC_URL; ?>FusionCharts/Charts/FusionCharts.js"></SCRIPT>
    <SCRIPT LANGUAGE="Javascript" SRC="<?php echo PUBLIC_URL; ?>FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>

    <div class="page-container">
        <?php
		//include top
        //include PUBLIC_PATH . "html/top.php";
		//include top_im
        include PUBLIC_PATH . "html/top_im.php";
        ?>

        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <form name="frm" id="frm" action="" method="post">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                	<label>Funding Source</label>
                                    <div class="controls" id="product_multi_div" style="border: 1px solid #F2F2F2; padding-left:25px; height:120px; overflow:auto;">
                                        <?php
                                        //select query
                                        //gets
                                        //wh id
                                        //wh name
                                        $qry = "SELECT
													tbl_warehouse.wh_id,
													tbl_warehouse.wh_name
												FROM
													stakeholder
												INNER JOIN tbl_warehouse ON stakeholder.stkid = tbl_warehouse.stkofficeid
												WHERE
													stakeholder.stk_type_id = 2
												AND tbl_warehouse.is_active = 1
												ORDER BY
													stakeholder.stkorder ASC";
                                        //result
                                        $qryRes = mysql_query($qry);
                                       while ( $row = mysql_fetch_array($qryRes) )
                                        {
                                            echo '<label class="checkbox">';
                                            echo "<input type=\"checkbox\" name=\"funding_source[]\" id=\"funding_source\" value=\"".$row['wh_id']."\" /> " . $row['wh_name'];
                                            echo "</label>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="control-group">
                                    <label>Product</label>
                                    <select name="product" id="product" required class="form-control input-sm">
                                        <?php
                                        $qry = "SELECT DISTINCT
                                                    itminfo_tab.itm_id,
                                                    itminfo_tab.itm_name
                                                FROM
                                                    itminfo_tab
                                                INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
                                                WHERE
                                                    itminfo_tab.itm_category = 1
                                                ORDER BY
                                                    itminfo_tab.frmindex ASC";
                                       //query result
                                        $qryRes = mysql_query($qry);
                                        //fetch result
                                        while ( $row = mysql_fetch_array($qryRes) )
                                        {
                                            echo '<option value="'.$row['itm_id'].'">'.$row['itm_name'].'</option>';
                                        }
                                        ?>
									</select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <div class="form-group">
                                    <button type="button" id="expiry_search" value="search" class="btn btn-primary input-sm">Go</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget widget-tabs">
                            <div class="widget-body" id="showGraph">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    //include footer
    include PUBLIC_PATH . "/html/footer.php";
	?>
    <script>
        $(function() {
			$('#expiry_search').click(function(e) {
				if($('#product').val() != '' && $('[name="funding_source[]"]:checked').length != 0)
				{
					$.ajax({
						url: 'expiry_stock.php',
						type: 'POST',
						data: $('#frm').serialize(),
						success: function(data) {
							$('#showGraph').html(data);
						}
					})
				}
				else
				{
					if($('[name="funding_source[]"]:checked').length == 0){
						alert('Select at least one funding source');
						$('#stk_id').focus();
						return false;
					}
					if($('#product').val() == ''){
						alert('Select product');
						$('#product').focus();
						return false;
					}
				}
            });
        })
    </script>
</body>
<!-- END BODY -->
</html>