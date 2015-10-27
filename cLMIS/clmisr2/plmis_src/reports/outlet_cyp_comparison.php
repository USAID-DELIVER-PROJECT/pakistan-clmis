<?php
include("../../html/adminhtml.inc.php");
Login();

$rptId = 'outletCYP';

$districtId = '';
if (isset($_POST['submit'])) {
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    $stakeholder = 1;

    $endDate = $selYear . '-' . $selMonth;
    $startDate = date('Y-m', strtotime("-1 month", strtotime($endDate)));

    // Get Province name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $selProv";
    $row = mysql_fetch_array(mysql_query($qry));
    $provinceName = $row['LocName'];

    $fileName = 'outlet_CYP_comparison_' . $provinceName . '_of_' . date('M-Y', strtotime($startDate)) . '_and_' . date('M-Y', strtotime($endDate));
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
                        <h3 class="page-title row-br-b-wp">Outlet Wise Comparison of Performance (In Terms of CYP)</h3>
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
								A.pk_id,
								A.hf_type,
								A.Month1,
								A.Month2,
								(A.Month2 - A.Month1) AS diff,
								(((A.Month2 - A.Month1) / A.Month1) * 100) AS diffPer
							FROM
								(
									SELECT
										A.pk_id,
										A.hf_type,
										(IFNULL(A.Month1, 0) + IFNULL(B.Month1, 0)) AS Month1,
										(IFNULL(A.Month2, 0) + IFNULL(B.Month2, 0)) AS Month2
									FROM
										(
											SELECT
												tbl_hf_type.pk_id,
												tbl_hf_type.hf_type,
												tbl_hf_type_rank.hf_type_rank,
												SUM(IF (DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') = '$startDate', (tbl_hf_data.issue_balance * itminfo_tab.extra), 0)) AS Month1,
												SUM(IF (DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') = '$endDate', (tbl_hf_data.issue_balance * itminfo_tab.extra), 0)) AS Month2
											FROM
												tbl_hf_data
											INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
											INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
											INNER JOIN tbl_hf_type ON tbl_warehouse.hf_type_id = tbl_hf_type.pk_id
											INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id
											WHERE
												tbl_hf_type_rank.stakeholder_id = $stakeholder
											AND tbl_hf_type_rank.province_id = $selProv
											AND tbl_warehouse.prov_id = $selProv
											AND tbl_warehouse.stkid = $stakeholder
											AND itminfo_tab.itm_category = 1
											AND DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') BETWEEN '$startDate' AND '$endDate'
											GROUP BY
												tbl_hf_type.pk_id
										) A
									LEFT JOIN (
										SELECT
											tbl_warehouse.hf_type_id,
											SUM(IF (DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') = '$startDate', (tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) * itminfo_tab.extra, 0)) AS Month1,
											SUM(IF (DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') = '$endDate', (tbl_hf_data_reffered_by.static + tbl_hf_data_reffered_by.camp) * itminfo_tab.extra, 0)) AS Month2
										FROM
											tbl_hf_data
										INNER JOIN tbl_hf_data_reffered_by ON tbl_hf_data.pk_id = tbl_hf_data_reffered_by.hf_data_id
										INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
										INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
										WHERE
											DATE_FORMAT(tbl_hf_data.reporting_date, '%Y-%m') BETWEEN '$startDate' AND '$endDate'
										AND tbl_warehouse.stkid = $stakeholder
										AND tbl_warehouse.prov_id = $selProv
										AND itminfo_tab.itm_category = 2
										GROUP BY
											tbl_warehouse.hf_type_id
									) B ON A.pk_id = B.hf_type_id
									ORDER BY
										A.hf_type_rank
								) A";
                    $qryRes = mysql_query($qry);
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                        <div class="row">
                            <div class="col-md-12 right">
                                <img src="<?php echo SITE_URL; ?>images/print-16.png" onClick="printContents()" alt="Print" style="cursor:pointer;" />
                                <img src="<?php echo SITE_URL; ?>images/excel-16.png" onClick="tableToExcel('export', 'sheet 1', '<?php echo $fileName; ?>')" alt="Excel" style="cursor:pointer;" />
                            </div>
                        </div>
                        <div class="row" id="export">
                            <?php include('sub_dist_reports.php'); ?>
                            <div class="col-md-12" style="overflow:auto;">
                                <table width="100%">
                                    <tr>
                                        <td align="center">
                                            <h4 class="center">
                                                Outlet Wise Comparison of Performance (In Terms of CYP) <br>
                                                For the month of  <?php echo date('M-Y', strtotime($startDate)) . ' and ' . date('M-Y', strtotime($endDate)) . ', Province ' . $provinceName; ?>
                                            </h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">
                                            <table width="100%" id="myTable" cellspacing="0" align="center">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" width="5%">S.No</th>
                                                        <th rowspan="2">Outlet</th>
                                                        <th colspan="2">CYP</th>
                                                        <th rowspan="2" width="20%">Increase / Decrease</th>
                                                        <th rowspan="2" width="20%">Percentage Increase / Decrease</th>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo date('M-Y', strtotime($startDate)); ?></th>
                                                        <th><?php echo date('M-Y', strtotime($endDate)); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $counter = 1;
                                                    $m1Total = 0;
                                                    $m2Total = 0;
													$color = '';
                                                    while ($row = mysql_fetch_array($qryRes)) {
                                                        $m1Total += $row['Month1'];
                                                        $m2Total += $row['Month2'];
														if ($row['Month2'] < $row['Month1']){
															$color = '#ED4E66';
														}else{
															$color = '#FFF';
														}
                                                        ?>
                                                        <tr bgcolor="<?php echo $color;?>">
                                                            <td class="center"><?php echo $counter++; ?></td>
                                                            <td><?php echo $row['hf_type']; ?></td>
                                                            <td class="right"><?php echo ($row['Month1'] != 0) ? number_format($row['Month1']) : 0; ?></td>
                                                            <td class="right"><?php echo ($row['Month2'] != 0) ? number_format($row['Month2']) : 0; ?></td>
                                                            <td class="right"><?php echo ($row['diff'] != 0) ? number_format($row['diff']) : 0; ?></td>
                                                            <td class="right"><?php echo ($row['Month1'] != 0 && $row['Month2']) ? number_format($row['diffPer']) : ''; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
													$diff = ($m2Total - $m1Total);
													$diffPer = (($m2Total - $m1Total) / $m1Total) * 100;
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                <?php $color = ($diff > 0) ? '#FFF' : '#ED4E66';?>
                                                    <tr bgcolor="<?php echo $color;?>">
                                                        <th colspan="2" class="center">Total</th>
                                                        <th class="right"><?php echo ($m1Total != 0) ? number_format($m1Total) : 0; ?></th>
                                                        <th class="right"><?php echo ($m2Total != 0) ? number_format($m2Total) : 0; ?></th>
                                                        <th class="right"><?php echo ($diff != 0) ? number_format($diff) : 0; ?></th>
                                                        <th class="right"><?php echo ($m1Total != 0 && $m2Total != 0) ? number_format($diffPer) : ''; ?></th>
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
                ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/footer.php"; ?>
</body>
</html>