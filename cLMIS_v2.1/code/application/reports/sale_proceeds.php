<?php
/**
 * sale+proceeds
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
//flag is_provincial_user 
$is_provincial_user = false;
// If Provincial User
if ($_SESSION['user_level'] == 2) {
//set flag is_provincial_user true	
    $is_provincial_user = true;
    //set province id
    $prov_id = $_SESSION['user_province1'];
}
//if submitted
if (isset($_POST['submit'])) {
    //get selected month
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    //get selected year
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    //get reporting date 
    $reportingDate = mysql_real_escape_string($_POST['year_sel']) . '-' . $selMonth . '-01';
    //get prev month 
    $prevMonth = date('Y-m-d', strtotime('-1 Month', strtotime($reportingDate)));
    //get corr month 
    $corrMonth = date('Y-m-d', strtotime('-12 Month', strtotime($reportingDate)));
    //get stakeholder 
    $stakeholder = 1;
    //check district
    if (isset($_POST['district'])) {
        //get district Id 
        $districtId = mysql_real_escape_string($_POST['district']);
    } else {
        //get district Id 
        $districtId = $_SESSION['user_district'];
    }
    //select query
    // Get 
    // District 
    // and Province name
    $qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName,
				tbl_locations.ParentID AS prov_id
			FROM
				tbl_locations
			WHERE
				tbl_locations.PkLocID = $districtId";
    //query result
    $row = mysql_fetch_array(mysql_query($qry));
    //province id
    $provId = $row['prov_id'];
    //distrct name 
    $distrctName = $row['LocName'];
    //file name 
    $fileName = 'Sale-Proceeds_' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
} else {
    if (date('d') > 10) {
        //set selected month
        $selMonth = date('m', strtotime("-1 month", strtotime(date('Y-m'))));
        //set selected year
        $selYear = date('Y', strtotime("-1 month", strtotime(date('Y-m'))));
    } else {
        //set selected month
        $selMonth = date('m', strtotime("-2 month", strtotime(date('Y-m'))));
        //set selected year
        $selYear = date('Y', strtotime("-2 month", strtotime(date('Y-m'))));
    }
}
?>
</head>
<!-- END HEAD -->
<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
        <?php
//include top
        include PUBLIC_PATH . "html/top.php";
////include top_im
        include PUBLIC_PATH . "html/top_im.php";
        ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">Sale Proceeds of Contraceptives</h3>
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Filter by</h3>
                            </div>
                            <div class="widget-body">
                                <form name="frm" id="frm" action="" method="post" role="form">
                                    <div class="row">               
                                        <div class="col-md-12">
                                            <?php if ($is_provincial_user) { ?>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">District</label>
                                                        <div class="form-group">
                                                            <select name="district" id="district" class="form-control input-sm" required>
                                                                <option value="">Select</option>
                                                                <?php
                                                                $and = '';
                                                                if ($_SESSION['userdata'][6] != 'SG-020') {
                                                                    $and = " AND tbl_locations.ParentID = $prov_id";
                                                                }
                                                                //select query
                                                                //gets
                                                                //pk location id
                                                                //location name
                                                                $qry = "SELECT
																		tbl_locations.PkLocID,
																		tbl_locations.LocName
																	FROM
																		tbl_locations
																	WHERE
																		tbl_locations.LocLvl = 3
																		$and
																	ORDER BY
																		tbl_locations.LocName ASC";
                                                                //query result
                                                                $qryRes = mysql_query($qry);
                                                                //fetch result
                                                                while ($row = mysql_fetch_array($qryRes)) {
                                                                    $sel = ($districtId == $row['PkLocID']) ? 'selected="selected"' : '';
                                                                    echo "<option value=\"$row[PkLocID]\" $sel>$row[LocName]</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Month</label>
                                                    <div class="form-group">
                                                        <select name="month_sel" id="month_sel" class="form-control input-sm" required>
                                                            <?php
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                if ($selMonth == $i) {
                                                                    $sel = "selected='selected'";
                                                                } else {
                                                                    $sel = "";
                                                                }
                                                                ?>
                                                                <option value="<?php echo date('m', mktime(0, 0, 0, $i, 1)); ?>"<?php echo $sel; ?> ><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Year</label>
                                                    <div class="form-group">
                                                        <select name="year_sel" id="year_sel" class="form-control input-sm" required>
                                                            <?php
                                                            for ($j = date('Y'); $j >= 2010; $j--) {
                                                                if ($selYear == $j) {
                                                                    $sel = "selected='selected'";
                                                                } else {
                                                                    $sel = "";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">&nbsp;</label>
                                                    <div class="form-group">
                                                        <button type="submit" name="submit" class="btn btn-primary input-sm">Go</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                if (isset($_POST['submit'])) {
                    //select query
                    //gets
                    //curr month
                    //prev month
                    //corr month
                    $qry = "SELECT
								SUM(IF (tbl_hf_data.reporting_date = '$reportingDate', (REPgetItemPrice('$reportingDate', 1, $provId, itminfo_tab.itm_id) * tbl_hf_data.issue_balance), 0)) AS currMonth,
								SUM(IF (tbl_hf_data.reporting_date = '$prevMonth', (REPgetItemPrice('$prevMonth', 1, $provId, itminfo_tab.itm_id) * tbl_hf_data.issue_balance), 0)) AS prevMonth,
								SUM(IF (tbl_hf_data.reporting_date = '$corrMonth', (REPgetItemPrice('$corrMonth', 1, $provId, itminfo_tab.itm_id) * tbl_hf_data.issue_balance), 0)) AS corrMonth
							FROM
								tbl_warehouse
							INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
							INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
							INNER JOIN item_price ON itminfo_tab.itm_id = item_price.item_id
							WHERE
								tbl_warehouse.dist_id = $districtId
							AND item_price.stakeholder_id = $stakeholder
							AND tbl_warehouse.stkid = $stakeholder
							AND item_price.province_id = $provId
							AND (
								tbl_hf_data.reporting_date BETWEEN '$prevMonth'
								AND '$reportingDate'
								OR tbl_hf_data.reporting_date = '$corrMonth'
							)";
                    //query result
                    $qryRes = mysql_query($qry);
                    //if result exists
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                        <?php
                        //include sub_dist_reports
                        include('sub_dist_reports.php');
                        ?>
                        <div class="col-md-12" style="overflow:auto;">
                            <?php
                            //fetch result
                            $row = mysql_fetch_array($qryRes);

                            $currMonthSales = $row['currMonth'];
                            $prevMonthSales = $row['prevMonth'];
                            $corrMonthSales = $row['corrMonth'];
                            $currCorrPer = $currPrevPer = '';

                            $currPrevPer = ($prevMonthSales > 0) ? round((($currMonthSales / $prevMonthSales) - 1) * 100, 2) : '';
                            $currCorrPer = ($corrMonthSales > 0) ? round((($currMonthSales / $corrMonthSales) - 1) * 100, 2) : '';
                            ?>
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <h4 class="center">
                                            Sale Proceeds of Contraceptives	<br>
                                            For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', District ' . $distrctName; ?>
                                        </h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table id="myTable" cellspacing="0" align="center" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Current Month(Rs)</th>
                                                    <th width="15%">Previous Month(Rs)</th>
                                                    <th width="15%">Corr. Month of Last Year(Rs)</th>
                                                    <th width="15%">Current Month Over Previous Month(%)</th>
                                                    <th width="15%">Current Month Over Corr. Month of Last Year(%)</th>
                                                    <th width="25%">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="right"><?php echo number_format($currMonthSales, 2); ?></td>
                                                    <td class="right"><?php echo number_format($prevMonthSales, 2); ?></td>
                                                    <td class="right"><?php echo number_format($corrMonthSales, 2); ?></td>
                                                    <td class="right"><?php echo ($prevMonthSales > 0 && $currMonthSales) ? $currPrevPer . '%' : ''; ?></td>
                                                    <td class="right"><?php echo ($corrMonthSales > 0) ? $currCorrPer . '%' : ''; ?></td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width:100%; padding:18px;">
                            <div style="float:left; margin-top:50px;">
                                <b>District Population Welfare Officer<br><?php echo $distrctName; ?></b>
                            </div>
                            <div style="float:right; margin-top:50px; padding-right:10px;">
                                <b>District Demographer<br><?php echo $distrctName; ?></b>
                            </div>
                        </div>
                        <!--<div style="clear:both; float:right; margin-top:20px;" id="printButt">
                            <input type="button" name="print" value="Print" class="btn btn-warning" onclick="javascript:printContents();" />
                        </div>-->
                    </div>
                    <?php
                } else {
                    echo "No record found";
                }
            }
// Unset varibles
            unset($data, $total, $whName, $method);
            ?>
        </div>
    </div>
</div>
<?php
//include footer
include PUBLIC_PATH . "/html/footer.php";
?>
</body>
</html>