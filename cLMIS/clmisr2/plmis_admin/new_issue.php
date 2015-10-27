<?php
include("../html/adminhtml.inc.php");
include("Includes/AllClasses.php");

$title = "New Issue";
include('../' . $_SESSION['menu']);
$TranNo = '';
$stock_id = 0;
$userid = $_SESSION['userid'];
$wh_id = $_SESSION['wh_id'];
$stockArray = array();

$tempstocksIssue = $objStockMaster->GetTempStockIssue($userid, $wh_id, 2);
if ($tempstocksIssue != FALSE) {
    $result = mysql_fetch_object($tempstocksIssue);
    $stock_id = $result->PkStockID;
    $TranDate = date('d/m/Y', strtotime($result->TranDate));
    $funding_source = $result->funding_source;
    $TranNo = $result->TranNo;
    $TranRef = $result->TranRef;
    $wh_name = $result->wh_name;
    $whouse_id = $result->WHIDTo;
    $issued_by = $result->issued_by;
    $whTo = $result->WHIDTo;
} else {
    $TranDate = date("d/m/Y");
    $wh_name = '';
    $TranRef = '';
}
$warehouses = $warehouses1 = $objwarehouse->GetUserWarehouses();
$tempstocks = $objStockMaster->GetTempStocksIssueList($userid, $wh_id, 2);
$items = $objManageItem->GetAllWHProduct();
//$units = $objItemUnits->GetAllItemUnits();

// Query to get the last transaction information of the warehouse

unset($_SESSION['lastTransStk']);
unset($_SESSION['lastTransWH']);
unset($_SESSION['lastTransOffice']);
unset($_SESSION['lastTransProv']);
unset($_SESSION['lastTransDist']);

if ($wh_id == 123) // If central warehouse
{
	$qry = "SELECT
				tbl_stock_master.WHIDTo,
				tbl_warehouse.stkofficeid,
				tbl_warehouse.stkid,
				stakeholder.lvl,
				tbl_warehouse.prov_id,
				tbl_warehouse.dist_id
			FROM
				tbl_stock_master
			INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
				tbl_stock_master.WHIDFrom = $wh_id
			AND tbl_stock_master.TranTypeID = 2
			ORDER BY
				tbl_stock_master.PkStockID DESC
			LIMIT 1";
	$row = mysql_fetch_array(mysql_query($qry));
	$_SESSION['lastTransStk'] = $row['stkid'];
	$_SESSION['lastTransWH'] = $row['WHIDTo'];
	$_SESSION['lastTransOffice'] = $row['lvl'];
	$_SESSION['lastTransProv'] = $row['prov_id'];
	$_SESSION['lastTransDist'] = $row['dist_id'];
}
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">New Issue</h3>
                            </div>

                            <div class="widget-body">
                                <?php if (isset($_GET['warehouse']) && $_GET['warehouse'] == 1) { ?>
                                    <div class="alert alert-danger">
                                        <button data-dismiss="alert" class="close" type="button"> X</button>
                                        Please select warehouse!
                                    </div>
                                <?php } ?>
                                <form method="POST" name="new_issue_form" id="new_issue_form" action="new_issue_action.php">
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Issue No
                                                    </label>

                                                    <div class="controls">
                                                        <input class="form-control input-medium" id="issue_no" name="issue_no" type="text" disabled=""
                                                               value="<?php echo $TranNo; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Date
                                                    </label>

                                                    <div class="controls">
                                                        <input class="form-control input-medium" id="issue_date" name="issue_date" required type="text" value="<?php echo $TranDate; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Issue Reference
                                                    </label>

                                                    <div class="controls">
                                                        <input class="form-control input-medium" id="issue_ref" name="issue_ref" type="text"
                                                               value="<?php echo $TranRef; ?>" <?php
                                                               if ($TranRef != '') {
                                                                   ?> disabled="" <?php }
                                                               ?> />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Issued By
                                                    </label>

                                                    <div class="controls">
                                                    	<select class="form-control input-medium" name="issued_by" id="issued_by">
                                                        	<option value="">Select</option>
                                                            <?php 
															$qry = "SELECT
																		list_detail.pk_id,
																		list_detail.list_value
																	FROM
																		list_detail
																	WHERE
																		list_detail.list_master_id = 21
																	ORDER BY
																	list_detail.list_value ASC";
															$qryRes = mysql_query($qry);
															while ( $row = mysql_fetch_array($qryRes) )
															{
																$sel = ($issued_by == $row['pk_id']) ? 'selected="selected"' : '';
																echo "<option value=\"$row[pk_id]\" $sel>$row[list_value]</option>";
															}
															?>
                                                        </select>
                                                    	<?php ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($wh_id == 123){?>
                                        <input type="hidden" id="showSelection" value="<?php echo $_SESSION['lastTransStk'];?>" />
                                        <?php }?>
                                        <?php
                                        if ($wh_name == '') {
                                            ?>
                                            <?php
                                            $button = 'true';
                                            // $js='';

                                            $user_lvl = (!empty($_SESSION['stkofficeid']) ? $_SESSION['stkofficeid'] : '');
                                            switch ($user_lvl) {
                                                case 1:
                                                case 2:
                                                case 3:
                                                    include("levelcombos_all_levels.php");
                                                    $js = 'levelcombos_all_levels.js';
                                                    break;
                                                case 4:
                                                    include("levelcombos.php");
                                                    $js = 'levelcombos.js';
                                                    break;
                                            }
                                            ?>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <input class="form-control input-medium" id="recipient" name="" type="text" disabled="" value="<?php echo $wh_name; ?>"/>
                                                            <input class="form-control input-medium" id="warehouse" name="warehouse" type="hidden" value="<?php echo $whouse_id; ?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                        <div class="col-md-12">
                                            <?php if( $_SESSION['wh_id'] == 123 ){?>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Funding Source <span class="red">*</span>
                                                    </label>
                                                    <div class="controls">
                                                        <select name="funding_source" id="funding_source" required="true" class="form-control input-medium" <?php if (!empty($funding_source) && !empty($TranNo)) { ?>disabled="" <?php } ?>>
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
                                            </div>
                                            <?php }?>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Product <span class="red">*</span>
                                                    </label>

                                                    <div class="controls">
                                                        <select name="product" id="product" class="form-control input-medium" required="true">
                                                            <option value="">
                                                                Select
                                                            </option>
                                                            <?php
                                                            foreach ($items as $item) {
                                                                echo "<option value=" . $item['id'] . ">" . $item['name'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <label class="control-label" for="firstname">
                                                        Batch <span class="red">*</span>
                                                    </label>

                                                    <div class="controls">
                                                        <select name="batch" id="batch" class="form-control input-medium" required="true">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label" for="firstname">
                                                    Quantity <span class="red">*</span>
                                                </label>

                                                <div class="controls">
                                                    <input type="text" class="form-control input-medium num" name="qty" id="qty" autocomplete="off" required/>
                                                    <span id="product-unit"></span>
                                                    <span id="product-unit1" style="display:none;"></span>
                                                </div>
                                            </div>
                                            <div class="" id="itembatches">
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label class="control-label" for="firstname">
                                                            Available
                                                        </label>

                                                        <div class="controls">
                                                            <input type="text" class="form-control input-medium num" name="available_qty" id="available_qty" readonly/>
                                                            <input type="hidden" value="" id="ava_qty" name="ava_qty" class="form-control input-medium">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2" id="expiry_div">
                                                    <div class="control-group">
                                                        <label class="control-label" for="firstname">
                                                            Expiry date
                                                        </label>

                                                        <div class="controls">
                                                            <input type="text" class="form-control input-medium" name="expiry_date" id="expiry_date" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <label class="control-label" for="firstname">
                                                    Comments (Max 300 Char)
                                                </label>
                                                <textarea name="comments" id="comments" maxlength="300" style="resize:none;" class="form-control input-medium"></textarea>
                                            </div>
                                            <div class="col-md-9 right" style="margin-top:35px;">
                                                <label class="control-label" for="firstname">
                                                    &nbsp;
                                                </label>

                                                <div class="controls">
                                                    <button type="submit" class="btn btn-primary" id="add_issue">
                                                        Add Issue
                                                    </button>
                                                    <button type="reset" class="btn btn-info">
                                                        Reset
                                                    </button>
                                                    <input type="hidden" name="trans_no" id="trans_no" value="<?php echo $TranNo; ?>"/>
                                                    <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id; ?>"/>
                                                    <input type="hidden" name="prov_id" id="prov_id"
                                                           value="<?php echo $_SESSION['prov_id']; ?>"/>
                                                    <input type="hidden" name="dist_id" id="prov_id"
                                                           value="<?php echo $_SESSION['dist_id']; ?>"/>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- // Row END -->
                        <?php if ($tempstocks != FALSE) : ?>
                            <!-- Widget -->
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">Issue List</h3>
                                </div>
                                <!-- // Widget heading END -->
                                <div class="widget-body" id="gridData">
                                    <!-- Table -->
                                    <!-- Table -->
                                    <table class="table table-striped table-bordered table-condensed" id="myTable">
                                        <!-- Table heading -->
                                        <thead>
                                            <tr bgcolor="#009C00" style="color:#FFF;">
                                                <th>Date</th>
                                                <th>Product</th>
                                                <th>Unit</th>
                                                <th>Issue To</th>
                                                <th class="span2">Quantity</th>
                                                <th>Cartons</th>
                                                <th class="span2">Batch</th>
                                                <th>Expiry Date</th>
                                                <th class="center" width="50">Action</th>
                                            </tr>
                                        </thead>
                                        <!-- // Table heading END -->

                                        <!-- Table body -->
                                        <tbody>
                                            <!-- Table row -->
                                            <?php
                                            $i = 1;
                                            $checksumVials = array();
                                            $checksumDoses = array();
                                            while ($row = mysql_fetch_object($tempstocks)) :
                                                $stockArray[] = $row;
                                                // Checksum
                                                $checksumVials[$row->itm_category][] = abs($row->Qty);
                                                $checksumDoses[$row->itm_category][] = abs($row->Qty) * $row->doses_per_unit;
                                                ?>
                                                <tr class="gradeX">
                                                    <td>
                                                        <?php echo date("d/m/y", strtotime($row->TranDate)); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row->itm_name; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row->UnitType; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row->wh_name; ?>
                                                    </td>
                                                    <td class="Qty id<?php echo $row->PkDetailID; ?> right">
                                                        <?php echo number_format(abs($row->Qty)); ?>
                                                    </td>
                                                    <td class="Qty id<?php echo $row->PkDetailID; ?> right">
                                                        <?php echo number_format(abs($row->Qty) / $row->qty_carton); ?>
                                                    </td>
                                                    <td class="Batch id<?php echo $row->PkDetailID; ?>"><!--editableSingle -->
                                                        <?php echo $row->batch_no; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo date("d/m/y", strtotime($row->batch_expiry)); ?>
                                                    </td>
                                                    <td class="center">
                                                        <span data-toggle="notyfy" id="<?php echo $row->PkDetailID; ?>" data-type="confirm" data-layout="top"><img class="cursor" src="<?php echo SITE_URL; ?>plmis_img/cross.gif" /></span>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endwhile;
                                            ?>
                                            <!-- // Table row END -->
                                        </tbody>
                                        <!-- // Table body END -->

                                    </table>
                                    <!-- // Table END -->
                                </div>
                            </div>

                            <!-- Widget -->
                            <div class="widget-body right">
                                <form name="receive_stock" id="receive_stock" action="new_issue_action.php" method="POST">
                                    <button id="print_issue" type="button" class="btn btn-warning">
                                        Print
                                    </button>
                                    <button type="submit" class="btn btn-primary"
                                            onclick="return confirm('Are you sure you want to save the list?');">
                                        Save
                                    </button>
                                    <input type="hidden" name="stockid" id="stockid" value="<?php echo $stock_id; ?>"/>
                                    <input type="hidden" name="whTo" id="whTo" value="<?php echo $whTo; ?>"/>
                                </form>
                            </div>
<?php endif; ?>
                    </div>
                </div>
            </div></div></div>

    <!-- // Content END -->
<?php include "../plmis_inc/common/footer.php"; ?>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script>
    <script src="<?php echo SITE_URL; ?>plmis_admin/Scripts/jquery.validate.js"></script>
    <script src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit.js"></script>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/newissue.js"></script>
    <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/<?php echo $js; ?>"></script>

    <?php
    $_SESSION['stockIssueArray'] = $stockArray;
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
    <!-- // Content END -->

</div>
<!-- END FOOTER -->

</body>
<!-- END BODY -->
</html>