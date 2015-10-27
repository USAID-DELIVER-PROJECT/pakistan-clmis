<?php
include("Includes/AllClasses.php");

$strDo = "Add";
$nstkId = 0;
$remarks = '';
if (isset($_REQUEST['area']) && !empty($_REQUEST['area'])) {
    $area = $_REQUEST['area'];
}
if (isset($_REQUEST['level']) && !empty($_REQUEST['level'])) {
    $level = $_REQUEST['level'];
}
$wh_id = $_SESSION['wh_id'];

$getLocationStatus=mysql_query("select * from placement_config where area=".$area." AND level=".$level."") or die("Err Ger Location Status");

header("location:placement_locations.php");
exit;
?>