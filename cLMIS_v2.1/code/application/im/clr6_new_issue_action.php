<?php

/**
 * clr6_new_issue_action
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("../includes/classes/AllClasses.php");

$strDo = "Add";
$nstkId = 0;
$autorun = false;
$vvmstage = '';

//check issue date
if (isset($_REQUEST['issue_date']) && !empty($_REQUEST['issue_date'])) {
//get issue date	
    $issue_date = $_REQUEST['issue_date'];
}
//check clr6 id
if (isset($_REQUEST['clr6_id']) && !empty($_REQUEST['clr6_id'])) {
//get clr6 id	
    $clr6_id = $_REQUEST['clr6_id'];
}
//check issue reference
if (isset($_REQUEST['issue_ref']) && !empty($_REQUEST['issue_ref'])) {
//get issue reference	
    $issue_ref = $_REQUEST['issue_ref'];
}
//check issued by
if (isset($_REQUEST['issued_by']) && !empty($_REQUEST['issued_by'])) {
//get issued by	
    $issued_by = $_REQUEST['issued_by'];
}
//check warehouse
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
//get warehouse	
    $issue_to = $_REQUEST['warehouse'];
} else {
    header("location:" . APP_URL . "im/issue.php?warehouse=1");

    exit;
}
//check product
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
//get product	
    $product = $_REQUEST['product'];
}
//check item rec
if (isset($_REQUEST['itmrec']) && !empty($_REQUEST['itmrec'])) {
//get item rec	
    $itemrec = $_REQUEST['itmrec'];
}
//check batch
if (isset($_REQUEST['batch']) && !empty($_REQUEST['batch'])) {
    $batch = $_REQUEST['batch'];
}
//check qty issued
if (isset($_REQUEST['qty_issued']) && !empty($_REQUEST['qty_issued'])) {
//get qty	
    $qty = $_REQUEST['qty_issued'];
}
//qty
$qty = array_filter($qty);
//batch
$batch = array_filter($batch);
foreach ($qty as $key => $value) {
    $batch = explode("|", $key);
    $batchId = $batch[1];
}
//set Transaction date
$objStockMaster->TranDate = dateToDbFormat($issue_date);
//set Transaction type id
$objStockMaster->TranTypeID = 2;
//set Transaction ref
$objStockMaster->TranRef = $issue_ref;
//set issued by
$objStockMaster->issued_by = $issued_by;
//set from warehouse
$objStockMaster->WHIDFrom = $_SESSION['user_warehouse'];
//set to warehouse
$objStockMaster->WHIDTo = $issue_to;
//set created by
$objStockMaster->CreatedBy = $_SESSION['user_id'];
//set created on
$objStockMaster->CreatedOn = date("Y-m-d");
//current year
$current_year = date("Y");
//current month
$current_month = date("m");
if ($current_month < 7) {
    $from_date = ($current_year - 1) . "-06-30";
    $to_date = $current_year . "-07-30";
} else {
    $from_date = $current_year . "-06-30";
    $to_date = ($current_year + 1) . "-07-30";
}
//last id
$last_id = $objStockMaster->getLastID($from_date, $to_date, 2);

if ($last_id == NULL) {
    $last_id = 0;
}
//transaction number
$trans_no = "I" . date('ym') . str_pad(($last_id + 1), 4, "0", STR_PAD_LEFT);
//set transaction number
$objStockMaster->TranNo = $trans_no;
$objStockMaster->trNo = ($last_id + 1);
$objStockMaster->LinkedTr = 0;
//save stock master
$fkStockID = $objStockMaster->save();
//add
if ($strDo == "Add") {
    foreach ($qty as $key => $value) {
        //value
        $value = str_replace(',', '', $value);
        //batch
        $batch = explode("|", $key);
        //batch id
        $batchId = $batch[1];
        //item rec
        $itemrec[$batch[0]];
        //value array
        $valueArr[$batch[0]] = $value;
        //set fk stock id
        $objStockDetail->fkStockID = $fkStockID;
        //set batch id
        $objStockDetail->BatchID = $batchId;
        //set fk unit id
        $objStockDetail->fkUnitID = $unit;
        //set qty
        $objStockDetail->Qty = "-" . $value;
        //set is received
        $objStockDetail->IsReceived = 0;
        //set adjustment type
        $objStockDetail->adjustmentType = 2;
        //set vvm stage
        $objStockDetail->vvm_stage = $vvmstage;
        //set comment
        $objStockDetail->comments = $comments;

        $result = $objStockDetail->save();

        // Adjust Batch Quantity
        $objStockBatch->adjustQtyByWh($batchId, $_SESSION['user_warehouse']);

        if ($autorun == true) {
            $objStockBatch->autoRunningLEFOBatch($batch[0], $_SESSION['user_warehouse']);
            $objStockBatch->changeStatus($batchId, 'Finished');
        }
        foreach ($valueArr as $id => $value) {
            $sumArray[$id]+=$value;
        }
        //update clr6
        $updateCLR6 = mysql_query("UPDATE clr_details SET approval_status='Issued', stock_master_id=" . $fkStockID . " WHERE itm_id='" . $itemrec[$batch[0]] . "' AND pk_master_id=" . $clr6_id) or die(mysql_error());
    }
}

$objWhData->addReport($fkStockID, 2);
//get Wh Level By Stock ID
$result = $objStockMaster->getWhLevelByStockID($fkStockID);
if ($result['level'] == 3) {
    $result['uc_wh_id'];
    $objWhData->addReport($fkStockID, 10, '', $result['uc_wh_id']);
}
//add Report
$objWhData->addReport($fkStockID, 2);
//get Wh Level By Stock ID
$result = $objStockMaster->getWhLevelByStockID($fkStockID);
if ($result['level'] == 3) {
    $objWhData->addReport($fkStockID, 10, '', $result['uc_wh_id']);
}
if ($clr6_id) {
    $qry = "SELECT
				clr_details.stock_master_id,
				clr_details.approval_status
			FROM
				clr_details
			WHERE
				clr_details.pk_master_id = $clr6_id
			AND clr_details.approval_status <> 'Denied'
			AND clr_details.stock_master_id IS NULL
			AND clr_details.desired_stock > 0";
    $num = mysql_num_rows(mysql_query($qry));
    if ($num == 0) {
        $clr_master_status = 'Issued';
    } else {
        $clr_master_status = 'Issue in Process';
    }
}
$updateCLR6 = mysql_query("UPDATE clr_master SET approval_status='" . $clr_master_status . "' where pk_id=" . $clr6_id) or die(mysql_error());
header("location:" . APP_URL . "im/requisitions.php");
exit;
?>