<?php
include("Includes/AllClasses.php");

$title = "Approved Requisition";
$print = 1;
$whTo = mysql_real_escape_string($_REQUEST['wh_id']);
	$id = mysql_real_escape_string($_REQUEST['id']);
	if (isset($_REQUEST['id']) && isset($_REQUEST['wh_id']))
{
 $qry = "SELECT
				tbl_warehouse.dist_id,
				tbl_warehouse.prov_id,
				tbl_warehouse.stkid,
				tbl_locations.LocName,
				MainStk.stkname AS MainStk
			FROM
			tbl_warehouse
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
			WHERE
			tbl_warehouse.wh_id = ". $whTo;
	$qryRes = mysql_fetch_array(mysql_query($qry));
	$distId = $qryRes['dist_id'];
	$provId = $qryRes['prov_id'];
	$stkid = $qryRes['stkid'];
	$distName = $qryRes['LocName'];
	$mainStk = $qryRes['MainStk'];
	
	$qry = "SELECT
			clr_master.requisition_num,
			clr_master.date_from,
			clr_master.date_to,
			clr_details.replenishment,
			DATE_FORMAT(
					clr_master.requested_on,
					'%d/%m/%Y'
				) AS requested_on,
			itminfo_tab.itmrec_id,
			itminfo_tab.itm_name,
			clr_details.desired_stock,
			stock_batch.batch_no,
			itminfo_tab.itm_id,
			clr_details.approve_qty,
			clr_details.approve_date,
			clr_details.available_qty,
			clr_details.approval_status
			FROM
			clr_master
			INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
			INNER JOIN itminfo_tab ON clr_details.itm_id = itminfo_tab.itmrec_id
			LEFT JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
			WHERE
			clr_master.pk_id =". $id." AND
clr_details.desired_stock > 0 
			";
	$qryRes = mysql_query($qry);

	while ( $row = mysql_fetch_array($qryRes) )
	{
		$requisitionNum = $row['requisition_num'];
		$approveDate[$row['itm_id']]=$row['approve_date'];
		$dateFrom = date('M-Y', strtotime($row['date_from']));
		$dateTo = date('M-Y', strtotime($row['date_to']));
		$requestedOn = $row['requested_on'];
		$item_id[]=$row['itm_id'];
		$batchno[$item_id] = $row['batch_no'];
		$product[$row['itm_id']] = $row['itm_name'];
		$requestedOn = $row['requested_on'];
		$replenishment[$row['itmrec_id']] = $row['replenishment'];
		$desiredStock[$row['itm_id']] = $row['desired_stock'];
		$itemrec_id[$row['itm_id']]=$row['itmrec_id'];
		$approved[$row['itm_id']]=$row['approve_qty'];
		$available[$row['itm_id']]=$row['available_qty'];
		$approvalStatus[$row['itm_id']]=$row['approval_status'];
	}
	$duration = $dateFrom.' to '.$dateTo;
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
		$rptName = "Approved Requisition for $mainStk District $distName";
    	include('report_header.php');
	?>
    	<div style="text-align:center;font-family:Arial;font-size:12px">
            <b style="float:left;">Requisiton No.: <?php echo $requisitionNum; ?></b><br/>
            <b style="float:left;">Requisiton For.: <?php echo $dateFrom.'-'.$dateTo; ?></b>
            <b style="float:right;">Print Date: <?php echo date('d/m/Y');?></b>
        </div>
        
        <table id="myTable" cellpadding="3" style="font-family:Arial">
            <tr>
                <td rowspan="2" width="6%"><b>S. No.</b></td>
                <td rowspan="2"><b>Product</b></td>
                <td rowspan="2" align="center"><b>Requested Qty</b></td>
                <td rowspan="2" align="center"><b>Available Qty</b></td>
                <td rowspan="2" align="center"><b>Approved Qty</b></td>
                <td rowspan="2" width="10%"><b>Status</b></td>
                <td rowspan="2" width="10%"><b>Approved On</b></td>
            </tr>
            <tbody>
						<?php
                                $count = 1;
                                foreach( $product as $proId=>$proName )
                                {
                                ?>
                                    <tr>
                                        <td class="TAC"><?php echo $count++;?></td>
                                        <td>
											<?php echo $proName;?>
                                            <input type="hidden" name="product[<?php echo $proId?>]" id="product" value="<?php echo $proId?>" />
                                             <input type="hidden" name="itmrec[<?php echo $proId?>]" id="itmrec" value="<?php echo $itemrec_id[$proId]?>" />
                                        </td>
                                        <td class="TAR"><?php echo $desiredStock[$proId];?></td>
                                        <td class="TAR">
                                        <?php if($available[$proId]){echo $available[$proId];}else{echo "-";}?>
                                        </td>
                                        <td>
                                            <?php if($approved[$proId]){echo $approved[$proId];}else{echo "-";}?>
                                        </td>
                                        <td>
                                        <?php echo $approvalStatus[$proId]?>
                                        </td>
                                        <td><?php if(!empty($approveDate[$proId])){echo date('d/m/Y',strtotime($approveDate[$proId]));}else{echo "-";}?></td>
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