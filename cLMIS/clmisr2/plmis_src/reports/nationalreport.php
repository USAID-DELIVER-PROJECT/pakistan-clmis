<?php
ob_start();
$report_id = "SNASUM";
$report_title = "National Report for";
$actionpage = "";
$parameters = "TS";
$parameter_width = "40%";

include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();


if(isset($_GET['month_sel']) && !isset($_POST['go'])){

    $sel_year = $_GET['year_sel'];
    $sel_month = $_GET['month_sel'];
    $sel_stk = 1;
    $sector = 'public';
    $rptType = $sector;
    if ($sector=='Public' || $sector=='public')
        $lvl_stktype=0;
    else
        $lvl_stktype=1;


}
else if(isset($_POST['go'])){

    if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
        $sel_month = $_POST['month_sel'];

    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];

    if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
        $sel_stk = $_POST['stk_sel'];

    $sector = $_POST['sector'];
    $rptType = $sector;
    if ($sector=='Public' || $sector=='public')
        $lvl_stktype=0;
    else
        $lvl_stktype=1;
} else {
	
	if ( date('d') > 10 )
	{
		$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
	}
	else
	{
		$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
	}
	$sel_month = date('m', strtotime($date));
	$sel_year = date('Y', strtotime($date));
	if ($_SESSION['userdata'][12] == 0){
		$sector = 'public';
		$rptType = 'public';
	}else if ($_SESSION['userdata'][12] == 1){
		$sector = 'private';
		$rptType = 'private';
	}else{
		$sector = 'public';
		$rptType = 'public';
	}
    $sel_stk = 0;
    if ($sector=='Public' || $sector=='public')
        $lvl_stktype=0;
    else
        $lvl_stktype=1;

}

$in_type =  'N';
$in_month =  $sel_month;
$in_year =   $sel_year;
$in_item =  '';
$in_stk = 0 ;
$in_prov = 0;
$in_dist = 0;

//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/reports/".$basename;

/*if ($sel_stk == 'all'){
    $stkName = "\'All\'";
}else{
    $stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
    $stkName = "\'$stakeNameQryRes[stkname]\'";
}*/
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
<?php
if ($rptType == 'all' ){
	$stk = 0;
	$filter = '';
}else if ($rptType == 'public'){
	$stk = 0;
	$filter = ' AND stakeholder.stk_type_id = 0';
}else if ($rptType == 'private'){
	$stk = 0;
	$filter = ' AND stakeholder.stk_type_id = 1';
}

$reportingDate = $sel_year.'-'.$sel_month.'-01';
$qry = "SELECT
			itminfo_tab.itmrec_id,
			itminfo_tab.itm_name,
			SUM(summary_national.consumption) AS consumption,
			SUM(summary_national.avg_consumption) AS avg_consumption,
			SUM(summary_national.soh_national_lvl) AS SOH,
			(SUM(summary_national.soh_national_lvl) / SUM(summary_national.avg_consumption)) AS MOS,
			(SUM(summary_national.consumption) * itminfo_tab.extra) AS CYP
		FROM
			summary_national
		INNER JOIN itminfo_tab ON summary_national.item_id = itminfo_tab.itmrec_id
		INNER JOIN stakeholder ON summary_national.stakeholder_id = stakeholder.stkid
		WHERE
			summary_national.reporting_date = '$reportingDate'
		AND itminfo_tab.itm_category = 1
		$filter
		GROUP BY
			itminfo_tab.itmrec_id
		ORDER BY
			itminfo_tab.frmindex ASC";
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .= "<rows>";
while ( $row = mysql_fetch_array($qryRes) )
{
	$xmlstore .= "<row>";
	$xmlstore .= "<cell>".$row['itm_name']."</cell>";
	$xmlstore .= "<cell>".number_format($row['consumption'])."</cell>";
	$xmlstore .= "<cell>".number_format($row['avg_consumption'])."</cell>";
	$xmlstore .= "<cell>".number_format($row['SOH'])."</cell>";
	$xmlstore .= "<cell>".number_format($row['MOS'], 1)."</cell>";
	
	$rs_mos = mysql_query("SELECT getMosColor('".$row['MOS']."', '".$row['itmrec_id']."', '".$stk."', 1)");
	$bgcolor = mysql_result($rs_mos, 0, 0);
	
	$xmlstore .= "<cell><![CDATA[<div style=\"width:10px; height:12px; background-color:$bgcolor;\"></div>]]></cell>";
	$xmlstore .= "<cell>".number_format($row['CYP'])."</cell>";
	$xmlstore .= "</row>";
}
$xmlstore .= "</rows>";
?>
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<div style='text-align:center;'><?php echo "National Report - ".ucwords($rptType)." Sector".' Stakeholders ('. date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?> </div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("Product, Consumption, AMC, Stock On Hand, <div style='text-align: center;'>Month of Stock</sdiv>,#cspan, CYP");
		mygrid.setInitWidths("*,160,160,160,60,40,80");
		mygrid.setColAlign("left,right,right,right,center,center,right");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
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
<?php 
include "../../plmis_inc/common/_top.php";?>


<div class="page-content-wrapper">
	<div class="page-content">

	<!-- BEGIN PAGE HEADER-->
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
                            <div id="mygrid_container" style="width:100%; height:320px;"></div>
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

<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
<script>
    $(function(){
        $('#sector').change(function(e) {
            var val = $('#sector').val();
            getStakeholder(val, '');
        });
        getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
    })
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>