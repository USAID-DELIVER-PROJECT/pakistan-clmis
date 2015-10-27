<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'spr8';

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
    $fileName = 'SPR8_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
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
								B.wh_id,
								B.wh_name,
								B.itm_id,
								B.itm_name,
								B.itm_category,
								B.wh_rank,
								B.hf_type_rank,
								B.hf_type_id,
								B.frmindex,
								A.ref_CS_Cases,
								A.new,
								A.old,
								A.total,
								A.pre_natal_new,
								A.pre_natal_old,
								A.post_natal_new,
								A.post_natal_old,
								A.ailment_children,
								A.ailment_adults,
								A.general_ailment
							FROM
								(
									SELECT

										IF (tbl_warehouse.hf_type_id IN(4, 5), SUM(IF (tbl_hf_data_reffered_by.hf_type_id IN(4, 5), tbl_hf_data_reffered_by.ref_surgeries, 0)), tbl_hf_data.issue_balance) AS ref_CS_Cases,
										tbl_warehouse.wh_id,
										tbl_warehouse.hf_type_id,
										tbl_hf_data.new,
										tbl_hf_data.old,
										(tbl_hf_data.new + tbl_hf_data.old) AS total,
										tbl_hf_mother_care.pre_natal_new,
										tbl_hf_mother_care.pre_natal_old,
										tbl_hf_mother_care.post_natal_new,
										tbl_hf_mother_care.post_natal_old,
										tbl_hf_mother_care.ailment_children,
										tbl_hf_mother_care.ailment_adults,
										tbl_hf_mother_care.general_ailment,
										tbl_hf_data.item_id
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									INNER JOIN tbl_hf_mother_care ON tbl_warehouse.wh_id = tbl_hf_mother_care.warehouse_id
									WHERE
										tbl_hf_data.reporting_date = '$reportingDate'
									AND tbl_warehouse.dist_id = $districtId
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_mother_care.reporting_date = '$reportingDate'
									GROUP BY
										tbl_warehouse.wh_id,
										tbl_hf_data.item_id
								) A
							RIGHT JOIN (
								SELECT
									itminfo_tab.itm_id,
									itminfo_tab.frmindex,
									itminfo_tab.itm_name,
									itminfo_tab.itm_category,
									tbl_hf_type_rank.hf_type_rank,
									tbl_warehouse.wh_id,
									tbl_warehouse.wh_name,
									tbl_warehouse.wh_rank,
									tbl_warehouse.hf_type_id
								FROM
									tbl_warehouse
								INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
								INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id,
								itminfo_tab
							INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
							WHERE
								tbl_warehouse.stkid = $stakeholder
							AND stakeholder_item.stkid = $stakeholder
							AND tbl_warehouse.dist_id = $districtId
							AND tbl_hf_type_rank.province_id = $selProv
							AND tbl_hf_type_rank.stakeholder_id = $stakeholder
							) B ON A.wh_id = B.wh_id
							AND A.item_id = B.itm_id
							AND A.hf_type_id = B.hf_type_id
							ORDER BY
								IF (B.wh_rank = '' OR B.wh_rank IS NULL, 1, 0),
								B.wh_rank,
								B.hf_type_rank ASC,
								B.wh_name ASC,
								B.frmindex ASC";
                    $qryRes = mysql_query($qry);
					
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                $items = '';
                                $whName = '';
								$wh_id = '';
                                while ($row = mysql_fetch_array($qryRes)) {
                                    if (!in_array($row['itm_name'], $items) && $row['itm_category'] != 2){
										$items[] = $row['itm_name'];
									}
									if (!in_array($row['wh_name'], $whName)){
										$whName[$row['wh_id']] = $row['wh_name'];
										$whType[$row['wh_id']] = $row['hf_type_id'];
									}
									
									if ( $row['itm_category'] == 2 )
									{
										if ($row['itm_id'] == '31')
										{
                                    		$data[$row['wh_id']]['ref_CS_Cases_male'] = $row['ref_CS_Cases'];
										}
										if ($row['itm_id'] == '32')
										{
                                    		$data[$row['wh_id']]['ref_CS_Cases_female'] = $row['ref_CS_Cases'];
										}
									}
									else
									{
										$data[$row['wh_id']][$row['itm_name']]['new'] = $row['new'];
										$data[$row['wh_id']][$row['itm_name']]['old'] = $row['old'];
										$data[$row['wh_id']][$row['itm_name']]['total'] = $row['total'];
									}
                                    $data[$row['wh_id']]['pre_natal_new'] = $row['pre_natal_new'];
                                    $data[$row['wh_id']]['pre_natal_old'] = $row['pre_natal_old'];
                                    $data[$row['wh_id']]['post_natal_new'] = $row['post_natal_new'];
                                    $data[$row['wh_id']]['post_natal_old'] = $row['post_natal_old'];
                                    $data[$row['wh_id']]['ailment_children'] = $row['ailment_children'];
                                    $data[$row['wh_id']]['ailment_adults'] = $row['ailment_adults'];
                                    $data[$row['wh_id']]['general_ailment'] = $row['general_ailment'];
									
									// To Show Total
									if ( $row['itm_category'] == 2 )
									{
										if ($row['itm_id'] == '31')
										{
                                    		$total['ref_CS_Cases_male'][] = $row['ref_CS_Cases'];
										}
										if ($row['itm_id'] == '32')
										{
                                    		$total['ref_CS_Cases_female'][] = $row['ref_CS_Cases'];
										}
									}
									else
									{
										$total[$row['itm_name']]['new'][] = $row['new'];
										$total[$row['itm_name']]['old'][] = $row['old'];
										$total[$row['itm_name']]['total'][] = $row['total'];
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
								/*echo "<pre>";
								print_r($total);
								exit;*/
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                            <?php
											if ($selProv == 2){
                                                echo "District Monthly Activities Report <br>";
                                            }else {
                                                echo "District Monthly Report of Family Planning Activities <br>";
											}
											?>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">SPR-8</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                        <?php
                                        if ($selProv == 3)
										{
										?>
                                            <table id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">S.No</th>
                                                        <th rowspan="2">Name of the Outlet</th>
                                                        <?php
                                                        foreach ($items as $name) {
                                                            echo "<th colspan=\"3\">$name</th>";
                                                        }
                                                        ?>
                                                        <th rowspan="2">Surgery Cases</th>
                                                        <th colspan="4">Mothercare Cases</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($items as $name) {
                                                            echo "<th>New</th>";
                                                            echo "<th>Old</th>";
                                                            echo "<th>Total</th>";
                                                        }
                                                        ?>
                                                        <th>Ante-natal</th>
                                                        <th>Post-natal</th>
                                                        <th>Children</th>
                                                        <th>Adults</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
												$counter = 1;
												$hfTypeId = '';
												$hfTypeCount = 1;
												$print = '';
												$subTotal = '';
                                                foreach ($whName as $whId => $whName) {
													if ($whType[$whId] != $hfTypeId){
														$hfTypeId = $whType[$whId];
														$print = true;
														if ( $hfTypeCount > 1 && $print == true )
														{
															//echo "<pre>";
															//print_r($subTotal);
															//exit;	
														?>
														<tr>
                                                            <th class="center" colspan="2">Total</th>
                                                        <?php
                                                            foreach ($items as $methodName) {
																echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['new']) != 0) ? number_format(array_sum($subTotal[$methodName]['new'])) : 0 ). "</th>";
                                                                echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['old']) != 0) ? number_format(array_sum($subTotal[$methodName]['old'])) : 0 ). "</th>";
                                                                echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['total']) != 0) ? number_format(array_sum($subTotal[$methodName]['total'])) : 0 ). "</th>";
                                                            }                                                            
                                                            $refCases = array_sum($subTotal['refCases']);
                                                            $preNatal = array_sum($subTotal['preNatal']);
                                                            $postNatal = array_sum($subTotal['postNatal']);
                                                            $ailmentChildren = array_sum($subTotal['ailment_children']);
                                                            $ailmentAdults = array_sum($subTotal['ailment_adults']);
                                                        ?>
                                                            <th class="right"><?php echo ($refCases != 0) ? number_format($refCases) : 0;?></th>
                                                            <th class="right"><?php echo ($preNatal != 0) ? number_format($preNatal) : 0;?></th>
                                                            <th class="right"><?php echo ($postNatal != 0) ? number_format($postNatal) : 0;?></th>
                                                            <th class="right"><?php echo ($ailmentChildren != 0) ? number_format($ailmentChildren) : 0;?></th>
                                                            <th class="right"><?php echo ($ailmentAdults != 0) ? number_format($ailmentAdults) : 0;?></th>
                                                        </tr>
														<?php
															unset($subTotal);
															$hfTypeCount = 1;
														}else{
															unset($subTotal);
														}
													}else{
														$hfTypeCount++;
														$print = false;
													}
													
                                                ?>
                                                	<tr>
                                                        <td class="center"><?php echo $counter++; ?></td>
                                                        <td><?php echo $whName;?></td>
                                                    <?php
														foreach ($items as $methodName) {
															$tot = $data[$whId][$methodName]['old'] + $data[$whId][$methodName]['new'];
															echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
															echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
															echo "<td class=\"right\">". ( ($tot != 0) ? number_format($tot) : 0 ). "</td>";
															$subTotal[$methodName]['new'][] = $data[$whId][$methodName]['new'];
															$subTotal[$methodName]['old'][] = $data[$whId][$methodName]['old'];
															$subTotal[$methodName]['total'][] = $tot;
														}
														$preNatal = $data[$whId]['pre_natal_new'] + $data[$whId]['pre_natal_old'];
														$postNatal = $data[$whId]['post_natal_new'] + $data[$whId]['post_natal_old'];
														$refCases = $data[$whId]['ref_CS_Cases_male'] + $data[$whId]['ref_CS_Cases_female'];
														
														$subTotal['refCases'][] = $refCases;
														$subTotal['preNatal'][] = $preNatal;
														$subTotal['postNatal'][] = $postNatal;
														$subTotal['ailment_children'][] = $data[$whId]['ailment_children'];
														$subTotal['ailment_adults'][] = $data[$whId]['ailment_adults'];
													?>
                                                        <td class="right"><?php echo ($refCases != 0) ? number_format($refCases) : 0;?></td>
                                                        <td class="right"><?php echo ($preNatal != 0) ? number_format($preNatal) : 0;?></td>
                                                        <td class="right"><?php echo ($postNatal != 0) ? number_format($postNatal) : 0;?></td>
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
													foreach ($items as $methodName) {
														$tot = array_sum($total[$methodName]['new']) + array_sum($total[$methodName]['old']);
														echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
														echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
														echo "<th class=\"right\">". ( ($tot != 0) ? number_format($tot) : 0 ). "</th>";
													}
													$preNatal = array_sum($total['pre_natal_new']) + array_sum($total['pre_natal_old']);
													$postNatal = array_sum($total['post_natal_new']) + array_sum($total['post_natal_old']);
													$refCases = array_sum($total['ref_CS_Cases_male']) + array_sum($total['ref_CS_Cases_female']);
													?>
                                                        <th class="right"><?php echo ($refCases  != 0) ? number_format($refCases ) : 0;?></th>
                                                        <th class="right"><?php echo ($preNatal != 0) ? number_format($preNatal) : 0;?></th>
                                                        <th class="right"><?php echo ($postNatal != 0) ? number_format($postNatal) : 0;?></th>
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
                                        	<table id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">S.No</th>
                                                        <th rowspan="2">Name of the Outlet</th>
                                                        <?php
                                                        foreach ($items as $name) {
                                                            echo "<th colspan=\"3\">$name</th>";
                                                        }
                                                        ?>
                                                        <th colspan="2">Surgery Cases</th>
                                                        <th colspan="4">Mothercare Cases</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($items as $name) {
                                                            echo "<th>New</th>";
                                                            echo "<th>Old</th>";
                                                            echo "<th>Total</th>";
                                                        }
                                                        ?>
                                                        <th>Male</th>
                                                        <th>Female</th>
                                                        <th>Ante-natal</th>
                                                        <th>Post-natal</th>
                                                        <th>Children</th>
                                                        <th>Ailments</th>
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
														foreach ($items as $methodName) {
															$tot = $data[$whId][$methodName]['old'] + $data[$whId][$methodName]['new'];
															echo "<td class=\"right\">". ( ($data[$whId][$methodName]['new'] != 0) ? number_format($data[$whId][$methodName]['new']) : 0 ). "</td>";
															echo "<td class=\"right\">". ( ($data[$whId][$methodName]['old'] != 0) ? number_format($data[$whId][$methodName]['old']) : 0 ). "</td>";
															echo "<td class=\"right\">". ( ($tot != 0) ? number_format($tot) : 0 ). "</td>";
														}
														$preNatal = $data[$whId]['pre_natal_new'] + $data[$whId]['pre_natal_old'];
														$postNatal = $data[$whId]['post_natal_new'] + $data[$whId]['post_natal_old'];
														$childs = $data[$whId]['ailment_children'] + $data[$whId]['ailment_adults'];
													?>
                                                        <td class="right"><?php echo ($data[$whId]['ref_CS_Cases_male'] != 0) ? number_format($data[$whId]['ref_CS_Cases_male']) : 0;?></td>
                                                        <td class="right"><?php echo ($data[$whId]['ref_CS_Cases_female'] != 0) ? number_format($data[$whId]['ref_CS_Cases_female']) : 0;?></td>
                                                        <td class="right"><?php echo ($preNatal != 0) ? number_format($preNatal) : 0;?></td>
                                                        <td class="right"><?php echo ($postNatal != 0) ? number_format($postNatal) : 0;?></td>
                                                        <td class="right"><?php echo ($childs != 0) ? number_format($childs) : 0;?></td>
                                                        <td class="right"><?php echo ($data[$whId]['general_ailment'] != 0) ? number_format($data[$whId]['general_ailment']) : 0;?></td>
													<?php 
												}
												?>
                                                </tbody>
                                                <tfoot>
                                                	<tr>
                                                        <th colspan="2" class="right">Total</th>
                                                    <?php
													foreach ($items as $methodName) {
														$tot = array_sum($total[$methodName]['new']) + array_sum($total[$methodName]['old']);
														echo "<th class=\"right\">". ( (array_sum($total[$methodName]['new']) != 0) ? number_format(array_sum($total[$methodName]['new'])) : 0 ). "</th>";
														echo "<th class=\"right\">". ( (array_sum($total[$methodName]['old']) != 0) ? number_format(array_sum($total[$methodName]['old'])) : 0 ). "</th>";
														echo "<th class=\"right\">". ( ($tot != 0) ? number_format($tot) : 0 ). "</th>";
													}
													$preNatal = array_sum($total['pre_natal_new']) + array_sum($total['pre_natal_old']);
													$postNatal = array_sum($total['post_natal_new']) + array_sum($total['post_natal_old']);
													$childs = array_sum($total['ailment_children']) + array_sum($total['ailment_adults']);
													?>
                                                        <th class="right"><?php echo (array_sum($total['ref_CS_Cases_male']) != 0) ? number_format(array_sum($total['ref_CS_Cases_male'])) : 0;?></th>
                                                        <th class="right"><?php echo (array_sum($total['ref_CS_Cases_female']) != 0) ? number_format(array_sum($total['ref_CS_Cases_female'])) : 0;?></th>
                                                        <th class="right"><?php echo ($preNatal != 0) ? number_format($preNatal) : 0;?></th>
                                                        <th class="right"><?php echo ($postNatal != 0) ? number_format($postNatal) : 0;?></th>
                                                        <th class="right"><?php echo ($childs != 0) ? number_format($childs) : 0;?></th>
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
                unset($items, $whName, $data, $total);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <?php include ('combos.php'); ?>
</body>
</html>