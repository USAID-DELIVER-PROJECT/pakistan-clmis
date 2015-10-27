<?php
include("../html/adminhtml.inc.php");
//include "../plmis_inc/common/top.php";
include "../plmis_inc/common/top_im.php";
include("Includes/AllClasses.php");

$whId = $_SESSION['wh_id'];

if(isset($_REQUEST['tran_no']))
{
    $tran_no= $_REQUEST['tran_no'];
    if(!empty($tran_no)){
		$strSql = "SELECT
						tbl_stock_master.TranDate,
						tbl_stock_detail.Qty AS Qty,
						(tbl_stock_detail.Qty / itminfo_tab.qty_carton) AS cartonQty,
						itminfo_tab.qty_carton,
						itminfo_tab.itm_type,
						stock_batch.batch_no,
						stock_batch.batch_id,
						stock_batch.unit_price,
						stock_batch.batch_expiry,
						itminfo_tab.itm_name,
						itminfo_tab.itm_id,
						tbl_warehouse.wh_name,
						tbl_stock_master.PkStockID,
						tbl_stock_master.TranNo,
						tbl_stock_master.TranRef,
						tbl_stock_detail.PkDetailID,
						GetPicked(tbl_stock_detail.PkDetailID) AS picked
				FROM
					tbl_stock_master
					INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
					INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
					INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
					INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
				WHERE
					tbl_stock_detail.temp = 0 AND
					tbl_stock_master.WHIDFrom = '" . $whId . "' AND
					tbl_stock_master.TranTypeID = 2 AND
					tbl_stock_master.PkStockID = " . $tran_no . "";

        $result = mysql_query($strSql) or die($strSql);
    }
}
$date_from = date('01' . '/m/Y');
$date_to = date('d/m/Y');
$db_date_from = dateToDbFormat($date_from);
$db_date_to = dateToDbFormat($date_to);
?>
<?php include "../plmis_inc/common/_header.php";?>
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
include "../plmis_inc/common/top_im.php";
include "../plmis_inc/common/_top.php";
?>
<div class="page-content-wrapper">
<div class="page-content"> 
    
    <!-- BEGIN PAGE HEADER-->
    
        <div class="row">
            <div class="col-md-12">
                    <!-- Widget -->
                    <div class="widget">
                        <div class="widget-head">
                           <h3 class="heading">Stock Pick</h3>
                        </div>
                        <div class="widget-body">
                            <form name="issue_voucher_detail" id="issue_voucher_detail" action="" method="get">
                                <div class="col-md-12" style="padding-left:0px;">
                                    <div class="col-md-2">
                                        <div class="control-group">
                                            <label class="control-label">Date From</label>
                                            <div class="controls">
                                                <input type="text" class="form-control input-small" name="date_from" id="date_from" readonly value="<?php echo $date_from;?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="control-group">
                                            <label class="control-label">Date To</label>
                                            <div class="controls">
                                                <input type="text" class="form-control input-small" name="date_to" id="date_to" readonly  value="<?php echo $date_to;?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    	<div class="control-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div class="controls">
                                                <button type="button" class="btn btn-primary" onClick="showVoucherList()">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding-left:0px;">
                                    <div class="col-md-3">
                                    	<div class="control-group"><label class="control-label">Pick from Issue Voucher List</label>
                                            <div class="controls">
                                                <select name="tran_no" id="tran_no" class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php
                                                    /*$strSqlList="SELECT DISTINCT
                                                                 tbl_stock_master.TranNo,
                                                                 tbl_stock_master.PkStockID
                                                                 FROM
                                                                 tbl_stock_master
                                                                 INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
                                                                 WHERE 
                                                                 tbl_stock_master.TranTypeID = 2
                                                                 AND tbl_stock_master.WHIDFrom = $whId
                                                                 AND tbl_stock_master.TranDate BETWEEN '$db_date_from' AND '$db_date_to'
                                                                 AND ABS(tbl_stock_detail.Qty) - ABS(GetPicked (tbl_stock_detail.PkDetailID)) > 0
                                                                 ORDER BY tbl_stock_master.TranDate DESC";*/
                                                    
                                                    // Added by GM
                                                    
                                                   $strSqlList= "SELECT DISTINCT
																	A.TranNo,
																	A.PkStockID
																FROM
																	(
																		SELECT DISTINCT
																			tbl_stock_master.TranNo,
																			tbl_stock_master.PkStockID,
																			ABS(GetPicked (tbl_stock_detail.PkDetailID)) AS Picked,
																			ABS(tbl_stock_detail.Qty) AS Qty
																		FROM
																			tbl_stock_master
																		INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
																		WHERE
																			tbl_stock_master.TranTypeID = 2
																		AND tbl_stock_master.WHIDFrom = $whId
																		AND tbl_stock_master.TranDate BETWEEN '$db_date_from'
																		AND '$db_date_to'
																		ORDER BY
																			tbl_stock_master.TranDate DESC
																	) A
																WHERE
																	A.Qty > A.Picked";
     
                                                   $issueList=mysql_query($strSqlList) or die("ERR Issue Voucher");
                                                                       
                                                    while($rowVouchers=mysql_fetch_assoc($issueList)){?>
                                                    <option value="<?php echo $rowVouchers['PkStockID']?>" <?php echo ($_REQUEST['tran_no'] == $rowVouchers['PkStockID']) ? 'selected="selected"' : '';?>> <?php echo $rowVouchers['TranNo']?> </option>
                                                    <?php }?>
                                                </select>
                                           	</div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                    	<div class="control-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div class="controls">
                                                <button type="submit" class="btn btn-primary">Go</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div style="clear:both;"></div>
                            <?php if(mysql_num_rows($result)>0){?>
                            <table class="table table-striped table-bordered table-condensed" style="margin-top:10px;">
                                
                                <!-- Table heading -->
                                <thead>
                                    <tr bgcolor="#009C00" style="color:#FFF;">
                                        <th width="6%">S.No.</th>
                                        <th width="8%">Date</th>
                                        <th>Product</th>
                                        <th width="10%">Batch</th>
                                        <th width="8%">Expiry</th>
                                        <th width="25%">Issued</th>
                                        <th width="25%">Picked</th>
                                        <th width="8%" class="center">Action</th>
                                    </tr>
                                </thead>
                                <!-- // Table heading END --> 
                                
                                <!-- Table body --> 
                                
                                <!-- Table row -->
                                <?php
                    $counter=1;
                    if(mysql_num_rows($result)>0)
                    {
                        while ($row=mysql_fetch_object($result)) {
                            $_SESSION['itm_id']=$row->itm_id;
                            $_SESSION['itm_name']=$row->itm_name;
                            $_SESSION['batch_no']=$row->batch_no;
                            $_SESSION['expiry']=$row->batch_expiry;
                            ?>
                                <tr class="gradeX">
                                    <td class="center"><?php echo $counter; ?></td>
                                    <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
                                    <td><?php echo $row->itm_name; ?></td>
                                    <td><?php echo $row->batch_no; ?></td>
                                    <td class="center"><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?></td>
                                    <td class="right">
										<?php
										$issueQty = abs($row->Qty) / abs($row->qty_carton);
										if (abs($row->Qty) > 0){
											echo number_format(abs($row->Qty)).' '.$row->itm_type.' / ';
											echo ((floor($issueQty) != $issueQty) ? number_format($issueQty, 2) : number_format($issueQty)) . ' Cartons';
										}
										else{
											echo '0';
										}
										?>
                                    </td>
                                    <td class="right">
										<?php
										$pickQty = abs($row->picked) / abs($row->qty_carton);
										if (abs($row->picked) > 0){
											echo number_format(abs($row->picked)).' '.$row->itm_type.' / ';
											echo ((floor($pickQty) != $pickQty) ? number_format($pickQty, 2) : number_format($pickQty)) . ' Cartons';
										}
										else{
											echo '0';
										}
										?>
                                    </td>
                                    <td class="center">
										<?php if ($issueQty != $pickQty){?><a class="btn btn-info" style="font-size:12px !important; padding:2px 10px 2px 10px !important;" href="ajax_stock_pick.php?id=<?php echo base64_encode($row->itm_id.'_'.$row->batch_id.'_'.$row->batch_expiry.'_'.$row->itm_name.'_'.$row->PkDetailID.'_'.(abs($row->Qty) - abs($row->picked)).'_'.$_REQUEST['tran_no'].'_'.$_REQUEST['date_from'].'_'.$_REQUEST['date_to']);?>" data-target="#ajax" data-toggle="modal"> Pick</a><?php }?>
                                    </td>
                                </tr>
                                <?php
                            $counter++;
							}
						}?>
								</tbody>
                            </table>
				 <?php 
				 }?>
                        </div>
                    </div>
                    <!-- Widget --> 
                    
                <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
                    <div class="page-loading page-loading-boxed">
                        <img src="../../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                        <span>
                        &nbsp;&nbsp;Loading... </span>
                    </div>
                    <div class="modal-dialog">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
                
                <!-- // Content END --> 
                
            </div>
        </div>
    </div>
</div>
</div>
<?php include "../plmis_inc/common/footer.php";?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockplacement.js"></script>
<script>
	$(function(){
		$('#date_from').datepicker({
			dateFormat: "dd/mm/yy",
			constrainInput: false,
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
		});
        $('#date_to').datepicker({
			dateFormat: "dd/mm/yy",
			constrainInput: false,
			maxDate: 0,
			changeMonth: true,
			changeYear: true,
		});
	})
	
    function showVoucherList()
	{
        var dateFrom = $('#date_from').val();
        var dateTo = $('#date_to').val();
        $.ajax({
            url:'pick_stock_ajax.php',
            type:'POST',
            data:{dateFrom:dateFrom, dateTo:dateTo},
            success:function (data) {
				$('#tran_no').html(data);
            }
        })
    }
</script>

<?php 
if (isset($_SESSION['success']) && !empty($_SESSION['success']) ) {
    ?>
<script>
    var self = $('[data-toggle="notyfy"]');
    notyfy({
        force: true,
        text: 'Stock has been picked successfully!',
        type: 'success',
        layout: self.data('layout')
    });
</script>
	<?php 
        unset($_SESSION['success']);
	} ?>
</body>
<!-- END BODY -->
</html>