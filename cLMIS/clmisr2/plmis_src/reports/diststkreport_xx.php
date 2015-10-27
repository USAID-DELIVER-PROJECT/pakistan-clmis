<?php

	//ob_start();
	/***********************************************************************************************************
	Developed by  Munir Ahmed
	Email Id:    mnuniryousafzai@gmail.com
	This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP" against each district. For viewing the details about a district user need    to click on the district name and a new page with district information will open up.
	/***********************************************************************************************************/
	
	include("../../html/adminhtml.inc.php");
	//Login();
    $report_id = "SDISTRICTREPORT";
    $report_title = "District Report";
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
        	/*echo "m+go<pre>";
		print_r($_GET);
		echo "</pre>";*/
		
        $sel_month = $_GET['month_sel'];
        $sel_year = $_GET['year_sel'];
        $sel_item = $_GET['item_sel'];
        $sel_prov = $_GET['prov_sel'];  
		$proStkID = $_GET['stkid'];
		if ($proStkID == "all")
		{
			$lvl_stktype = '0';
			$where = "";	
		}
		else 
		{
			$where = "AND tbl_warehouse.stkid = '".$proStkID."' ";	
			
			$querystktype = "SELECT stk_type_id FROM stakeholder WHERE stkid = '".$proStkID."' ";
			$rsstktype = mysql_query($querystktype) or die(mysql_error());
			$rowstktype = mysql_fetch_array($rsstktype);
			$lvl_stktype = $rowstktype['stk_type_id'];		
		}
	
	}elseif(isset($_POST['go']))
	{
		/*echo "go<pre>";
		print_r($_REQUEST);
		echo "</pre>";*/
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
			$sel_item = $_POST['prod_sel'];
			
		if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
			$sel_prov = $_POST['prov_sel'];
			
		if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
			$proStkID = $_POST['stk_sel'];
			
		
		
		
		/*if(isset($_POST['sector']) && !empty($_POST['sector']))
		{
					if ($_POST['sector'] == 'public')
					
					if ($_POST['sector'] == 'private')
					$lvl_stktype = '1';
		}*/
		
		if ($proStkID == "all")
		{
			$lvl_stktype = '0';
			$where = "";	
		}
		else 
		{
			$where = "AND tbl_warehouse.stkid = '".$proStkID."' ";	
			
			$querystktype = "SELECT stk_type_id FROM stakeholder WHERE stkid = '".$proStkID."' ";
			$rsstktype = mysql_query($querystktype) or die(mysql_error());
			$rowstktype = mysql_fetch_array($rsstktype);
			$lvl_stktype = $rowstktype['stk_type_id'];		
		}
			
		/*print $proStkID."sdfsdfsd";
		exit;*/
		
	} 
	else 
	{
		$querymaxmonth = "SELECT month(MAX(RptDate)) as report_month , year(MAX(RptDate)) as report_year FROM tbl_wh_data";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		$sel_month = $rowmaxmonth['report_month']-1;
		$sel_year = $rowmaxmonth['report_year'];

		$sel_item = "IT-001";
		$sel_prov = "1";
		$proStkID = '1';
		$where = " and tbl_warehouse.stkid=1 ";
		$lvl_stktype='0';
		//print "else";
		$in_stk = 1;
		
	}
	
  // this for rate summary";
if($sel_prov=='all' && $proStkID=='all')
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


if($proStkID != 'all')
{
	$in_type .=  'S';
	$in_stk = $proStkID;
}
else
	$in_stk = 1;

if($sel_prov!='all' && $proStkID!='all')
{
	$in_type =  'SP';
	$in_prov = $sel_prov; 
	$in_stk = $proStkID; 
}



$in_month =  $sel_month;
$in_year =  $sel_year;
$in_item =  $sel_item;
$sel_stk= $proStkID; 

/*$in_stk = 0 ;

$in_dist = 0;    */
//exit;

$_SESSION['PROSTKHOLDER'] = $proStkID; 
   
  /*  //////////// GET FILE NAME FROM THE URL
 	$arr = explode("?", basename($_SERVER['REQUEST_URI']));
	$basename = $arr[0];
	$filePath = "plmis_src/reports/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra']; */
			
?>

<?php startHtml($system_title." - District Reports");?>
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
	function frmvalidate()
	{
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

  <link rel="stylesheet" href="../../plmis_css/nn_proj.css" type="text/css">
 
<script type="text/javascript" >
function popUp(str1) {
	str = "index.php?name="+str1;
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(str, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=375px,height=950px,left = 190,top = 162');");
}

function functionCall(month, year, prod, stkID, province){
	//	window.location = "fieldstkreport.php?month_sel="+month+"&year_sel="+year+"&prov_sel="+province+"&stkid="+stkID+"&item_sel="+prod;
	}
	
/*function showstkHolders(){
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
}*/
</script>

<?php
				
	$provWhere="";

	if($sel_prov=='all') 
		$provWhere="";
	else
		$provWhere=" AND tbl_locations.ParentID = '$sel_prov' ";
	
	
	$querydist = "SELECT DISTINCT
						tbl_locations.PkLocID AS whrec_id,
						tbl_locations.LocName AS wh_name,
						tbl_locations.ParentID AS province
						FROM
						tbl_locations
						INNER JOIN tbl_warehouse ON tbl_warehouse.locid = tbl_locations.PkLocID
						INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
						WHERE stakeholder.stk_type_id = ".$lvl_stktype." and tbl_locations.LocLvl >= 3 $provWhere $where
						ORDER BY stakeholder.stkid,province,wh_name";
/*	print $querydist;
	exit;*/
	$rsdist = mysql_query($querydist) or die(mysql_error());
	/*while($rowdist = mysql_fetch_array($rsdist)) {
				 $sdistid=$rowdist['whrec_id'];
			$queryStk = "SELECT DISTINCT(stakeholder.stkname), stakeholder.stkid
						FROM stakeholder
						INNER JOIN tbl_warehouse ON tbl_warehouse.stkid = stakeholder.stkid
						INNER JOIN stakeholder AS office ON office.stkid = tbl_warehouse.stkofficeid
						WHERE office.lvl = 3 and stakeholder.stk_type_id = 0 
						and tbl_warehouse.locid=".$rowdist['whrec_id']." $where ORDER BY stakeholder.stkid ASC";
		print $queryStk."<br />";	}
	exit;	*/
		
		
		$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$xmlstore .="<rows>\n";
		$counter= 1;
	while($rowdist = mysql_fetch_array($rsdist)) {
				 $sdistid=$rowdist['whrec_id'];
		$xmlstore .="\t<row id=\"$counter\">\n";
		//$xmlstore .="\t\t<cell colspan=\"6\"><![CDATA[$rowdist[wh_name]]]></cell>\n";
		$tempVar1 = "\"$rowdist[wh_name]\"";
		$tempVar = str_replace(" ", "_", $tempVar1);
		
			$tempVar = "";
				$tempVar .= "\"$sel_month\",";
				$tempVar .= "\"$sel_year\",";
				$tempVar .= "\"$sel_item\",";
				$tempVar .= "\"$proStkID\",";
				$tempVar .= "\"$sdistid\"";
				//print $tempVar;
		$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rowdist[wh_name]</a>]]>^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		
		$queryStk = "SELECT DISTINCT(stakeholder.stkname), stakeholder.stkid,stakeholder.stk_type_id
						FROM stakeholder
						INNER JOIN tbl_warehouse ON tbl_warehouse.stkid = stakeholder.stkid
						INNER JOIN stakeholder AS office ON office.stkid = tbl_warehouse.stkofficeid
						WHERE office.lvl >= 3 and stakeholder.stk_type_id = ".$lvl_stktype." 
						and tbl_warehouse.locid=".$rowdist['whrec_id']." $where ORDER BY stakeholder.stkid ASC";
		//print $queryStk;				// 
		
		 $rsStk3 = mysql_query($queryStk) or die(mysql_error());

//if (mysql_num_rows ($rsStk3)>0)
		 while($rowStk3 = mysql_fetch_array($rsStk3)) 
		 {
			 $counter++;
			// print $queryvals2;
			 //print "[".'CABMY'."][".'R'."][".'WSPD'."][".$sel_month."][".$sel_year."][".$sel_item."][".$rowStk3['stkid']."][".$sel_prov.$rowdist['whrec_id']."]["."<BR>";
			 	 		
			 $queryvals2 =  "SELECT REPgetData('CABM','R','TSPD','$sel_month','$sel_year','".$sel_item."','".$rowStk3['stkid']."','$sel_prov','".$rowdist['whrec_id']."') AS Value FROM DUAL";

/*	print $queryvals2;
	exit;*/
	
			 $rsvals2 = mysql_query($queryvals2) or die(mysql_error());
			 $rowvals2 = mysql_fetch_array($rsvals2);                  
			 $tmp = explode('*',$rowvals2['Value']); 
			
			 $sel_item = $sel_item;
			 $sel_stk = $rowStk3['stkid'];
			 $sel_lvl = 3;
			 
			 
				 
				
				 $xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rs[prov_title]</a>]]>^_self</cell>\n";
			 
			$xmlstore .="\t<row id=\"$counter\">\n";
			$xmlstore .="\t\t<cell></cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$rowStk3[stkname]]]></cell>\n";
			//$xmlstore .="\t</row>\n";
								 
								                              
		include("incl_data_render.php");                               
		} 
		$counter++;
	}
       $xmlstore .="</rows>\n";
	   
	   
////////////// GET Product Name
	$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
	$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name
	$provinceQryRes = mysql_fetch_array(mysql_query("SELECT tbl_locations.LocName as prov_title from tbl_locations WHERE tbl_locations.PkLocID = '".$sel_prov."' "));
	$provinceName = "\'$provinceQryRes[prov_title]\'";
	

?> 
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		//mygrid.setHeader("District,#cspan,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "District Report For Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='District Name'>Districts</span>,#cspan,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan");
		mygrid.setInitWidths("*,160,160,160,160,40,40");
		mygrid.setColAlign("left,left,right,right,right,center,center");
		//mygrid.setColSorting(",,str,str,str,str,str");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/district_report.xml");
	}
 
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();showstkHolders()">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
      
        <div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align=""><br>
    
             <?php  showBreadCrumb();?> <div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div> <br><br>
      
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
        
        writeXML('district_report.xml', $xmlstore);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>