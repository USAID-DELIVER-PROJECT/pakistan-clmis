<?php

/**
 * spr8
 * @package reports
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("../includes/classes/AllClasses.php");
//include header
include(PUBLIC_PATH."html/header.php");
//report id
$rptId = 'spr8';
//user province id
$userProvId = $_SESSION['user_province1'];
//if submitted
if (isset($_POST['submit'])) {
    //get from date
    $fromDate = isset($_POST['from_date']) ? mysql_real_escape_string($_POST['from_date']) : '';
    //get to date
    $toDate = isset($_POST['to_date']) ? mysql_real_escape_string($_POST['to_date']) : '';
    //get selected province
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    //get district id
    $districtId = mysql_real_escape_string($_POST['district']);
//select query
    // Get district name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $districtId";
    //query result
    $row = mysql_fetch_array(mysql_query($qry));
    //district name
    $distrctName = $row['LocName'];
    //file name
    $fileName = 'SPR8_' . $distrctName . '_for_' . $fromDate . '-' . $toDate;
}
?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
		<?php 
                //include top
                include PUBLIC_PATH."html/top.php";
        //include top_im
        include PUBLIC_PATH."html/top_im.php";?>
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
                                <?php 
                                //sub_dist_form
                                include('sub_dist_form.php'); ?>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                //check if submitted
                if (isset($_POST['submit']))
				{
                    //select query
                    //gets
                    //warehouse id
                    //warehouse name
                    //item name
                    //item category
                    //warehouse rank
                    //hf type rank
                    //frm index
                    //ref CS cases
                    //new 
                    //old 
                    //total
                    //pre natal new
                    //pre natal old
                    //post natal new
                    //post natal old
                    //ailment children
                    //ailment adult
                    //general ailment
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
										SUM(tbl_hf_data.new) AS new,
										SUM(tbl_hf_data.old) AS old,
										SUM(tbl_hf_data.new + tbl_hf_data.old) AS total,
										SUM(tbl_hf_mother_care.pre_natal_new) AS pre_natal_new,
										SUM(tbl_hf_mother_care.pre_natal_old) AS pre_natal_old,
										SUM(tbl_hf_mother_care.post_natal_new) AS post_natal_new,
										SUM(tbl_hf_mother_care.post_natal_old) AS post_natal_old,
										SUM(tbl_hf_mother_care.ailment_children) AS ailment_children,
										SUM(tbl_hf_mother_care.ailment_adults) AS ailment_adults,
										SUM(tbl_hf_mother_care.general_ailment) AS general_ailment,
										tbl_hf_data.item_id
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									INNER JOIN tbl_hf_mother_care ON tbl_warehouse.wh_id = tbl_hf_mother_care.warehouse_id
									AND tbl_hf_mother_care.reporting_date = tbl_hf_data.reporting_date
									WHERE
										DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') BETWEEN '$fromDate' AND '$toDate'
									AND tbl_warehouse.dist_id = $districtId
									AND tbl_warehouse.stkid = $stakeholder
									AND DATE_FORMAT(tbl_hf_mother_care.reporting_date, '%Y-%m') BETWEEN '$fromDate' AND '$toDate'
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
							AND DATE_FORMAT(tbl_warehouse.reporting_start_month, '%Y-%m') <= '$toDate'
							) B ON A.wh_id = B.wh_id
							AND A.item_id = B.itm_id
							AND A.hf_type_id = B.hf_type_id
							ORDER BY
								IF (B.wh_rank = '' OR B.wh_rank IS NULL, 1, 0),
								B.wh_rank,
								B.hf_type_rank ASC,
								B.wh_name ASC,
								B.frmindex ASC";
                    //query result
                        $qryRes = mysql_query($qry);
					//check if result exists
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php 
                            //include sub_dist_reports
                            include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                //items
                                                                $items = '';
                                //warehouse name
                                                                $whName = '';
                                //warehouse id
								$wh_id = '';
								//items
                                                                $items = $whName = array();
                                //fetch results
                                                                while ($row = mysql_fetch_array($qryRes)) {
                                    //check item                                
                                    if (!in_array($row['itm_name'], $items) && $row['itm_category'] != 2){
										$items[] = $row['itm_name'];
									}
									if (!in_array($row['wh_name'], $whName)){
                                                                            //warehouse name
										$whName[$row['wh_id']] = $row['wh_name'];
									//warehouse type
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
									//new 	
                                                                            $data[$row['wh_id']][$row['itm_name']]['new'] = $row['new'];
									//old
                                                                            $data[$row['wh_id']][$row['itm_name']]['old'] = $row['old'];
									//total
                                                                            $data[$row['wh_id']][$row['itm_name']]['total'] = $row['total'];
									}
                                                                        //pre_natal_new
                                    $data[$row['wh_id']]['pre_natal_new'] = $row['pre_natal_new'];
                                    //pre_natal_old
                                    $data[$row['wh_id']]['pre_natal_old'] = $row['pre_natal_old'];
                                    //post_natal_new
                                    $data[$row['wh_id']]['post_natal_new'] = $row['post_natal_new'];
                                    //post_natal_old
                                    $data[$row['wh_id']]['post_natal_old'] = $row['post_natal_old'];
                                    //ailment_children
                                    $data[$row['wh_id']]['ailment_children'] = $row['ailment_children'];
                                    //ailment_adults
                                    $data[$row['wh_id']]['ailment_adults'] = $row['ailment_adults'];
                                    //general_ailment
                                    $data[$row['wh_id']]['general_ailment'] = $row['general_ailment'];
									
									// To Show Total
									if ( $row['itm_category'] == 2 )
									{
										if ($row['itm_id'] == '31')
										{
                                                                                    //total ref_CS_Cases_male
                                    		$total['ref_CS_Cases_male'][] = $row['ref_CS_Cases'];
										}
										if ($row['itm_id'] == '32')
										{
                                    		//total ref_CS_Cases_female
                                                                                    $total['ref_CS_Cases_female'][] = $row['ref_CS_Cases'];
										}
									}
									else
									{
                                                                            //new
										$total[$row['itm_name']]['new'][] = $row['new'];
										//old
                                                                                $total[$row['itm_name']]['old'][] = $row['old'];
										//total
                                                                                $total[$row['itm_name']]['total'][] = $row['total'];
									}
									if ( $wh_id != $row['wh_id'] )
									{
									//pre_natal_new
                                                                            $total['pre_natal_new'][] = $row['pre_natal_new'];
									//pre_natal_old
                                                                            $total['pre_natal_old'][] = $row['pre_natal_old'];
									//post_natal_new
                                                                            $total['post_natal_new'][] = $row['post_natal_new'];
									//post_natal_old
                                                                            $total['post_natal_old'][] = $row['post_natal_old'];
									//ailment children
                                                                            $total['ailment_children'][] = $row['ailment_children'];
									//ailment adut
                                                                            $total['ailment_adults'][] = $row['ailment_adults'];
									//general ailment
                                                                            $total['general_ailment'][] = $row['general_ailment'];
									//warehouse id
                                                                            $wh_id = $row['wh_id'];
									}
                                }
								
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
											
											if( $fromDate != $toDate )
											{
                                                                                            //reportint period
												$reportingPeriod = "For the period of " . date('M-Y', strtotime($fromDate)) . ' to ' . date('M-Y', strtotime($toDate));
											}
											else
											{
											//reportint period
                                                                                            $reportingPeriod = "For the month of " . date('M-Y', strtotime($fromDate));
											}
											?>
                                            <?php echo $reportingPeriod . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">SPR-8</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                        <?php
                                        //check sel Province
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
												//hf type id
                                                                                                $hfTypeId = '';
												//hf type count
                                                                                                $hfTypeCount = 1;
												//print
                                                                                                $print = '';
												//sub total
                                                                                                $subTotal = '';
                                                foreach ($whName as $whId => $whName) {
													if ($whType[$whId] != $hfTypeId){
														$hfTypeId = $whType[$whId];
														$print = true;
														if ( $hfTypeCount > 1 && $print == true )
														{
															
														?>
														<tr>
                                                            <th class="center" colspan="2">Total</th>
                                                        <?php
                                                            foreach ($items as $methodName) {
																echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['new']) != 0) ? number_format(array_sum($subTotal[$methodName]['new'])) : 0 ). "</th>";
                                                                echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['old']) != 0) ? number_format(array_sum($subTotal[$methodName]['old'])) : 0 ). "</th>";
                                                                echo "<th class=\"right\">". ( (array_sum($subTotal[$methodName]['total']) != 0) ? number_format(array_sum($subTotal[$methodName]['total'])) : 0 ). "</th>";
                                                            }                                            
                                                            //ref cases
                                                            $refCases = array_sum($subTotal['refCases']);
                                                            //pre natal
                                                            $preNatal = array_sum($subTotal['preNatal']);
                                                            //post natal
                                                            $postNatal = array_sum($subTotal['postNatal']);
                                                            //ailment children
                                                            $ailmentChildren = array_sum($subTotal['ailment_children']);
                                                            //ailment adult
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
															//new
                                                                                                                        $subTotal[$methodName]['new'][] = $data[$whId][$methodName]['new'];
                                                                                                                        //old
															$subTotal[$methodName]['old'][] = $data[$whId][$methodName]['old'];
                                                                                                                        //total
															$subTotal[$methodName]['total'][] = $tot;
														}
                                                                                                                //pre natal
														$preNatal = $data[$whId]['pre_natal_new'] + $data[$whId]['pre_natal_old'];
														//post natal
                                                                                                                $postNatal = $data[$whId]['post_natal_new'] + $data[$whId]['post_natal_old'];
														//ref cases 
                                                                                                                $refCases = $data[$whId]['ref_CS_Cases_male'] + $data[$whId]['ref_CS_Cases_female'];
														//sub total ref cases
														$subTotal['refCases'][] = $refCases;
														//sub total pre natal
                                                                                                                $subTotal['preNatal'][] = $preNatal;
														//sub total post natal
                                                                                                                $subTotal['postNatal'][] = $postNatal;
														//sub total ailment children
                                                                                                                $subTotal['ailment_children'][] = $data[$whId]['ailment_children'];
														//sub total ailment adult
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
                                                                                                        //pre natal
													$preNatal = array_sum($total['pre_natal_new']) + array_sum($total['pre_natal_old']);
													//post natal
                                                                                                        $postNatal = array_sum($total['post_natal_new']) + array_sum($total['post_natal_old']);
													//ref cases
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
                                                                                                                //pre natal
														$preNatal = $data[$whId]['pre_natal_new'] + $data[$whId]['pre_natal_old'];
														//post natal
                                                                                                                $postNatal = $data[$whId]['post_natal_new'] + $data[$whId]['post_natal_old'];
														//childs
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
	<?php 
        //include footer
        include PUBLIC_PATH."/html/footer.php";
        //include combos
     include ('combos.php'); ?>
</body>
</html>