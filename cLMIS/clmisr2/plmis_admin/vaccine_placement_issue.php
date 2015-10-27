<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Issue List";

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
if ($_GET['grpBy'] == 'loc')
{
	$title = 'Location wise ';
	$orderBy = 'ORDER BY tbl_warehouse.wh_name, tbl_stock_master.TranDate ASC';
}
else if ($_GET['grpBy'] == 'prod')
{
	$title = 'Product wise ';
	$orderBy = 'ORDER BY itminfo_tab.itm_name, tbl_stock_master.TranDate ASC';
}
else
{
	$title = '';
}
$qry = $_SESSION['qry']. " $orderBy";

?>

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
		$rptName = $title. 'Stock Issue List';
    	include('report_header.php');
	?>
	<?php
    $product = '0';
    $location = '0';
	$totalVials = 0;
	$totalDoses = 0;
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
	
	if ( $_GET['grpBy'] != 'none' )
	{
		if($num > 0 && $_GET['grpBy'] == 'prod')
		{
			//echo "<pre>";
			foreach($productArr as $key=>$data)
			{
			?>
            	<b><?php echo $key;?></b>
				<table id="myTable" style="margin-bottom:20px;">
                    <thead>
                        <tr>
                            <th width="6%">S.No.</th>
                            <th width="12%">Issue Date</th>
                            <th>Issue To</th>
                            <th width="12%">Batch No.</th>
                            <th width="12%">Expiry Date</th>
							<th width="12%">Quantity</th>
                            <th width="8%">Unit</th>
							<th width="8%">Cartons</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                $i=0;
				$totalQty = 0;
				$totalCartons = 0;
                foreach( $data as $val )
                {
                    $i++;
					$totalQty += abs($val->Qty);
					$totalCartons += abs($val->Qty) / $val->qty_carton;
                ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $i;?></td>
                        <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->TranDate));?></td>
                        <td><?php echo $val->wh_name; ?></td>
                        <td><?php echo $val->batch_no; ?></td>
                        <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->batch_expiry)); ?></td>
                        <td style="text-align:right;"><?php echo number_format(abs($val->Qty));?></td>
                        <td style="text-align:center;"><?php echo $val->UnitType;?></td>
						<td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton);?>&nbsp;</td>
                    </tr>
                <?php	
                    }
                    ?>
                    <tr>
                        <td colspan="5" align="right"><b>Total</b></td>
                        <td align="right"><b><?php echo number_format($totalQty);?></b></td>
                        <td>&nbsp;</td>
						<td style="text-align:right;"><b><?php echo number_format($totalCartons); ?></b></td>
                    </tr>
                </tbody>
            </table>
			<?php
			}
		}
		if($num > 0 && $_GET['grpBy'] == 'loc')
		{
			//echo "<pre>";
			foreach($locationArr as $key=>$data)
			{
			?>
            	<b><?php echo $key;?></b>
				<table id="myTable" style="margin-bottom:20px;">
                	<thead>    
                        <tr>
                            <th width="6%">S.No.</th>
                            <th width="12%">Issue Date</th>
                            <th>Product</th>
                            <th width="15%">Batch No.</th>
                            <th width="12%">Expiry Date</th>
                            <th width="12%">Quantity</th>
                            <th width="8%">Unit</th>
							<th width="8%">Cartons</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                $i=1;
				$totalDoses = 0;
				$totalCartons = 0;
                foreach( $data as $val )
                {
					$totalQty += abs($val->Qty);
					$totalCartons += abs($val->Qty) / $val->qty_carton;
                ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $i;?></td>
                        <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->TranDate));?></td>
                        <td><?php echo $val->itm_name; ?></td>
                        <td><?php echo $val->batch_no; ?></td>
                        <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->batch_expiry)); ?></td>
                        <td style="text-align:right;"><?php echo number_format(abs($val->Qty));?></td>
                        <td style="text-align:center;"><?php echo $val->UnitType;?></td>
                        <td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton);?>&nbsp;</td>
                    </tr>
                <?php	
                    }
                    ?>
                    <tr>
                        <td colspan="5" align="right"><b>Total</b></td>
                        <td align="right"><b><?php echo number_format($totalQty);?></b></td>
                        <td>&nbsp;</td>
						<td style="text-align:right;"><b><?php echo number_format($totalCartons); ?></b></td>
                    </tr>
                </tbody>
            </table>
			<?php
			}
		}
	}
	else
	{?>
        <table id="myTable" style="margin-bottom:20px;">
        	<thead>
                <tr>
                    <th width="6%">S.No.</th>
                    <th width="10%">Issue Date</th>
                    <th width="15%">Product</th>
                    <th>Issue To</th>
                    <th width="10%">Batch No.</th>
                    <th width="10%">Expiry Date</th>
                    <th width="10%">Quantity</th>
                    <th width="8%">Unit</th>
					<th width="8%">Cartons</th>
                </tr>
            </thead>
            <tbody>
		<?php
		$i=0;
		$totalVials = 0;
		$totalCartons = 0;
		while($val = mysql_fetch_object($qryRes))
		{
			$i++;
			$totalVials += abs($val->Qty);
			$totalCartons += abs($val->Qty) / $val->qty_carton;
		?>
    		
				<tr>
					<td style="text-align:center;"><?php echo $i;?></td>
					<td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->TranDate));?></td>
					<td><?php echo $val->itm_name; ?></td>
					<td><?php echo $val->wh_name; ?></td>
					<td><?php echo $val->batch_no; ?></td>
					<td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->batch_expiry)); ?></td>
					<td style="text-align:right;"><?php echo number_format(abs($val->Qty));?></td>
					<td style="text-align:center;"><?php echo $val->UnitType;?></td>
					<td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton);?></td>					
				</tr>
		<?php	
            }
			?>
			<tr>
				<td colspan="6" align="right"><b>Total</b></td>
				<td align="right"><b><?php echo number_format($totalVials);?></b></td>
				<td>&nbsp;</td>
				<td style="text-align:right;"><b><?php echo number_format($totalCartons); ?></b></td>
			</tr>
        </tbody>
    </table>
	<?php
    }
    ?>
    <div style="float:left; font-size:12px;">
        <?php echo !empty($sCriteria) ? '<b>Criteria: </b>'.$sCriteria : ''; ?><br />
        <b>Print Date:</b> <?php echo date('d/m/y'). ' <b>by</b> '. $_SESSION['user_name'];?>
    </div>
    <div style="float:right;" id="printButt">
        <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
    </div>
    
</div>
    
<!-- // Content END -->
<?php //include('../template/footer.php'); ?>
<script src="<?php echo SITE_DOMAIN; ?>plmis_js/dataentry/newcoldchain.js"></script> 
<script src="<?php echo SITE_DOMAIN; ?>plmis_js/dataentry/levelcombos_all_levels.js"></script>
<?php
unset($_SESSION['stock_id']);
?>
<script language="javascript">
$(function(){
	printCont();
})
function printCont()
{
	window.print();
}
</script>