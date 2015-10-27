<?php
	ob_start();
	//echo "<h1>Report is temp. down</h1> ";exit;
	include("../../html/adminhtml.inc.php");
	Login();

	//////////// GET FILE NAME FROM THE URL
 	$arr = explode("?", basename($_SERVER['REQUEST_URI']));
	$basename = $arr[0];
	$filePath = "plmis_src/reports/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
	
    $report_id = "SNASUMSTOCKLOC";  
    $report_title = "Item Availability Report";
    $actionpage = "itemsreport.php";
    $parameters = "TS01P01I";
	
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
	
	
    
	if(isset($_GET['tp']) && !isset($_POST['go']))
	{
	//print "GET";

		if(isset($_GET['report_month']) && !empty($_GET['report_month']))
			$sel_month = $_GET['report_month'];
		
		if(isset($_GET['report_year']) && !empty($_GET['report_year']))
			$sel_year = $_GET['report_year'];
		
		if(isset($_GET['item_id']) && !empty($_GET['item_id'])){
			$sel_item = $_GET['item_id'];
			$qStrPro = " AND itmrec_id = '".$_GET['item_id']."'";
		}	
		if(isset($_GET['stk_id']) && !empty($_GET['stk_id']))
		{	
			if ( $_GET['stk_id']!='all')
			{
			$sel_stk = $_GET['stk_id'];
			$qStrStk = " AND tbl_warehouse.stkid = ".$_GET['stk_id'];
			}
			else
			{
			$qStrStk = " ";
			$sel_stk = $_GET['stk_id'];
			}
			
		}	
		if(isset($_GET['prov_id']) && !empty($_GET['prov_id']))
		{	
		if ($_GET['prov_id']!='all')
			{
			$sel_prov = $_GET['prov_id'];	
			$qStrProv = " AND tbl_warehouse.prov_id = ".$_GET['prov_id'];
			}
		else
			{
			$sel_prov = $_GET['prov_id'];	
			$qStrProv = "";
			}
		}
	
	//print $_REQUEST['prov_id']."<br >".$_REQUEST['stk_id']."GET";
	
	}
	else if(isset($_POST['go'])){
		
			//print "POST";
/*		if(isset($_GET['tp']) && !empty($_GET['tp']))
			$sel_col = 'tbl_wh_data.wh_cbl_a';
*/		
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']) && $_POST['prod_sel']!='all'){
			$sel_item = $_POST['prod_sel'];
			$qStrPro = " AND itmrec_id = '".$_POST['prod_sel']."'";
		}

		if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']) && $_POST['stk_sel']!='all'){	
			$sel_stk = $_POST['stk_sel'];
			$qStrStk = " AND tbl_warehouse.stkid = ".$_POST['stk_sel'];
		}else
		{
		$qStrStk = " ";
			$sel_stk = $_GET['stk_id'];	
		}
		
		if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']) && $_POST['prov_sel']!='all'){	
			$sel_prov = $_POST['prov_sel'];	
			$qStrProv = " AND tbl_warehouse.prov_id = ".$_POST['prov_sel'];
		}else
			{
			$sel_prov = $_GET['prov_id'];	
			$qStrProv = "";
			}
	
			/*$colspan = 4;
			$sel_col = 'tbl_wh_data.wh_cbl_a'; */
		//print $_REQUEST['prov_id']."|".$_REQUEST['stk_id']."&nbspPOST <br >";
	} 
	else 
	{
		//print "ELSE";
									
		$querymaxmonth = "SELECT month(MAX(RptDate)) as report_month , year(MAX(RptDate)) as report_year FROM tbl_wh_data";
		$rsmaxmonth = mysql_query($querymaxmonth) or die(mysql_error());
		$rowmaxmonth = mysql_fetch_array($rsmaxmonth);
		$sel_month = $rowmaxmonth['report_month']-1;
		$sel_year = $rowmaxmonth['report_year'];
		
		$sel_stk = '1';
		$qStrStk = " AND tbl_warehouse.stkid = 1";
		$sel_item = 'IT-001';
		$qStrPro = " AND itmrec_id = 'IT-001'";
		$sel_prov = '1';	
		$qStrProv = " AND tbl_warehouse.prov_id = 1";
	/*	$sel_col = 'tbl_wh_data.wh_cbl_a';
		$colspan = 4;
*/	}

if($sel_stk=='all'){$in_stk = 0; }
if($sel_prov=='all'){$in_prov = 0; }


/*	if(empty($sel_stk) && empty($sel_prov)){
		$in_type =  'N';
		$in_id =  0;
		$in_stk = 0;
		$in_prov = 0;
	}else if(!empty($sel_stk) && empty($sel_prov))
{
		$in_type =  'S';	
		$in_id =  $sel_stk;
		$in_stk = 0;
		$in_prov = 0;
	}else if(empty($sel_stk) && !empty($sel_prov))
{
		$in_type =  'P';	
		$in_id =  $sel_prov;
		$in_stk = 0;
		$in_prov = 0;
	}else
{
		$in_type =  'SP';		
		$in_id =  0;
		$in_stk = $sel_stk ;
		$in_prov = $sel_prov; 
	}*/
	

	$in_month =  $sel_month;
	$in_year =   $sel_year;
	$in_item =  $sel_prod;
	
	
	/*function getProdName($id){
		$query = "SELECT itm_name FROM itminfo_tab WHERE itmrec_id='$id'";
		$rs = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		return $row['itm_name'];
	}*/
	
/*	function getStakeholderName($id){
		$query = "SELECT stkname FROM stakeholder WHERE stkid='$id'";
		$rs = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		return $row['stkname'];
	}
	function getDistrictName($id){
		$query = "SELECT tbl_locations.LocName as wh_name
												FROM tbl_locations where tbl_locations.PkLocID='$id'";
		$rs = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		return $row['wh_name'];
	}
	function getProvinceName1($id){
		$query = "SELECT tbl_locations.LocName as prov_title
												FROM tbl_locations where tbl_locations.PkLocID='$id'";
		$rs = mysql_query($query) or die(mysql_error());
		$row = mysql_fetch_array($rs);
		return $row['prov_title'];
	}*/
	/*function getTotalCBLA($sel_prod,$sel_month,$sel_year,$sel_stk){
		$sum1=0;
		$sum2=0;
		$sum3=0;
		//Central Warehouse
		$queryItem = "SELECT tbl_wh_data.wh_cbl_a as thirdCol 
							  FROM tbl_warehouse 
							  Inner Join stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
								LEFT JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
								LEFT JOIN stakeholder_item ON stakeholder_item.stk_item = tbl_wh_data.item_id
							  WHERE 
								report_month='$sel_month' AND 
								report_year='$sel_year' AND 
								item_id='$sel_prod' AND
								Office.lvl = 1  $sel_stk";
								//print $queryItem;
		$rsItem = mysql_query($queryItem) or die(mysql_error());
		while($rowItem = mysql_fetch_array($rsItem)){
			$sum1 = $sum1+ $rowItem['thirdCol'];
		}
		//PPIUs
		$queryItem1 = "SELECT tbl_wh_data.wh_cbl_a as thirdCol 
							  FROM tbl_warehouse
							  Inner Join stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid 
								LEFT JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
								LEFT JOIN stakeholder_item ON stakeholder_item.stk_item = tbl_wh_data.item_id
							  WHERE 
								report_month='$sel_month' AND 
								report_year='$sel_year' AND 
								item_id='$sel_prod' AND
								Office.lvl = 2 $sel_stk";
		$rsItem1 = mysql_query($queryItem1) or die(mysql_error());
		while($rowItem1 = mysql_fetch_array($rsItem1)){
			$sum2 = $sum2 + $rowItem1['thirdCol'];
		}
		//Districts
		$queryItem2 = "SELECT wh_cbl_a,wh_cbl_a
									FROM tbl_wh_data
									LEFT JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
									 Inner Join stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid 
									WHERE Office.lvl = 3 AND
												tbl_wh_data.report_month='$sel_month' AND
												tbl_wh_data.report_year='$sel_year' AND
												tbl_wh_data.item_id ='$sel_prod' $sel_stk
									GROUP BY tbl_warehouse.dist_id,tbl_warehouse.stkid
									ORDER BY prov_id,dist_id,stkid";
		//print $queryItem2;
		//exit();
		
		$rsItem2 = mysql_query($queryItem2) or die(mysql_error());
		
		while($rowItem2 = mysql_fetch_array($rsItem2)){
			$sum3 = $sum3 + $rowItem2['wh_cbl_a'] + $rowItem2['wh_cbl_a'];
		}
		
		$sum = $sum1 + $sum2 + $sum3;
		return $sum;
	}
*/
	
?>
<?php startHtml($system_title." - Item Availability Report");?>

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
</script>
				<?php
	 ob_flush();
$total = 0;
$cwhtotal = 0;
$ppiutotal = 0;
$disttotal = 0;
$queryItem = " SELECT DISTINCT tbl_warehouse.wh_id,tbl_warehouse.wh_name as firstCol, tbl_warehouse.stkid as secondCol,tbl_warehouse.prov_id,stakeholder.stkname
			FROM tbl_warehouse
			INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
			INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid
			INNER JOIN stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
			INNER JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id 
			WHERE Office.lvl = 1 and tbl_wh_data.report_month='$sel_month' and tbl_wh_data.report_year='$sel_year' and tbl_warehouse.prov_id=10
			 $qStrPro $qStrStk ";
$rsItem = mysql_query($queryItem) or die(mysql_error());
				  $numItemCWH = mysql_num_rows($rsItem);
$xmlstore2 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlstore2 .="<rows>\n";
$counter= 1;

while($rowItem = mysql_fetch_array($rsItem)) 
{
		$xmlstore2 .="\t<row id=\"$counter\">\n";
		$cwhamc=0;
		$cwhtotal=0;
		$cwhmos=0;
						 
		$query = mysql_query("SELECT REPgetConsumptionAVG('FS','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."',0,0) FROM DUAL");
		$cwhamc = mysql_result($query,0,0);
				
		$query = mysql_query("SELECT REPgetCB('WS1','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."',0,0) FROM DUAL");
		$cwhtotal = mysql_result($query,0,0);
						
		$query = mysql_query("SELECT REPgetMOS('WS1','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."',0,0) FROM DUAL");
		$cwhmos=mysql_result($query,0,0);
		
						if($old1!=$rowItem['firstCol']){$cwhName = $rowItem['firstCol'];}
						
						$stkName = $rowItem['stkname'];
						$xmlstore2 .="\t\t<cell>".$cwhName."</cell>\n";
						$xmlstore2 .="\t\t<cell><![CDATA[$stkName]]></cell>\n";
						$xmlstore2 .="\t\t<cell>".number_format($cwhamc/PLMIS_CBL_UNIT)."</cell>\n";
						$xmlstore2 .="\t\t<cell>".number_format($cwhtotal/PLMIS_CBL_UNIT)."</cell>\n";
						$xmlstore2 .="\t\t<cell>".number_format($cwhmos,1)."</cell>\n";
						$xmlstore2 .="\t</row>\n";
						
						$old1 = $rowItem['firstCol'];
						$total = $total+$ppiutotal;
						$counter++;				
				    }

 $xmlstore2 .="</rows>\n";

                
				
				$queryItem = "SELECT DISTINCT Office.stkname AS firstCol,
										tbl_warehouse.prov_id AS firstCola,
										tbl_warehouse.stkid AS secondCol,
										stakeholder.stkname,
										prov.LocName AS Province
										FROM tbl_warehouse
										INNER JOIN tbl_locations AS prov ON tbl_warehouse.prov_id = prov.PkLocID
										INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
										INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid
										INNER JOIN stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
										INNER JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id
										INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id 
									WHERE Office.lvl = 2 and tbl_wh_data.report_month='$sel_month' and tbl_wh_data.report_year='$sel_year'
									 $qStrPro $qStrStk $qStrProv";
				  $rsItem = mysql_query($queryItem) or die(mysql_error());
				  $numItem = mysql_num_rows($rsItem); 
				  if($numItem>0) { 
				  
					$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					$xmlstore .="<rows>\n";
					$counter= 1;

				  	while($rowItem = mysql_fetch_array($rsItem)) {
						 $xmlstore .="\t<row id=\"$counter\">\n";
						 $ppiuamc = 0;
						 $ppiutotal =0;
						 $ppiumos=0;
						 
						 $query = mysql_query("SELECT REPgetConsumptionAVG('FSP','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."','".$rowItem['firstCola']."',0) FROM DUAL");
						$ppiuamc = mysql_result($query,0,0);
						
						$query = mysql_query("SELECT REPgetCB('WSP2','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."','".$rowItem['firstCola']."',0) FROM DUAL");
						$ppiutotal = mysql_result($query,0,0);
						$query = mysql_query("SELECT REPgetMOS('WSP2','$sel_month','$sel_year','".$sel_item."','".$rowItem['secondCol']."','".$rowItem['firstCola']."',0) FROM DUAL");
						$ppiumos=mysql_result($query,0,0);
						
						$stkName = $rowItem['stkname'];
						$xmlstore .="\t\t<cell>".$rowItem['Province']."</cell>\n";
						$xmlstore .="\t\t<cell><![CDATA[$stkName]]></cell>\n";
						$xmlstore .="\t\t<cell>".number_format($ppiuamc/PLMIS_CBL_UNIT)."</cell>\n";
						$xmlstore .="\t\t<cell>".number_format($ppiutotal/PLMIS_CBL_UNIT)."</cell>\n";
						$xmlstore .="\t\t<cell>".number_format($ppiumos,1)."</cell>\n";
						$xmlstore .="\t</row>\n";
						
						$old1 = $rowItem['firstCol'];
						$total = $total+$ppiutotal;
						$counter++;				
				    } 
					
					 $xmlstore .="</rows>\n";
					
				  } else{
						$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						$xmlstore .="<rows>\n";  
						$xmlstore .="</rows>\n";
					}
					// Get all private Stakeholders
					$pStkQry = mysql_fetch_array(mysql_query("SELECT 
																GROUP_CONCAT(DISTINCT stakeholder.MainStakeholder ORDER BY stakeholder.MainStakeholder) AS pStakeholders
																FROM
																	stakeholder
																WHERE
																	stakeholder.stk_type_id = 1"));
					
					
					$stkArr = explode(',', ($pStkQry['pStakeholders']));
					if (in_array($sel_stk, $stkArr))
					{
						$where = '  Office.lvl >= 3';
					}
					else
					{
						$where = '  Office.lvl = 3';
					}
 				
                  $queryItem2 = "SELECT DISTINCT tbl_warehouse.dist_id,tbl_warehouse.stkid,
				  					tbl_warehouse.prov_id,prov.LocName AS Province,
				  					dist.LocName AS District,stakeholder.stkname
									FROM tbl_warehouse 
									INNER JOIN tbl_locations AS prov ON tbl_warehouse.prov_id = prov.PkLocID
									INNER JOIN tbl_locations AS dist ON  tbl_warehouse.dist_id=dist.PkLocID 
									INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid
									INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid
									INNER JOIN stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
									INNER JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id
									INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id 
									WHERE $where
									and tbl_wh_data.report_month='$sel_month' and tbl_wh_data.report_year='$sel_year'
									 $qStrPro $qStrStk $qStrProv
									ORDER BY stakeholder.stkname ASC,
										prov.LocName ASC,
											dist.LocName ASC";
									//print $queryItem2;
									
				  $rsItem2 = mysql_query($queryItem2) or die(mysql_error());
				  $numItem2 = mysql_num_rows($rsItem2);
				  if($numItem2>0) { 
				  
					$xmlstore1="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
					$xmlstore1 .="<rows>\n";
					$counter= 1;
					
					while($rowItem2 = mysql_fetch_array($rsItem2)) { 
					$xmlstore1 .="\t<row id=\"$counter\">\n";				   
					   $districtName = $rowItem2['District'];
					   $provinceName = $rowItem2['Province'];
					   $stkName = $rowItem2['stkname'];
						$ppiuamc = 0;
						$AMC =  0;
						$WHCB = 0;
						$store = 0;
						$MOS =  0;
						$FLDCB = 0;
						$field = 0;
						$MOS1 = 0;
						
						
					    $strSQL="SELECT REPgetConsumptionAVG('WSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL"; 					  
					    $query = mysql_query($strSQL);
					  

						$ppiuamc = mysql_result($query,0,0);
						$AMC =  number_format($ppiuamc/PLMIS_CBL_UNIT);
						$AMC =  $ppiuamc;
					 		
						$query = mysql_query("SELECT REPgetCB('WSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL");
						
						$WHCB = mysql_result($query,0,0);
						$store = $WHCB;
						
						//$query = mysql_query("SELECT REPgetMOS('WSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL");
						
						
						if($store>0 && $AMC >0)
							$MOS = $store/$AMC;
						else
							$MOS = 0;
							
						$ssql="SELECT REPgetCB('FSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL";
						//print $ssql;
						$query = mysql_query($ssql);
						$FLDCB = mysql_result($query,0,0);
						$field = $FLDCB;
						/*
						$query = mysql_query("SELECT REPgetMOS('FSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL");
						$MOS1 = number_format(mysql_result($query,0,0),1);*/
						
						if($field>0 && $AMC >0)
							$MOS1 = $field/$AMC;
						else
							$MOS1=0;
				
						$dTotal = $WHCB+$FLDCB;
						$totalAmt = $dTotal;
						$MOS2 = $MOS + $MOS1;
						/*$query = mysql_query("SELECT REPgetMOS('TSPD','$sel_month','$sel_year','".$sel_item."','".$rowItem2['stkid']."','".$rowItem2['prov_id']."','".$rowItem2['dist_id']."') FROM DUAL");*/
						// number_format(mysql_result($query,0,0),1);
						
						
						$old2 = $rowItem2['dist_id'];
						$old3 = $rowItem2['prov_id'];
						$disttotal = $WHCB+$FLDCB;
						$total = $total+$disttotal;
						
						$xmlstore1 .="\t\t<cell>".$districtName."</cell>\n";
						$xmlstore1 .="\t\t<cell>".$provinceName."</cell>\n";
						$xmlstore1 .="\t\t<cell><![CDATA[$stkName]]></cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($AMC)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($store)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($MOS,2)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($field)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($MOS1,2)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($totalAmt)."</cell>\n";
						$xmlstore1 .="\t\t<cell>".number_format($MOS2,2)."</cell>\n";
						$xmlstore1 .="\t</row>\n";
						
						$counter++;
					}
					$xmlstore1 .="</rows>\n"; 
				  }else{
						$xmlstore1="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
						$xmlstore1 .="<rows>\n";  
						$xmlstore1 .="</rows>\n";
					}
					
										 				  			//	echo "adasdsad2"; 
				?>
                <input type="hidden" name="total" id="total" value="<?php echo number_format($total/PLMIS_CBL_UNIT);?>" />
              
<?php echo getReportDescriptionFooter($report_id); 
////////////// GET Product Name

	$proNameQryRes = mysql_fetch_array(mysql_query("SELECT itm_name FROM `itminfo_tab` WHERE itmrec_id = '".$sel_item."' "));
	$prodName = "\'$proNameQryRes[itm_name]\'";
//////////// GET Province/Region Name
	
	if ($sel_prov == 'all' || $sel_prov == ""){
		$provinceName = "\'All\'";		
	}else{
		$provinceQryRes = mysql_fetch_array(mysql_query("SELECT tbl_locations.LocName as prov_title
												FROM tbl_locations where tbl_locations.PkLocID = '".$sel_prov."' "));
		$provinceName = "\'$provinceQryRes[prov_title]\'";
	}
////////////// GET Stakeholders
  	
	if ($sel_stk == 'all' || $sel_stk == ""){
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
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Provincial Report For Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Provincial Office'>Provincial Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
		mygrid.setInitWidths("*,250,100,100,100");
		mygrid.setColAlign("left,left,right,right,right");
		mygrid.setColSorting("str,str");
		mygrid.setColTypes("ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/item_availablity_report_ppi.xml");
		
		mygrid1 = new dhtmlXGridObject('mygrid_container1');
		mygrid1.selMultiRows = true;
		mygrid1.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid1.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Districts Report For Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid1.attachHeader("<span title='District Name'>Districts</span>,<span title='Province/Region Name'>Province/Region</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Store'>Store</span>,<span title='Month of Scale'>MOS</span>,<span title='Field'>Field</span>,<span title='Month of Scale'>MOS</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
		mygrid1.setInitWidths("*,150,100,80,80,80,80,80,80,80");
		mygrid1.setColAlign("left,left,left,right,right,right,right,right,right,right");
		mygrid1.setColSorting("str,str,str");
		mygrid1.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid1.enableLightMouseNavigation(true);
		mygrid1.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid1.setSkin("light");
		mygrid1.init();
		mygrid1.loadXML("xml/item_availablity_report.xml");
		
		mygrid2 = new dhtmlXGridObject('mygrid_container2');
		mygrid2.selMultiRows = true;
		mygrid2.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid2.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Central Warehouse Report For Stakeholder = $stkName Province/Region = $provinceName  And Product = $prodName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan");
		mygrid2.attachHeader("<span title='Central Warehouse Name'>Central Warehouse</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Total'>Total</span>,<span title='Month of Scale'>MOS</span>");
		mygrid2.setInitWidths("*,250,100,100,100");
		mygrid2.setColAlign("left,left,right,right,right");
		mygrid2.setColSorting("str,str");
		mygrid2.setColTypes("ro,ro,ro,ro,ro");
		//mygrid2.enableLightMouseNavigation(true);
		mygrid2.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid2.setSkin("light");
		mygrid2.init();
		mygrid2.loadXML("xml/item_availablity_report2.xml");
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
    
                <?php showBreadCrumb();?><div style="float:right; padding-left:70px"><?php //echo readMeLinks($readMeTitle);?></div> <br><br>
      
      
                <table width="99%">
                    <tr>
                        <td colspan="2">
                            <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                        </td>
                    </tr>   	
                </table>
    
                <?php 
                if($numItemCWH>0) { ?>
                <table width="99%">
                    <tr>
                        <td class="sb1NormalFont">
                         <!-- Choose skin to apply: 
                          <select onChange="mygrid2.setSkin(this.value)">
                            <option value="light" selected>Light
                            <option value="sbdark">SB Dark
                            <option value="gray">Gray
                            <option value="clear">Clear
                            <option value="modern">Modern
                            <option value="dhx_skyblue">Skyblue
                        </select>-->
                        </td>
                        <td align="right">
                            <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid2.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                            <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid2.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                       </td>
                    </tr>
                </table>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container2" style="width:100%; height:200px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                </table><br>
                <?php }else {?>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container2" style="width:100%; height:26px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <strong>No record found.</strong>
                        </td>
                    </tr>
                </table><br>
                <?php } if($numItem>0) { ?>
                <table width="99%">
                    <tr>
                        <td class="sb1NormalFont">
                         <!-- Choose skin to apply: 
                          <select onChange="mygrid.setSkin(this.value)">
                            <option value="light" selected>Light
                            <option value="sbdark">SB Dark
                            <option value="gray">Gray
                            <option value="clear">Clear
                            <option value="modern">Modern
                            <option value="dhx_skyblue">Skyblue
                        </select>-->
                        </td>
                        <td align="right" style="padding-right:0px">
                            <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                            <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                       </td>
                    </tr>
                </table>
                
                     
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container" style="width:100%; height:200px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                </table><br>
                
                <?php }else {?>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container" style="width:100%; height:26px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <strong>No record found.</strong>
                        </td>
                    </tr>
                </table>
                <?php } ?><br />
                
                <?php if($numItem2>0) { ?>
                <table width="99%">
                    <tr>
                        <td class="sb1NormalFont">
                         <!-- Choose skin to apply: 
                          <select onChange="mygrid1.setSkin(this.value)">
                            <option value="light" selected>Light
                            <option value="sbdark">SB Dark
                            <option value="gray">Gray
                            <option value="clear">Clear
                            <option value="modern">Modern
                            <option value="dhx_skyblue">Skyblue
                        </select>-->
                        </td>
                        <td align="right">
                            <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid1.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                            <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid1.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                       </td>
                    </tr>
                </table>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container1" style="width:100%; height:320px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                </table>
                <?php }else {?>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div id="mygrid_container1" style="width:100%; height:26px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <strong>No record found.</strong>
                        </td>
                    </tr>
                </table>
                <?php } ?>
                 
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
        
        writeXML('item_availablity_report_ppi.xml', $xmlstore);
        writeXML('item_availablity_report.xml', $xmlstore1);
        writeXML('item_availablity_report2.xml', $xmlstore2);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>