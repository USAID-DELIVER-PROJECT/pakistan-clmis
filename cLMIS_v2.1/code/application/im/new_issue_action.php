<?php
/**
 * new_issue_action
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//AllClasses
include("../includes/classes/AllClasses.php");

$strDo = "Add";
$nstkId = 0;
$autorun = false;

// Make data parmanent when user click on save button
if (isset($_REQUEST['stockid']) && !empty($_REQUEST['stockid'])) {
    //stock id
    $stockid = $_REQUEST['stockid'];
    //update stock master temp
    $objStockMaster->updateTemp($stockid);
    //update stock detail temp
    $objStockDetail->updateTemp($stockid);

    //Save Data in WH data table
    $objWhData->addReport($stockid, 2);
    //get Wh Level By Stock ID
    $result = $objStockMaster->getWhLevelByStockID($stockid);
    if ($result['level'] ==4) {
        $objWhData->addReport($stockid, 10, '', $result['uc_wh_id']);
    }
	$_SESSION['success'] = 1;
    header("location:new_issue.php");
    exit;
}
// End save button
//check transaction number 
if (isset($_REQUEST['trans_no']) && !empty($_REQUEST['trans_no'])) {
    //get transaction number
    $trans_no = $_REQUEST['trans_no'];
}
//check stock id
if (isset($_REQUEST['stock_id']) && !empty($_REQUEST['stock_id'])) {
    //get stock id
    $stock_id = $_REQUEST['stock_id'];
}
//check issue date
if (isset($_REQUEST['issue_date']) && !empty($_REQUEST['issue_date'])) {
    //get issue date
    $issue_date = $_REQUEST['issue_date'];
}
//check issue ref
if (isset($_REQUEST['issue_ref']) && !empty($_REQUEST['issue_ref'])) {
    //get issue ref
    $issue_ref = $_REQUEST['issue_ref'];
}
//check warehouse
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
    //get warehouse
    $issue_to = $_REQUEST['warehouse'];
} else {
    header("Location:new_issue.php?warehouse=1");
    exit;
}
//check product
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
    //get product
    $product = $_REQUEST['product'];
}
//check batch
if (isset($_REQUEST['batch']) && !empty($_REQUEST['batch'])) {
    //get batch
    $batch = $_REQUEST['batch'];
}
//check expiry date
if (isset($_REQUEST['expiry_date']) && !empty($_REQUEST['expiry_date'])) {
    //get expiry date
    $expiry_date = $_REQUEST['expiry_date'];
}
//check qty
if (isset($_REQUEST['qty']) && !empty($_REQUEST['qty'])) {
    
    //get qty
    $qty = str_replace(",", "", $_REQUEST['qty']);
}
//check available_qty
if (isset($_REQUEST['available_qty']) && !empty($_REQUEST['available_qty'])) {
    //get available_qty
    $ava_qty = str_replace(",", "", $_REQUEST['available_qty']);
}
if ((int) $qty > (int) $ava_qty || (int) $qty == (int) $ava_qty) {
    $qty = $ava_qty;
    $autorun = true;
}
//check unit
if (isset($_REQUEST['unit']) && !empty($_REQUEST['unit'])) {
    //get unit
    $unit = $_REQUEST['unit'];
}
//get comments
if (isset($_REQUEST['comments']) && !empty($_REQUEST['comments'])) {
    //check comments
    $comments = $_REQUEST['comments'];
}
//issue by
if (isset($_REQUEST['issued_by']) && !empty($_REQUEST['issued_by'])) {
    //issued by
    $issued_by = $_REQUEST['issued_by'];
}

$objStockBatch->funding_source = $receive_from;

if (empty($trans_no)) {
    $objStockMaster->TranDate = dateToDbFormat($issue_date);
    $objStockMaster->TranTypeID = 2;
    $objStockMaster->issued_by = $issued_by;
    $objStockMaster->TranRef = $issue_ref;
    $objStockMaster->WHIDFrom = $_SESSION['user_warehouse'];
    $objStockMaster->WHIDTo = $issue_to;
    $objStockMaster->CreatedBy = $_SESSION['user_id'];
    $objStockMaster->CreatedOn = date("Y-m-d");
    $objStockMaster->ReceivedRemarks = $comments;

    $current_year = date("Y");
    $current_month = date("m");
    if ($current_month < 7) {
        $from_date = ($current_year - 1) . "-06-30";
        $to_date = $current_year . "-07-30";
    } else {
        $from_date = $current_year . "-06-30";
        $to_date = ($current_year + 1) . "-07-30";
    }

    $last_id = $objStockMaster->getLastID($from_date, $to_date, 2);

    if ($last_id == NULL) {
        $last_id = 0;
    }
    $trans_no = "I" . date('ym').str_pad(($last_id + 1), 4, "0", STR_PAD_LEFT);
    $objStockMaster->TranNo = $trans_no;
    $objStockMaster->temp = 1;
    $objStockMaster->trNo = ($last_id + 1);
    $objStockMaster->LinkedTr = 0;
    $fkStockID = $objStockMaster->save();

} else {
    $fkStockID = $stock_id;
    $objStockMaster->TranDate = dateToDbFormat($issue_date);
    $objStockMaster->TranRef = $issue_ref;
    $objStockMaster->issued_by = $issued_by;
    $objStockMaster->ReceivedRemarks = $comments;
    $objStockMaster->updateMasterIssueDate($stock_id);
}

if ($strDo == "Add") {
    $objStockDetail->fkStockID = $fkStockID;
    $objStockDetail->BatchID = $batch;
    $objStockDetail->fkUnitID = $unit;
    $objStockDetail->Qty = "-" . $qty;
    $objStockDetail->temp = 1;
    $objStockDetail->IsReceived = 0;
    $objStockDetail->adjustmentType = 2;
    $result = $objStockDetail->save();

    // Adjust Batch Quantity
    $objStockBatch->adjustQtyByWh($batch, $_SESSION['user_warehouse']);

    if ($autorun == true) {
        $objStockBatch->autoRunningLEFOBatch($product, $_SESSION['user_warehouse']);
        $objStockBatch->changeStatus($batch, 'Finished');
    }
}

$_SESSION['stock_id'] = $fkStockID;
header("location:new_issue.php");
exit;
?>