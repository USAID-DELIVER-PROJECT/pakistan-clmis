<?php
/// Stock Issue need to update
include("../html/adminhtml.inc.php");
include("Includes/AllClasses.php");

$sCriteria = array();
$number = '';
$date_from = '';
$date_to = '';
$searchby = '';
$warehouse = '';
$product = '';
$selStk = '';
$selProv = '';

if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {

    if (!empty($_REQUEST['searchby']) && !empty($_REQUEST['number'])) {
        $searchby = $_REQUEST['searchby'];
        $number = $_REQUEST['number'];
        switch ($searchby) {
            case 1:
                $objStockMaster->TranNo = $number;
                $sCriteria[0]['TranNo'] = $number;
                break;
            case 2:
                $objStockMaster->TranRef = $number;
                $sCriteria[0]['TranRef'] = $number;
                break;
            case 3:
                $objStockMaster->batch_no = $number;
                $sCriteria[0]['batch_no'] = $number;
                break;
        }
    }

    if (isset($_REQUEST['warehouse']) && !empty($_REQUEST['warehouse'])) {
        $warehouse = $_REQUEST['warehouse'];
        $wh = mysql_fetch_array(mysql_query("SELECT
												tbl_warehouse.wh_name
											FROM
												tbl_warehouse
											WHERE
												tbl_warehouse.wh_id = " . $warehouse . " "));
        $sCriteria[0]['Issued To'] = $wh['wh_name'];
    }
    if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
        $product = $_REQUEST['product'];
        $pro = mysql_fetch_array(mysql_query("SELECT
													itminfo_tab.itm_name
												FROM
													itminfo_tab
												WHERE
													itminfo_tab.itm_id = " . $product . ""));
        $sCriteria[0]['Product'] = $pro['itm_name'];
    }
    if (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) {
        $date_from = $_REQUEST['date_from'];
        $dateArr = explode('/', $date_from);
        $sCriteria[0]['From'] = date('d/m/y', strtotime($dateArr[0] . '-' . $dateArr[1] . '-' . $dateArr[2]));
    }
    if (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to'])) {
        $date_to = $_REQUEST['date_to'];
        $dateArr = explode('/', $date_to);
        $sCriteria[0]['To'] = date('d/m/y', strtotime($dateArr[0] . '-' . $dateArr[1] . '-' . $dateArr[2]));
    }
	if (isset($_REQUEST['funding_source']) && !empty($_REQUEST['funding_source'])) {
		$funding_source = $_REQUEST['funding_source'];
		$objStockMaster->funding_source = $funding_source;
	}
    $objStockMaster->WHIDTo = (!empty($warehouse)) ? $warehouse : '';
    $objStockMaster->item_id = (!empty($product)) ? $product : '';
    $objStockMaster->fromDate = (!empty($date_from)) ? dateToDbFormat($date_from) : '';
    $objStockMaster->toDate = (!empty($date_to)) ? dateToDbFormat($date_to) : '';
	
	$selProv = (!empty($_REQUEST['province'])) ? $_REQUEST['province'] : '';
	$selStk = (!empty($_REQUEST['stakeholder'])) ? $_REQUEST['stakeholder'] : '';
    $objStockMaster->province = $selProv;
    $objStockMaster->stakeholder = $selStk;
} else {
    $date_from = date('01' . '/m/Y');
    $date_to = date('d/m/Y');
    $objStockMaster->fromDate = dateToDbFormat($date_from);
    $objStockMaster->toDate = dateToDbFormat($date_to);
}
$wh_id = $_SESSION['wh_id'];
$result = $objStockMaster->StockIssueSearch(2, $wh_id);

$title = "Stock Receive";
include('../' . $_SESSION['menu']);

$fundingSources = $objwarehouse->GetUserWarehouses();
$warehouses = $objwarehouse->GetUserIssueToWH($wh_id);
$items = $objManageItem->GetAllManageItem();
$stakeholders = $objstk->GetAllMainStakeholders();
$provinces = $objloc->GetAllProvinces();
?>
<?php include "../plmis_inc/common/_header.php"; ?>

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
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Stock Issue</h3>
                            </div>
                            <div class="widget-body">
                                <form method="POST" name="batch_search" action="" >
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-12"></div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="firstname">Search by</label>
                                                    <select name="searchby" id="searchby" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <option value="1" <?php if ($searchby == 1) { ?> selected <?php } ?>>Issue No</option>
                                                        <option value="2" <?php if ($searchby == 2) { ?> selected <?php } ?>>Issue Ref</option>
                                                        <option value="3" <?php if ($searchby == 3) { ?> selected <?php } ?>>Batch No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="number">&nbsp;</label>
                                                    <input class="form-control input-medium" id="number" name="number" type="text" value="<?php echo $number; ?>" />
                                                </div>
                                            </div>
										<?php if( $_SESSION['wh_id'] == 123 ){?>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>
                                                        Funding Source
                                                    </label>
                                                    <select name="funding_source" id="funding_source" class="form-control input-medium" <?php if (!empty($funding_source) && !empty($TranNo)) { ?>disabled="" <?php } ?>>
                                                        <option value="">Select</option>
                                                        <?php
                                                        if (mysql_num_rows($fundingSources) > 0) {
                                                            while ($row = mysql_fetch_object($fundingSources)) {
                                                                ?>
                                                                <option value="<?php echo $row->wh_id; ?>" <?php if ($funding_source == $row->wh_id) { ?> selected="" <?php } ?>> <?php echo $row->wh_name; ?> </option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($funding_source) && !empty($TranNo)) { ?>
                                                        <input type="hidden" name="funding_source" id="funding_source" value="<?php echo $funding_source;?>" />
                                                    <?php }?>
                                                </div>
                                            </div>
                                        <?php }?>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label  for="firstname">Product</label>

                                                    <select name="product" id="product" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($items != FALSE) {
                                                            while ($row = mysql_fetch_object($items)) {
                                                                ?>
                                                                <option value="<?php echo $row->itm_id; ?>" <?php if ($product == $row->itm_id) { ?> selected="" <?php } ?>><?php echo $row->itm_name; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label  for="stakeholder">Stakeholder</label>
                                                    <select name="stakeholder" id="stakeholder" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($stakeholders != FALSE) {
                                                            while ($row = mysql_fetch_object($stakeholders)) {
                                                                ?>
                                                                <option value="<?php echo $row->stkid; ?>" <?php if ($selStk == $row->stkid) { ?> selected="selected" <?php } ?>><?php echo $row->stkname; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label  for="province">Province</label>
                                                    <select name="province" id="province" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
                                                        if ($provinces != FALSE) {
                                                            while ($row = mysql_fetch_object($provinces)) {
                                                                ?>
                                                                <option value="<?php echo $row->PkLocID; ?>" <?php if ($selProv == $row->PkLocID) { ?> selected="selected" <?php } ?>><?php echo $row->LocName; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="warehouse">Warehouse/Supplier</label>
                                                    <select name="warehouse" id="warehouse" class="form-control input-medium">
                                                        <?php
														if ( !empty($selProv) || !empty($selStk) )
														{
															$and = " 1=1 ";
															if (!empty($selProv))
															{
																$and .= " AND tbl_warehouse.prov_id = ".$selProv." ";
															}if (!empty($selStk))
															{
																$and .= " AND tbl_warehouse.stkid = ".$selStk."";
															}
															$qry = "SELECT DISTINCT
																		tbl_warehouse.wh_id,
																		CONCAT(tbl_warehouse.wh_name,	'(', stakeholder.stkname, ')') AS wh_name
																	FROM
																		tbl_warehouse
																	INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
																	INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
																	WHERE
																		$and
																	ORDER BY
																		tbl_warehouse.wh_name ASC";
															$qryRes = mysql_query($qry);
															echo '<option value="">Select</option>';
															while ($row = mysql_fetch_array($qryRes))
															{
																$sel = ($warehouse == $row['wh_id']) ? 'selected="selected"' : '';
																echo "<option value=\"$row[wh_id]\" $sel>$row[wh_name]</option>";
															}
														}
														else 
														{
															echo '<option value="">Select</option>';
														}
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <!-- Group -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="firstname">Date From</label>
                                                    <input type="text" readonly class="form-control input-medium" name="date_from" id="date_from" value="<?php echo $date_from; ?>"/>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label  for="firstname">Date To</label>
                                                    <input type="text" readonly class="form-control input-medium" name="date_to" id="date_to" value="<?php echo $date_to; ?>"/>
                                                </div>

                                            </div>
                                            <div class="col-md-6" style="text-align:right;">
                                                <label for="firstname">&nbsp;</label>
                                                <div class="form-group">
                                                    <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                                                    <button type="reset" class="btn btn-info">Reset</button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- Widget -->
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Issue Search</h3>
                            </div>
                            <!-- // Widget heading END -->

                            <div class="widget-body">
                            
                                <!-- Table -->
                                <table class="issuesearch table table-bordered table-condensed">

                                    <!-- Table heading -->
                                    <thead>
                                        <tr>
                                            <th width="5%">Sr. No</th>                                             
                                            <th>Issue No</th>                       
                                            <th>Ref No</th>
                                            <th>Issue To</th>
                                            <th>Issue Date</th>
                                            <th>Product</th>
                                            <?php if( $_SESSION['wh_id'] == 123 ){?>
                                            <th>Funding Source</th>
                                            <?php }?>
                                            <th>Batch No.</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Expiry Date</th>
                                            <!-- th>Action</th -->
                                        </tr>
                                    </thead>
                                    <!-- // Table heading END -->

                                    <!-- Table body -->
                                    <tbody>
                                        <!-- Table row -->
                                        <?php
                                        $stockArray = array();
                                        $i = 1;
                                        if ($result != FALSE) :
											$transNo = '';
                                            while ($row = mysql_fetch_object($result)) :
                                                $stockArray[] = $row;
                                                $dis = 'Pick Order';
                                                $linkk = 'coldChainQtyPick.php';
                                                if ($row->sumIssueQty == abs($row->Qty)) {
                                                    $dis = 'Picked';
                                                    $linkk = 'coldChainQtyPickHistroy.php';
                                                }
												
												if ($transNo != $row->PkStockID){
													$bgColor = ($bgColor == '#CCC') ? '#FFF' : '#CCC';
												}else {
													$bgColor = $bgColor;
												}
												$transNo = $row->PkStockID;
                                                ?>
                                                <tr class="gradeX" style="background-color:<?php echo $bgColor;?> !important">
                                                    <td class="center"><?php echo $i; ?></td>  
                                                    <td><a  onclick="window.open('printIssue.php?id=<?php echo $row->PkStockID; ?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $row->TranNo; ?></a></td>
                                                    <td><?php echo $row->TranRef; ?>&nbsp;</td>
                                                    <td><?php echo $row->wh_name; ?></td>  
                                                    <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
                                                	<td><?php echo $row->itm_name; ?></td>
													<?php if( $_SESSION['wh_id'] == 123 ){?>
                                                    <td><?php echo $row->funding_source; ?></td>
                                                    <?php }?>
                                                    <td><?php echo $row->batch_no; ?></td>
                                                    <td style="text-align:right;"><?php echo number_format(abs($row->Qty)); ?></td>
                                                    <td><?php echo $row->UnitType; ?></td>
                                                    <td><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?></td>

                                                   <!-- td>
                                                        <a  onclick="window.open('<?php echo $linkk; ?>?stockDetailId=<?php echo base64_encode($row->PkDetailID); ?>&qty=<?php echo base64_encode(abs($row->Qty)); ?>&batchID=<?php echo base64_encode($row->BatchID); ?>&product=<?php echo base64_encode($row->itm_name); ?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $dis; ?></a>
                                                        <!--  a data-toggle="notyfy" id="<?php echo $row->PkDetailID; ?>" data-type="confirm" data-layout="top" style="cursor: pointer;"><img src="../plmis_img/delete_icon.png" /></a>
                                                    </td -->
                                                </tr>
                                                <?php
                                                $i++;
                                            endwhile;
                                        endif;
                                        ?>
                                        <!-- // Table row END -->
                                    </tbody>
                                    <!-- // Table body END -->
                                </table>
                                <!-- // Table END -->
                                <?php if ($result != FALSE) { ?>
                                    <div class="right" style="margin-top:10px;">
                                        <div style="float:right;">
                                            <b>Detail: </b>
                                            <input type="radio" name="groupBy" id="none" value="none" checked="checked" /> None
                                            <input type="radio" name="groupBy" id="loc" value="loc" /> Location wise
                                            <input type="radio" name="groupBy" id="prod" value="prod" /> Product wise
                                            <button id="print_vaccine_issue" type="button" class="btn btn-warning">Print</button>
                                        </div>
                                        <div style="float:left;">
                                            <b>Summary: </b>
                                            <input type="radio" name="summary" id="prod" value="prod" checked="checked" /> Product Wise
                                            <input type="radio" name="summary" id="loc" value="loc" /> Location wise
                                            <button id="print_vaccine_summary" type="button" class="btn btn-warning">Print</button>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- // Content END -->
            </div>
        </div>
    </div>

</div>

</div>
<?php include "../plmis_inc/common/footer.php"; ?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockissue.js"></script>
<?php
$_SESSION['stockArray'] = $stockArray;
$_SESSION['sCriteria'] = $sCriteria;
/* echo '<pre>';
  print_r($_SESSION);die; */
if (isset($_REQUEST['s']) && $_REQUEST['s'] == 't') {
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'Data has been deleted successfully!',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php } ?>
</body>
<!-- END BODY -->
</html>