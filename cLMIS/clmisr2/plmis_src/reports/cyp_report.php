<?php

/***********************************************************************************************************
Developed by  Wasif Raza
Email Id:    wasif@deliver-pk.org
This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district. The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
/***********************************************************************************************************/

$report_title = "CYP Report";
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
	}
	
	if ($period == 2) 
	{
		$dtf=$sel_year.'-04-01';
		$dtt=$sel_year.'-06-01';
	}
	
	if ($period == 3) 
	{
		$dtf=$sel_year.'-07-01';
		$dtt=$sel_year.'-09-01';
	}
	
	if ($period == 4) 
	{
		$dtf=$sel_year.'-10-01';
		$dtt=$sel_year.'-12-01';
	}
	
	if ($period == 5) 
	{
		$dtf=$sel_year.'-01-01';
		$dtt=$sel_year.'-06-01';
	}
	
	if ($period == 6) 
	{
		$dtf=$sel_year.'-07-01';
		$dtt=$sel_year.'-12-01';
	}
	
	if ($period >= 7) 
	{
		$dtf=$sel_year.'-01-01';
		$dtt=$sel_year.'-12-01';
	}
	
	$dttf= date("Y-m-t", strtotime($dtt));

	$head="<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>CYP Report $dtf / $dttf </div>,#cspan,#cspan,#cspan";
	$head1="<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Product</div>,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Public</div>,#cspan,#cspan,#cspan,#cspan,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Private</div>,#cspan,#cspan,#cspan,#cspan,#cspan";
	
	$SubHead="<span></span>";
	$colwidths="*,70,70,70";
	$colaligns="left,right,right,right";
	$colTypes="ro,ro,ro,ro";
	$lasttype=0;
	
	$queryvals0 ="SELECT stkname,stk_type_id FROM stakeholder WHERE ParentID is null ORDER BY stk_type_id,stkid";	
	$rsvals0 = mysql_query($queryvals0) or die(mysql_error());
	while($rowvals0 = mysql_fetch_array($rsvals0))
	{
		$stkType=(int)$rowvals0['stk_type_id'];
		$stkName=$rowvals0['stkname'];
		$head.=",#cspan";
		$head1.=",#cspan";
		
		$colwidths.=",70";
		$colaligns.=",right";
		$colTypes.=",ro";
		if ($lasttype!=$stkType)
		{
			$SubHead.=",<span title='SBTotal'>Sub Total</span>";
		}
		$SubHead.=",<span title='$stkName'>$stkName</span>";
		$lasttype=$stkType;
		} 
	$SubHead.=",<span title='SPTotal'>Sub Total</span>,<span title='TOTCYP'>Total CYP</span>";
	
	
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$queryPro = "SELECT itmrec_id,itm_name FROM `itminfo_tab` WHERE `itm_status`='Current' ORDER BY frmindex";
	$queryPro = mysql_query($queryPro) or die(mysql_error());
	$counter = 1;
	$tot=0;
	while($rsPro = mysql_fetch_array($queryPro))
	{
		$tot=0;
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
		
		$queryvals ="SELECT stakeholder.stkid, stakeholder.stkname, stakeholder_type.stk_type_descr,stakeholder.stk_type_id
		FROM stakeholder
		INNER JOIN stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id 
		WHERE ParentID is null ORDER BY stakeholder.stk_type_id,stakeholder.stkid";	
		$rsvals = mysql_query($queryvals) or die(mysql_error());
		$lasttype=0;

		while($rowvals = mysql_fetch_array($rsvals)) 
		{				
			$stkType=(int)$rowvals['stk_type_id'];
			//print $rsPro[itm_name]." ".$rowvals['stkname']." ".$lasttype.":".$stkType."<br />";
			if($lasttype!=$stkType)
			{
				$xmlstore .="\t\t<cell>".number_format($tot)."</cell>\n";
				$gtot=$tot;
				$tot=0;
			}
		
			$queryvals1 =  "SELECT REPgetCYPDT('FS','".$dtf."','".$dtt."',".$rowvals['stkid'].",0,0,'".$rsPro['itmrec_id']."') as Value FROM DUAL";
			$rsvals1 = mysql_query($queryvals1) or die(mysql_error());
			while($rowvals1 = mysql_fetch_array($rsvals1))
			{
				$vali=(double)$rowvals1['Value'];
				$xmlstore .="\t\t<cell>".number_format($vali)."</cell>\n";
				$tot+=$rowvals1['Value'];
			}
			$lasttype=$stkType;
		}
		$xmlstore .="\t\t<cell>".number_format($tot)."</cell>\n";
		$gtot+=$tot;
		$xmlstore .="\t\t<cell>".number_format($gtot)."</cell>\n";			
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
	mygrid.attachHeader("<?php echo $head1;?>");
	mygrid.attachHeader("<?php echo $SubHead;?>");
	mygrid.setInitWidths("<?php echo $colwidths;?>");
	mygrid.setColAlign("<?php echo $colaligns;?>");
	mygrid.setColTypes("<?php echo $colTypes;?>");
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	
	mygrid.setSkin("light");
	mygrid.init();
	mygrid.loadXML("xml/cyp_report.xml");
}

</script>


</head>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();">


<?php include "../../plmis_inc/common/top.php";?>
<div class="body_sec">

<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
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
<td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2"><? echo 'CYP Report'.' '.$dtf.' / '.$dttf;?></td>
</tr>
</tbody>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:-2px">
<tbody>
<tr>
<td width="100%" bgcolor="#FFFFFF" colspan="2">				
<table style="width:auto" height="28" cellspacing="0" cellpadding="0" border="0">
<tbody>
<tr bgcolor="#FFFFFF">				
<td width="100px" bgcolor="#FFFFFF" class="sb1NormalFont"><strong>Filter by:</strong></td>

<td bgcolor="#FFFFFF" class="sb1NormalFont">Year:</td>
<td bgcolor="#FFFFFF"> 
<select class="input_select" id="year_sel" name="year_sel">
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
<td bgcolor="#FFFFFF" class="sb1NormalFont">Period:</td>
<td bgcolor="#FFFFFF"> 
<SELECT NAME = "period" style = "width:200">
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
	writeXML('cyp_report.xml', $xmlstore);
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