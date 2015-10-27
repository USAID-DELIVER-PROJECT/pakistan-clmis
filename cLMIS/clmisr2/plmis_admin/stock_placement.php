<?php
include("../html/adminhtml.inc.php");
//include "../plmis_inc/common/top.php";
include "../plmis_inc/common/top_im.php";
include("Includes/AllClasses.php");
$wh_id=$_SESSION['wh_id'];

$getPlacementLocation=mysql_query("SELECT
placement_config.pk_id,
placement_config.location_name,
placement_config.warehouse_id
FROM
placement_config
WHERE
placement_config.warehouse_id =".$wh_id." ORDER BY placement_config.location_name") or die("Err Get Placement Location");

?>
<?php include "../plmis_inc/common/_header.php";?>
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
                        <h3 class="heading">Manage Locations (Bins)</h3>
                    </div>
                    <div class="widget-body">
                        <?php if(isset($_GET['id'])){
$getPlacementCongfig=mysql_query("select * from placement_config where pk_id=".$_GET['id']) or (die(mysql_error()));
$resPlacement=mysql_fetch_assoc($getPlacementCongfig);
?>
                        <form method="POST" name="new_receive" id="new_receive" action="stock_placement_action.php" >
                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"> 
                                        <!-- Group Receive No-->
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Area </label>
                                            <div class="controls">
                                                <select name="area" id="area" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 14") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['area']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Group END Receive No-->
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Row </label>
                                            <div class="controls">
                                                <select name="row" id="row" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 15") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['row']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Rack </label>
                                            <div class="controls">
                                                <select name="rack" id="rack" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 16") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['rack']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Rack Type </label>
                                            <div class="controls">
                                                <select name="rack_type" id="rack_type" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                                    rack_information.rack_type,
                                                    rack_information.pk_id
                                                    FROM
                                                    rack_information") or die("ERR Get Rack Type");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['rack_information_id']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['rack_type'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Pallet </label>
                                            <div class="controls">
                                                <select name="pallet" id="pallet" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 18") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['pallet']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Level </label>
                                            <div class="controls">
                                                <select name="level" id="level" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 19") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>" <?php if($resPlacement['level']==$rowArea['pk_id']){echo "selected=selected";}?>><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <label class="control-label" for="firstname"> &nbsp; </label>
                                        <div class="controls" >
                                            <button type="submit" class="btn btn-primary" id="add_receive"> Save Entry </button>
                                            <button type="reset" class="btn btn-info" id="reset"> Reset </button>
                                            <input type="hidden" name="trans_no" id="trans_no" value="<?php echo $TranNo; ?>" />
                                            <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id; ?>" />
                                            
                                            <!--<input  type="hidden" name="PkStockID" value="<?php echo $PkStockID; ?>"/>--> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $_GET['id']?>"/>
                        </form>
                        <?php }
else{
?>
                        <form method="POST" name="new_receive" id="new_receive" action="stock_placement_action.php" >
                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"> 
                                        <!-- Group Receive No-->
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Area </label>
                                            <div class="controls">
                                                <select name="area" id="area" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 14") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Group END Receive No-->
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Row </label>
                                            <div class="controls">
                                                <select name="row" id="row" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 15") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Rack </label>
                                            <div class="controls">
                                                <select name="rack" id="rack" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 16") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Rack Type </label>
                                            <div class="controls">
                                                <select name="rack_type" id="rack_type" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                                    rack_information.rack_type,
                                                    rack_information.pk_id
                                                    FROM
                                                    rack_information") or die("ERR Get Rack Type");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['rack_type'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Pallet </label>
                                            <div class="controls">
                                                <select name="pallet" id="pallet" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 18") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="control-group">
                                            <label class="control-label" for="receive_no"> Level </label>
                                            <div class="controls">
                                                <select name="level" id="level" required class="form-control input-medium">
                                                    <option value="">Select</option>
                                                    <?php $getArea=mysql_query("SELECT
                                        list_detail.pk_id,
                                        list_detail.list_value
                                        FROM
                                        list_master
                                        INNER JOIN list_detail ON list_master.pk_id = list_detail.list_master_id
                                        WHERE
                                        list_master.pk_id = 19") or die("ERR Get Area");
                    while($rowArea=mysql_fetch_assoc($getArea))
                    {
                        ?>
                                                    <option value="<?php echo $rowArea['pk_id'];?>"><?php echo $rowArea['list_value'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <label class="control-label" for="firstname"> &nbsp; </label>
                                        <div class="controls" style="float:right;">
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
                        <?php }?>
                    </div>
                </div>
                
                <!-- // Row END --> 
                <!-- Widget -->
                
                <div class="widget" data-toggle="collapse-widget">
                    <div class="widget-head">
                        <h3 class="heading">Location List</h3>
                    </div>
                    <!-- // Widget heading END -->
                    
                    <div class="widget-body"> 
                        
                        <!-- Table --> 
                        <!-- Table -->
                        <table class="dynamicTable2 table table-striped table-bordered table-condensed">
                            
                            <!-- Table heading -->
                            <thead>
                                <tr>
                                    <th> Location </th>
                                    <?php if ($_SESSION['UserType'] != 'UT-007'){?>
                                    <th width="50"> Action </th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <!-- // Table heading END --> 
                            
                            <!-- Table body -->
                            <tbody>
                                <!-- Table row -->
                                <?php
    while ($row = mysql_fetch_assoc($getPlacementLocation)) {
        ?>
                                <tr class="gradeX">
                                    <td nowrap><?php echo $row['location_name']?></td>
                                    <?php if ($_SESSION['UserType'] != 'UT-007'){?><td class="center"><span data-toggle="notyfy" id="<?php echo $row['pk_id']; ?>" data-type="confirm" data-layout="top"><img class="cursor" src="<?php echo SITE_URL;?>plmis_img/cross.gif" /></span></td><?php }?>
                                </tr>
                                <?php
    } ?>
                                <!-- // Table row END -->
                            </tbody>
                        </table>
                        <!-- // Table END --> 
                    </div>
                </div>
                <!-- Widget -->
            </div>
        </div>
    </div>
</div>
<?php include "../plmis_inc/common/footer.php";?>
<script src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockplacement.js"></script>
                        
	<?php
    if (isset($_SESSION['success']) && !empty($_SESSION['success']) ) {
        if ($_SESSION['success'] == 1)
        {
            $msg = '<strong>Location</strong> with the same name already exists';
            $type = 'error';
        }
        else if ($_SESSION['success'] == 2)
        {
            $msg = '<strong>Data</strong> has been saved successfully';
            $type = 'success';
        }
        else if ($_SESSION['success'] == 3)
        {
            $msg = '<strong>Data</strong> has been deleted successfully';
            $type = 'success';
        }
        ?>
        <script>
            var self = $('[data-toggle="notyfy"]');
            notyfy({
                force: true,
                text: '<?php echo $msg;?>',
                type: '<?php echo $type;?>',
                layout: self.data('layout')
            });
        
        </script>
    <?php
        unset($_SESSION['success']);
    } ?>
</body>
<!-- END BODY -->
</html>