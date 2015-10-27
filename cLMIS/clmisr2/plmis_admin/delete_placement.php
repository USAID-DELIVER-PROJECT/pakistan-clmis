<?php
include("Includes/AllClasses.php");

if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    $batchId = $_REQUEST['batchId'];

    $deletePlacement = mysql_query("DELETE FROM placements WHERE placement_location_id=".$id." AND stock_batch_id = $batchId") or die("Error Delete Placement");
}
