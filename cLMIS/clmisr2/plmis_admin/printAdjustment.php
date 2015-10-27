<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Adjustment Voucher";
$print = 1;
//include('../'.$_SESSION['menu']);

$stockId = $_GET['id'];

$qry = "SELECT
				tbl_stock_master.WHIDFrom,
				tbl_stock_master.CreatedBy
			FROM
				tbl_stock_master
			WHERE
				tbl_stock_master.PkStockID = ".$_GET['id'];
$qryRes = mysql_fetch_array(mysql_query($qry));
$wh_id = $qryRes['WHIDFrom'];
$userid = $qryRes['CreatedBy'];

/*$wh_id = $_SESSION['wh_id'];
$userid = $_SESSION['userid'];*/


$stocks = $objStockMaster->StockAdjustmentSearchList($stockId);//($userid, $wh_id, 2, $stockId);
$receiveArr = array();
while ($row = mysql_fetch_object($stocks)) {
    $issue_no = $row->TranNo;
    $issue_date = $row->TranDate;
    $issue_to = $row->wh_name;
    $receiveArr[] = $row;
}
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
		$rptName = 'Stock Adjustment Voucher';
    	include('report_header.php');
	?>
    	<div style="text-align:center;">
            <b style="float:left;">Adjustment No.: <?php echo $issue_no; ?></b>
            <b style="float:right;">Date of Adjustment: <?php echo date("d/m/y", strtotime($issue_date)); ?></b>
        </div>
        <div style="clear:both;">
            <b>Adjustment To: <?php echo $issue_to;?></b>
        </div>
        <table id="myTable" cellpadding="3">
            <tr>
                <th width="8%">S. No.</th>
                <th>Product</th>
                <th width="15%">Batch No.</th>
                <th width="15%" style="text-align:right;">Quantity</th>
                <th width="10%" align="center">Unit</th>
                <th width="10%" style="text-align:right;">Cartons</th>
                <th width="12%">Expiry Date</th>
            </tr>
            <tbody>
                <?php
                $i = 1;
				$totalQty = 0;
				$totalCartons = 0;
                if (!empty($receiveArr)) {
                    foreach ($receiveArr as $val) {
						$totalQty += abs($val->Qty);
						$totalCartons += abs($val->Qty) / $val->qty_carton;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i++; ?></td>
                            <td><?php echo $val->itm_name; ?></td>
                            <td><?php echo $val->batch_no; ?></td>
                            <td style="text-align:right;"><?php echo number_format(abs($val->Qty)); ?></td>
                            <td style="text-align:center;"><?php echo $val->itm_type; ?></td>
                            <td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton); ?></td>
                            <td style="text-align:center;"> <?php echo (!empty($val->batch_expiry)) ? date("d/m/y", strtotime($val->batch_expiry)) : ''; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Total</th>
                    <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                    <th>&nbsp;</th>
                    <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>
        </table>
        
        <?php include('report_footer_issue.php');?>
        
        <div style="float:right; margin-top:20px;" id="printButt">
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
	window.print();
}
</script>