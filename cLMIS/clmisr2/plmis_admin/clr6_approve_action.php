<?php
include("Includes/AllClasses.php");

$status = "Pending";
$approved = '';
$userid=$_SESSION['userid'];

if (isset($_REQUEST['clr6_id']) && !empty($_REQUEST['clr6_id'])) {
	$clr6_id = $_REQUEST['clr6_id'];
}
if (isset($_REQUEST['rq_no']) && !empty($_REQUEST['rq_no'])) {
	$rq = $_REQUEST['rq_no'];
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
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
	$warehouse = $_REQUEST['warehouse'];
}
if (isset($_REQUEST['itmrec']) && !empty($_REQUEST['itmrec'])) {
	$itemrec = $_REQUEST['itmrec'];
}

if (isset($_REQUEST['qty_approved']) && !empty($_REQUEST['qty_approved'])) {
	$qty=$_REQUEST['qty_approved'];
}
if (isset($_REQUEST['approve']) && !empty($_REQUEST['approve'])) {
	$approveItm=$_REQUEST['approve'];
}
if (isset($_REQUEST['qty_available']) && !empty($_REQUEST['qty_available'])) {
	$qtyAvailable=$_REQUEST['qty_available'];
}
if(!empty($clr6_id))
{
	foreach ($itemrec as $key=>$value)
	{
		$approvedQty = str_replace(',', '', $qty[$key]);
		$availableQty = str_replace(',', '', $qtyAvailable[$key]);
		$itm_id = $itemrec[$key];
		if(($approveItm[$key] && $approvedQty>0))
		{
			$approved = true;
			$status='Approved';
			$approveClr6Detail="update clr_details set available_qty=".$availableQty.",approve_qty=".$approvedQty.", approval_status='".$status."',approved_by=".$userid.", approve_date='".date('Y-m-d H:i:s')."' where pk_master_id=".$clr6_id." AND itm_id='".$itm_id."'";
			$resUpdateClrDetail=mysql_query($approveClr6Detail) or die(mysql_error());
		}
		else
		{
			$status='Denied';
			$approveClr6Detail="update clr_details set available_qty=".$availableQty.",approval_status='".$status."',approved_by=".$userid.", approve_date='".date('Y-m-d H:i:s')."' where pk_master_id=".$clr6_id." AND itm_id='".$itm_id."'";
			$resUpdateClrDetail=mysql_query($approveClr6Detail) or die(mysql_error());
		}
	}
	$status = ($approved === true) ? 'Approved' : 'Denied';
	mysql_query("UPDATE clr_master SET approval_status = '$status' WHERE pk_id = $clr6_id");
}
//header("location:".SITE_URL."plmis_src/operations/issue.php?id=".$clr6_id."&wh_id=".$warehouse."&rq=".$rq);
header("location:".SITE_URL."plmis_src/operations/requisitions.php");
exit;
?>