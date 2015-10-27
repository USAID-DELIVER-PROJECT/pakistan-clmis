<?php
include("Includes/AllClasses.php");
$nstkId =0;
$statusGroupName="";

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	$nstkId = $_REQUEST['hdnstkId'];
}

if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}

if(isset($_REQUEST['productstatus']) && !empty($_REQUEST['productstatus']))
{
	$productstatus = $_REQUEST['productstatus'];
}

//Filling value in stakeholder objects variables

$objitemstatus->m_ItemStatusName = $productstatus;
$objitemstatus->m_npkId = $nstkId;

if($strDo=="Edit")
{
	$objitemstatus->EditItemStatus();
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{

	$objitemstatus->AddItemStatus();
	
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}


if($strDo=="Delete")
{
	$objitemstatus->DeleteItemStatus();
}

header("location:ManageProductStatus.php");
exit;
?>