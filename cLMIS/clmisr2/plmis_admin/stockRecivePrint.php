<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Recieve List";
$print = 1;
?>

<div id="content_print">
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
		$rptName = 'Stock Recieve From Supplier Voucher';
    	include('report_header.php');
	?>        
    	<div style="text-align:center;">
            <b style="float:left;">Receive Voucher: <?php echo $_REQUEST['rec_no']; ?></b>
            <b style="float:right;">Receiving Time: <?php echo $_REQUEST['rec_date'];?></b>
        </div>
        <div style="clear:both;">
            <b style="float:left;">Reference No.: <?php echo $_REQUEST['ref_no']; ?></b>
            <b style="float:right;">Source: <?php echo $_REQUEST['rec_from']; ?></b>
        </div>
        <table id="myTable" cellpadding="3">
            <tr>
                <th width="7%">S. No.</th>
                <th>Product</th>
                <th width="10%">Batch No.</th>
                <th width="12%">Quantity</th>
                <th width="8%">Unit</th>
                <th width="10%">Cartons</th>
                <th width="12%">Production Date</th>
                <th width="10%">Expiry Date</th>
            </tr>
            <tbody>
                <?php				
				$summaryArr = array();
				$product = '0';
                $i = 0;
				$totalQty = 0;
				$totalCartons = 0;
                $vacPlace = $_SESSION['stock_rec_supplier'];
                if (!empty($vacPlace)) {
                    foreach ($vacPlace as $val) {
						$totalQty += abs($val->Qty);
						$totalCartons += abs($val->Qty) / $val->qty_carton;
						
						if ($product != $val->itm_name)
						{
							$summaryArr[$val->itm_name]['Qty'][] = abs($val->Qty);
							$summaryArr[$val->itm_name]['qty_carton'][] = abs($val->Qty) / $val->qty_carton;
							$product = $val->itm_name;
						}
						else
						{
							$summaryArr[$val->itm_name]['Qty'][] = abs($val->Qty);
							$summaryArr[$val->itm_name]['qty_carton'][] = abs($val->Qty) / $val->qty_carton;
						}
                        $i++;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i; ?></td>
                            <td><?php echo $val->itm_name; ?></td>	
                            <td><?php echo $val->batch_no; ?></td>
                            <td style="text-align:right;"><?php echo number_format($val->Qty); ?></td>
                            <td style="text-align:right;"><?php echo $val->UnitType; ?></td>
                            <td style="text-align:right;"><?php echo number_format($val->Qty / $val->qty_carton); ?></td>
                            <td style="text-align:center;"><?php echo !empty($val->production_date) ? date("d/m/y", strtotime($val->production_date)) : ''; ?></td>
                            <td style="text-align:center;"> <?php echo date("d/m/y", strtotime($val->batch_expiry)); ?></td>
                        </tr>
                    <?php }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Total</th>
                    <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                    <th>&nbsp;</th>
                    <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                    <th colspan="2">&nbsp;</th>
                </tr>
            </tfoot>
        </table>
        
        <h5 style="margin-top:30px;" class="heading">Summary</h5>
        <table id="myTable" style="width:70%;" cellpadding="3">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Cartons</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach( $summaryArr as $product=>$qty)
            {
            ?>
                <tr>
                    <td><?php echo $product;?></td>
                    <td style="text-align:right;"><?php echo number_format(array_sum($summaryArr[$product]['Qty']));?></td>
                    <td style="text-align:right;"><?php echo number_format(array_sum($summaryArr[$product]['qty_carton']));?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        
        <?php include('report_footer_rcv.php');?>
        
        <div style="float:right;margin:20px;" id="printButt">
            <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
        </div>
    
</div>

<?php
unset($_SESSION['stock_id']);
?>
<script src="<?php echo ASSETS;?>global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script language="javascript">
$(function(){
	printCont();
})
function printCont()
{
	window.print();
}
</script>