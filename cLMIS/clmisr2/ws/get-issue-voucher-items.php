<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

if(isset($_GET['voucher_id']))
{
	
$voucher=$_GET['voucher_id'];

$query="SELECT
			A.transaction_number,
			A.quantity,
			A.pk_id,
			A.stc_master_pkid,
			A.number,
			A.batch_id,
			A.expiry_date,
			A.item_name,
			A.itemID,
			A.quantity_per_pack,
			B.place_quantity
		FROM
			(
				SELECT
					tbl_stock_detail.PkDetailID AS pk_id,
					tbl_stock_master.TranNo AS transaction_number,
					tbl_stock_master.PkStockID AS stc_master_pkid,
					tbl_stock_master.TranDate AS transaction_date,
					Sum(ABS(tbl_stock_detail.Qty)) AS quantity,
					stock_batch.batch_id,
					stock_batch.batch_no AS number,
					stock_batch.batch_expiry AS expiry_date,
					stock_batch.item_id AS itemID,
					itminfo_tab.itm_name AS item_name,
					itminfo_tab.qty_carton AS quantity_per_pack
				FROM
					tbl_stock_master
				INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
				INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
				INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				WHERE
					tbl_stock_master.TranTypeID = 2
				AND tbl_stock_master.WHIDFrom = 123
				AND tbl_stock_master.PkStockID = 12
				GROUP BY
					tbl_stock_detail.PkDetailID
			) A
		LEFT JOIN (
			SELECT
				placements.stock_detail_id,
				SUM(ABS(placements.quantity)) AS place_quantity
			FROM
				placements
			WHERE
				placements.placement_transaction_type_id IN (90, 91)
			GROUP BY
				placements.stock_detail_id
		) B ON A.pk_id = B.stock_detail_id
		WHERE
			A.quantity != IFNULL(B.place_quantity, 0)
		ORDER BY
			A.transaction_date DESC";

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