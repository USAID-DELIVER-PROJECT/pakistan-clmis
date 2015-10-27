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
if(isset($_REQUEST['month']) && !empty($_REQUEST['month']))	$wh_month = $_REQUEST['month'];
if(isset($_REQUEST['year']) && !empty($_REQUEST['year']))	$wh_year = $_REQUEST['year'];
if(isset($_REQUEST['wh_type']) && !empty($_REQUEST['wh_type']))	$hf_type_id = $_REQUEST['wh_type'];

$objwarehouse->m_npkId = $nstkId;
$objwarehouse->m_stkid = $stkid;
$objwarehouse->m_stkofficeid=$stkofficeid;
$objwarehouse->m_prov_id = $prov_id;
$objwarehouse->m_dist_id=$dist_id;
$objwarehouse->m_wh_name = $wh_name;
$objwarehouse->hf_type_id = $hf_type_id;

if($strDo=="Edit") {
	$objwarehouse->Editwarehouse();
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}
if($strDo=="Add"){
	$id = $objwarehouse->Addwarehouse();
	/*$objWhData->m_report_month = $wh_month;
	$objWhData->m_report_year = $wh_year;
	$objWhData->m_item_id = 'IT-001';
	$objWhData->m_wh_id = $id;
	$objWhData->m_wh_obl_a = '0';
	$objWhData->m_wh_obl_c = '0';
	$objWhData->m_wh_received = '0';
	$objWhData->m_wh_issue_up = '0';
	$objWhData->m_wh_cbl_a = '0';
	$objWhData->m_wh_cbl_c = '0';
	$objWhData->m_wh_adja = '0';
	$objWhData->m_wh_adjb = '0';
	$objWhData->m_RptDate = $wh_year."-".$wh_month."-01";
	$objWhData->AddWarehouseData();*/
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}
header("location:ManageWarehouse.php");
exit;