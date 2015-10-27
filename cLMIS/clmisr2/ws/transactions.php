<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

// Example Call for 1st time: http://localhost/clmis/ws/transactions.php?td=2014-02-19&tn=0006&tt=1&tr=000056&wf=1&wt=2&cb=99&co=2014-02-19&rr=remakrs&bn=b00001&be=2015-01-01&itm=12&qty=1000
// Example Call for when we have master Id: http://localhost/clmis/ws/transactions.php?bn=b00001&be=2015-01-01&itm=12&qty=1000&mId=12

$transactionDate = !empty($_REQUEST['td']) ? $_REQUEST['td'] : '';
$transactionNum = !empty($_REQUEST['tn']) ? $_REQUEST['tn'] : '';
$transactionTypeId = !empty($_REQUEST['tt']) ? $_REQUEST['tt'] : '';
$transactionRef = !empty($_REQUEST['tr']) ? $_REQUEST['tr'] : '';
$warehouseFrom = !empty($_REQUEST['wf']) ? $_REQUEST['wf'] : '';
$warehouseTo = !empty($_REQUEST['wt']) ? $_REQUEST['wt'] : '';
$createdBy = !empty($_REQUEST['cb']) ? $_REQUEST['cb'] : '';
$createdOn = !empty($_REQUEST['co']) ? $_REQUEST['co'] : '';
$receivedRemarks = !empty($_REQUEST['rr']) ? $_REQUEST['rr'] : '';
$batchNum = !empty($_REQUEST['bn']) ? $_REQUEST['bn'] : '';
$batchExpiry = !empty($_REQUEST['be']) ? $_REQUEST['be'] : '';
$itemId = !empty($_REQUEST['itm']) ? $_REQUEST['itm'] : '';
$quantity = !empty($_REQUEST['qty']) ? $_REQUEST['qty'] : '';
$masterId = !empty($_REQUEST['mId']) ? $_REQUEST['mId'] : '';


// Insert into stock Batch table (stock_batch)
if ( !empty($batchNum) )
{
	// check if batch already exists
	$qry = "SELECT
				COUNT(batch_id) AS num,
				batch_id
			FROM
				stock_batch
			WHERE
				stock_batch.batch_no = '".$batchNum."'";
	$batchExist = mysql_fetch_array(mysql_query($qry));
	
	if ($batchExist['num'] == 0)
	{
		$qry = "INSERT INTO stock_batch
				SET
					batch_no = '".$batchNum."',
					batch_expiry = '".$batchExpiry."',
					item_id = '".$itemId."' ";
		mysql_query($qry);
		$batchPKId = mysql_insert_id();
	}
	else
	{
		$batchPKId = $batchExist['batch_id'];
	}
}

// Insert into stock master table (stock_master)
if ( empty($masterId) )
{
	$qry = "INSERT INTO stock_master
			SET
				TranDate = '".$transactionDate."',
				TranNo = '".$transactionNum."',
				TranTypeID = '".$transactionTypeId."',
				TranRef = '".$transactionRef."',
				WHIDFrom = '".$warehouseFrom."',
				WHIDTo = '".$warehouseTo."',
				CreatedBy = '".$createdBy."',
				CreatedOn = '".$createdOn."',
				ReceivedRemarks = '".$receivedRemarks."' ";
	mysql_query($qry);
	$masterPKId = mysql_insert_id();	
}
else
{
	$masterPKId = $masterId;
}

// Insert into stock detail table (stock_detail)
if ( !empty($quantity) )
{
	$qry = "INSERT INTO stock_detail
			SET
				fkStockID = '".$masterPKId."',
				BatchID = '".$batchPKId."',
				Qty = '".$quantity."' ";
	mysql_query($qry);
	$detailPKId = mysql_insert_id();	
}
$arr = array('master_id'=>$masterPKId, 'detail_id'=>$detailPKId);

$arr1[] = $arr;
print(json_encode($arr1));
?>