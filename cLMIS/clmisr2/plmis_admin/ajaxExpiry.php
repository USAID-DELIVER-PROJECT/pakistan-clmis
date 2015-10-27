<?php
include("Includes/AllClasses.php");
if (isset($_REQUEST['Id']) && !empty($_REQUEST['Id'])) {
    $id = $_REQUEST['Id'];
    $type = $_REQUEST['type'];
    $data = $_REQUEST['data'];

    if ($type == 'expiry' && $data != '') {
        $date = dateToDbFormat($data);
        $objStockBatch->editBatchExpiry($id, $date);
    }
}
?>