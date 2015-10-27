<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'form14';

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
    $fileName = 'Form14_' . $provinceName . '_for_' . date('M-Y', strtotime($reportingDate));
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
                        <h3 class="page-title row-br-b-wp">Provincial Summary of Contraceptive Performance Delivery Services by Category of Service Outlets</h3>
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
					
					// Health Facility Type Wise
					$qry = "SELECT
								B.hf_type_id,
								B.hf_type,
								B.hf_type_rank,
								B.itm_id,
								B.itm_name,
								B.itm_category,
								B.itm_type,
								B.total_outlets,
								A.performance,
								(A.CYPFactor * A.performance) AS CYP,
								(A.userFactor * A.performance) AS Users
							FROM
								(
									SELECT
										tbl_hf_data.item_id,
										tbl_warehouse.hf_type_id,
										SUM(tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) AS performance,
										itminfo_tab.user_factor AS userFactor,
										itminfo_tab.extra AS CYPFactor,
										itminfo_tab.itm_category
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									INNER JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
									WHERE
										tbl_warehouse.prov_id = $selProv
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_data.reporting_date = '$reportingDate'
									GROUP BY
										tbl_warehouse.hf_type_id,
										tbl_hf_data.item_id
									UNION
										SELECT
											tbl_hf_data.item_id,
											tbl_warehouse.hf_type_id,
											SUM(tbl_hf_data.issue_balance) AS performance,
											itminfo_tab.user_factor AS userFactor,
											itminfo_tab.extra AS CYPFactor,
											itminfo_tab.itm_category
										FROM
											tbl_warehouse
										INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
										INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
										WHERE
											tbl_warehouse.prov_id = $selProv
										AND tbl_warehouse.stkid = $stakeholder
										AND tbl_hf_data.reporting_date = '$reportingDate'
										AND itminfo_tab.itm_category = 1
										GROUP BY
											tbl_warehouse.hf_type_id,
											tbl_hf_data.item_id
								) A
							RIGHT JOIN (
								SELECT
									COUNT(DISTINCT tbl_warehouse.wh_id) AS total_outlets,
									tbl_warehouse.hf_type_id,
									tbl_hf_type.hf_type,
									tbl_hf_type_rank.hf_type_rank,
									itminfo_tab.itm_id,
									itminfo_tab.itm_name,
									itminfo_tab.frmindex,
									itminfo_tab.itm_category,
									CONCAT(itminfo_tab.method_type, '(', itminfo_tab.itm_type, ')') AS itm_type,
									itminfo_tab.method_rank
								FROM
									tbl_warehouse
								INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
								INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
								INNER JOIN tbl_hf_type ON tbl_warehouse.hf_type_id = tbl_hf_type.pk_id
								INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id,
								itminfo_tab
							INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
							WHERE
								tbl_warehouse.prov_id = $selProv
							AND tbl_warehouse.stkid = $stakeholder
							AND stakeholder.lvl = 7
							AND tbl_hf_type_rank.stakeholder_id = $stakeholder
							AND tbl_hf_type_rank.province_id = $selProv
							AND stakeholder_item.stkid = $stakeholder
							GROUP BY
								tbl_warehouse.hf_type_id,
								itminfo_tab.itm_id
							) B ON A.hf_type_id = B.hf_type_id
							AND A.item_id = B.itm_id
							ORDER BY
								B.hf_type_rank ASC,
								B.method_rank ASC,
								B.frmindex ASC";
                    $qryRes = mysql_query($qry);
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                while ($row = mysql_fetch_array($qryRes)) {
                                    if (!in_array($row['itm_name'], $items)){
										$items[] = $row['itm_name'];
										$product[$row['itm_type']][] = $row['itm_name'];
									}
									if (!in_array($row['hf_type'], $hfType)){
										$hfType[$row['hf_type_id']] = $row['hf_type'];
										if ($selProv == 3){
											if (!in_array($row['hf_type_id'], $hfPrograms)){
												// Get Facilities Count
												$qry = "SELECT REPgetNonProgramFacilities('HFT', $stakeholder, ".$row['hf_type_id'].", ".$selProv.", '$reportingDate') AS total_outlets FROM DUAL ";
												$hfCountRow = mysql_fetch_array(mysql_query($qry));
												
												$totalOutlets[$row['hf_type_id']] = (!empty($hfCountRow['total_outlets'])) ? $hfCountRow['total_outlets'] : '';
											}else{
												$totalOutlets[$row['hf_type_id']] = $row['total_outlets'];
											}
										}else{
											$totalOutlets[$row['hf_type_id']] = $row['total_outlets'];
										}
									}
									
									$data[$row['hf_type_id']]['CYP'][] = $row['CYP'];
									$data[$row['hf_type_id']]['Users'][] = $row['Users'];
									$data[$row['hf_type_id']][$row['itm_name']] = $row['performance'];
									
									$total['CYP'][] = $row['CYP'];						
									$totalCYP[$row['itm_name']][] = $row['CYP'];
									$total['Users'][] = $row['Users'];			
									$totalUsers[$row['itm_name']][] = $row['Users'];
									$total[$row['itm_name']][] = $row['performance'];
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td style="padding-top: 10px;" align="center">
                                        	<h4 class="center bold">
                                            	Monthly Performance Report(Outlet wise) For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear; ?><br>
												Inrespect of Population Welfare Department <?php echo $provinceName?>
                                            </h4>
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                    	<th rowspan="2">S.No.</th>
                                                        <th rowspan="2" width="13%">Name of Service Outlet</th>
                                                        <th rowspan="2" width="7%">No. of Outlets</th>
                                                        <?php
                                                        foreach ($product as $proType => $proNames) {
															if ($proType == 'Condoms(PCs)'){
                                                            	echo "<th colspan=" . sizeof($proNames) . ">$proType</th>";
															} else{
	                                                            echo "<th colspan=" . (sizeof($proNames) + 1) . ">$proType</th>";
															}
                                                        }
                                                        ?>
                                                        <th rowspan="2">CYP</th>
                                                        <th rowspan="2">Users</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
                                                            foreach ($proNames as $name) {
                                                                echo "<th width='" . (70 / count($items)) . "%'>$name</th>";
                                                            }
															if ($proType != $var && $count > 1){
																echo "<th width='100'>Total</th>";
															}
															$var = $proType;
															$count++;
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
													$counter = 1;
                                                    foreach ($hfType as $id => $hfName) {
                                                        ?>
                                                        <tr>
                                                        	<td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $hfName; ?></td>
                                                            <td class="center"><?php echo $totalOutlets[$id]; ?></td>
                                                            <?php
															$var = '';
															$count = 1;
															foreach ($product as $proType => $proNames) {
																$methodTypeTotal = 0;
																foreach ($proNames as $methodName) {
																	$methodTypeTotal = $methodTypeTotal + $data[$id][$methodName];
																	echo "<td class=\"right\">" . number_format($data[$id][$methodName]) . "</td>";
																}
																if ($proType != $var && $count > 1){
																	echo "<td class=\"right\">" . number_format($methodTypeTotal) . "</td>";
																}
																$var = $proType;
																$count++;
															}
															echo "<th class=\"right\">" . number_format(array_sum($data[$id]['CYP'])) . "</th>";
															echo "<th class=\"right\">" . number_format(array_sum($data[$id]['Users'])) . "</th>";
                                                            ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="right" colspan="2">Total</th>
                                                        <th class="center"><?php echo number_format(array_sum($totalOutlets));?></th>
														<?php
														$var = '';
														$count = 1;
														foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($total[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($total[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
														echo "<th class=\"right\">" . number_format(array_sum($total['CYP'])) . "</th>";
														echo "<th class=\"right\">" . number_format(array_sum($total['Users'])) . "</th>";
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">CYP</th>
														<?php
														$var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($totalCYP[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($totalCYP[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">Users</th>
														<?php
														$var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($totalUsers[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($totalUsers[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
                                                        ?>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            
                                        	<h5 style="margin-top:20px;"  class="center bold">
                                            	Monthly Performance Report(District wise) For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear; ?><br>
												Inrespect of Population Welfare Department <?php echo $provinceName?>
                                            </h5>
                                            <?php
											// Unset varibles
											unset($data, $total, $issue, $totalUsers, $totalCYP, $items, $hfType, $totalOutlets, $product);
											// District Wise 
											$qry = "SELECT
														B.dist_id,
														B.LocName,
														B.itm_id,
														B.itm_name,
														B.itm_category,
														B.itm_type,
														B.total_outlets,
														A.performance,
														(A.CYPFactor * A.performance) AS CYP,
														(A.userFactor * A.performance) AS Users
													FROM
														(
															SELECT
																tbl_hf_data.item_id,
																tbl_warehouse.dist_id,
																SUM(tbl_hf_data.issue_balance) AS performance,
																itminfo_tab.user_factor AS userFactor,
																itminfo_tab.extra AS CYPFactor,
																itminfo_tab.itm_category
															FROM
																tbl_warehouse
															INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
															INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
															WHERE
																tbl_warehouse.prov_id = $selProv
															AND tbl_warehouse.stkid = $stakeholder
															AND tbl_hf_data.reporting_date = '$reportingDate'
															AND itminfo_tab.itm_category = 1
															GROUP BY
																tbl_warehouse.dist_id,
																tbl_hf_data.item_id
															UNION
																SELECT
																	tbl_hf_data.item_id,
																	tbl_warehouse.dist_id,
																	SUM(tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) AS performance,
																	itminfo_tab.user_factor AS userFactor,
																	itminfo_tab.extra AS CYPFactor,
																	itminfo_tab.itm_category
																FROM
																	tbl_warehouse
																INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
																INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
																INNER JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
																WHERE
																	tbl_warehouse.prov_id = $selProv
																AND tbl_warehouse.stkid = $stakeholder
																AND tbl_hf_data.reporting_date = '$reportingDate'
																AND itminfo_tab.itm_category = 2
																GROUP BY
																	tbl_warehouse.dist_id,
																	tbl_hf_data.item_id
														) A
													RIGHT JOIN (
														SELECT
															COUNT(DISTINCT tbl_warehouse.wh_id) AS total_outlets,
															tbl_warehouse.dist_id,
															tbl_locations.LocName,
															itminfo_tab.itm_id,
															itminfo_tab.itm_name,
															itminfo_tab.frmindex,
															itminfo_tab.itm_category,
															CONCAT(itminfo_tab.method_type, '(', itminfo_tab.itm_type, ')') AS itm_type,
															itminfo_tab.method_rank
														FROM
															tbl_warehouse
														INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
														INNER JOIN tbl_hf_type ON tbl_warehouse.hf_type_id = tbl_hf_type.pk_id
														INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
														INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid,
														itminfo_tab
													INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
													WHERE
														tbl_warehouse.prov_id = $selProv
													AND tbl_warehouse.stkid = $stakeholder
													AND stakeholder.lvl = 7
													AND stakeholder_item.stkid = $stakeholder
													GROUP BY
														tbl_warehouse.dist_id,
														itminfo_tab.itm_id
													) B ON A.dist_id = B.dist_id
													AND A.item_id = B.itm_id
													ORDER BY
														B.LocName ASC,
														B.method_rank ASC,
														B.frmindex ASC";
											$qryRes = mysql_query($qry);
											
											while ($row = mysql_fetch_array($qryRes)) {
												if (!in_array($row['itm_name'], $items)){
													$items[] = $row['itm_name'];
													$product[$row['itm_type']][] = $row['itm_name'];
												}
												if (!in_array($row['LocName'], $distName)){
													$distName[$row['dist_id']] = $row['LocName'];
													if ($selProv == 3){
														// Get Facilities Count
														$qry = "SELECT REPgetNonProgramFacilities('D', $stakeholder, ".$row['dist_id'].", ".$selProv.", '$reportingDate') AS total_outlets FROM DUAL ";
														$hfCountRow = mysql_fetch_array(mysql_query($qry));
														
														$totalOutlets[$row['dist_id']] = (!empty($hfCountRow['total_outlets'])) ? $hfCountRow['total_outlets'] : '';
													}else{
														$totalOutlets[$row['dist_id']] = $row['total_outlets'];
													}
												}
												
												
												$data[$row['dist_id']]['CYP'][] = $row['CYP'];
												$data[$row['dist_id']]['Users'][] = $row['Users'];
												$data[$row['dist_id']][$row['itm_name']] = $row['performance'];
												
												$total['CYP'][] = $row['CYP'];						
												$totalCYP[$row['itm_name']][] = $row['CYP'];
												$total['Users'][] = $row['Users'];
												$totalUsers[$row['itm_name']][] = $row['Users'];
												$total[$row['itm_name']][] = $row['performance'];
											}
											/*echo "<pre>";
											print_r($totalCYP);
											echo "</pre>";
											exit;*/
											?>
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                    	<th rowspan="2">S.No.</th>
                                                        <th rowspan="2" width="13%">Name of Service Outlet</th>
                                                        <th rowspan="2" width="7%">No. of Outlets</th>
                                                        <?php
                                                        foreach ($product as $proType => $proNames) {
															if ($proType == 'Condoms(PCs)'){
                                                            	echo "<th colspan=" . sizeof($proNames) . ">$proType</th>";
															} else{
	                                                            echo "<th colspan=" . (sizeof($proNames) + 1) . ">$proType</th>";
															}
                                                        }
                                                        ?>
                                                        <th rowspan="2">CYP</th>
                                                        <th rowspan="2">Users</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
                                                            foreach ($proNames as $name) {
                                                                echo "<th width='" . (70 / count($items)) . "%'>$name</th>";
                                                            }
															if ($proType != $var && $count > 1){
																echo "<th width='100'>Total</th>";
															}
															$var = $proType;
															$count++;
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
													$counter = 1;
                                                    foreach ($distName as $id => $name) {
                                                        ?>
                                                        <tr>
                                                        	<td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $name; ?></td>
                                                            <td class="center"><?php echo $totalOutlets[$id]; ?></td>
                                                            <?php
															$var = '';
															$count = 1;
															foreach ($product as $proType => $proNames) {
																$methodTypeTotal = 0;
																foreach ($proNames as $methodName) {
																	$methodTypeTotal = $methodTypeTotal + $data[$id][$methodName];
																	echo "<td class=\"right\">" . number_format($data[$id][$methodName]) . "</td>";
																}
																if ($proType != $var && $count > 1){
																	echo "<td class=\"right\">" . number_format($methodTypeTotal) . "</td>";
																}
																$var = $proType;
																$count++;
															}
															echo "<th class=\"right\">" . number_format(array_sum($data[$id]['CYP'])) . "</th>";
															echo "<th class=\"right\">" . number_format(array_sum($data[$id]['Users'])) . "</th>";
                                                            ?>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th class="right" colspan="2">Total</th>
                                                        <th class="center"><?php echo number_format(array_sum($totalOutlets));?></th>
														<?php
														$var = '';
														$count = 1;
														foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($total[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($total[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
														echo "<th class=\"right\">" . number_format(array_sum($total['CYP'])) . "</th>";
														echo "<th class=\"right\">" . number_format(array_sum($total['Users'])) . "</th>";
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">CYP</th>
														<?php
														$var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($totalCYP[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($totalCYP[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">Users</th>
														<?php
														$var = '';
														$count = 1;
                                                        foreach ($product as $proType => $proNames) {
															$methodTypeTotal = 0;
															foreach ($proNames as $methodName) {
																$methodTypeTotal = $methodTypeTotal + array_sum($totalUsers[$methodName]);
																echo "<th class=\"right\">" . number_format(array_sum($totalUsers[$methodName])) . "</th>";
															}
															if ($proType != $var && $count > 1){
																echo "<th class=\"right\">" . number_format($methodTypeTotal) . "</th>";
															}
															$var = $proType;
															$count++;
														}
                                                        ?>
                                                    </tr>
                                                </tfoot>
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
				unset($data, $issue, $totalUsers, $totalCYP, $items, $distName, $totalOutlets, $product);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
</body>
</html>