<?php
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/top_im.php";
//include "../plmis_inc/common/top.php";
include("Includes/AllClasses.php");
$title = "Batch Management";
//include('../template/header-top.php');
$items = $objManageItem->GetAllManageItem();
$warehouses = $warehouses1 = $objwarehouse->GetUserWarehouses();

if (!empty($_SESSION['batch_management'])) {
    unset($_SESSION['batch_management']);
}

$product = '';
$batch_no = '';
$ref_no = '';
$status = '4';
$p_search = '';
$batchArray = array();

$flag = false;
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
    $product = $_REQUEST['product'];
    $flag = true;
}
if (isset($_REQUEST['status']) && !empty($_REQUEST['status'])) {
    $status = $_REQUEST['status'];
}
if (isset($_REQUEST['batch_no']) && !empty($_REQUEST['batch_no'])) {
    $batch_no = $_REQUEST['batch_no'];
}
if (isset($_REQUEST['ref_no']) && !empty($_REQUEST['ref_no'])) {
    $ref_no = $_REQUEST['ref_no'];
}
if (isset($_REQUEST['funding_source']) && !empty($_REQUEST['funding_source'])) {
	$funding_source = $_REQUEST['funding_source'];
    $objStockBatch->funding_source = $funding_source;
}

$data = $objStockBatch->search($product, $batch_no, $ref_no, $status);
?>
<?php include "../plmis_inc/common/_header.php"; ?>
<style>
    input[type="radio"]
    {
        margin-top:0px !important;	
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

                        <div class="widget">
                            <div class="widget-head">
                                <h3 class="heading">Batch Management</h3>
                            </div>
                            <div class="widget-body">
                                <form method="POST" name="batch_search" action="" >
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <!-- Widget -->
                                                <div class="widget row">

                                                    <!-- Widget heading -->
                                                    <div class="widget-head">
                                                        <h4 class="heading">Product</h4>
                                                    </div>
                                                    <!-- // Widget heading END -->

                                                    <div class="widget-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <select name="product" id="product" class="form-control input-small">
                                                                    <?php
                                                                    echo "<option value=''>Select</option>";
                                                                    if (mysql_num_rows($items) > 0) {
                                                                        while ($row = mysql_fetch_object($items)) {
                                                                            ?>
                                                                            <option value="<?php echo $row->itm_id; ?>" <?php if ($row->itm_id == $product) { ?>selected=""<?php } ?>><?php echo $row->itm_name ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div style="float:right; <?php echo (!empty($product)) ? 'display:none' : 'display:block'; ?>" id="printSummary"><button type="button" class="btn btn-primary input-small" onClick="window.open('print_batch_management.php?type=1', '_blank', 'scrollbars=1,width=860,height=595');">Summary</button></div>
                                                            </div>


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <!-- Widget -->
                                                <div class="widget">

                                                    <!-- Widget heading -->
                                                    <div class="widget-head">
                                                        <h4 class="heading">Status</h4>
                                                    </div>
                                                    <!-- // Widget heading END -->

                                                    <div class="widget-body" style="margin-left:20px;">
                                                        <label class="radio">
                                                            <input type="radio" class="radio" name="status" value="1" <?php if ($status == 1) { ?>checked="" <?php } ?> />
                                                            Running
                                                        </label><br/>
                                                        <label class="radio">
                                                            <input type="radio" class="radio" name="status" value="2" <?php if ($status == 2) { ?>checked="" <?php } ?> />
                                                            Stacked
                                                        </label><br/>
                                                        <label class="radio">
                                                            <input type="radio" class="radio" name="status" value="3" <?php if ($status == 3) { ?>checked="" <?php } ?> />
                                                            Finished
                                                        </label><br/>
                                                        <label class="radio">
                                                            <input type="radio" class="radio" name="status" value="4" <?php if ($status == '4') { ?>checked="" <?php } ?>/>
                                                            Total (Running + Stacked)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12">

                                                <!-- // Widget END -->
                                                <div class="col-md-2">
                                                    <!-- Group -->
                                                    <div class="form-group">
                                                        <label for="firstname">Batch No</label>
                                                        <input class="form-control input-small" id="batch_no" name="batch_no" type="text" value="<?php echo $batch_no; ?>" />
                                                    </div>
                                                    <!-- // Group END -->
                                                </div>
                                                <div class="col-md-2">
                                                    <!-- Group -->
                                                    <div class="form-group">
                                                        <label for="firstname">Ref No</label>
                                                        <input class="form-control input-small" id="ref_no" name="ref_no" type="text" value="<?php echo $ref_no; ?>" />
                                                    </div>
                                                    <!-- // Group END -->
                                                </div>
                                            <?php if( $_SESSION['wh_id'] == 123 ){?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="firstname">
                                                            Funding Source
                                                        </label>
                                                        <select name="funding_source" id="funding_source" class="form-control input-medium" <?php if (!empty($funding_source) && !empty($TranNo)) { ?>disabled="" <?php } ?>>
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (mysql_num_rows($warehouses) > 0) {
                                                                while ($row = mysql_fetch_object($warehouses)) {
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
                                                <div class="col-md-2">
                                                    <!-- Group -->
                                                    <div class="form-group" style="margin-top: 23px;">
                                                        <button class="btn btn-primary input-small" id="search" name="search" type="submit" > <i></i>Search </button>
                                                    </div>
                                                    <!-- // Group END -->
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="vaccine-detail" <?php if ($flag == true) { ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?>>
                                                <!-- Widget -->
                                                <?php
                                                if (isset($product) && !empty($product)) {
                                                    $result = $objStockBatch->find_by_item($product);
                                                    (int) $RunningVials = $result->RunningQty;
                                                    (int) $StackedVials = $result->StackedQty;
                                                    (int) $FinishedVials = $result->FinishedQty;
                                                    (int) $total = $RunningVials + $StackedVials + $FinishedVials;
                                                } else {
                                                    $RunningVials = 0;
                                                    $StackedVials = 0;
                                                    $FinishedVials = 0;
                                                    $total = 0;
                                                }
                                                ?>
                                                <div class="widget row-fluid" data-toggle="collapse-widget"  id="batch_detail_ajax">

                                                    <!-- Widget heading -->
                                                    <div class="widget-head">
                                                        <h4 class="heading"><?php echo $result->itm_name; ?></h4>
                                                    </div>
                                                    <!-- // Widget heading END -->

                                                    <div class="widget-body">
                                                        <div class="col-md-4">
                                                            <p><b>Batch Status</b></p>
                                                            <p>Running</p>
                                                            <p>Stacked</p>
                                                            <p>Finished</p>
                                                            <p><b>Total</b></p>
                                                        </div>
                                                        <div class="col-md-4 center">
                                                            <p><b>No of Batches</b></p>
                                                            <p style="text-align:right" id="running"><?php echo (!empty($result->running) ? $result->running : 0 ); ?></p>
                                                            <p style="text-align:right" id="stacked"><?php echo (!empty($result->stacked) ? $result->stacked : 0 ); ?></p>
                                                            <p style="text-align:right"id="finished"><?php echo (!empty($result->finished) ? $result->finished : 0 ); ?></p>
                                                            <p style="text-align:right" id="total"><b><?php echo ($result->running + $result->stacked + $result->finished); ?></b></p>
                                                        </div>
                                                        <div class="col-md-4 center">
                                                            <p style="text-align:right"><b>Quantity <?php if ($result->fkUnitID == 2) { ?>in Doses<?php } else if ($result->fkUnitID == 3) { ?>in Pcs<?php } ?></b></p>
                                                            <p style="text-align:right"><?php echo number_format($RunningVials); ?></p>
                                                            <p style="text-align:right"><?php echo number_format($StackedVials); ?></p>
                                                            <p style="text-align:right"><?php echo number_format($FinishedVials); ?></p>
                                                            <p style="text-align:right" id="total"><b><?php echo number_format($total); ?></b></p>
                                                        </div>
                                                        <?php if ($result->fkUnitID == 2) { ?>
                                                            <div class="col-md-4 center">
                                                                <p style="text-align:right"><b>Quantity in Vials</b>
                                                                <p style="text-align:right"><?php echo number_format($result->RunningQty); ?></p>
                                                                <p style="text-align:right"><?php echo number_format($result->StackedQty); ?></p>
                                                                <p style="text-align:right"><?php echo number_format($result->FinishedQty); ?></p>
                                                                <p style="text-align:right" id="total"><b><?php echo number_format($result->RunningQty + $result->StackedQty + $result->FinishedQty); ?></b></p>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div style="clear:both;"></div>
                                                </div>
                                                <!-- Widget -->
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- // Row END -->
                        <!-- Widget -->
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Batch List</h3>
                            </div>

                            <!-- // Widget heading END -->

                            <div class="widget-body">

                                <!-- Table -->
                                <!-- Table -->
                                <table class="dynamicTable table table-striped table-bordered table-condensed">

                                    <!-- Table heading -->
                                    <thead>
                                        <tr>
                                            <th width="60">Sr. No.</th>
                                            <th>Product</th>
                                            <?php if ( $_SESSION['wh_id'] == 123 ){?>
                                            <th>Funding Source</th>
                                            <?php }?>
                                            <th>Batch No.</th>
                                            <th>Expiry Date</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Cartons</th>
                                            <th>Status</th>
                                            <?php if ($_SESSION['UserType'] != 'UT-007'){?>
                                            <th class="center" width="260">Action</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <!-- // Table heading END -->

                                    <!-- Table body -->
                                    <tbody>
                                        <?php
                                        if (!empty($data) && mysql_num_rows($data) > 0) {
                                            $i = 1;
                                            while ($row = mysql_fetch_object($data)) {
                                                $batchArray[] = $row;
                                                ?>
                                                <!-- Table row -->
                                                <tr class="gradeX">
                                                    <td class="center"><?php echo $i; ?></td>
                                                    <td><?php echo $row->itm_name; ?></td>
		                                            <?php if ( $_SESSION['wh_id'] == 123 ){?>
                                                    <td><?php echo $row->funding_source; ?></td>
                                                    <?php }?>
                                                    <td><?php echo $row->batch_no; ?></td>
                                                    <td class="editableSingle expiry id<?php echo $row->batch_id; ?>"><?php echo date("d/m/Y", strtotime($row->batch_expiry)); ?></td>
                                                    <td class="right"><?php echo number_format($row->BatchQty); ?></td>
                                                    <td class="right"><?php echo $row->UnitType ?></td>
                                                    <td class="right">
													<?php
														$cartonQty = $row->BatchQty / $row->qty_carton;
														echo (floor($cartonQty) != $cartonQty) ? number_format($cartonQty, 2) : number_format($cartonQty);
													?>
                                                    </td>
                                                    <td id="batch<?php echo $row->batch_id; ?>-status"> &nbsp; <?php echo $row->status; ?></td>
                                            		<?php if ($_SESSION['UserType'] != 'UT-007'){?>
                                                    <td class="span3">
                                                        <input type="hidden" name="status" id="batch<?php echo $row->batch_id; ?>_status" value="<?php echo $row->status; ?>" />
                                                        <input type="hidden" name="batch_id" id="batch<?php echo $row->batch_id; ?>_id" value="<?php echo $row->batch_id; ?>" />
                                                        <?php
                                                        if ($row->status == 'Finished') {
                                                            echo '';
                                                        } else {
                                                            ?>
                                                            <button class="btn input-sm input-small <?php echo ($row->status == 'Stacked') ? "btn-success" : "btn-danger"; ?> btn-mini" onClick="makeIt(this.id)" id="batch<?php echo $row->batch_id; ?>-makeit">
                                                                Make it <span id="batch<?php echo $row->batch_id; ?>-button">
                                                                    <?php echo ($row->status == 'Stacked') ? "Running" : "Stacked"; ?></span>
                                                                </span>
                                                            </button>
                                                            <?php
                                                            $var = base64_encode($row->itm_name . '|' . $row->batch_id . '|' . $row->batch_no . '|' . date("d/m/Y", strtotime($row->batch_expiry)));
                                                            ?>
                                                        <a class="btn btn-info input-sm" onClick="loadPlacementInfo('<?php echo $var;?>');" data-target="#modal-ajax-placement-info" data-toggle="modal"> Placement Info</a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php }?>
                                                </tr>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>
                                        <!-- // Table row END -->

                                    </tbody>
                                    <!-- // Table body END -->

                                </table>
                                <!-- // Table END -->
                                <div style="float:right;">
                                    <button id="print_vaccine_placement" type="button" class="btn btn-warning" onClick="window.open('print_batch_management.php', '_blank', 'scrollbars=1,width=860,height=595');">
                                        Print
                                    </button>
                                </div>
                                <div style="clear:both"></div>
                            </div>
                        </div>
                        <?php $_SESSION['batch_management'] = $batchArray; ?>
                    </div>
            <div class="modal fade" id="modal-ajax-placement-info" tabindex="-1" role="basic" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3>Placement Information</h3>
                        </div>
                        <div class="modal-body" id="modal-body-contents"></div>
                    </div>
                </div>
            </div>
                    
                    <!--<div class="modal fade" id="ajax_placement_info" role="basic" aria-hidden="true">
                        <div class="page-loading page-loading-boxed">
                            <img src="../../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                            <span>&nbsp;&nbsp;Loading... </span>
                        </div>
                        <div class="modal-dialog" style="margin-top: 60px;">
                            <div class="modal-content">
                            </div>
                        </div>
                    </div>
                     // Content END -->

                </div>
            </div>
        </div>
    </div>

    <?php include "../plmis_inc/common/footer.php"; ?>

    <script src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit_date.js"></script>
    <script src="<?php echo SITE_URL; ?>plmis_js/batch_management.js"></script>

</body>
<!-- END BODY -->
</html>