<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
$act=2;
$strDo = "Add";
$nwharehouseId =0;
$nstkId =0;
$stkOfficeId="";
$dist_id=0;
$prov_id=0;
$stkid=0;
$wh_type_id=0;
$whType = '';
$stkname="";
$test='false';

include("xml/xml_generation_warehouse.php");

if (!ini_get('register_globals')) {
  $superglobals = array( $_GET,  $_POST, $_COOKIE, $_SERVER );
  if (isset ($_SESSION)) {
	array_unshift($superglobals, $_SESSION);
  }
  foreach ($superglobals as $superglobal) {
	extract($superglobal, EXTR_SKIP);
  }
  ini_set('register_globals', true);
}

$sql=mysql_query("SELECT
		sysuser_tab.UserID,
		sysuser_tab.stkid,
		sysuser_tab.province,
		stakeholder.stkname AS stkname,
		province.LocName AS provincename
		FROM
		sysuser_tab
		Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
		Left Join tbl_locations AS province ON sysuser_tab.province = province.PkLocID
		WHERE sysuser_tab.UserID='".$_SESSION['userid']."'");
	
$sql_row=mysql_fetch_array($sql);
$stakeholder12=$sql_row['stkid'];
$provinceidd1=$sql_row['province'];
$stkname1=$sql_row['stkname'];
$provincename1=$sql_row['provincename'];

if($stakeholder12=='-1' && $provinceidd1=='-1'){
	$test='true';
}

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}


if($strDo == "Delete" )
{
	$objwarehouse->m_npkId=$nstkId; 	
	$objwarehouse->Deletewarehouse();
	
	$_SESSION['err']['text'] = 'Data has been successfully deleted.';
	$_SESSION['err']['type'] = 'success';
	//header("location:ManageWarehouse.php");
	echo '<script>window.location="ManageWarehouse.php"</script>';
	exit;
}
if($strDo == "im_allowed" )
{
	$objwarehouse->m_npkId=$nstkId; 	
	$objwarehouse->allowIM($nstkId);
	exit;
	//header("location:ManageWarehouse.php");
}


if($strDo == "Edit")
{

	$objwarehouse->m_npkId=$nstkId;

  $objWhData->m_wh_id = $nstkId;
  $isDataAvailable = $objWhData->getDataByWhId();

	$rswarehouse=$objwarehouse->GetWarehouseById(); 
	if($rswarehouse!=FALSE && mysql_num_rows($rswarehouse)>0)
	{
		$RowEditStk = mysql_fetch_object($rswarehouse);
		$stkid=$RowEditStk->stkid;
		$stkname=$RowEditStk->stkname;
		$wh_name=$RowEditStk->wh_name;
		$stkOfficeId = $RowEditStk->stkofficeid;
		$_SESSION['stkOfficeId'] = $stkOfficeId;
		$prov_id = $RowEditStk->prov_id;
		$dist_id = $RowEditStk->dist_id;
		$_SESSION['dist_id'] = $dist_id;
		$_SESSION['pk_id'] = $nstkId;
		$province=$RowEditStk->province;
		$district=$RowEditStk->district;
		$whType=$RowEditStk->hf_type_id;
		if($stkOfficeId!='')
		{
			$objstk->m_npkId=$stkOfficeId;
			$stkOffice=$objstk->get_stakeholder_name();
		}
	}
}

if($stakeholder12=='-1' && $provinceidd1=='-1')
{
	$objwarehouse1 = $objwarehouse->GetAllWarehouses0();
}
else
{
	$objwarehouse->m_stkid=$stakeholder12;
	$objwarehouse->m_provid=$provinceidd1;
	$objwarehouse1 = $objwarehouse->GetAllWarehouses1();
}

$rsStakeholders = $objstk->GetAllStakeholders();
$rsWHTypes = $objwarehouse->GetWHTypes();

$objloc->LocLvl=2;
$rsloc=$objloc->GetAllLocations();

//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/operations/".$basename;

//////// GET Read Me Title From DB. 

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
$readMeTitle = $qryResult['extra'];
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
	<div class="page-container">
	<?php include "template/menu.php";?>
	
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title row-br-b-wp">Warehouse Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Warehouse</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManagewarehouseAction.php" name="managewarehouses" id="managewarehouses">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Stakeholder<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="Stakeholders" id="Stakeholders" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
														while($RowGroups = mysql_fetch_object($rsStakeholders))
														{
                                                        ?>
                                                        <option value="<?=$RowGroups->stkid?>" <?php echo ($stkid == $RowGroups->stkid) ? 'selected' : '';?>><?=$RowGroups->stkname?></option>
                                                        <?php
														}
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Office Type<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="StakeholdersOffices" id="StakeholdersOffices" class="form-control input-medium">
                                                        	<option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Province<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="Provinces" id="Provinces" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
                                                        while($RowLoc = mysql_fetch_object($rsloc))
                                                        {
                                                        ?>
                                                        <option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$prov_id) echo 'selected="selected"';?>><?=$RowLoc->LocName?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>District<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="districts" id="districts" class="form-control input-medium">
                                                        	<option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Warehouse / Store Name<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input  name="wh_name" id="wh_name" type="text" value="<?php echo $wh_name; ?>" size="30" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Warehouse / Store Type</label>
                                                    <div class="controls">
                                                        <select name="wh_type" id="wh_type" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
														while($row = mysql_fetch_object($rsWHTypes))
														{
                                                        ?>
                                                        <option value="<?=$row->pk_id?>" <?php echo ($whType == $row->pk_id) ? 'selected' : '';?>><?=$row->hf_type?></option>
                                                        <?php
														}
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php /*?><div class="col-md-2">
                                                <div class="control-group">
                                                	<label>Start Month</label>
                                                    <div class="controls">
                                                        <select name="month" id="month" class="form-control input-small">
                                                            <option value="1" <?php if(date("n") == 1) { ?>selected <?php } ?>>January</option>
                                                            <option value="2" <?php if(date("n") == 2) { ?>selected <?php } ?>>February</option>
                                                            <option value="3" <?php if(date("n") == 3) { ?>selected <?php } ?>>March</option>
                                                            <option value="4" <?php if(date("n") == 4) { ?>selected <?php } ?>>April</option>
                                                            <option value="5" <?php if(date("n") == 5) { ?>selected <?php } ?>>May</option>
                                                            <option value="6" <?php if(date("n") == 6) { ?>selected <?php } ?>>June</option>
                                                            <option value="7" <?php if(date("n") == 7) { ?>selected <?php } ?>>July</option>
                                                            <option value="8" <?php if(date("n") == 8) { ?>selected <?php } ?>>August</option>
                                                            <option value="9" <?php if(date("n") == 9) { ?>selected <?php } ?>>September</option>
                                                            <option value="10" <?php if(date("n") == 10) { ?>selected <?php } ?>>October</option>
                                                            <option value="11" <?php if(date("n") == 11) { ?>selected <?php } ?>>November</option>
                                                            <option value="12" <?php if(date("n") == 12) { ?>selected <?php } ?>>December</option>
                                                     	</select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="control-group">
                                                	<label>Start Year</label>
                                                    <div class="controls">
                                                        <select name="year" id="year" class="form-control input-small">
															<?php for($i = 2010 ; $i <= date("Y"); $i++) { ?>
                                                            <option value="<?php echo $i; ?>" <?php if(date("Y") == $i) { ?> selected <?php }?>><?php echo $i; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div><?php */?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12 right">
                                                <div class="control-group">
                                                	<label>&nbsp;</label>
                                                    <div class="controls">
                                                        <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
                                                        <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
                                                        <input type="submit" value="<?=$strDo?>" class="btn btn-primary" />            
                                                        <input name="btnAdd" type="button" id="btnCancel" value="Cancel" class="btn btn-info" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            					</form>
							</div>
						</div>
					</div>
				</div>
                <div class="row">
					<div class="col-md-12">
						<div class="widget">
							<div class="widget-head">
								<h3 class="heading">All Warehouses</h3>
							</div>
							<div class="widget-body">
                                <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td style="text-align:right;">
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(5,true); mygrid.setColumnHidden(6,true);mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');mygrid.setColumnHidden(5,false); mygrid.setColumnHidden(6,false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(5,true); mygrid.setColumnHidden(6,true);mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');mygrid.setColumnHidden(5,false); mygrid.setColumnHidden(6,false);" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><div id="mygrid_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div></td>
                                    </tr>
                                    <tr>
                                    	<td><div id="recinfoArea"></div></td>
                                    </tr>
                                </table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
<?php include "template/footer.php";?>
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
<!-- <link rel="stylesheet" type="text/css" href="lightbox/themes/default/jquery.lightbox.css" />-->

<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>	
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../plmis_src/operations/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>

<script type="text/javascript">
	<?php if($test=='true'){ ?>
	// JavaScript Document
	$(document).ready(function() {
		showOfficeTypes();
		showDistricts();
		$("select#Stakeholders").change(function(){
			// if changed after last element has been selected, will reset last boxes choice to default
			showOfficeTypes();
		});
	
		$("select#Provinces").change(function(){
			showDistricts();
		});
		$("select#districts").change(function(){
			$("select#Warehouses").html("<option value=''>Please wait...</option>");
			var bid = $("select#districts option:selected").attr('value');
			var pid = $("select#StakeholdersOffices option:selected").attr('value');
	
			$.post("getfromajax.php", {
				ctype : 5, id : bid, id2 : pid
			}, function(data){
				$("select#Warehouses").html(data);
			});
		});
	}
	
	//  ---------------------------------------
	
	);
		<?php } else { ?>
	$(document).ready(function() {
		
		   var pid = <?php echo $stakeholder12; ?>;
	   
		   $.post("getfromajax.php", {ctype:1,id:pid}, function(data){
		 
		   $("#StakeholdersOffices").html(data);
	   });
	   
		 var bid = <?php echo $provinceidd1; ?>;
	
		  $.post("getfromajax.php", {ctype:8,id:bid}, function(data){
		   
		   $("#districts").html(data);
	  
	   });
	   
	  });
	  <?php } ?>
	function imAllowedFunction(val, name){
		if (confirm("Are you sure you want to change Inventory Managment settings for this warehouse?")){
			//window.location="ManageWarehouse.php?Do=im_allowed&Id="+val;
			$.post("ManageWarehouse.php", {Do:'im_allowed', Id:val}, function(data){
				//$("#districts").html(data);
			});
		}
		else
		{
			$('input[name="'+name+'"]').attr('checked', 'checked');
		}
	}
	function showOfficeTypes()
	{
		var pid = $("select#Stakeholders option:selected").attr('value');
		if (pid == '')
		{
			$("select#Warehouses").html("<option value=''>Select</option>");
			return false;
		}
		$("select#Warehouses").html('<option value="" selected="selected">Choose...</option>');
			$("select#StakeholdersOffices").html("<option>Please wait...</option>");
			$("select#Warehouses").html("<option value=''>Please wait...</option>");
			$.post("getfromajax.php", {
				ctype : 1, id : pid
			}, function(data){
				$("select#StakeholdersOffices").html(data);
			});
	}
	function showDistricts()
	{
		var bid = $("select#Provinces option:selected").attr('value');
		if (bid == '')
		{
			$("select#districts").html("<option value=''>Select</option>");
			return false;
		}
		$("select#districts").html("<option value=''>Please wait...</option>");

		$.post("getfromajax.php", {
			ctype : 8, id : bid
		}, function(data){
			$("select#districts").html(data);
		});
	}
	
	function editFunction(val){
		window.location="ManageWarehouse.php?Do=Edit&Id="+val;
	}
	function delFunction(val){
		if (confirm("Are you sure you want to delete the record?")){
			window.location="ManageWarehouse.php?Do=Delete&Id="+val;
		}	
	}
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Stakeholder'>Stakeholder</span>,<span title='Office type'>Office Type</span>,<span title='Province'>Province</span>,<span title='District'>District</span>,<span title='Warehouse'>Warehouse</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
		mygrid.attachHeader("#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,,");
		mygrid.setInitWidths("130,*,150,200,200,30,30");
		mygrid.setColAlign("left,left,left,left,left,center,center")
		mygrid.setColSorting("str,str,str,str,str");
		mygrid.setColTypes("ro,ro,ro,ro,ro,img,img");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.enablePaging(true, 10, 6, "recinfoArea");
		mygrid.setPagingSkin("toolbar", "dhx_skyblue");
		
		mygrid.init();
		mygrid.loadXML("xml/warehouse.xml");
	}
</script>
<?php
if (isset($_SESSION['err'])) {
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: '<?php echo $_SESSION['err']['text'];?>',
			type: '<?php echo $_SESSION['err']['type'];?>',
			layout: self.data('layout')
		});
	</script>
<?php 
	unset($_SESSION['err']);
} ?>
</body>
</html>