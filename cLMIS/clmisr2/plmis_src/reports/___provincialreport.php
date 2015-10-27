<?php
	ob_start();
	include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
	Login();
	
	//////////// GET FILE NAME FROM THE URL
	$arr = explode("?", basename($_SERVER['REQUEST_URI']));
	$basename = $arr[0];
	$filePath = "plmis_src/reports/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
	
	/***********************************************************************************************************
	Developed by  Munir Ahmed
	Email Id:    mnuniryousafzai@gmail.com
	This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP" against each province. For viewing the details against a province or    district. The details are shown in a hirerchy i-e first of all it shows the product details against province, there from you select the province and then the    product details are shown against each district in that province.
	/***********************************************************************************************************/
	
    $report_id = "SPROVINCEREPORT";
    $report_title = "Province/Region Report";    
    $actionpage = "provincialreport.php";
    $parameters = "TS01I";
    $parameter_width = "100%";
	$sel_stk = '1';


	//echo '<pre>';
	//print_r($_POST);
	//exit;
    //back page setting
    $backparameters = "TI";
    $backpage = "nationalreportSTK.php";

    //forward page setting
    $forwardparameters = "";
    $forwardpage = ""; 
	
	
   //'user may have run '
    
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
	if(isset($_GET['month_sel']) && !isset($_POST['go'])){
		
		$sel_month = $_GET['month_sel'];
		$sel_year = $_GET['year_sel'];
		$sel_item = $_GET['item_sel'];
		$sel_stk = $_GET['stkid'];
		$Stkid = " AND tbl_warehouse.Stkid = '".$_GET['stkid']."'";
		
	}elseif(isset($_POST['go'])){
		
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
			$sel_item = $_POST['prod_sel'];    
		
		if(!empty($_POST['stk_sel']) && $_POST['stk_sel']!='all')
			$Stkid = " AND tbl_warehouse.Stkid = '".$_POST['stk_sel']."'";  

		if($_POST['stk_sel']=='all')
			//$sel_stk = 1;  
			$sel_stk = 'all';
		else
			$sel_stk = $_POST['stk_sel'];
			
		if($_POST['sector']=='All')
			//$sel_stk = 1;  
			$rptType = 'All';
		else
			$rptType = $_POST['sector'];  
		
		//echo $_POST['stkid'];   
 		
	} elseif (isset($_GET['item_sel']) && !empty($_GET['item_sel'])) {

    /* if(isset($_GET['prod_sel']) && !empty($_GET['prod_sel']))
            $sel_item = $_POST['prod_sel'];
			
       if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
            $sel_month = $_POST['month_sel'];
			
       if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
            $sel_year = $_POST['year_sel'];        
	   
	   if(!empty($_POST['stkid']))
			$Stkid = " AND tbl_warehouse.Stkid = '".$_POST['stkid']."'";
		
		$sel_stk = $_POST['stkid']; */
		$sel_month = $_GET['month_sel'];
        $sel_year = $_GET['year_sel'];
        $sel_item = $_GET['item_sel'];
        $sel_prov = $_GET['prov_sel'];  
		$sel_stk = $_GET['stkid'];
		$sel_item = $_POST['prod_sel'];
	   
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
        $sel_item = "IT-001";					
		$Stkid = "";
		$sel_stk = '1';
		$rptType = 'all';
	}

	if($sel_stk==0){
		$in_type   = 'N';
		$in_stk    = 0;
	} else {
		$in_type   = 'S';
		$in_id     = $sel_stk;
		$in_stk    = $sel_stk;
	}
	$in_month  = $sel_month;
	$in_year   = $sel_year;
	$in_item   = $sel_item;
	$in_prov   = 0;
	$in_dist = 0;

?>

<?php 
startHtml($system_title." - Provinicial Reports");
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

	$rowcolor = '#dff2a9';
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$query = safe_query("SELECT tbl_locations.PkLocID as prov_id, tbl_locations.LocName as prov_title FROM tbl_locations where LocLvl=2 and parentid is not null");
	$counter = 1; 
	if (isset($_GET['stkid']) && !empty($_GET['stkid'])){
	$provinceQry = mysql_query("SELECT *  FROM tbl_locations
Inner Join tbl_warehouse ON tbl_warehouse.prov_id = tbl_locations.PkLocID
WHERE tbl_warehouse.stkid = '".$_GET['stkid']."' GROUP BY tbl_warehouse.prov_id");
	/*$numofRec = mysql_num_rows($provinceQry);
	if ($numofRec == 1){
			while($rs = mysql_fetch_array($query)){
				$qryResult = mysql_fetch_array($provinceQry);
				if($_GET['stkid'] == $qryResult['stkid']){
					$xmlstore .="\t<row id=\"$counter\">\n";
										
					if($sel_stk==0)
						$queryvals = "SELECT REPgetData('CABMY','R','TP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
					else									
						$queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";								
					$rsvals = mysql_query($queryvals) or die(mysql_error());				
					while($rowvals = mysql_fetch_array($rsvals)) {
						$tmp = explode('*',$rowvals['Value']);    
				//<!-- begin data rending -->
						 $sel_item = $sel_item;
						 $sel_stk = $sel_stk;
						 $sel_lvl = 2; 
						 //$xmlstore .="\t\t<cell>".$rs['prov_title']."</cell>\n"; 
						
							
								$tempVar = "";
								$tempVar .= "\"$sel_month\",";
								$tempVar .= "\"$sel_year\",";
								$tempVar .= "\"$sel_item\",";
								$tempVar .= "\"$sel_stk\",";
								$tempVar .= "\"$qryResult[prov_id]\"";
								 
								$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$qryResult[prov_title]</a>]]>^_self</cell>\n";
								
								 include("incl_data_render.php");
					} 
					$counter++;
			}
		}
	}
	else if($numofRec > 1){*/
		while($rs = mysql_fetch_array($query)){
			$xmlstore .="\t<row id=\"$counter\">\n";
			
			/*if($sel_stk==0)
				$queryvals = "SELECT REPgetData('CABMY','R','TP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			else	*/								
				$queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";					
					//print $queryvals;	
			$rsvals = mysql_query($queryvals) or die(mysql_error());				
			while($rowvals = mysql_fetch_array($rsvals)) {
				$tmp = explode('*',$rowvals['Value']);    
		//<!-- begin data rending -->
				 $sel_item = $sel_item;
				 $sel_stk = $sel_stk;
				 $sel_lvl = 2; 
				 //$xmlstore .="\t\t<cell>".$rs['prov_title']."</cell>\n"; 
				 
				$tempVar = "";
				$tempVar .= "\"$sel_month\",";
				$tempVar .= "\"$sel_year\",";
				$tempVar .= "\"$sel_item\",";
				$tempVar .= "\"$sel_stk\",";
				$tempVar .= "\"$rs[prov_id]\"";
				 
				
				 $xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rs[prov_title]</a>]]>^_self</cell>\n";
				 
				 include("incl_data_render.php");
			} 
			$counter++;
		}	
	//}
	}else if (!isset($_GET['stkid'])){
		while($rs = mysql_fetch_array($query)){
			$xmlstore .="\t<row id=\"$counter\">\n";

			if($sel_stk == 'all' && $rptType == 'all')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','TXP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			else if($sel_stk == 'all' && $rptType == 'public')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','TP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			else if($sel_stk == 'all' && $rptType == 'private')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','XP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			else if($sel_stk != 'all' && $rptType == 'public')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			else if($sel_stk != 'all' && $rptType == 'private')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','XSP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			else if($sel_stk != 'all' && $rptType == 'all')
			{
				$queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$sel_item."','$sel_stk','".$rs['prov_id']."',0) AS Value FROM DUAL";
			}
			
			//print $queryvals.'<br>';
			
			$rsvals = mysql_query($queryvals) or die(mysql_error());				
			while($rowvals = mysql_fetch_array($rsvals)) {
				$tmp = explode('*',$rowvals['Value']);    
		//<!-- begin data rending -->
				 $sel_item = $sel_item;
				 $sel_stk = $sel_stk;
				 $sel_lvl = 2; 
				 //$xmlstore .="\t\t<cell>".$rs['prov_title']."</cell>\n"; 
				 
				$tempVar = "";
				$tempVar .= "\"$sel_month\",";
				$tempVar .= "\"$sel_year\",";
				$tempVar .= "\"$sel_item\",";
				$tempVar .= "\"$sel_stk\",";
				$tempVar .= "\"$rs[prov_id]\"";
				 
				
				 $xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rs[prov_title]</a>]]>^_self</cell>\n";
				 
				 include("incl_data_render.php");
			} 
			$counter++;
		}		
	}
	$xmlstore .="</rows>\n";
  
  ////////////// GET Product Name
	$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
	$prodName = "\'$proNameQryRes[itm_name]\'";
  ////////////// GET Stakeholders
  	
	if ($sel_stk == 'all'){
		$stkName = "\'All\'";		
	}else{
		$stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
		$stkName = "\'$stakeNameQryRes[stkname]\'";
	}
	 
  
 ?>
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		//mygrid.setHeader("Province,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Province/Region Report For Sector = '".ucwords($rptType)."' Stakeholder(s) = $stkName And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Province/Region Name'>Province/Region</span>,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan");
		mygrid.setInitWidths("*,160,160,160,40,40");
		mygrid.setColAlign("left,right,right,right,center,center");
		//mygrid.setColSorting("str,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/provincial_report.xml");
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
  <body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
    
        <div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
        
        <div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align=""><br>
            
             <?php  showBreadCrumb();?><div style="float:right; padding-right:2px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
    
      
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
        
        writeXML('provincial_report.xml', $xmlstore);
        ?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
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
</body>
</html>