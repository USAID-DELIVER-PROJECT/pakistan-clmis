<?php
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId = 0;
$ref_no = '';
$quantity = '';

if (isset($_REQUEST['adjustment_no']) && !empty($_REQUEST['adjustment_no'])) {
    $trans_no = $_REQUEST['adjustment_no'];
}
if (isset($_REQUEST['adjustment_date']) && !empty($_REQUEST['adjustment_date'])) {
    $adjustment_date = $_REQUEST['adjustment_date'];
    list($dd,$mm,$yy) = explode("/",$adjustment_date);
} else {
	 $adjustment_date = date('m/d/Y');
    list($dd,$mm,$yy) = explode("/",$adjustment_date);
}
if (isset($_REQUEST['ref_no']) && !empty($_REQUEST['ref_no'])) {
    $ref_no = $_REQUEST['ref_no'];
}
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
    $product = $_REQUEST['product'];
}
if (isset($_REQUEST['batch_no']) && !empty($_REQUEST['batch_no'])) {
    $batch_id = $_REQUEST['batch_no'];
}
if (isset($_REQUEST['available']) && !empty($_REQUEST['available'])) {
    $available = $_REQUEST['available'];
}
if (isset($_REQUEST['types']) && !empty($_REQUEST['types'])) {
    $type = $_REQUEST['types'];
}
if (isset($_REQUEST['quantity']) && !empty($_REQUEST['quantity'])) {
    $quantity = str_replace(',','',$_REQUEST['quantity']);
}
if (isset($_REQUEST['comments']) && !empty($_REQUEST['comments'])) {
    $comments = $_REQUEST['comments'];
}
if (isset($_REQUEST['unit']) && !empty($_REQUEST['unit'])) {
    $unit = $_REQUEST['unit'];
}

$objStockMaster->TranDate = $yy."-".$mm."-".$dd;
$objStockMaster->TranTypeID = $type;
$objStockMaster->TranRef = $ref_no;
$objStockMaster->WHIDFrom = $_SESSION['wh_id'];
$objStockMaster->WHIDTo = $_SESSION['wh_id'];
$objStockMaster->CreatedBy = $_SESSION['userid'];
$objStockMaster->CreatedOn = date("Y-m-d");
$objStockMaster->ReceivedRemarks = $comments;
$fy_dates = $objFiscalYear->getFiscalYear();
$last_id  = $objStockMaster->getAdjLastID($fy_dates['from_date'],$fy_dates['to_date']);

if ($last_id == NULL) {
	$last_id = 0;
}
$trans_no = "A". date('ym').str_pad(($last_id + 1), 4, "0", STR_PAD_LEFT);
$objStockMaster->TranNo = $trans_no;

$objStockMaster->BatchID = $batch_id;
$objStockMaster->temp = 0;
$objStockMaster->trNo = $last_id + 1;
$objStockMaster->LinkedTr = 0;
$fkStockID = $objStockMaster->save();

$type_nature = $objTransType->find_by_id($type);

//$objStockBatch->Qty = $quantity;
//$objStockBatch->adjustQty($batch_id, "Qty".$type_nature->trans_nature.$quantity);

if ($strDo == "Add") {
    $objStockDetail->fkStockID = $fkStockID;
    $objStockDetail->BatchID = $batch_id;
    $objStockDetail->Qty = $type_nature->trans_nature.$quantity;
	$objStockDetail->adjustmentType = $type;
    $objStockDetail->temp = 0;
	$objStockDetail->fkunitID = $unit;
    $objStockDetail->save();
}

$adjustedQty = $objStockBatch->adjustQtyByWh($batch_id, $_SESSION['wh_id']);
$objWhData->addReport($fkStockID, $type);

$_SESSION['success'] = 1;
header("location:add_adjustment.php");
exit;
?>