<?php 
/***********************************************************************************************************
Developed by: Muhammad Waqas Azeem
email: waqasazeemcs06@gmail.com
This is the file used for requisition
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
$wh_id=$_SESSION['wh_id'];
Login();
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
			clr_details.approval_status,
			itminfo_tab.itm_type
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
		$product[$row['itm_id']] = $row['itm_name'];
		$requestedOn = $row['requested_on'];
		$replenishment[$row['itmrec_id']] = $row['replenishment'];
		$desiredStock[$row['itm_id']] = $row['desired_stock'];
		$itemrec_id[$row['itm_id']]=$row['itmrec_id'];
		$approved[$row['itm_id']]=$row['approve_qty'];
		$available[$row['itm_id']]=$row['available_qty'];
		$approvalStatus[$row['itm_id']]=$row['approval_status'];
		$units[$row['itm_id']]=$row['itm_type'];
	}
	$duration = $dateFrom.' to '.$dateTo;
}
?>
<?php include "../../plmis_inc/common/_header.php";?>
<style>
.btn-link {
	color: #fff !important;
	text-shadow: none;
}
</style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" >
<!-- BEGIN HEADER -->
<div class="page-container">
<?php
include "../../plmis_inc/common/_top.php";
include "../../plmis_inc/common/top_im.php";
?>
<div class="page-content-wrapper">
	<div class="page-content"> 
    	<div class="row">
        	<div class="col-md-12">
                <!-- Widget -->
                <div class="widget">
                    <div class="widget-head">
                        <h3 class="heading">CLR-7</h3>
                    </div>
                    <div class="widget-body">
                        
                        <div id="printing" style="clear:both;margin-top:20px;">
                <div style="margin-left:0px !important; width:100% !important;">
                    <style>
                        body{margin:0px !important;font-family:Arial,Helvetica,sans-serif; }
                        table#myTable{margin-top:20px;border-collapse: collapse;border-spacing: 0;}
                        table#myTable tr td, table#myTable tr th{font-size:11px;padding-left:5px; text-align:left; border:1px solid #999;}
                        table#myTable tr td.TAR{text-align:right; padding:5px;width:50px !important;}
                        .sb1NormalFont {
                            color: #444444;
                            font-family: Verdana,Arial,Helvetica,sans-serif;
                            font-size: 11px;
                            font-weight: bold;
                            text-decoration: none;
                        }
                        p{margin-bottom:5px; font-size:11px !important; line-height:1 !important; padding:0 !important;}
                        table#headerTable tr td{ font-size:11px;}
    
                        /* Print styles */
                        @media only print
                        {
                            table#myTable tr td, table#myTable tr th{font-size:8px;padding-left:2 !important; text-align:left; border:1px solid #999;}
                            #doNotPrint{display: none !important;}
                        }
                    </style>
                    <p style="color: #000000; font-size: 20px;text-align:center"><b>Government of Pakistan<br/>
    Planning and Development Division<br/>
    Directorate of Central Warehouse &amp; Supplies<br/>
    F-508, S.I.T.E Karachi
    <hr style="margin:3px 10px;" />
     <p style="text-align:center;margin-left:35px;"><u><b>Contraceptive Issue and Receive Voucher(IRV)</b></u></b><span style="float:right; font-weight:normal;">CLR-7</span></p>
                    <p style="text-align:center;margin-left:15px;">(<?php echo "For $mainStk District $distName";?>)</p>
                    <table width="200" id="headerTable" align="left">
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 80px;">Requisition No: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $requisitionNum;?></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 83px;">Dated: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo date('d/m/Y');?></span>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <div style="clear:both;"></div>
                    <table width="700" id="headerTable" align="Left">
                        <tr>
                            <td align="left">
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 105px;">Name of Consignee: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $mainStk; ?></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 80px;">Designation/Address: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;"><?php echo $distName; ?></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 135px;">Requisition for the Month: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;">As per Distribution of USAID Deliver Project and Approved by P &amp; D Division<?php //echo $dateFrom.'-'.$dateTo; ?></span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 95px;">Mode of Dispatch: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;">Handover to UPS Authorized Corrier Agent of USAID Deliver Project for Destination Delivery</span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 105px;">Dispatch Document: </span>
                                    <span style="display: table-cell; border-bottom: 1px solid black;">__________________________</span>
                                </p>
                            </td>
                        </tr>
                    </table>
                    <div style="clear:both;"></div>
                    <table id="myTable" cellpadding="3" style="font-family:Arial">
                        <tr>
                            <td rowspan="2"  width="6%"><b>S. No.</b></td>
                            <td rowspan="2"><b>Product</b></td>
                            <td rowspan="2" align="center"><b>Date of Expiry<br/>&amp; Batch No.</b></td>
                            <td rowspan="2" align="center"><b>Unit</b></td>
                            <td colspan="3" width="10%"><b>Details</b>
                            <td colspan="2" width="10%"><b>Variation if any in</b>
                            <td colspan="2"><b>Remarks</b></td>
                        </tr>
                        <tr>
                            <td  align="center"><b>Requisitoned</b></td>
                            <td  align="center"><b>Dispatched</b></td>
                            <td  width="10%"><b>Received by the Consignee</b></td>
                            <td><b>Req &amp; Desp col 3-4</b></td>
                            <td><b>Desp &amp; col 4-5</b></td>
                            <td><b>Packing</b></td>
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
                                                    <td><?php 
                                                    $getBatches=mysql_query("SELECT
                                                            stock_batch.batch_no,
                                                            stock_batch.batch_id,
                                                            stock_batch.batch_expiry,
                                                            stock_batch.item_id
                                                        FROM
                                                        stock_batch
                                                        INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
                                                        INNER JOIN tbl_stock_master ON tbl_stock_detail.fkStockID = tbl_stock_master.PkStockID
                                                        INNER JOIN clr_details ON clr_details.stock_master_id = tbl_stock_master.PkStockID
                                                        WHERE
                                                            stock_batch.Qty <> 0
                                                        AND stock_batch.`status` = 'Running'
                                                        AND stock_batch.item_id =$proId
                                                        AND stock_batch.wh_id = $wh_id
                                                        AND tbl_stock_detail.temp = 0
                                                        GROUP BY
                                                            stock_batch.batch_no
                                                        ORDER BY
                                                            stock_batch.batch_expiry ASC,
                                                            tbl_stock_detail.Qty ASC") or die("Err GetCLRDetailBatches");
                                                    while($rowBatch=mysql_fetch_assoc($getBatches))
                                                    {?>
                                                    <div><b>Batch No:</b> <?php echo $rowBatch['batch_no'];?></div>
                                                    <div><b>Batch Expiry:<b/> <?php echo date('d/m/Y',strtotime($rowBatch['batch_expiry']));?></div>
                                                    <br/>
                                                    <?php }
                                                    ?></td>
                                                    <td><?php echo $units[$proId]?></td>
                                                    <td class="TAR" style="text-align:right"><?php echo number_format($desiredStock[$proId]);?></td>
                                                    <td style="text-align:right">
                                                        <?php if($available[$proId]){echo number_format($approved[$proId]);}?>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            <?php }
                                            ?>
                                            <tr id="doNotPrint">
                                                <td colspan="10" style="text-align:right; border:none; padding-top:15px;">
                                                    <input type="button" onClick="printContents()" value="Print" class="btn btn-warning input-sm" />
                                                </td>
                                            </tr>
                        </tbody>
            
                    </table>
                            <table width="700" id="headerTable" align="Left">
                        <tr>
                            <td align="left">
                                <p style="width: 100%; display: table;">
                                    <span style="display: table-cell; width: 105px;">IRV Voucher CLR-7 checked by Store Supervisor (CW &amp; S) </span>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="width: 100%;">
                                    <span style="display: table-cell;;">Note Below: <br/></span>
                                    <ol style="padding:0 0 0 15px !important;">
                                    <li style="list-style:lower-roman!important;">Please submitt CLR-6 on 3 month average sale and also indicate last month sale alongwith original challan of sale proceeds.</li>
                                    <li style="list-style:lower-roman!important;">Please attach this CLR-7 duly acknowledged with next CLR-6 failing which supply could be delayed/withheld.</li>
                                    <li style="list-style:lower-roman!important;">Date of receipt of consignment and page No. of the CLR-5 (FOR EACH CC) must be mentioned on the acknowledgement.</li>
                                    <li style="list-style:lower-roman!important;">Mejestron injections be placed as instruction given on it&#39;s box.</li>
                                </ol></p>
                                <p style="width: 100%; display: table;">
                                    
                                </p>
                            </td>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr>
                        <td colspan="2" style="text-align:center">Issuer</td>
                        <td colspan="2" style="text-align:center">Receiver</td></tr>
                        <tr>
                            <td style="text-align:left;" width="10%" class="sb1NormalFont">Signature:</td>
                            <td width="40%">__________________________</td>
                            <td style="text-align:left;" width="10%" class="sb1NormalFont">Signature:</td>
                            <td width="40%">__________________________</td>
                       </tr>
                       <tr>
                            <td width="30%" style="text-align:left;" class="sb1NormalFont">Name:</td>
                            <td width="20%">__________________________</td>
                            <td width="30%" style="text-align:left;" class="sb1NormalFont">Name:</td>
                            <td width="20%">__________________________</td>
                        </tr>
                        <tr>
                            <td width="30%" style="text-align:left;" class="sb1NormalFont">Title:</td>
                            <td width="20%">__________________________</td>
                            <td width="30%" style="text-align:left;" class="sb1NormalFont">Title:</td>
                            <td width="20%">__________________________</td>
                       </tr>
                       <tr><td colspan="4">&nbsp;</td></tr>
                       <tr><td colspan="4" style="text-align: right">Supply Received On:__________________</td></tr>
                    </table>
                </div>
            </div>
                    
                    </div>
                </div>                
        	</div>
        </div>
    </div>
</div>
</div>
<?php include "../../plmis_inc/common/footer.php";?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockplacement.js"></script>
<script>
function printContents(){
	var dispSetting="toolbar=yes,location=no,directories=yes,menubar=yes,scrollbars=yes, left=100, top=25";
	var printingContents = document.getElementById("printing").innerHTML;
	
	var docprint=window.open("","",printing);
	docprint.document.open();
	docprint.document.write('<html><head><title>CLR-7</title>');
	docprint.document.write('</head><body onLoad="self.print(); self.close()"><center>');
	docprint.document.write(printingContents);
	docprint.document.write('</center></body></html>');
	docprint.document.close();
	docprint.focus();
}
</script>
<?php include "../../plmis_inc/common/footer_template.php";?>
</body>
<!-- END BODY -->
</html>