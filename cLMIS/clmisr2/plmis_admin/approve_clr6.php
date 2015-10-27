<?php
include("../html/adminhtml.inc.php");
//include "../plmis_inc/common/top.php";
include "../plmis_inc/common/top_im.php";

include("Includes/AllClasses.php");
$title = "New Issue";
include('../' . $_SESSION['menu']);
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
				DATE_FORMAT(clr_master.requested_on,'%d/%m/%Y') AS requested_on,
				itminfo_tab.itmrec_id,
				itminfo_tab.itm_name,
				clr_details.desired_stock,
				stock_batch.batch_no,
				itminfo_tab.itm_id,
				clr_details.approve_qty,
				clr_details.approval_status,
				SUM(stock_batch.Qty) AS available_qty,
				clr_master.approval_status AS masterStatus
			FROM
				clr_master
			INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
			INNER JOIN itminfo_tab ON clr_details.itm_id = itminfo_tab.itmrec_id
			LEFT JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
			WHERE
				clr_master.pk_id = $id
			AND clr_details.desired_stock > 0
			GROUP BY
				itminfo_tab.itmrec_id";
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
        $replenishment[$row['itmrec_id']] = $row['replenishment'];
        $desiredStock[$row['itm_id']] = $row['desired_stock'];
        $itemrec_id[$row['itm_id']]=$row['itmrec_id'];
        $approved[$row['itm_id']]=$row['approve_qty'];
        $status[$row['itm_id']]=$row['approval_status'];
        $availableQty[$row['itm_id']]=$row['available_qty'];
		$masterStatus = $row['masterStatus'];
    }
    $duration = $dateFrom.' to '.$dateTo;
}
?>
<?php include "../plmis_inc/common/_header.php";?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" >
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php
	include "../plmis_inc/common/top_im.php";
	include "../plmis_inc/common/_top.php";
	?>
    <div class="page-content-wrapper">
        <div class="page-content"> 
            <div class="row">
                <div class="col-md-12">
                
                    <div class="widget">
                        <div class="widget-head">
                            <h3 class="heading">Stock Issuance Approval Form [Requisition No.: <?php echo $_GET['rq'];?>, Requisition Period: <?php echo $dateFrom.' to '.$dateTo.', Store: '.$mainStk .' '.$distName;?>]</h3>
                        </div>
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-condensed">
                                        
                                        <!-- Table heading -->
                                        <thead>
                                            <tr>
                                                <th width="40">S. No.</th>
                                                <th>Product</th>
                                                <th>Requested Qty</th>
                                                <th>Available Qty</th>
                                                <th>Approved Qty</th>
                                                <th width="150">Action</th>
                                            </tr>                                                
                                        </thead>
                                        <!-- // Table heading END --> 
                                        
                                        <!-- Table body -->
                                        <tbody>
                                            <!-- Table row -->
                                        <form name="approve_clr6" id="approve_clr6" action="clr6_approve_action.php" method="POST" onSubmit="return formValidation()">
                                    <?php
                                    $disabled = ($masterStatus == 'Pending') ? '' : 'disabled="disabled"';
                                    $count = 1;
                                    foreach( $product as $proId=>$proName )
                                    {
                                        if ($status[$proId] == 'Denied' || $status[$proId] == 'Pending')
                                        {
                                            $deniedSel = 'checked';
                                            $approvedSel = '';
                                        }
                                        else
                                        {
                                            $deniedSel = '';
                                            $approvedSel = 'checked';
                                        }
                                        ?>
                                            <tr>
                                                <td class="center"><?php echo $count++;?></td>
                                                <td><?php echo $proName;?>
                                                    <input type="hidden" name="product[<?php echo $proId?>]" id="product" value="<?php echo $proId?>" />
                                                    <input type="hidden" name="itmrec[<?php echo $proId?>]" id="itmrec" value="<?php echo $itemrec_id[$proId]?>" /></td>
                                                <td class="right"><?php echo number_format($desiredStock[$proId]);?></td>
                                                <td class="right"><input class="form-control input-small input-sm" type="text" name="qty_available[<?php echo $proId?>]" id="qty_available[<?php echo $proId?>]" value="<?php echo number_format($availableQty[$proId]);?>" style="text-align:right;" readonly/></td>
                                                <td><input autocomplete="off" readonly max="<?php echo $availableQty[$proId];?>" class="qty form-control input-small input-sm" type="text" name="qty_approved[<?php echo $proId?>]" style="text-align:right;" id="qty_approved-<?php echo $proId?>" value="<?php if(!empty($approved[$proId])){echo $approved[$proId];}?>" <?php echo $disabled;?> /></td>
                                               	<td class="center">
                                                    <?php /*?><input type="checkbox" name="approve[<?php echo $proId?>]"  <?php if($status[$proId]=='Approved' || $status[$proId]=='Denied' || $status[$proId]=='Issued' ){echo "disabled=disabled";} else {echo "checked=checked";}?>/><?php */?>
                                                    <input type="radio" name="approve[<?php echo $proId?>]" id="approve_<?php echo $proId?>" value="1" onClick="checkAction(this, '<?php echo $proId?>')" <?php echo $approvedSel;?> <?php echo $disabled;?> /> Approve
                                                    <input type="radio" name="approve[<?php echo $proId?>]" id="decline_<?php echo $proId?>" value="0" onClick="checkAction(this, '<?php echo $proId?>')" <?php echo $deniedSel;?> <?php echo $disabled;?> /> Decline
                                                </td>
                                            </tr>
                                            <?php
                                    }
                                    ?>
                                            <tr>
                                                <td colspan="6" style="text-align:right;">
                                                    <?php 
                                                    if ($masterStatus == 'Pending')
                                                    {
                                                    ?>
                                                    <button type="submit" id="submit" class="btn btn-primary"> Save </button>
                                                    <button type="button" onClick="javascript: history.go(-1)" class="btn btn-warning"> Cancel </button>
                                                    <?php
                                                    }
                                                    else
                                                    {?>
                                                    <button type="button" onClick="javascript: history.go(-1)" class="btn btn-warning"> Back </button>
                                                    <?php	
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="warehouse" id="warehouse" value="<?php echo $_REQUEST['wh_id']?>"/>
                                            <input type="hidden" name="clr6_id" id="clr6_id" value="<?php echo $_REQUEST['id']?>"/>
                                            <input type="hidden" name="rq_no" value="<?php echo $requisitionNum?>"/>
                                        </form>
                                            </tbody>
                                        
                                        <!-- // Table body END -->
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
<?php include "../plmis_inc/common/footer.php";?>

<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/clr6issue.js"></script>

<script>
<?php
$_SESSION['stockIssueArray'] = $stockArray;
if (isset($_REQUEST['success']) && $_REQUEST['success'] == '1') {
    ?>
	var self = $('[data-toggle="notyfy"]');
	notyfy({
		force: true,
		text: 'Data has been saved successfully!',
		type: 'success',
		layout: self.data('layout')
	})
	<?php } ?>
	function checkAction(checkBox, id)
	{
		if ( $(checkBox).val() == 1 )
		{
			$('#qty_approved-'+id).removeAttr('readonly');
			
		}
		else if ( $(checkBox).val() == 0 )
		{
			$('#qty_approved-'+id).val('');
			$('#qty_approved-'+id).attr('readonly', 'readonly');
			
		}
	}
	function formValidation()
	{
		if (confirm('Are you sure you want to save the list?'))
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
		}
		else
		{
			return false;
		}
		
		$('#submit').attr('disabled', true);
		$('#submit').html('Submitting...');
	}
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
</script> 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>