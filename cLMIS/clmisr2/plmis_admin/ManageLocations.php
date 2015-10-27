<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");


// Ajax call for districts
if(isset($_REQUEST['id']))
{
	$qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_locations
			WHERE tbl_locations.LocLvl = 3 AND tbl_locations.ParentID = '".$_REQUEST['id']."'
			ORDER BY tbl_locations.LocName";
	$qryRes = mysql_query($qry);
	while ( $row = mysql_fetch_array($qryRes) )
	{
	?>
	<option value="<?php echo $row['PkLocID'];?>" <?php echo ($_SESSION['ParentID'] == $row['PkLocID']) ? 'selected=selected' : ''?>><?php echo $row['LocName'];?></option>
	<?php
	}
	exit;
}


$act=2;
$strDo = "Add";
$nwharehouseId =0;
$nstkId =0;
$stkOfficeId="";
$dist_id=0;
$prov_id=0;
$stkid=0;
$wh_type_id=0;
$stkname="";
$test='false';

include("xml/xml_generation_location.php");

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

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}
if (isset($_SESSION['pk_id']))
{
	unset($_SESSION['pk_id']);
}
if($strDo == "Edit")
{
	$objloc->PkLocID=$nstkId;
	$rsloc=$objloc->GetLocationById();
	$RowEditStk = mysql_fetch_object($rsloc);
	$location_level=$RowEditStk->LocLvl;
	$location_type=$RowEditStk->LocType;
	$ParentID=$RowEditStk->ParentID;
	$location_name = $RowEditStk->LocName;
	$province=$RowEditStk->Province;
	$_SESSION['pk_id'] = $nstkId;
	
	$_SESSION['loc_type'] = $location_type;
	$_SESSION['ParentID'] = $ParentID;
	
}
if($strDo == "Delete")
{
	$objloc->PkLocID = $nstkId;
	$objloc->DeleteLocation();
	
	$_SESSION['err']['text'] = 'Data has been successfully deleted.';
	$_SESSION['err']['type'] = 'success';
	//header("location:ManageLocations.php");
	echo "<script>window.location='ManageLocations.php'</script>";
	exit;
}


//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/operations/".$basename;

//////// GET Read Me Title From DB. 

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
$readMeTitle = $qryResult['extra'];


$objloc->LocLvl=2;
$rsloc=$objloc->GetAllLocations();
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
						<h3 class="page-title row-br-b-wp">Location Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Location</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManageLocationAction.php" name="managelocation" id="managelocation">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Location Level<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                    	<select name="loc_level" id="loc_level" class="form-control input-medium">
                                                        	<option value="">Select</option>
                                                        <?php
                                                        $strSql = "SELECT * FROM tbl_dist_levels WHERE lvl_id IN (3,4)";
                                                        $rsSql = mysql_query($strSql);
                                                        if(mysql_num_rows($rsSql)>0)
                                                        {
															while($RowLoc2 = mysql_fetch_array($rsSql))
															{
															?>
																<option value="<?php echo $RowLoc2['lvl_id'];?>" <?php if($RowLoc2['lvl_id']==$location_level) echo 'selected="selected"';?>><?php echo $RowLoc2['lvl_name'];?></option>
															<?php
															}
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Province<font color="#FF0000">*</font></label>
                                                    <div class="controls">
														<select name="provinces" id="provinces" class="form-control input-medium">
                                                            <option value="">Select</option>
                                                            <?php
                                                            if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
                                                            {
																while($RowLoc = mysql_fetch_object($rsloc))
																{
																?>
																<option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$province) echo 'selected="selected"';?>><?=$RowLoc->LocName?></option>
																<?php
																}
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Location Type<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="loc_type" id="loc_type" class="form-control input-medium">
                                                        	<option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="districts" style="display:none">
                                                <div class="control-group">
                                                	<label>District<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="dist_id" id="dist_id" class="form-control input-medium">
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
                                                	<label>Location Name<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input autocomplete="off" name="loc_name" id="loc_name" type="text" value="<?php echo $location_name;  ?>" size="30" class="form-control input-medium" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 right">
                                                <div class="control-group">
                                                    <div class="control-group">
                                                        <label>&nbsp;</label>
                                                        <div class="controls">
                                                            <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
                                                            <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
                                                            <input type="submit" class="btn btn-primary" value="<?=$strDo?>" />            
                                                            <input name="btnAdd" class="btn btn-info" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
                                                        </div>
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
								<h3 class="heading">All Locations</h3>
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
<script>
	function editFunction(val){
         window.location="ManageLocations.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
        	window.location="ManageLocations.php?Do=Delete&Id="+val;
        }	
    }
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Province'>Province</span>,<span title='Location Level'>Location Level</span>,<span title='Location Type'>Location Type</span>,<span title='Location Name'>Location Name</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
		mygrid.attachHeader("#text_filter,#select_filter,#select_filter,#select_filter");
		mygrid.setInitWidths("*,150,150,200,30,30");
		mygrid.setColAlign("left,left,left,left,center,center")
		mygrid.setColSorting("str");
		mygrid.setColTypes("ro,ro,ro,ro,img,img");
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/location.xml");
	}
	
	$(document).ready(function Stakeholders() {
		<?php
		if(isset($_REQUEST['Do']))
		{?>
			showLocTypes();
		<?php
		}
		?>
		
		$("#loc_level").change(function(){
			showLocTypes();
		});
		
		$('#provinces').change(function(){
			showDistricts();
		})
	});
	
	function showLocTypes()
	{
		//$('#provinces').val('');
		if (bid == 4)
		{
			$('#districts').show();
		}
		else
		{
			$('#districts').hide();
		}
		$("#loc_type").html("<option value=''>Please wait...</option>");
		var bid = $("#loc_level").val();
		$.post("getfromajax.php", {ctype:9,id:bid}, function(data){
			// $("select#districts").removeAttr("disabled");
			$("#loc_type").html(data);
			<?php
			if(isset($_REQUEST['Do']))
			{?>
				showDistricts();
			<?php
			}
			?>
		});
	}
	function showDistricts()
	{
		var bid = $("#loc_level").val();
		if (bid == 4)
		{
			$('#districts').show();
			$.post("ManageLocations.php", {id:$('#provinces').val()}, function(data){
				// $("select#districts").removeAttr("disabled");
				$("#dist_id").html(data);
			});
		}
		else
		{
			$('#districts').hide();
		}
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