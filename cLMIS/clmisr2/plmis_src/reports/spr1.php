<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'spr1';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $reportingDate = mysql_real_escape_string($_POST['year_sel']) . '-' . $selMonth . '-01';
    $stakeholder = 1;

    // Get Province name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $selProv";
    $row = mysql_fetch_array(mysql_query($qry));
    $provinceName = $row['LocName'];
    $fileName = 'SPR1_' . $provinceName . '_for_' . date('M-Y', strtotime($reportingDate));
}
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
        <?php include "../../plmis_inc/common/_top.php"; ?>
        <?php  include "../../plmis_inc/common/top_im.php"; ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">District wise Provincial Contraceptive Surgery Cases Report</h3>
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
									tbl_warehouse.dist_id,
									tbl_locations.LocName,
									tbl_hf_data.item_id,
									tbl_warehouse.hf_type_id,
									SUM(tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) AS surgery
								FROM
									tbl_hf_data
								INNER JOIN tbl_warehouse ON tbl_hf_data.warehouse_id = tbl_warehouse.wh_id
								INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
								INNER JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
								WHERE
									tbl_warehouse.prov_id = $selProv
								AND tbl_hf_data.reporting_date = '$reportingDate'
								GROUP BY
									tbl_warehouse.dist_id,
									tbl_warehouse.hf_type_id,
									tbl_hf_data.item_id
								ORDER BY
									tbl_locations.LocName ASC,
									tbl_warehouse.hf_type_id DESC";
                    $qryRes = mysql_query($qry);
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                $distId = '';
                                while ($row = mysql_fetch_array($qryRes)) {
                                    $data[$row['dist_id']]['DistName'] = $row['LocName'];
									
									if ( $row['dist_id'] != $distId )
									{
										$data[$row['dist_id']]['RHSA_male'] = 0;
										$data[$row['dist_id']]['RHSA_female'] = 0;
										$data[$row['dist_id']]['RHSB_male'] = 0;
										$data[$row['dist_id']]['RHSB_female'] = 0;
									}
									
									if ($row['hf_type_id'] == 4){
										if($row['item_id'] == '31'){
											$data[$row['dist_id']]['RHSA_male'] = $row['surgery'];
										}else if($row['item_id'] == '32'){
											$data[$row['dist_id']]['RHSA_female'] = $row['surgery'];
										}
									}else if ($row['hf_type_id'] == 5){
										if($row['item_id'] == '31'){
											$data[$row['dist_id']]['RHSB_male'] = $row['surgery'];
										}else if($row['item_id'] == '32'){
											$data[$row['dist_id']]['RHSB_female'] = $row['surgery'];
										}
									}
									$distId = $row['dist_id'];
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                District wise Provincial Contraceptive Surgery Cases Report<br>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', Province ' . $provinceName; ?>
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" width="5%">S. No.</th>
                                                        <th rowspan="2" width="20%">Name of District</th>
                                                        <th colspan="3" width="25%">RHS-A Centres</th>
                                                        <th colspan="3" width="25%">RHS-B Centres</th>
                                                        <th colspan="3" width="25%">Total RHS-A&B Centres</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Total</th>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Total</th>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
												$counter = 1;
												foreach($data as $distId=>$distData)
												{
													$maleTotal = $data[$distId]['RHSA_male'] + $data[$distId]['RHSB_male'];
													$femaleTotal = $data[$distId]['RHSA_female'] + $data[$distId]['RHSB_female'];
													$total = $maleTotal + $femaleTotal;
													$colSum['RHSA_male'][] = $data[$distId]['RHSA_male'];
													$colSum['RHSA_female'][] = $data[$distId]['RHSA_female'];
													$colSum['RHSB_male'][] = $data[$distId]['RHSB_male'];
													$colSum['RHSB_female'][] = $data[$distId]['RHSB_female'];
												?>
                                                	<tr>
                                                    	<td class="center"><?php echo $counter++;?></td>
                                                    	<td><?php echo $data[$distId]['DistName'];?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSA_male']);?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSA_female']);?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSA_male'] + $data[$distId]['RHSA_female']);?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSB_male']);?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSB_female']);?></td>
                                                    	<td class="right"><?php echo number_format($data[$distId]['RHSB_male'] + $data[$distId]['RHSB_female']);?></td>
                                                    	<td class="right"><?php echo number_format($maleTotal);?></td>
                                                    	<td class="right"><?php echo number_format($femaleTotal);?></td>
                                                    	<td class="right"><?php echo number_format($total);?></td>
                                                    </tr>
                                                <?php
												}
												?>
                                                	<tr>
                                                    	<td colspan="2" class="center"><b>Total</b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_male']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_female']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_male']) + array_sum($colSum['RHSA_female']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSB_male']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSB_female']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSB_male']) + array_sum($colSum['RHSB_female']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_male']) + array_sum($colSum['RHSB_male']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_female']) + array_sum($colSum['RHSB_female']));?></b></td>
                                                        <td class="right"><b><?php echo number_format(array_sum($colSum['RHSA_male']) + array_sum($colSum['RHSA_female']) + array_sum($colSum['RHSB_male']) + array_sum($colSum['RHSB_female']));?></b></td>
                                                    </tr>
                                                </tbody>
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
				// Unset varibles
                unset($data, $distId);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
</body>
</html>