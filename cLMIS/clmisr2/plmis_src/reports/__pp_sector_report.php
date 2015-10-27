<?php
	//ob_start();
	/***********************************************************************************************************
	Developed by  Munir Ahmed
	Email Id:    mnuniryousafzai@gmail.com
	This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district.
    The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
	/***********************************************************************************************************/
   /* $report_id = "SNASUM";*/
    $report_title = "Public Private Sector Report";
    /*$actionpage = "";
    $parameters = "T";
    $parameter_width = "40%";*/
	

 	include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
	Login();
	
	
	
	if(isset($_POST['go'])){
          
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
			$sel_month = $_POST['month_sel'];
	if (!empty($sel_month)){
		$reportMonth = date('F',mktime(0,0,0,$sel_month));	
	}else {
		$reportMonth = "";	
	}
		
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
			$sel_year = $_POST['year_sel'];
		
		if(isset($_POST['level_type']) && !empty($_POST['level_type']))
			$lvl_type = $_POST['level_type'];
		
		if(isset($_POST['province']) && !empty($_POST['province']))
			$province = $_POST['province'];
		
		if(isset($_POST['district']) && !empty($_POST['district']))
			$district = $_POST['district'];
		
		/*if(isset($_POST['field']) && !empty($_POST['field']))
			$field = $_POST['field'];*/
		
	
	}
?>
<?php startHtml($system_title." - Public Private Sector Report");?>
<!-- <script type="text/javascript" src="../../plmis_js/rhi.js"></script>-->
 <script language="javascript">
	function frmvalidate(){
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
	function functionCall(month, year, prod){
		window.location = "nationalreportSTK.php?month_sel="+month+"&year_sel="+year+"&item_sel="+prod;
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
			$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			$xmlstore .="<rows>\n";
			$queryPro = safe_query("SELECT itmrec_id,itm_name FROM `itminfo_tab` WHERE `itm_status`='Current' ORDER BY frmindex");
			$counter = 1;
			while($rsPro = mysql_fetch_array($queryPro))
			{
				$xmlstore .="\t<row id=\"$counter\">\n";
				
				/// If level type is national
				if ($lvl_type == 'national')
				{
					// For Public Sector
					$queryvals =  "SELECT REPgetData('CABMY','N','T','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";	
					$rsvals = mysql_query($queryvals) or die(mysql_error());
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
						 include("incl_data_render_pp.php");
					}
					
					// For Private Sector
					$queryvals = "SELECT REPgetData('CABMY','S','X','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());				
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 include("incl_data_render_pp.php");
					}
					
					$xmlstore .= "\t</row>\n";
				}
				
				/// If level type is provincial
				if ($lvl_type == 'provincial')
				{
					// For Public Sector
					$queryvals = "SELECT REPgetData('CABMY','R','TP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
						 include("incl_data_render_pp.php");
					}
					
					// For Private Sector
					$queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());				
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 include("incl_data_render_pp.php");
					}
					
					$xmlstore .= "\t</row>\n";
				}
				
				/// If level type is District
				if ($lvl_type == 'district')
				{
					// For Public Sector
					$queryvals = "SELECT REPgetData('CABMY','R','TPD','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',$district) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
						 include("incl_data_render_pp.php");
					}
					
					// For Private Sector
					$queryvals = "SELECT REPgetData('CABMY','R','XPD','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',$district) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());				
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 include("incl_data_render_pp.php");
					}
					
					$xmlstore .= "\t</row>\n";
				}
				
				/// If level type is Field
				if ($lvl_type == 'field')
				{
					// For Public Sector
					$queryvals = "SELECT REPgetData('CABMY','R','FP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
						 include("incl_data_render_pp.php");
					}
					
					// For Private Sector
					$queryvals = "SELECT REPgetData('CABMY','R','X','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
					$rsvals = mysql_query($queryvals) or die(mysql_error());				
					while($rowvals = mysql_fetch_array($rsvals)) {
						 
						 $monthNum = "\"$sel_month\"";
						 $yearNum = "\"$sel_year\"";
						 $productName = "\"$rsPro[itmrec_id]\"";
						 
						 $tmp = explode('*',$rowvals['Value']);
						 $sel_item = $rsPro['itmrec_id'];
						 $sel_stk = 0;
						 $sel_lvl = 1;
						 
						 include("incl_data_render_pp.php");
					}
					
					$xmlstore .= "\t</row>\n";
				}
				
				
				
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
		
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Public-Private Sector Report".' ('. date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?> </div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		
		mygrid.attachHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Product</div>,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Public</div>,#cspan,#cspan,#cspan,#cspan,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Private</div>,#cspan,#cspan,#cspan,#cspan");
		
		mygrid.attachHeader("<span></span>,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan");
		
		mygrid.setInitWidths("*,100,100,100,40,40,100,100,100,40,40");
		mygrid.setColAlign("left,right,right,right,center,center,right,right,right,center,center");
		//mygrid.setColSorting("str,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/pp_sector_report.xml");
	}
	
</script>
<script>
function showProvinces()
{
	var val = $('#level_type').val();
	var colId;
	var selectedVal = '';
	if (val == 'national')
	{
		$('#provincesCol').hide();
		$('#districtsCol').hide();
	}
	if (val == 'provincial')
	{
		$('#provincesCol').show();
		$('#districtsCol').hide();
		colId = 'provincesCol';
	}
	if (val == 'district' || val == 'field')
	{
		$('#provincesCol').show();$('#districtsCol').html('');
		$('#districtsCol').show();
		colId = 'provincesCol';
	}
	if (val == 'provincial' || val == 'district' || val == 'field')
	{
		<?php
//		if ( $_POST['go'] ){
		?>
			selectedVal = "pId=<?php echo $province;?>";
		<?php 
//		}
		?>
		
		$.ajax({
			type: 'POST',
			url: 'ajax_calls.php',
			data: "val="+val+"&"+selectedVal,
			success: function(data) {
				$("#"+colId).html(data);
			}
		});
		
		<?php
		if ( $_POST['go'] ){
		?>
			showDistricts();
		<?php 
		}
		?>
		
		/*if ( $('#province').val() != '' )
		{
			showDistricts();
		}*/
	}
}

function showDistricts()
{
	if ( $('#level_type').val() == 'district' || $('#level_type').val() == 'field' )
	{
		//alert("dId=<?php echo $district;?>&provinceId=<?php echo $province;?>");
		//alert($('#level_type').val());
		var val = $('#province').val();
		
		var data = '';
		<?php
		if ( $_POST['go'] ){
		?>
		
		//{
			data = "dId=<?php echo $district;?>&provinceId=<?php echo $province;?>";			
		//}
		if (val!='')
		//{

			data = "provinceId="+val;			
			//alert(data);
		//}
		
		<?php 
		}
		else
		{
		?>
			data = "provinceId="+val;
		<?php
		}
		?>
		//alert(data);
		
		$.ajax({
			type: 'POST',
			url: 'ajax_calls.php',
			data: data,
			success: function(data) {
				$("#districtsCol").html(data);
			}
		});
	}
}

// Form validation
function formValidate()
{
	if( $('#month_sel').val() == '' )
	{
		alert('Select month.');
		$('#month_sel').focus();
		$('#month_sel').css('border', '1px solid red');
		return false;
	}else
	{
		$('#month_sel').css('border', '1px solid #D1D1D1');
	}
	if( $('#year_sel').val() == '' )
	{
		alert('Select year.');
		$('#year_sel').focus();
		$('#year_sel').css('border', '1px solid red');
		return false;
	}else
	{
		$('#year_sel').css('border', '1px solid #D1D1D1');
	}
	if( $('#level_type').val() == '' )
	{
		alert('Select level.');
		$('#level_type').focus();
		$('#level_type').css('border', '1px solid red');
		return false;
	}
	else
	{
		$('#level_type').css('border', '1px solid #D1D1D1');
	}
	if( $('#level_type').val() != '' )
	{
		if( $('#province').val() == '' )
		{
			alert('Select province.');
			$('#province').focus();
			$('#province').css('border', '1px solid red');
			return false;
		}
		else
		{
			$('#province').css('border', '1px solid #D1D1D1');
		}
	}
}

</script>

 </head>
 	
  <body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid();<?php if ( $_POST['go'] ){?>showProvinces();showDistricts();<?php }?>">
  
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content" align=""><br>
		<?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
		<table width="100%">
			<tr>
		        <td colspan="2">
		         	<?php //include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
					
					
					
					<form method="post" action="" id="searchfrm" name="searchfrm" onSubmit="return formValidate()">
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr height="34">
									<td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2"><? echo 'Public Private Sector Report'.' '.$reportMonth.' '.$sel_year;?></td>
								</tr>
							</tbody>
						</table>
						
						<table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:-2px">
							<tbody>
								<tr bgcolor="#FFFFFF">
									<td style="padding-right:20px; padding-top: 10px;font-family: Arial, Verdana, Helvetica, sans-serif; 	color: #444444; 	font-size: 12px;" colspan="2"><br><span class="sb1NormalFont">MOS: </span><div style="display:inline-block;margin-left:5px;">Stock Out</div><div style="display:inline-block;width:15px; height:12px; background-color:#ff370f;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Under Stock</div><div style="display:inline-block;width:15px; height:12px; background-color:#0000ff;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Satisfactory</div><div style="display:inline-block;width:15px; height:12px; background-color:#008000;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Over Stock</div><div style="display:inline-block;width:15px; height:12px; background-color:#6bceff;margin-left:5px;"></div> <br><br>   </td>
								</tr>
								<tr>
									<td width="100%" bgcolor="#FFFFFF" colspan="2">				
									<table style="width:auto" height="28" cellspacing="0" cellpadding="0" border="0">
										<tbody>
										<tr bgcolor="#FFFFFF">				
											<td width="100px" bgcolor="#FFFFFF" class="sb1NormalFont"><strong>Filter by:</strong></td>
											<td bgcolor="#FFFFFF" class="sb1NormalFont">Month:</td>
											<td bgcolor="#FFFFFF">				
												<select class="input_select" id="month_sel" name="month_sel">
					                                <option value="">Select</option>
													<?php
													for ($i = 1; $i <= 12; $i++) {
													?>
													<option value="<?php echo $i; ?>" <?php echo ($i == $sel_month) ? 'selected=selected' : '';?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
													<?php
													}
													?>
										        </select>
											</td>				
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
											<td bgcolor="#FFFFFF" class="sb1NormalFont">Level:</td>
											<td bgcolor="#FFFFFF"> 
												<select class="input_select" id="level_type" name="level_type" onChange="showProvinces()">
					                                <option value="national" <?php echo ($lvl_type == national) ? 'selected=selected' : '';?>>National</option>
													<option value="provincial" <?php echo ($lvl_type == provincial) ? 'selected=selected' : '';?>>Provincial</option>
													<option value="district" <?php echo ($lvl_type == district) ? 'selected=selected' : '';?>>District</option>
													<option value="field" <?php echo ($lvl_type == field) ? 'selected=selected' : '';?>>Field</option>
										        </select>
											</td>
											<td id="provincesCol" bgcolor="#FFFFFF" style="display:none;"></td>
											<td id="districtsCol" bgcolor="#FFFFFF" style="display:none;"></td>
											
											
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
		//print "wasif".$xmlfile_path;
	}

	if(isset($_POST['go']))
	{
		writeXML('pp_sector_report.xml', $xmlstore);
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