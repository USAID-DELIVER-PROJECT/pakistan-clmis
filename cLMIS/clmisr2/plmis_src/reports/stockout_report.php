<?php

/***********************************************************************************************************
Developed by  Wasif Raza
Email Id:    wasif@deliver-pk.org
This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district. The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	


$report_id="STOCKOUTRPT";
$report_title = "Yearly Stockout Summary Report";
?>

<?php startHtml($system_title." - Stockouts Yearly Report");?>

<script language="javascript">
function frmvalidate(){

	var x=document.getElementById('in_MOS').value
	if (x==null || x=='')
	{
		alert("Please Enter MOS value");
		document.getElementById('in_MOS').focus();
		return false;
	}
	else if (is_valid = !/^[0-9]+$/.test(x))
	{
		alert("MOS value must have numbers");
		document.getElementById('in_MOS').focus();
		return false;
	}
}

function functionCall(getvari){
		var url;
		var title = "Stockout Districts";
		var h=450;
		var w=650;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h);
		url = "stockout_dist.php?"+getvari;
		window.open(url, title, 'toolbar=no, location=no, directories=no, statusbar=no, menubar=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		//alert("Private.");
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

if(isset($_POST['go']))
{
	$sector =0;

	if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
		$sel_year = $_POST['year_sel'];	
		
	if(isset($_POST['in_MOS']) && !empty($_POST['in_MOS']))
		$in_MOS = $_POST['in_MOS'];

	if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
		$prod_sel = $_POST['prod_sel'];

	if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
		{
			$stk_sel = $_POST['stk_sel'];
			$stkWhere=" and tbl_warehouse.stkid=".$stk_sel;
		}
		else
		{
			$stk_sel = 'all';
			$stkWhere="";		
		}
		
	if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
		{
			if ($_POST['prov_sel']!='all')
			{
				$prov_sel = $_POST['prov_sel'];
				$provWhere=" and tbl_warehouse.prov_id=".$prov_sel;			
			}
			else
			{
				$prov_sel = 'all';
				$provWhere="";		
			}
		}

		
	
	$totWH=0;
	$strSQL="SELECT count(wh_id) as cValue FROM tbl_warehouse
							INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
							WHERE
							stakeholder.lvl = 3 ".$stkWhere.$provWhere;
	$rez = mysql_query($strSQL) or die(mysql_error());
	while($rsWhRez = mysql_fetch_array($rez))
	{
	$totWH=$rsWhRez['cValue'];
		
	}
	
	$head="<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Yearly Stockout Summary Report for $sel_year (MOS less then $in_MOS)</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan";
	$SubHead="<span>Product</span>,<span>Total WH</span>,<span>Jan</span>,<span>Feb</span>,<span>Mar</span>,<span>Apr</span>,<span>May</span>,<span>Jun</span>,<span>Jul</span>,<span>Aug</span>,<span>Sep</span>,<span>Oct</span>,<span>Nov</span>,<span>Dec</span>";
	$colwidths="100,100,60,60,60,60,60,60,60,60,60,60,60,60";
	$colaligns="left,center,right,right,right,right,right,right,right,right,right,right,right,right";
	$colTypes="ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro";
	
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$queryPro = "SELECT itmrec_id,itm_name FROM `itminfo_tab` WHERE itmrec_id='$prod_sel'";
	$queryPro = "SELECT itmrec_id,itm_id,itm_name FROM itminfo_tab
					INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
					WHERE itminfo_tab.itm_status = 'Current' AND stakeholder_item.stkid = $stk_sel
					ORDER BY frmindex";
	$queryPro = mysql_query($queryPro) or die(mysql_error());
	$counter = 1;
	$tot=0;
	while($rsPro = mysql_fetch_array($queryPro))
	{
		$tot=0;
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
		$xmlstore .="\t\t<cell>$totWH</cell>\n";
		
		for($mmj=1;$mmj<13;$mmj++) 
		{				
		if (date("Y-m-d", strtotime($sel_year."-".$mmj."-01"))  <= date('Y-m-d',strtotime(date('Y')."-".(date('m')-1)."-01")))
		{		
			 $queryvals1 =  "SELECT count(wh_id) as cValue FROM tbl_warehouse
							INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
							WHERE
							stakeholder.lvl = 3 
							AND
							REPgetMOSDt('$sel_year-$mmj-01',wh_id,'".$rsPro[itmrec_id]."') < $in_MOS and REPgetMOSDt('$sel_year-$mmj-01',wh_id,'".$rsPro[itmrec_id]."') > 0 ".$stkWhere.$provWhere;
							
			$tempVar="stk=$stk_sel&prov=$prov_sel&MOS=$in_MOS&Dt=$sel_year-$mmj-01&Prod=$rsPro[itmrec_id]";			
			
			$rsvals1 = mysql_query($queryvals1) or die(mysql_error());
			while($rowvals1 = mysql_fetch_array($rsvals1))
			{
				$vali=(double)$rowvals1['cValue'];
				$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall('$tempVar')>".$vali."</a>]]>^_self</cell>\n";
			}
			$lasttype=$stkType;
		}
		else
		{
			$xmlstore .="\t\t<cell>UNK</cell>\n";
		}

		}
	$xmlstore .= "\t</row>\n";
	$counter++;
	}

	$xmlstore .="</rows>\n";
}
?>

<script>
var mygrid;
function doInitGrid(){
	mygrid = new dhtmlXGridObject('mygrid_container');
	mygrid.selMultiRows = true;
	mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid.setHeader("<?php echo $head;?>");
	mygrid.attachHeader("<?php echo $SubHead;?>");
	mygrid.setInitWidths("<?php echo $colwidths;?>");
	mygrid.setColAlign("<?php echo $colaligns;?>");
	mygrid.setColTypes("<?php echo $colTypes;?>");
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.loadXML("xml/stockout_report.xml");
}

</script>


</head>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();">


<?php include "../../plmis_inc/common/top.php";?>
<div class="body_sec">
<div class="wrraper" style="height:auto; padding-left:5px">
<div class="content" align=""><br>
<?php  showBreadCrumb();?><br>

                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr height="34">
                            <td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2">Yearly  Stockouts Report For <?php echo $sel_year;?></td>
                        </tr>
                    </tbody>
                </table>



<tr>
<td colspan="2" bgcolor="#FFFFFF" width="100%">
 <form name="searchfrm" id="searchfrm" action="" method="post" onsubmit="return frmvalidate()">
<table width="99%">
<tr>
<td bgcolor="#FFFFFF"  valign="bottom" width="90px"> 
						<div class="sb1NormalFont">Year:</div>
                            <select name="year_sel" id="year_sel" class="input_select" style="width:60px">

                            <?php if ($paramTsel == 1   ) { ?>
                                <option value="">Select</option>  
                            <?php
                                }?>
                            <?php if ($paramTall == 1   ) { ?>
                               <option value="all">All</option>
                            <?php
                                }?>

                                  <?php                                   
                                  for ($j = date('Y'); $j >= 2010; $j--) {
                                    if ($sel_year == $j)
                                      $sel = "selected='selected'";
                                    else
                                      if ($j == date("Y"))
                                        $sel = "selected='selected'";
                                      else
                                        $sel = "";
                                    ?>
                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select></td>
<td bgcolor="#FFFFFF"  valign="bottom" width="90px"> 
						<div class="sb1NormalFont">MOS:</div> 
						<input type="text" name="in_MOS" id="in_MOS" class="input_select" value="<?php echo $in_MOS;?>" style="width:60px" onblur="frmvalidate();"/>
                         </td>						 
 <td bgcolor="#FFFFFF" width="165px">
						<div class="sb1NormalFont">Province/Region:</div>
                            <select name="prov_sel" id="prov_sel" class="input_select">
                               <option value="all">All</option>
                                   <?php
                                  $queryprov = "SELECT tbl_locations.PkLocID as prov_id, tbl_locations.LocName as prov_title
												FROM tbl_locations where LocLvl=2 and parentid is not null";
                                  $rsprov = mysql_query($queryprov) or die();
                                  while ($rowprov = mysql_fetch_array($rsprov)) 
								  {
                                    if ($prov_sel == $rowprov['prov_id'])
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select></td>		
<td bgcolor="#FFFFFF" width="120px">
						<div class="sb1NormalFont">Stakeholder:<?php //echo $sel_stk.'sdfsdf';?></div>
						
                        <select name="stk_sel" id="stk_sel" width="150px" class="input_select"">
                              <!-- <option value="0">All</option>-->
                                  <?php
                                  $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null order by stkorder";
                                  $rsstk = mysql_query($querystk) or die();
                                  while ($rowstk = mysql_fetch_array($rsstk)) 
								  {
                                    if ($stk_sel == $rowstk['stkid'])
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select> 
							</td>							
<td bgcolor="#FFFFFF" style="margin-left:20px;" valign="bottom">
						<input type="submit" name="go" id="go" value="GO" class="input_button" /></td>
</tr>   	
<tr>
<td colspan="5"  align="right">
<img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
<img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
</td>
</tr>
</table>
</form>
<?php
if(isset($_POST['go']))
{
	?>
	<table width="99%" cellpadding="0" cellspacing="0">
	<tr>
	<td>
	<div id="mygrid_container" style="width:100%; height:430px; background-color:white;"></div>
	</td>
	</tr>
	</table>
	<?php
}
?>
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

if(isset($_POST['go']))
{
	//print $xmlstore;
	writeXML('stockout_report.xml', $xmlstore);
}
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