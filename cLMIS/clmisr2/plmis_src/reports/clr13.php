<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'clr13';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $districtId = mysql_real_escape_string($_POST['district']);
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

     // Get district name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $districtId";
    $row = mysql_fetch_array(mysql_query($qry));
    $distrctName = $row['LocName'];

    $fileName = 'CLR13_' . $distrctName . '_' . $provinceName . '_for_' . date('M-Y', strtotime($reportingDate));
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
								B.wh_id,
								B.wh_name,
								B.itm_id,
								B.itm_name,
								A.opening_balance AS OB,
								A.received_balance AS Rcv,
								A.issue_balance AS Issue,
								A.closing_balance AS CB
							FROM
								(
									SELECT
										tbl_warehouse.wh_id,
										tbl_warehouse.wh_name,
										tbl_hf_data.opening_balance,
										tbl_hf_data.received_balance,
										tbl_hf_data.issue_balance,
										tbl_hf_data.closing_balance,
										itminfo_tab.itm_id,
										itminfo_tab.itm_name,
										tbl_hf_type_rank.hf_type_id
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
									INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
									WHERE
										tbl_warehouse.dist_id = $districtId
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_data.reporting_date = '$reportingDate'
									AND itminfo_tab.itm_category = 1
								) A
							RIGHT JOIN (
								SELECT
									tbl_warehouse.wh_id,
									tbl_warehouse.wh_name,
									tbl_warehouse.wh_rank,
									itminfo_tab.itm_id,
									itminfo_tab.itm_name,
									itminfo_tab.frmindex,
									tbl_hf_type_rank.hf_type_id,
									tbl_hf_type_rank.hf_type_rank
								FROM
									tbl_warehouse
								INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
								INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id,
								itminfo_tab
								INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
							WHERE
								tbl_warehouse.dist_id = $districtId
							AND tbl_warehouse.stkid = $stakeholder
							AND stakeholder_item.stkid = $stakeholder
							AND itminfo_tab.itm_category = 1
							) B ON A.wh_id = B.wh_id
							AND A.itm_id = B.itm_id
							ORDER BY
								 IF (B.wh_rank = '' OR B.wh_rank IS NULL, 1, 0),
								 B.wh_rank ASC,
								 B.hf_type_rank ASC,
								 B.wh_name ASC,
								 B.frmindex ASC";
                    $qryRes = mysql_query($qry);
					
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <?php
                                $distId = '';
                                while ($row = mysql_fetch_array($qryRes)) {
									if (!in_array($row['wh_name'], $whName)){
										$whName[$row['wh_id']] = $row['wh_name'];
									}
									$items[$row['itm_id']] = $row['itm_name'];
                                    $data[$row['wh_id']]['OB'][$row['itm_id']] = $row['OB'];
                                    $data[$row['wh_id']]['Rcv'][$row['itm_id']] = $row['Rcv'];
                                    $data[$row['wh_id']]['Issue'][$row['itm_id']] = $row['Issue'];
                                    $data[$row['wh_id']]['CB'][$row['itm_id']] = $row['CB'];
                                }
                                ?>
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                Health Facility Wise Contraceptive Stock Report for <?php echo $distrctName; ?> <br>
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
                                                        <th rowspan="2" width="10%">Health Facility</th>
                                                        <?php
                                                        foreach ($items as $id => $name) {
                                                            echo "<th colspan=\"4\">$name</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        foreach ($items as $name) {
                                                            echo "<th width='" . (85 / (count($items) * 4)) . "%'>Opening Balance</th>";
                                                            echo "<th width='" . (85 / (count($items) * 4)) . "%'>Receive</th>";
                                                            echo "<th width='" . (85 / (count($items) * 4)) . "%'>Issue</th>";
                                                            echo "<th width='" . (85 / (count($items) * 4)) . "%'>Closing Balance</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    $opening_gtotal_dist = $receive_gtotal_dist = $issue_gtotal_dist = $closing_gtotal_dist = 0;
                                                    foreach ($whName as $id => $name) {
                                                        ?>
                                                        <tr>
                                                            <td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $name; ?></td>
                                                            <?php
                                                            $district_total_html = $field_total_html = $facilities_html = "";
                                                            foreach ($items as $itmId => $hfName) {
                                                                $opening[$itmId][] = $data[$id]['OB'][$itmId];
                                                                $receive[$itmId][] = $data[$id]['Rcv'][$itmId];
                                                                $issue[$itmId][] = $data[$id]['Issue'][$itmId];
                                                                $closing[$itmId][] = $data[$id]['CB'][$itmId];

                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['OB'][$itmId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['Rcv'][$itmId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['Issue'][$itmId]) . "</td>";
                                                                $facilities_html .= "<td class=\"right\">" . number_format($data[$id]['CB'][$itmId]) . "</td>";
                                                            }
                                                            echo $facilities_html;
                                                            ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="right">Total</th>
                                                            <?php
                                                            foreach ($items as $itmId => $hfName) {
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format(array_sum($opening[$itmId])) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format(array_sum($receive[$itmId])) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format(array_sum($issue[$itmId])) . "</th>";
                                                                $gtotal_facilities .= "<th class=\"right\">" . number_format(array_sum($closing[$itmId])) . "</th>";
                                                            }
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
                unset($data, $items, $whName);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <?php include ('combos.php'); ?>
</body>
</html>