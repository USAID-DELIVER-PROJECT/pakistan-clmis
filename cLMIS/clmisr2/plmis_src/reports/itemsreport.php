<?php
ob_start();
include("../../html/adminhtml.inc.php");
Login();

//////////// GET FILE NAME FROM THE URL
$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/reports/".$basename;

$report_id = "SNASUMSTOCKLOC";  
$report_title = "Stock Availability Report for ";
$actionpage = "itemsreport.php";
$parameters = "TS01P01I";

include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File

if(isset($_GET['tp']) && !isset($_POST['go']))
{
	if(isset($_GET['report_month']) && !empty($_GET['report_month']))
		$sel_month = $_GET['report_month'];
	
	if(isset($_GET['report_year']) && !empty($_GET['report_year']))
		$sel_year = $_GET['report_year'];
	
	if(isset($_GET['item_id']) && !empty($_GET['item_id'])){
		$sel_item = $_GET['item_id'];
		$proFilter = " AND summary_district.item_id = '".$sel_item."'";
	}	
	if(isset($_GET['stk_id']) && !empty($_GET['stk_id']))
	{	
		if ( $_GET['stk_id']!='all')
		{
			$sel_stk = $_GET['stk_id'];
			$stkFilter = " AND stakeholder.stkid = ".$sel_stk;
		}
		else
		{
			$qStrStk = " ";
			$sel_stk = $_GET['stk_id'];
		}
		
	}	
	if(isset($_GET['prov_id']) && !empty($_GET['prov_id']))
	{	
		if ($_GET['prov_id']!='all')
		{
			$sel_prov = $_GET['prov_id'];	
			$provFilter = " AND summary_district.province_id = ".$sel_prov;
		}
		else
		{
			$sel_prov = $_GET['prov_id'];	
			$qStrProv = "";
		}
	}

}
else if(isset($_POST['go']))
{
	if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
		$sel_month = $_POST['month_sel'];
	
	if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
		$sel_year = $_POST['year_sel'];
	
	if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']) && $_POST['prod_sel']!='all'){
		$sel_item = $_POST['prod_sel'];
		$proFilter = " AND summary_district.item_id = '".$sel_item."'";
	}

	if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']) && $_POST['stk_sel']!='all'){	
		$sel_stk = $_POST['stk_sel'];
		$stkFilter = " AND stakeholder.stkid = ".$sel_stk;
	}else{
		$qStrStk = " ";
		$sel_stk = $_GET['stk_id'];	
	}
	
	if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']) && $_POST['prov_sel']!='all'){	
		$sel_prov = $_POST['prov_sel'];	
		$provFilter = " AND summary_district.province_id = ".$sel_prov;
	}else{
		$sel_prov = $_GET['prov_id'];	
		$qStrProv = "";
	}
} 
else 
{
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
	
    $sel_prov = ($_SESSION['userdata'][0] == 2054) ? 1 : $_SESSION['userdata'][10];
	$sel_stk = $_SESSION['userdata'][7];
	$stkFilter = " AND stakeholder.stkid = $sel_stk ";
	$sel_item = 'IT-001';
	$proFilter = " AND summary_district.item_id = '$sel_item'";
	
	$provFilter = ($sel_prov != 10) ? " AND summary_district.province_id = $sel_prov " : '';
	$sel_prov = ($sel_prov != 10) ? $sel_prov : 'all';
}

if($sel_stk=='all'){$in_stk = 0; }
if($sel_prov=='all'){$in_prov = 0; }
$in_month =  $sel_month;
$in_year =   $sel_year;
$in_item =  $sel_prod;
?>
<?php include "../../plmis_inc/common/_header.php";?>

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
<script language="javascript">
	function frmvalidate(){
		
		if(document.getElementById('prod_sel').value==''){
			alert('Please Select Product');
			document.getElementById('prod_sel').focus();
			return false;
		}
		
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
</script>
<?php
// Central Warehouses
ob_flush();
$total = 0;
$cwhtotal = 0;
$ppiutotal = 0;
$disttotal = 0;
$reportingDate = $sel_year.'-'.$sel_month.'-01';
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
		WHERE
			summary_national.reporting_date = '$reportingDate'
		AND summary_national.item_id = '$sel_item'
		AND stakeholder.lvl = 1
		$stkFilter
		GROUP BY
			summary_national.stakeholder_id
		ORDER BY
			stakeholder.stkorder ASC";
$qryRes = mysql_query($qry);
$numCentral = mysql_num_rows(mysql_query($qry));
$xmlCentral = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlCentral .= "<rows>";
while ( $row = mysql_fetch_array($qryRes) )
{
	$xmlCentral .= "<row>";
	$xmlCentral .= "<cell><![CDATA[".$row['wh_name']."]]></cell>";
	$xmlCentral .= "<cell><![CDATA[".$row['stkname']."]]></cell>";
	$xmlCentral .= "<cell>".((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK')."</cell>";
	$xmlCentral .= "<cell>".((!is_null($row['SOH'])) ? number_format($row['SOH']) : 'UNK')."</cell>";
	$xmlCentral .= "<cell>".((!is_null($row['MOS'])) ? number_format($row['MOS'], 1) : 'UNK')."</cell>";
	$xmlCentral .= "</row>";
}
$xmlCentral .= "</rows>";

//District Warehouses
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
$qryRes = mysql_query($qry);
$numDistrict = mysql_num_rows(mysql_query($qry));
$xmlDistrict = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlDistrict .= "<rows>";
while ( $row = mysql_fetch_array($qryRes) )
{
	$xmlDistrict .= "<row>";
	$xmlDistrict .= "<cell>".$row['distId']."</cell>";
	$xmlDistrict .= "<cell><![CDATA[".$row['distName']."]]></cell>";
	$xmlDistrict .= "<cell><![CDATA[".$row['provName']."]]></cell>";
	$xmlDistrict .= "<cell><![CDATA[".$row['stkname']."]]></cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['SOH_district'])) ? number_format($row['SOH_district']) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['MOS_district'])) ? number_format($row['MOS_district'], 1) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['SOH_field'])) ? number_format($row['SOH_field']) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['MOS_field'])) ? number_format($row['MOS_field'], 1) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['SOH_total'])) ? number_format($row['SOH_total']) : 'UNK')."</cell>";
	$xmlDistrict .= "<cell>".((!is_null($row['MOS_total'])) ? number_format($row['MOS_total'], 1) : 'UNK')."</cell>";
	$xmlDistrict .= "</row>";
}
$xmlDistrict .= "</rows>";


////////////// GET Product Name
$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name

if ($sel_prov == 'all' || $sel_prov == ""){
	$provinceName = "\'All\'";		
}else{
	$qry = "SELECT tbl_locations.LocName as prov_title
			FROM tbl_locations where tbl_locations.PkLocID = '".$sel_prov."' ";
	$provinceQryRes = mysql_fetch_array(mysql_query($qry));
	$provinceName = "\'$provinceQryRes[prov_title]\'";
}
////////////// GET Stakeholders

if ($sel_stk == 'all' || $sel_stk == ""){
	$stkName = "\'All\'";		
}else{
	$stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
	$stkName = "\'$stakeNameQryRes[stkname]\'";
}		
?>
 <style>
.objbox{overflow-x:hidden !important;}
</style>   
<script>
	var mygrid;
	function doInitGrid(){
		
		gridCentral = new dhtmlXGridObject('central_container');
		gridCentral.selMultiRows = true;
		gridCentral.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		gridCentral.setHeader("<div style='text-align:center;'><?php echo "Central Warehouse Report for Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan");
		gridCentral.attachHeader("Warehouse, Stakeholder, AMC, Stock on Hand, Month of Stock");
		gridCentral.setInitWidths("*,250,100,100,100");
		gridCentral.setColAlign("left,left,right,right,right");
		gridCentral.setColSorting("str,str");
		gridCentral.setColTypes("ro,ro,ro,ro,ro");
		gridCentral.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		gridCentral.setSkin("light");
		gridCentral.init();
		gridCentral.clearAll();
		gridCentral.loadXMLString('<?php echo $xmlCentral;?>');
		
		/*gridProvince = new dhtmlXGridObject('province_container');
		gridProvince.selMultiRows = true;
		gridProvince.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		gridProvince.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Provincial Report for Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan");
		gridProvince.attachHeader("<span title='Provincial Office'>Provincial Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
		gridProvince.setInitWidths("*,250,100,100,100");
		gridProvince.setColAlign("left,left,right,right,right");
		gridProvince.setColSorting("str,str");
		gridProvince.setColTypes("ro,ro,ro,ro,ro");
		gridProvince.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		gridProvince.setSkin("light");
		gridProvince.init();
		gridProvince.clearAll();
		gridProvince.loadXMLString('<?php echo $xmlProvince;?>');*/
		
		gridDistrict = new dhtmlXGridObject('district_container');
		gridDistrict.selMultiRows = true;
		gridDistrict.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		gridDistrict.setHeader(",<div style='text-align:center;'><?php echo "Districts Report for Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		gridDistrict.attachHeader("District Id, District, Province/Region, Stakeholder, AMC, SOH Store, MOS Store, SOH Field, MOS Field, SOH Total, MOS Total");
		gridDistrict.setInitWidths("50,*,150,100,80,80,80,80,80,80,80");
		gridDistrict.setColAlign("left,left,left,left,right,right,right,right,right,right,right");
		gridDistrict.setColSorting("str,str,str");
		gridDistrict.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
		gridDistrict.setColumnHidden(0,true);
		gridDistrict.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		gridDistrict.setSkin("light");
		gridDistrict.init();
		gridDistrict.clearAll();
		gridDistrict.loadXMLString('<?php echo $xmlDistrict;?>');
	}
</script>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">

<div class="page-container">
<?php include "../../plmis_inc/common/_top.php";?>
    
    <div class="page-content-wrapper">
        <div class="page-content">
    
            <div class="row">
                <div class="col-md-12">
    
                    <table width="100%">
                        <tr>
                            <td colspan="2">
                                <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                            </td>
                        </tr>
                    </table>
    
                <?php
                if($numCentral > 0) { ?>
                    <table width="100%">
                        <tr>
                            <td align="right" style="padding-right:5px;">
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="gridCentral.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="gridCentral.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                            </td>
                        </tr>
                        <tr>
                            <td class="hdrTable">
                                <div id="central_container" style="width:100%; height:150px; background-color:white;overflow:hidden"></div>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <?php }else {?>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="hdrTable">
                                <div id="central_container" style="width:100%; height:26px; background-color:white;overflow:hidden"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <strong>No record found.</strong>
                            </td>
                        </tr>
                    </table><br>
                    
                    <?php } if($numDistrict > 0) { ?>
                    <table width="100%">
                        <tr>
                            <td align="right" style="padding-right:5px;">
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="gridDistrict.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="gridDistrict.setColumnHidden(0,false); gridDistrict.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php'); gridDistrict.setColumnHidden(0,true); " />
                            </td>
                        </tr>
                        <tr>
                            <td class="hdrTable">
                                <div id="district_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div>
                            </td>
                        </tr>
                    </table>
                    <br>
					<?php }else {?>
                    <table width="100%" cellpadding="0" cellspacing="0" style="display:none;">
                        <tr>
                            <td class="hdrTable">
                                <div id="district_container" style="width:100%; height:26px; background-color:white;overflow:hidden"></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <strong>No record found.</strong>
                            </td>
                        </tr>
                    </table>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>