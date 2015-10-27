<?php
 ob_start();
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;

if(isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
	$product = $_REQUEST['product'];
}
if(isset($_REQUEST['status']) && !empty($_REQUEST['status'])) {
	$status = $_REQUEST['status'];
}
if(isset($_REQUEST['batch_no']) && !empty($_REQUEST['batch_no'])) {
	$batch_no = $_REQUEST['batch_no'];
}
if(isset($_REQUEST['ref_no']) && !empty($_REQUEST['ref_no'])) {
	$ref_no = $_REQUEST['ref_no'];
}

$objStockBatch->item_id = $product;
$objStockBatch->batch_no = $batch_no;
$objStockBatch->TranRef = $ref_no;
$objStockBatch->search();

header("location:batch_management.php");
ob_flush();
exit;
 
?>