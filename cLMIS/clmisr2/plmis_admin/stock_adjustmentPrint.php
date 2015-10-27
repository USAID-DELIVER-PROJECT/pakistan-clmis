<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Adjustment";
$print = 1;
//include('../' . $_SESSION['menu']);
/*
if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
    if (isset($_REQUEST['adjustment_no']) && !empty($_REQUEST['adjustment_no'])) {
        $adjustment_no = $_REQUEST['adjustment_no'];
    }
    if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {
        $type = $_REQUEST['type'];
    }
    if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
        $product = $_REQUEST['product'];
    }
    if (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) {
        $date_from = $_REQUEST['date_from'];
    }
    if (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to'])) {
        $date_to = $_REQUEST['date_to'];
    }
}

$items = $objManageItem->GetAllProduct();
$types = $objTransType->getAdjusmentTypes();

$objStockMaster->WHIDFrom = $_SESSION['wh_id'];
$objStockMaster->TranNo = (!empty($adjustment_no)) ? $adjustment_no : '';
$objStockMaster->TranTypeID = (!empty($type)) ? $type : '';
$objStockMaster->WHIDTo = '-1';
$objStockMaster->item_id = (!empty($product)) ? $product : '';
$objStockMaster->fromDate = (!empty($date_from)) ? dateToDbFormat($date_from) : '';
$objStockMaster->toDate = (!empty($date_to)) ? dateToDbFormat($date_to) : '';
$adjustment_list = $objStockMaster->StockAdjustmentSearch();
*/
?>
<!-- Content -->
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
		$rptName = 'Stock Adjustment';
    	include('report_header.php');
	?>
    <table id="myTable" cellpadding="3">
        <!-- Table heading -->
        <thead>
            <tr>
                <th class="span2">Date</th>
                <th class="span2">Adjustment No.</th>
                <th>Ref. No.</th>
                <th>Product</th>
                <th>Batch No.</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Carton</th>
                <th class="span2">Adjustment Type</th>
                <th>Comments</th>
            </tr>
        </thead>
        <!-- // Table heading END -->
        
        <!-- Table body -->
        <tbody>				
            <!-- Table row -->
		<?php
        $i = 1;
        $adjustArray=$_SESSION['adjustArray'];
		$totalQty = 0;
		$totalCartons = 0;
        if ($adjustArray) :
            foreach($adjustArray as $row){
				$totalQty += abs($row->Qty);
				$totalCartons += abs($row->Qty) / $row->qty_carton;
			?>
                <tr class="gradeX">
                    <td style="text-align:center;"><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
                    <td><?php echo $row->TranNo; ?></td>
                    <td><?php echo (!empty($row->TranRef)) ? $row->TranRef : '&nbsp;' ; ?></td>
                    <td><?php echo $row->itm_name; ?></td>
                    <td><?php echo $row->batch_no; ?></td>
                    <td style="text-align:right;"><?php echo number_format(abs($row->Qty));?></td>
                    <td style="text-align:right;"><?php echo $row->itm_type;?></td>
                    <td style="text-align:right;"><?php echo number_format(abs($row->Qty) / $row->qty_carton);?></td>
                    <td><?php echo $row->trans_type; ?></td>
                    <td><?php echo (!empty($row->ReceivedRemarks) ? $row->ReceivedRemarks : '&nbsp;'); ?></td>
                </tr>
                <?php
                $i++;
            }
        endif;
        ?>
            <!-- // Table row END -->
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right;">Total</th>
                <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                <th>&nbsp;</th>
                <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                <th colspan="2">&nbsp;</th>
            </tr>
        </tfoot>
    </table>
    
	<?php include('report_footer_issue.php');?>
        
    <div style="clear:both;float:right; margin:20px;" id="printButt">
        <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
    </div>
    
</div>

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