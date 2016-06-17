<?php
/**
 * spr10
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
include(PUBLIC_PATH . "html/header.php");
//report id
$rptId = 'spr10';
//if submitted
if (isset($_POST['submit'])) {
    //from date
    $fromDate = isset($_POST['from_date']) ? mysql_real_escape_string($_POST['from_date']) : '';
    //to date
    $toDate = isset($_POST['to_date']) ? mysql_real_escape_string($_POST['to_date']) : '';
    //selected province
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    //district id
    $districtId = mysql_real_escape_string($_POST['district']);
    //select query
    // Get district name
    $qry = "SELECT
				tbl_locations.LocName
            FROM
				tbl_locations
            WHERE
				tbl_locations.PkLocID = $districtId";
    //fetch results
    $row = mysql_fetch_array(mysql_query($qry));
    //distrct name 
    $distrctName = $row['LocName'];
    //file name 
    $fileName = 'SPR10_' . $distrctName . '_for_' . $fromDate . '-' . $toDate;
}
?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
        <?php
        //include top
        include PUBLIC_PATH . "html/top.php";
        //include top_im        
        include PUBLIC_PATH . "html/top_im.php";
        ?>

        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">District Contraceptive Performance</h3>
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
                //if submitted
                if (isset($_POST['submit'])) {
                    //select query
                    //gets
                    //warehouse id,
                    //warehouse name,
                    //item name,
                    //issue balance,
                    //performed,
                    //reffered,
                    //item category,
                    //sales,
                    //CYP
                    $qry = "SELECT
								A.wh_id,
								A.wh_name,
								A.itm_name,
								A.issue_balance,
								A.performed,
								A.reffered,
								A.itm_category,
								(REPgetItemPrice('$toDate-01', 1, $selProv, A.itm_id) * A.issue_balance) AS sales,
								IF(A.itm_category = 2, (A.CYPFactor * A.performed), (A.CYPFactor * A.issue_balance)) AS CYP
							FROM
								(
									SELECT DISTINCT
										tbl_warehouse.wh_id,
										tbl_warehouse.wh_name,
										SUM(tbl_hf_data.issue_balance) AS issue_balance,
										itminfo_tab.extra AS CYPFactor,
										itminfo_tab.itm_id,
										itminfo_tab.itm_name,
										itminfo_tab.itm_category,
										tbl_hf_type_rank.hf_type_rank,
										SUM(tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) AS performed,
										IF (tbl_warehouse.hf_type_id IN(4, 5), (IF (tbl_hf_data_reffered_by.hf_type_id IN(4, 5), SUM(tbl_hf_data_reffered_by.ref_surgeries), 0)), SUM(tbl_hf_data.issue_balance)) AS reffered
									FROM
										tbl_warehouse
									INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
									INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
									INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
									LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
									WHERE
										tbl_warehouse.dist_id = $districtId
									AND tbl_hf_type_rank.stakeholder_id = $stakeholder
									AND tbl_warehouse.stkid = $stakeholder
									AND tbl_hf_type_rank.province_id = $selProv
									AND DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') BETWEEN '$fromDate' AND '$toDate'
									GROUP BY
										tbl_warehouse.wh_id,
										tbl_hf_data.item_id
									ORDER BY
										IF (tbl_warehouse.wh_rank = '' OR tbl_warehouse.wh_rank IS NULL, 1, 0),
										tbl_warehouse.wh_rank,
										tbl_hf_type_rank.hf_type_rank ASC,
										tbl_warehouse.wh_name ASC,
										itminfo_tab.frmindex ASC
								) A";
                    //query results
                    $qryRes = mysql_query($qry);
                    //fetch results
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                        <?php
                        //include sub_dist_reports
                        include('sub_dist_reports.php');
                        ?>
                        <div class="col-md-12" style="overflow:auto;">
                            <?php
                            $whId = '';
                            $itemId = '';
                            $items = $whName = array();
                            //fetch results
                            while ($row = mysql_fetch_array($qryRes)) {
                                if (!in_array($row['itm_name'], $items) && $row['itm_category'] != 2) {
                                    $items[] = $row['itm_name'];
                                }
                                //check warehouse name
                                if (!in_array($row['wh_name'], $whName)) {
                                    $whName[$row['wh_id']] = $row['wh_name'];
                                }

                                $data[$row['wh_id']]['CYP'][] = round($row['CYP']);
                                $data[$row['wh_id']]['sales'][] = round($row['sales']);
                                $total['CYP'][] = round($row['CYP']);
                                $total['sales'][] = round($row['sales']);
                                //check  item category
                                if ($row['itm_category'] == 2) {
                                    $data[$row['wh_id']]['cs_done'][] = $row['performed'];
                                    $data[$row['wh_id']]['cs_reffer'][] = $row['reffered'];
                                    $total['cs_done'][] = $row['performed'];
                                    $total['cs_reffer'][] = $row['reffered'];
                                } else {
                                    $data[$row['wh_id']][$row['itm_name']] = $row['issue_balance'];
                                    $total[$row['itm_name']][] = $row['issue_balance'];
                                }
                            }
                            ?>
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <h4 class="center">
                                            Individual Outlet wise Contraceptive Performance <br>
        <?php
        if ($fromDate != $toDate) {
            //set reporting period
            $reportingPeriod = "For the period of " . date('M-Y', strtotime($fromDate)) . ' to ' . date('M-Y', strtotime($toDate));
        } else {
            //set reporting period	
            $reportingPeriod = "For the month of " . date('M-Y', strtotime($fromDate));
        }
        ?>
                                            <?php echo $reportingPeriod . ', District ' . $distrctName; ?>
                                        </h4>
                                    </td>
                                    <td>
                                        <h4 class="right">SPR-10</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top: 10px;">
                                        <table id="myTable" cellspacing="0" align="center">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">S.No</th>
                                                    <th rowspan="2" style="width:150px !important;">Name of the Outlet</th>
        <?php
        //fetch items
        foreach ($items as $name) {
            echo "<th>$name</th>";
        }
        ?>
                                                    <th colspan="2">Surgery Cases</th>
                                                    <th rowspan="2">CYP</th>
                                                    <th rowspan="2">Sales (Rs)</th>
                                                    <th rowspan="2">Remarks</th>
                                                </tr>
                                                <tr>
        <?php
        foreach ($items as $name) {
            echo "<th>(Achivement)</th>";
        }
        ?>
                                                    <th>Reffered</th>
                                                    <th>Performed</th>
                                                </tr>
                                            </thead>
                                            <tbody>
        <?php
        $counter = 1;
        foreach ($whName as $id => $name) {
            ?>
                                                    <tr>
                                                        <td class="center"><?php echo $counter++; ?></td>
                                                        <td><?php echo $name; ?></td>
                                                    <?php
                                                    //fetch items
                                                    foreach ($items as $methodName) {
                                                        echo "<td class=\"right\">" . number_format($data[$id][$methodName]) . "</td>";
                                                    }
                                                    echo "<td class=\"right\">" . number_format(array_sum($data[$id]['cs_reffer'])) . "</td>";
                                                    echo "<td class=\"right\">" . number_format(array_sum($data[$id]['cs_done'])) . "</td>";
                                                    ?>
                                                        <td class="right" style="width:90px;"><?php echo number_format(array_sum($data[$id]['CYP'])); ?></td>
                                                        <td class="right"><?php echo number_format(array_sum($data[$id]['sales'])); ?></td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                        <?php
                                                    }
                                                    ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th class="right" colspan="2">Total</th>
                                                <?php
                                                //fetch items
                                                foreach ($items as $methodName) {
                                                    echo "<th class=\"right\">" . number_format(array_sum($total[$methodName])) . "</th>";
                                                }
                                                echo "<th class=\"right\">" . number_format(array_sum($total['cs_reffer'])) . "</th>";
                                                echo "<th class=\"right\">" . number_format(array_sum($total['cs_done'])) . "</th>";
                                                echo "<th class=\"right\">" . number_format(array_sum($total['CYP'])) . "</th>";
                                                echo "<th class=\"right\">" . number_format(array_sum($total['sales'])) . "</th>";
                                                ?>
                                                    <th>&nbsp;</th>
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
unset($data, $issue, $items, $whName);
?>
        </div>
    </div>
</div>
            <?php
            //include footer
            include PUBLIC_PATH . "/html/footer.php";
            //include combos
            include ('combos.php');
            ?>
</body>
</html>