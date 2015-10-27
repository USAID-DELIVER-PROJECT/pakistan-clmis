<?php
require_once("Includes/AllClasses.php");

if(isset($_POST['product']) && !empty($_POST['product'])){
	$product = $_POST['product'];
	$name = $objManageItem->GetProductName($product);
	
	if($name != false){
		echo $name;
	}
}


?>