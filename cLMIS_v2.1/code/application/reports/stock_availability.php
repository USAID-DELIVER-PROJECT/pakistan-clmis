<?php
/**
 * stock_availability
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
//include FunctionLib
include(APP_PATH . "includes/report/FunctionLib.php");
//include header
include(PUBLIC_PATH . "html/header.php");
//report id 
$report_id = "SNASUMSTOCKLOC";
//report title
$report_title = "Stock Availability Report for ";
//action page
$actionpage = "stock_availability.php";
//parameters 
$parameters = "TS01P01I";
//selected product
$sel_prod = $sel_stk = $stk_id = $prov_id = $stkFilter = $provFilter = $prov_id = '';

if (isset($_GET['tp']) && !isset($_POST['go'])) {
    //check report_month
    if (isset($_GET['report_month']) && !empty($_GET['report_month'])) {
        //get report_month
        $sel_month = $_GET['report_month'];
    }
    //check report_year
    if (isset($_GET['report_year']) && !empty($_GET['report_year'])) {
        //get report_year
        $sel_year = $_GET['report_year'];
    }
//check item_id
    if (isset($_GET['item_id']) && !empty($_GET['item_id'])) {
        //get item_id
        $sel_item = $_GET['item_id'];
        //proFilter
        $proFilter = " AND summary_district.item_id = '" . $sel_item . "'";
    }
    //check stk id
    if (isset($_GET['stk_id']) && !empty($_GET['stk_id'])) {
        if ($_GET['stk_id'] != 'all') {
            //get stk id
            $sel_stk = $_GET['stk_id'];
            $stkFilter = " AND stakeholder.stkid = " . $sel_stk;
        } else {
            $qStrStk = " ";
            $sel_stk = $_GET['stk_id'];
        }
    }
    //check prov id
    if (isset($_GET['prov_id']) && !empty($_GET['prov_id'])) {
        if ($_GET['prov_id'] != 'all') {
            //get prov Id
            $sel_prov = $_GET['prov_id'];
            //provFilter 
            $provFilter = " AND summary_district.province_id = " . $sel_prov;
        } else {
            $sel_prov = $_GET['prov_id'];
            $qStrProv = "";
        }
    }
} else if (isset($_POST['go'])) {
    //check month_sel
    if (isset($_POST['month_sel']) && !empty($_POST['month_sel'])) {
        //get month_sel
        $sel_month = $_POST['month_sel'];
    }
//check year_sel
    if (isset($_POST['year_sel']) && !empty($_POST['year_sel'])) {
        //get year_sel
        $sel_year = $_POST['year_sel'];
    }
//check prod_sel
    if (isset($_POST['prod_sel']) && !empty($_POST['prod_sel']) && $_POST['prod_sel'] != 'all') {
        //get prod_sel
        $sel_item = $_POST['prod_sel'];
        //proFilter 
        $proFilter = " AND summary_district.item_id = '" . $sel_item . "'";
    }
//check stl_sel
    if (isset($_POST['stk_sel']) && !empty($_POST['stk_sel']) && $_POST['stk_sel'] != 'all') {
        //get stl_sel
        $sel_stk = $_POST['stk_sel'];
        //stkFilter 
        $stkFilter = " AND stakeholder.stkid = " . $sel_stk;
    } else {
        $qStrStk = " ";
        //get selected stk
        $sel_stk = isset($_GET['stk_id']) ? $_GET['stk_id'] : '';
    }
//check prov_sel
    if (isset($_POST['prov_sel']) && !empty($_POST['prov_sel']) && $_POST['prov_sel'] != 'all') {
        //get selected prov
        $sel_prov = $_POST['prov_sel'];
        //prov filter
        $provFilter = " AND summary_district.province_id = " . $sel_prov;
    } else {
        //selected prov
        $sel_prov = isset($_GET['prov_id']) ? $_GET['prov_id'] : '';
        $qStrProv = "";
    }
} else {
    if (date('d') > 10) {
        $date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
    } else {
        $date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
    }
    //selected month
    $sel_month = date('m', strtotime($date));
    //selected year
    $sel_year = date('Y', strtotime($date));
//selected prov
    $sel_prov = ($_SESSION['user_id'] == 2054) ? 1 : $_SESSION['user_province1'];
    //selected stk
    $sel_stk = $_SESSION['user_stakeholder1'];
    //stk filter
    $stkFilter = " AND stakeholder.stkid = $sel_stk ";
    //selected item
    $sel_item = 'IT-001';
    //prov filter
    $proFilter = " AND summary_district.item_id = '$sel_item'";
    $provFilter = ($sel_prov != 10) ? " AND summary_district.province_id = $sel_prov " : '';
    //selected prov
    $sel_prov = ($sel_prov != 10) ? $sel_prov : 'all';
}

if ($sel_stk == 'all') {
    $in_stk = 0;
}
if ($sel_prov == 'all') {
    $in_prov = 0;
}
$in_month = $sel_month;
$in_year = $sel_year;
$in_item = $sel_prod;

// Central Warehouses
ob_flush();
$total = 0;
$cwhtotal = 0;
$ppiutotal = 0;
$disttotal = 0;
//reporting date
$reportingDate = $sel_year . '-' . $sel_month . '-01';
//select query
//gets
//stk name
//wh name
//avg_consumption
//SOH
//MOS
$qry = "SELECT
			stakeholder.stkname,
			tbl_warehouse.wh_name,
			summary_national.avg_consumption,
			summary_national.soh_national_store AS SOH,
			(summary_national.soh_national_store / summary_national.avg_consumption) AS MOS
		FROM
			summary_national
		INNER JOIN stakeholder ON summary_national.stakeholder_id = stakeholder.stkid
		INNER JOIN tbl_warehouse ON stakeholder.stkid = tbl_warehouse.stkofficeid
		INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
		WHERE
			summary_national.reporting_date = '$reportingDate'
		AND summary_national.item_id = '$sel_item'
		AND stakeholder.lvl = 1
		$stkFilter
		GROUP BY
			summary_national.stakeholder_id
		ORDER BY
			stakeholder.stkorder ASC";
//query result
$qryRes = mysql_query($qry);
$numCentral = mysql_num_rows(mysql_query($qry));
$xmlCentral = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlCentral .= "<rows>";
$i = 1;
//fetch result
while ($row = mysql_fetch_array($qryRes)) {
    $xmlCentral .= "<row>";
    $xmlCentral .= "<cell>" . $i++ . "</cell>";
    $xmlCentral .= "<cell><![CDATA[" . $row['wh_name'] . "]]></cell>";
    $xmlCentral .= "<cell><![CDATA[" . $row['stkname'] . "]]></cell>";
    $xmlCentral .= "<cell>" . ((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK') . "</cell>";
    $xmlCentral .= "<cell>" . ((!is_null($row['SOH'])) ? number_format($row['SOH']) : 'UNK') . "</cell>";

    $rs_mos = mysql_query("SELECT getMosColor('" . $row['MOS'] . "', '" . $sel_item . "', '" . $sel_stk . "', 1)");
    $bgcolor = mysql_result($rs_mos, 0, 0);
    $xmlCentral .= "<cell><![CDATA[" . ((!is_null($row['MOS'])) ? number_format($row['MOS'], 1) : 'UNK') . "<label style=\"width:10px; height:12px; background-color:$bgcolor;vertical-align: text-top; margin-left:5px;\"></label>]]></cell>";

    $xmlCentral .= "</row>";
}
$xmlCentral .= "</rows>";

//District Warehouses
//gets
//dist id
//dist name
//prov id
//prov name
//stk name
//consumption
//SOH_district
//MOS_district
//SOH_field
//MOS_field
//SOH_total
//MOS_total
$qry = "SELECT	
			tbl_locations.PkLocID AS distId,
			tbl_locations.LocName AS distName,
			Province.PkLocID AS provId,
			Province.LocName AS provName,
			stakeholder.stkname,
			summary_district.avg_consumption,
			summary_district.soh_district_store AS SOH_district,
			(summary_district.soh_district_store / summary_district.avg_consumption) AS MOS_district,
			(summary_district.soh_district_lvl - summary_district.soh_district_store) AS SOH_field,
			((summary_district.soh_district_lvl - summary_district.soh_district_store) / summary_district.avg_consumption ) AS MOS_field,
			summary_district.soh_district_lvl AS SOH_total,
			(summary_district.soh_district_lvl / summary_district.avg_consumption) AS MOS_total
		FROM
			summary_district
		INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
		INNER JOIN tbl_locations AS Province ON tbl_locations.ParentID = Province.PkLocID
		INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
		WHERE
			summary_district.reporting_date = '$reportingDate'
		$proFilter
		$stkFilter
		$provFilter		
		ORDER BY
			provId ASC,
			distName ASC,
			stakeholder.stkorder ASC";
//query result
$qryRes = mysql_query($qry);
$numDistrict = mysql_num_rows(mysql_query($qry));
$xmlDistrict = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlDistrict .= "<rows>";
$i = 1;
//fetch results
while ($row = mysql_fetch_array($qryRes)) {
    $xmlDistrict .= "<row>";
    $xmlDistrict .= "<cell>" . $row['distId'] . "</cell>";
    $xmlDistrict .= "<cell>" . $i++ . "</cell>";
    $xmlDistrict .= "<cell><![CDATA[" . $row['provName'] . "]]></cell>";
    $xmlDistrict .= "<cell><![CDATA[" . $row['distName'] . "]]></cell>";
    $xmlDistrict .= "<cell><![CDATA[" . $row['stkname'] . "]]></cell>";
    $xmlDistrict .= "<cell>" . ((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK') . "</cell>";
    $xmlDistrict .= "<cell>" . ((!is_null($row['SOH_district'])) ? number_format($row['SOH_district']) : 'UNK') . "</cell>";

//query result
    $rs_mos = mysql_query("SELECT getMosColor('" . $row['MOS_district'] . "', '" . $sel_item . "', '" . $sel_stk . "', 3)");
    $bgcolor = mysql_result($rs_mos, 0, 0);
    $xmlDistrict .= "<cell><![CDATA[" . ((!is_null($row['MOS_district'])) ? number_format($row['MOS_district'], 1) : 'UNK') . "<label style=\"width:10px; height:12px; background-color:$bgcolor;vertical-align: text-top; margin-left:5px;\"></label>]]></cell>";

    $xmlDistrict .= "<cell>" . ((!is_null($row['SOH_field'])) ? number_format($row['SOH_field']) : 'UNK') . "</cell>";

    $rs_mos = mysql_query("SELECT getMosColor('" . $row['MOS_field'] . "', '" . $sel_item . "', '" . $sel_stk . "', 4)");
    $bgcolor = mysql_result($rs_mos, 0, 0);
    $xmlDistrict .= "<cell><![CDATA[" . ((!is_null($row['MOS_field'])) ? number_format($row['MOS_field'], 1) : 'UNK') . "<label style=\"width:10px; height:12px; background-color:$bgcolor;vertical-align: text-top; margin-left:5px;\"></label>]]></cell>";

    $xmlDistrict .= "<cell>" . ((!is_null($row['SOH_total'])) ? number_format($row['SOH_total']) : 'UNK') . "</cell>";
    $xmlDistrict .= "<cell>" . ((!is_null($row['MOS_total'])) ? number_format($row['MOS_total'], 1) : 'UNK') . "</cell>";
    $xmlDistrict .= "</row>";
}
$xmlDistrict .= "</rows>";


////////////// GET Product Name
$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '" . $sel_item . "' "));
$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name
//check sel_prov
if ($sel_prov == 'all' || $sel_prov == "") {
    $provinceName = "\'All\'";
} else {
    $qry = "SELECT tbl_locations.LocName as prov_title
			FROM tbl_locations where tbl_locations.PkLocID = '" . $sel_prov . "' ";
    $provinceQryRes = mysql_fetch_array(mysql_query($qry));
    $provinceName = "\'$provinceQryRes[prov_title]\'";
}
////////////// GET Stakeholders
//check sel_stk
if ($sel_stk == 'all' || $sel_stk == "") {
    $stakeholderName = "\'All\'";
} else {
    //stakeNameQryRes
    $stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '" . $sel_stk . "' "));
    //stakeholderName
    $stakeholderName = "\'$stakeNameQryRes[stkname]\'";
}
?>
<style>
    .objbox {
        overflow-x: hidden !important;
    }
</style>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
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
                        <table width="100%">
                            <tr>
                                <td><?php include(APP_PATH . "includes/report/reportheader.php"); ?></td>
                            </tr>
                        </table>
                        <?php if ($numCentral > 0) { ?>
                            <table width="100%">
                                <tr>
                                    <td align="right" style="padding-right:5px;">
                                        <img style="cursor:pointer;" src="<?php echo PUBLIC_URL; ?>images/pdf-32.png" onClick="gridCentral.toPDF('<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
                                        <img style="cursor:pointer;" src="<?php echo PUBLIC_URL; ?>images/excel-32.png" onClick="gridCentral.toExcel('<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" title="Export to Excel" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="hdrTable"><div id="central_container" style="width:100%; height:150px; background-color:white;overflow:hidden"></div></td>
                                </tr>
                            </table>
                            <br>
                        <?php } else { ?>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="hdrTable"><div id="central_container" style="width:100%; height:26px; background-color:white;overflow:hidden"></div></td>
                                </tr>
                                <tr>
                                    <td align="center"><strong>No record found.</strong></td>
                                </tr>
                            </table>
                            <br>
                        <?php } if ($numDistrict > 0) { ?>
                            <table width="100%">
                                <tr>
                                    <td align="right" style="padding-right:5px;">
                                        <img style="cursor:pointer;" src="<?php echo PUBLIC_URL; ?>images/pdf-32.png" onClick="gridDistrict.toPDF('<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
                                        <img style="cursor:pointer;" src="<?php echo PUBLIC_URL; ?>images/excel-32.png" onClick="gridDistrict.setColumnHidden(0, false);
                                                gridDistrict.toExcel('<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');
                                                gridDistrict.setColumnHidden(0, true);" title="Export to Excel" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="hdrTable"><div id="district_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div></td>
                                </tr>
                            </table>
                            <br>
                        <?php } else { ?>
                            <table width="100%" cellpadding="0" cellspacing="0" style="display:none;">
                                <tr>
                                    <td class="hdrTable"><div id="district_container" style="width:100%; height:26px; background-color:white;overflow:hidden"></div></td>
                                </tr>
                                <tr>
                                    <td align="center"><strong>No record found.</strong></td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END FOOTER -->
    <?php
    //include footer
    include PUBLIC_PATH . "/html/footer.php";
//include report_includes
    include PUBLIC_PATH . "/html/reports_includes.php";
    ?>
    <script>
        var mygrid;
        function doInitGrid() {
            gridCentral = new dhtmlXGridObject('central_container');
            gridCentral.selMultiRows = true;
            gridCentral.setImagePath("<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
            gridCentral.setHeader("<div style='text-align:center;'><?php echo "Central Warehouse Report for Stakeholder = $stakeholderName And Product = $prodName (" . date('F', mktime(0, 0, 0, $sel_month)) . ' ' . $sel_year . ")"; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan");
            gridCentral.attachHeader("Sr. No., Warehouse, Stakeholder, AMC, Stock on Hand, Month of Stock");
            gridCentral.attachFooter("<div style='font-size: 10px;'><?php echo $lastUpdateText; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan");
            gridCentral.setInitWidths("60,*,250,100,100,100");
            gridCentral.setColAlign("center,left,left,right,right,right");
            gridCentral.setColSorting("int,str,str");
            gridCentral.setColTypes("ro,ro,ro,ro,ro,ro");
            gridCentral.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
            gridCentral.setSkin("light");
            gridCentral.init();
            gridCentral.clearAll();
            gridCentral.loadXMLString('<?php echo $xmlCentral; ?>');

            /*gridProvince = new dhtmlXGridObject('province_container');
             gridProvince.selMultiRows = true;
             gridProvince.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
             gridProvince.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Provincial Report for Stakeholder = $stakeholderName Province/Region = $provinceName  And Product = $prodName (" . date('F', mktime(0, 0, 0, $sel_month)) . ' ' . $sel_year . ")"; ?></div>,#cspan,#cspan,#cspan,#cspan");
             gridProvince.attachHeader("<span title='Provincial Office'>Provincial Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
             gridProvince.setInitWidths("*,250,100,100,100");
             gridProvince.setColAlign("left,left,right,right,right");
             gridProvince.setColSorting("str,str");
             gridProvince.setColTypes("ro,ro,ro,ro,ro");
             gridProvince.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
             gridProvince.setSkin("light");
             gridProvince.init();
             gridProvince.clearAll();
             gridProvince.loadXMLString('<?php //echo $xmlProvince;    ?>');*/

            gridDistrict = new dhtmlXGridObject('district_container');
            gridDistrict.selMultiRows = true;
            gridDistrict.setImagePath("<?php echo PUBLIC_URL; ?>dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
            gridDistrict.setHeader(",<div style='text-align:center;'><?php echo "Districts Report for Stakeholder = $stakeholderName Province/Region = $provinceName  And Product = $prodName (" . date('F', mktime(0, 0, 0, $sel_month)) . ' ' . $sel_year . ")"; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
            gridDistrict.attachHeader("District Id, Sr. No., Province/Region, District, Stakeholder, AMC, SOH Store, MOS Store, SOH Field, MOS Field, SOH Total, MOS Total");
            gridDistrict.attachFooter(",<div style='font-size: 10px;'><?php echo $lastUpdateText; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
            gridDistrict.setInitWidths("60,50,*,150,100,80,80,80,80,80,80,80");
            gridDistrict.setColAlign("left,center,left,left,left,right,right,right,right,right,right,right");
            gridDistrict.setColSorting("int,str,str,str");
            gridDistrict.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
            gridDistrict.setColumnHidden(0, true);
            gridDistrict.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
            gridDistrict.setSkin("light");
            gridDistrict.init();
            gridDistrict.clearAll();
            gridDistrict.loadXMLString('<?php echo $xmlDistrict; ?>');
        }
    </script>
</body>
</html>