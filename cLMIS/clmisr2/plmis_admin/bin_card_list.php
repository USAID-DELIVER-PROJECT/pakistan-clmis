<?php
ob_start();
//session_start();
include("Includes/AllClasses.php");

$area = $_REQUEST['area'];
$row = $_REQUEST['row'];

$title = "Bin Card - Location (".$area." - ".$row;

$wh_id = $_SESSION['wh_id'];
    $mainSQL = "SELECT	* FROM	(SELECT
	stock_batch.batch_expiry AS expiry,
	placements.stock_batch_id AS batchID,
	stock_batch.batch_no AS batchNo,
	stock_batch.item_id AS itemID,
	itminfo_tab.itm_name AS ItemName,
	itminfo_tab.itm_type,
	itminfo_tab.qty_carton AS qty_per_pack,
	placements.stock_detail_id AS DetailID,
	placement_config.location_name AS LocationName,
	placement_config.pk_id AS LocationID,
	placements.pk_id AS PlacementID,
	placement_config.warehouse_id AS wh_id,
	abs(SUM((placements.quantity))) AS Qty
        FROM
                placements
        INNER JOIN placement_config ON placements.placement_location_id = placement_config.pk_id
        INNER JOIN stock_batch ON placements.stock_batch_id = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        WHERE
        placement_config.warehouse_id = " . $wh_id . " and placement_config.location_name like  '". $area .$row."%'".
        " GROUP BY batchNo,itemID order BY itemID) AS A WHERE	A.Qty > 0";

//    echo $mainSQL;
//    exit;
    //print $mainSQL;
    $Bincard = mysql_query($mainSQL) or die("mainSQL");

//include('../'.$_SESSION['menu']); ?>

<!-- Content -->

<div id="content_print">
	<div style="float:right; font-size:12px;">QR/013/01.08.12</div>
	
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
		$rptName = 'Bin Card - Location (Area - '.$area.' / Row # '.$row.')';
    	include('report_bin_card_header.php');
	?>
    <table id="myTable" cellpadding="3">
        <!-- Table heading -->
        <thead>
            <tr>
                <th width="6%">S. No.</th>
                <th>Product</th>
                <th width="10%">Batch No.</th>
                <th width="12%">Quantity</th>
                <th width="8%">Unit</th>
                <th width="10%">Cartons</th>
                <th width="12%">Expiry Date</th>
            </tr>
        </thead>
        <!-- // Table heading END -->
        
        <!-- Table body -->
        <tbody>				
        <?php
        $i=1;
		$totalQty = 0;
		$totalCartons = 0;
        while ($row = mysql_fetch_array($Bincard)) {
			$totalQty += $row['Qty'];
			$totalCartons += $row['Qty'] / $row['qty_per_pack'];
            ?>
                <tr>
                     <td style="text-align:center;"><?php echo $i;?></td>
                    <td>
                <?php echo $row["ItemName"] ?>
                    </td>
                    <td>
                        <?php echo $row["batchNo"] ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($row["Qty"]) ?>
                    </td>
                    <td>
                        <?php echo $row["itm_type"] ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($row["Qty"] / $row["qty_per_pack"]) ?>
                    </td>
                    <td style="text-align:center;">
                        <?php echo date("d/m/y", strtotime($row["expiry"]));  ?>
                    </td>
                </tr>
                        <?php
                        $i++;
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
    <div style="float:left; font-size:12px;">
        <br>
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