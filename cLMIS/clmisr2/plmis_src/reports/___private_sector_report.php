<?php
	//ob_start();
	//session_start();
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
	

if(isset($_POST['go'])){
	/////// Start XML Genaration
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
		
		if ($_POST['stk_sel'] == 'all'){
			$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id WHERE stkid IN (SELECT stkid FROM stakeholder WHERE stk_type_id = 1) GROUP BY itmrec_id ORDER BY frmindex");
			$where = " AND stakeholder.stk_type_id = 1";
		}else {
			$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id WHERE stkid = '".$_POST['stk_sel']."' GROUP BY itmrec_id ORDER BY frmindex");
			$where = " AND stakeholder.MainStakeholder = '".$_POST['stk_sel']."'";
		}
		
		$counter = 1;
		while ($row = mysql_fetch_array($proNamesQry)){
			
			$xmlstore .="\t<row id=\"$counter\">\n";
			$itemName = $row['itmrec_id'];
			$xmlstore .="\t\t<cell>$row[itm_name]</cell>\n";
			
			if ($_POST['repIndicators'] == 1){
				for ($i=1; $i<=12; $i++){
					$myqry="SELECT sum(tbl_wh_data.wh_issue_up) AS total FROM 
							tbl_warehouse 
							inner JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
							INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
							WHERE stakeholder.lvl=4 and tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' $where AND tbl_wh_data.report_month = '".$i."' GROUP BY tbl_wh_data.report_month ";
					//print $myqry;
					$getTotal = mysql_query($myqry);
					$row1 = mysql_fetch_array($getTotal);
					$monthSum = number_format($row1['total']);
					$xmlstore .="\t\t<cell>$monthSum</cell>\n";
				}

			}else if ($_POST['repIndicators'] == 2){
				for ($i=1; $i<=12; $i++){
					$getTotal = mysql_query("SELECT sum(tbl_wh_data.wh_cbl_a) as total FROM tbl_warehouse JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' $where AND tbl_wh_data.report_month = '".$i."' GROUP BY tbl_wh_data.report_month ");
					$row1 = mysql_fetch_array($getTotal);
					$monthSum = number_format($row1['total']);
					$xmlstore .="\t\t<cell>$monthSum</cell>\n";
				}
				
			}else if ($_POST['repIndicators'] == 3){
				for ($i=1; $i<=12; $i++){
					$getTotal = mysql_query("SELECT sum(tbl_wh_data.wh_received) AS total FROM tbl_warehouse JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id WHERE tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_year = '".$_POST['year_sel']."' $where AND tbl_wh_data.report_month = '".$i."' GROUP BY tbl_wh_data.report_month ");
					$row1 = mysql_fetch_array($getTotal);
					$monthSum = number_format($row1['total']);
					$xmlstore .="\t\t<cell>$monthSum</cell>\n";
				}
			}
	
				
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
 		
		////////  Stakeholders for Grid Header
		if ($sel_indicator == 1){
			$ind = "\'Issued\'";
		}else if ($sel_indicator == 2){
			$ind = "\'Stock on Hand\'";
		}else if ($sel_indicator == 3){
			$ind = "\'Received\'";
		} 
		
	} else {
		
		$querymaxyear = "SELECT
							DATE_FORMAT(MAX(tbl_wh_data.RptDate), '%Y') AS report_year
						FROM
							tbl_wh_data
						Inner Join tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
						Inner Join stakeholder ON stakeholder.stkid = tbl_warehouse.stkid
						WHERE stakeholder.stk_type_id = 1";
		$rsmaxyear = mysql_query($querymaxyear) or die(mysql_error());
		$rowmaxyear = mysql_fetch_array($rsmaxyear);
		
		$sel_year = $rowmaxyear['report_year'];
		//$sel_month = 1;
		//$sel_year = 2010;
        $sel_item = "IT-001";					
		$Stkid = "";
		$sel_stk = 'all';
		
		$proNamesQry = mysql_query("SELECT itmrec_id, itm_name FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id WHERE stkid IN (SELECT stkid FROM stakeholder WHERE stk_type_id = 1) GROUP BY itmrec_id ORDER BY frmindex");
		$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstore .="<rows>\n";
		
		$counter = 1;
		while ($row = mysql_fetch_array($proNamesQry)){
			
			$xmlstore .="\t<row id=\"$counter\">\n";
			$itemName = $row['itmrec_id'];
			$xmlstore .="\t\t<cell>$row[itm_name]</cell>\n";
			for ($i=1; $i<=12; $i++){
				$qry="SELECT sum(tbl_wh_data.wh_issue_up) AS total FROM 
							tbl_warehouse 
							inner JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
							INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
							WHERE stakeholder.lvl=4 and tbl_wh_data.item_id = '".$itemName."' AND tbl_wh_data.report_year = '".$sel_year."'  AND stakeholder.stk_type_id = 1 AND tbl_wh_data.report_month = '".$i."' GROUP BY tbl_wh_data.report_month ";
				//exit($qry);
				$getTotal = mysql_query($qry);
				$row1 = mysql_fetch_array($getTotal);
				$monthSum = number_format($row1['total']);
				$xmlstore .="\t\t<cell>$monthSum</cell>\n";
			}
			$xmlstore .="\t</row>\n";
			$counter++;
		}
		
		$xmlstore .="</rows>\n";
		
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
	
////////  Stakeholders for Grid Header
if ($sel_stk == 'all'){
	$stkName = "\'All\'";		
}else{
	$stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
	$stkName = "\'$stakeNameQryRes[stkname]\'";
}

?>
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script type="text/javascript">
	function func(){
		var val = $('#stk_sel').val();
		
		if(val == 2){
			$('#ppiuList').show("slow");
			$('#ppiuList1').show("slow");
		}else {
			$('#ppiuList').hide("slow");
			$('#ppiuList1').hide("slow");
		}
	}
</script>

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
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Private Sector Yearly Report For Stakeholder(s) = $stkName And Indicator = $ind (".$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Product Name'>Product</span>,<span title='January'>Jan</span>,<span title='Febrary'>Feb</span>,<span title='March'>Mar</span>,<span title='April'>Apr</span>,<span title='May'>May</span>,<span title='June'>Jun</span>,<span title='July'>Jul</span>,<span title='August'>Aug</span>,<span title='September'>Sep</span>,<span title='October'>Oct</span>,<span title='November'>Nov</span>,<span title='December'>Dec</span>");
		mygrid.setInitWidths("*,60,60,60,60,60,60,60,60,60,60,60,60");
		mygrid.setColAlign("left,right,right,right,right,right,right,right,right,right,right,right,right");
		//mygrid.setColSorting("str,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/private_sector.xml");
	}
 
</script>
</head>
 
<?php 
startHtml($system_title." - Private Sector Yearly Report");
?>
  <body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();func()">
<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">

    	<div class="wrraper" style="height:auto;">
		<div class="content" align=""><br>
  		
         <?php  showBreadCrumb();?><div style="float:right; padding-right:2px"><?php echo readMeLinks($readMeTitle);?></div><br><br>


<form method="post" action="" id="searchfrm" name="searchfrm">  
    <table width="100%">
        <tr>
            <td colspan="2">
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr height="34">
                            <td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2">Private Sector Yearly  Report For <?php echo $sel_year;?></td>
                        </tr>
                    </tbody>
                </table>
                
                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:-2px">
                    <tbody>
                    <tr><td>&nbsp;</td></tr>
                    <tr bgcolor="#FFFFFF">
                        <td style="padding-right:20px; padding-top: 10px;font-family: Arial, Verdana, Helvetica, sans-serif; 	color: #444444; 	font-size: 12px;" colspan="2">   </td>
                    </tr>
                    <tr>
                        <td bgcolor="#FFFFFF" colspan="2">
                    
                            <table height="28" cellspacing="0" cellpadding="0" border="0" width="70%">
                                <tbody>
                                    <tr bgcolor="#FFFFFF">
                                        <td bgcolor="#FFFFFF" class="sb1NormalFont"><strong>Filter by:</strong></td>
                                        <td bgcolor="#FFFFFF" class="sb1NormalFont">Year:</td>
                                        <td bgcolor="#FFFFFF"> 
                                            <select style="width:60px" class="input_select" id="year_sel" name="year_sel">
												<?php                                   
                                                for ($j = date('Y'); $j >= 2010; $j--) {
													if ($sel_year == $j)
														$sel = "selected='selected'";
													else if ($j == date("Y"))
														$sel = "selected='selected'";
													else
														$sel = "";
													?>
													<option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <!-- Stakeholder -->
                                        <td bgcolor="#FFFFFF" class="sb1NormalFont">Stakeholder:</td>
                                        <td bgcolor="#FFFFFF">
                                        <?php 
											/// Get private Sectors Stakeholders
											$pvtQry = mysql_query("SELECT stkid, stkname FROM stakeholder WHERE stk_type_id = 1 and parentid is null GROUP BY stkid");
										?>
                                            <select class="input_select" id="stk_sel" name="stk_sel" style="width:120px">
                                            	 <option value="all">All</option>
											<?php while($pvtRow = mysql_fetch_array($pvtQry)){?>
                                            		<option value="<?php echo $pvtRow['stkid'];?>" <?php if ($pvtRow['stkid'] == $_POST['stk_sel']){echo "selected=selected";}?>><?php echo $pvtRow['stkname'];?></option>
                                            <?php }?>
                                            </select>
                                        </td>
                                        <td bgcolor="#FFFFFF" class="sb1NormalFont">Indicator:</td>
                                        <td bgcolor="#FFFFFF">
                                            <select id="repIndicators" name="repIndicators" class="input_select">
                                                <option value="1"<?php if ($_POST['repIndicators'] == 1){echo "selected=selected";}?>>Issued</option>
                                                <option value="2"<?php if ($_POST['repIndicators'] == 2){echo "selected=selected";}?>>Stock on Hand</option>
                                                <option value="3"<?php if ($_POST['repIndicators'] == 3){echo "selected=selected";}?>>Received</option>
                                            </select>	
                                        </td>                                
                                        <td bgcolor="#FFFFFF"><input type="submit" class="input_button" value="GO" id="go" name="go"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#FFFFFF" align="right" class="sb1NormalFontArial">&nbsp;</td>
                    </tr>
                    <tr><td height="8"></td></tr>
                    </tbody>
                </table> 
            </td>
        </tr>   	
        <tr>
            <td>
              <!--Choose skin to apply: 
              <select onChange="mygrid.setSkin(this.value)">
                <option value="light" selected>Light
                <option value="sbdark">SB Dark
                <option value="gray">Gray
                <option value="clear">Clear
                <option value="modern">Modern
                <option value="dhx_skyblue">Skyblue
            </select>-->
            </td>
            <td align="right">
                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
           </td>
        </tr>
    </table>
</form>

     
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div>
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

writeXML('private_sector.xml', $xmlstore);
?>

    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>


<style>
.input_select{
	border:#D1D1D1 1px solid;
	color:#474747;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	height:24px;
	max-width:150px;
}

.input_button{
	border:#D1D1D1 1px solid;
	background-color:#999;
	color:#000;
	height:24px;	
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;

}
</style>