<?php
/**
 * ajaxproductname
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
include("../includes/classes/AllClasses.php");

if(isset($_POST['product']) && !empty($_POST['product'])){
	$product = $_POST['product'];
	$name = $objManageItem->GetProductName($product);
	
	if($name != false){
		echo $name;
	}
}


?>