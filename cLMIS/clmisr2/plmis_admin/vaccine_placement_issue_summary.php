<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Issue List";
//include('../template/header-top.php');
//include('../template/header-bottom.php');

$sCriteria=@$_SESSION['sCriteria'][0];

$userid = $_SESSION['userid'];
$wh_id = $_SESSION['wh_id'];

$tempstocksIssue = $objStockMaster->GetTempStockIssue($userid, $wh_id, 2);
if ($tempstocksIssue != FALSE) {
    $result = mysql_fetch_object($tempstocksIssue);
    $wh_name = $result->wh_name;
} else {
    $TranDate = date("d/m/Y");
    $wh_name = '';
    $TranRef = '';
}
if($sCriteria){
	
	if(!empty($sCriteria['issued_to'])){
		$qry='SELECT
				wh_name
				FROM
				tbl_warehouse
				WHERE
				wh_id='.$sCriteria['issued_to'];
		$sts=$objclsColdchain->getByQuery('wh_name',$qry);
		$sCriteria['issued_to']=$sts;
	}
	if(!empty($sCriteria['product'])){
		$qry='SELECT
		        itm_name
		        FROM
		        itminfo_tab
				WHERE
				itm_id='.$sCriteria['product'];
		$sts=$objclsColdchain->getByQuery('itm_name',$qry);
		$sCriteria['product']=$sts;
	}
//
	//$sCriteria=implode(', ',$sCriteria);
	$r=array();
	array_walk($sCriteria, create_function('$b, $c', 'global $r; $r[]="$c: $b";'));
	$sCriteria=implode(', ', $r);

	
}

$orderBy = '';
$style = '';
$style1 = '';
if ($_GET['type'] == 'loc')
{
	$title = 'Location ';
	$orderBy = ' GROUP BY tbl_warehouse.wh_name, itminfo_tab.itm_name, tbl_stock_master.TranDate ORDER BY tbl_warehouse.wh_name, tbl_stock_master.TranDate ASC';
	$style = 'style="display:none;"';
}
else if ($_GET['type'] == 'prod')
{
	$title = 'Product ';
	$orderBy = ' GROUP BY itminfo_tab.itm_name, tbl_warehouse.wh_name, tbl_stock_master.TranDate ORDER BY itminfo_tab.itm_name, tbl_stock_master.TranDate ASC';
	$style1 = 'style="display:none;"';
}

list($select, $where) = explode('WHERE', $_SESSION['qry']);

$where = (!empty($where)) ? 'WHERE '.$where : '';

 $qry = "SELECT
			SUM(ABS(tbl_stock_detail.Qty)) AS Qty,
			itminfo_tab.itm_name,
			tbl_warehouse.wh_name,
			itminfo_tab.doses_per_unit,
			DATE_FORMAT(tbl_stock_master.TranDate, '%d/%m/%y') AS TranDate
		FROM
			tbl_stock_master
		INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
		INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
		INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		INNER JOIN tbl_itemunits ON itminfo_tab.fkUnitID = tbl_itemunits.pkUnitID
		" . $where . $orderBy;

 $qry = $_SESSION['qry']. " $orderBy";

//include('../'.$_SESSION['menu']); ?>

<!-- Content -->

<div id="content_print" style="margin-left:40px;">
	<style type="text/css" media="print">
    @media print
    {    
        #printButt
        {
            display: none !important;
        }
    }
    </style>
	<?php
		$rptName = $title.'wise Stock Issue Summary List';
    	include('report_header.php');
	?>
	<?php
    $product = '0';
    $location = '0';
    $i=0; 
    
    $qryRes = mysql_query($qry);
    $qryRes1 = mysql_query($qry);
    $num = mysql_num_rows($qryRes);
    
	while($val = mysql_fetch_object($qryRes1))
	{
		$productArr[$val->itm_name][] = $val;
		$locationArr[$val->wh_name][] = $val;
	}
	
	
	//$productArr = array_merge(array_unique($productArr, SORT_REGULAR));
	//$locationArr = array_merge(array_unique($locationArr, SORT_REGULAR));

	if($num > 0 && $_GET['type'] == 'prod')
	{
		//echo "<pre>";
		foreach($productArr as $key=>$data)
		{
		?>
			<b><?php echo $key;?></b>
			<table id="myTable" style="margin-bottom:20px;" cellpadding="3">
				<tr>
					<th width="10%">S.No.</th>
					<th width="17%">Issue Date</th>
					<th>Issue To</th>
					<th width="15%">Quantity</th>
					<th width="8%">Unit</th>
					<th width="12%">Cartons</th>
				</tr>
				<tbody>
			<?php
			$i=1;
			$totalQty = 0;
			$totalCartons = 0;
			foreach( $data as $val )
			{
				$totalQty += abs($val->Qty);
				$totalCartons += abs($val->Qty) / $val->qty_carton;
				
			?>
                <tr>
                    <td style="text-align:center;"><?php echo $i++;?></td>
                    <td style="text-align:center;"><?php echo date('d/m/y', strtotime($val->TranDate));?></td>
                    <td><?php echo $val->wh_name; ?></td>
                    <td style="text-align:right;"><?php echo number_format(abs($val->Qty));?>&nbsp;</td>
                    <td><?php echo $val->UnitType; ?></td>
                    <td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton);?>&nbsp;</td>
                </tr>
			<?php	
				}
				?>
				<tr>
					<td colspan="3" align="right"><b>Total</b></td>
					<td style="text-align:right;"><b><?php echo number_format($totalQty);?></b></td>
                    <td>&nbsp;</td>
					<td style="text-align:right;"><b><?php echo number_format($totalCartons);?></b></td>
				</tr>
			</tbody>
		</table>
		<?php
		}
	}
	if($num > 0 && $_GET['type'] == 'loc')
	{
		//echo "<pre>";
		foreach($locationArr as $key=>$data)
		{
		?>
			<b><?php echo $key;?></b>
			<table id="myTable" style="margin-bottom:20px;" cellpadding="3">
            	<thead>
                    <tr>
                        <th width="10%">S.No.</th>
                        <th width="17%">Issue Date</th>
                        <th>Product</th>
                        <th width="20%">Quantity</th>
                        <th width="6%">Unit</th>
                        <th width="15%">Cartons</th>
                    </tr>
                    
                </thead>
				<tbody>
			<?php
			$i=1;
			$totalQty = 0;
			$totalCartons = 0;
			foreach( $data as $val )
			{
				$totalQty += abs($val->Qty);
				$totalCartons += abs($val->Qty) / $val->qty_carton;
			?>
				
					<tr>
						<td style="text-align:center;"><?php echo $i++;?></td>
						<td style="text-align:center;"><?php echo date('d/m/y', strtotime($val->TranDate));?></td>
						<td><?php echo $val->itm_name; ?></td>
						<td style="text-align:right;"><?php echo number_format(abs($val->Qty));?>&nbsp;</td>
						<td><?php echo $val->UnitType; ?></td>
						<td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton);?>&nbsp;</td>
					</tr>
			<?php	
				}
				?>
				<tr>
					<td colspan="3" align="right"><b>Total</b></td>
					<td style="text-align:right;"><b><?php echo number_format($totalQty); ?></b></td>
                    <td>&nbsp;</td>
					<td style="text-align:right;"><b><?php echo number_format($totalCartons); ?></b></td>
				</tr>
			</tbody>
		</table>
		<?php
		}
	}

    ?>
    <div style="float:left; font-size:12px;">
        <?php echo !empty($sCriteria) ? '<b>Criteria: </b>'.$sCriteria : ''; ?><br />
        <b>Print Date:</b> <?php echo date('d/m/y').' <b>by</b> '.$_SESSION['user_name'];?>
    </div>
    <div style="float:right;" id="printButt">
        <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
    </div>
    
</div>

<?php
unset($_SESSION['stock_id']);
?>
<script src="<?php echo ASSETS;?>global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script language="javascript">
$(function(){
	//printCont();
})
function printCont()
{
	window.print();
}
</script>