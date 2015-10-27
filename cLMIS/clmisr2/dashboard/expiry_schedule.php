<?php
include("../html/adminhtml.inc.php");
Login();

include "../plmis_inc/common/_header.php";
include "../plmis_inc/common/top_im.php";
include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");
include "../plmis_admin/Includes/functions.php";


$whId = $_SESSION['userdata'][5];
$stkId = $_SESSION['stkid'];
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
                    	<div class="col-md-3">
                            <label for="product">Product</label>
                            <div class="form-group">
                                <select name="product" id="product" class="form-control input-sm">
                                <?php
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
            	<div class="col-md-6">
                	<div class="widget widget-tabs">
                    	<div class="widget-body" id="expirySchedule">
                        	<?php
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
										SUM(stock_batch.Qty) AS totalQty,
										SUM(IF (stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 6 MONTH), stock_batch.Qty, 0)) AS Expire6Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 6 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 12 MONTH), stock_batch.Qty, 0)) AS Expire12Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 12 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 18 MONTH), stock_batch.Qty, 0)) AS Expire18Months,
										SUM(IF (stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 18 MONTH), stock_batch.Qty, 0)) AS Expire18Greater
									FROM
										stock_batch
									INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
									INNER JOIN tbl_stock_master ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
									INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
									WHERE
									stock_batch.item_id IS NOT NULL
									AND stock_batch.Qty > 0
									AND stock_batch.item_id = $itemId
									AND tbl_stock_master.WHIDTo = $whId
									) A";
							$row = mysql_fetch_array(mysql_query($qry));
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
							//exit($xmlstore);
							FC_SetRenderer('javascript');
							echo renderChart("FusionCharts/Charts/Pie3D.swf", "", $xmlstore, 'Expiry' . $row['item_id'], '100%', 350, false, false);
							?>
                            <p class="center" style="color:#060">Note:- Click on the graph to see batch wise expiry details</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="display:none;" id="expiryData"></div>
            </div>
        </div>
    </div>
</div>

<?php include "../plmis_inc/common/footer.php";?>
<SCRIPT LANGUAGE="JavaScript">
	function showData(myVar){
		var paramArr = myVar.split(',');
		$('#expiryData').html("<center><div id='loadingmessage'><img src='../plmis_img/ajax-loader.gif'/></div></center>");
		$.ajax({
			type: "POST",
			url: 'expiry_ajax.php',
			data: {itemId: paramArr[0], type: paramArr[1]},
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
		var param = product+','+1;
		$('#expiryData').hide().html('');
		$('#expirySchedule').html("<center><div id='loadingmessage'><img src='plmis_img/ajax-loader.gif'/></div></center>");
		$.ajax({
			type: "POST",
			url: 'expiry_ajax.php',
			data: {product: product},
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