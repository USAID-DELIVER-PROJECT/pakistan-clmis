<?php
	ob_start();
	
    $report_id = "SFIELDREPORT";
    $report_title = "Field Report";
    $actionpage = "";
    $parameters = "TIP";
	$showexport = "no";
    //back page setting
    $backparameters = "STIP";
    $backpage = "diststkreport.php";

    //forward page setting
    $forwardparameters = "";
    $forwardpage = "";
	
    include("../../html/adminhtml.inc.php");
	Login();
	//include("../../plmis_inc/common/CnnDb.php");	//Include Database Connection File
	//include("../../plmis_inc/common/FunctionLib.php");	//Include Global Function File
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
	
	if(isset($_GET['month_sel']) && !isset($_POST['go'])){
        
        $sel_month = $_GET['month_sel'];
        $sel_year = $_GET['year_sel'];
        $sel_item = $_GET['item_sel'];
        if(isset($_GET['stkid']))
			$sel_stk = $_GET['stkid'];
		else
			$sel_stk = 1;
        $sel_prov = $_GET['prov_sel'];
		
		$proStkID = $_GET['stkid'];
		
		if ($proStkID == "all"){
			$where = "";	
		}else{
			$where = "AND tbl_warehouse.stkid = '".$proStkID."' ";
		}
	
	}
	elseif(isset($_POST['go'])){
		
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
			$sel_item = $_POST['prod_sel'];
			
		if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
			$sel_prov = $_POST['prov_sel'];
			
		if(isset($_POST['proWh']) && !empty($_POST['proWh']))
			$proStkID = $_POST['proWh'];
		
		if ($proStkID == "all"){
			$where = "";	
		}else {
			$where = "AND tbl_warehouse.stkid = '".$proStkID."' ";			
		}
		
			
		
	} else {
		$querymaxyear = "SELECT MAX(report_year) as report_year FROM tbl_wh_data WHERE wh_id IN (SELECT wh_id FROM tbl_warehouse )";
		$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
		$rowmaxyear = mysql_fetch_array($rsmaxyear);
		
		$querymaxmonth = "SELECT MAX(report_month) as report_month FROM tbl_wh_data WHERE wh_id IN (SELECT wh_id FROM tbl_warehouse ) AND report_year = ".$rowmaxyear['report_year']." AND (wh_obl_a <>0 OR wh_obl_c <>0 OR wh_cbl_a <>0 OR wh_cbl_c <>0)";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		
		$sel_month = $rowmaxmonth['report_month']-1;
		$sel_year = $rowmaxyear['report_year'];
		//$sel_month = 1;
		//$sel_year = 2010;	
		$sel_item = "IT-001";
		$sel_prov = "1";
		$sel_stk = 1;
		$proStkID = 'all';
		$where = "";
	}
	
if($sel_prov=='all'){
$in_type =  'N';
$in_prov = 0;
} else {
$in_type =  'P';
$in_prov = $sel_prov;
}
$in_id =  0;
$in_month =  $sel_month;
$in_year =  2010;
$in_item =  'IT-001';
$in_WF =  'F';
$in_stk = 0;

$_SESSION['PROSTKHOLDER'] = $proStkID; 

	 //////////// GET FILE NAME FROM THE URL
 	$arr = explode("?", basename($_SERVER['REQUEST_URI']));
	$basename = $arr[0];
	$filePath = "plmis_src/reports/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];

?>
<?php startHtml($system_title." - Field Reports");?>
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
function showstkHolders(){
	var province = $("#prov_sel").val();
	$('#stkheading').show("slow");
	var html = $.ajax({
                    
                    beforeSend: function(){
                        // Handle the beforeSend event
                        //alert('before Send!');
                    },
                    url: "showrelatedstkholder.php",
                    data: "province="+province,             
                    async: false,
                    complete: function(){
                    }
                }).responseText;
                
               $('#whID').html(html);
}
</script>
<?php
											
	if($sel_prov=='all')
		$querydist = "SELECT tbl_locations.PkLocID AS whrec_id, tbl_locations.LocName AS wh_name, tbl_locations.ParentID as province
FROM tbl_locations WHERE tbl_locations.LocLvl = 3 ORDER BY province,wh_name";
	else
		$querydist = "SELECT tbl_locations.PkLocID AS whrec_id, tbl_locations.LocName AS wh_name, tbl_locations.ParentID as province
FROM tbl_locations WHERE tbl_locations.LocLvl = 3 AND tbl_locations.ParentID ='$sel_prov' ORDER BY wh_name ASC";

print $querydist;

		$rsdist = mysql_query($querydist) or die(mysql_error());
		
		$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstore .="<rows>\n";
		$counter= 1;
	while($rowdist = mysql_fetch_array($rsdist)) {
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell colspan=\"6\"><![CDATA[$rowdist[wh_name]]]></cell>\n";
		$xmlstore .="\t</row>\n";
		
		print $queryStk = "SELECT DISTINCT(stkname),stakeholder.stkid
						FROM stakeholder
						LEFT JOIN tbl_warehouse ON tbl_warehouse.stkid = stakeholder.stkid";
					//	WHERE tbl_warehouse.prov_id=$sel_prov $where ORDER BY stakeholder.stkid ASC";
		 $rsStk3 = mysql_query($queryStk) or die(mysql_error());
		 $rowcolor = '#dff2a9';
		 while($rowStk3 = mysql_fetch_array($rsStk3)) {
			  $counter++;
			 if($rowcolor == '#dff2a9')
				$rowcolor = '#FFFFFF';
			else
				$rowcolor = '#dff2a9';
	 
			 $queryvals2 =  "SELECT REPgetData('CABMY','R','FSPD','$sel_month','$sel_year','".$sel_item."',".$rowStk3['stkid'].",'$sel_prov','".$rowdist['whrec_id']."') AS Value FROM DUAL";
			 $rsvals2 = mysql_query($queryvals2) or die(mysql_error());                
			 $rowvals2 = mysql_fetch_array($rsvals2);             
			 $tmp = explode('*',$rowvals2['Value']);
	//<!-- begin data rending -->
			 $sel_item = $sel_item;
			 $sel_stk = $rowStk3['stkid'];
			 $sel_lvl = 4; 
			 
			$xmlstore .="\t<row id=\"$counter\">\n";
			$xmlstore .="\t\t<cell></cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$rowStk3[stkname]]]></cell>\n";
																	 
	include("incl_data_render.php");
		} 
		$counter++;
	}
       $xmlstore .="</rows>\n";
	   
	   	   
////////////// GET Product Name
	$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
	$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name
	$provinceQryRes = mysql_fetch_array(mysql_query("SELECT prov_title FROM province WHERE prov_id = '".$sel_prov."' "));
	$provinceName = "\'$provinceQryRes[prov_title]\'";
	
?>
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		//mygrid.setHeader("District,#cspan,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Field Report For Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='District Name'>Districts</span>,#cspan,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan");
		mygrid.setInitWidths("*,160,160,160,160,40,40");
		mygrid.setColAlign("left,left,right,right,right,center,center");
		//mygrid.setColSorting(",,str,str,str,str,str");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/field_report.xml");
	}
 
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();showstkHolders();">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align="" style="min-height:679px;"><br>
            
            <?php showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
      
            <table width="99%">
                <tr>
                    <td colspan="2">
                        <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                    </td>
                </tr>   	
                <tr>
                    <td class="sb1NormalFont">
                      Choose skin to apply: 
                      <select onChange="mygrid.setSkin(this.value)">
                        <option value="light" selected>Light
                        <option value="sbdark">SB Dark
                        <option value="gray">Gray
                        <option value="clear">Clear
                        <option value="modern">Modern
                        <option value="dhx_skyblue">Skyblue
                    </select>
                    </td>
                    <td align="right">
                        <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                        <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                   </td>
                </tr>
            </table>
            
                 
            <table width="99%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="mygrid_container" style="width:100%; height:320px; background-color:white;overflow:hidden"></div>
                    </td>
                </tr>
            </table>
                 
            </div>
        </div> 
     
		<?php
        //XML write function
        function writeXML($xmlfile, $xmlData)
        {
			$xmlfile_path= REPORT_XML_PATH."/".$xmlfile;
			$handle = fopen($xmlfile_path, 'w');
			fwrite($handle, $xmlData);
        }
        
        writeXML('field_report.xml', $xmlstore);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>