<?php
include("../application/includes/classes/Configuration.inc.php");
include(APP_PATH."includes/classes/db.php");
include(PUBLIC_PATH."html/header.php");
?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
	<?php include PUBLIC_PATH."html/top_im.php";?>
        <div class="page-content-wrapper">
            <div class="page-content" style="min-height:353px; margin-left: 0px !important">
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-body">
                                <form name="frm" id="frm" action="" method="post" role="form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                <div class="control-group">
                                                    <label>Office Level</label>
                                                    <div class="controls">
                                                        <select name="level" id="level" class="form-control input-sm">
                                                            <option value="1">National</option>
                                                            <option value="2">Provincial</option>
                                                            <option value="3">District</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="province_div" style="display:none;">
                                                <div class="control-group">
                                                    <label>Province/Region</label>
                                                    <div class="controls">
                                                        <select name="province" id="province" class="form-control input-sm">
                                                        	<option value="">All</option>
                                                            <?php
															$qry = "SELECT
																		Province.PkLocID,
																		Province.LocName
																	FROM
																		tbl_locations AS Province
																	WHERE
																		Province.LocLvl = 2
																	AND Province.ParentID IS NOT NULL
																	ORDER BY
																		Province.PkLocID ASC";
															$rsQry = mysql_query($qry) or die();
															while ($row = mysql_fetch_array($rsQry)) {
																?>
																<option value="<?php echo $row['PkLocID']; ?>" <?php echo $sel; ?>><?php echo $row['LocName']; ?></option>
																<?php
															}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="district_div" style="display:none;">
                                                <div class="control-group" id="district_data">
                                                    <label>District</label>
                                                    <div class="controls">
                                                        <select name="district" id="district" class="form-control input-sm">
                                                            <option value="">Loading...</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="control-group">
                                                    <label>Stakeholder</label>
                                                    <div class="controls">
                                                        <select name="stakeholder" id="stakeholder" class="form-control input-sm">
                                                            <option value="">All</option>
                                                            <?php
                                                            $querystk = "SELECT DISTINCT
																			stakeholder.stkid,
																			stakeholder.stkname
																		FROM
																			tbl_warehouse
																		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
																		INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
																		WHERE
																			stakeholder.stk_type_id IN (0, 1)
																		ORDER BY
																			stakeholder.stkorder ASC";
                                                            $rsstk = mysql_query($querystk) or die();
                                                            while ($rowstk = mysql_fetch_array($rsstk)) {
                                                                ?>
                                                                <option value="<?php echo $rowstk['stkid']; ?>"><?php echo $rowstk['stkname']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="control-group">
                                                    <label>&nbsp;</label>
                                                    <div class="controls">
                                                        <input type="button" id="submit" value="GO" class="btn btn-primary input-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="widget">
                            <div class="widget-body" id="data_div"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include PUBLIC_PATH."/html/footer.php";?>
<style>
.page-content-wrapper .page-content{
	margin-left:0px !important;
}
</style>
<script>
    $(function() {
        $('#level').change(function() {
            officeType($(this).val());
        });
        $('#province').change(function() {
            var provId = $(this).val();
            showDistricts(provId);
        });
	
		// Submit Form
		$('#submit').click(function(e) {
			/*if ( parseInt($('#level').val()) == 3 && $('#district').val() == '' ){
				alert('Select district');
				$('#district').focus();
				return false;
			}
			if ( $('#stakeholder').val() == '' ){
				alert('Select stakeholder');
				$('#stakeholder').focus();
				return false;
			}*/
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: $('#frm').serialize(),
                dataType: 'html',
                success: function(data)
                {
                    $('#data_div').html(data);
                }
            });
        });
    });
    function officeType(officeLevel)
    {
        if (parseInt(officeLevel) == 1)
        {
			$('#province_div').hide();
			$('#district_div').hide();
		}
		else if (parseInt(officeLevel) == 2)
        {
			$('#province_div').show();
			$('#district_div').hide();
		}
		else if (parseInt(officeLevel) == 3)
        {
			$('#province_div').show();
			showDistricts($('#province').val());
		}
    }
    function showDistricts(provId)
    {
        var officeLevel = $('#level').val();
        if (officeLevel == 3)
        {
            $('#district_div').show();
            $('#district').html('<option value="">Loading...</option>');
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {provinceId: provId, validate: 'yes'},
                dataType: 'html',
                success: function(data)
                {
                    $('#district_data').html(data);
                }
            });
        }
    }
</script>
</body>
</html>