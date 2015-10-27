<?php
include("Includes/AllClasses.php");
$nstkId =0;
$CategoryGroupName="";

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	$nstkId = $_REQUEST['hdnstkId'];
}

if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}

if(isset($_REQUEST['productcategory']) && !empty($_REQUEST['productcategory']))
{
	$productcategory = $_REQUEST['productcategory'];
}

//Filling value in stakeholder objects variables

$objitemcategory->m_ItemCategoryName = $productcategory;
$objitemcategory->m_npkId = $nstkId;

if($strDo=="Edit")
{
	$objitemcategory->EditItemCategory();
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{

	$objitemcategory->AddItemCategory();
	
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}


if($strDo=="Delete")
{
	$objitemcategory->DeleteItemCategory();
}

header("location:ManageProductCategory.php");
exit;
?>