<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();

$report_id = "CENTRALWAREHOUSE";
$report_title = "Central/Provincial Warehouse Report for ";
$actionpage = "central_warehouse_report.php";
$parameters = "TS01I";
$parameter_width = "100%";

//forward page setting
$forwardparameters = "";
$forwardpage = "";

if(isset($_POST['go']))
{
    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];
    if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
        $sel_stk = $_POST['stk_sel'];
    if(isset($_POST['repIndicators']) && !empty($_POST['repIndicators']))
        $sel_indicator = $_POST['repIndicators'];
    if(isset($_POST['wh_type']) && !empty($_POST['repIndicators']))
        $whType = $_POST['wh_type'];
		
	if ( $sel_stk != 'all' ){
		$stkFilter = " AND tbl_warehouse.stkid = $sel_stk";
	}
	if ($whType == 'all'){
		$whTypeFilter = " AND Office.lvl IN(1, 2)";
	}else{
		$whTypeFilter = " AND Office.lvl = $whType";
	}
	
	// Check Indicators
    if ($sel_indicator == 1){
        $ind = "\'Issued\'";
		$colName = 'SUM(tbl_wh_data.wh_issue_up) AS total';
    }else if ($sel_indicator == 2){
        $ind = "\'Stock on Hand\'";
		$colName = 'SUM(tbl_wh_data.wh_cbl_a) AS total';
    }else if ($sel_indicator == 3){
        $ind = "\'Received\'";
		$colName = 'SUM(tbl_wh_data.wh_received) AS total';
    }
	
	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	
	$qry = "SELECT
				itminfo_tab.itm_name,
				tbl_wh_data.RptDate,
				$colName
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
			INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				1 = 1
			AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			$stkFilter
			$whTypeFilter
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";

}
else {
	if ( date('d') > 10 )
	{
		$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
	}
	else
	{
		$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
	}
	$sel_year = date('Y', strtotime($date));
	
    $Stkid = "";
    $sel_stk = 'all';
		
	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	
	$qry = "SELECT
				itminfo_tab.itm_name,
				tbl_wh_data.RptDate,
				SUM(tbl_wh_data.wh_issue_up) AS total
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
			INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				Office.lvl < 3
			AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			AND itminfo_tab.itm_category = 1
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";
    $ind = "\'Issued\'";
}

if($sel_stk==0){
    $in_type   = 'N';
    $in_stk    = 0;
} else {
    $in_type   = 'S';
    $in_id     = $sel_stk;
    $in_stk    = $sel_stk;
}
$in_year   = $sel_year;

////////  Stakeholders for Grid Header
if ($sel_stk == 'all'){
    $stkName = "\'All\'";
}else{
    $stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
    if($stakeNameQryRes['stkname'] == 'PWD')
    {
        $stkName = "\'PPW/CWH\'";
    }
    else
    {
        $stkName = "\'$stakeNameQryRes[stkname]\'";
    }
}
$whType = ($_REQUEST['wh_type'] == 1) ? 'Central' : 'Provincial';

// Execute uery
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$data = array();
while ( $row = mysql_fetch_array($qryRes) )
{
	$data[$row['itm_name']][$row['RptDate']] = $row['total'];
}

// Create XML
$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";

$begin = new DateTime($startDate);
$end = new DateTime($endDate1);
$diff = $begin->diff($end);
$totalMonths = (($diff->format('%y') * 12) + $diff->format('%m'));
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
foreach( $data as $itemName=>$prodData )
{
	
	$xmlstore .= "<row>";
	$xmlstore .= "<cell><![CDATA[".$itemName."]]></cell>";
	foreach ($period as $date)
	{
		$xmlstore .= "<cell>".number_format($prodData[$date->format( "Y-m-d" )])."</cell>";
	}
	$xmlstore .= "</row>";
}

$xmlstore .="</rows>";
?>

<?php include "../../plmis_inc/common/_header.php";?>
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script type="text/javascript">
    function func(){
        var val = $('#stk_sel').val();

        if(val == 2){
            $('#ppiuList').show("slow");
            $('#ppiuList1').show("slow");
        }else {
            $('#ppiuList').hide("slow");
            $('#ppiuList1').hide("slow");
        }
    }
</script>

<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        //mygrid.setHeader("Province,Consumption,AMC,On Hand,MOS,#cspan");
        mygrid.setHeader("<div style='text-align:center;'><?php echo "$whType Warehouse Report for Stakeholder(s) = $stkName And Indicator = $ind (".$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("Product, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec");
        mygrid.setInitWidths("*,65,65,65,65,65,65,65,65,65,65,65,65");
        mygrid.setColAlign("left,right,right,right,right,right,right,right,right,right,right,right,right");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }

</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php include "../../plmis_inc/common/_top.php";?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
					<table width="100%">
                        <tr>
                            <td>
                                <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                            </td>
                        </tr>
						<?php
                        if ($num > 0)
                        {?>
                        <tr>
                            <td align="right" style="padding-right:5px;">
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="mygrid_container" style="width:100%; height:390px; background-color:white;"></div>
                            </td>
                        </tr>
						<?php
                        }
                        else
                        {
                            echo "<tr><td>No record found</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>