<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'spr11';

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
    $fileName = 'SPR11_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
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
                        <h3 class="page-title row-br-b-wp">Project Wise Summary of Contraceptive Performance & CYP</h3>
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
								B.hf_type_id,
								B.hf_type,
								B.hf_type_rank,
								B.itm_id,
								B.itm_name,
								B.itm_category,
								B.itm_type,
								B.total_outlets,
								A.performance,
								A.CS_Performed,
								IF(A.itm_category = 2, (A.CYPFactor * A.CS_Performed), (A.CYPFactor * A.performance)) AS CYP,
								IF(A.itm_category = 2, (A.usersFactor * A.CS_Performed), (A.usersFactor * A.performance)) AS Users
							FROM
								(
									SELECT
										tbl_hf_data.item_id,
										tbl_warehouse.hf_type_id,
										SUM(tbl_hf_data.issue_balance) AS performance,
										SUM(tbl_hf_data_reffered_by.static) + SUM(tbl_hf_data_reffered_by.camp) AS CS_Performed,
										itminfo_tab.user_factor AS usersFactor,
										itminfo_tab.extra AS CYPFactor,
										itminfo_tab.itm_category
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
									LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									WHERE
										tbl_warehouse.dist_id = $districtId
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_data.reporting_date = '$reportingDate'
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
									itminfo_tab.itm_type
								FROM
									tbl_warehouse
								INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
								INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
								INNER JOIN tbl_hf_type ON tbl_warehouse.hf_type_id = tbl_hf_type.pk_id
								INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id,
								itminfo_tab
							INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
							WHERE
								tbl_warehouse.dist_id = $districtId
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
								B.frmindex";
                    $qryRes = mysql_query($qry);
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                while ($row = mysql_fetch_array($qryRes)) {
                                    if (!in_array($row['itm_name'], $items) && $row['itm_category'] != 2){
										$items[] = $row['itm_name'];
									}
									if (!in_array($row['hf_type'], $hfType)){
										$hfType[$row['hf_type_id']] = $row['hf_type'];
										if ($selProv == 3){
											if (!in_array($row['hf_type_id'], $hfPrograms)){
												// Get Facilities Count
												$qry = "SELECT REPgetNonProgramFacilities('HFTD', $stakeholder, ".$row['hf_type_id'].", ".$districtId.", '$reportingDate') AS total_outlets FROM DUAL ";
												$hfCountRow = mysql_fetch_array(mysql_query($qry));
												
												$totalOutlets[$row['hf_type_id']] = (!empty($hfCountRow['total_outlets'])) ? $hfCountRow['total_outlets'] : '';
											}else{
												$totalOutlets[$row['hf_type_id']] = $row['total_outlets'];
											}
										}else{
											$totalOutlets[$row['hf_type_id']] = $row['total_outlets'];
										}
									}
									
									
									$data[$row['hf_type_id']]['CYP'][] = round($row['CYP']);
									$data[$row['hf_type_id']]['Users'][] = round($row['Users']);
									$total['CYP'][] = round($row['CYP']);
									$total['Users'][] = round($row['Users']);
									
									$totalCYP[$row['itm_name']][] = round($row['CYP']);
									$totalUsers[$row['itm_name']][] = round($row['Users']);
									
									if ( $row['itm_category'] == 2 )
									{
										$data[$row['hf_type_id']]['cs_done'][] = $row['CS_Performed'];
										$total['cs_done'][] = $row['CS_Performed'];
									}
									else
									{
                                    	$data[$row['hf_type_id']][$row['itm_name']] = $row['performance'];
										$total[$row['itm_name']][] = $row['performance'];
									}
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                Outlet Wise Summary of Contraceptive Performance & CYP <br>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">SPR-11</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-top: 10px;">
                                        <?php
                                        if ( $selProv == 3 )
										{
										?>
                                        	<table id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">S.No.</th>
                                                        <th rowspan="2" nowrap>Name of the Outlet</th>
                                                        <th rowspan="2" width="5%">No. of Outlets</th>
                                                        <?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th>$methodName</th>";
                                                        }
                                                        ?>
                                                        <th>Surgery Cases</th>
                                                        <th rowspan="2">CYP</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th>(Achivement)</th>";
                                                        }
                                                        echo "<th>(Achivement)</th>";
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
                                                            foreach ($items as $methodName) {
                                                                echo "<td class=\"right\">" . number_format($data[$id][$methodName]) . "</td>";
                                                            }
															echo "<td class=\"right\">" . number_format(array_sum($data[$id]['cs_done'])) . "</td>";
                                                            ?>
                                                            <th class="right" style="width:90px;"><?php echo number_format(array_sum($data[$id]['CYP']));?></th>
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
                                                        foreach ($items as $methodName) {
                                                            echo "<th class=\"right\">" . number_format(array_sum($total[$methodName])) . "</th>";
                                                        }
														echo "<th class=\"right\">" . number_format(array_sum($total['cs_done'])) . "</th>";
														echo "<th class=\"right\">" . number_format(array_sum($total['CYP'])) . "</th>";
                                                        ?>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        <?php	
										}
										else
										{
										?>
                                            <table id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">S.No.</th>
                                                        <th rowspan="2" nowrap>Name of the Outlet</th>
                                                        <th rowspan="2" width="5%">No. of Outlets</th>
                                                        <?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th>$methodName</th>";
                                                        }
                                                        ?>
                                                        <th>Surgery Cases</th>
                                                        <th rowspan="2">CYP</th>
                                                        <th rowspan="2">Users</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th>(Achivement)</th>";
                                                        }
                                                        echo "<th>(Achivement)</th>";
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
                                                            foreach ($items as $methodName) {
                                                                echo "<td class=\"right\">" . number_format($data[$id][$methodName]) . "</td>";
                                                            }
															echo "<td class=\"right\">" . number_format(array_sum($data[$id]['cs_done'])) . "</td>";
                                                            ?>
                                                            <th class="right" style="width:90px;"><?php echo number_format(array_sum($data[$id]['CYP']));?></th>
                                                            <th class="right" style="width:90px;"><?php echo number_format(array_sum($data[$id]['Users']));?></th>
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
                                                        foreach ($items as $methodName) {
                                                            echo "<th class=\"right\">" . number_format(array_sum($total[$methodName])) . "</th>";
                                                        }
														echo "<th class=\"right\">" . number_format(array_sum($total['cs_done'])) . "</th>";
														echo "<th class=\"right\">" . number_format(array_sum($total['CYP'])) . "</th>";
														echo "<th class=\"right\">" . number_format(array_sum($total['Users'])) . "</th>";
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">CYP</th>
														<?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th class=\"right\">" . number_format(array_sum($totalCYP[$methodName])) . "</th>";
                                                        }
                                                        echo "<th class=\"right\">" . number_format(array_sum($totalCYP['Male']) + array_sum($totalCYP['Female'])) . "</th>";
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <th class="right" colspan="3">Users</th>
														<?php
                                                        foreach ($items as $methodName) {
                                                            echo "<th class=\"right\">" . number_format(array_sum($totalUsers[$methodName])) . "</th>";
                                                        }
                                                        echo "<th class=\"right\">" . number_format(array_sum($totalUsers['Male']) + array_sum($totalUsers['Female'])) . "</th>";
                                                        ?>
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
                unset($data, $total, $totalCYP, $totalUsers, $hfType, $items);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <?php include ('combos.php'); ?>
</body>
</html>