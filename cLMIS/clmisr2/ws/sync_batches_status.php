<?php

include_once("DBCon.php");          // Include Database Connection File
// Example Call for 1st time: http://localhost/clmis/ws/sync_transactions.php?td=2014-02-19&tn=0006&tt=1&tr=000056&wf=1&wt=2&cb=99&co=2014-02-19&rr=remakrs&bn=b00001&be=2015-01-01&itm=12&qty=1000
// Example Call for when we have master Id: http://localhost/clmis/ws/sync_transactions.php?bn=b00001&be=2015-01-01&itm=12&qty=1000&mId=12
$bids = !empty($_REQUEST['bids']) ? $_REQUEST['bids'] : ''; // Batch ids
$status = !empty($_REQUEST['bs']) ? $_REQUEST['bs'] : ''; // Batch status

$updateqry = "UPDATE
stock_batch
set stock_batch.`status`='".$status."'
where batch_id in (".$bids.")";

//echo $updateqry;
//exit;

mysql_query($updateqry)


//$arr = array('Transferid' => "", 'placementid' => $placementId);

//$arr1[] = $arr;
//print(json_encode($arr1));
?>