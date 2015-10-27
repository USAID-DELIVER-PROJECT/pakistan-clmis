<?php
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/top_im.php";
//include "../plmis_inc/common/top.php";
include("Includes/AllClasses.php");
$title = "New Issue";
include('../' . $_SESSION['menu']);

$TranRef = '';

$items = $objManageItem->GetAllWHProduct();
$types = $objTransType->getAdjusmentTypes();
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
            <form method="POST" name="batch_search" id="batch_search" action="add_adjustment_action.php" >
                <div class="widget" data-toggle="collapse-widget">
                    <div class="widget-head">
                        <h3 class="heading">New Adjustment</h3>
                    </div>
                    <!-- // Widget Heading END -->
                    
                    <div class="widget-body"> 
                        <!-- Row -->
                        <div class="row">
                            <div class="col-md-12">&nbsp;</div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label for="firstname"> Adjustment Date <span class="red">*</span> </label>
                                        <div class="controls">
                                            <input class="form-control input-medium" id="adjustment_date" name="adjustment_date" type="text" value="<?php echo date("d/m/Y"); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label for="firstname"> Ref. No. </label>
                                        <div class="controls">
                                            <input class="form-control input-medium" id="ref_no" name="ref_no" type="text" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Group Receive Date-->
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label class="control-label"> Product <span class="red">*</span> </label>
                                        <div class="controls">
                                            <select name="product" id="product" class="form-control input-medium" required="true">
                                                <option value="">Select</option>
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
                                        <label class="control-label" for="batch_no"> Batch No <span class="red">*</span> </label>
                                        <div class="controls">
                                            <select name="batch_no" id="batch_no" class="form-control input-medium" required="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" id="available_div">
                                    <div class="control-group">
                                        <label class="control-label" for="available"> Available </label>
                                        <div class="controls"> <span id="itembatches">
                                            <input class="form-control input-medium num" id="available" name="available" type="text" value="<?php echo $TranRef; ?>" disabled="" style="display:inline !important"/>
                                            </span> <span id="product-unit">Unit</span> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label class="control-label" for="firstname"> Adjustment Type <span class="red">*</span> </label>
                                        <div class="controls">
                                            <select name="types" id="types" class="form-control input-medium" required="true">
                                                <option value="">Select</option>
                                                <?php
                                                        foreach ($types as $type) {
                                                            echo "<option value=" . $type->trans_id . ">" . $type->trans_type . "</option>";
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label class="control-label" for="firstname"> Quantity <span class="red">*</span> </label>
                                        <div class="controls">
                                            <input class="form-control input-medium" id="quantity" name="quantity" type="text" value="<?php echo $TranRef; ?>" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="control-group">
                                        <label class="control-label" for="firstname"> Comment </label>
                                        <div class="controls">
                                            <textarea name="comments" id="comments" class="form-control input-medium input-medium"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 add-adjustment-btn right">
                                    <label class="control-label" for="firstname">&nbsp;</label>
                                    <div class="controls">
                                        <button type="submit" class="btn btn-primary" id="add_adjustment">Save</button>
                                        <button type="reset" class="btn btn-info" id="reset">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- // Content END -->
            <?php include('../template/footer-top.php'); ?>
        </div>
    </div>
</div>
<?php include "../plmis_inc/common/footer.php"; ?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_admin/Scripts/jquery.validate.js"></script> 
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/add_adjustment.js"></script>
<?php
$_SESSION['stockIssueArray'] = $stockArray;
if (isset($_SESSION['success']) && !empty($_SESSION['success']) ) {
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'Data has been saved successfully!',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php 
	unset($_SESSION['success']);
} ?>
<!-- END FOOTER --> 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>