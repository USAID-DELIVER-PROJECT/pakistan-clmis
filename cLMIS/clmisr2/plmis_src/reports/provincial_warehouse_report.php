<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
Login();

$report_id = "PROVINCIALWAREHOUSE";
$report_title = "Provincial Yearly Report for ";    
$actionpage = "provincial_warehouse_report.php";
$parameters = "TS01IP";
$parameter_width = "95%";

//forward page setting
$forwardparameters = "";
$forwardpage = ""; 

if(isset($_POST['go'])){
	
	if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
		$sel_year = $_POST['year_sel'];
	if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
		$sel_stk = $_POST['stk_sel'];
	if(isset($_POST['repIndicators']) && !empty($_POST['repIndicators']))
		$sel_indicator = $_POST['repIndicators'];
	if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
		$sel_prov = $_POST['prov_sel'];
	if($_POST['sector']=='All')
		$rptType = 'All';
	else
		$rptType = $_POST['sector']; 
		
	if(!empty($sel_stk) && $sel_stk!='all'){
		$stkFilter = " AND tbl_warehouse.stkid = '".$sel_stk."' ";
	}else if ( $rptType == 'public' && $sel_stk == 'all' ){
		$stkFilter = " AND stakeholder.stk_type_id = 0";
	}else if ( $rptType == 'private' && $sel_stk == 'all' ){
		$stkFilter = " AND stakeholder.stk_type_id = 1";
	}
	if ($sel_prov != 'all'){
		$provFilter = " AND tbl_locations.PkLocID = $sel_prov";
	}

	// Check indicators
	if ($sel_indicator == 1){
		$ind = "\'Consumption\'";
		$lvlFilter = " AND stakeholder.lvl = 4";
		$colName = 'SUM(tbl_wh_data.wh_issue_up) AS total';
	}else if ($sel_indicator == 2){
		$ind = "\'Stock on Hand\'";
		$lvlFilter = " AND stakeholder.lvl >= 2";
		$colName = 'SUM(tbl_wh_data.wh_cbl_a) AS total';
	}else if ($sel_indicator == 3){
		$ind = "\'CYP\'";
		$lvlFilter = " AND stakeholder.lvl = 4";
		$colName = 'SUM(tbl_wh_data.wh_issue_up) * itminfo_tab.extra AS total';
	}else if ($sel_indicator == 4){
		$ind = "\'Received(District)\'";
		$lvlFilter = " AND stakeholder.lvl = 3";
		$colName = 'SUM(tbl_wh_data.wh_received) AS total';
	}else if ($sel_indicator == 5){
		$ind = "\'Received(Field)\'";
		$lvlFilter = " AND stakeholder.lvl = 4";
		$colName = 'SUM(tbl_wh_data.wh_received) AS total';
	}

	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	
	$qry = "SELECT
				itminfo_tab.itmrec_id,
				itminfo_tab.itm_name,
				itminfo_tab.extra,
				tbl_wh_data.RptDate,
				$colName
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
			INNER JOIN tbl_locations ON tbl_warehouse.prov_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
				$stkFilter
				$provFilter
				$lvlFilter
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";
	
	
} else {
	if ( date('d') > 10 )
	{
		$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
	}
	else
	{
		$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
	}
	$sel_year = date('Y', strtotime($date));
	
	if ($_SESSION['userdata'][12] == 0){
		$rptType = 'public';
		$lvl_stktype = 0;
	}else if ($_SESSION['userdata'][12] == 1){
		$rptType = 'private';
		$lvl_stktype = 1;
	}
	
	$sel_stk = $_SESSION['userdata'][7];
	$sel_prov = $sel_prov = ($_SESSION['userdata'][0] == 2054) ? 1 : $_SESSION['userdata'][10];
	$provFilter = ($sel_prov != 10) ? (" AND tbl_locations.PkLocID = '$sel_prov' ") : '';
	$sel_prov = ($sel_prov != 10) ? $sel_prov : 'all';
	
	$sel_item = "IT-001";
	$Stkid = "";
	$sel_indicator = 1;
	
	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	$qry = "SELECT
				itminfo_tab.itmrec_id,
				itminfo_tab.itm_name,
				itminfo_tab.extra,
				tbl_wh_data.RptDate,
				SUM(tbl_wh_data.wh_issue_up) AS total
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
			INNER JOIN tbl_locations ON tbl_warehouse.prov_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				stakeholder.lvl = 4
			$provFilter
			AND tbl_warehouse.stkid = '$sel_stk'
			AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";
	
	$ind = "\'Consumption\'";
	
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
	

// Execute uery
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$data = array();
while ( $row = mysql_fetch_array($qryRes) )
{
	$data[$row['itmrec_id']][$row['RptDate']] = $row['total'];
	$itemsArr[$row['itmrec_id']]['name'] = $row['itm_name'];
	$itemsArr[$row['itmrec_id']]['CYPFactror'] = $row['extra'];
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
foreach( $data as $itemId=>$prodData )
{
	
	$xmlstore .= "<row>";
	$xmlstore .= "<cell><![CDATA[".$itemsArr[$itemId]['name']."]]></cell>";
	foreach ($period as $date)
	{
		$total = number_format($prodData[$date->format( "Y-m-d" )]);
		$param = urlencode($sel_indicator.'|'.$sel_year.'|'.$date->format("m") .'|'.$sel_stk.'|'.$sel_prov.'|'.$itemId.'|'.$itemsArr[$itemId]['name'].'|'.$rptType.'|'.$itemsArr[$itemId]['CYPFactror']);
		if ($total != 0)
		{
			$xmlstore .= "<cell style=\"text-align:right\"><![CDATA[<a href=javascript:showDetail(\"$param\")>$total</a>]]>^_self</cell>";
		}
		else
		{
			$xmlstore .="<cell>$total</cell>";
		}
	}
	$xmlstore .= "</row>";
}

$xmlstore .="</rows>";


////////  Stakeholders for Grid Header
if ($sel_stk == 'all'){
	$stkName = "\'All $sector\'";
}else{
	$stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
	$stkName = "\'$stakeNameQryRes[stkname]\'";
}
	
if ($sel_prov=='all'){
	$provinceName = "\'All\'";	
}
else
{
	$provinceQryRes = mysql_fetch_array(mysql_query("SELECT LocName as prov_title FROM tbl_locations WHERE PkLocID = '".$sel_prov."' "));
	$provinceName = "\'$provinceQryRes[prov_title]\'";
}
?>

<?php include "../../plmis_inc/common/_header.php";?>
<script language="javascript">
    function frmvalidate(){
        if(document.getElementById('month_sel').value==''){
            alert('Please Select Month');
            document.getElementById('month_sel').focus();
            return false;
        }
        if(document.getElementById('year_sel').value==''){
            alert('Please Select Year');
            document.getElementById('year_sel').focus();
            return false;
        }
    }
    function functionCall(month, year, prod){
        window.location = "nationalreportSTK.php?month_sel="+month+"&year_sel="+year+"&item_sel="+prod;
    }
    function getStakeholder(val, stk)
    {
        $.ajax({
            url: 'ajax_stk.php',
            data: {type:val, stk: stk},
            type: 'POST',
            success: function(data){
                $('#stk_sel').html(data)
            }
        })
    }
</script>
<link href="../../plmis_css/tab_menu.css" media="screen" rel="stylesheet" type="text/css" />
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

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
		mygrid.setHeader("<div style='text-align:center;'><?php echo "Provincial Yearly Report for Stakeholder(s) = $stkName Province = $provinceName And Indicator = $ind (".$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
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
	function showDetail(param)
	{
		window.open('detail_view.php?param='+param, '_blank', 'scrollbars=1,width=600,height=500');
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
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" title="Export to Excel" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="mygrid_container" style="width:100%; height:320px; background-color:white;"></div>
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
    <script>	
	function getStakeholder(val, stk)
	{
		$.ajax({
			url: 'ajax_stk.php',
			data: {type:val, stk: stk},
			type: 'POST',
			success: function(data){
				$('#stk_sel').html(data)
			}
		})
	}
	$(function(){
		$('#sector').change(function(e) {
			var val = $('#sector').val();
			getStakeholder(val, '');
		});
		getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
	})
</script>
</body>
<!-- END BODY -->
</html>