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
<style>
#content_print {
    margin-left: 50px;
    width: 624px;
}
table#myTable {
    border: 1px solid #e5e5e5;
    font-size: 9pt;
    width: 100%;
}
table#myTable tr td {
    border: 1px solid #e5e5e5;
}
table#myTable tr th {
    border: 1px solid #e5e5e5;
}
</style>
<div style="float:right;">CLR-7</div>
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
		$rptName = "Issue Voucher for $mainStk District $distName";
    	//include('report_header.php');
	?>
	<div style="line-height: 1;margin:0 12%;text-align: center; width: 87%;">
        <span style="line-height:20px">Government of Pakistan</span><br/>
        <span style="line-height:15px">Planning and Development Division</span><br/>
        <span style="line-height:15px">Directorate of Central Warehouse &amp; Supplies</span><br/>
        <span style="line-height:15px">F-508, S.I.T.E Karachi</span>
        <hr style="margin:3px 10px;" />
        <p><b>Contraceptive Issue and Receive Voucher(IRV)</b>
        </p>
    </div>
    
</div>
<div style="clear:both"></div>
    	<div style="text-align:center;font-family: arial;font-size: 13px;line-height: 21px;">
            <b style="float:left;">Requisiton No.: <?php echo $requisitionNum; ?></b>
            <b style="float:right;">Dated: <?php echo date('d/m/Y');?></b>
        </div>
<div style="clear:both"></div>
        <div style="font-family: arial;font-size: 13px;line-height: 21px;">
            <b style="float:left;">Name of Consignee:</b> <?php echo $mainStk; ?><br/>
             <b style="float:left;">Designation/Address:</b> <?php echo $distName; ?><br/>
              <b style="float:left;">Requisition for the Month:</b> <?php echo $dateFrom.'-'.$dateTo; ?><br/>
               <b style="float:left;">Mode of Dispatch:__________________________</b><br/>
                <b style="float:left;">Dispatch Document:__________________________</b>
            <b style="float:right;">Dated: <?php echo date('d/m/Y');?></b>
        </div>
        <table id="myTable" cellpadding="3" style="font-family:Arial">
            <tr>
                <td rowspan="2"  width="6%"><b>S. No.</b></td>
                <td rowspan="2"><b>Product</b></td>
                <td rowspan="2" align="center"><b>Date of Expiry<br/>&amp; Batch No.</b></td>
                <td rowspan="2" align="center"><b>Unit</b></td>
                <td colspan="3" width="10%"><b>Details</b>
                
                <td colspan="2" width="10%"><b>Variation if any in</b>
                <td colspan="2"><b>Remarks</b></td>
                
                
            </tr><tr>
            	<td rowspan="2" align="center"><b>Requisitoned</b></td>
                <td rowspan="2" align="center"><b>Dispatched</b></td>
                <td rowspan="2" width="10%"><b>Received by the Consignee</b></td>
            	<td rowspan="2"><b>Req &amp; Desp col 3-4</b></td>
                <td rowspan="2"><b>Desp &amp; col 4-5</b></td>
                <td rowspan="2"><b>Packing</b></td>
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
                                        <td>Unit</td>
                                        <td>Batch No.</td>
                                        <td class="TAR"><?php echo $desiredStock[$proId];?></td>
                                        <td>
                                            <?php if($available[$proId]){echo $approved[$proId];}else{echo "-";}?>
                                        </td>
                                        <td>
                                        <?php echo $approvalStatus[$proId]?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
            </tbody>

        </table>
        <table style="width:50%;float:left;border:1px solid #eee;font-family:arial;font-size:12px">
                            <tr><td colspan="4" align="center">Issuer</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td width="30%" style="text-align:left;" class="sb1NormalFont">Signature:</td>
                                <td width="20%" style="text-align:left;">_________________________________________</td>
                            </tr>
                            <tr><td style="text-align:left;" width="10%" class="sb1NormalFont">Name:</td>
                                <td width="40%" style="text-align:left;">_________________________________________</td>
                             </tr><tr><td width="30%" style="text-align:left;" class="sb1NormalFont">Title:</td>
                                <td width="20%" style="text-align:left;">_________________________________________</td>
                            </tr>
                            <tr ><td colspan="4">&nbsp;</td></tr>
                            </table>
                            <table style="width:50%;float:left;border:1px solid #eee;font-family:arial;font-size:12px">
                            <tr><td colspan="4" align="center">Receiver</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td width="30%" style="text-align:left;" class="sb1NormalFont">Signature:</td>
                                <td width="20%" style="text-align:left;">_________________________________________</td>
                            </tr>
                            <tr><td style="text-align:left;" width="10%" class="sb1NormalFont">Name:</td>
                                <td width="40%" style="text-align:left;">_________________________________________</td>
                             </tr><tr><td width="30%" style="text-align:left;" class="sb1NormalFont">Title:</td>
                                <td width="20%" style="text-align:left;">_________________________________________</td>
                            </tr>
                            <tr ><td colspan="4">&nbsp;</td></tr>
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