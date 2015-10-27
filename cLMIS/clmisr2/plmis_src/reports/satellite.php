<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'satellite';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $districtId = mysql_real_escape_string($_POST['district']);
    $campId = mysql_real_escape_string($_POST['camps']);
    $reportingDate = mysql_real_escape_string($_POST['year_sel']) . '-' . $selMonth . '-01';
    $stakeholder = 1;
	
	if($campId == 'all')
	{
		$campFilter = "";
		$title = 'FWC & MSU';
	}
	else
	{
		if ($campId == 'msu'){
			$title = 'MSU';
			$campFilter = " AND tbl_warehouse.hf_type_id = 2";
		}else if($campId == 'fwc'){
			$title = 'FWC';
			$campFilter = " AND tbl_warehouse.hf_type_id = 1";
		}else{
			$title = 'FWC & MSU';
			$campFilter = " AND tbl_warehouse.wh_id = $campId";
		}
	}
	
    // Get district name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $districtId";
    $row = mysql_fetch_array(mysql_query($qry));
    $distrctName = $row['LocName'];
    $fileName = 'Satellite' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
}
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
        <?php include "../../plmis_inc/common/_top.php"; ?>
        <?php include "../../plmis_inc/common/top_im.php"; ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">Monthly Performance Report of Satellite Camps</h3>
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Filter by</h3>
                            </div>
                            <div class="widget-body">
                                <?php include('sub_dist_form.php'); ?>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                if (isset($_POST['submit'])) {
                	$qry = "SELECT
								itminfo_tab.itm_id,
								itminfo_tab.itm_name,
								SUM(tbl_hf_satellite_data.issue_balance) AS performance,
								SUM(tbl_hf_satellite_mother_care.pre_natal_new + tbl_hf_satellite_mother_care.pre_natal_old) AS prenatal,
								SUM(tbl_hf_satellite_mother_care.post_natal_new + tbl_hf_satellite_mother_care.post_natal_old) AS postnatal,
								SUM(tbl_hf_satellite_mother_care.ailment_children) AS children,
								SUM(tbl_hf_satellite_mother_care.ailment_adults) AS adults,
								SUM(tbl_satellite_camps.camps_target) AS camps_target,
								SUM(tbl_satellite_camps.camps_held) AS camps_held
							FROM
								tbl_warehouse
							INNER JOIN tbl_hf_satellite_data ON tbl_warehouse.wh_id = tbl_hf_satellite_data.warehouse_id
							INNER JOIN tbl_hf_satellite_mother_care ON tbl_warehouse.wh_id = tbl_hf_satellite_mother_care.warehouse_id
							INNER JOIN itminfo_tab ON tbl_hf_satellite_data.item_id = itminfo_tab.itm_id
							INNER JOIN tbl_satellite_camps ON tbl_hf_satellite_mother_care.reporting_date = tbl_satellite_camps.reporting_date
							AND tbl_hf_satellite_mother_care.warehouse_id = tbl_satellite_camps.warehouse_id
							WHERE
								tbl_hf_satellite_mother_care.reporting_date = '$reportingDate'
							AND tbl_hf_satellite_data.reporting_date = '$reportingDate'
							AND tbl_warehouse.dist_id = $districtId
							$campFilter
							GROUP BY
								tbl_hf_satellite_data.item_id
							ORDER BY
								itminfo_tab.frmindex ASC";
                    $qryRes = mysql_query($qry);
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
						
						while ($row = mysql_fetch_array($qryRes))
						{
							$campsTarget = $row['camps_target'];
							$campsHeld = $row['camps_held'];
							$prenatal = $row['prenatal'];
							$postnatal = $row['postnatal'];
							$children = $row['children'];
							$adults = $row['adults'];
							
							if ($row['itm_id'] == 31 || $row['itm_id'] == 32)
							{
								$csCases[] = $row['performance'];
							}
							else
							{
								$contraceptives[$row['itm_name']] = $row['performance'];
							}
						}
                    ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <table width="100%" align="center">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                Monthly Performance Report of Satellite Camps of <?php echo $title;?><br>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="80%" cellspacing="0" align="center" style="margin-top:30px;">
                                            	<tr>
                                                	<td>
                                                    	<table width="35%" align="left" id="myTable">
                                                        	<tr>
                                                                <td>Camps Target</td>
                                                                <td class="center"><?php echo $campsTarget;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Camps Held</td>
                                                                <td class="center"><?php echo $campsHeld;?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                	<td><h4>Performance</h4></td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:10%;">
                                                    	<h4>1- Contraceptives</h4>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:20%;">
                                                    	<table width="50%" align="left" id="myTable">
                                                        <?php
														foreach( $contraceptives as $item=>$performance )
														{
														?>
                                                        	<tr>
                                                                <td><?php echo $item?></td>
                                                                <td class="center"><?php echo number_format($performance);?></td>
                                                            </tr>
                                                        <?php
														}
														?>   
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:10%;">
                                                    	<h4>2- MCH Cases Attended</h4>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:20%;">
                                                    	<table width="50%" align="left" id="myTable">
                                                        	<tr>
                                                                <td>Ante-natal</td>
                                                                <td class="center"><?php echo $campsTarget;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Post-natal</td>
                                                                <td class="center"><?php echo $campsHeld;?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:10%;">
                                                    	<h4>3- No. of Patient Provided Medicines for Minor Ailments</h4>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="padding-left:20%;">
                                                    	<table width="50%" align="left" id="myTable">
                                                        	<tr>
                                                                <td>Adults</td>
                                                                <td class="center"><?php echo $adults;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Children</td>
                                                                <td class="center"><?php echo $children;?></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "No record found";
                    }
                }

				// Unset variables
                unset($ob, $cb, $rcv, $issue, $data, $hfTypes, $itemIds, $product);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <script>
		$(function() {
			showDistricts();
			$('#prov_sel').change(function(e) {
				$('#district').html('<option value="">Select</option>');
				$('#camps').html('<option value="">Select</option>');
				showDistricts();
			});
			$(document).on('change', '#district', function(){
				$('#camps').html('<option value="">Select</option>');
				showCamps();
			});
		})
		function showDistricts()
		{
			var provinceId = $('#prov_sel').val();
			if (provinceId != '')
			{
				$.ajax({
					url: 'ajax_calls.php',
					data: {provinceId: provinceId, dId: '<?php echo $districtId; ?>'},
					type: 'POST',
					success: function(data)
					{
						$('#districtDiv').html(data);
						showCamps();
					}
				})
			}
		}
		function showCamps()
		{
			var districtId = $('#district').val();
			if (districtId != '')
			{
				$.ajax({
					url: 'ajax_calls.php',
					data: {districtId: districtId, campId: '<?php echo $campId; ?>'},
					type: 'POST',
					success: function(data)
					{
						$('#camps').html(data);
					}
				})
			}
		}
    </script>
</body>
</html>