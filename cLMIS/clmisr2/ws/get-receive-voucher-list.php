<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

if(isset($_GET['wh_id']))
{
	
$wh_id=$_GET['wh_id'];

$query="SELECT DISTINCT
 A.transaction_number,
 A.stc_master_pkid,
 A.transaction_date,
 A.warehouse_name,
 A.to_warehouse_id,
 A.from_warehouse_id
FROM
 (
  SELECT
   tbl_stock_detail.PkDetailID,
   tbl_stock_master.TranNo AS transaction_number,
   tbl_stock_master.PkStockID AS stc_master_pkid,
   tbl_stock_master.TranDate AS transaction_date,
   tbl_warehouse.wh_name AS warehouse_name,
   tbl_stock_master.WHIDTo AS to_warehouse_id,
   tbl_stock_master.WHIDFrom AS from_warehouse_id,
   SUM(ABS(tbl_stock_detail.Qty)) AS totalQty
  FROM
   tbl_stock_master
  INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
  INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
  WHERE
   tbl_stock_master.TranTypeID = 1
  AND tbl_stock_master.WHIDTo = $wh_id
  AND tbl_stock_master.TranDate >= '2015-01-01'
  GROUP BY
   tbl_stock_detail.PkDetailID
 ) A
LEFT JOIN (
 SELECT
  placements.stock_detail_id,
  SUM(ABS(placements.quantity)) AS pickedQty
 FROM
  placements
 WHERE
  placements.placement_transaction_type_id IN (90, 89)
 GROUP BY
  placements.stock_detail_id
) B ON A.PkDetailID = B.stock_detail_id
WHERE
 A.totalQty != IFNULL(B.pickedQty, 0)
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
	print json_encode('Please provide wh_id like ?wh_id=1');
}
// example: http://localhost/lmis/ws/locations.php?ID=4
?>