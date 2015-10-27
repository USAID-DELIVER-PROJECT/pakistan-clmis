<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

if(isset($_GET['voucher_id']))
{
	
$voucher=$_GET['voucher_id'];

$query="SELECT DISTINCT
			tbl_stock_master.TranNo AS transcation_number,
			(tbl_stock_detail.Qty) AS quantity,
			tbl_stock_detail.PkDetailID AS pk_id,
			tbl_stock_detail.fkStockID AS stc_master_pkid,
			stock_batch.batch_no AS number,
			stock_batch.batch_id,
			stock_batch.batch_expiry AS expiry_date,
			itminfo_tab.itm_name AS item_name,
			stock_batch.item_id AS itemID,
			itminfo_tab.qty_carton AS quantity_per_pack,
			GetPlaced (
				tbl_stock_detail.PkDetailID
			) AS place_quantity
		FROM
			tbl_stock_master
		INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
		INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		LEFT JOIN placements ON tbl_stock_detail.PkDetailID = placements.stock_detail_id
		WHERE
			tbl_stock_master.TranTypeID = 1
		AND tbl_stock_master.PkStockID = $voucher
		GROUP BY
			tbl_stock_detail.BatchID,
			tbl_stock_detail.fkStockID";

$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);
}
else{
	print json_encode('Please provide voucher_id like ?voucher_id=1');
}
// example: http://localhost/lmis/ws/locations.php?ID=4
?>