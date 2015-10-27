<?php
include("../html/adminhtml.inc.php");
//include "../plmis_inc/common/top.php";
include "../plmis_inc/common/top_im.php";
include("Includes/AllClasses.php");

$sCriteria = array();
$number = '';
$date_from = '';
$date_to = '';
$searchby = '';
$warehouse = '';
$product = '';
$manufacturer = '';
if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {

    if (!empty($_REQUEST['searchby']) && !empty($_REQUEST['number'])) {
        $searchby = $_REQUEST['searchby'];
        $number = $_REQUEST['number'];
        switch ($searchby) {
            case 1:
                $objStockMaster->TranNo = $number;
                $sCriteria[0]['Transaction No.'] = $number;
                break;
            case 2:
                $objStockMaster->TranRef = $number;
                $sCriteria[0]['Transaction Reference'] = $number;
                break;
            case 3:
                $objStockMaster->batch_no = $number;
                $sCriteria[0]['Batch No.'] = $number;
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
        $sCriteria[0]['Receive From'] = $wh['wh_name'];
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
    if (isset($_REQUEST['manufacturer']) && !empty($_REQUEST['manufacturer'])) {
        $manufacturer = $_REQUEST['manufacturer'];
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

    $objStockMaster->WHIDFrom = (!empty($warehouse)) ? $warehouse : '';
    $objStockMaster->item_id = (!empty($product)) ? $product : '';
    $objStockMaster->manufacturer = (!empty($manufacturer)) ? $manufacturer : '';
    $objStockMaster->fromDate = (!empty($date_from)) ? dateToDbFormat($date_from) : '';
    $objStockMaster->toDate = (!empty($date_to)) ? dateToDbFormat($date_to) : '';
    $_SESSION['sCriteria'] = $sCriteria;
} else {
    unset($_SESSION['sCriteria']);
    $date_from = date('01' . '/m/Y');
    $date_to = date('d/m/Y');
    $objStockMaster->fromDate = dateToDbFormat($date_from);
    $objStockMaster->toDate = dateToDbFormat($date_to);
}


$wh_id = $_SESSION['wh_id'];
$result = $objStockMaster->StockSearch(1, $wh_id);

$title = "Stock Receive";
//include('../template/header-top.php');
//include('../template/header-bottom.php');
include('../' . $_SESSION['menu']);

$warehouses = $objwarehouse->GetUserReceiveFromWH($wh_id);
$items = $objManageItem->GetAllManageItem();
if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {

    $manufacturers = $manufacturer_product = $objstk->GetAllManufacturersUpdate($_REQUEST['product']);
}
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
                                <h3 class="heading">Stock Receive Search</h3>
                            </div>
                            <div class="widget-body">
                                <form method="POST" name="batch_search" action="" >
                                    <!-- Row -->
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label">Search by</label>
                                                    <div class="controls">
                                                        <select name="searchby" id="searchby" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <option value="1" <?php if ($searchby == 1) { ?> selected <?php } ?>>Receive No</option>
                                                            <option value="2" <?php if ($searchby == 2) { ?> selected <?php } ?>>Receive Ref</option>
                                                            <option value="3" <?php if ($searchby == 3) { ?> selected <?php } ?>>Batch No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="number">&nbsp;</label>
                                                    <div class="controls">
                                                        <input class="form-control input-medium" id="number" name="number" type="text" value="<?php echo $number; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="warehouse">Warehouse/Supplier</label>
                                                    <div class="controls">
                                                        <select name="warehouse" id="warehouse" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (mysql_num_rows($warehouses) > 0) {
                                                                while ($row = mysql_fetch_object($warehouses)) {
                                                                    ?>
                                                                    <option value="<?php echo $row->wh_id; ?>" <?php if ($warehouse == $row->wh_id) { ?> selected="" <?php } ?>><?php echo $row->wh_name; ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="product">Product</label>
                                                    <div class="controls">
                                                        <select name="product" id="product" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if (mysql_num_rows($items) > 0) {
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
                                            </div>
                                        </div>
                                        <div class="col-md-12"> 
                                            <!-- Group -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Date From</label>
                                                    <input type="text" class="form-control input-medium" name="date_from" readonly id="date_from" value="<?php echo $date_from; ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Date To</label>
                                                    <input type="text" class="form-control input-medium" name="date_to"  readonly="" id="date_to" value="<?php echo $date_to; ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-6 right">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;</label>
                                                    <div class="form-group">
                                                        <button type="submit" name="search" value="search" class="btn btn-primary">Search</button>
                                                        <button type="reset" class="btn btn-info" id="reset">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Widget -->
                        <div class="widget" data-toggle="collapse-widget"> 

                            <!-- Widget heading -->
                            <div class="widget-head">
                                <h4 class="heading">Receive Search</h4>
                            </div>

                            <!-- // Widget heading END -->

                            <div class="widget-body"> 

                                <!-- Table --> 
                                <!-- Table -->
                                <table class="receivesearch table table-striped table-bordered table-condensed">

                                    <!-- Table heading -->
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>                                            
                                            <th>Receive No</th>                                          
                                            <th>Ref No</th>
                                            <th>Receive From</th>
                                            <th>Receive Date</th>
<!--                                            <th>Product</th>
                                            <th>Batch No</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Expiry Date</th>-->
                                        </tr>
                                    </thead>
                                    <!-- // Table heading END --> 

                                    <!-- Table body -->
                                    <tbody>

                                        <!-- Table row -->
                                        <?php
                                        $m_res = array();
                                        $i = 1;
                                        if ($result != FALSE) :
                                            while ($row = mysql_fetch_object($result)) :
                                                $m_res[] = $row;
                                                ?>
                                                <tr class="gradeX">
                                                    <td class="center"><?php echo $i; ?></td>                                                   
                                                    <td><a onClick="window.open('printReceive.php?id=<?php echo $row->PkStockID; ?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $row->TranNo; ?></a></td>
                                                    <td><?php echo '&nbsp;' . $row->TranRef; ?></td>
                                                    <td><?php echo $row->wh_name; ?></td>
                                                    <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
        <!--                                                    <td><?php echo $row->itm_name; ?></td>
                                                   <td><?php echo $row->batch_no; ?></td>
                                                   <td style="text-align:right;"><?php echo number_format($row->Qty); ?></td>
                                                   <td><?php echo $row->itm_type; ?></td>
                                                   <td><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?></td>-->
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
                                <?php $_SESSION['stock_rec'] = $m_res; ?>
                                <?php if ($result != FALSE) { ?>
                                    <div class="right" style="margin-top:10px !important;">
                                        <div style="float:right;">
                                            <button id="print_stock" type="button" class="btn btn-warning">Print</button>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>


                        <!-- // Content END -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include "../plmis_inc/common/footer.php"; ?>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockreceive.js"></script>
    
	<?php
    unset($_SESSION['stock_id']);
    if (!empty($_REQUEST['s']) && $_REQUEST['s'] == 't') {
        ?>
        <script type="text/javascript">
            var self = $('[data-toggle="notyfy"]');
            notyfy({
                force: true,
                text: 'Data has been deleted successfully!',
                type: 'success',
                layout: self.data('layout')
            });
        </script>
    <?php } ?>

    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
