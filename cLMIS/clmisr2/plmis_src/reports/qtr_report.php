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
	
    $report_id = "QTRREPORT";
    $report_title = "Quaterly Report";    
    $actionpage = "provincial_warehouse_report.php";
    $parameters = "TS01IP";
    $parameter_width = "95%";
	


	//echo '<pre>';
	//print_r($_POST);
	//exit;
    //back page setting
    /*$backparameters = "TI";
    $backpage = "nationalreportSTK.php";*/

    //forward page setting
    $forwardparameters = "";
    $forwardpage = ""; 
	
	
   //'user may have run '
    
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/

if(isset($_POST['go']))
{
			
		if ($_POST['stk_sel'] == 'all'){
			$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id GROUP BY itmrec_id ORDER BY frmindex");
			$where = "";
		}
		else 
			{
			$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id WHERE stakeholder_item.stkid = '".$_POST['stk_sel']."' ORDER BY frmindex");
			$where = "AND tbl_warehouse.stkid = '".$_POST['stk_sel']."'";
			}
		
		$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstore .="<rows>\n";
		
		$counter = 1;
		while ($row = mysql_fetch_array($proNamesQry)){
			
			$xmlstore .="\t<row id=\"$counter\">\n";
			$itemName = $row['itmrec_id'];
			$xmlstore .="\t\t<cell>$row[itm_name]</cell>\n";
			
			//$provClause="AND tbl_warehouse.prov_id = '".$_POST['prov_sel']."'";
			
			if ( isset($_POST['districts']) && !empty($_POST['districts']) )
			{
				$provClause = " AND tbl_warehouse.dist_id = '".$_POST['districts']."'";
			}
			else 
			{
				$provClause="AND tbl_warehouse.prov_id = '".$_POST['prov_sel']."'";
			}
			
					
					if ($_POST['prov_sel']==10)
					{$provClause="";}
					
					if ($_POST['qtr_sel']==1) 
					{
						$ind=1;
						$m1=1;$m2=3;
						$gtitle="'January'>Jan</span>,<span title='Febrary'>Feb</span>,<span title='March'>Mar</span>,<span title='Total'>Total</span>,<span title='Total'>SoH</span>";
					}
					if ($_POST['qtr_sel']==2) 
					{
						$ind=2;
						$m1=4;$m2=6;
						$gtitle="'April'>Apr</span>,<span title='May'>May</span>,<span title='June'>Jun</span>,<span title='Total'>Total</span>,<span title='Total'>SoH</span>";
					}
					if ($_POST['qtr_sel']==3)
					{					
						$ind=3;$m1=7;$m2=9;
						$gtitle="'July'>Jul</span>,<span title='August'>Aug</span>,<span title='September'>Sep</span>,<span title='Total'>Total</span>,<span title='Total'>SoH</span>";
					}
					if ($_POST['qtr_sel']==4)
					{
						$ind=4;
						$m1=10;$m2=12;
						$gtitle="'October'>Oct</span>,<span title='November'>Nov</span>,<span title='December'>Dec</span>,<span title='Total'>Total</span>,<span title='Total'>SoH</span>";
					}
					
				
					
				$qtrSum=0;
			//if ($_POST['repIndicators'] == 1)
		//	{
				for ($i=$m1; $i<=$m2; $i++)
				{
				
				$newQry="SELECT sum(tbl_wh_data.wh_issue_up)  AS total FROM tbl_warehouse Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_month = '".$i."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' ".$provClause." AND stakeholder.stk_type_id=0 and stakeholder.lvl=4 $where GROUP BY tbl_wh_data.report_month ";
				//print $newQry;
				//exit;
					$getTotal = mysql_query($newQry);
					$row1 = mysql_fetch_array($getTotal);
					$monthSum1 = number_format($row1['total']);
					//$qtrSum=$qtrSum+$monthSum1;
					$xmlstore .="\t\t<cell>$monthSum1</cell>\n";
					$qtrSum += $row1['total'];
				
			}			
			$qtrSum = number_format($qtrSum);
				
		//	}
		/*else if ($_POST['repIndicators'] == 2){
				for ($i=$m1; $i<=$m2; $i++){
								
				*/	
				
				$xmlstore .="\t\t<cell>$qtrSum</cell>\n";
				
				$newQry="SELECT sum(tbl_wh_data.wh_cbl_a)  AS total FROM tbl_warehouse Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid INNER JOIN tbl_locations ON tbl_warehouse.prov_id = tbl_locations.PkLocID WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_month = '".$m2."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' ".$provClause." AND stakeholder.stk_type_id=0 and stakeholder.lvl>=2 $where GROUP BY tbl_wh_data.report_month ";
					
					$getTotal1 = mysql_query($newQry);
					
					$row2 = mysql_fetch_array($getTotal1);
					$monthSum1 = number_format($row2['total']);
					$xmlstore .="\t\t<cell>$monthSum1</cell>\n";
				/*}
				
			}else if ($_POST['repIndicators'] == 3){
				for ($i=$m1; $i<=$m2; $i++){
										
					$newQry="SELECT sum(tbl_wh_data.wh_received)  AS total FROM tbl_warehouse Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_month = '".$i."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' ".$provClause." AND stakeholder.stk_type_id=0 and stakeholder.lvl=4 $where GROUP BY tbl_wh_data.report_month ";
					$getTotal = mysql_query($newQry);
					$row1 = mysql_fetch_array($getTotal);
					$monthSum = number_format($row1['total']);
					$xmlstore .="\t\t<cell>$monthSum</cell>\n";
				}
			}
			*/
			
			/*while ($row1 = mysql_fetch_array($getTotal)){
				$monthSum = number_format($row1['total']);
				$xmlstore .="\t\t<cell>$monthSum</cell>\n";
			}*/
			
			$xmlstore .="\t</row>\n";
			$counter++;
		}
		
		$xmlstore .="</rows>\n";
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
			$sel_stk = $_POST['stk_sel'];
		if(isset($_POST['repIndicators']) && !empty($_POST['repIndicators']))
			$sel_indicator = $_POST['repIndicators'];
		if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
			$sel_prov = $_POST['prov_sel'];
 		
		////////  Stakeholders for Grid Header

		//if ($_POST['qtr_sel'] == 1){
			$ind = $_POST['qtr_sel']; //"\'1\'";
	//	}else if ($sel_indicator == 2){
	//		$ind = "\'Stock on Hand\'";
	//	}else if ($sel_indicator == 3){
	//		$ind = "\'Received\'";
	//	} 
		
} 
else 
{
			
		if(!isset($_POST['qtr_sel']))
		{
			$m1=1;$m2=3;
			$ind=1;
			//$ind=3;$m1=7;$m2=9;
			$gtitle="'January'>Jan</span>,<span title='Febrary'>Feb</span>,<span title='March'>Mar</span>,<span title='Total'>Total</span>,<span title='Total'>SoH</span>";
		}
		$querymaxyear = "SELECT MAX(report_year) as report_year FROM tbl_wh_data";
		$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
		$rowmaxyear = mysql_fetch_array($rsmaxyear);
		
		$querymaxmonth = "SELECT MAX(report_month) as report_month FROM tbl_wh_data WHERE report_year = ".$rowmaxyear['report_year']." AND (wh_obl_a <>0 OR wh_obl_c <>0 OR wh_cbl_a <>0 OR wh_cbl_c <>0)";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		
		$sel_year = $rowmaxyear['report_year'];		
		//$sel_month = 1;
		//$sel_year = 2010;
        $sel_item = "IT-010";					
		$Stkid = "";
		$sel_stk = '1';
		$sel_prov = "1";
		
		
		$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id GROUP BY itmrec_id ORDER BY frmindex");
		$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstore .="<rows>\n";
		
		$counter = 1;
		while ($row = mysql_fetch_array($proNamesQry)){
			
			$xmlstore .="\t<row id=\"$counter\">\n";
			$itemName = $row['itmrec_id'];
			$xmlstore .="\t\t<cell>$row[itm_name]</cell>\n";
			
			$qtrSum=0;
			for ($i=$m1; $i<=$m2; $i++)
			{
				$getTotal = mysql_query("SELECT ifnull(sum(tbl_wh_data.wh_issue_up),0) AS total 
				FROM tbl_warehouse 
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id 
				INNER JOIN tbl_locations ON tbl_warehouse.prov_id = tbl_locations.PkLocID 
				Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid 
				WHERE stakeholder.lvl=4 and tbl_wh_data.item_id = '".$itemName."' 
				AND tbl_wh_data.report_year = '".$sel_year."'  
				AND tbl_wh_data.report_month = '".$i."' 
				AND tbl_locations.PkLocID = '".$sel_prov."' $where GROUP BY tbl_wh_data.report_month ");
				//echo ""."dsfsdfsdf";
				//exit;
				$row1 = mysql_fetch_array($getTotal);
				$monthSum = number_format($row1['total']);
				$xmlstore .="\t\t<cell>$monthSum</cell>\n";
				//echo $monthSum;
				$qtrSum += $row1['total'];
			}			
			$qtrSum = number_format($qtrSum);
			$xmlstore .="\t\t<cell>$qtrSum</cell>\n";
			
			
			$newQry="SELECT sum(tbl_wh_data.wh_cbl_a)  AS total FROM tbl_warehouse Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid INNER JOIN tbl_locations ON tbl_warehouse.prov_id = tbl_locations.PkLocID WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_month = '".$m2."' AND tbl_wh_data.report_year = '".$sel_year."' AND tbl_locations.PkLocID = '".$sel_prov."' AND stakeholder.stk_type_id=0 and stakeholder.lvl>=2 $where GROUP BY tbl_wh_data.report_month ";
					//echo $newQry;
				//exit;
					$getTotal1 = mysql_query($newQry);
					
					$row2 = mysql_fetch_array($getTotal1);
					$monthSum1 = number_format($row2['total']);
					$xmlstore .="\t\t<cell>$monthSum1</cell>\n";
					
			$xmlstore .="\t</row>\n";
			$counter++;
			
			
		}
		
		$xmlstore .="</rows>\n";
		
		//$ind = "\'Consumption\'";
		
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
	$stkName = "\'$stakeNameQryRes[stkname]\'";
}
if ($_POST['prov_sel']=10){
	$provinceName = "\'All\'";		
}
else
{
$provinceQryRes = mysql_fetch_array(mysql_query("SELECT prov_title FROM province WHERE prov_id = '".$sel_prov."' "));
$provinceName = "\'$provinceQryRes[prov_title]\'";
}
?>
<?php startHtml($system_title." - Provincial Warehouse Reports");?>

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
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Quaterly Consumption and Stock Balance Report For Stakeholder(s) = $stkName Province = $provinceName And Qtr = $ind (".$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Product Name'>Product</span>,<span title=<?php echo $gtitle;?>");
		mygrid.setInitWidths("*,80,80,80,80,100");
		mygrid.setColAlign("left,right,right,right,right,right");
		//mygrid.setColSorting("str,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/provincial_wh_report.xml");
	}
 
</script>
<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">

	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">

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
        
        writeXML('provincial_wh_report.xml', $xmlstore);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
	
	
<script>
$(function(){
	$('#prov_sel').change(function(){
		$('#lvl_type option').eq(0).attr('selected', 'selected');
		$('#districts_td_id').hide();
	});
	
	$('#lvl_type').change(function(){
		showDistricts();
	});
	showDistricts();
});

function showDistricts()
{
	var lvlType = $('#lvl_type').val();
	if ( lvlType == '3' ){
		var province = $('#prov_sel').val();
		$.ajax({
			type: "POST",
			url: "../../plmis_inc/report/show_districts.php",
			data: "province_id="+province,
			success: function(data){
				$('#districts_td_id').show();
				$('#districts_td_id').html(data);
			}  
		});
	}
	else {
		$('#districts_td_id').hide();
		$('#districts_td_id').html('');
	}
}
</script>
	
</body>
</html>