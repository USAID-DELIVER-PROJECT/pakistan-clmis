<?php
include("Includes/AllClasses.php");
$nstkId =0;
$stkname="";
$stkgroupid=0;
$strNewGroupName="";
$stktype=0;
$prov_id=0;

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	$nstkId = $_REQUEST['hdnstkId'];
}

if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}

if(isset($_REQUEST['ItemID']) && !empty($_REQUEST['ItemID']))
{
	$ItemID = $_REQUEST['ItemID'];
	$ItemOfGroup->m_GroupID=$nstkId;
	$ItemOfGroup->DeleteItemOfGroup();
	
	foreach($ItemID as $arec)
	{
		$ItemOfGroup->m_ItemID = $arec;
		$ItemOfGroup->m_GroupID = $nstkId;
		//print "[".$ItemOfGroup->m_ItemID."]".$nstkId;
		//exit;
		$ItemOfGroup->AddItemOfGroup();
	}
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}
header("location:ManageItemsofGroups.php");
exit;
?>