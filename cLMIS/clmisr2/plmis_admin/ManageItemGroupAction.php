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

if(isset($_REQUEST['ItemGroupName']) && !empty($_REQUEST['ItemGroupName']))
{
	$ItemGroupName = $_REQUEST['ItemGroupName'];
}

//Filling value in stakeholder objects variables

$ItemGroup->m_ItemGroupName = $ItemGroupName;
$ItemGroup->m_npkId = $nstkId;

if($strDo=="Edit")
{
	$ItemGroup->EditItemGroup();
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{
	$ItemGroup->AddItemGroup();
	
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}


if($strDo=="Delete")
{
	$ItemGroup->DeleteItemGroup();
}

header("location:ManageItemsGroups.php");
exit;
?>