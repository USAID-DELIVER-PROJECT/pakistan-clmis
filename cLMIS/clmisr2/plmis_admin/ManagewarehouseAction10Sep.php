<?php
include("Includes/AllClasses.php");
$nwharehouseId =0;
$npkId=0;
$stkOfficeId="";
$dist_id=0;
$prov_id=0;
$stkid=0;
$wh_type_id="";
$stkname="";
$wh_desc="";
$wh_name="";

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))	$nstkId = $_REQUEST['hdnstkId'];
if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))	$strDo = $_REQUEST['hdnToDo'];
if(isset($_REQUEST['Stakeholders']) && !empty($_REQUEST['Stakeholders']))	$stkid = $_REQUEST['Stakeholders'];
if(isset($_REQUEST['StakeholdersOffices']) && !empty($_REQUEST['StakeholdersOffices']))	$stkofficeid = $_REQUEST['StakeholdersOffices'];
if(isset($_REQUEST['Provinces']) && !empty($_REQUEST['Provinces']))	$prov_id = $_REQUEST['Provinces'];
if(isset($_REQUEST['districts']) && !empty($_REQUEST['districts']))	$dist_id = $_REQUEST['districts'];
if(isset($_REQUEST['wh_name']) && !empty($_REQUEST['wh_name']))	$wh_name = $_REQUEST['wh_name'];

$objwarehouse->m_npkId = $nstkId;
$objwarehouse->m_stkid = $stkid;
$objwarehouse->m_stkofficeid=$stkofficeid;
$objwarehouse->m_prov_id = $prov_id;
$objwarehouse->m_dist_id=$dist_id;
$objwarehouse->m_wh_name = $wh_name;

if($strDo=="Edit")	$objwarehouse->Editwarehouse();
if($strDo=="Add")	$objwarehouse->Addwarehouse();
/*if($strDo=="Delete")	$objwarehouse->Deletewarehouse();*/

header("location:ManageWarehouse.php");
exit;
?>
