<?php
include("../../html/adminhtml.inc.php");
Login();
$report_id = "SDISTRICTREPORT";
$report_title = "District Report for ";
$actionpage = "";
$parameters = "SPIT";
$showexport = "no";
//back page setting
$backparameters = "TSI";
$backpage = "provincialreport.php";

//forward page setting
$forwardparameters = "TSIP";
$forwardpage = "fieldstkreport.php";
$parameter_width = "90%";
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File


if(isset($_GET['month_sel']) && !isset($_POST['go']))
{
    $sel_month = $_GET['month_sel'];
    $sel_year = $_GET['year_sel'];
    $sel_item = $_GET['item_sel'];
    $sel_prov = $_GET['prov_sel'];
    $rptType = $_GET['sector'];
    $sel_stk = $_GET['stkid'];
		
	if ($sel_prov != 'all'){
		$provFilter = " AND tbl_locations.ParentID = $sel_prov";
		$provFilter1 = " AND summary_district.province_id = $sel_prov";
	}
	
	if(!empty($sel_stk) && $sel_stk != 'all'){
		$filter = " AND summary_district.stakeholder_id = '".$sel_stk."'";
		$stkFilter = " AND tbl_warehouse.stkid = '".$sel_stk."'";
	}else if ( $rptType == 'public' && $sel_stk == 'all' ){
		$filter = " AND stakeholder.stk_type_id = 0";
		$stkFilter = " AND stakeholder.stk_type_id = 0";
		$lvl_stktype = 0;
	}else if ( $rptType == 'private' && $sel_stk == 'all' ){
		$filter = " AND stakeholder.stk_type_id = 1";
		$stkFilter = " AND stakeholder.stk_type_id = 1";
		$lvl_stktype = 1;
	}
}
elseif(isset($_POST['go']))
{
    if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
        $sel_month = $_POST['month_sel'];

    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];

    if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
        $sel_item = $_POST['prod_sel'];

    if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
        $sel_prov = $_POST['prov_sel'];
		
	if ($sel_prov != 'all'){
		$provFilter = " AND tbl_locations.ParentID = $sel_prov";
		$provFilter1 = " AND summary_district.province_id = $sel_prov";
	}

	if ( $_POST['sector'] == 'All' ){
		 $rptType = 'All';
	}else{
		$rptType = $_POST['sector'];
	}
	
	if(!empty($_POST['stk_sel']) && $_POST['stk_sel']!='all'){
		$sel_stk = $_POST['stk_sel'];
		$filter = " AND summary_district.stakeholder_id = '".$_POST['stk_sel']."'";
		$stkFilter = " AND tbl_warehouse.stkid = '".$sel_stk."'";
	}else if ( $_POST['sector'] == 'public' && $_POST['stk_sel'] == 'all' ){
		$sel_stk = 'all';
		$filter = " AND stakeholder.stk_type_id = 0";
		$stkFilter = " AND stakeholder.stk_type_id = 0";
	}else if ( $_POST['sector'] == 'private' && $_POST['stk_sel'] == 'all' ){
		$sel_stk = 'all';
		$filter = " AND stakeholder.stk_type_id = 1";
		$stkFilter = " AND stakeholder.stk_type_id = 1";
	}else{
		$sel_stk = 'all';
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

    $sel_item = "IT-001";
    $sel_prov = ($_SESSION['userdata'][0] == 2054) ? 1 : $_SESSION['userdata'][10];
	$sel_prov = ($sel_prov != 10) ? $sel_prov : 'all';
	
	if ($_SESSION['userdata'][12] == 0){
		$rptType = 'public';
		$lvl_stktype = 0;
	}else if ($_SESSION['userdata'][12] == 1){
		$rptType = 'private';
		$lvl_stktype = 1;
	}
	$sel_stk = $_SESSION['userdata'][7];
	$in_stk = $sel_stk;
	$filter = " AND summary_district.stakeholder_id = '".$sel_stk."'";
	$stkFilter = " AND tbl_warehouse.stkid = '".$sel_stk."'";
	
}

// this for rate summary";
if($sel_prov=='all' && $sel_stk=='all')
{
    $in_type =  'N';
    $in_prov = 0;
    $in_stk = 0;
}

if($sel_prov != 'all')
{
    $in_type =  'P';
    $in_prov = $sel_prov;
}
else
    $in_prov = 0;


if($sel_stk != 'all')
{
    $in_type .=  'S';
    $in_stk = $sel_stk;
}
else
    $in_stk = 1;

if($sel_prov!='all' && $sel_stk!='all')
{
    $in_type =  'SP';
    $in_prov = $sel_prov;
    $in_stk = $sel_stk;
}
$in_month =  $sel_month;
$in_year =  $sel_year;
$in_item =  $sel_item;
$sel_stk= $sel_stk;

$_SESSION['PROSTKHOLDER'] = $sel_stk;
?>
<?php include "../../plmis_inc/common/_header.php";?>
<script language="javascript">
    function frmvalidate(){
        if(document.getElementById('item_sel').value==''){
            alert('Please Select Item');
            document.getElementById('item_sel').focus();
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

        if(document.getElementById('stk_sel').value==''){
            alert('Please Select Stakeholder');
            document.getElementById('stk_sel').focus();
            return false;
        }
    }


    function functionCall(month, year, prod, stkID, province){
        window.location = "diststkreport.php?month_sel="+month+"&year_sel="+year+"&prov_sel="+province+"&stkid="+stkID+"&item_sel="+prod;
    }

</script>
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
$reportingDate = $sel_year.'-'.$sel_month.'-01';

$qry = "SELECT
			*
		FROM
			(
				SELECT DISTINCT
					tbl_locations.PkLocID,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					1 = 1
				$provFilter
				$stkFilter
			) A
		LEFT JOIN (
			SELECT
			summary_district.district_id,
			SUM(summary_district.consumption) AS consumption,
			SUM(summary_district.avg_consumption) AS avg_consumption,
			SUM(summary_district.soh_district_lvl) AS SOH,
			(SUM(summary_district.soh_district_lvl) / SUM(summary_district.avg_consumption)) AS MOS
		FROM
			summary_district
		INNER JOIN itminfo_tab ON summary_district.item_id = itminfo_tab.itmrec_id
		INNER JOIN tbl_locations ON summary_district.province_id = tbl_locations.PkLocID
		INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
		WHERE
			summary_district.item_id = '$sel_item'
		AND summary_district.reporting_date = '$reportingDate'
		$provFilter1
		$filter
		GROUP BY
			summary_district.district_id
		) B ON A.PkLocID = B.district_id
		ORDER BY 
			A.LocName";
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .= "<rows>";
while ( $row = mysql_fetch_array($qryRes) )
{
	$tempVar = "";
	$tempVar .= "\"$sel_month\",";
	$tempVar .= "\"$sel_year\",";
	$tempVar .= "\"$sel_item\",";
	$tempVar .= "\"$sel_stk\",";
	$tempVar .= "\"$row[PkLocID]\"";
	
	$xmlstore .= "<row>";
	$xmlstore .= "<cell>".$row['PkLocID']."</cell>";
	$xmlstore .= "<cell><![CDATA[".$row['LocName']."]]></cell>";
	$xmlstore .= "<cell>".((!is_null($row['consumption'])) ? number_format($row['consumption']) : 'UNK')."</cell>";
	$xmlstore .= "<cell>".((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK')."</cell>";
	$xmlstore .= "<cell>".((!is_null($row['SOH'])) ? number_format($row['SOH']) : 'UNK')."</cell>";
	$xmlstore .= "<cell>".((!is_null($row['MOS'])) ? number_format($row['MOS'], 1) : 'UNK')."</cell>";
	
	$rs_mos = mysql_query("SELECT getMosColor('".$row['MOS']."', '".$sel_item."', '".$sel_stk."', 3)");
	$bgcolor = mysql_result($rs_mos, 0, 0);
	
	$xmlstore .= "<cell><![CDATA[<div style=\"width:10px; height:12px; background-color:$bgcolor;\"></div>]]></cell>";
	$xmlstore .= "</row>";
}
$xmlstore .= "</rows>";

////////////// GET Product Name
$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
$prodName = "\'$proNameQryRes[itm_name]\'";
if ($sel_stk == 'all'){
	$stkName = "\'All\'";		
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
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		//mygrid.setHeader("District,#cspan,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader(",<div style='text-align:center;'><?php echo "District Report for Sector = '".ucwords($rptType)."' Stakeholder(s) = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("District Id, District, Consumption, AMC, Stock On Hand, <div text-align:center;>Month of Stock</div>,#cspan");
		mygrid.setInitWidths("50,*,160,160,160,60,40");
		mygrid.setColAlign("left,left,right,right,right,center,center");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setColumnHidden(0,true);
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
	}
	function getStakeholder(val, stk)
	{
		$.ajax({
			url: 'ajax_stk.php',
			data: {type:val, stk: stk},
			type: 'POST',
			success: function(data){
				$('#stk_sel').html(data);
				showProducts('<?php echo $sel_item;?>');
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

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<div class="page-container">
<?php include "../../plmis_inc/common/_top.php";?>
    <div class="page-content-wrapper">
        <div class="page-content"> 
            <div class="row">
                <div class="col-md-12"> 
                    <!-------- -------->
                    
                    <table width="100%">
                        <tr>
                            <td><?php $in_stk = 1;include(PLMIS_INC."report/reportheader.php");    //Include report header file ?></td>
                        </tr>
						<?php
                        if ($num > 0)
                        {?>
                        <tr>
                            <td align="right" style="padding-right:5px;">
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.setColumnHidden(0,false); mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php'); mygrid.setColumnHidden(0,true);" />
                            </td>
                        </tr>
                        <tr>
                            <td><div id="mygrid_container" style="width:100%; height:320px;"></div></td>
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
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>