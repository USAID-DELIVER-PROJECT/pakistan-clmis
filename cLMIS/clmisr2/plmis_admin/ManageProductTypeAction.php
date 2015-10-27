<?php
include("Includes/AllClasses.php");
$nstkId =0;
$ItemGroupName="";

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	$nstkId = $_REQUEST['hdnstkId'];
}

if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}

if(isset($_REQUEST['producttype']) && !empty($_REQUEST['producttype']))
{
	$producttype = $_REQUEST['producttype'];
}

//Filling value in stakeholder objects variables

$objitemtype->m_ItemTypeName = $producttype;
$objitemtype->m_npkId = $nstkId;

if($strDo=="Edit")
{
	$objitemtype->EditItemType();
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{

	$objitemtype->AddItemType();
	
	$_SESSION['err']['text'] = 'Data has been successfully addded.';
	$_SESSION['err']['type'] = 'success';
}


if($strDo=="Delete")
{
	$objitemtype->DeleteItemType();
}

header("location:ManageProductType.php");
exit;
?>