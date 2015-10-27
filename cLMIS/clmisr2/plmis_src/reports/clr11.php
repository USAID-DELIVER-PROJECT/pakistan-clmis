<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'clr11';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $selItem = mysql_real_escape_string($_POST['itm_id']);
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

    // Get Product name
    $qry = "SELECT
                itminfo_tab.itmrec_id,
                itminfo_tab.itm_name
            FROM
                itminfo_tab
            WHERE
                itminfo_tab.itm_id = '$selItem' ";
    $row = mysql_fetch_array(mysql_query($qry));
    $itemName = $row['itm_name'];
    $itemRecId = $row['itmrec_id'];

    $fileName = 'CLR11_' . $itemName . '_' . $provinceName . '_for_' . date('M-Y', strtotime($reportingDate));
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
                        <h3 class="page-title row-br-b-wp">District Wise Provincial Contraceptive Stock and Sale Report</h3>
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
									tbl_locations.PkLocID,
									tbl_locations.LocName,
									tbl_hf_type.pk_id,
									tbl_hf_type.hf_type,
									SUM(tbl_hf_type_data.opening_balance) AS OB,
									SUM(tbl_hf_type_data.received_balance) AS Rcv,
									SUM(tbl_hf_type_data.issue_balance) AS Issue,
									SUM(tbl_hf_type_data.closing_balance) AS CB
								FROM
									tbl_hf_type_data
								INNER JOIN tbl_hf_type ON tbl_hf_type.pk_id = tbl_hf_type_data.facility_type_id
								INNER JOIN tbl_locations ON tbl_locations.PkLocID = tbl_hf_type_data.district_id
								INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id
								WHERE
									tbl_locations.ParentID = $selProv
								AND tbl_hf_type_data.reporting_date = '$reportingDate'
								AND tbl_hf_type_data.item_id = $selItem
								AND tbl_hf_type_rank.province_id = $selProv
								AND tbl_hf_type_rank.stakeholder_id = $stakeholder
								GROUP BY
									tbl_hf_type_data.district_id,
									tbl_hf_type.hf_type
								ORDER BY
									tbl_locations.LocName ASC,
									tbl_hf_type_rank.hf_type_rank ASC";
                    $qryRes = mysql_query($qry);
                    $qryRes1 = mysql_query($qry);

                    // Get Distinct Health facility Types
                    while ($row = mysql_fetch_array($qryRes1)) {
                        $hfType[$row['pk_id']] = $row['hf_type'];
                    }
                    // Sort the array
                    //asort($hfType);		

                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                $distId = '';
                                while ($row = mysql_fetch_array($qryRes)) {
                                    if ($distId != $row['PkLocID']) {
                                        $districtName[$row['PkLocID']] = $row['LocName'];
                                        $distId = $row['PkLocID'];
                                    }
                                    $data[$row['PkLocID']]['OB'][$row['pk_id']] = $row['OB'];
                                    $data[$row['PkLocID']]['Rcv'][$row['pk_id']] = $row['Rcv'];
                                    $data[$row['PkLocID']]['Issue'][$row['pk_id']] = $row['Issue'];
                                    $data[$row['PkLocID']]['CB'][$row['pk_id']] = $row['CB'];
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                District Wise Provincial Contraceptive Stock and Sale Report for <?php echo $itemName; ?> <br>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', Province ' . $provinceName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">CLR-11</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-top: 10px;">
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" width="4%">S.No.</th>
                                                        <th rowspan="2" width="11%">District</th>
                                                        <th colspan="4">District Store Total</th>
                                                        <th colspan="4">Field Total</th>
                                                        <?php
                                                        foreach ($hfType as $id => $name) {
                                                            echo "<th colspan=\"4\">$name</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        //District Store Total Columns
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Opening Balance</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Receive</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Issue</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Closing Balance</th>";

                                                        //Field Total Columns
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Opening Balance</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Receive</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Issue</th>";
                                                        echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Closing Balance</th>";

                                                        foreach ($hfType as $name) {
                                                            echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Opening Balance</th>";
                                                            echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Receive</th>";
                                                            echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Issue</th>";
                                                            echo "<th width='" . (85 / (count($hfType) * 4)) . "%'>Closing Balance</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    $opening_gtotal_dist = $receive_gtotal_dist = $issue_gtotal_dist = $closing_gtotal_dist = 0;
                                                    foreach ($districtName as $id => $name) {

														// Get District warehouse
														$qry = "SELECT
																	tbl_warehouse.wh_id
																FROM
																	tbl_warehouse
																INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
																WHERE
																	tbl_warehouse.dist_id = $id
																AND tbl_warehouse.stkid = 1
																AND stakeholder.lvl = 3
																ORDER BY
																	tbl_warehouse.wh_id ASC
																LIMIT 1";
														$qryRes = mysql_fetch_array(mysql_query($qry));
														$distWH = $qryRes['wh_id'];
														
														$qry_dist = "SELECT
																		tbl_warehouse.dist_id,
																		itminfo_tab.itm_id,
																		tbl_wh_data.wh_obl_a AS OB,
																		tbl_wh_data.wh_received AS Rcv,
																		tbl_wh_data.wh_issue_up AS Issue,
																		tbl_wh_data.wh_cbl_a AS CBDistStore
																	FROM
																		tbl_wh_data
																	INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
																	INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
																	WHERE
																		tbl_wh_data.RptDate = '$reportingDate'
																	AND tbl_wh_data.wh_id = $distWH
																	AND tbl_wh_data.item_id = '$itemRecId'
																	AND tbl_warehouse.stkid = $stakeholder
																	GROUP BY
																		tbl_wh_data.item_id";
                                                        $qryRes_dist = mysql_query($qry_dist);
														$opening_dist = $receive_dist = $issue_dist = $CBDistStore = 0;
                                                        if (mysql_num_rows($qryRes_dist) > 0) {
															$row_dist = mysql_fetch_array($qryRes_dist);
															$opening_dist = $row_dist['OB'];
															$receive_dist = $row_dist['Rcv'];
															$issue_dist = $row_dist['Issue'];
															$CBDistStore = $row_dist['CBDistStore'];
															
															$opening_gtotal_dist += $opening_dist;
															$receive_gtotal_dist += $receive_dist;
															$issue_gtotal_dist += $issue_dist;
															$closing_gtotal_dist += $CBDistStore;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $name; ?></td>
                                                            <?php
                                                            $district_total_html = $field_total_html = $facilities_html = "";
															$opening = $receive = $issue = $closing = 0;
                                                            foreach ($hfType as $hfId => $hfName) {
                                                                $opening += $data[$id]['OB'][$hfId];
                                                                $receive += $data[$id]['Rcv'][$hfId];
                                                                $issue += $data[$id]['Issue'][$hfId];
                                                                $closing += $data[$id]['CB'][$hfId];

                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['OB'][$hfId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['Rcv'][$hfId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['Issue'][$hfId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['CB'][$hfId]) . "</td>";
                                                            }
                                                            $district_total_html .= "<td class=\"right\">" . number_format($opening_dist) . "</td>";
                                                            $district_total_html .= "<td class=\"right\">" . number_format($receive_dist) . "</td>";
                                                            $district_total_html .= "<td class=\"right\">" . number_format($issue_dist) . "</td>";
                                                            $district_total_html .= "<td class=\"right\">" . number_format($CBDistStore) . "</td>";

                                                            $field_total_html .= "<td class=\"right\">" . number_format($opening) . "</td>";
                                                            $field_total_html .= "<td class=\"right\">" . number_format($receive) . "</td>";
                                                            $field_total_html .= "<td class=\"right\">" . number_format($issue) . "</td>";
                                                            $field_total_html .= "<td class=\"right\">" . number_format($closing) . "</td>";

                                                            echo $district_total_html;
                                                            echo $field_total_html;
                                                            echo $facilities_html;
                                                            ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                    $qry = "SELECT
																tbl_locations.PkLocID,
																tbl_locations.LocName,
																tbl_hf_type.pk_id,
																tbl_hf_type.hf_type,
																SUM(tbl_hf_type_data.opening_balance) AS OB,
																SUM(tbl_hf_type_data.received_balance) AS Rcv,
																SUM(tbl_hf_type_data.issue_balance) AS Issue,
																SUM(tbl_hf_type_data.closing_balance) AS CB
															FROM
																tbl_hf_type_data
															INNER JOIN tbl_hf_type ON tbl_hf_type.pk_id = tbl_hf_type_data.facility_type_id
															INNER JOIN tbl_locations ON tbl_locations.PkLocID = tbl_hf_type_data.district_id
															WHERE
																tbl_locations.ParentID = $selProv
															AND tbl_hf_type_data.reporting_date = '$reportingDate'
															AND tbl_hf_type_data.item_id = $selItem
															GROUP BY
																tbl_hf_type.hf_type
															ORDER BY
																tbl_hf_type.hf_rank ASC";
                                                    $qryRes = mysql_query($qry);
                                                    unset($data);
                                                    while ($row = mysql_fetch_array($qryRes)) {
                                                        $data[$row['pk_id']]['OB'] = $row['OB'];
                                                        $data[$row['pk_id']]['Rcv'] = $row['Rcv'];
                                                        $data[$row['pk_id']]['Issue'] = $row['Issue'];
                                                        $data[$row['pk_id']]['CB'] = $row['CB'];
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="right">Grand Total</th>
                                                            <?php
                                                            $gtotal_district = $gtotal_field_total = $gtotal_facilities = "";
															$opening = $receive = $issue = $closing = 0;
                                                            foreach ($hfType as $id => $name) {
                                                                $opening += $data[$id]['OB'];
                                                                $receive += $data[$id]['Rcv'];
                                                                $issue += $data[$id]['Issue'];
                                                                $closing += $data[$id]['CB'];
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format($data[$id]['OB']) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format($data[$id]['Rcv']) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format($data[$id]['Issue']) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format($data[$id]['CB']) . "</th>";
                                                            }

                                                            $gtotal_district .= "<th class=\"right\">" . number_format($opening_gtotal_dist) . "</th>";
                                                            $gtotal_district .= "<th class=\"right\">" . number_format($receive_gtotal_dist) . "</th>";
                                                            $gtotal_district .= "<th class=\"right\">" . number_format($issue_gtotal_dist) . "</th>";
                                                            $gtotal_district .= "<th class=\"right\">" . number_format($closing_gtotal_dist) . "</th>";
                                                            
                                                            $gtotal_field_total .= "<th class=\"right\">" . number_format($opening) . "</th>";
                                                            $gtotal_field_total .= "<th class=\"right\">" . number_format($receive) . "</th>";
                                                            $gtotal_field_total .= "<th class=\"right\">" . number_format($issue) . "</th>";
                                                            $gtotal_field_total .= "<th class=\"right\">" . number_format($closing) . "</th>";

                                                            echo $gtotal_district;
                                                            echo $gtotal_field_total;
                                                            echo $gtotal_facilities;
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
                unset($data, $issue, $hfType, $districtName);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
</body>
</html>