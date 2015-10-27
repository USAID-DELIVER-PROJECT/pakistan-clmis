<?php
/***********************************************************************************************************
Developed by: Muhammad Waqas Azeem
email: waqasazeemcs06@gmail.com
This is the file used for requisition
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
Login();
$wh_id=$_SESSION['wh_id'];
if (isset($_REQUEST['id']) && isset($_REQUEST['wh_id']))
{
    $whTo = mysql_real_escape_string($_REQUEST['wh_id']);
    $id = mysql_real_escape_string($_REQUEST['id']);
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
			clr_details.approval_status,
			clr_details.available_qty
			FROM
			clr_master
			INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
			INNER JOIN itminfo_tab ON clr_details.itm_id = itminfo_tab.itmrec_id
			LEFT JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
			WHERE
			clr_master.pk_id =". $id." AND
			clr_details.desired_stock > 0 AND
			(clr_details.approval_status = 'Approved' OR clr_details.approval_status = 'Issued') AND
			clr_details.approve_qty > 0";
    $qryRes = mysql_query($qry);
    $batchno='';
    while ( $row = mysql_fetch_array($qryRes) )
    {
        $requisitionNum = $row['requisition_num'];
        $dateFrom = date('M-Y', strtotime($row['date_from']));
        $dateTo = date('M-Y', strtotime($row['date_to']));
        $requestedOn = $row['requested_on'];
        $item_id[]=$row['itm_id'];
        $batchno[$item_id] = $row['batch_no'];
        $product[$row['itm_id']] = $row['itm_name'];
        $requestedOn = $row['requested_on'];
        $replenishment[$row['itm_id']] = $row['replenishment'];
        $desiredStock[$row['itm_id']] = $row['desired_stock'];
        $itemrec_id[$row['itm_id']]=$row['itmrec_id'];
        $approved[$row['itm_id']]=$row['approve_qty'];
        $available[$row['itm_id']]=$row['available_qty'];
        $status[$row['itm_id']]=$row['approval_status'];
        $batch[$row['itm_id']]=$row['batch_no'];
        $appStatus[]=$row['approval_status'];
    }
    $duration = $dateFrom.' to '.$dateTo;
}
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">

<!-- BEGIN HEADER -->
<div class="page-container">
<?php include "../../plmis_inc/common/_top.php";?>
<?php include "../../plmis_inc/common/top_im.php";?>

<style>
table#myTable tr th, table#myTable td{font-size:12px;}
</style>

<div class="page-content-wrapper">
    <div class="page-content">
    	<div class="row">
            <div class="col-md-12">
            
                <div class="widget">
                    <div class="widget-head">
                        <h3 class="heading">Stock Issuance Form [Requisition No.: <?php echo $_GET['rq'];?>, Requisition Period: <?php echo $dateFrom.' to '.$dateTo.', Store: '.$mainStk .' '.$distName;?>]</h3>
                    </div>
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-md-12">
								<form name="frm" id="frm" action="<?php echo SITE_URL?>plmis_admin/clr6_new_issue_action.php" method="post" onSubmit="return formValidation()">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <?php  if(mysql_num_rows($qryRes)>0){?>
                                        <thead>
                                        <tr>
                                            <th width="80" style="text-align:center;">S. No.</th>
                                            <th width="180">Item</th>
                                            <th width="110">Requested Qty</th>
                                            <th>
                                                <table class="table table-condensed" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th width="150">Batch No</th>
                                                            <th width="80">Expiry</th>
                                                            <th>Available Qty</th>
                                                            <th>Issue Qty</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </th>
                                            <th width="110">Approved Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 1;
        
                                        foreach( $product as $proId=>$proName )
                                        {
                                            $strSql = "SELECT
                                                            stock_batch.batch_no,
                                                            stock_batch.batch_id,
                                                            stock_batch.batch_expiry,
                                                            stock_batch.item_id,
                                                            SUM(tbl_stock_detail.Qty) as Qty
                                                            FROM
                                                            stock_batch
                                                            INNER JOIN tbl_stock_detail ON stock_batch.batch_id = tbl_stock_detail.BatchID
                                                            WHERE
                                                            stock_batch.Qty <> 0 AND
                                                            stock_batch.`status` = 'Running' AND
                                                            stock_batch.item_id = $proId AND
                                                            stock_batch.wh_id = $wh_id AND
                                                            tbl_stock_detail.temp = 0
                                                            GROUP BY
                                                            stock_batch.batch_no ORDER BY
                                                            stock_batch.batch_expiry ASC,
                                                            tbl_stock_detail.Qty ASC";
        
                                            $rsSql = mysql_query($strSql) or die("Error: GetAllRunningBatches");
                                            ?>
                                        <tr>
                                            <td class="center"><?php echo $count++;?></td>
                                            <td>
                                                <?php echo $proName;?>
                                                <input type="hidden" name="product[<?php echo $proId?>]" id="product" value="<?php echo $proId?>" />
                                                <input type="hidden" name="itmrec[<?php echo $proId?>]" id="itmrec" value="<?php echo $itemrec_id[$proId]?>" />
                                            </td>
                                            <td class="right"><?php echo number_format($desiredStock[$proId]);?></td>
                                            <td>
                                                <table class="table-condensed" id="myTable">
                                                    <tbody>
                                                    <?php
                                                    $a=$approved[$proId];
                                                    $i=$replenishment[$proId];
                                                    $totalQty = 0;
                                                    while($resStockIssues=mysql_fetch_assoc($rsSql))
                                                    {
                                                        $avail = $resStockIssues['Qty'];
                                                        $totalQty += $avail;
                                                    ?>
                                                        <tr>
                                                            <td width="140"><?php echo $resStockIssues['batch_no'];?></td>
                                                            <td width="80"><?php echo date('d/m/Y',strtotime($resStockIssues['batch_expiry']));?></td>
                                                            <td><input class="form-control input-small input-sm" type="text" value="<?php echo number_format($resStockIssues['Qty'])?>" disabled style="text-align:right;"/></td>
                                                            <td>
                                                                <?php 
                                                                if($status[$proId] == 'Approved'){
                                                                ?>
                                                                <input autocomplete="off" max="<?php echo $totalQty;?>" class="qty form-control input-small input-sm" style="text-align:right" type="text" name="qty_issued[<?php echo $proId."|".$resStockIssues['batch_id']?>]" id="qty_issued[<?php echo $proId."|".$resStockIssues['batch_id']?>]" value="<?php
                                                                    if($i<$a && $avail<=$a-$i)
                                                                    {
                                                                        echo $a-$i;
                                                                        $i=$i+$a;
                                                                    }
                                                                    ?>"/>
                                                                <?php }echo $resStockIssues['qty'];?>
                                                            </td>
                                                        </tr>
                                                        
                                                    <?php
                                                    }?>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <input class="form-control input-small input-sm" type="text" name="approved[<?php echo $proId?>]" id="approved[<?php echo $proId?>]" value="<?php echo number_format($approved[$proId]);?>" style="text-align:right;" readonly />
                                            </td>
                                        </tr>
                                            <?php
                                        }
                                        if(in_array('Approved', $appStatus)){
                                            ?>
                                        <tr>
                                            <td colspan="5" style="text-align:right; border:none; padding-top:10px;">
                                                <input type="submit" id="submit" name="submit"  value="Issue" class="btn btn-primary" />
                                                <button type="button" onClick="javascript: history.go(-1)" class="btn btn-warning"> Cancel </button>                
                                            </td>
                                        </tr>
                                            <?php }}else{?>
                                    <tr>
                                        <td colspan="7" style="text-align:Center;font-size:14px; border:none; padding-top:10px;">
                                            No Approved Items to Issue.
                                        </td>
                                    </tr><?php }?>
                                    </tbody>
                                    </table>
                                    <input type="hidden" name="warehouse" id="warehouse" value="<?php echo $_REQUEST['wh_id']?>"/>
                                    <input type="hidden" name="issue_date" id="issue_date" value="<?php echo date("d/m/Y")?>"/>
                                    <input type="hidden" name="issue_ref" id="issue_ref" value="<?php echo $requisitionNum?>"/>
                                    <input type="hidden" name="trans_no" id="trans_no" value="-1"/>
                                    <input type="hidden" name="stock_id" id="stock_id" value="0"/>
                                    <input type="hidden" name="clr6_id" id="clr6_id" value="<?php echo $_REQUEST['id']?>"/>
                                    <input type="hidden" name="rq_no" value="<?php echo $requisitionNum?>"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/clr6issue.js"></script>
<script>
$(function(){
	$('.qty').priceFormat({
		prefix: '',
		thousandsSeparator: ',',
		suffix: '',
		centsLimit: 0,
		limit: 10,
		clearOnEmpty:true
	});
})

function formValidation()
{
	var q = 0;
	var inp = $('.qty');
	for (var i = 0; i < inp.length; i++) {
		if (inp[i].value != '') {
			q++;
			var qtyValue = inp[i].value;
			qtyValue = parseInt(qtyValue.replace(/\,/g,''));
			if (qtyValue == 0)
			{
				alert('Quantity can not be 0');
				inp[i].focus();
				return false;
			}
			else if (qtyValue > parseInt(inp[i].getAttribute('max'))) {
				alert('Quantity can not be greater than ' + inp[i].getAttribute('max'));
				inp[i].focus();
				return false;
			}
		}
	}

	if (q == 0) {
		alert('Please enter at least one quantity');
		return false;
	}
	
	$('#submit').attr('disabled', true);
	$('#submit').val('Submitting...');
}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>