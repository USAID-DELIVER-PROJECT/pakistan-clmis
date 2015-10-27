<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/_header.php";
?>
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
                    <div class="widget">
                        <div class="widget-head">
                            <h3 class="heading">Import Data</h3>
                        </div>
                        <div class="widget-body">
							<form name="frm" id="frm" action="import_action.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                    	<a href="./template/gs_sample.php">Download file with Store Codes and Product IDs</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <div class="control-group">
                                            	<label class="control-label">Month</label>
                                                <div class="controls">
                                                	<select name="month" id="month" class="form-control input-sm">
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++)
													{	
														$sel = ((date('m')-1) == $i) ? 'selected="selected"' : '';
													?>
                                                    	<option value="<?php echo $i; ?>" <?php echo $sel;?>><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                            	<label class="control-label">Month</label>
                                                <div class="controls">
                                                	<select name="year" id="year" class="form-control input-sm">
                                                    <?php
                                                    for ($i = date('Y'); $i >= 2010; $i--)
													{
													?>
                                                    	<option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group">
                                            	<label class="control-label">Select Data File(xlsx)</label>
                                                <div class="controls">
                                                    <input type="file" name="data_file" id="data_file" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group">
                                            	<label class="control-label">&nbsp;</label>
                                                <div class="controls">
                                                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Import</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="row">
                                	<div class="col-md-12">
                                        <div class="col-md-12" style="color:#F00;">
											<?php
                                            echo $_SESSION['error'];
											unset($_SESSION['error']);
											?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- // Content END --> 
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../plmis_inc/common/footer.php";?>
<script>
$.validator.addMethod("extension", function(value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, $.validator.format("Please select csv file."));

$("#frm").validate({ 
    onfocusout: function(e) {
        this.element(e);
    },
    rules:{
        data_file:{
            required:true,
            extension: "xlsx"
        }
    }
});
</script>
<?php
if (!empty($_SESSION['msg']))
{
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'Data imported successfully',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php 
	unset($_SESSION['msg']);
} ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>