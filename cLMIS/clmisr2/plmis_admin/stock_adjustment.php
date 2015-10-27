<?php
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/top_im.php";
//include "../plmis_inc/common/top.php";

include("Includes/AllClasses.php");

$title = "New Cold Chain Asset";
include('../' . $_SESSION['menu']);

if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
    if (isset($_REQUEST['adjustment_no']) && !empty($_REQUEST['adjustment_no'])) {
        $adjustment_no = $_REQUEST['adjustment_no'];
    }
    if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {
        $type = $_REQUEST['type'];
    }
    if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
        $product = $_REQUEST['product'];
    }
    if (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) {
        $date_from = $_REQUEST['date_from'];
    }
    if (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to'])) {
        $date_to = $_REQUEST['date_to'];
    }
} else {
    $date_from = date('01' . '/m/Y');
    $date_to = date('d/m/Y');
}

$items = $objManageItem->GetAllProduct();
$types = $objTransType->getAdjusmentTypes();

$objStockMaster->WHIDFrom = $_SESSION['wh_id'];
$objStockMaster->TranNo = (!empty($adjustment_no)) ? $adjustment_no : '';
$objStockMaster->TranTypeID = (!empty($type)) ? $type : '';
$objStockMaster->WHIDTo = $_SESSION['wh_id'];
$objStockMaster->item_id = (!empty($product)) ? $product : '';
$objStockMaster->fromDate = (!empty($date_from)) ? dateToDbFormat($date_from) : '';
$objStockMaster->toDate = (!empty($date_to)) ? dateToDbFormat($date_to) : '';
$adjustment_list = $objStockMaster->StockAdjustmentSearch();
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
                                <h3 class="heading">Stock Adjustment Search</h3>
                            </div>
                            <!-- // Widget Heading END -->

                            <div class="widget-body">
                                <form method="POST" name="batch_search" action="" >
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-12"></div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group ">
                                                    <label>Adjustment No.</label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" id="adjustment_no" name="adjustment_no" value="<?php echo (!empty($_POST['adjustment_no']) ? $_POST['adjustment_no'] : ''); ?>" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group ">
                                                    <label>Adjustment Type</label>
                                                    <div class="controls">
                                                        <select name="type" id="type" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (count($types) > 0) {
                                                                foreach ($types as $row) {
                                                                    $sel = '';
                                                                    if ($_POST['type']) {
                                                                        if ($_POST['type'] == $row->trans_id) {
                                                                            $sel = 'selected';
                                                                        }
                                                                    }
                                                                    echo '<option value="' . $row->trans_id . '" ' . $sel . ' >' . $row->trans_type . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group ">
                                                    <label>Product</label>
                                                    <div class="controls">
                                                        <select name="product" id="product" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (count($items) > 0) {
                                                                foreach ($items as $row) {
                                                                    $sel = '';
                                                                    if ($_POST['product']) {
                                                                        if ($_POST['product'] == $row['id']) {
                                                                            $sel = 'selected';
                                                                        }
                                                                    }
                                                                    echo '<option value="' . $row['id'] . '" ' . $sel . ' >' . $row['name'] . '</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3" style="padding-top: 12px;">
                                                <div class="control-group ">
                                                    <label>Date From</label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" readonly id="date_from" name="date_from" value="<?php echo (!empty($date_from) ? $date_from : ''); ?>" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding-top: 12px;">
                                                <div class="control-group ">
                                                    <label>Date To</label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" readonly id="date_to" name="date_to" value="<?php echo (!empty($date_to) ? $date_to : ''); ?>" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="col-md-3">
                                                <div class="control-group center">
                                                    <label class="control-label">&nbsp;</label>
                                                    <div class="controls right">
                                                        <button class="btn btn-primary" type="submit" name="search" value="Search">Search</button>
                                                        <button class="btn btn-info" type="reset" id="reset">Reset</button>
                                                    </div>
                                                </div>
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
                                <h3 class="heading">Search Results</h3>
                            </div>
                            <!-- // Widget heading END -->

                            <div class="widget-body">

                                <!-- Table -->
                                <!-- Table -->
                                <table class="dynamicTable table table-striped table-bordered table-condensed">

                                    <!-- Table heading -->
                                    <thead>
                                        <tr>
                                            <th width="8%">Date</th>
                                            <th>Adjustment No.</th>
<!--                                            <th>Ref. No.</th>-->
                                            <!-- <th>Product</th>
                                            <th>Batch No.</th>-->
                                            <!--<th>Quantity</th>-->
                                            <th>Adjustment Type</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    <!-- // Table heading END -->

                                    <!-- Table body -->
                                    <tbody>
                                        <!-- Table row -->
                                        <?php
                                        $i = 1;
                                        $adjustArray = array();
                                        if ($adjustment_list != FALSE) :
                                            while ($row = mysql_fetch_object($adjustment_list)) :
                                                $adjustArray[] = $row;
                                                ?>
                                                <tr class="gradeX">
                                                    <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
                                                   <td><a  onclick="window.open('printAdjustment.php?id=<?php echo $row->PkStockID; ?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $row->TranNo; ?></a></td>                                                                                              
                                                    <td><?php echo $row->trans_type; ?></td>
                                                    <td><?php echo (!empty($row->ReceivedRemarks) ? $row->ReceivedRemarks : '&nbsp;'); ?></td>
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
                                <div class="right">
                                    <button id="print_stock" class="btn btn-warning" type="button">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- // Content END -->
            <?php
            $_SESSION['adjustArray'] = $adjustArray;
			// include('../template/footer-top.php'); 
            ?>
            <!-- // Content END -->
        </div>
    </div>
    <?php include "../plmis_inc/common/footer.php"; ?>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stock_adjustment.js"></script>
    <?php include "../plmis_inc/common/footer_template.php"; ?>
    <!-- END FOOTER -->

</body>
<!-- END BODY -->
</html>