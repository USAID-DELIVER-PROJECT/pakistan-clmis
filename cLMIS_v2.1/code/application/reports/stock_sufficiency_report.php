<?php
/**
 * stock_sufficiency_report
 * @package reports
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Including AllClasses
include("../includes/classes/AllClasses.php");
//Including FunctionLib
include(APP_PATH . "includes/report/FunctionLib.php");
//Including header
include(PUBLIC_PATH . "html/header.php");
//report id
$report_id = "STOCKSUFFICIENCY";

$districtId = '';
//If for submitted
if (isset($_POST['submit'])) {
    //Getting month_sel
    $selMonth = mysql_real_escape_string($_POST['month_sel']);
    //Getting year_sel
    $selYear = mysql_real_escape_string($_POST['year_sel']);
    //Getting prov_sel
    $selProv = mysql_real_escape_string($_POST['prov_sel']);
    //Getting year_sel
    $reportingDate = mysql_real_escape_string($_POST['year_sel']) . '-' . $selMonth . '-01';
    $stakeholder = 1;

    // select query
    // Get Province name
    $qry = "SELECT
                tbl_locations.LocName
            FROM
                tbl_locations
            WHERE
                tbl_locations.PkLocID = $selProv";
    //Query result
    $row = mysql_fetch_array(mysql_query($qry));
    //province name
    $provinceName = $row['LocName'];

    $fileName = 'stock_sufficiency_report_' . $provinceName . '_for_' . date('M-Y', strtotime($reportingDate));
}
?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="page-container">
        <?php
        //Including top
        include PUBLIC_PATH . "html/top.php";
        //Including top_im
        include PUBLIC_PATH . "html/top_im.php";
        ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">Provincial Monthly Stock Sufficiency Report</h3>
                        <div style="display: block;" id="alert-message" class="alert alert-info text-message"><?php echo stripslashes(getReportDescription($report_id)); ?></div>
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Filter by</h3>
                            </div>
                            <div class="widget-body">
                                <form name="frm" id="frm" action="" method="post" role="form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="control-label">Month</label>
                                                    <div class="form-group">
                                                        <select name="month_sel" id="month_sel" class="form-control input-sm" required>
                                                            <?php
                                                            for ($i = 1; $i <= 12; $i++) {
                                                                //check selMonth
                                                                if ($selMonth == $i) {
                                                                    $sel = "selected='selected'";
                                                                } else {
                                                                    $sel = "";
                                                                }
                                                                ?>
                                                                <?php // Populate month_sel combo?>
                                                                <option value="<?php echo date('m', mktime(0, 0, 0, $i, 1)); ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
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
                                                                //check selYear
                                                                if ($selYear == $j) {
                                                                    $sel = "selected='selected'";
                                                                } else {
                                                                    $sel = "";
                                                                }
                                                                ?>
                                                                <?php // Populate year_sel combo?>
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
                                                    <label class="control-label">Province</label>
                                                    <div class="form-group">
                                                        <select name="prov_sel" id="prov_sel" required class="form-control input-sm">
                                                            <option value="">Select</option>
                                                            <?php
                                                            //Province query
                                                            //gets
                                                            //prov_id
                                                            //prov_title
                                                            $queryprov = "SELECT
                                                                        tbl_locations.PkLocID AS prov_id,
                                                                        tbl_locations.LocName AS prov_title
                                                                    FROM
                                                                        tbl_locations
                                                                    WHERE
                                                                        LocLvl = 2
                                                                    AND parentid IS NOT NULL";
                                                            //Query result
                                                            $rsprov = mysql_query($queryprov) or die();
                                                            //fetch data from rsprov
                                                            while ($rowprov = mysql_fetch_array($rsprov)) {
                                                                //check selProv
                                                                if ($selProv == $rowprov['prov_id']) {
                                                                    $sel = "selected='selected'";
                                                                } else {
                                                                    $sel = "";
                                                                }
                                                                
                                                                ?>
                                                                <?php //Populate prov_sel combo?>
                                                                <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
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
                //Checking submit
                if (isset($_POST['submit'])) {
                    //This query gets
                    //B.PkLocID,
                    //LocName,
                    //itm_name,
                    //distMOS,
                    //fieldMOS,
                    $qry = "SELECT
								B.PkLocID,
								B.LocName,
								B.itm_name,
								A.distMOS,
								A.fieldMOS,
								(A.distMOS + A.fieldMOS) AS totalMOS
							FROM
								(
									SELECT
										tbl_locations.PkLocID,
										tbl_locations.LocName,
								  		itminfo_tab.itmrec_id,
										ROUND(
											(
												summary_district.soh_district_store / summary_district.avg_consumption
											),
											2
										) AS distMOS,
										ROUND(
											(
												(
													summary_district.soh_district_lvl - summary_district.soh_district_store
												) / summary_district.avg_consumption
											),
											2
										) AS fieldMOS,
										itminfo_tab.itm_name
									FROM
										summary_district
									INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
									INNER JOIN itminfo_tab ON summary_district.item_id = itminfo_tab.itmrec_id
									WHERE
										summary_district.stakeholder_id = $stakeholder
									AND summary_district.province_id = $selProv
									AND summary_district.reporting_date = '$reportingDate'
									GROUP BY
										summary_district.district_id,
										itminfo_tab.itmrec_id
								) A
							  RIGHT JOIN (
								SELECT DISTINCT
									itminfo_tab.itmrec_id,
									itminfo_tab.frmindex,
									itminfo_tab.method_rank,
									itminfo_tab.itm_name,
									tbl_locations.PkLocID,
									tbl_locations.LocName
								FROM
									itminfo_tab
								INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item,
								tbl_warehouse
								INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
								INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
								WHERE
									tbl_warehouse.stkid = $stakeholder
								AND stakeholder_item.stkid = $stakeholder
								AND itminfo_tab.itm_category = 1
								AND tbl_warehouse.prov_id = $selProv
							) B ON A.PkLocID = B.PkLocID
							AND A.itmrec_id = B.itmrec_id
							ORDER BY
								B.LocName ASC,
								B.method_rank,
								B.frmindex";
                    //Query result
                    $qryRes = mysql_query($qry);
                    //fetch data from qry
                    if (mysql_num_rows(mysql_query($qry)) > 0) {
                        ?>
                            <?php include('sub_dist_reports.php'); ?>
                        <div class="col-md-12" style="overflow:auto;">
                            <?php
                            //whid
                            $whId = '';
                            //itemid
                            $itemId = '';
                            //itemtype
                            $itemType = array();
                            //fetch data from qryRes
                            while ($row = mysql_fetch_array($qryRes)) {
                                if (!in_array($row['itm_name'], $itemType)) {
                                    $itemType[] = $row['itm_name'];
                                }
                                //Checking whid
                                if ($whId != $row['PkLocID']) {
                                    $whName[$row['PkLocID']] = $row['LocName'];
                                    $whId = $row['PkLocID'];
                                }
                                //new
                                $data[$row['PkLocID']]['new'][] = $row['distMOS'];
                                //old
                                $data[$row['PkLocID']]['old'][] = $row['fieldMOS'];
                                //total
                                $data[$row['PkLocID']]['total'][] = $row['distMOS'] + $row['fieldMOS'];
                            }
                            ?>
                            <table width="100%">
                                <tr>
                                    <td align="center"><h4 class="center"> Provincial Stock Sufficiency Report <br>
                                            For the month of <?php echo date('M', mktime(0, 0, 0, $selMonth, 1)) . '-' . $selYear . ', Province ' . $provinceName; ?> </h4></td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 10px;"><table id="myTable" cellspacing="0" align="center">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">S.No</th>
                                                    <th rowspan="2" width="100">District</th>
                                                    <?php
                                                    //get data from itemType
                                                    foreach ($itemType as $name) {

                                                        echo "<th colspan=\"4\">$name</th>";
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    foreach ($itemType as $name) {
                                                        echo "<th width=\"50\">Dist. Store MOS</th>";
                                                        echo "<th width=\"50\">Field MOS</th>";
                                                        echo "<th width=\"50\">Total MOS</th>";
                                                        echo "<th width=\"60\">Remarks</th>";
                                                    }
                                                    ?>
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
                                                        //count
                                                        $count = 0;
                                                        //all New
                                                        $allNew = 0;
                                                        //all Old
                                                        $allOld = 0;
                                                        //all total
                                                        $allTotal = 0;
                                                        foreach ($data[$id]['new'] as $val) {
                                                            echo "<td class=\"right\">" . ( (!empty($val) && $val != 0) ? number_format($val, 2) : number_format($val) ) . "&nbsp</td>";
                                                            echo "<td class=\"right\">" . ( (!empty($data[$id]['old'][$count]) && $data[$id]['old'][$count] != 0) ? number_format($data[$id]['old'][$count], 2) : number_format($data[$id]['old'][$count]) ) . "&nbsp</td>";
                                                            echo "<td class=\"right\">" . ( (!empty($data[$id]['total'][$count]) && $data[$id]['total'][$count] != 0) ? number_format($data[$id]['total'][$count], 2) : number_format($data[$id]['total'][$count]) ) . "&nbsp</td>";
                                                            echo "<td class=\"right\">&nbsp;</td>";
                                                            //all new
                                                            $allNew += $val;
                                                            //all old
                                                            $allOld += $data[$id]['old'][$count];
                                                            //all total
                                                            $allTotal += $data[$id]['total'][$count];
                                                            $count++;
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 10px;" colspan="15"><table cellspacing="0" align="left" id="myTable">
                                            <tbody>
                                                <tr>
                                                    <td> Note: The stock outs measured on the basis of less than 2 months stock. </td>
                                                </tr>
                                            </tbody>
                                        </table></td>
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
            unset($data, $issue, $itemType, $whName);
            ?>
        </div>
    </div>
</div>
<?php
//Including footer
include PUBLIC_PATH . "/html/footer.php";
?>
<script>
    function printContents() {
        var w = 900;
        var h = screen.height;
        var left = Number((screen.width / 2) - (w / 2));
        var top = Number((screen.height / 2) - (h / 2));
        var dispSetting = "toolbar=yes,location=no,directories=yes,menubar=yes,scrollbars=yes,left=" + left + ",top=" + top + ",width=" + w + ",height=" + h;
        var printingContents = document.getElementById("export").innerHTML;
        var docprint = window.open("", "", dispSetting);
        docprint.document.open();
        docprint.document.write('<html><head><title>SPR-2</title>');
        docprint.document.write('</head><body onLoad="self.print();"><center>');
        docprint.document.write(printingContents);
        docprint.document.write('</center></body></html>');
        docprint.document.close();
        docprint.focus();
    }
</script>
</body>
</html>