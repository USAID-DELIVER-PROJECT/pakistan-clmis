<?php

/**
 * delete_placements
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

include("../includes/classes/AllClasses.php");

if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && isset($_REQUEST['batchId'])) {
    $id = $_REQUEST['id'];
    //Batch Id
    $batchId = $_REQUEST['batchId'];
    $deletePlacement = mysql_query("DELETE FROM placements WHERE placement_location_id=".$id." AND stock_batch_id = $batchId") or die("Error Delete Placement");
}
if (isset($_REQUEST['id']) && !empty($_REQUEST['id']) && !isset($_REQUEST['batchId'])) {
    $id = $_REQUEST['id'];
    $deletePlacement = mysql_query("DELETE FROM placement_config WHERE pk_id=".$id." ") or die("Error Delete Location");
	$_SESSION['success'] = 3;
	header('Location: placement_locations.php');
}
