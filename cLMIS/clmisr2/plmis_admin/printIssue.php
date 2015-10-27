<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Stock Issue Voucher";
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

$stocks = $objStockMaster->GetStocksIssueList($userid, $wh_id, 2, $stockId);
$receiveArr = array();
while ($row = mysql_fetch_object($stocks)) {
    $issue_no = $row->TranNo;
    $tran_ref = $row->TranRef;
    $issue_date = $row->TranDate;
    $wh_to_id = $row->wh_id;
    $issue_to = $row->wh_name;
    $issued_by = $row->issued_by;
    $receiveArr[] = $row;
}
// Get district Name
$getDist = "SELECT
			tbl_locations.LocName
		FROM
			tbl_warehouse
		INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
		WHERE
			tbl_warehouse.wh_id = $wh_to_id";
$rowDist = mysql_fetch_object(mysql_query($getDist));
?>

<div id="content_print">
	<div style="float:right; font-size:12px;">QR/015/01.08.2</div>
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
		$rptName = 'Stock Issue Voucher';
    	include('report_header.php');
	?>
        <div style="text-align:center;">
            <b style="float:right;">District: <?php echo $rowDist->LocName; ?></b><br />
            <b style="float:left;">Issue Voucher: <?php echo $issue_no; ?></b>
            <b style="float:right;">Date of Departure: <?php echo date("d/m/y", strtotime($issue_date)); ?></b>
        </div>
        <div style="clear:both;">
            <b style="float:left;">Reference No.: <?php echo $tran_ref; ?></b>
            <b style="float:right;">Issue To: <?php echo $issue_to;?></b><br />
            <b style="float:right;">Issue By: <?php echo $issued_by;?></b>
        </div>
        
        <table id="myTable" cellpadding="3">
            <tr>
                <th width="8%">S. No.</th>
                <th>Product</th>
                <th width="15%">Batch No.</th>
                <th width="15%">Expiry Date</th>
                <th width="15%" align="center">Quantity</th>
                <th width="10%" align="center">Unit</th>
                <th width="15%" align="center">Cartons</th>
            </tr>
            <tbody>
                <?php
                $i = 1;
				$totalQty = 0;
				$totalCartons = 0;
				$product = '';
                if (!empty($receiveArr)) {
                    foreach ($receiveArr as $val) {
						if ( $val->itm_name != $product && $i > 1 )
						{
						?>
                        <tr>
                            <th colspan="4" style="text-align:right;">Total</th>
                            <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                            <th>&nbsp;</th>
                            <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                        </tr>
                        <?php
							$totalQty = abs($val->Qty);
							$totalCartons = abs($val->Qty) / $val->qty_carton;
						}
						else
						{	
							$totalQty += abs($val->Qty);
							$totalCartons += abs($val->Qty) / $val->qty_carton;
						}
						$product = $val->itm_name;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i++; ?></td>
                            <td><?php echo $val->itm_name; ?></td>
                            <td><?php echo $val->batch_no; ?></td>
                            <td style="text-align:center;"> <?php echo date("d/m/y", strtotime($val->batch_expiry)); ?></td>
                            <td style="text-align:right;"><?php echo number_format(abs($val->Qty)); ?></td>
                            <td style="text-align:center;"><?php echo $val->UnitType; ?></td>
                            <td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <th colspan="4" style="text-align:right;">Total</th>
                    <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                    <th>&nbsp;</th>
                    <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                </tr>
            </tbody>
        </table>
        
        <?php include('report_footer_issue.php');?>
        
        <div style="width:100%; clear:both;">
            <table width="48%" cellpadding="5" style="float:left; border:2px solid #E5E5E5 !important; border-collapse:collapse; margin-top:10px;">
                <tr>
                    <td><b>Verified by</b> - Name: ________________________________________</td>
                </tr>
                <tr>
                    <td>Signature: ________________________________________</td>
                </tr>
            </table>
        </div>
        
        <div style="float:right; margin-top:20px;" id="printButt">
        	<input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
        </div>
    </div>
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