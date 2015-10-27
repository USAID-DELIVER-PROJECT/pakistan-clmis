<?php 
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");
$title = "Batch Management";
$print=1;
//include('../template/header-top.php');
//include('../template/header-bottom.php');
?>
<?php $print = true; //include('../template/header-bottom.php'); ?>
<?php 
if ( isset($_REQUEST['type']) && $_REQUEST['type'] == 1 )
{
	 $qry = "SELECT
			itminfo_tab.itm_name,
			itminfo_tab.qty_carton,
			SUM(stock_batch.Qty) AS Vials,
			tbl_itemunits.UnitType
		FROM
			stock_batch
		INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
		INNER JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
		WHERE
			stock_batch.`wh_id` = '".$_SESSION['wh_id']."'
		GROUP BY
			itminfo_tab.itm_id
		ORDER BY
			itminfo_tab.frmindex";
			
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows($qryRes);
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
        $rptName = 'Batch Management Summary';
        include('report_header.php');
    ?>
	<table id="myTable">
		<thead>
			<tr>
				<th>S. No.</th>
				<th>Product</th>
				<th>Quantity</th>
				<th>Unit</th>
				<th>Cartons</th>
			</tr>
		</thead>
		<tbody>
		<?php if($num > 0){ $i=1;
		while ($row = mysql_fetch_object($qryRes)) {
			$totalQty += abs($row->Vials);
			$totalCartons += abs($row->Vials) / $row->qty_carton;
			?>
			<!-- Table row -->
			<tr>
				<td style="text-align:center;"><?php echo $i; ?></td>
				<td><?php echo $row->itm_name; ?></td>
				<td style="text-align:right;"><?php echo number_format($row->Vials);?></td>
				<td style="text-align:right;"><?php echo $row->UnitType;?></td>
				<td style="text-align:right;"><?php echo number_format($row->Vials / $row->qty_carton);?></td>
			</tr>
			<?php $i++; } } ?>
			<!-- // Table row END -->
		</tbody>
        <tfoot>
            <tr>
                <th colspan="2" style="text-align:right;">Total</th>
                <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                <th>&nbsp;</th>
                <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
            </tr>
        </tfoot>
	</table>
	<div style="float:right;" id="printButt">
		<input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
	</div>
    
</div>

<?php
}
else
{

$data = $_SESSION['batch_management'];
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
		$rptName = 'Batch Management';
    	include('report_header.php');
	?>
    <div>
    	<table id="myTable">
        	<thead>
                <tr>
                    <th>S. No.</th>
                    <th>Product</th>
                    <th>Batch No.</th>
                    <th>Expiry Date</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Cartons</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
			<?php if(!empty($data) > 0){ $i=1;
            foreach($data as $row) {
				$totalQty += abs($row->BatchQty);
				$totalCartons += abs($row->BatchQty) / $row->qty_carton;
                ?>
                <!-- Table row -->
                <tr class="gradeX">
                    <td style="text-align:center;"><?php echo $i; ?></td>
                    <td style="padding-left:5px;"><?php echo $row->itm_name; ?></td>
                    <td><?php echo $row->batch_no; ?></td>
                    <td style="text-align:center;"><?php echo date("d/m/y", strtotime($row->batch_expiry));?></td>
                    <td style="text-align:right;"><?php echo number_format($row->BatchQty);?></td>
                    <td style="text-align:right;"><?php echo $row->UnitType;?></td>
                    <td style="text-align:right;"><?php echo number_format($row->BatchQty / $row->qty_carton);?></td>
                    <td id="batch<?php echo $row->batch_id; ?>-status" style="padding-left:5px;"><?php echo $row->status; ?></td>
                </tr>
                <?php $i++; } } ?>
                <!-- // Table row END -->
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align:right;">Total</th>
                    <th style="text-align:right;"><?php echo number_format($totalQty);?></th>
                    <th>&nbsp;</th>
                    <th style="text-align:right;"><?php echo number_format($totalCartons);?></th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>
        </table>
        <div style="float:right;" id="printButt">
            <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printCont();" />
        </div>
    </div>
    
</div>
<?php
}
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