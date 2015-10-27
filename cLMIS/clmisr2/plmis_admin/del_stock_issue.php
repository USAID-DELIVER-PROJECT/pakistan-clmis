<?php
/// Stock Issue need to update
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");
$sCriteria = array();
$number = '';
$date_from = '';
$date_to = '';
$searchby = '';
$warehouse = '';
$product = '';

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
												tbl_warehouse.wh_id = ".$warehouse." "));
        $sCriteria[0]['Issued To'] = $wh['wh_name'];
    }
    if (isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
        $product = $_REQUEST['product'];
		$pro = mysql_fetch_array(mysql_query("SELECT
													itminfo_tab.itm_name
												FROM
													itminfo_tab
												WHERE
													itminfo_tab.itm_id = ".$product.""));
        $sCriteria[0]['Product'] = $pro['itm_name'];
    }
    if (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) {
        $date_from = $_REQUEST['date_from'];
		$dateArr = explode('/', $date_from);
        $sCriteria[0]['From'] = date('d/m/y', strtotime($dateArr[0].'-'.$dateArr[1].'-'.$dateArr[2]));
    }
    if (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to'])) {
        $date_to = $_REQUEST['date_to'];
		$dateArr = explode('/', $date_to);
        $sCriteria[0]['To'] = date('d/m/y', strtotime($dateArr[0].'-'.$dateArr[1].'-'.$dateArr[2]));
    }

    $objStockMaster->WHIDTo = (!empty($warehouse)) ? $warehouse : '';
    $objStockMaster->item_id = (!empty($product)) ? $product : '';
    $objStockMaster->fromDate = (!empty($date_from)) ? dateToDbFormat($date_from) : '';
    $objStockMaster->toDate = (!empty($date_to)) ? dateToDbFormat($date_to) : '';
}
/*else {
    $date_from = date('01' . '/m/Y');
    $date_to = date('d/m/Y');
    $objStockMaster->fromDate = dateToDbFormat($date_from);
    $objStockMaster->toDate = dateToDbFormat($date_to);
}*/
//$wh_id = $_SESSION['wh_id'];
$result = $objStockMaster->DelStockIssueSearch(2);

$title = "Stock Receive";
include('../template/header-top.php');
include('../template/header-bottom.php');
include('../' . $_SESSION['menu']);

$warehouses = $objwarehouse->DelGetUserIssueToWH();
$items = $objManageItem->GetAllManageItem();
?>
<!-- Content -->
<div id="content">
    <h3 class="heading-mosaic">Deleted Stock Issue</h3>
    <div class="innerLR">
        <div class="widget">

            <div class="widget-body">
                <form method="POST" name="batch_search" action="" >
                    <!-- Row -->
                    <div class="row-fluid">
                        <div class="span12"></div>
                        <div class="span12">
                            <div class="span3">
                                <div class="control-group">
                                    <label class="control-label" for="firstname">Search by</label>
                                    <div class="controls">
                                        <select name="searchby" id="searchby" class="span10">
                                            <option value="">Select</option>
                                            <option value="1" <?php if ($searchby == 1) { ?> selected <?php } ?>>Issue No</option>
                                            <option value="2" <?php if ($searchby == 2) { ?> selected <?php } ?>>Issue Ref</option>
                                            <option value="3" <?php if ($searchby == 3) { ?> selected <?php } ?>>Batch No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <label class="control-label" for="number">&nbsp;</label>
                                    <div class="controls">
                                        <input class="span10" id="number" name="number" type="text" value="<?php echo $number; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="span12">
                            <div class="span3">
                                <div class="control-group">
                                    <label class="control-label" for="warehouse">Warehouse/Supplier</label>
                                    <div class="controls">
                                        <select name="warehouse" id="warehouse" class="span10">
                                            <option value="">Select</option>
											<?php
                                            if ($warehouses != FALSE) {
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
                            <div class="span3">
                                <div class="control-group">
                                    <label class="control-label" for="firstname">Product</label>
                                    <div class="controls">
                                        <select name="product" id="product" class="span10">
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
                            </div>
                        </div>
                        <div class="span12">
                            <!-- Group -->
                            <div class="span3">
                                <label class="control-label" for="firstname">Date From</label>
                                <div class="controls">
                                    <input type="text" readonly="" class="span10" name="date_from" id="date_from" value="<?php echo $date_from; ?>"/>
                                </div>
                            </div>
                            <div class="span3">
                                <label class="control-label" for="firstname">Date To</label>
                                <div class="controls">
                                    <input type="text" readonly="" class="span10" name="date_to" id="date_to" value="<?php echo $date_to; ?>"/>
                                </div>
                            </div>
                            <div class="span3">
                                <label class="control-label" for="firstname">&nbsp</label>
                                <div class="controls">
                                    <button type="submit" name="search" value="search" class="btn btn-success">Search</button>
                                    <button type="reset" class="btn btn-info">Reset</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>      
        </div>
        <!-- Widget -->
        <div class="widget">

            <!-- Widget heading -->
            <div class="widget-head">
                <h4 class="heading">Deleted Issue Search</h4>
            </div>
            <!-- // Widget heading END -->

            <div class="widget-body">

                <!-- Table -->
                <!-- Table -->
                <table class="dynamicTable table table-striped table-bordered table-condensed">

                    <!-- Table heading -->
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Issue No.</th>
                            <th>Issue To.</th>
                            <th>Product</th>
                            <th>Batch No.</th>						
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Expiry Date</th>
                            <th>Deleted On</th>
                            <th>Deleted By</th>
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
                            while ($row = mysql_fetch_object($result)) :
                                $stockArray[] = $row;
                                $dis = 'Pick Order';
                                $linkk = 'coldChainQtyPick.php';
                                if ($row->sumIssueQty == abs($row->Qty)) {
                                    $dis = 'Picked';
                                    $linkk = 'coldChainQtyPickHistroy.php';
                                }
                                ?>
                                <tr class="gradeX">
                                    <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?></td>
                                    <td><?php echo $row->TranNo; ?></td>
                                    <td><?php echo $row->wh_name; ?></td>
                                    <td><?php echo $row->itm_name; ?></td>
                                    <td><?php echo $row->batch_no; ?></td>						
                                    <td style="text-align:right;"><?php echo number_format(abs($row->Qty)); ?></td>
                                    <td><?php echo $row->UnitType; ?></td>
                                    <td><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?></td>
                                    <td><?php echo $row->DeletedOn; ?></td>
                                    <td><?php echo $row->sysusr_name; ?></td>
                                </tr>
        <?php $i++;
    endwhile;
endif; ?>
                        <!-- // Table row END -->
                    </tbody>
                    <!-- // Table body END -->
                </table>
                <!-- // Table END -->
                
            </div>
        </div>
    </div>
</div>
<!-- // Content END -->
<?php
$_SESSION['stockArray'] = $stockArray;
$_SESSION['sCriteria'] = $sCriteria;
/* echo '<pre>';
  print_r($_SESSION);die; */
include('../template/footer-top.php');?>
<script src="<?php echo SITE_DOMAIN; ?>plmis_js/dataentry/stockissue.js"></script>
<?php include('../template/footer-bottom.php'); ?>