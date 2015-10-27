<?php
include("../html/adminhtml.inc.php");
/*include "../plmis_inc/common/top_im.php";
include "../plmis_inc/common/top.php";*/
include("Includes/AllClasses.php");

$title = "Stock Receive from Warehouse";
//include('../template/header-top.php');
//include('../template/header-bottom.php');
include('../' . $_SESSION['menu']);
$issue_no = '';
$stockReceive = false;
if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) {
    if (isset($_REQUEST['issue_no']) && !empty($_REQUEST['issue_no'])) {
        $issue_no = $_REQUEST['issue_no'];
    }
    $objStockMaster->TranNo = $issue_no;
    $objStockMaster->WHIDTo = $_SESSION['wh_id'];
    $stockReceive = $objStockMaster->GetWHStockByIssueNo();
}
//$warehouses = $objwarehouse->GetUserWarehouses();
//$items = $objManageItem->GetAllManageItem();
$types = $objTransType->find_all();
$count = 0;
if(!empty($stockReceive)){
    $count = mysql_num_rows($stockReceive);
}
?>
<?php include "../plmis_inc/common/_header.php";?>
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
                                       <h3 class="heading">Stock Receive (From Warehouse)</h3>
                                    </div>
                                    <div class="widget-body">
                                        <form method="POST" name="batch_search" action="" >
                                            <!-- Row -->
                                            <div class="row ">
                                                <div class="col-md-12">
                                                    <div class="col-md-3"> 
                                                        <!-- Group Receive No-->
                                                        <div class="">
                                                            <label for="issue_no"> Issue No </label>
                                                            <input class="form-control" tabindex="1" id="issue_no" value="<?php echo $issue_no; ?>" name="issue_no" type="text" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group input-medium" style="margin-top: 21px;">
                                                            <button type="submit" class="btn btn-primary" name="search" value="Search"> Search </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- // Row END --> 
                                <!-- Widget -->
                                <form name="receive_stock" id="receive_stock" action="new_receive_wh_action.php" method="POST">
                                    <?php if ($stockReceive != FALSE) : ?>
                                    <div class="widget">
                                        <div class="widget-body"> 
                                            
                                            <!-- Table -->
                                            <table class="table table-bordered table-condensed table-striped table-vertical-center checkboxs js-table-sortable">
                                                
                                                <!-- Table heading -->
                                                <thead>
                                                    <tr>
                                                        <th> Product </th>
                                                        <th> Batch </th>
                                                        <th> Quantity </th>
                                                        <th> Adjusted Qty </th>
                                                        <th> Adjustment </th>
                                                        <th style="width: 1%;"> <input type="checkbox" id="checkAll" />
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <!-- // Table heading END --> 
                                                
                                                <!-- Table body -->
                                                <tbody>
                                                    <!-- Table row -->
                                                    <?php
                                            $i = 1;
                                            while ($row = mysql_fetch_object($stockReceive)) :
                                                $stockID = $row->fkStockID;
                                                //$vvm_stage = $row->vvm_stage;
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row->itm_name; ?></td>
                                                        <td><?php echo $row->batch_no; ?></td>
                                                        <td class="right"><?php echo number_format(abs($row->Qty)); ?>
                                                            <input type="hidden" id="<?php echo $i; ?>-qty" value="<?php echo abs($row->Qty); ?>" /></td>
                                                        <td class="col-md-3"><input type="text" name="missing[]" id="<?php echo $i; ?>-missing" value="" class="form-control input-sm input-small" /></td>
                                                        <td class="col-md-3"><select name="types[]" id="<?php echo $i; ?>-types" class="form-control input-sm input-small">
                                                                <?php
                                                        if (!empty($types)) {
                                                            foreach ($types as $type) {
                                                                echo "<option value=" . $type->trans_id . ">" . $type->trans_type . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                            </select></td>
                                                        <td class="center uniformjs"><input type="checkbox" name="stockid[]" value="<?php echo $row->PkDetailID; ?>" /></td>
                                                    </tr>
                                                    <?php $i++;
                                            endwhile; ?>
                                                    <!-- // Table row END -->
                                                </tbody>
                                                <!-- // Table body END -->
                                                
                                            </table>
                                            <!-- // Table END --> 
                                            
                                        </div>
                                    </div>
                                    
                                    <!-- Widget -->
                                    <div class="widget">
                                        <div class="widget-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label class="control-label" for="remarks"> Remarks </label>
                                                        <div class="controls">
                                                            <input name="remarks" id="remarks" type="text" class="form-control input-sm input-small" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label class="control-label" for="rec_ref"> Receive Reference </label>
                                                        <div class="controls">
                                                            <input name="rec_ref" id="rec_ref" type="text" class="form-control input-sm input-small" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="control-group">
                                                        <label class="control-label" for="rec_date"> Receive Date </label>
                                                        <div class="controls">
                                                            <input name="rec_date" class="form-control input-sm input-small" id="rec_date" value="<?php echo date("d/m/Y"); ?>" type="text" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 right">
                                                    <div class="control-group">
                                                    	<label class="control-label">&nbsp;</label>
                                                    </div>
                                                    <div class="controls">
                                                        <button type="submit" id="save" class="btn btn-primary"> Save </button>
                                                        <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stockID; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php elseif(!empty($issue_no)): ?>
                                    <div class="widget">
                                        <div class="widget-body red"> Voucher not found! </div>
                                    </div>
                                    <?php elseif(isset($_GET['msg']) && !empty($_GET['msg'])): ?>
                                    <div class="widget">
                                        <div class="widget-body green"> <?php echo $_GET['msg']; ?> </div>
                                    </div>
                                    <?php endif; ?>
                                    <input id="issue_no" value="<?php echo $issue_no; ?>" name="issue_no" type="hidden"/>
                                    <input id="count" value="<?php echo $count; ?>" name="count" type="hidden"/>
                                </form>
                            </div>
                        </div>
                        <!-- // Content END --> 
                        <script src="<?php echo SITE_URL; ?>plmis_js/dataentry/newreceive_wh.js"></script>
                    </div>
                </div>
            
        </div>

<?php include "../plmis_inc/common/footer.php";?>
<!-- END FOOTER -->

</body>
<!-- END BODY -->
</html>
