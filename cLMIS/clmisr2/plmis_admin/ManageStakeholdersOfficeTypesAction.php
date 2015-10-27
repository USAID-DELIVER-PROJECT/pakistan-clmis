<?php
include("Includes/AllClasses.php");
$hdnstktypeId =0;
$ParentID=0;
$stkname="";
$lstlvl=0;
if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	$strDo = $_REQUEST['hdnToDo'];
}
if(isset($_REQUEST['ID']) && !empty($_REQUEST['ID']))
{
	$ID = $_REQUEST['ID'];
}
if(isset($_REQUEST['txtStktype']) && !empty($_REQUEST['txtStktype']))
{
	$stkname = $_REQUEST['txtStktype'];
}
if(isset($_REQUEST['lstlvl']) && !empty($_REQUEST['lstlvl']))
{
	$lstlvl = $_REQUEST['lstlvl'];
	$objstk->m_lvl=$lstlvl;
}

if(isset($_REQUEST['lststkholdersParent']) && !empty($_REQUEST['lststkholdersParent']))
{
	$ParentID = $_REQUEST['lststkholdersParent'];
		$objstk->m_ParentID=$ParentID;
}

if(isset($_REQUEST['Stakeholders']) && !empty($_REQUEST['Stakeholders']))
{
	$MainStakeholder = $_REQUEST['Stakeholders'];
		$objstk->m_MainStakeholder=$MainStakeholder;
}
$objstk->m_stkname = $stkname;

$getStkType = mysql_fetch_array(mysql_query("SELECT
												stakeholder.stk_type_id
											FROM
												stakeholder
											WHERE
												stakeholder.stkid =" . $_POST['Stakeholders']));
$objstk->m_stk_type_id	= $getStkType['stk_type_id'];



if($strDo=="Edit")
{
	$objstk->m_npkId = $ID;
	$objstk->EditStakeholder();
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}

if($strDo=="Add")
{
 	$objstk->m_stkorder=$objstk->GetMaxRank()+1; //max value
	$objstk->AddStakeholder();
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}
header("location:ManageStakeholdersOfficeTypes.php");
exit;
?>