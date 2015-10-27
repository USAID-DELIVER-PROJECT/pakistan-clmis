<?php
include("Includes/AllClasses.php");
/*if(!in_array($_SESSION['UserLvl'], array("1","2","3","4"))){
    echo "<script> window.location.href = 'index.php?strMsg=Please+login'; </script>";
    exit;
}*/

$strDo = "Add";
$nstkId = 0;
$remarks = '';
if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $update = $_REQUEST['id'];
}
if (isset($_REQUEST['area']) && !empty($_REQUEST['area'])) {
    $area = $_REQUEST['area'];
}
if (isset($_REQUEST['row']) && !empty($_REQUEST['row'])) {
    $row = $_REQUEST['row'];
}
if (isset($_REQUEST['rack']) && !empty($_REQUEST['rack'])) {
    $rack = $_REQUEST['rack'];
}
if (isset($_REQUEST['rack_type']) && !empty($_REQUEST['rack_type'])) {
    $rack_type = $_REQUEST['rack_type'];
}
if (isset($_REQUEST['pallet']) && !empty($_REQUEST['pallet'])) {
    $pallet = $_REQUEST['pallet'];
}
if (isset($_REQUEST['level']) && !empty($_REQUEST['level'])) {
    $level = $_REQUEST['level'];
}
$wh_id = $_SESSION['wh_id'];
$getListMaster=mysql_query("select pk_id,list_master_name from list_master") or die("ERR list master");
while($resListMaster=mysql_fetch_assoc($getListMaster))
{
	 $getDetail=mysql_query("select pk_id,list_value from list_detail where list_master_id=".$resListMaster['pk_id']) or die(mysql_error());
		while($resListDetail=mysql_fetch_assoc($getDetail))
		{
			//print_r($resListDetail);
			//echo $resListDetail['pk_id']."--".$area;
			if($resListDetail['pk_id']==$area)
			{
				 $locNameArr[]=$resListDetail['list_value'];
			}
		if($resListDetail['pk_id']==$row)
			{
				 $locNameArr[]=$resListDetail['list_value'];
			}
		if($resListDetail['pk_id']==$rack)
			{
				 $locNameArr[]=$resListDetail['list_value'];
			}
		if($resListDetail['pk_id']==$pallet)
			{
				 $locNameArr[]=$resListDetail['list_value'];
			}
		if($resListDetail['pk_id']==$level)
			{
				 $locNameArr[]=$resListDetail['list_value'];
			}
		}
		
}
$locName=implode('',$locNameArr);
if($update)
{
	$addPlacementLocation=mysql_query("update placement_config set location_name='".$locName."',warehouse_id=".$wh_id.",rack_information_id=".$rack_type.",area=".$area.",row=".$row.",rack=".$rack.",pallet=".$pallet.",level=".$level." where pk_id=".$update);
}
else
{
	// Check location name if already exists
	$qry = "SELECT
				placement_config.location_name
			FROM
				placement_config
			WHERE
				placement_config.location_name = '".$locName."'
			AND placement_config.warehouse_id=".$wh_id."";
	$num = (mysql_num_rows(mysql_query($qry)));
	if($num == 0)
	{	
		$addPlacementLocation=mysql_query("INSERT INTO placement_config set location_name='".$locName."',warehouse_id=".$wh_id.",rack_information_id=".$rack_type.",area=".$area.",row=".$row.",rack=".$rack.",pallet=".$pallet.",level=".$level."");
		$_SESSION['success'] = 2;
		$url = "stock_placement.php";
	}
	else
	{
		$_SESSION['success'] = 1;
		$url = "stock_placement.php";
	}
}
header("location: $url");
exit;
?>