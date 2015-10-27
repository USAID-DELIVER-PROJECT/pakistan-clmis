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
    $report_title = "National Summary Report by Stakeholder For "; 
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
		$querymaxyear = "SELECT MAX(report_year) as report_year FROM tbl_wh_data";
		$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
		$rowmaxyear = mysql_fetch_array($rsmaxyear);
		
		$querymaxmonth = "SELECT MAX(report_month) as report_month FROM tbl_wh_data WHERE report_year = ".$rowmaxyear['report_year']." AND (wh_obl_a <>0 OR wh_obl_c <>0 OR wh_cbl_a <>0 OR wh_cbl_c <>0)";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		
		$sel_month = $rowmaxmonth['report_month'];
		$sel_year = $rowmaxyear['report_year']; 
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
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
    
?>

<?php 
startHtml($system_title." - National Summary Report by Stakeholder");
?>

<!--<script type="text/javascript" src="../../plmis_js/rhi.js"></script>-->
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
    /*jQuery(document).ready(function($) {
      $('a[rel*=facebox]').facebox({
        loading_image : 'loading.gif',
        close_image   : 'closelabel.gif'
      }) 
    })*/
	
	
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

	/*$queryvals = "(SELECT  
					 stakeholder.stkid,
					 stakeholder.stkname,
					 stakeholder.stk_type_id
				FROM 
					stakeholder WHERE ParentID IS NULL AND stk_type_id = 0) UNION (SELECT  0, 'Private Sector', 1 FROM DUAL)";*/
					
	$queryvals = 	"SELECT  
					 stakeholder.stkid,
					 stakeholder.stkname,
					 stakeholder.stk_type_id
				FROM 
					stakeholder WHERE ParentID IS NULL order by stkorder";

//report type
$rtype = 'TS';
$rsvals = mysql_query($queryvals) or die(mysql_error());																	
	
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 1;
	
	while($rowvals = mysql_fetch_array($rsvals)){
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$tempVar = "";
		$tempVar .= "\"$sel_month\",";
		$tempVar .= "\"$sel_year\",";
		$tempVar .= "\"$sel_item\",";
		
?>

	<?php
	 //if(empty($rowvals['stkgrouplabel'])){
	 if($rowvals['stk_type_id'] == 0){
	   $rtype = 'TS';
	   // echo '<a href="provincialreport.php?month_sel='.$sel_month.'&year_sel='.$sel_year.'&stkid='.$rowvals['stkid'].'&item_sel='.$sel_item.'">'.$rowvals['stkname'].'</a>'; 

	 }else {
		$rtype = 'TS';
		//echo '<a href="nationalreportSTKPrivate.php?month_sel='.$sel_month.'&year_sel='.$sel_year.'&groupid='.$rowvals['stkgroupid'].'&item_sel='.$sel_item.'" rel="facebox">Private Sector/NGO</a>';								 
	 }
		 
		 $queryvals2 =  "SELECT REPgetData('CA','S','$rtype','$sel_month','$sel_year','".$sel_item."',".$rowvals['stkid'].",0,0) AS Value FROM DUAL";
		 $rsvals2 = mysql_query($queryvals2) or die(mysql_error());                
		 $rowvals2 = mysql_fetch_array($rsvals2);   
	
		 $tmp = explode('*',$rowvals2['Value']);    
//<!-- begin data rending -->
		 $sel_item = $sel_item;
		 $sel_stk = $rowvals['stkid'];
		 $sel_lvl = 1;
		 
		 if ($rtype == 'TS'){
		 	$tempVar .= "\"$rowvals[stkid]\"";
			$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rowvals[stkname]</a>]]>^_self</cell>\n";
		 }else if($rtype == 'X'){
		 	$tempVar .= "\"$rowvals[stk_type_id]\"";
			$xmlstore .="\t\t<cell><![CDATA[<a rel=facebox href=javascript:functionCallPrivate($tempVar)>Private Sector</a>]]>^_self</cell>\n";
		 }
										  
		 include("incl_data_render.php");   
						  
		 $counter++;
	}
	$xmlstore .="</rows>\n";
	
	
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
		//mygrid.setHeader("Stakeholder,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "National Summary Report by Stakeholder For $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan");
		mygrid.attachHeader("<span title='Stakeholder Name'>Stakeholder</span>,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>");
/*		mygrid.setInitWidths("*,160,160,160,40,40");
		mygrid.setColAlign("left,right,right,right,center,center");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro");
*/
		mygrid.setInitWidths("*,160,160");
		mygrid.setColAlign("left,right,right");
		mygrid.setColTypes("ro,ro,ro");

		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/national_reportSTK.xml");
	}
 
</script>
<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
    	<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
        <div class="wrraper" style="padding-left:5px">
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
        
        writeXML('national_reportSTK.xml', $xmlstore);
        ?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>