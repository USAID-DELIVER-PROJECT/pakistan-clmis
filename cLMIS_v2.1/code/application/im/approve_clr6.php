<?php
/**
 * approve_clr6
 * @package im
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("../includes/classes/AllClasses.php");
//include header
include(PUBLIC_PATH . "html/header.php");
//tytle
$title = "New Issue";

if (isset($_REQUEST['id']) && isset($_REQUEST['wh_id'])) {
    //to warwhouse
    $whTo = mysql_real_escape_string($_REQUEST['wh_id']);
    //get id
    $id = mysql_real_escape_string($_REQUEST['id']);
    //select query
    //gets
    //dist id
    //prov id
    //stk id
    //location name
    //main stakeholder
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
			tbl_warehouse.wh_id = " . $whTo;
    //query result
    $qryRes = mysql_fetch_array(mysql_query($qry));
    //dist id
    $distId = $qryRes['dist_id'];
    //prov id
    $provId = $qryRes['prov_id'];
    //stk id
    $stkid = $qryRes['stkid'];
    //dist name
    $distName = $qryRes['LocName'];
    //main stk
    $mainStk = $qryRes['MainStk'];
//select query
    //gets
    //clr_master.requisition_num,
    //date_from,
    //date_to,
    //replenishment,
    //requested_on,
    //itm_id,
    //itmrec_id,
    //itm_name,
    //desired_stock,
    //batch_no,
    //approve_qty,
    //approval_status,
    //available_qty,
    //masterStatus
    $qry = "SELECT
				clr_master.requisition_num,
				clr_master.date_from,
				clr_master.date_to,
				clr_details.replenishment,
				DATE_FORMAT(clr_master.requested_on,'%d/%m/%Y') AS requested_on,
				itminfo_tab.itm_id,
				itminfo_tab.itmrec_id,
				itminfo_tab.itm_name,
				clr_details.desired_stock,
				stock_batch.batch_no,
				clr_details.approve_qty,
				clr_details.approval_status,
				SUM(stock_batch.Qty) AS available_qty,
				clr_master.approval_status AS masterStatus
			FROM
				clr_master
			INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
			INNER JOIN itminfo_tab ON clr_details.itm_id = itminfo_tab.itm_id
			LEFT JOIN stock_batch ON itminfo_tab.itm_id = stock_batch.item_id
			WHERE
				clr_master.pk_id = $id
			GROUP BY
				itminfo_tab.itmrec_id
			ORDER BY
				itminfo_tab.frmindex ASC";
    //query result
    $qryRes = mysql_query($qry);
    //batch number
    $batchno = '';
    //fetch result
    while ($row = mysql_fetch_array($qryRes)) {
        //requisitionNum 
        $requisitionNum = $row['requisition_num'];
        //date from 
        $dateFrom = date('M-Y', strtotime($row['date_from']));
        //date to
        $dateTo = date('M-Y', strtotime($row['date_to']));
        //requested on
        $requestedOn = $row['requested_on'];
        //item id
        $item_id[] = $row['itm_id'];
        //batch num
        $batchno[$row['itm_id']] = $row['batch_no'];
        //product
        $product[$row['itm_id']] = $row['itm_name'];
        //replenishment
        $replenishment[$row['itm_id']] = $row['replenishment'];
        //desiredStock
        $desiredStock[$row['itm_id']] = $row['desired_stock'];
        //itemrec_id
        $itemrec_id[$row['itm_id']] = $row['itm_id'];
        //approved
        $approved[$row['itm_id']] = $row['approve_qty'];
        //status
        $status[$row['itm_id']] = $row['approval_status'];
        //availableQty
        $availableQty[$row['itm_id']] = $row['available_qty'];
        //masterStatus 
        $masterStatus = $row['masterStatus'];
    }
    $duration = $dateFrom . ' to ' . $dateTo;
}
?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" >
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include PUBLIC_PATH . "html/top.php"; ?>
        <div class="page-content-wrapper">
            <div class="page-content"> 
                <div class="row">
                    <div class="col-md-12">

                        <div class="widget" id="printing">

                            <?php include PUBLIC_PATH . "html/top_im.php"; ?>
                            <style type="text/css" media="print">
                                @media print
                                {    
                                    #printButt
                                    {
                                        display: none !important;
                                    }
                                }
                            </style>
                            <div class="widget-head">
                                <h3 class="heading">Stock Issuance Approval Form [Requisition No.: <?php echo $_GET['rq']; ?>, Requisition Period: <?php echo $dateFrom . ' to ' . $dateTo . ', Store: ' . $mainStk . ' ' . $distName; ?>]</h3>
                            </div>
                            <div class="widget-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-condensed">

                                            <!-- Table heading -->
                                            <thead>
                                                <tr>
                                                    <th width="70">S. No.</th>
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
                                                $disabled = '';
                                                $readonly = '';
                                                $deniedSel = '';
                                                $approvedSel = '';
                                                $count = 1;
                                                foreach ($product as $proId => $proName) {

                                                    if ($masterStatus == 'Pending' || $masterStatus == 'Denied') {
                                                        $disabled = '';
                                                        $readonly = 'readonly="readonly"';
                                                        $deniedSel = 'checked="checked"';
                                                        $approvedSel = '';
                                                    } else if ($masterStatus == 'Approved') {
                                                        if ($status[$proId] == 'Approved') {
                                                            $readonly = '';
                                                            $approvedSel = 'checked="checked"';
                                                            $deniedSel = '';
                                                        } else if ($status[$proId] == 'Denied') {
                                                            $readonly = 'readonly="readonly"';
                                                            $deniedSel = 'checked="checked"';
                                                            $approvedSel = '';
                                                        }
                                                        $disabled = '';
                                                    } else if ($masterStatus == 'Issue in Process') {
                                                        if ($status[$proId] == 'Issued') {
                                                            $disabled = 'disabled="disabled"';
                                                            $readonly = '';
                                                            $approvedSel = 'checked="checked"';
                                                            $deniedSel = '';
                                                        } else if ($status[$proId] == 'Approved') {
                                                            $disabled = '';
                                                            $readonly = '';
                                                            $approvedSel = 'checked="checked"';
                                                            $deniedSel = '';
                                                        } else if ($status[$proId] == 'Denied') {
                                                            $disabled = '';
                                                            $readonly = 'readonly="readonly"';
                                                            $deniedSel = 'checked="checked"';
                                                            $approvedSel = '';
                                                        }
                                                    } else if ($masterStatus == 'Issued') {
                                                        if ($status[$proId] == 'Issued') {
                                                            $approvedSel = 'checked="checked"';
                                                        } else if ($status[$proId] == 'Denied') {
                                                            $deniedSel = 'checked="checked"';
                                                        }
                                                        $disabled = 'disabled="disabled"';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="center"><?php echo $count++; ?></td>
                                                        <td><?php echo $proName; ?>
                                                            <input type="hidden" name="product_status[<?php echo $proId; ?>]" id="product_status" value="<?php echo $status[$proId] ?>" />
                                                            <input type="hidden" name="itmrec[<?php echo $proId ?>]" id="itmrec" value="<?php echo $itemrec_id[$proId] ?>" /></td>
                                                        <td class="right"><?php echo number_format($replenishment[$proId]); ?></td>
                                                        <td class="right"><input class="form-control input-small input-sm" type="text" name="qty_available[<?php echo $proId ?>]" id="qty_available[<?php echo $proId ?>]" value="<?php echo number_format($availableQty[$proId]); ?>" style="text-align:right;" readonly/></td>
                                                        <td><input autocomplete="off" <?php echo $readonly; ?> max="<?php echo $availableQty[$proId]; ?>" class="qty form-control input-small input-sm" type="text" name="qty_approved[<?php echo $proId ?>]" style="text-align:right;" id="qty_approved-<?php echo $proId ?>" value="<?php
                                                            if (!empty($approved[$proId])) {
                                                                echo $approved[$proId];
                                                            }
                                                            ?>" <?php echo $disabled; ?> /></td>
                                                        <td class="center">
    <?php /* ?><input type="checkbox" name="approve[<?php echo $proId?>]"  <?php if($status[$proId]=='Approved' || $status[$proId]=='Denied' || $status[$proId]=='Issued' ){echo "disabled=disabled";} else {echo "checked=checked";}?>/><?php */ ?>
                                                            <input type="radio" name="approve[<?php echo $proId ?>]" id="approve_<?php echo $proId ?>" value="1" onClick="checkAction(this, '<?php echo $proId ?>')" <?php echo $approvedSel; ?> <?php echo $disabled; ?> /> Approve
                                                            <input type="radio" name="approve[<?php echo $proId ?>]" id="decline_<?php echo $proId ?>" value="0" onClick="checkAction(this, '<?php echo $proId ?>')" <?php echo $deniedSel; ?> <?php echo $disabled; ?> /> Decline
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="6" style="text-align:right;" id="printButt">
                                                        <?php
                                                        if ($masterStatus != 'Issued') {
                                                            ?>
                                                            <button type="submit" id="submit" class="btn btn-primary"> Save </button>
                                                            <button type="button" onClick="javascript: history.go(-1)" class="btn btn-warning"> Cancel </button>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" onClick="javascript: history.go(-1)" class="btn btn-primary"> Back </button>
                                                            <button type="button" onClick="printContents()" class="btn btn-warning"> Print </button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="warehouse" id="warehouse" value="<?php echo $_REQUEST['wh_id'] ?>"/>
                                                <input type="hidden" name="clr6_id" id="clr6_id" value="<?php echo $_REQUEST['id'] ?>"/>
                                                <input type="hidden" name="rq_no" value="<?php echo $requisitionNum ?>"/>
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
<?php include PUBLIC_PATH . "/html/footer.php"; ?>

    <script src="<?php echo PUBLIC_URL; ?>js/dataentry/clr6issue.js"></script>

    <script>
                                                            function printContents() {
                                                                var dispSetting = "toolbar=yes,location=no,directories=yes,menubar=yes,scrollbars=yes, left=100, top=25";
                                                                var printingContents = document.getElementById("printing").innerHTML;

                                                                var docprint = window.open("", "", printing);
                                                                docprint.document.open();
                                                                docprint.document.write('<html><head><title>Approve CLR-6</title>');
                                                                docprint.document.write('</head><body onLoad="self.print(); self.close()"><center>');
                                                                docprint.document.write(printingContents);
                                                                docprint.document.write('</center></body></html>');
                                                                docprint.document.close();
                                                                docprint.focus();
                                                            }
    </script>
    <script>
<?php
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
            if ($(checkBox).val() == 1)
            {
                $('#qty_approved-' + id).removeAttr('readonly');

            }
            else if ($(checkBox).val() == 0)
            {
                $('#qty_approved-' + id).val('');
                $('#qty_approved-' + id).attr('readonly', 'readonly');

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
                        qtyValue = parseInt(qtyValue.replace(/\,/g, ''));
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

                /*if (q == 0) {
                 alert('Please enter at least one quantity');
                 return false;
                 }*/
            }
            else
            {
                return false;
            }

            $('#submit').attr('disabled', true);
            $('#submit').html('Submitting...');
        }
        $(function() {
            $('.qty').priceFormat({
                prefix: '',
                thousandsSeparator: ',',
                suffix: '',
                centsLimit: 0,
                limit: 10,
                clearOnEmpty: true
            });
        })
    </script> 
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>