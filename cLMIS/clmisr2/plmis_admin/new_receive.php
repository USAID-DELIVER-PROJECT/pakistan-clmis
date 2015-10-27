<?php
include("../html/adminhtml.inc.php");
/* include "../plmis_inc/common/top.php";
  include "../plmis_inc/common/top_im.php"; */
include("Includes/AllClasses.php");
$TranNo = '';
$TranRef = '';
$from_id = 0;
$productID = 0;
$unit_price = 0;
$vvmtype = 0;
$vvmstage = 0;
$stock_id = 0;

$userid = $_SESSION['userid'];
$wh_id = $_SESSION['wh_id'];
$wh_from = '';
$PkStockID = '';

if (isset($_GET['PkStockID'])) {
    $PkStockID = base64_decode($_GET['PkStockID']);
    $tempstocksIssue = $objStockMaster->GetTempStockRUpdate($PkStockID);
} else {
    $tempstocksIssue = $objStockMaster->GetTempStockReceive($userid, $wh_id, 1);
}
if (!empty($tempstocksIssue) && mysql_num_rows($tempstocksIssue) > 0) {

    $result = mysql_fetch_object($tempstocksIssue);
    /* 	echo '<pre>';
      print_r($result);die; */
    $stock_id = $result->PkStockID;
    $from_id = $result->WHIDFrom;
    //$productID=$result->itm_id;
    $wh_from = $objwarehouse->GetWHByWHId($from_id);

    //$TranDate = dateToUserFormat($result->TranDate);
    $TranDate = $result->TranDate;

    $TranNo = $result->TranNo;
    $TranRef = $result->TranRef;

    $tempstocksIssueDet = $objStockMaster->GetLastInseredTempStocksReceiveList($userid, $wh_id, 1);
    if (!empty($tempstocksIssueDet)) {
        $result1 = mysql_fetch_object($tempstocksIssueDet);
        if (!empty($result1)) {
            $productID = $result1->itm_id;
            $unit_price = $result1->unit_price;
            $vvmtype = $result1->vvm_type;
            $vvmstage = $result1->vvm_stage;
            $manufacturer = $result1->manufacturer;
        }
    }
}
if (!empty($productID)) {

    //$manufacturer_product = $objstk->GetAllManufacturersUpdate($productID);
}

$tempstocks = $objStockMaster->GetTempStocksReceiveList($userid, $wh_id, 1);
if (!empty($tempstocks) && mysql_num_rows($tempstocks) > 0) {
    
} else {
    $objStockMaster->PkStockID = $stock_id;
    $objStockMaster->delete();
}

$warehouses = $warehouses1 = $objwarehouse->GetUserWarehouses();
$items = $objManageItem->GetAllManageItem();
$units = $objItemUnits->GetAllItemUnits();
//$vvmtypes = $objvvmType->getAllTypes();
?>
<?php include "../plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
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
                                <h3 class="heading">Stock Receive (From Supplier)</h3>
                            </div>
                            <div class="widget-body">
                                <form method="POST" name="new_receive" id="new_receive" action="new_receive_action.php" >
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"> 
                                                <!-- Group Receive No-->
                                                <div class="control-group">
                                                    <label class="control-label" for="receive_no"> Receipt No </label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" tabindex="1" id="receive_no" value="<?php echo $TranNo; ?>" name="receive_no" type="text" readonly />
                                                        <input type="hidden"  id="source_name" name="source_name" value="<?php echo $wh_from; ?> " />
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- // Group END Receive No-->
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="receive_ref"> Ref. No. <span class="red">*</span> </label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" required id="receive_ref" name="receive_ref" type="text" value="<?php echo $TranRef; ?>" <?php if (!empty($TranRef)) { ?>disabled="" <?php } ?>/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="receive_date"> Receiving Time </label>
                                                    <div class="controls">
<?php //echo $TranDate;  ?>
                                                        <input class="form-control input-medium" <?php
if (!empty($TranDate)) {
    echo 'disabled=""';
} else {
    echo 'readonly="readonly" style="background:#FFF"';
}
?> id="receive_date" tabindex="2" name="receive_date" type="text" value="<?php echo (!empty($TranDate)) ? date("d/m/y h:i A", strtotime($TranDate)) : date("d/m/Y h:i A"); ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">&nbsp;</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label" for="receive_from"> Received From (Funding Source)<span class="red">*</span> </label>
                                                <div class="controls">
                                                    <select name="receive_from" id="receive_from" required="true" class="form-control input-medium" <?php if (!empty($from_id) && !empty($TranNo)) { ?>disabled="" <?php } ?>>
                                                        <option value="">Select</option>
														<?php
                                                        if (mysql_num_rows($warehouses) > 0) {
                                                            while ($row = mysql_fetch_object($warehouses)) {
                                                                ?>
                                                                <option value="<?php echo $row->wh_id; ?>" <?php if ($from_id == $row->wh_id) { ?> selected="" <?php } ?>> <?php echo $row->wh_name; ?> </option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($from_id) && !empty($TranNo)) { ?>
                                                    	<input type="hidden" name="receive_from" id="receive_from" value="<?php echo $from_id;?>" />
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label" for="product"> Product <span class="red">*</span> </label>
                                                <div class="controls">
                                                    <select name="product" id="product" required="true" class="form-control input-medium">
                                                        <option value=""> Select </option>
<?php
if (mysql_num_rows($items) > 0) {
    while ($row = mysql_fetch_object($items)) {

        $sel = '';
        if ($productID == $row->itm_id) {
            $sel = '';
        }
        echo "<option value=" . $row->itm_id . " " . $sel . " >" . $row->itm_name . "</option>";
    }
}
?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-4" style="padding-left:0 !important;">
                                                    <label class="control-label" for="manufacturer"> Manufacturer </label>
                                                    <div class="controls">
                                                        <select name="manufacturer" id="manufacturer" class="form-control input-medium" style="width:180px !important;">
                                                            <option value="">Select</option>
<?php
if (mysql_num_rows($manufacturer_product) > 0) {

    while ($row = mysql_fetch_object($manufacturer_product)) {
        $sel = '';
        if ($manufacturer == $row->stk_id) {
            $sel = 'selected=""';
        }
        echo "<option value=" . $row->stk_id . " " . $sel . " >" . $row->stkname . "</option>";
    }
}
?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" style="margin-top: 30px; padding-right: 22px;"> <a class="btn btn-primary alignvmiddle"
                                                                                                                         style="display:none;" id="add_m_p"  onclick="javascript:void(0);" data-toggle="modal"  href="#modal-manufacturer">Add</a> </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label" for="batch"> Batch No <span class="red">*</span> </label>
                                                <div class="controls">
                                                    <input class="form-control input-medium" id="batch" name="batch" type="text" required />
                                                </div>
                                            </div>
                                            <!-- div class="col-md-3">
                    <div class="control-group">
                        <label class="control-label" for="prod_date">
                            Production Date
                        </label>
                        <div class="controls">
                            <input class="form-control input-medium" id="prod_date" name="prod_date" type="text" value="<?php echo (!empty($prod_date)) ? $prod_date : ''; ?>" />
                        </div>
                    </div>
                </div -->
                                            <div class="col-md-3" id="expiry_div">
                                                <label class="control-label" for="expiry_date"> Expiry date <span class="red">*</span> </label>
                                                <div class="controls">
                                                    <input type="text" class="form-control input-medium" name="expiry_date" id="expiry_date" readonly required style="background:#FFF;"/>
                                                </div>
                                            </div>
                                            <!-- div class="col-md-3">
                    <div class="control-group">
                        <label class="control-label" for="unit_price">
                            Unit Price (US$)
                        </label>
                        <div class="controls">
                            <input class="form-control input-medium" id="unit_price" name="unit_price" type="text" value="<?php echo number_format($unit_price, 2); ?>"/>
                        </div>
                    </div>
                </div --> 
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label" for="qty"> Quantity <span class="red">*</span> </label>
                                                <div class="controls">
                                                    <input type="text" class="form-control input-medium num" name="qty" id="qty" autocomplete="off" />
                                                    <span id="product-unit"> </span> <span id="product-unit1" style="display:none;"> </span> </div>
                                            </div>
                                            <div class="col-md-9">
                                                <label class="control-label" for="firstname"> &nbsp; </label>
                                                <div class="controls right">
                                                    <button type="submit" class="btn btn-primary" id="add_receive"> Save Entry </button>
                                                    <button type="reset" class="btn btn-info" id="reset"> Reset </button>
                                                    <input type="hidden" name="trans_no" id="trans_no" value="<?php echo $TranNo; ?>" />
                                                    <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id; ?>" />

<!--<input  type="hidden" name="PkStockID" value="<?php echo $PkStockID; ?>"/>--> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="modal-manufacturer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content"> 
                                    <!-- Modal heading -->
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <div id="pro_loc"></div>
                                    </div>
                                    <!-- // Modal heading END --> 

                                    <!-- Modal body -->
                                    <div class="modal-body"> 
                                        <!-- BODY HERE -->
                                        <form name="addnew" action="add_action_manufacturer.php" method="POST">
                                            <div class="span6">
                                                <label class="control-label">Add Manufacturer</label>
                                                <div class="controls">
                                                    <input required class="form-control input-medium input-sm" type="text" id="new_manufacturer" name="new_manufacturer" value=""/>
                                                </div>
                                            </div>
                                            <input type="hidden" id="add_manufacturer" name="add_manufacturer" value="1"/>
                                        </form>
                                    </div>
                                    <!-- // Modal body END --> 

                                    <!-- Modal footer -->
                                    <div class="modal-footer"> <a data-dismiss="modal" class="btn btn-default" href="#">Close</a> <a class="btn btn-primary" id="save_manufacturer" data-dismiss="modal" href="#">Save changes</a> </div>
                                    <!-- // Modal footer END --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>       
                <!-- // Row END -->
<?php if (!empty($tempstocks) && mysql_num_rows($tempstocks) > 0) { ?>
                    <!--  -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">Receive List</h3>
                                </div>
                                <div class="widget-body" id="gridData">
                                    <table class="table table-striped table-bordered table-condensed" id="myTable">
                                        <!-- Table heading -->
                                        <thead>
                                            <tr bgcolor="#009C00" style="color:#FFF;">
                                                <th> Receiving Time </th>
                                                <th> Product </th>
                                                <th> Manufacturer </th>
                                                <th> Unit </th>
                                                <th> Receive From </th>
                                                <th class="span2"> Quantity </th>
                                                <th> Cartons </th>
                                                <th class="span2"> Batch </th>
                                                <th nowrap> Expiry Date </th>
                                                <th width="50"> Action </th>
                                            </tr>
                                        </thead>
                                        <!-- // Table heading END --> 

                                        <!-- Table body -->
                                        <tbody>
                                            <!-- Table row -->
    <?php
    $i = 1;
    $makeArr = array();
    $checksumVials = array();
    $checksumDoses = array();
    while ($row = mysql_fetch_object($tempstocks)) {
        $makeArr[] = $row;
        // Checksum
        // $checksumVials[$row->itm_category][] = abs($row->Qty);
        // $checksumDoses[$row->itm_category][] = abs($row->Qty) * $row->doses_per_unit;
        ?>
                                                <tr class="gradeX">
                                                    <td nowrap><?php echo date("d/m/y h:i A", strtotime($row->TranDate)); ?></td>
                                                    <td><?php echo $row->itm_name; ?></td>
                                                    <td><?php
                                        if (!empty($row->manufacturer)) {

                                            $getManufacturer = mysql_query("SELECT
                            stakeholder.stkname
                            FROM
                            stakeholder_item
                            INNER JOIN stakeholder ON stakeholder_item.stkid = stakeholder.stkid
                            WHERE
                            stakeholder_item.stk_id = $row->manufacturer") or die("err  manufacturer");
                                            $manufacturerRow = mysql_fetch_assoc($getManufacturer);
                                            echo $manufacturerRow['stkname'];
                                        }
        ?></td>
                                                    <td><?php echo $row->UnitType; ?></td>
                                                    <td><?php echo $row->wh_name; ?></td>
                                                    <td class="right editableSingle Qty id<?php echo $row->PkDetailID; ?>"><?php echo number_format(abs($row->Qty)); ?></td>
                                                    <td class="right"><?php echo number_format(abs($row->Qty) / $row->qty_carton); ?></td>
                                                    <td class="editableSingle Batch id<?php echo $row->PkDetailID; ?>"><?php echo $row->batch_no; ?></td>
                                                    <td><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?></td>
                                                    <td class="center">
                                                        <span data-toggle="notyfy" id="<?php echo $row->PkDetailID; ?>" data-type="confirm" data-layout="top"><img class="cursor" src="<?php echo SITE_URL; ?>plmis_img/cross.gif" /></span>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            }
                                            ?>
                                            <?php $_SESSION['stock_rec_supplier'] = $makeArr; ?>
                                            <!-- // Table row END -->
                                        </tbody>
                                        <!-- // Table body END -->
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <div class="-body right">
                            <form name="receive_stock" id="receive_stock" action="new_receive_action.php" method="POST">
                                <button id="print_vaccine_placement" type="submit" class="btn btn-primary" onClick="return confirm('Are you sure you want to save the form?');"> Save &amp; Print </button>
                                <input type="hidden" name="stockid" id="stockid" value="<?php echo $stock_id; ?>" />
                            </form>
                        </div>
                    </div>
                    <?php }
                ?>
            </div>
        </div>
        <!-- // Content END --> 

    </div>

    <?php include "../plmis_inc/common/footer.php"; ?>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/newreceive.js"></script> 
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script> 
    <script src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit.js"></script>
    <?php
    if (!empty($_SESSION['success'])) {
        if ($_SESSION['success'] == 1) {
            $text = 'Data has been saved successfully';
        }
        if ($_SESSION['success'] == 2) {
            $text = 'Data has been deleted successfully';
        }
        ?>
        <script>
                                    var self = $('[data-toggle="notyfy"]');
                                    notyfy({
                                        force: true,
                                        text: '<?php echo $text; ?>',
                                        type: 'success',
                                        layout: self.data('layout')
                                    });
        </script>
    <?php
    unset($_SESSION['success']);
}
?>
    <!-- END FOOTER --> 

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>