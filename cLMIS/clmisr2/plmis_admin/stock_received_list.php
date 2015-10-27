<?php
ob_start();
//session_start();
include("Includes/AllClasses.php");
$title = "Stock Receive List";
$print = 1;
$sCriteria = '';
if(!empty($_SESSION['sCriteria'])){
	$sCriteria=$_SESSION['sCriteria'][0];
if($sCriteria){
	
	if(!empty($sCriteria['warehouse']) && $sCriteria['warehouse']){
			$qry='SELECT
					wh_name
					FROM
					tbl_warehouse
					WHERE
					wh_id='.$sCriteria['warehouse'];
			$sts=$objclsColdchain->getByQuery('wh_name',$qry);
			$sCriteria['warehouse']=$sts;
		}
		if(isset($sCriteria['product'])){
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
}

//include('../'.$_SESSION['menu']); ?>

<!-- Content -->

<div id="content_print">
	<style type="text/css" media="print">
    .page
    {
     -webkit-transform: rotate(-90deg); -moz-transform:rotate(-90deg);
     filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }
	@media print
	{    
		#printButt
		{
			display: none !important;
		}
	}
</style>
	<?php
		$rptName = 'Stock Receive List';
    	include('report_header.php');
	?>
    <table id="myTable" cellpadding="3">
        <!-- Table heading -->
        <thead>
            <tr>
                <th width="6%">S. No.</th>
                <th width="10%">Receive Date</th>
                <th width="10%">Receive No.</th>
                <th>Product</th>
                <th width="13%">Receive From Warehouse / Supplier</th>
                <th width="10%">Ref.No.</th>
                <th width="10%">Batch No.</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Cartons</th>
                <th width="12%">Expiry Date</th>
            </tr>
        </thead>
        <!-- // Table heading END -->
        
        <!-- Table body -->
        <tbody>				
        <?php $i=1;
		$qry = $_SESSION['stock_rcv_list'];
		$qryRes = mysql_query($qry);
        //$m_res=$_SESSION['stock_rec'];
        if(mysql_num_rows(mysql_query($qry)) > 0){
        while ( $val = mysql_fetch_object($qryRes) )
		{
			$totalQty += abs($val->Qty);
			$totalCartons += abs($val->Qty) / $val->qty_carton;
		?>
            <tr>
                <td style="text-align:center;"><?php echo $i++;?></td>
                <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->TranDate)); ?>&nbsp;</td>
                <td><?php echo $val->TranNo; ?>&nbsp;</td>
                <td><?php echo $val->itm_name; ?>&nbsp;</td>
                <td><?php echo $val->wh_name; ?>&nbsp;</td>
                <td><?php echo $val->TranRef; ?>&nbsp;</td>
                <td><?php echo $val->batch_no; ?>&nbsp;</td>
                <td style="text-align:right;"><?php echo number_format($val->Qty); ?>&nbsp;</td>
                <td style="text-align:right;"><?php echo $val->itm_type; ?></td>
                <td style="text-align:right;"><?php echo number_format($val->Qty / $val->qty_carton); ?>&nbsp;</td>
                <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->batch_expiry)); ?>&nbsp;</td>
            </tr>
            <?php }
            }else{?>
                <td colspan="9">Nothing to Print</td>					
            <?php }
            ?>
            <!-- // Table row END -->
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:right;">Total</th>
                    <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                    <th>&nbsp;</th>
                    <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>
        </tbody>
    </table>
    <div style="float:left; font-size:12px;">
        <?php echo !empty($sCriteria) ? '<b>Criteria: </b>'.$sCriteria : ''; ?><br />
        <b>Print Date:</b> <?php echo date('d/m/y').' <b>by</b> '.$_SESSION['user_name'];?>
    </div>
    <div style="float:right; margin:20px;" id="printButt">
        <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
    </div>
    
</div>

<!-- // Content END -->
<?php include('../template/footer.php'); ?>
<script language="javascript">
$(function(){
	printCont();
})
function printCont()
{
	//$('#printButt').hide();
	window.print();
	//$('#printButt').show();
	/*setTimeout(function() {
		// Do something after 5 seconds
		$('#printButt').show();
	}, 5000);*/
}
</script>