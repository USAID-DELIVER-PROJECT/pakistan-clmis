<?php

include_once("DBCon.php");          // Include Database Connection File
// Example Call for 1st time: http://localhost/clmis/ws/sync_transactions.php?td=2014-02-19&tn=0006&tt=1&tr=000056&wf=1&wt=2&cb=99&co=2014-02-19&rr=remakrs&bn=b00001&be=2015-01-01&itm=12&qty=1000
// Example Call for when we have master Id: http://localhost/clmis/ws/sync_transactions.php?bn=b00001&be=2015-01-01&itm=12&qty=1000&mId=12

$transactionDate = !empty($_REQUEST['td']) ? $_REQUEST['td'] : '';
$transactionNum = !empty($_REQUEST['tn']) ? $_REQUEST['tn'] : '';
$transactionTypeId = !empty($_REQUEST['tt']) ? $_REQUEST['tt'] : '';
$transactionRef = !empty($_REQUEST['tr']) ? $_REQUEST['tr'] : '';
$warehouseFrom = !empty($_REQUEST['wf']) ? $_REQUEST['wf'] : '';
$warehouseTo = !empty($_REQUEST['wt']) ? $_REQUEST['wt'] : '';
$createdBy = !empty($_REQUEST['cb']) ? $_REQUEST['cb'] : '';
$createdOn = !empty($_REQUEST['co']) ? $_REQUEST['co'] : '';
$receivedRemarks = !empty($_REQUEST['rr']) ? $_REQUEST['rr'] : '';
$batchNum = !empty($_REQUEST['bn']) ? $_REQUEST['bn'] : '';
$batchid = !empty($_REQUEST['bid']) ? $_REQUEST['bid'] : '';
$batchExpiry = !empty($_REQUEST['be']) ? $_REQUEST['be'] : '';
$itemId = !empty($_REQUEST['itm']) ? $_REQUEST['itm'] : '';
$quantity = !empty($_REQUEST['qty']) ? $_REQUEST['qty'] : '';
$masterId = !empty($_REQUEST['mId']) ? $_REQUEST['mId'] : '';


// Insert into stock Batch table (stock_batch)
// check if batch already exist
//// To warehouse Stock Batch Adjustments.
//$qry = "SELECT
//			COUNT(batch_id) AS num,
//			batch_id,
//                        Qty
//			FROM
//			stock_batch
//			WHERE
//			stock_batch.batch_no = '" . $batchNum . "' AND wh_id=" . $warehouseTo . "";
//
//
//
//$batchExist = mysql_fetch_array(mysql_query($qry));
//
//$batchPKId = 0;
//
//// If not found then add
//if ($batchExist['num'] == 0) {
//    // Insert Batch
//    $qry = "INSERT INTO stock_batch
//				SET
//                                        Qty = '" . abs($quantity) . "', 
//					batch_no = '" . $batchNum . "',
//					batch_expiry = '" . $batchExpiry . "',
//					wh_id = '" . $warehouseTo . "',
//					item_id = '" . $itemId . "' ";
//    mysql_query($qry);
//    $batchPKId = mysql_insert_id();
//} else { // If found then update
//    $updatedQty = ($batchExist['Qty'] + abs($quantity));
//
//    // Update Batch qty.
//    $qry = "Update  stock_batch
//				SET                                      
//                              Qty = '" . $updatedQty . "' WHERE batch_id='" . $batchExist['batch_id'] . "' AND wh_id='" . $warehouseTo . "'";
//
//
//    mysql_query($qry);
//}
//echo "from wh";
// From Warehouse Stock updation
$qry = "SELECT
                         batch_id,
			Qty
			FROM
			stock_batch
			WHERE
			stock_batch.batch_id = '" . $batchid . "' AND wh_id='" . $warehouseFrom . "'";

// echo $qry;
// exit;
$FrombatchExist = mysql_fetch_array(mysql_query($qry));

$updatedQty = ($FrombatchExist['Qty'] + $quantity);

// Update Batch qty.
$qry = "Update  stock_batch
				SET                                      
                              Qty = '" . $updatedQty . "' WHERE batch_id='" . $batchid . "' AND wh_id='" . $warehouseFrom . "'";

mysql_query($qry);


//oo "from updated";
// -
// Insert into stock master table (tbl_stock_master)
if (empty($masterId)) {
    $qry = "INSERT INTO tbl_stock_master
			SET
				TranDate = '" . $transactionDate . "',
				TranNo = '" . $transactionNum . "',
				TranTypeID = '" . $transactionTypeId . "',
				TranRef = '" . $transactionRef . "',
				WHIDFrom = '" . $warehouseFrom . "',
				WHIDTo = '" . $warehouseTo . "',
				CreatedBy = '" . $createdBy . "',
				CreatedOn = '" . $createdOn . "',
				temp='0',
				ReceivedRemarks = '" . $receivedRemarks . "' ";
    mysql_query($qry);
    $masterPKId = mysql_insert_id();
} else {
    $masterPKId = $masterId;
}

// Insert into stock detail table (tbl_stock_detail)
if (!empty($quantity)) {
    $qry = "INSERT INTO tbl_stock_detail
			SET
				fkStockID = '" . $masterPKId . "',
				BatchID = '" . $batchid . "',
				temp='0',
				Qty = '" . $quantity . "' ";
    mysql_query($qry);
    $detailPKId = mysql_insert_id();
}
$arr = array('master_id' => $masterPKId, 'detail_id' => $detailPKId);

$arr1[] = $arr;
print(json_encode($arr1));
?>