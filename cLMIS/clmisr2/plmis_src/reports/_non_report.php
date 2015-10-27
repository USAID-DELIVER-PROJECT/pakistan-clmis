<?php
include("../../html/adminhtml.inc.php");
Login();
//include("../../plmis_inc/common/CnnDb.php");	//Include Database Connection File
//include("../../plmis_inc/common/FunctionLib.php");	//Include Global Function File
   
//////////// GET FILE NAME FROM THE URL
$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/reports/".$basename;

//////// GET Read Me Title From DB. 

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
$readMeTitle = $qryResult['extra'];


$report_id = "SNONREPDIST";
if ( isset($_POST['rptType']) && $_POST['rptType'] == 'reported' )
{
	$report_title = "Reported Warehouses Report for";
}
else
{
	$report_title = "Non-reported Warehouses Report for";
}
$actionpage = "non_report.php";
$parameters = "TSP";


/*if(isset($_POST['go']))
{
		$sel_month = $_GET['report_month'];
		$sel_year = $_GET['report_year'];
		$sel_prov = $_GET['prov_id'];
		$sel_stk = $_GET['stk_id'];
		
	if($sel_prov=='all' || $sel_prov=='' || $sel_prov==0)
		$qstrprov = "";
	else
		$qstrprov = "AND tbl_warehouse.prov_id = ".$sel_prov;
	
	if($sel_stk=='all' || $sel_stk=='' || $sel_stk==0)
		$qstrstk = "";
	else
		$qstrstk = "AND tbl_warehouse.stkid = ".$sel_stk;	
	
	
} else */


if(isset($_REQUEST['month_sel'])){
	$sel_month = $_REQUEST['month_sel'];
	$sel_year = $_REQUEST['year_sel'];
	$sel_stk = $_REQUEST['stk_sel'];
	$sel_prov = $_REQUEST['prov_sel'];

	if($sel_prov=='all' || $sel_prov=='' || $sel_prov==0)
		$qstrprov = "";
	else
		$qstrprov = "AND tbl_warehouse.prov_id = ".$sel_prov;
	
	if($sel_stk=='all' || $sel_stk=='' || $sel_stk==0)
		$qstrstk = "";
	else
		$qstrstk = "AND tbl_warehouse.stkid = ".$sel_stk;
 	
	$whtypestr = "";
	/*if(isset($_GET['tp'])){
		if($_GET['tp']=='fl'){
			$obla = 'tbl_wh_data.fld_obl_a'; 
			$cbla = 'tbl_wh_data.fld_cbl_a'; 
			$recd = 'tbl_wh_data.fld_recieved';
			$issued = 'tbl_wh_data.fld_issue_up';
			$adja = 'tbl_wh_data.fld_adja';
			$adjb = 'tbl_wh_data.fld_adjb';
			$whtypestr = " AND tbl_warehouse.wh_type_id in ('DPWO','EDO(H)','DPIU','Private Sector')";
		} else {
			$obla = 'tbl_wh_data.wh_obl_a'; 
			$cbla = 'tbl_wh_data.wh_cbl_a'; 
			$recd = 'tbl_wh_data.wh_received';
			$issued = 'tbl_wh_data.wh_issue_up';
			$adja = 'tbl_wh_data.wh_adja';
			$adjb = 'tbl_wh_data.wh_adjb';
			$whtypestr = "";		
		}
	} else {
		$obla = 'tbl_wh_data.wh_obl_a'; 
		$cbla = 'tbl_wh_data.wh_cbl_a'; 
		$recd = 'tbl_wh_data.wh_received';
		$issued = 'tbl_wh_data.wh_issue_up';
		$adja = 'tbl_wh_data.wh_adja';
		$adjb = 'tbl_wh_data.wh_adjb';
	}*/
//	if(isset($_GET['tp']))
	/**/
	
} else {
	$sel_month = date('m');
	$sel_year = date('Y');
	$sel_stk = "";
	$sel_prov = "";
	$qstrprov = "";
	$qstrstk = "";
	$province = 1;
	$obla = 'tbl_wh_data.wh_obl_a'; 
	$cbla = 'tbl_wh_data.wh_cbl_a'; 
	$recd = 'tbl_wh_data.wh_received';
	$issued = 'tbl_wh_data.wh_issue_up';
	$adja = 'tbl_wh_data.wh_adja';
	$adjb = 'tbl_wh_data.wh_adjb';
	$whtypestr = "";
}

if(empty($sel_stk) && empty($sel_prov)){
	$in_type =  'N';
	$in_id =  0;
	$in_stk = 0;
	$in_prov = 0;
}else if(!empty($sel_stk) && empty($sel_prov)){
	$in_type =  'S';	
	$in_id =  $sel_stk;
	$in_stk = 0;
	$in_prov = 0;
}else if(empty($sel_stk) && !empty($sel_prov)){
	$in_type =  'P';	
	$in_id =  $sel_prov;
	$in_stk = 0;
	$in_prov = 0;
}else{
	$in_type =  'SP';		
	$in_id =  0;
	$in_stk = $sel_stk ;
	$in_prov = $sel_prov; 
}

$in_month =  $sel_month;
$in_year =   $sel_year;
$in_item =  'IT-001';
?>
<?php startHtml($system_title." - $report_title");?>

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
<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="../../plmis_js/FunctionLib.js"></SCRIPT>
<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
       
        var width = 910, height = 300;
        var IT;
        window.onerror = ScriptError;				
        function ScriptError()
            {
                //window.parent.location="../Error.php";
                //return true;
            }
        
        function FilterData()
            {
                document.frmF7.ActionType.value="Filter"
                document.frmF7.submit();
            }
        function ShowData(RowID)
            {
                document.frmF7.ActionType.value="EditShow"
                //document.frmF7.Stake.value=stk
                document.frmF7.PrvRecordID.value=RowID
                document.frmF7.submit();
            }				
        function Logout()
            {
                window.parent.location="../Logout.php?Logid="+document.frmF7.LogedID.value
            }
    
        function ContinueValidate()
            {
                if (document.frmF7.Stk1.value== "")
                    {
                        alert("Please Select A Stakeholder");
                        document.frmF7.Stk1.focus();
                        return false;
                    }
                
                if (document.frmF7.report_year.value== "")
                    {
                        alert("Please Select A Year");
                        document.frmF7.report_year.focus();
                        return false;
                    }		
                
                if (document.frmF7.report_month.value== "")
                    {
                        alert("Please Select A Month");
                        document.frmF7.report_month.focus();
                        return false;
                    }	
            }
</SCRIPT>
		

    <?php
   
    $mynum2 = 0;
    if($sel_stk>0){
        $rs_stkname = mysql_query("SELECT getSktName(".$sel_stk.")");
        $StakeHolderName = mysql_result($rs_stkname,0,0);
    } else {
        $StakeHolderName = '';
    }
    
	 //Reported Warehouses
	if ( !empty($_REQUEST['lvl_type']) && $_REQUEST['lvl_type'] != 'all' )
	{
		$lvlType = " stakeholder.lvl= '".$_REQUEST['lvl_type']."' ";
	}
	else
	{
		$lvlType = " 1=1 ";
	}
	
	if ( $_REQUEST['lvl_type'] == 3 ) $type = 'District';
	else if ( $_REQUEST['lvl_type'] == 4 ) $type = 'Field';
	else $type = '';
	
    //Total Warehouses
	/* print $query = "SELECT COUNT(distinct tbl_wh_data.wh_id)
               FROM tbl_warehouse
Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid 
              WHERE 1=1 and $lvlType $whtypestr $qstrstk $qstrprov";*/
    
	// Total Warehouses
	$query = "SELECT
				COUNT(tbl_warehouse.wh_id)
			FROM tbl_warehouse
			Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid 
			WHERE  stakeholder.lvl > 2 and $lvlType $whtypestr $qstrstk $qstrprov";
    $rs = mysql_query($query) or die(mysql_error());
    $total = mysql_fetch_array($rs);
    
	//print $query."<br>"; 
   
	// Reported Warehiuses
    $query2 = "SELECT COUNT(distinct tbl_wh_data.wh_id)
               FROM tbl_warehouse
Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
where $lvlType $qstrstk $qstrprov AND stakeholder.lvl > 2  AND ( tbl_wh_data.report_month = ".$sel_month." AND tbl_wh_data.report_year = ".$sel_year.") $whtypestr";
			  // print $query2;
    $rs2 = mysql_query($query2) or die(mysql_error());   
    $reported = mysql_fetch_array($rs2); 
    $nonreported = 	$total[0] - $reported[0];
    $reported = 	$reported[0];
    if($total[0]==0)
	{
        $ReportRate=(($total[0]-$nonreported)/1)*100;
	}
    else
	{
        $ReportRate=(($total[0]-$nonreported)/$total[0])*100;
	}
?>            
       <?php if(!empty($StakeHolderName)) {}
               
							 
		//Reported List
		$queryl = "SELECT DISTINCT(tbl_wh_data.wh_id)
				   FROM tbl_warehouse
Inner Join tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
Inner Join stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
where  $lvlType  AND ( tbl_wh_data.report_month = ".$sel_month." AND tbl_wh_data.report_year = ".$sel_year.") $qstrstk $qstrprov $whtypestr";
		$rsl = mysql_query($queryl) or die(mysql_error());
		$numl = mysql_num_rows($rsl);
		if($numl>0){
			while($rowl = mysql_fetch_array($rsl)){
				$whlist	 .= "'".$rowl[0]."',";
			}
			$whlist = substr($whlist,0,strlen($whlist)-1);
			
			// If report type is "Reported Warehouses"
			if ( isset($_POST['rptType']) && $_POST['rptType'] == 'reported' )
			{
				$qwhlist = " AND tbl_warehouse.wh_id IN(".$whlist.")";
			}
			else // If report type is "Non-reported Warehouses"
			{
				$qwhlist = " AND tbl_warehouse.wh_id NOT IN(".$whlist.")";
			}
			
		}else {
			// If report type is "Reported Warehouses"
			if ( isset($_POST['rptType']) && $_POST['rptType'] == 'reported' )
			{
				$qwhlist = " AND tbl_warehouse.wh_id IN('')";
			}
			else // If report type is "Non-reported Warehouses"
			{
				$qwhlist = "";
			}
		}

		//LEFT OUTER JOIN tbl_districts ON tbl_districts.whrec_id = tbl_warehouse.dist_id
		// All warehouses ID's based on our report type
		$queryr2 = "SELECT DISTINCT
						tbl_warehouse.wh_id
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID 
					WHERE stakeholder.lvl > 2 and $lvlType $whtypestr $qstrstk $qstrprov $qwhlist ORDER BY tbl_locations.LocName ASC";
		$rsr2 = mysql_query($queryr2) or die(mysql_error());   
		$numr2 = mysql_num_rows($rsr2);
		$i=1;
		if($numr2>0)
		$rowcolor = '#dff2a9';
			$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			$xmlstore .="<rows>\n";
			$counter = 1;
			
			while($rowr2 = mysql_fetch_array($rsr2)) {
				$xmlstore .="\t<row id=\"$counter\">\n";
				$xmlstore .="\t\t<cell style=\"text-align:center\">".$i."</cell>\n";
				$querywh = "SELECT
								office.stkname,
								stakeholder.stkname AS wh_type_id,
								province.LocName AS prov_tittle,
								Districts.LocName AS wh_name,
								(SELECT
									CONCAT(DATE_FORMAT(tbl_wh_data.last_update, '%d/%m/%Y'), ' ', TIME_FORMAT(tbl_wh_data.last_update, '%h:%i:%s %p'))
								FROM
									tbl_wh_data
								WHERE
									tbl_wh_data.report_month = ".$sel_month."
								AND tbl_wh_data.report_year = ".$sel_year."
								AND tbl_wh_data.wh_id = ".$rowr2['wh_id']." LIMIT 1)AS last_update,
								(SELECT
									tbl_wh_data.ip_address
								FROM
									tbl_wh_data
								WHERE
									tbl_wh_data.report_month = ".$sel_month."
								AND tbl_wh_data.report_year = ".$sel_year."
								AND tbl_wh_data.wh_id = ".$rowr2['wh_id']." LIMIT 1)AS ip_address
							FROM
								tbl_warehouse
							Inner JOIN tbl_locations AS province ON tbl_warehouse.prov_id = province.PkLocID
							Inner JOIN tbl_locations AS Districts ON tbl_warehouse.locid = Districts.PkLocID
							Inner JOIN stakeholder AS office ON tbl_warehouse.stkid = office.stkid
							Inner JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
							WHERE
								tbl_warehouse.wh_id=".$rowr2['wh_id']."
							LIMIT 1";
				//echo $querywh; exit;
				$rswh = mysql_query($querywh) or die(mysql_error());
				$rsRow2 = mysql_fetch_array($rswh);	
				if($rowcolor == '#dff2a9')
					$rowcolor = '#FFFFFF';
				else
					$rowcolor = '#dff2a9';	
			$i++;
			$counter++;
			  
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[prov_tittle]]]></cell>\n";  
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[wh_name]]]></cell>\n";  
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[stkname]]]></cell>\n";  
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[wh_type_id]]]></cell>\n";
			$xmlstore .="\t\t<cell style=\"text-align:center\"><![CDATA[$rsRow2[last_update]]]></cell>\n";
			$tempVar = "\"$rsRow2[ip_address]\"";
			$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall($tempVar)>$rsRow2[ip_address]</a>]]>^_self</cell>\n";
			$xmlstore .="\t</row>\n";
			}
			$xmlstore .="</rows>\n";
echo getReportDescriptionFooter($report_id); 

////////////// GET Product Name

	$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
	$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name
	
	if ($sel_prov == 'all' || $sel_prov == ""){
		$provinceName = "\'All\'";		
	}else{
		$provinceQryRes = mysql_fetch_assoc(mysql_query("SELECT LocName AS prov_tittle FROM tbl_locations WHERE PkLocID = '".$sel_prov."' "));
		$provinceName = "\'$provinceQryRes[prov_tittle]\'";
	}
////////////// GET Stakeholders
  	
	if ($sel_stk == 'all' || $sel_stk == ""){
		$stkName = "\'All\'";
	}else{
		$stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
		$stkName = "\'$stakeNameQryRes[stkname]\'";
	}	
	
	/////////////// To show all Stakeholders. And All provinces on pageload
	
	$stakeHolder = 1;
	$province = 1;
?> 
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "$type $report_title Stakeholder = $stkName And Province/Region = $provinceName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Serial Number'>S. No.</span>,<span title='Province/Region Name'>Province/Region</span>,<span title='District Name'>District</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Warehouse Type'>WH Type</span>,<span title='Last Report Update'>Last Updated</span>,<span title='IP Address of The Reported System'>IP Address</span>");
		mygrid.attachHeader(",#select_filter,#select_filter,#select_filter,#select_filter,,");
		mygrid.setInitWidths("80,150,150,100,*,160,160");
		mygrid.setColAlign("left,left,left,left,left,left");
		mygrid.setColSorting("int,str,str,str,str,str,str");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/non_report.xml");
	}

	function functionCall(ip)
	{
		window.open('ip_info.php?ip='+ip, '_blank', 'scrollbars=1,width=650,height=600');
	} 
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align="" style="min-height:679px;"><br>
    
            <?php showBreadCrumb();?><div style="float:right"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
    
            <table width="99%">
                <tr>
                    <td colspan="8">
                        <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" height="29" align="center" valign="middle"><strong class="sb1FormLabel">Total Warehouses</strong></td>
                    <td width="22" align="left" valign="middle">&nbsp;<span class="sb1Exception"><?php echo $total[0]; ?></span></td>
                    <td width="240" align="center" valign="middle" class="sb1FormLabel">Total Reported Warehouses</td>
                    <td width="60" align="left" valign="middle" class="sb1FormLabel"><span class="sb1Exception"><?php echo $reported;?></span></td>
                    <td width="240" align="center" valign="middle" class="sb1FormLabel">Total Non Reported Warehouses</td>
                    <td width="60" align="left" valign="middle" class="sb1FormLabel"><span class="sb1Exception"><?php echo $nonreported;?></span></td>
                    <td width="147" align="center" valign="middle" class="sb1FormLabel">Reporting Rate</td>
                    <td width="31" align="left" valign="middle" class="sb1Exception"><?php echo number_format($ReportRate,2);?>%&nbsp;</td>
                </tr>  	
                <tr>
                    <td align="right" colspan="8">
                        <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                        <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                   </td>
                </tr>
            </table>
    		
         	<?php if($numr2>0){?>
            <table width="99%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="mygrid_container" style="width:100%; height:470px; background-color:white;overflow:hidden"></div>
                    </td>
                </tr>
            </table>
            <?php }else
			{
				echo "No record found.";
			}?>
                 
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
        
        writeXML('non_report.xml', $xmlstore);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>