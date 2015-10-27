<?php
include("Includes/AllClasses.php");

$loc_level = $_REQUEST['loc_level'];
$prov_id = $_REQUEST['provinces'];
$dist_id = isset($_REQUEST['dist_id']) ? $_REQUEST['dist_id'] : '';
$loc_type = $_REQUEST['loc_type'];
$loc_name = $_REQUEST['loc_name'];

if ( $loc_level == 3 )
{
	$parent_id = $prov_id;
}
else
{
	$parent_id = $dist_id;
}
$objloc->PkLocID = (isset($_REQUEST['hdnstkId'])) ? $_REQUEST['hdnstkId'] : '';
$objloc->LocLvl = $loc_level;
$objloc->LocType = $loc_type;
$objloc->LocName = $loc_name;
$objloc->ParentID = $parent_id;

$strDo = $_REQUEST['hdnToDo'];

if($strDo=="Edit")
{
	$objloc->Editlocations();
	$_SESSION['err']['text'] = 'Data has been successfully updated.';
	$_SESSION['err']['type'] = 'success';
}
if($strDo=="Add")
{
	$objloc->Addlocations();
	$_SESSION['err']['text'] = 'Data has been successfully added.';
	$_SESSION['err']['type'] = 'success';
}

header("location:ManageLocations.php");
exit;