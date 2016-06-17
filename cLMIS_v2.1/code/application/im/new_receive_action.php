<?php
/**
 * new_receive_action
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
//remarks
$remarks = '';
//initialize 
$prod_date = $unit_price = '';
// Make data parmanent when user click on save button
//check stock id
if (isset($_REQUEST['stockid']) && !empty($_REQUEST['stockid'])) {
    ?>
    <!--<script language="javascript">
        var ref_no, rec_no, rec_date, unit_pric, vvm_type, rec_from;
        ref_no = $('#receive_ref').val();
        rec_no = $('#receive_no').val();
        rec_date = $('#receive_date').val();
        unit_pric = $('#unit_price').val();
        vvm_type = $('#vvmtype').val();
        rec_from = $('#receive_from').val();
        window.open('vaccine_placement_details.php?rec_no=' + rec_no + '&ref_no=' + ref_no + '&rec_date=' + rec_date + '&unit_pric=' + unit_pric + '&vvm_type=' + vvm_type + '&rec_from=' + rec_from, '_blank', 'width=842,height=595');
    </script>-->
    <?php
    //get stock id
	$stockid = $_REQUEST['stockid'];
        //updste stock master
    $objStockMaster->updateTemp($stockid);
    //update stock detail
    $objStockDetail->updateTemp($stockid);

    //Save Data in WH data table
   	$objWhData->addReport($stockid, 1);
	$_SESSION['success'] = 1;
    redirect("new_receive.php");
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
//check receive date
if (isset($_REQUEST['receive_date']) && !empty($_REQUEST['receive_date'])) {
    //get receive date
    $receive_date = $_REQUEST['receive_date'];
}
//check receive ref
if (isset($_REQUEST['receive_ref']) && !empty($_REQUEST['receive_ref'])) {
    //get receive ref
    $receive_ref = $_REQUEST['receive_ref'];
}
//check receive from
if (isset($_REQUEST['receive_from']) && !empty($_REQUEST['receive_from'])) {
    //get receive from
    $receive_from = $_REQUEST['receive_from'];
}
//check product
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
    //get product
    $product = $_REQUEST['product'];
}
//check manufacturer
if (isset($_REQUEST['manufacturer']) && !empty($_REQUEST['manufacturer'])) {
    //get manufacturer
    $manufacturer = $_REQUEST['manufacturer'];
	
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
} else {
    $expiry_date = date('d/m/Y');
}
//check qty
if (isset($_REQUEST['qty']) && !empty($_REQUEST['qty'])) {
    //get qty
    $qty = str_replace(',', '', $_REQUEST['qty']);
}
//check unit
if (isset($_REQUEST['unit']) && !empty($_REQUEST['unit'])) {
    //get unit
    $unit = $_REQUEST['unit'];
}
//check remarks
if (isset($_REQUEST['remarks']) && !empty($_REQUEST['remarks'])) {
    //get remarks
    $remarks = $_REQUEST['remarks'];
}
//set funding source
$objStockBatch->funding_source = $receive_from;

if (empty($trans_no) && $receive_from>0) {
	$dataArr = explode(' ', $receive_date);
	$time = date('H:i:s', strtotime($dataArr[1].$dataArr[2]));
        //transaction date
    $objStockMaster->TranDate = dateToDbFormat($dataArr[0]).' '.$time;
    //transaction type id
    $objStockMaster->TranTypeID = 1;
    //transaction ref
    $objStockMaster->TranRef = $receive_ref;
    //from warehouse
    $objStockMaster->WHIDFrom = $receive_from;
    //to warehouse
    $objStockMaster->WHIDTo = $_SESSION['user_warehouse'];
    //created by
    $objStockMaster->CreatedBy = $_SESSION['user_id'];
    //created on
    $objStockMaster->CreatedOn = date("Y-m-d");
    //Received Remarks 
    $objStockMaster->ReceivedRemarks = $remarks;
    //current year
    $current_year = date("Y");
    //current month
    $current_month = date("m");
    if ($current_month < 7) {
        //from date
        $from_date = ($current_year - 1) . "-06-30";
        //to date
        $to_date = $current_year . "-07-30";
    } else {
        //from date
        $from_date = $current_year . "-06-30";
        //to date
        $to_date = ($current_year + 1) . "-07-30";
    }
    //get last id
    $last_id = $objStockMaster->getLastID($from_date, $to_date, 1);
    if ($last_id == NULL) {
        $last_id = 0;
    }
    $trans_no = "R" .  date('ym').str_pad(($last_id + 1), 4, "0", STR_PAD_LEFT);
    $objStockMaster->TranNo = $trans_no;
    $objStockBatch->batch_no = $batch;
    $objStockBatch->batch_expiry = dateToDbFormat($expiry_date);
    $objStockBatch->item_id = $product;
    $objStockBatch->Qty = $qty;
    $objStockBatch->status = "Stacked";
    $objStockBatch->production_date = dateToDbFormat($prod_date);
    $objStockBatch->unit_price = $unit_price;
    $objStockBatch->wh_id = $_SESSION['user_warehouse'];
	$objStockBatch->manufacturer = $manufacturer;
    $batch_id = $objStockBatch->save();

    $objStockMaster->BatchID = $batch_id;
    $objStockMaster->temp = 1;
    $objStockMaster->trNo = ($last_id + 1);
    $objStockMaster->LinkedTr = 0;
    $fkStockID = $objStockMaster->save();

} else {
    $fkStockID = $stock_id;
    $objStockBatch->batch_no = $batch;
    $objStockBatch->batch_expiry = dateToDbFormat($expiry_date);
    $objStockBatch->item_id = $product;
    $objStockBatch->Qty = $qty;
    $objStockBatch->status = "Stacked";
    $objStockBatch->production_date = dateToDbFormat($prod_date);
    $objStockBatch->unit_price = $unit_price;
    $objStockBatch->wh_id = $_SESSION['user_warehouse'];
    $objStockBatch->manufacturer = $manufacturer;
    $batch_id = $objStockBatch->save();
}

if ($strDo == "Add") {
    $objStockDetail->fkStockID = $fkStockID;
    $objStockDetail->BatchID = $batch_id;
    $objStockDetail->fkUnitID = $unit;
    $objStockDetail->IsReceived = 0;
    $objStockDetail->adjustmentType = 1;
    $objStockDetail->Qty = "+" . $qty;
    $objStockDetail->temp = 1;
    $objStockDetail->save();

    // Adjust Batch Quantity
    $objStockBatch->adjustQtyByWh($batch_id, $_SESSION['user_warehouse']);
    
    // Auto Running Batches
    $objStockBatch->autoRunningLEFOBatch($product, $_SESSION['user_warehouse']);
}

header("location:new_receive.php");
exit;
?>