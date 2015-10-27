<?php
	ob_start();
	/***********************************************************************************************************
	Developed by  Munir Ahmed
	Email Id:    mnuniryousafzai@gmail.com
	This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district.
    The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
	/***********************************************************************************************************/
    $report_id = "SNASUM";
    $report_title = "National Report for";
    $actionpage = "";
    $parameters = "T";
    $parameter_width = "40%";
	

 	include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
	Login();
	

	
	$querymaxyear = "SELECT MAX(report_year) as report_year FROM tbl_wh_data";
	$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
	$rowmaxyear = mysql_fetch_array($rsmaxyear);
	
	$querymaxmonth = "SELECT MAX(report_month) as report_month FROM tbl_wh_data WHERE report_year = ".$rowmaxyear['report_year']." AND (wh_obl_a <>0 OR wh_obl_c <>0 OR wh_cbl_a <>0 OR wh_cbl_c <>0)";
	$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
	$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
	
	$lastMonthDB = $rowmaxmonth['report_month'];
	

	if(isset($_GET['month_sel']) && !isset($_POST['go'])){
		
		$sel_year = $_GET['year_sel'];
		$sel_month = $_GET['month_sel'];

		
	}
	else if(isset($_POST['go'])){
          
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		
		$sector = $_POST['sector'];
		if ($sector=='Public' || $sector=='public')
			$lvl_stktype=0;
		else
			$lvl_stktype=1;
		
	
	} else {
		
		$querymaxyear = "SELECT MAX(report_year) as report_year FROM tbl_wh_data";
		$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
		$rowmaxyear = mysql_fetch_array($rsmaxyear);
		
		$querymaxmonth = "SELECT MAX(report_month) as report_month FROM tbl_wh_data WHERE report_year = ".$rowmaxyear['report_year']." AND (wh_obl_a <>0 OR wh_obl_c <>0 OR wh_cbl_a <>0 OR wh_cbl_c <>0)";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		
		$sel_month = $rowmaxmonth['report_month'];
		$sel_year = $rowmaxyear['report_year']; 
		//$sel_month = 1;
		//$sel_year = 2010;
		$sector = 'public';
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
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
	
			
?>
<?php startHtml($system_title." - National Summary Reports");?>
<!-- <script type="text/javascript" src="../../plmis_js/rhi.js"></script>-->
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
	 
			$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			$xmlstore .="<rows>\n";
			$queryPro = safe_query("SELECT itmrec_id,itm_name FROM `itminfo_tab` WHERE `itm_status`='Current' ORDER BY frmindex");
			$counter = 1;
			while($rsPro = mysql_fetch_array($queryPro)){
					
				$xmlstore .="\t<row id=\"$counter\">\n";
				if ($sector == 'public')
				{
					$queryvals =  "SELECT REPgetData('CABMY','N','T','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";	
				}
				if ($sector == 'private')
				{
					$queryvals = "SELECT REPgetData('CABMY','S','X','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";	
				}
				
				$rsvals = mysql_query($queryvals) or die(mysql_error());				
				while($rowvals = mysql_fetch_array($rsvals)) {
					 
					 $monthNum = "\"$sel_month\"";
					 $yearNum = "\"$sel_year\"";
					 $productName = "\"$rsPro[itmrec_id]\"";
					 
					 $tmp = explode('*',$rowvals['Value']);    
	//<!-- begin data rending -->
					 $sel_item = $rsPro['itmrec_id'];
					 $sel_stk = 0;
					 $sel_lvl = 1;
					
					 //$xmlstore .="\t\t<cell>".$rsPro['itm_name']."</cell>\n";
					 
					 $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
												   //^javascript:functionCall($monthNum, $yearNum, $productName);^_self
					 include("incl_data_render.php");
			   
	?>
	<!--End of data rending -->
		   <?php
				} 
					$counter++;
			}
				$xmlstore .="</rows>\n";
			?>
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "National Report - ".ucwords($sector)." Sector".' ('. date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?> </div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		
		mygrid.attachHeader("<span title='Product Name'>Product</span>,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span  style='text-align: center;' title='Month of Scale'>MOS</span>,#cspan,<span style='text-align: center;' title='CYP'>CYP</span>");
		mygrid.setInitWidths("*,160,160,160,40,40,80");
		mygrid.setColAlign("left,right,right,right,center,right,right");
		//mygrid.setColSorting("str,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/national_report.xml");
	}
	
</script>
 </head>
 	
  <body text="#000000" bgColor="#FFFFFF"  style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content" align=""><br>
		<?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
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
					<img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
		        	<img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" title="Export to Excel" />
		       </td>
		    </tr>
		</table>

     
		<table width="99%" cellpadding="0" cellspacing="0">
		    <tr>
		        <td>
		            <div id="mygrid_container" style="width:100%; height:320px; background-color:white;"></div>
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
		//print "wasif".$xmlfile_path;
	}


	writeXML('national_report.xml', $xmlstore);
	?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>
