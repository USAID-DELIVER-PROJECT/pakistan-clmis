<?php
include("Includes/AllClasses.php");
/*if(!in_array($_SESSION['UserLvl'], array("1","2","3","4"))){
    echo "<script> window.location.href = 'index.php?strMsg=Please+login'; </script>";
    exit;
}*/
$strDo = "Add";
$nstkId = 0;
$remarks = '';
// Make data parmanent when user click on save button
if (isset($_REQUEST['stockid']) && !empty($_REQUEST['stockid'])) {
    ?>
    <script language="javascript">
        var ref_no, rec_no, rec_date, unit_pric, vvm_type, rec_from;
        ref_no = $('#receive_ref').val();
        rec_no = $('#receive_no').val();
        rec_date = $('#receive_date').val();
        unit_pric = $('#unit_price').val();
        vvm_type = $('#vvmtype').val();
        rec_from = $('#receive_from').val();
        window.open('vaccine_placement_details.php?rec_no=' + rec_no + '&ref_no=' + ref_no + '&rec_date=' + rec_date + '&unit_pric=' + unit_pric + '&vvm_type=' + vvm_type + '&rec_from=' + rec_from, '_blank', 'width=842,height=595');
    </script>
    <?php $stockid = $_REQUEST['stockid'];
    $objStockMaster->updateTemp($stockid);
    $objStockDetail->updateTemp($stockid);

    //Save Data in WH data table
   	$objWhData->addReport($stockid, 1);
	$_SESSION['success'] = 1;
    redirect("new_receive.php");
    exit;
}
// End save button

if (isset($_REQUEST['trans_no']) && !empty($_REQUEST['trans_no'])) {
    $trans_no = $_REQUEST['trans_no'];
}
if (isset($_REQUEST['stock_id']) && !empty($_REQUEST['stock_id'])) {
    $stock_id = $_REQUEST['stock_id'];
}
if (isset($_REQUEST['receive_date']) && !empty($_REQUEST['receive_date'])) {
    $receive_date = $_REQUEST['receive_date'];
}
if (isset($_REQUEST['receive_ref']) && !empty($_REQUEST['receive_ref'])) {
    $receive_ref = $_REQUEST['receive_ref'];
}
if (isset($_REQUEST['receive_from']) && !empty($_REQUEST['receive_from'])) {
    $receive_from = $_REQUEST['receive_from'];
}

if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
    $product = $_REQUEST['product'];
}
if (isset($_REQUEST['manufacturer']) && !empty($_REQUEST['manufacturer'])) {
    $actualManufacturer = $_REQUEST['manufacturer'];
//save manufacturer to item
$getStkItem="select * from stakeholder_item where stkid=".$actualManufacturer.' AND stk_item='.$product;
$resStkItem=mysql_query($getStkItem) or die("select * from stakeholder_item where stkid=".$actualManufacturer.' AND stk_item='.$product);
if(mysql_num_rows($resStkItem)==0)
{
	$addStkItem="insert into stakeholder_item set stkid=".$actualManufacturer.', stk_item='.$product;
	$resAddStkItem=mysql_query($addStkItem) or die("insert into stakeholder_item set stkid=".$actualManufacturer.', stk_item='.$product);	
}
	$getManufacturer=mysql_query("select stk_id from stakeholder_item where stkid=$actualManufacturer AND stk_item=$product");
	$manufacturerRow=mysql_fetch_assoc($getManufacturer);
	$manufacturer=$manufacturerRow['stk_id'];	
}
if (isset($_REQUEST['batch']) && !empty($_REQUEST['batch'])) {
    $batch = $_REQUEST['batch'];
}
if (isset($_REQUEST['expiry_date']) && !empty($_REQUEST['expiry_date'])) {
    $expiry_date = $_REQUEST['expiry_date'];
} else {
    $expiry_date = date('d/m/Y');
}
if (isset($_REQUEST['prod_date']) && !empty($_REQUEST['prod_date'])) {
    $prod_date = $_REQUEST['prod_date'];
}
if (isset($_REQUEST['qty']) && !empty($_REQUEST['qty'])) {
    $qty = str_replace(',', '', $_REQUEST['qty']);
}
if (isset($_REQUEST['unit']) && !empty($_REQUEST['unit'])) {
    $unit = $_REQUEST['unit'];
}
if (isset($_REQUEST['unit_price']) && !empty($_REQUEST['unit_price'])) {
    $unit_price = $_REQUEST['unit_price'];
}
if (isset($_REQUEST['remarks']) && !empty($_REQUEST['remarks'])) {
    $remarks = $_REQUEST['remarks'];
}

$objStockBatch->funding_source = $receive_from;

if (empty($trans_no) && $receive_from>0) {
	$dataArr = explode(' ', $receive_date);
	$time = date('H:i:s', strtotime($dataArr[1].$dataArr[2]));
	
    $objStockMaster->TranDate = dateToDbFormat($dataArr[0]).' '.$time;
    $objStockMaster->TranTypeID = 1;
    $objStockMaster->TranRef = $receive_ref;
    $objStockMaster->WHIDFrom = $receive_from;
    $objStockMaster->WHIDTo = $_SESSION['wh_id'];
    $objStockMaster->CreatedBy = $_SESSION['userid'];
    $objStockMaster->CreatedOn = date("Y-m-d");
    $objStockMaster->ReceivedRemarks = $remarks;

    $current_year = date("Y");
    $current_month = date("m");
    if ($current_month < 7) {
        $from_date = ($current_year - 1) . "-06-30";
        $to_date = $current_year . "-07-30";
    } else {
        $from_date = $current_year . "-06-30";
        $to_date = ($current_year + 1) . "-07-30";
    }

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
    $objStockBatch->wh_id = $_SESSION['wh_id'];
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
    $objStockBatch->wh_id = $_SESSION['wh_id'];
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
    $objStockDetail->manufacturer = $manufacturer;
    $objStockDetail->save();

    // Adjust Batch Quantity
    $objStockBatch->adjustQtyByWh($batch_id, $_SESSION['wh_id']);
    
    // Auto Running Batches
    $objStockBatch->autoRunningLEFOBatch($product, $_SESSION['wh_id']);
}

header("location:new_receive.php");
exit;
?>