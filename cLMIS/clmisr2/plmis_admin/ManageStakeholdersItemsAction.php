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
	
	$objstakeholderitem->m_stk_id=$nstkId;
	$objstakeholderitem->Deletestakeholderitem();
	
	foreach($ItemID as $arec)
	{
		$objstakeholderitem->m_stk_item = $arec;
		$objstakeholderitem->m_stkid = $nstkId;
		//print "[".$objstakeholderitem->m_stk_item."]".$nstkId;
		
		$objstakeholderitem->Addstakeholderitem1();
	}
}

header("location:ManageStakeholdersItems.php");
exit;
?>