<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$print = 1;
$title = "Stock Issue Voucher";
include('../template/header-top.php');
//include('../template/header-bottom.php');
//include('../'.$_SESSION['menu']);
// Get district Name
$wh_id = $_REQUEST['whTo'];
$getDist = "SELECT
			tbl_locations.LocName
		FROM
			tbl_warehouse
		INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
		WHERE
			tbl_warehouse.wh_id = $wh_id";
$rowDist = mysql_fetch_object(mysql_query($getDist));
?>

<!-- Content -->

<div id="content_print">
	<?php
		$rptName = ' Stock Issue Voucher';
    	include('report_header.php');
	?>
    <div style="text-align:center;">
        <b style="float:right;">District: <?php echo $rowDist->LocName; ?></b><br />
        <b style="float:left;">Issue Voucher: <?php echo $_REQUEST['req_no'];?></b>
        <b style="float:right;">Date of Departure: <?php echo date('d/m/y', strtotime($_SESSION['stockIssueArray'][0]->TranDate)); ?></b>
    </div>
    <div style="clear:both;">
        <b style="float:left;">Reference No.: <?php echo $_REQUEST['refrenceno']; ?></b>
        <b style="float:right;">Issue To: <?php echo $_REQUEST['recip'];?></b><br />
        <b style="float:right;">Issue By: <?php echo $_REQUEST['issuedBy'];?></b>
    </div>
    
    <table id="myTable">
        <thead>
            <tr>
                <th width="6%">S. No.</th>
                <th>Product</th>
                <th width="10%">Batch No.</th>
                <th width="15%">Quantity</th>
                <th width="8%">Unit</th>
                <th width="10%">Cartons</th>
                <th width="10%">Expiry Date</th>
            </tr>
        </thead>
		<tbody>
        <?php
        $summaryArr = array();
        $product = '';
        $product = '';
        $i=0;
		$totalQty = 0;
		$totalCartons = 0;
        $stlPlace=$_SESSION['stockIssueArray'];
        if(!empty($stlPlace))
        {
            foreach($stlPlace as $val)
            {				
				if ( $val->itm_name != $product && $i > 1 )
				{
				?>
				<tr>
					<th colspan="3" style="text-align:right;">Total</th>
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
                $i++;
        ?>
            <tr class="gradeX">
                <td style="text-align:center;"><?php echo $i;?></td>
                <td><?php echo $val->itm_name;?>&nbsp;</td>
                <td><?php echo $val->batch_no; ?>&nbsp;</td>
                <td style="text-align:right;"><?php echo number_format(abs($val->Qty));?>&nbsp;</td>
                <td style="text-align:right;"><?php echo $val->UnitType; ?></td>
                <td style="text-align:right;"><?php echo number_format(abs($val->Qty) / $val->qty_carton); ?></td>
                <td style="text-align:center;"><?php echo date("d/m/y", strtotime($val->batch_expiry)); ?>&nbsp;</td>
            </tr>
            <?php 
            }
        }
            //var_dump($summaryArr);
            ?>
            <!-- // Table row END -->
            <tr>
                <th colspan="3" style="text-align:right;">Total</th>
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
	printCont();
})
function printCont()
{
	window.print();
}
</script>