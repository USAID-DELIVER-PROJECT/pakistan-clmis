<?php
require_once("Includes/AllClasses.php");

if(isset($_POST['product']) && !empty($_POST['product'])){
	$product = $_POST['product'];
	$cat = $objManageItem->GetProductCat($product);
	
	if($cat != false){
		echo $cat;
	}
}

if(isset($_POST['qty']) && !empty($_POST['qty']) && !empty($_POST['itemId']))
{
	$qty = $_POST['qty'];
	$product = $_POST['itemId'];
	$doses = $objManageItem->GetProductDoses($product);
	
	if($doses != false)
	{
		//echo $doses * str_replace(',', '', $qty) . ' Doses';
	}
}
?>