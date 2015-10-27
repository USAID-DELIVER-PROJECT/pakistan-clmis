<?php
include("Includes/AllClasses.php");

if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];

    /*$stockDetailId = $_REQUEST['id'];

    $getDetailEntry = "select * from tbl_stock_detail where PKDetailID=" . $stockDetailId;
    $resDetailEntry = mysql_query($getDetailEntry) or die(mysql_error());
    $detailRow = mysql_fetch_assoc($resDetailEntry);
    if ($detailRow) {
        // print_r($detailRow);
        $getMasterEntry = 'select * from tbl_stock_master where PKStockID=' . $detailRow['fkStockID'];
        $resmasterEntry = mysql_query($getMasterEntry) or die(mysql_error());
        $masterRow = mysql_fetch_assoc($resmasterEntry);
        // print_r($masterRow);
        $getBatchEntry = 'select * from stock_batch where batch_id=' . $detailRow['BatchID'];
        $resbatchEntry = mysql_query($getBatchEntry) or die(mysql_error());
        $batchRow = mysql_fetch_assoc($resbatchEntry);
        //print_r($batchRow);

        $insertLogMaster = 'insert into log_tbl_stock_master set PKStockID="' . $detailRow['fkStockID'] . '",TranDate="' . $masterRow['TranDate'] . '",TranNo="' . $masterRow['TranNo'] . '",TranTypeID="' . $masterRow['TranTypeID'] . '",TranRef="' . $masterRow['TranRef'] . '",WHIDFrom="' . $masterRow['WHIDFrom'] . '",WHIDTo="' . $masterRow['WHIDTo'] . '",CreatedBy="' . $masterRow['CreatedBy'] . '",CreatedOn="' . $masterRow['CreatedOn'] . '",ReceivedRemarks="' . $masterRow['ReceivedRemarks'] . '",temp="' . $masterRow['temp'] . '",trNo="' . $masterRow['trNo'] . '",LinkedTr="' . $masterRow['LinkedTr'] . '",deleted_on="' . date('Y-m-d H:i:s') . '",deleted_by="' . $_SESSION['userid'] . '"';
        mysql_query($insertLogMaster) or die(mysql_error());

        $insertLogDetail = 'insert into log_tbl_stock_detail set PKDetailID="' . $detailRow['PkDetailID'] . '",fkStockID="' . $detailRow['fkStockID'] . '",BatchID="' . $detailRow['BatchID'] . '",fkUnitID="' . $detailRow['fkUnitID'] . '",Qty="' . $detailRow['Qty'] . '",temp="' . $detailRow['temp'] . '",vvm_stage="' . $detailRow['vvm_stage'] . '",IsReceived="' . $detailRow['IsReceived'] . '",adjustmentType="' . $detailRow['adjustmentType'] . '",comments="' . $detailRow['comments'] . '",deleted_on="' . date('Y-m-d H:i:s') . '",deleted_by="' . $_SESSION['userid'] . '"';
        mysql_query($insertLogDetail) or die(mysql_error());

        $insertLogBatch = 'insert into log_stock_batch set batch_id="' . $batchRow['batch_id'] . '",batch_no="' . $batchRow['batch_no'] . '",batch_expiry="' . $batchRow['batch_expiry'] . '",item_id="' . $batchRow['item_id'] . '",Qty="' . $batchRow['Qty'] . '",status="' . $batchRow['status'] . '",unit_price="' . $batchRow['unit_price'] . '",production_date="' . $batchRow['production_date'] . '",vvm_type="' . $batchRow['vvm_type'] . '",wh_id="' . $batchRow['wh_id'] . '",deleted_on="' . date('Y-m-d H:i:s') . '",deleted_by="' . $_SESSION['userid'] . '"';
        mysql_query($insertLogBatch) or die(mysql_error());
        // exit;
    }*/
    $objStockDetail->deleteReceive($id);
    //$objLogStockDetail->create();

    if (!empty($_REQUEST['p']) && $_REQUEST['p'] == 'stock') {
		$_SESSION['success'] = 2;
        redirect("stock_receive.php");
        exit;
    }
	$_SESSION['success'] = 2;
    redirect("new_receive.php");
    exit;
}
?>