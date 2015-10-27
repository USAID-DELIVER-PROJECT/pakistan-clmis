<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'dpw_f1';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $districtId = mysql_real_escape_string($_POST['district']);
    $reportingDate = mysql_real_escape_string($_POST['year_sel']) . '-' . $selMonth . '-01';
    $stakeholder = 1;

    // Get district name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $districtId";
    $row = mysql_fetch_array(mysql_query($qry));
    $distrctName = $row['LocName'];
    $fileName = 'DPW-F1_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
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
                        <h3 class="page-title row-br-b-wp">District Monthly Report of Family Planning Activities</h3>
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
                if (isset($_POST['submit']))
				{
                	$qry = "SELECT
								A.wh_id,
								A.wh_name,
								A.method_type,
								A.method_rank,
								SUM(A.new) AS new,
								SUM(A.old) AS old,
								SUM(A.issue_balance) AS sold,
								SUM(A.surCases) AS surCases,
								B.pre_natal_new,
								B.pre_natal_old,
								B.post_natal_new,
								B.post_natal_old,
								B.ailment_children,
								B.ailment_adults,
								B.general_ailment
							FROM
								(
									SELECT
										tbl_warehouse.wh_id,
										tbl_warehouse.wh_name,
										tbl_warehouse.wh_rank,
										tbl_hf_type_rank.hf_type_rank,
										tbl_hf_data.new,
										tbl_hf_data.old,
										SUM(IF (tbl_warehouse.hf_type_id IN(4, 5), (IF (tbl_hf_data_reffered_by.hf_type_id IN(4, 5), tbl_hf_data_reffered_by.ref_surgeries, 0)), tbl_hf_data.issue_balance)) AS surCases,
										tbl_hf_data.issue_balance,
										itminfo_tab.method_type,
										itminfo_tab.method_rank
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
									WHERE
										tbl_warehouse.dist_id = $districtId
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_type_rank.stakeholder_id = $stakeholder
									AND tbl_hf_type_rank.province_id = $selProv
									AND tbl_hf_data.reporting_date = '$reportingDate'
									GROUP BY
										tbl_warehouse.wh_id,
										itminfo_tab.itm_id
								) A
							INNER JOIN (
								SELECT
									tbl_warehouse.wh_id,
									tbl_hf_mother_care.pre_natal_new,
									tbl_hf_mother_care.pre_natal_old,
									tbl_hf_mother_care.post_natal_new,
									tbl_hf_mother_care.post_natal_old,
									tbl_hf_mother_care.ailment_children,
									tbl_hf_mother_care.ailment_adults,
									tbl_hf_mother_care.general_ailment
								FROM
									tbl_warehouse
								INNER JOIN tbl_hf_mother_care ON tbl_warehouse.wh_id = tbl_hf_mother_care.warehouse_id
								WHERE
									tbl_warehouse.dist_id = $districtId
								AND tbl_warehouse.stkid = $stakeholder
								AND tbl_hf_mother_care.reporting_date = '$reportingDate'
							) B ON A.wh_id = B.wh_id
							GROUP BY
								A.wh_id,
								A.method_type
							ORDER BY
								IF (A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
								A.wh_rank ASC,
								IF (A.hf_type_rank = '' OR A.hf_type_rank IS NULL, 1, 0),
								A.hf_type_rank ASC,
								A.wh_name ASC,
								A.method_rank ASC";
                    $qryRes = mysql_query($qry);
					
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
								$method = '';
								$whName = '';
								$wh_id = '';
                                while ($row = mysql_fetch_array($qryRes)) {
                                    if (!in_array($row['method_type'], $method)){
										$method[] = $row['method_type'];
									}
									if (!in_array($row['wh_name'], $whName)){
										$whName[$row['wh_id']] = $row['wh_name'];
									}
									
									$data[$row['wh_id']][$row['method_type']]['new'] = $row['new'];
                                    $data[$row['wh_id']][$row['method_type']]['old'] = $row['old'];
                                    $data[$row['wh_id']][$row['method_type']]['sold'] = $row['sold'];
                                    $data[$row['wh_id']]['pre_natal_new'] = $row['pre_natal_new'];
                                    $data[$row['wh_id']]['pre_natal_old'] = $row['pre_natal_old'];
                                    $data[$row['wh_id']]['post_natal_new'] = $row['post_natal_new'];
                                    $data[$row['wh_id']]['post_natal_old'] = $row['post_natal_old'];
                                    $data[$row['wh_id']]['ailment_children'] = $row['ailment_children'];
                                    $data[$row['wh_id']]['ailment_adults'] = $row['ailment_adults'];
                                    $data[$row['wh_id']]['general_ailment'] = $row['general_ailment'];
                                    $data[$row['wh_id']]['surCases'] = $row['surCases'];
									
									// To show Total
									if ( $row['method_type'] == 'Contraceptives Surgery' ){
										$total['surCases'][] = $row['surCases'];
									}
									else
									{
										$total[$row['method_type']]['new'][] = $row['new'];
										$total[$row['method_type']]['old'][] = $row['old'];
										$total[$row['method_type']]['sold'][] = $row['sold'];
									}
									
									
									if ( $wh_id != $row['wh_id'] )
									{
										$total['pre_natal_new'][] = $row['pre_natal_new'];
										$total['pre_natal_old'][] = $row['pre_natal_old'];
										$total['post_natal_new'][] = $row['post_natal_new'];
										$total['post_natal_old'][] = $row['post_natal_old'];
										$total['ailment_children'][] = $row['ailment_children'];
										$total['ailment_adults'][] = $row['ailment_adults'];
										$total['general_ailment'][] = $row['general_ailment'];
										$wh_id = $row['wh_id'];
									}
									
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                CONTRACEPTIVE PERFORMANCE IN RELATION TO TARGETS AND PERCENTAGE	<br>
                                                Achievement For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">DPW F1</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                        <?php 
										if ( $selProv == 3 )
										{
										?>
                                        	<table id="myTable" cellspacing="0" align="center" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="3">S.No</th>
                                                        <th rowspan="3" style="width:200px !important;">Health Facility</th>
                                                        <th colspan="<?php echo sizeof($method) + 9;?>">Family Planning</th>
                                                        <th colspan="6">MCH and General Patient</th>
                                                    </tr>
                                                    <tr>
													<?php
                                                    foreach ($method as $methodName) {
                                                        if ( $methodName == 'IUD' ){
															echo "<th colspan=\"2\">$methodName</th>";
														}else if ( $methodName == 'Contraceptives Surgery' ){
															echo "<th>Surgery Cases</th>";
														}
														else{
															echo "<th colspan=\"3\">$methodName</th>";
														}
                                                    }
                                                    ?>
                                                        <th colspan="2">Ante-natal</th>
                                                        <th colspan="2">Post-natal</th>
                                                        <th colspan="2">MCH</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($method as $methodName) {
															if ( $methodName == 'IUD' ){
																echo "<th>New Case</th>";
                                                            	echo "<th>Old Case</th>";
															}else if ( $methodName == 'Contraceptives Surgery' ){
                                                            	echo "<th>No. of CS Cases</th>";
															}
															else{
																echo "<th>New Case</th>";
                                                            	echo "<th>Old Case</th>";
																if ($methodName == 'Injectables'){
                                                            		echo "<th>No. of Injectables</th>";
																}
																if ($methodName == 'Oral Pills'){
                                                            		echo "<th>No. of Oral Pills</th>";
																}
																if ($methodName == 'Condoms'){
                                                            		echo "<th>No. of Condoms Sold</th>";
																}
																if ($methodName == 'Implant'){
                                                            		echo "<th>Implant</th>";
																}
															}
                                                        }
                                                        ?>
                                                        <th>New Case</th>
                                                        <th>Old Case</th>
                                                        <th>New Case</th>
                                                        <th>Old Case</th>
                                                        <th>Children</th>
                                                        <th>Adults</th>
                                                    </tr>
                                                </thead>
                                               <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    foreach ($whName as $whId => $whName) {
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $whName;?></td>
                                                        <?php
														foreach ($method as $methodName) {
															if ( $methodName == 'IUD' ){
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
															}else if ( $methodName == 'Contraceptives Surgery' ){
																echo "<td class=\"right\">". ( ($data[$whId]['surCases'] != 0) ? number_format($data[$whId]['surCases']) : 0 ). "</td>";
															}
															else{
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['sold'] != 0) ? number_format($data[$whId][$methodName]['sold']) : 0 ). "</td>";
															}
														}
														?>
                                                            <td class="right"><?php echo ($data[$whId]['pre_natal_new'] != 0) ? number_format($data[$whId]['pre_natal_new']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['pre_natal_old'] != 0) ? number_format($data[$whId]['pre_natal_old']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['post_natal_new'] != 0) ? number_format($data[$whId]['post_natal_new']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['post_natal_old'] != 0) ? number_format($data[$whId]['post_natal_old']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['ailment_children'] != 0) ? number_format($data[$whId]['ailment_children']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['ailment_adults'] != 0) ? number_format($data[$whId]['ailment_adults']) : 0;?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                	<tr>
                                                        <th colspan="2" class="right">Total</th>
                                                    <?php													
													foreach ($method as $methodName) {
														if ( $methodName == 'IUD' ){
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
														}
														else if ( $methodName != 'Contraceptives Surgery' ){
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['sold']) != 0) ? number_format(array_sum($total[$methodName]['sold'])) : 0 ). "</th>";
														}
													}
													?>
                                                        <th class="right"><?php echo (array_sum($total['surCases']) != 0) ? number_format(array_sum($total['surCases'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['pre_natal_new']) != 0) ? number_format(array_sum($total['pre_natal_new'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['pre_natal_old']) != 0) ? number_format(array_sum($total['pre_natal_old'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['post_natal_new']) != 0) ? number_format(array_sum($total['post_natal_new'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['post_natal_old']) != 0) ? number_format(array_sum($total['post_natal_old'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['ailment_children']) != 0) ? number_format(array_sum($total['ailment_children'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['ailment_adults']) != 0) ? number_format(array_sum($total['ailment_adults'])) : 0;?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php	
										}
										else
										{
										?>    
                                            <table id="myTable" cellspacing="0" align="center" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="3">S.No</th>
                                                        <th rowspan="3" style="width:200px !important;">Health Facility</th>
                                                        <th colspan="<?php echo sizeof($method) + 9;?>">Family Planning</th>
                                                        <th colspan="7">MCH and General Patient</th>
                                                    </tr>
                                                    <tr>
													<?php
                                                    foreach ($method as $methodName) {
                                                        if ( $methodName == 'IUD' ){
															echo "<th colspan=\"2\">$methodName</th>";
														}else if ( $methodName == 'Contraceptives Surgery' ){
															echo "<th>Surgery Cases</th>";
														}
														else{
															echo "<th colspan=\"3\">$methodName</th>";
														}
                                                    }
                                                    ?>
                                                        <th colspan="2">Ante-natal</th>
                                                        <th colspan="2">Post-natal</th>
                                                        <th colspan="2">MCH</th>
                                                        <th rowspan="2">No. of General Patients</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($method as $methodName) {
															if ( $methodName == 'IUD' ){
																echo "<th>New Case</th>";
                                                            	echo "<th>Old Case</th>";
															}else if ( $methodName == 'Contraceptives Surgery' ){
                                                            	echo "<th>No. of CS Cases</th>";
															}
															else{
																echo "<th>New Case</th>";
                                                            	echo "<th>Old Case</th>";
																if ($methodName == 'Injectables'){
                                                            		echo "<th>No. of Injectables</th>";
																}
																if ($methodName == 'Oral Pills'){
                                                            		echo "<th>No. of Oral Pills</th>";
																}
																if ($methodName == 'Condoms'){
                                                            		echo "<th>No. of Condoms Sold</th>";
																}
																if ($methodName == 'Implant'){
                                                            		echo "<th>Implant</th>";
																}
															}
                                                        }
                                                        ?>
                                                        <th>New Case</th>
                                                        <th>Old Case</th>
                                                        <th>New Case</th>
                                                        <th>Old Case</th>
                                                        <th>New Case</th>
                                                        <th>Old Case</th>
                                                    </tr>
                                                </thead>
                                               <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    foreach ($whName as $whId => $whName) {
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $whName;?></td>
                                                        <?php
														foreach ($method as $methodName) {
															if ( $methodName == 'IUD' ){
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
															}else if ( $methodName == 'Contraceptives Surgery' ){
																echo "<td class=\"right\">". ( ($data[$whId]['surCases'] != 0) ? number_format($data[$whId]['surCases']) : 0 ). "</td>";
															}
															else{
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
																echo "<td class=\"right\">". ( ($data[$whId][$methodName]['sold'] != 0) ? number_format($data[$whId][$methodName]['sold']) : 0 ). "</td>";
															}
														}
														?>
                                                            <td class="right"><?php echo ($data[$whId]['pre_natal_new'] != 0) ? number_format($data[$whId]['pre_natal_new']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['pre_natal_old'] != 0) ? number_format($data[$whId]['pre_natal_old']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['post_natal_new'] != 0) ? number_format($data[$whId]['post_natal_new']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['post_natal_old'] != 0) ? number_format($data[$whId]['post_natal_old']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['ailment_children'] != 0) ? number_format($data[$whId]['ailment_children']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['ailment_adults'] != 0) ? number_format($data[$whId]['ailment_adults']) : 0;?></td>
                                                            <td class="right"><?php echo ($data[$whId]['general_ailment'] != 0) ? number_format($data[$whId]['general_ailment']) : 0;?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                	<tr>
                                                        <th colspan="2" class="right">Total</th>
                                                    <?php													
													foreach ($method as $methodName) {
														if ( $methodName == 'IUD' ){
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
														}
														else if ( $methodName != 'Contraceptives Surgery' ){
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
															echo "<th class=\"right\">". ( (array_sum($total[$methodName]['sold']) != 0) ? number_format(array_sum($total[$methodName]['sold'])) : 0 ). "</th>";
														}
													}
													?>
                                                        <th class="right"><?php echo (array_sum($total['surCases']) != 0) ? number_format(array_sum($total['surCases'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['pre_natal_new']) != 0) ? number_format(array_sum($total['pre_natal_new'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['pre_natal_old']) != 0) ? number_format(array_sum($total['pre_natal_old'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['post_natal_new']) != 0) ? number_format(array_sum($total['post_natal_new'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['post_natal_old']) != 0) ? number_format(array_sum($total['post_natal_old'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['ailment_children']) != 0) ? number_format(array_sum($total['ailment_children'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['ailment_adults']) != 0) ? number_format(array_sum($total['ailment_adults'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['general_ailment']) != 0) ? number_format(array_sum($total['general_ailment'])) : 0;?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php 
										}
										?>
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
                unset($method, $whName, $data, $total);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <?php include ('combos.php'); ?>
</body>
</html>