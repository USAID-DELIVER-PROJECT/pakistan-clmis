<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'clr15';

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
    $fileName = 'CLR15_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
	
	// Get District warehouse
	$qry = "SELECT
				tbl_warehouse.wh_id
			FROM
				tbl_warehouse
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
				tbl_warehouse.dist_id = $districtId
			AND tbl_warehouse.stkid = 1
			AND stakeholder.lvl = 3
			ORDER BY
				tbl_warehouse.wh_id ASC
			LIMIT 1";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	$distWH = $qryRes['wh_id'];
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
                        <h3 class="page-title row-br-b-wp">District Contraceptive Stock Report</h3>
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
					// Query for Part I
                	$qry = "SELECT
                                    A.OB,
                                    A.Rcv,
                                    A.Issue,
                                    B.itm_id,
                                    B.itm_name,
                                    B.generic_name,
                                    B.itm_category,
                                    B.method_type,
                                    B.method_rank,
                                    B.frmindex,
                                    B.CBDistStore,
                                    B.CBFldStore,
                                    B.CBTotal
                                FROM
                                    (
                                        SELECT
                                            tbl_warehouse.dist_id,
                                            itminfo_tab.itm_id,
                                            tbl_wh_data.wh_obl_a AS OB,
                                            tbl_wh_data.wh_received AS Rcv,
                                            tbl_wh_data.wh_issue_up AS Issue
                                        FROM
                                            tbl_wh_data
                                        INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
                                        INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
                                        WHERE
                                            tbl_wh_data.RptDate = '$reportingDate'
                                        AND tbl_wh_data.wh_id = $distWH
                                        AND tbl_warehouse.stkid = $stakeholder
                                        GROUP BY
                                            tbl_wh_data.item_id
                                    ) A
                            RIGHT JOIN (
                                    SELECT
                                        itminfo_tab.itm_id,
                                        itminfo_tab.itm_name,
                                        itminfo_tab.method_type,
                                        itminfo_tab.method_rank,
                                        itminfo_tab.frmindex,
                                        itminfo_tab.generic_name,
                                        itminfo_tab.itm_category,
                                        summary_district.district_id,
                                        SUM(summary_district.soh_district_store) AS CBDistStore,
                                        SUM(summary_district.soh_district_lvl) - SUM(summary_district.soh_district_store) AS CBFldStore,
                                        SUM(summary_district.soh_district_lvl) AS CBTotal
                                    FROM
                                        summary_district
                                    INNER JOIN itminfo_tab ON summary_district.item_id = itminfo_tab.itmrec_id
                                    WHERE
                                        summary_district.district_id = $districtId
                                    AND summary_district.reporting_date = '$reportingDate'
                                    AND summary_district.stakeholder_id = $stakeholder
                                    GROUP BY
                                        itminfo_tab.itm_id
                            ) B ON A.itm_id = B.itm_id
                            AND A.dist_id = B.district_id
                            ORDER BY
                                B.method_rank ASC,
                                B.frmindex ASC";
                    $qryRes = mysql_query($qry);
					
                    // Query for Part II
                    $qry1 = "SELECT
                                tbl_hf_type.pk_id AS hf_type_id,
                                tbl_hf_type.hf_type,
                                tbl_hf_data.item_id,
                                itminfo_tab.itm_id,
                                itminfo_tab.itm_category,
                                SUM(tbl_hf_data.opening_balance) AS opening_balance,
                                SUM(tbl_hf_data.received_balance) AS received_balance,
                                SUM(IF(itminfo_tab.itm_category = 2, (tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp), tbl_hf_data.issue_balance)) AS issue_balance,
                                SUM(tbl_hf_data.closing_balance) AS closing_balance
                            FROM
                                tbl_warehouse
                            INNER JOIN tbl_hf_type ON tbl_hf_type.pk_id = tbl_warehouse.hf_type_id
							INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id
                            INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
                            INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
							LEFT JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
                            WHERE
                                tbl_warehouse.dist_id = $districtId
                            AND tbl_warehouse.stkid = $stakeholder
                            AND tbl_hf_data.reporting_date = '$reportingDate'
							AND tbl_hf_type_rank.province_id = $selProv
							AND tbl_hf_type_rank.stakeholder_id = $stakeholder
                            GROUP BY
                                tbl_hf_type.pk_id,
                                itminfo_tab.itm_id
                            ORDER BY
                                tbl_hf_type_rank.hf_type_rank ASC,
                                itminfo_tab.frmindex ASC";
                    $qryRes1 = mysql_query($qry1);
					
                    if (mysql_num_rows(mysql_query($qry)) > 0 && mysql_num_rows(mysql_query($qry1)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center bold">
                                                District Contraceptive Stock Report <br>
                                                For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                            </h4>
                                        </td>
                                        <td>
                                            <h4 class="right">CLR-15</h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            while ($row = mysql_fetch_array($qryRes)) {
                                                $itemIds[] = $row['itm_id'];
                                                $product[$row['method_type']][] = $row['itm_name'];
												
                                                if ($row['itm_category'] == 1)
                                                {
                                                    $ob[$row['itm_id']] = $row['OB'];
                                                    $rcv[$row['itm_id']] = $row['Rcv'];
                                                    $issue[$row['itm_id']] = $row['Issue'];
                                                    $CBDistStore[$row['itm_id']] = $row['CBDistStore'];
                                                    $CBFldStore[$row['itm_id']] = $row['CBFldStore'];
                                                    $CBTotal[$row['itm_id']] = $row['CBTotal'];
                                                }
                                                else
                                                {
                                                    $ob[$row['itm_id']] = '';
                                                    $rcv[$row['itm_id']] = '';
                                                    $issue[$row['itm_id']] = '';
                                                    $CBDistStore[$row['itm_id']] = '';
                                                    $CBTotal[$row['itm_id']] = '';
                                                }
												
                                                if (strtoupper($row['method_type']) == strtoupper($row['generic_name'])) {
                                                    $methodType[$row['method_type']]['rowspan'] = 2;
                                                } else {
                                                    $genericName[$row['generic_name']][] = $row['itm_name'];
                                                }
                                            }
                                            ?>
                                            <h5 class="center bold">Part-I</h5>
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="3" width="20%">District Store</th>
                                                        <?php
                                                        foreach ($product as $proType => $proNames) {
                                                            echo "<th colspan=" . sizeof($proNames) . " rowspan=" . $methodType[$proType]['rowspan'] . ">$proType</th>";
                                                        }
                                                        ?>
                                                        <th rowspan="3" width="10%">Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $col = '';
                                                        foreach ($genericName as $name => $proNames) {
                                                            echo "<th colspan=" . sizeof($proNames) . ">$name</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $col = '';
                                                        foreach ($product as $proType => $proNames) {
                                                            foreach ($proNames as $name) {
                                                                echo "<th width='" . (70 / count($itemIds)) . "%'>$name</th>";
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align:center">1</td>
                                                        <?php
                                                        $counter = 2;
                                                        foreach ($itemIds as $id) {
                                                            echo "<td style=\"text-align:center !important;\">" . $counter++ . "</td>";
                                                        }
                                                        ?>
                                                        <td class="center"><?php echo $counter; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Opening Balance</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($ob[$id]). "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Received from Central Warehouse</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($rcv[$id]) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Issued to field</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($issue[$id]) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="<?php echo sizeof($itemIds) + 2; ?>">Closing Balance</td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;&nbsp;(i) District Store</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($CBDistStore[$id]) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;&nbsp;(ii) Field Store</td>
                                                        <?php
                                                        // Get surgery Cases
                                                        $csQry = "SELECT
                                                                        tbl_hf_data.item_id,
                                                                        SUM(tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) AS CS_Done
                                                                    FROM
                                                                        tbl_hf_data
                                                                    INNER JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
                                                                    INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
                                                                    WHERE
                                                                        tbl_warehouse.dist_id = $districtId
                                                                    AND tbl_hf_data.reporting_date = '$reportingDate'
                                                                    GROUP BY
                                                                        tbl_hf_data.item_id
                                                                    ORDER BY
                                                                        tbl_hf_data.item_id ASC";
                                                        $csRows = mysql_query($csQry);
                                                        while ( $row = mysql_fetch_array($csRows) )
                                                        {
                                                                $CBFldStore[$row['item_id']] = $row['CS_Done'];
                                                        }
														
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($CBFldStore[$id]) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . number_format($CBTotal[$id]) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <?php
                                            // Unset variables
                                            unset($ob, $rcv, $issue, $CBDistStore, $CBFldStore, $CBTotal);
                                            ?>                   

                                            <h5 style="margin-top:20px; text-align:center;" class="center bold">Part-II</h5>

                                            <?php
                                            $hfTypeId = '';
                                            while ($row = mysql_fetch_array($qryRes1)) {
                                                if ( $row['itm_category'] == 1 )
                                                    {
                                                        $data[$row['hf_type_id']]['ob'][$row['itm_id']] = $row['opening_balance'];
                                                        $data[$row['hf_type_id']]['rcv'][$row['itm_id']] = $row['received_balance'];
                                                        $data[$row['hf_type_id']]['issue'][$row['itm_id']] = $row['issue_balance'];
                                                        $data[$row['hf_type_id']]['cb'][$row['itm_id']] = $row['closing_balance'];
                                                        $ob[$row['itm_id']][] = $row['opening_balance'];
                                                        $rcv[$row['itm_id']][] = $row['received_balance'];
                                                        $issue[$row['itm_id']][] = $row['issue_balance'];
                                                        $cb[$row['itm_id']][] = $row['closing_balance'];
                                                    }
                                                    else
                                                    {
                                                        $data[$row['hf_type_id']]['ob'][$row['itm_id']] = '';
                                                        $data[$row['hf_type_id']]['rcv'][$row['itm_id']] = '';
                                                        $data[$row['hf_type_id']]['cb'][$row['itm_id']] = '';
                                                        if ( $row['hf_type_id'] == 4 || $row['hf_type_id'] == 5 )
                                                        {
                                                            $data[$row['hf_type_id']]['issue'][$row['itm_id']] = $row['issue_balance'];
                                                            $issue[$row['itm_id']][] = $row['issue_balance'];
                                                        }
                                                        else
                                                        {
                                                            $data[$row['hf_type_id']]['issue'][$row['itm_id']] = '';
                                                        }
                                                    }
                                                    if ($hfTypeId != $row['hf_type_id']) {
                                                    $hfTypes[$row['hf_type_id']] = $row['hf_type'];
                                                    $hfTypeId = $row['hf_type_id'];
                                                }
                                            }
                                            ?>
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="3" width="20%">Field</th>
                                                        <?php
                                                        foreach ($product as $proType => $proNames) {
                                                            echo "<th colspan=" . sizeof($proNames) . " rowspan=" . $methodType[$proType]['rowspan'] . ">$proType</th>";
                                                        }
                                                        ?>
                                                        <th rowspan="3" width="10%">Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $col = '';
                                                        foreach ($genericName as $name => $proNames) {
                                                            echo "<th colspan=" . sizeof($proNames) . ">$name</th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                    <tr>
                                                        <?php
                                                        $col = '';
                                                        foreach ($product as $proType => $proNames) {
                                                            foreach ($proNames as $name) {
                                                                echo "<th width='" . (70 / count($itemIds)) . "%'>$name</th>";
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align:center">1</td>
                                                        <?php
                                                        $counter = 2;
                                                        foreach ($itemIds as $id) {
                                                            echo "<td style=\"text-align:center !important;\">" . $counter++ . "</td>";
                                                        }
                                                        ?>
                                                        <td class="center"><?php echo $counter; ?></td>
                                                    </tr>
                                                    <?php
                                                    foreach ($hfTypes as $hfTypeid => $hfType) {
                                                        ?>
                                                        <tr>
                                                            <td colspan="<?php echo sizeof($itemIds) + 2; ?>"><b><?php echo $hfType; ?></b></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Opening Balance</td>
                                                            <?php
                                                            foreach ($itemIds as $id) {
                                                                echo "<td class=\"right\">" . number_format($data[$hfTypeid]['ob'][$id]) . "</td>";
                                                            }
                                                            ?>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Received from district warehouse</td>
                                                            <?php
                                                            foreach ($itemIds as $id) {
                                                                echo "<td class=\"right\">" . number_format($data[$hfTypeid]['rcv'][$id]) . "</td>";
                                                            }
                                                            ?>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sold/Issued</td>
                                                            <?php
                                                            foreach ($itemIds as $id) {
                                                                echo "<td class=\"right\">" . number_format($data[$hfTypeid]['issue'][$id]) . "</td>";
                                                            }
                                                            ?>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Closing Balance</td>
                                                            <?php
                                                            foreach ($itemIds as $id) {
                                                                echo "<td class=\"right\">" . number_format($data[$hfTypeid]['cb'][$id]) . "</td>";
                                                            }
                                                            ?>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    ?>
                                                    <tr>
                                                        <td colspan="<?php echo sizeof($itemIds) + 2; ?>"><b>Field Total</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Opening Balance</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . ((($id == '31' || $id == '32') && array_sum($ob[$id]) == 0) ? '' : number_format(array_sum($ob[$id]))) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Received from district warehouse</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . ((($id == '31' || $id == '32') && array_sum($rcv[$id]) == 0) ? '' : number_format(array_sum($rcv[$id]))) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sold/Issued</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . ((($id == '31' || $id == '32') && array_sum($issue[$id]) == 0) ? '0' : number_format(array_sum($issue[$id]))) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Closing Balance</td>
                                                        <?php
                                                        foreach ($itemIds as $id) {
                                                            echo "<td class=\"right\">" . ((($id == '31' || $id == '32') && array_sum($cb[$id]) == 0) ? '' : number_format(array_sum($cb[$id]))) . "</td>";
                                                        }
                                                        ?>
                                                        <td>&nbsp;</td>
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

				// Unset variables
                unset($ob, $cb, $rcv, $issue, $data, $hfTypes, $itemIds, $product);
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
    <?php include ('combos.php'); ?>
    
</body>
</html>