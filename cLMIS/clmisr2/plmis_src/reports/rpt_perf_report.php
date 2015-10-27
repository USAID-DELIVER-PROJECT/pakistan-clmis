<?php

/***********************************************************************************************************
Developed by  Wasif Raza
Email Id:    wasif@deliver-pk.org
This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district. The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
/***********************************************************************************************************/

$report_title = "District Montlhy Data Reporting Performance";
$period=7;
$sel_year=2013;


include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	

if(isset($_POST['go']))
{
	if(isset($_POST['period']) && !empty($_POST['period']))
		$period = $_POST['period'];
	
	if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
		$sel_year = $_POST['year_sel'];	
		
	if(isset($_POST['prov_sel']) && !empty($_POST['prov_sel']))
		{
		if($_POST['prov_sel']!='all')
			$prov_sel = $_POST['prov_sel'];	
		else
			$prov_sel = 0;
		}
}
?>

<?php startHtml($system_title." - CYP Report");?>
<head>

<script language="javascript">
function frmvalidate(){
	if(document.getElementById('period').value==''){
		alert('Please Select Period');
		document.getElementById('period').focus();
		return false;
	}
	if(document.getElementById('year_sel').value==''){
		alert('Please Select Year');
		document.getElementById('year_sel').focus();
		return false;
	}
}

function functionCall(getvari){
		var url;
		var title = "Districts reporting Performance";
		var h=450;
		var w=650;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/3)-(h);
		url = "rpt_perf_subreport.php?"+getvari;
		window.open(url, title, 'toolbar=no, location=no, directories=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
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
	
	if ($period <= 1) 
	{
		$dtf=$sel_year.'-01-01';
		$dtt=$sel_year.'-03-01';
		$stcol=3;
		$stm=1;
	}
	
	if ($period == 2) 
	{
		$dtf=$sel_year.'-04-01';
		$dtt=$sel_year.'-06-01';
		$stcol=3;
		$stm=4;
	}
	
	if ($period == 3) 
	{
		$dtf=$sel_year.'-07-01';
		$dtt=$sel_year.'-09-01';
		$stcol=3;
		$stm=7;
	}
	
	if ($period == 4) 
	{
		$dtf=$sel_year.'-10-01';
		$dtt=$sel_year.'-12-01';
		$stcol=3;
		$stm=10;
	}
	
	if ($period == 5) 
	{
		$dtf=$sel_year.'-01-01';
		$dtt=$sel_year.'-06-01';
		$stcol=6;
		$stm=1;
	}
	
	if ($period == 6) 
	{
		$dtf=$sel_year.'-07-01';
		$dtt=$sel_year.'-12-01';
		$stcol=6;
		$stm=7;
	}
	
	if ($period >= 7) 
	{
		$dtf=$sel_year.'-01-01';
		$dtt=$sel_year.'-12-01';
		$stcol=12;
		$stm=1;
	}
	
	$dttf= date("Y-m-t", strtotime($dtt));

$head="<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>District Montlhy Data Reporting Performance : $dtf / $dttf </div>";
	
if ($prov_sel==0) $head.=",#cspan";
	
$head1="<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Location</div>,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Total</div>";

//if ($prov_sel==0) $head1.=",<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Total</div>";

	$colwidths="125,*";
	$colaligns="left,right";
	$colTypes="ro,ro";
	$lasttype=0;
	
	for($i=$stm;$i<$stm+$stcol;$i++)
	{
		$dt="2013-".$i."-01";
		$monName=date('M', strtotime($dt));
		$head.=",#cspan";
		$head1.=",<span title='$monName'>$monName</span>";
		//$colwidths.=",150";
		$colaligns.=",right";
		$colTypes.=",ro";
	} 
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$queryPro = "SELECT tbl_locations.PkLocID, tbl_locations.LocName FROM tbl_locations 
				WHERE tbl_locations.LocLvl = 2 and ParentID is not null";
	$queryPro = mysql_query($queryPro) or die(mysql_error());
	$counter = 1;
	$tot=0;
	$StkType=0;
	$notincludedStk="9";
	while($rsPro = mysql_fetch_array($queryPro))
	{
		$tot=0;
		
		$prov_sel=$rsPro[PkLocID];
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell>$rsPro[LocName]</cell>\n";	
			
		$queryPro1 ="SELECT count(PkLocID) as vali FROM tbl_locations where ParentID=".$rsPro[PkLocID];
		$queryProz = mysql_query($queryPro1) or die(mysql_error());
		$rsProz = mysql_fetch_array($queryProz);							
		$xmlstore .="\t\t<cell>".$rsProz[vali]."</cell>\n";

			for($i=$stm;$i<$stm+$stcol;$i++)
			{
				$tot=0;
				 $tdt=$sel_year.'-'.$i.'-01';
				
				$disttot=0;
				$queryvals1 =  "SELECT PkLocID FROM tbl_locations where ParentID=$rsPro[PkLocID]";
				$rsvals1 = mysql_query($queryvals1) or die(mysql_error());
				while($rowvals1 = mysql_fetch_array($rsvals1))
				{
					$queryPro2 ="Select IsAllWhRptedInDistrict(".$rowvals1['PkLocID'].",".$StkType.",'".$notincludedStk."','".$tdt."') as Valiz from DUAL";
					//if($rowvals1['PkLocID']==103)  print $queryPro2;
					$queryProzz = mysql_query($queryPro2) or die(mysql_error());
					$rsProzz = mysql_fetch_array($queryProzz);							
					//$disttot.="[".$rowvals1['PkLocID']."|".$rsProzz[Valiz]."]";
					$disttot+=$rsProzz[Valiz];

				}
				$tempVar="nonstk=$notincludedStk&stktype=$StkType&prov=$prov_sel&Dt=$tdt";	
				$xmlstore .="\t\t<cell><![CDATA[<a href=javascript:functionCall('$tempVar')>".$disttot."</a>]]>^_self</cell>\n";
				$disttot=0;
			}
		
		$xmlstore .= "\t</row>\n";
		$counter++;
	}
	$xmlstore .="</rows>\n";
}
?>

<script>
var mygrid;
function doInitGrid()
{
	mygrid = new dhtmlXGridObject('mygrid_container');
	mygrid.selMultiRows = true;
	mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");	
	mygrid.setHeader("<?php echo $head;?>");
	mygrid.attachHeader("<?php echo $head1;?>");
	//mygrid.attachHeader("<?php echo $SubHead;?>");
	mygrid.setInitWidths("<?php echo $colwidths;?>");
	mygrid.setColAlign("<?php echo $colaligns;?>");
	mygrid.setColTypes("<?php echo $colTypes;?>");
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.loadXML("xml/rpt_pref_report.xml");
}
</script>


</head>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;
 margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();">


<?php include "../../plmis_inc/common/top.php";?>
<div class="body_sec">
<div class="wrraper" style="height:auto; padding-left:5px">
<div class="content" align=""><br>
<?php  showBreadCrumb();?><br>
<table width="100%">
<tr>
<td colspan="2">
<form method="post" action="" id="searchfrm" name="searchfrm" onsubmit="return formValidate()">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr height="34">
<td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2"><? echo "District's Data Reporting Performance".' '.$dtf.' / '.$dttf;?></td>
</tr>
</tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:-2px">
<tbody>
<tr>
<td width="100%" bgcolor="#FFFFFF" colspan="2">		
<br />		
<table style="width:auto" height="28" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr bgcolor="#FFFFFF">				
<td width="100px" bgcolor="#FFFFFF" class="sb1NormalFont"><strong>Filter by:</strong></td>
<td bgcolor="#FFFFFF" width="90px"> 
<div class="sb1NormalFont">Year:</div>
<select id="year_sel" name="year_sel" class="input_select">
<option value="">Select</option>
<?php
for ($j = date('Y'); $j >= 2010; $j--){
	?>
	<option value="<?php echo $j; ?>" <?php echo ($j == $sel_year) ? 'selected=selected' : '';?>><?php echo $j; ?></option>
	<?php
}
?>
</select>
</td>				

<td bgcolor="#FFFFFF" width="150px"> 
<div class="sb1NormalFont">Period:</div>
<SELECT NAME = "period" width="200px" class="input_select">
<optgroup label = "Quarter">
<OPTION VALUE = "1" <?php echo ($period == '1') ? 'selected=selected' : '';?>>First Quarter</OPTION>

<OPTION VALUE = "2" <?php echo ($period == '2') ? 'selected=selected' : '';?>>Second Quarter</OPTION>

<OPTION VALUE = "3" <?php echo ($period == '3') ? 'selected=selected' : '';?>>Third Quarter</OPTION>

<OPTION VALUE = "4" <?php echo ($period == '4') ? 'selected=selected' : '';?>>Fourth Quarter</OPTION>
</optgroup>

<optgroup label = "Half">
<OPTION VALUE = "5" <?php echo ($period == '5') ? 'selected=selected' : '';?>>First Half</OPTION>

<OPTION VALUE = "6" <?php echo ($period == '6') ? 'selected=selected' : '';?>>Second Half</OPTION>
</optgroup>

<optgroup label = "Annual">
<OPTION VALUE = "7" <?php echo ($period == '7') ? 'selected=selected' : '';?>>Annual</OPTION>
</optgroup
>
</SELECT>

</td>

 <td bgcolor="#FFFFFF" width="165px">
	<!--<div class="sb1NormalFont">Province/Region:</div>
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
        </select>-->
</td>	

<td bgcolor="#FFFFFF"><input type="submit" class="input_button" value="GO" id="go" name="go"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>											 
</form>



</td>
</tr>
<?php 
if(isset($_POST['go']))
{
	?>
	<tr>
	<td class="sb1NormalFont">
	
	</td>
	<td align="right">
	<img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
	<img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" title="Export to Excel" />
	</td>
	</tr>
	<?php
}
?>
</table>

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
	writeXML('rpt_pref_report.xml', $xmlstore);
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