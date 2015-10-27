<?php
include("Includes/AllClasses.php");

/*if (!in_array($_SESSION['UserLvl'], array("1", "2", "3", "4"))) {
 echo "<script> window.location.href = 'index.php?strMsg=Please+login'; </script>";
 exit;
 }
 */
$strDo = "Add";
$nstkId = 0;
$autorun = false;
$vvmstage = '';


if (isset($_REQUEST['issue_date']) && !empty($_REQUEST['issue_date'])) {
	$issue_date = $_REQUEST['issue_date'];
}
if (isset($_REQUEST['clr6_id']) && !empty($_REQUEST['clr6_id'])) {
	$clr6_id = $_REQUEST['clr6_id'];
}
if (isset($_REQUEST['issue_ref']) && !empty($_REQUEST['issue_ref'])) {
	$issue_ref = $_REQUEST['issue_ref'];
}
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
	$issue_to = $_REQUEST['warehouse'];
} else {
	header("location:".SITE_URL."plmis_src/operations/issue.php?warehouse=1");

	exit;
}
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
	$product = $_REQUEST['product'];
}
if (isset($_REQUEST['itmrec']) && !empty($_REQUEST['itmrec'])) {
	$itemrec = $_REQUEST['itmrec'];
}
if (isset($_REQUEST['batch']) && !empty($_REQUEST['batch'])) {
	$batch = $_REQUEST['batch'];
}
if (isset($_REQUEST['qty_issued']) && !empty($_REQUEST['qty_issued'])) {
	$qty=$_REQUEST['qty_issued'];
}
$qty=array_filter($qty);
$batch=array_filter($batch);
foreach($qty as $key=>$value)
{
	$batch=explode("|",$key);
	$batchId=$batch[1];
}

$objStockMaster->TranDate = dateToDbFormat($issue_date);
$objStockMaster->TranTypeID = 2;
$objStockMaster->TranRef = $issue_ref;
$objStockMaster->WHIDFrom = $_SESSION['wh_id'];
$objStockMaster->WHIDTo = $issue_to;
$objStockMaster->CreatedBy = $_SESSION['userid'];
$objStockMaster->CreatedOn = date("Y-m-d");
//$objStockMaster->ReceivedRemarks = $remarks;
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
$trans_no = "I" . str_pad(($last_id + 1), 6, "0", STR_PAD_LEFT);
$objStockMaster->TranNo = $trans_no;
// $objStockMaster->temp = 1;
$objStockMaster->trNo = ($last_id + 1);
$objStockMaster->LinkedTr = 0;

$fkStockID = $objStockMaster->save();

if ($strDo == "Add") {
	foreach($qty as $key=>$value)
	{
	$value = str_replace(',', '', $value);
	$batch=explode("|",$key);
	 $batchId=$batch[1];
	 $itemrec[$batch[0]];
	 $valueArr[$batch[0]]=$value;

	 $objStockDetail->fkStockID = $fkStockID;
	 $objStockDetail->BatchID = $batchId;
	 $objStockDetail->fkUnitID = $unit;
	 $objStockDetail->Qty = "-" . $value;
	 //$objStockDetail->temp = 1;
	 $objStockDetail->IsReceived = 0;
	 $objStockDetail->adjustmentType = 2;
	 $objStockDetail->vvm_stage = $vvmstage;
	 $objStockDetail->comments = $comments;

	 $result = $objStockDetail->save();

	 // Adjust Batch Quantity
	 $objStockBatch->adjustQtyByWh($batchId, $_SESSION['wh_id']);

	 if ($autorun == true) {
	 	$objStockBatch->autoRunningLEFOBatch($batch[0], $_SESSION['wh_id']);
	 	$objStockBatch->changeStatus($batchId, 'Finished');
	 }
	 foreach ($valueArr as $id=>$value) {
		 $sumArray[$id]+=$value;
	 }

	 $updateCLR6=mysql_query("update clr_details set replenishment=".$sumArray[$batch[0]].",stock_master_id=".$fkStockID." where itm_id='".$itemrec[$batch[0]]."' AND pk_master_id=".$clr6_id) or die(mysql_error());
	 $updateDetailCLR6=mysql_query("update clr_details set approval_status='Issued' where itm_id='".$itemrec[$batch[0]]."' AND pk_master_id=".$clr6_id." AND replenishment>=approve_qty") or die(mysql_error());
	}
}

$objWhData->addReport($fkStockID, 2);
    $result = $objStockMaster->getWhLevelByStockID($fkStockID);
    if ($result['level'] == 3) {
    	 $result['uc_wh_id'];
         $objWhData->addReport($fkStockID, 10, '', $result['uc_wh_id']);
    }
$objWhData->addReport($fkStockID, 2);
    $result = $objStockMaster->getWhLevelByStockID($fkStockID);
    if ($result['level'] == 3) {
         $objWhData->addReport($fkStockID, 10, '', $result['uc_wh_id']);
    }
if($clr6_id){
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
	if($num == 0)
	{
		$clr_master_status='Issued';
	}
	else
	{
		$clr_master_status='Issue in Process';
	}
}
$updateCLR6=mysql_query("update clr_master set approval_status='".$clr_master_status."' where pk_id=".$clr6_id) or die(mysql_error());
header("location:".SITE_URL."plmis_src/operations/requisitions.php");
exit;
?>