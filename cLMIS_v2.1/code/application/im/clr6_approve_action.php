<?php

/**
 * clr6_approve_action
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Including AllClasses file
include("../includes/classes/AllClasses.php");
//Initializing variables
//status
$status = "Pending";
//approved
$approved = '';
//Getting user_id
$userid = $_SESSION['user_id'];
//Checking clr6_id
if (isset($_REQUEST['clr6_id']) && !empty($_REQUEST['clr6_id'])) {
    //Getting clr6_id
    $clr6_id = $_REQUEST['clr6_id'];
}
//Checking rq_no
if (isset($_REQUEST['rq_no']) && !empty($_REQUEST['rq_no'])) {
    //Getting rq_no
    $rq = $_REQUEST['rq_no'];
}
//Checking warehouse
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
    //Getting warehouse
    $issue_to = $_REQUEST['warehouse'];
} else {
    header("location:" . SITE_URL . "plmis_src/operations/issue.php?warehouse=1");
    exit;
}
//Checking product_status
if (isset($_REQUEST['product_status']) && !empty($_REQUEST['product_status'])) {
    //Getting product_status
    $product_status = $_REQUEST['product_status'];
}
//Checking warehouse
if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
    //Getting warehouse
    $warehouse = $_REQUEST['warehouse'];
}
//Checking itmrec
if (isset($_REQUEST['itmrec']) && !empty($_REQUEST['itmrec'])) {
    //Getting itmrec
    $itemrec = $_REQUEST['itmrec'];
}
//Checking qty_approved
if (isset($_REQUEST['qty_approved']) && !empty($_REQUEST['qty_approved'])) {
    //Getting qty_approved
    $qty = $_REQUEST['qty_approved'];
}
//Checking approve
if (isset($_REQUEST['approve']) && !empty($_REQUEST['approve'])) {
    //Getting approve
    $approveItm = $_REQUEST['approve'];
}
//Checking qty_available
if (isset($_REQUEST['qty_available']) && !empty($_REQUEST['qty_available'])) {
    //Getting qty_available
    $qtyAvailable = $_REQUEST['qty_available'];
}
//Checking $clr6_id
if (!empty($clr6_id)) {
    //Update clr details
    $qry = "UPDATE clr_details SET approve_qty = 0 WHERE pk_master_id=" . $clr6_id . " AND approval_status != 'Issued' ";
    mysql_query($qry);

    foreach ($itemrec as $key => $value) {
        $approvedQty = str_replace(',', '', $qty[$key]);
        $availableQty = str_replace(',', '', $qtyAvailable[$key]);
        $itm_id = $itemrec[$key];
        //checking product_status
        if ($product_status[$key] != 'Issued') {
            if (($approveItm[$key] && $approvedQty > 0)) {
                $approved = true;
                $status = 'Approved';
                $approveClr6Detail = "update clr_details set available_qty=" . $availableQty . ",approve_qty=" . $approvedQty . ", approval_status='" . $status . "',approved_by=" . $userid . ", approve_date='" . date('Y-m-d H:i:s') . "' where pk_master_id=" . $clr6_id . " AND itm_id='" . $itm_id . "'";
                $resUpdateClrDetail = mysql_query($approveClr6Detail) or die(mysql_error());
            } else {
                $status = 'Denied';
                $approveClr6Detail = "update clr_details set available_qty=" . $availableQty . ",approval_status='" . $status . "',approved_by=" . $userid . ", approve_date='" . date('Y-m-d H:i:s') . "' where pk_master_id=" . $clr6_id . " AND itm_id='" . $itm_id . "'";
                $resUpdateClrDetail = mysql_query($approveClr6Detail) or die(mysql_error());
            }
        }
    }
    $status = ($approved === true) ? 'Approved' : 'Denied';
    //Query for check Detail Status
    $checkDetailStatus = "SELECT
							clr_details.pk_id
						FROM
							clr_details
						WHERE
							clr_details.pk_master_id = $clr6_id
						AND clr_details.approval_status = 'Issued' ";
    if (mysql_num_rows(mysql_query($checkDetailStatus)) == 0) {
        mysql_query("UPDATE clr_master SET approval_status = '$status' WHERE pk_id = $clr6_id");
    }
}
//Redirecting to im/requisitions
header("location: " . APP_URL . "im/requisitions.php");
exit;
?>