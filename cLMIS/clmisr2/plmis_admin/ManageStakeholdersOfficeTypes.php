<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");

$act=2;
$strDo = "Add";
$stkid =0;
$lvl_id=0;
$stktype="";
$nstktypeId=0;
if(isset($_SESSION['level_id']))
{
	unset($_SESSION['level_id']);
	unset($_SESSION['parent_id']);
}

include("xml/xml_generation_stakeholder_office.php");

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
	$objstk->m_npkId=$nstkId; 	
	$rsEditCat = $objstk->DeleteStakeholder();
	$_SESSION['err']['text'] = 'Data has been successfully deleted.';
	$_SESSION['err']['type'] = 'success';
	//header("location:ManageStakeholdersOfficeTypes.php");
	echo '<script>window.location="ManageStakeholdersOfficeTypes.php"</script>';
	exit;
}

if (isset($_SESSION['pk_id']))
{
	unset($_SESSION['pk_id']);
}
if($strDo == "Edit" )
{
	$objstk->m_npkId=$nstkId;
	$_SESSION['pk_id'] = $nstkId;
	$rsEditstk = $objstk->GetStakeholdersById();
	if($rsEditstk!=FALSE && mysql_num_rows($rsEditstk)>0)
	{
		$RowEditStk = mysql_fetch_object($rsEditstk);
		$StkparentID=$RowEditStk->ParentID;
		$gpid=$RowEditStk->MainStakeholder;
		
		if ($StkparentID==0)
		{
			$StkparentID=$RowEditStk->stkid;
			$selfParent=TRUE;
		}
		else
		$selfParent=FALSE;

		$stkname=$RowEditStk->stkname;
		
		
		$lvl_id = $RowEditStk->lvl;
		$_SESSION['level_id'] = $lvl_id;
		$lvl_name=$RowEditStk->lvl_name;
		$stknameabb = $RowEditStk->stkname;
		$stkorder = $RowEditStk->stkorder;
		$objstk->m_npkId=$StkparentID;
		$_SESSION['parent_id'] = $StkparentID;
		$StkparentID=$objstk->get_stakeholder_name();
		$objstk->m_npkId=$gpid;
		$StkgparentID=$objstk->get_stakeholder_name();
	}
}

$rsStakeholdersTypes = $objstk->GetAllStakeholdersOfficeTypes();
$rsStakeholders = $objstk->GetAllStakeholders();

//$rslvls=$objstk->GetAllLevels();

$rslvls=$objlvl->GetAlllevels();
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
						<h3 class="page-title row-br-b-wp">Manage Stakeholder Office Types</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Stakeholder Office Types</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManageStakeholdersOfficeTypesAction.php" id="ManageStakeholdersOfficeTypesAction">                                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Stakeholder<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="Stakeholders" id="Stakeholders" class="multi form-control input-medium">
                                                        	<option value="">Select</option>
                                                        <?php
                                                        while($RowGroups = mysql_fetch_object($rsStakeholders))
                                                        {
                                                        ?>
                                                        	<option value="<?=$RowGroups->stkid?>" <?php echo ($RowGroups->stkid == $gpid) ? 'selected="selected"' : '';?>><?=$RowGroups->stkname?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Level<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="lstlvl" id="lstlvl" class="multi form-control input-medium">
                                                        	<option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="parentOffice" style="display:none;">
                                                <div class="control-group">
                                                	<label>Parent Office</label>
                                                    <div class="controls">
                                                        <select name='lststkholdersParent' id="lststkholdersParent" class="multi form-control input-medium">
                                                        	<option value="">Select</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Office Type<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input autocomplete="off" type="text" name="txtStktype" class="multi form-control input-medium" value="<?=$stkname?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12 right">
                                                <div class="control-group">
                                                	<label>&nbsp;</label>
                                                    <div class="controls">                                                        
                                                        <input type="hidden" name="ID" value="<?=$nstkId?>" />
                                                        <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
                                                        <input type="submit" class="btn btn-primary" value="<?=$strDo?>" />
                                                        <input name="btnAdd" class="btn btn-info" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
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
								<h3 class="heading">All Sub-admins</h3>
							</div>
							<div class="widget-body">
                                <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td style="text-align:right;">
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(3,true); mygrid.setColumnHidden(4,true);mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');mygrid.setColumnHidden(3,false); mygrid.setColumnHidden(4,false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(3,true); mygrid.setColumnHidden(4,true);mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');mygrid.setColumnHidden(3,false); mygrid.setColumnHidden(4,false);" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div></td>
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

<script type="text/javascript">
	function editFunction(val){
		window.location="ManageStakeholdersOfficeTypes.php?Do=Edit&Id="+val;
	}	
	function delFunction(val){
		if (confirm("Are you sure you want to delete the record?")){
			window.location="ManageStakeholdersOfficeTypes.php?Do=Delete&Id="+val;
		}
	}
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Stakeholder Name'>Stakeholder</span>,<span title='Level'>Level</span>,<span title='Office Type'>Office Type</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
		mygrid.attachHeader("#select_filter,#select_filter,#text_filter,,");
		mygrid.setInitWidths("250,215,*,30,30");
		mygrid.setColAlign("left,left,left,center,center")
		mygrid.setColSorting("str");
		mygrid.setColTypes("ro,ro,ro,img,img");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		
		mygrid.init();
		mygrid.loadXML("xml/stakeholder_office.xml");
	}
	
	$(function() {
		//Disabling sub-combos start 
		//$("select#lstlvl").attr('disabled', 'disabled');
		//$("select#lststkholdersParent").attr('disabled', 'disabled');
		// end
	
		showLevels();
		$("select#Stakeholders").change(function(){
			showLevels();
		});

		$("select#lstlvl").change(function(){
			showParentOffices();
		});
	  
	});
	function showParentOffices()
	{
		$("select#lststkholdersParent").html("<option>Please wait...</option>");
		var bid = $("select#lstlvl option:selected").attr('value');
		var pid = $("select#Stakeholders option:selected").attr('value');
		if ( bid != 1 )
		{
			$.post("getfromajax.php", {ctype:4,id:bid,id2:pid}, function(data){
				//   $("select#lststkholdersParent").removeAttr("disabled");
				$("#parentOffice").show();
				$("select#lststkholdersParent").html(data);
			});
		}
		else
		{
			$("#parentOffice").hide();
		}
	}
	function showLevels()
	{
		var bid = $("select#Stakeholders option:selected").attr('value');
		if (bid == '')
		{
			$("select#lstlvl").html('<option>Select</option>');
			return false;
		}
		$("select#lstlvl").html("<option>Please wait...</option>");
		$.post("getfromajax.php", {ctype:7,id:bid}, function(data){
			//  $("select#lstlvl").removeAttr("disabled");
			$("select#lstlvl").html(data);
			<?php if (isset($_REQUEST['Do'])){
			?>
				showParentOffices();
			<?php }?>
		});
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