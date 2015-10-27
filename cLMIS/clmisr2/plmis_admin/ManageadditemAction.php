<?php
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;
$itm_name="";
$itm_type="";
$itm_category="";
$qty_carton=0;
$field_color="";
$itm_des="";
$itm_status="";
$frmindex=0;
$extra="";
$stkname="";
$stkid=0;
$stkorder=0;

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	$nstkId = $_REQUEST['hdnstkId'];
}

if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}

if(isset($_REQUEST['txtStkName1']) && !empty($_REQUEST['txtStkName1']))
{
	$itm_name = $_REQUEST['txtStkName1'];
}
if(isset($_REQUEST['txtStkName2']) && !empty($_REQUEST['txtStkName2']))
{
	$itm_type = $_REQUEST['txtStkName2'];
}

if(isset($_REQUEST['txtStkName4']) && !empty($_REQUEST['txtStkName4']))
{
	$itm_category = $_REQUEST['txtStkName4'];
}

if(isset($_REQUEST['txtStkName5']) && !empty($_REQUEST['txtStkName5']))
{
	$field_color = $_REQUEST['txtStkName5'];

}

if(isset($_REQUEST['txtStkName6']) && !empty($_REQUEST['txtStkName6']))
{
	$itm_status = $_REQUEST['txtStkName6'];
}
if(isset($_REQUEST['txtStkName7']) && !empty($_REQUEST['txtStkName7']))
{
	$itm_des = $_REQUEST['txtStkName7'];
}

if(isset($_REQUEST['txtStkName8']) && !empty($_REQUEST['txtStkName8']))
{
	$frmindex = $_REQUEST['txtStkName8'];

}

if(isset($_REQUEST['stkid']) && !empty($_REQUEST['stkid']))
{
	$stkid = $_REQUEST['stkid'];
}

//product group id here

if(isset($_REQUEST['select2']) && !empty($_REQUEST['select2']))
{
	$productgroupid = $_REQUEST['select2'];
}

//Filling value in stakeholder objects variables

$objManageItem->m_npkId = $nstkId;
$objManageItem->m_itm_name = $itm_name;
$objManageItem->m_itm_type = $itm_type;
$objManageItem->m_itm_category=$itm_category;

$objManageItem->m_qty_carton = $qty_carton;
//$objManageItem->m_field_color = $field_color;
$objManageItem->m_itm_des = $itm_des;
$objManageItem->m_itm_status=$itm_status;

$objManageItem->m_frmindex = $frmindex;
$objManageItem->m_extra = $extra;
//$objManageItem->m_stkname = $stkname;
//$objManageItem->m_stkid=$stkid;
//$objManageItem->m_stkorder=$stkorder;

if($strDo=="Edit")
{
	$objManageItem->EditManageItem();
	
	//editing value from stakeholder table
	$objstakeholderitem->m_stk_item=$nstkId;
	$objstakeholderitem->m_stkid=$stkid;
	$objstakeholderitem->Editstkholderitem();
	
	//editing values from product group table
	$ItemOfGroup->m_ItemID=$nstkId;	
	$ItemOfGroup->m_GroupID=$productgroupid;
	$ItemOfGroup->EditItemGroup();
	
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
	
}

if($strDo=="Add")
{
	//$itemid=$objManageItem->AddManageItem();
	
	$itemid=$objManageItem->AddManageItem();
	
	
	//calling method to add values in stakeholder item
	$objstakeholderitem->m_stk_item=$itemid;	
	
	//$objstakeholderitem->stk_n=$n_stk;
	$objstakeholderitem->m_stkid=$stkid;
	$objstakeholderitem->Addstakeholderitem();
	
	//calling method to add values in item of groups
	$ItemOfGroup->m_ItemID=$itemid;	
	
	//$ItemOfGroup->n_group=$ngroup;
	$ItemOfGroup->m_GroupID=$productgroupid;
	$ItemOfGroup->AddItemOfGroup1();
	
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
	
}


/*if($strDo=="Delete")
{
	$objManageItem->DeleteManageItem();
	
	//deleting value from stakeholder item
	$objstakeholderitem->m_stk_item=$nstkId;
	$objstakeholderitem->Deletestkholderitem();
	
	
	//deleting value from items of groups
	$ItemOfGroup->m_ItemID=$nstkId;	
	$ItemOfGroup-> DeleteItemGroup();
	
	
	
	
}*/
header("location:ManageItems.php");
exit;
?>