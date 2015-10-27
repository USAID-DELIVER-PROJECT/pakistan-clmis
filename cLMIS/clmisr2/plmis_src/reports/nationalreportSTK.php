<?php
ob_start();
/***********************************************************************************************************
Developed by  Munir Ahmed
Email Id:    mnuniryousafzai@gmail.com
This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP" by stakeholder. For viewing the details against a stakeholder, province or    district. The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the    product details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
Login();
// reports settings are used to display header and footer text, execute action page, and set parameter forms
$report_id = "SNASUMSTK";
$report_title = "National Summary Report by Stakeholder for ";
$actionpage = "nationalreportSTK.php";
$parameters = "TI";
$parameter_width = "60%";
//back page setting
$backparameters = "T";
$backpage = "nationalreport.php";
//forward page setting
$forwardparameters = "";
$forwardpage = "";

include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File

//'user may have run '
if(isset($_GET['month_sel']) && !isset($_POST['go'])){
    //	print_r($_GET);
    $sel_month = $_GET['month_sel'];
    $sel_year = $_GET['year_sel'];
    $sel_item = $_GET['item_sel'];

}else if(isset($_POST['go'])){

    if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
        $sel_month = $_POST['month_sel'];

    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];

    if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
        $sel_item = $_POST['prod_sel'];

} elseif (isset($_GET['prod_sel']) && !empty($_GET['prod_sel'])) {

    $sel_month = $_GET['month_sel'];
    $sel_year = $_GET['year_sel'];
    $sel_item = $_GET['item_sel'];
    $sel_prov = $_GET['prov_sel'];
    $sel_stk = $_GET['stkid'];
    $sel_item = $_POST['prod_sel'];

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
	 $sel_month = date('m', strtotime($date));
	$sel_year = date('Y', strtotime($date));
	
    $sel_item = "IT-001";
}

$in_type =  'N';
$in_month =  $sel_month;
$in_year =  $sel_year;
$in_item =  $sel_item;
$in_stk = 0 ;
$in_prov = 0;
$in_dist = 0;

//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/reports/".$basename;
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

	}
</script>
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
<style>
.objbox{overflow-x:hidden !important;}
</style>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>


<script type="text/javascript">
	function functionCall(month, year, prod, stkID){
		window.location = "provincialreport.php?month_sel="+month+"&year_sel="+year+"&stkid="+stkID+"&item_sel="+prod;
	}
	function functionCallPrivate(month, year, prod, stkID){
		var url;
		var title = "Pakistan Logisticts Management Information System - Private Sector Report";
		var h=150;
		var w=650;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h);
		url = "nationalreportSTKPrivate.php?month_sel="+month+"&year_sel="+year+"&groupid="+stkID+"&item_sel="+prod;
		window.open(url, title, 'toolbar=no, location=no, directories=no, statusbar=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		//alert("Private.");
	}
</script>

<!--- BEGIN  MAIN CONTENT AREA //--->
<?php
$reportingDate = $sel_year.'-'.$sel_month.'-01';

$qry = "SELECT
			A.stkid,
			A.stkname,
			B.consumption,
			B.avg_consumption
		FROM
			(
				SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stk_type_id
				FROM
					stakeholder
				WHERE
					ParentID IS NULL
				AND stakeholder.stk_type_id IN (0, 1)
				ORDER BY
					stkorder
			) A
		LEFT JOIN (
			SELECT
				SUM(summary_national.consumption) AS consumption,
				SUM(summary_national.avg_consumption) AS avg_consumption,
				summary_national.stakeholder_id
			FROM
				summary_national
			INNER JOIN itminfo_tab ON summary_national.item_id = itminfo_tab.itmrec_id
			RIGHT JOIN stakeholder ON summary_national.stakeholder_id = stakeholder.stkid
			WHERE
				summary_national.reporting_date = '$reportingDate'
			AND itminfo_tab.itmrec_id = '$sel_item'
			GROUP BY
				summary_national.stakeholder_id
		) B ON A.stkid = B.stakeholder_id";
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .= "<rows>";
while ( $row = mysql_fetch_array($qryRes) )
{
	$xmlstore .= "<row>";
	$xmlstore .= "<cell>".$row['stkname']."</cell>";
	$xmlstore .= "<cell>".((!is_null($row['consumption'])) ? number_format($row['consumption']) : 'UNK')."</cell>";
	$xmlstore .= "<cell>".((!is_null($row['avg_consumption'])) ? number_format($row['avg_consumption']) : 'UNK')."</cell>";
	$xmlstore .= "</row>";
}
$xmlstore .= "</rows>";

////////////// GET Product Name
$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
$prodName = "\'$proNameQryRes[itm_name]\'";

?>

<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center;'><?php echo "National Summary Report by Stakeholder for $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan");
        mygrid.attachHeader("Stakeholder, Consumption, AMC");
        mygrid.setInitWidths("*,160,160");
        mygrid.setColAlign("left,right,right");
        mygrid.setColTypes("ro,ro,ro");
        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		$('body').append('<textarea id="xml_string" style="display:none;"><?php echo $xmlstore;?></textarea>');
		mygrid.loadXMLString(document.getElementById('xml_string').value);
		//mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }
</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->

<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN SIDEBAR -->
<?php include "../../plmis_inc/common/_top.php";?>

<!-- BEGIN CONTENT -->
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
                            <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                            <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
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
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>