<?php
include("Includes/AllClasses.php");
if (isset($_REQUEST['req']) && !empty($_REQUEST['req'])) {
    $id = $_REQUEST['req'];
	
    $updateStatus="update clr_master set approval_status='Declined' where pk_id=".$id;
    $resUpdateStatus=mysql_query($updateStatus) or die(mysql_error());

    header("location:".SITE_URL."plmis_src/operations/requisitions.php");
    exit;
}
?>