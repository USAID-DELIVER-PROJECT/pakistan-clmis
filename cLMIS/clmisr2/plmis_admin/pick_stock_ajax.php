<?php
include("../plmis_inc/common/CnnDb.php");
include("../plmis_admin/Includes/functions.php");
session_start();

// Ajax Call to get voucher list
if ( isset($_REQUEST['dateFrom']) && isset($_REQUEST['dateTo']) )
{
	$whId = $_SESSION['wh_id'];
	$db_date_from = dateToDbFormat($_REQUEST['dateFrom']);
	$db_date_to = dateToDbFormat($_REQUEST['dateTo']);
	
	$strSqlList="SELECT
					A.PkStockID,
					A.TranNo
				FROM
					(
						SELECT
							tbl_stock_master.PkStockID,
							tbl_stock_master.TranNo,
							SUM(ABS(tbl_stock_detail.Qty)) AS Qty,
							tbl_stock_master.TranDate
						FROM
							tbl_stock_master
						INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
						WHERE
							tbl_stock_master.TranTypeID = 2
						AND tbl_stock_master.WHIDFrom = $whId
						AND tbl_stock_master.TranDate BETWEEN '$db_date_from' AND '$db_date_to'
						GROUP BY
							tbl_stock_master.PkStockID,
							tbl_stock_master.TranNo
					) A
				LEFT JOIN (
					SELECT
						tbl_stock_master.PkStockID,
						tbl_stock_master.TranNo,
						SUM(ABS(placements.quantity)) AS pickedQty
					FROM
						tbl_stock_master
					INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
					INNER JOIN placements ON tbl_stock_detail.PkDetailID = placements.stock_detail_id
					WHERE
						tbl_stock_master.TranTypeID = 2
					AND tbl_stock_master.WHIDFrom = $whId
					AND tbl_stock_master.TranDate BETWEEN '$db_date_from' AND '$db_date_to'
					AND placements.placement_transaction_type_id IN (90, 91)
					GROUP BY
						tbl_stock_master.TranNo
				) B ON A.PkStockID = B.PkStockID
				WHERE
					(A.Qty - IFNULL(B.pickedQty, 0)) > 0
				ORDER BY
					A.TranDate DESC";

	$issueList=mysql_query($strSqlList) or die("ERR Issue Voucher");
	if (mysql_num_rows(mysql_query($strSqlList)))
	{
		while($rowVouchers=mysql_fetch_assoc($issueList))
		{?>
		<option value="<?php echo $rowVouchers['PkStockID']?>" <?php echo ($_REQUEST['tran_no'] == $rowVouchers['PkStockID']) ? 'selected="selected"' : '';?>> <?php echo $rowVouchers['TranNo']?> </option>
		<?php
		}
	}
	else
	{
		echo '<option value="">No voucher found</option>';
	}
}