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

if(isset($_REQUEST['txtStkName']) && !empty($_REQUEST['txtStkName']))
{
	$stkname = $_REQUEST['txtStkName'];
}

if(isset($_REQUEST['lstStktype']) && !empty($_REQUEST['lstStktype']))
{
	$stktype = $_REQUEST['lstStktype'];

}
if(isset($_REQUEST['lstLvl']) && !empty($_REQUEST['lstLvl']))
{
	$lstLvl = $_REQUEST['lstLvl'];
}


//Filling value in stakeholder objects variables

$objstk->m_stkname = $stkname;
$objstk->m_stk_type_id = $stktype;
$objstk->m_npkId = $nstkId;
$objstk->m_lvl=$lstLvl;

if($strDo=="Edit")
{
	$objstk->EditStakeholder();
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{
	$objstk->m_stkorder =$objstk->GetMaxRank()+1;
	$objstk->AddStakeholder();
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}


/*if($strDo=="Delete")
{
	$objstk->DeleteStakeholder();
}*/
unset($_SESSION['pk_id']);
header("location:ManageStakeholders.php");
exit;
?>