<?php
include("../html/adminhtml.inc.php");
Login();

include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");

$whId = $_SESSION['userdata'][5];
$stkId = $_SESSION['stkid'];

if ( $_REQUEST['product'] )
{

	$itemId = $_REQUEST['product'];
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
	$xmlstore = "<chart theme='fint' numberSuffix='%' exportEnabled='1' exportAction='Download' caption='Expiry Schedule $row[itm_name]' exportFileName='Expiry Schedule $row[itm_name]" . date('Y-m-d H:i:s') . "'>";
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
	echo '<p class="center" style="color:#060">Note:- Click on the graph to see batch wise expiry details</p>';
}

if ( isset($_REQUEST['itemId']) && isset($_REQUEST['type']) )
{
	$type = $_REQUEST['type'];
	$itemId = $_REQUEST['itemId'];
	$getItmName = mysql_fetch_array(mysql_query("SELECT getItemNameByID(".$_REQUEST['itemId'].") AS item_name FROM DUAL "));
	$itmName = $getItmName['item_name'];
	
	$where = '';
	if ($type == 1)
	{
		$title = "Stock Expiring in <= 6 Months";
		$where = ' AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 6 MONTH)';
	}
	else if ($type == 2)
	{
		$title = "Stock Expiring in <= 12 Months";
		$where = ' AND stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 6 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 12 MONTH)';
	}
	else if ($type == 3)
	{
		$title = "Stock Expiring in <= 18 Months";
		$where = ' AND stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 12 MONTH) AND stock_batch.batch_expiry <= ADDDATE(CURDATE(), INTERVAL 18 MONTH)';
	}
	else if ($type == 4)
	{
		$title = "Stock Expiring in > 18 Months";
		$where = ' AND stock_batch.batch_expiry > ADDDATE(CURDATE(), INTERVAL 18 MONTH)';
	}
	
	$qry = "SELECT
				itminfo_tab.itm_name,
				itminfo_tab.qty_carton,
				stock_batch.batch_no,
				DATE_FORMAT(stock_batch.batch_expiry, '%m/%d/%Y') AS batch_expiry,
				stock_batch.`status`,
				SUM(stock_batch.Qty) AS Qty,
				SUM(stock_batch.Qty) / itminfo_tab.qty_carton AS carton
			FROM
				tbl_stock_master
			INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
			INNER JOIN stock_batch ON stock_batch.batch_id = tbl_stock_detail.BatchID
			INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
			WHERE
				stock_batch.item_id IS NOT NULL
			AND stock_batch.Qty > 0
			AND stock_batch.item_id = $itemId
			AND tbl_stock_master.WHIDTo = $whId
			$where
			GROUP BY
				stock_batch.batch_no
			ORDER BY
				stock_batch.batch_expiry ASC";
	$qryRes = mysql_query($qry);
	
	?>
	<script>
	$(function()
	{	
		if ($('.dynamicTable2').size() > 0)
		{
			var datatable = $('.dynamicTable2').dataTable({
				"sPaginationType": "bootstrap",
				//"sDom": 'W<"clear">lfrtip',
			   // "sDom": 'T<"clear">lfrtip',
			  "sDom": "<'row'<'col-md-11'>T<'clear'>><'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-5'i><'col-md-7'p>>",
				// "sDom": '<"clear">lfrtipT',
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"oTableTools": {
					"aButtons": [
						{
							"sExtends": "xls",
							"sButtonText": "<img src=../images/excel-16.png>",
							"sTitle": "Expiry Schedule"
						},
						{
							"sExtends": "pdf",
							"sButtonText": "<img src=../images/pdf-16.png>",
							"sTitle": "Expiry Schedule",
							"sPdfOrientation": "landscape"
						}
						
					],
					
					"sSwfPath": basePath  + "/common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
				}
	
			});
	
		}
	});
    </script>
    <div class="widget widget-tabs">
        <div class="widget">
            <div class="widget-head">
                <h3 class="heading"><?php echo $itmName . ' - ' . $title;?></h3>
            </div>
            <div class="widget-body" id="expiryData">
            	<table width="100%" class="dynamicTable2 table table-striped table-bordered table-condensed dataTable">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Batch No</th>
                            <th>Expiry</th>
                            <th>Quantity</th>
                            <th>Cartons</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                $i = 1;
                while ( $row = mysql_fetch_array($qryRes) )
                {
                ?>
                    <tr>
                        <td align="center"><?php echo $i++;?></td>
                        <td><?php echo $row['batch_no'];?></td>
                        <td><?php echo $row['batch_expiry'];?></td>
                        <td class="right"><?php echo number_format($row['Qty']);?></td>
                        <td class="right"><?php echo number_format($row['carton']);?></td>
                        <td><?php echo $row['status'];?></td>
                    </tr>
                <?php
                }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
	<?php
}