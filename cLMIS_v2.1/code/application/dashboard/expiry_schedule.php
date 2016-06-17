<?php
/**
 * expiry_schedule
 * @package dashboard
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include Configuration
include("../includes/classes/Configuration.inc.php");
Login();
//include db
include(APP_PATH."includes/classes/db.php");
//include functions
include APP_PATH."includes/classes/functions.php";
//include header
include(PUBLIC_PATH."html/header.php");
//include FusionCharts
include(PUBLIC_PATH."FusionCharts/Code/PHP/includes/FusionCharts.php");

//whId
$whId = $_SESSION['user_warehouse'];
//stk id
$stkId = $_SESSION['user_stakeholder'];
//item id
$itemId = 1;
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
<SCRIPT LANGUAGE="Javascript" SRC="<?php echo PUBLIC_URL;?>FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="<?php echo PUBLIC_URL;?>FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>

<div class="page-container">
	<?php 
        //including top
        include PUBLIC_PATH."html/top.php";
        //including top_im
        include PUBLIC_PATH."html/top_im.php";?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabsbar">
                        <ul>
                            <li><a href="shipment.php"> <b>Distribution and SOH</b></a></li>
                            <li class="active"><a href="#"> <b>Expiry Status</b></a></li>
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
                            <label for="product">Product</label>
                            <div class="form-group">
                                <select name="product" id="product" class="form-control input-sm">
                                <?php
                                //query
                                //gets
                                //item id
                                //item name
                                $qry = "SELECT
											itminfo_tab.itm_id,
											itminfo_tab.itm_name
										FROM
											itminfo_tab
										INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
										WHERE
											itminfo_tab.itm_category = 1
										AND stakeholder_item.stkid = 1
										ORDER BY
											itminfo_tab.frmindex ASC";
								$qryRes = mysql_query($qry);
								while ($row = mysql_fetch_array($qryRes))
								{
								?>
                                	<option value="<?php echo $row['itm_id'];?>"><?php echo $row['itm_name'];?></option>
                                <?php
								}
								?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Funding Source</label>
                            <div class="form-group">
                                <select name="funding_source" id="funding_source" class="form-control input-sm">
                                	<option value="all">All</option>
                                    <?php
                                    //query
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
                                                                        //query result
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
                                <button type="button" id="expiry_search" name="expiry_search" value="search" class="btn btn-primary input-sm">Go</button>
                            </div>
                        </div>
                	</div>
				</form>
            </div>
            <div class="row">
            	<div class="col-md-5">
                	<div class="widget widget-tabs">
                    	<div class="widget-body" id="expirySchedule">
                        	<?php
                                //query
                                //gets
                                //Expire6Months
                                //Expire12Months
                                //Expire18Months
                                //Expire18Greater
							$qry = "SELECT
										A.item_id,
										A.itm_name,
										ROUND(((A.Expire6Months / A.totalQty) * 100), 1) AS Expire6Months,
										ROUND(((A.Expire12Months / A.totalQty) * 100), 1) AS Expire12Months,
										ROUND(((A.Expire18Months / A.totalQty) * 100), 1) AS Expire18Months,
										ROUND(((A.Expire18Greater / A.totalQty) * 100), 1) AS Expire18Greater
									FROM (SELECT
										stock_batch.item_id,
										itminfo_tab.itm_name,
										SUM(tbl_stock_detail.Qty) AS totalQty,
										SUM(IF (stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 6 MONTH), tbl_stock_detail.Qty, 0)) AS Expire6Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 6 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 12 MONTH), tbl_stock_detail.Qty, 0)) AS Expire12Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 12 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 18 MONTH), tbl_stock_detail.Qty, 0)) AS Expire18Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 18 MONTH), tbl_stock_detail.Qty, 0)) AS Expire18Greater
									FROM
										stock_batch
									INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
									INNER JOIN tbl_stock_master ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
									INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
									WHERE
									stock_batch.item_id IS NOT NULL
									AND stock_batch.Qty > 0
									AND stock_batch.item_id = $itemId
									AND stock_batch.wh_id = $whId
									) A";
							$row = mysql_fetch_array(mysql_query($qry));
							//xml
                                                        $xmlstore = "<chart theme='fint' numberSuffix='%' exportEnabled='1' exportAction='Download' caption='Expiry Status $row[itm_name]' exportFileName='Expiry Status $row[itm_name]" . date('Y-m-d H:i:s') . "'>";
							if ($row['Expire6Months'] > 0)
							{
								$xmlstore .= "<set label='Expiry &lt;= 6 Months' value='$row[Expire6Months]' link=\"JavaScript:showData('$row[item_id], 1');\" issliced='1' />";
							}
							if ($row['Expire12Months'] > 0)
							{
								$xmlstore .= "<set label='Expiry &lt;= 12 Months' value='$row[Expire12Months]' link=\"JavaScript:showData('$row[item_id], 2');\" />";
							}
							if ($row['Expire18Months'] > 0)
							{
								$xmlstore .= "<set label='Expiry &lt;= 18 Months' value='$row[Expire18Months]' link=\"JavaScript:showData('$row[item_id], 3');\" />";
							}
							if ($row['Expire18Greater'] > 0)
							{
								$xmlstore .= "<set label='Expiry &gt; 18 Months' value='$row[Expire18Greater]' link=\"JavaScript:showData('$row[item_id], 4');\" />";
							}
							$xmlstore .= "</chart>";
							FC_SetRenderer('javascript');
							echo renderChart(PUBLIC_URL."FusionCharts/Charts/Pie3D.swf", "", $xmlstore, 'Expiry' . $row['item_id'], '100%', 350, false, false);
							?>
                            <p class="center" style="color:#060">Note:- Click on the graph to see batch wise expiry details</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-7" style="display:none;" id="expiryData"></div>
            </div>
        </div>
    </div>
</div>

<?php 
//including footer
include PUBLIC_PATH."/html/footer.php";?>

<SCRIPT LANGUAGE="JavaScript">
	function showData(myVar){
		var paramArr = myVar.split(',');
		var fund_source = $('#funding_source').val();
		$('#expiryData').html("<center><div id='loadingmessage'><img src='../plmis_img/ajax-loader.gif'/></div></center>");
		$.ajax({
			type: "POST",
			url: 'expiry_ajax.php',
			data: {itemId: paramArr[0], type: paramArr[1], fund_source: fund_source},
			dataType: 'html',
			success: function(data) {
				$('#expiryData').show().html(data);
			}
		});
	}
	$(function(){
		// Show Expity Grid
		//showData('1,1');	
	})
	
	$('#expiry_search').click(function(e) {
		var product = $('#product').val();
		var fund_source = $('#funding_source').val();
		var param = product+','+1;
		$('#expiryData').hide().html('');
		$('#expirySchedule').html("<center><div id='loadingmessage'><img src='../plmis_img/ajax-loader.gif'/></div></center>");
		$.ajax({
			type: "POST",
			url: 'expiry_ajax.php',
			data: {product: product, fund_source: fund_source},
			success: function(data) {
				$("#expirySchedule").html(data);
				// Show Expity Grid
				//showData(param);
			}
		});
	});
</SCRIPT>
</body>
<!-- END BODY -->
</html>