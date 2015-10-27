<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

$title = "Gate Pass Voucher";
$print = 1;
include('../template/header-top.php');
include('../template/header-bottom.php');
//include('../'.$_SESSION['menu']);

$stockId = $_GET['id'];

$qry = "SELECT
		tbl_stock_master.TranNo,
		tbl_stock_master.TranDate,
		gatepass_master.transaction_date,
		stock_batch.batch_no,
		gatepass_master.number,
		gatepass_detail.quantity,
		itminfo_tab.itm_name,
		CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
		stock_batch.batch_expiry
	FROM
		gatepass_detail
	INNER JOIN tbl_stock_detail ON gatepass_detail.stock_detail_id = tbl_stock_detail.PkDetailID
	INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
	INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
	INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
	INNER JOIN gatepass_master ON gatepass_detail.gatepass_master_id = gatepass_master.pk_id
	INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
	INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
	WHERE
		gatepass_detail.gatepass_master_id =".$_GET['id'];

$qryRes2= mysql_fetch_array(mysql_query($qry));
$qryRes = mysql_query($qry);
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
		$rptName = 'Gate Pass Voucher';
    	include('report_header.php');
	?>
    
    	<div style="text-align:center;">
            <b style="float:left;">Gate Pass No.: <?php echo $qryRes2['number']; ?></b>
            <b style="float:right;">Gate Pass Date: <?php echo date("d/m/y", strtotime($qryRes2['transaction_date'])); ?></b>
        </div>
        <div style="clear:both;">
            <b>Transaction Date: <?php echo date("d/m/y", strtotime($qryRes2['TranDate'])); ?></b>
        </div>
    <br>
        <table id="myTable">
            <tr>
                <th width="6%">S.No.</th>
                <th>Product</th>
                <th width="12%">Batch No.</th>
                <th width="40%">Issued To</th>
                <th width="15%" align="center">Quantity</th>
                <th width="12%">Expiry Date</th>
            </tr>
            <tbody>
                <?php
                $i = 1;
               
                while($row= mysql_fetch_array($qryRes)){
                    
                //if (!empty($qryRes)) {
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $i; ?></td>
                            <td><?php echo $row['itm_name']; ?></td>
                            <td><?php echo $row['batch_no']; ?></td>
                            <td><?php echo $row['wh_name']; ?></td>
                            <td style="text-align:right;"><?php echo number_format($row['quantity']); ?></td>
                            <td style="text-align:center;"> <?php echo date("d/m/y", strtotime($row['batch_expiry'])); ?></td>
                        </tr>
                        <?php
              $i++;  }
                ?>
            </tbody>

        </table>
        
        <?php //include('report_footer_issue.php');?>
        
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