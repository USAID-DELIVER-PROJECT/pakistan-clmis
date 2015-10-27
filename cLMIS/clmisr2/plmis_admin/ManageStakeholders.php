<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
$act=2;
$strDo = "Add";
$nstkId =0;

$stkname="";
$stkgroupid=0;
$strNewGroupName="";
$stktype=0;
$stkorder=0;
$newRank=0;
$lvl_id=0;

include("xml/xml_generation_stakeholder.php");

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


if($strDo == "Delete" )
{
	//Check if Sub-offices exists
	$checkStk = "SELECT
					COUNT(*) AS num
				FROM
					stakeholder
				WHERE
					stakeholder.stkid != $nstkId
				AND stakeholder.MainStakeholder = $nstkId";
	$stkQryRes = mysql_fetch_array(mysql_query($checkStk));
	
	if ( $stkQryRes['num'] == 0 )
	{	
		$objstk->m_npkId=$nstkId; 	
		$rsEditCat = $objstk->DeleteStakeholder();
		
		$_SESSION['err']['text'] = 'Data has been successfully deleted.';
		$_SESSION['err']['type'] = 'success';
		//header("location:ManageStakeholders.php");
	}
	else
	{
		$_SESSION['err']['text'] = "Stakeholder can not be deleted. Please delete stakeholder offices first.";
		$_SESSION['err']['type'] = 'error';
	}
	echo '<script>window.location="ManageStakeholders.php"</script>';
	exit;
} 
if (isset($_SESSION['pk_id']))
{
	unset($_SESSION['pk_id']);
}
if($strDo == "Edit")
{
	$objstk->m_npkId=$nstkId; 	
	$rsEditstk = $objstk->GetStakeholdersById();
	if($rsEditstk!=FALSE && mysql_num_rows($rsEditstk)>0)
	{
		$RowEditStk = mysql_fetch_object($rsEditstk);
		$stkname=$RowEditStk->stkname;
		$_SESSION['pk_id'] = $nstkId;
		$stktype = $RowEditStk->stk_type_id;
		$stkorder = $RowEditStk->stkorder;
		$lvl_id=$RowEditStk->lvl;
	}
}

$rsStakeholders = $objstk->GetAllStakeholders();
$rsstktype = $objstkType->GetAllstk_types();
$rsranks=$objstk->GetRanks();

$rslvl=$objlvl->GetAlllevels();

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
						<h3 class="page-title row-br-b-wp">Stakeholder Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Stakeholder</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManageStakeholdersAction.php" id="Managestakeholdersaction">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Jurisdiction<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="lstLvl" id="lstLvl" class="form-control input-medium">
														<?php
                                                        while($Rowlvl = mysql_fetch_object($rslvl))
                                                        {
                                                        ?>
                                                        	<option value="<?=$Rowlvl->lvl_id?>" <?php if($Rowlvl->lvl_id==$lvl_id) echo 'selected="selected"';?>><?=$Rowlvl->lvl_name?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                        <span class="help-block">(National / Provincial / District /Field)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Type (Private / Public)<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="lstStktype" class="form-control input-medium">
														<?php
														while($Rowstktype = mysql_fetch_object($rsstktype))
														{
														?>
															<option value="<?=$Rowstktype->stk_type_id?>" <?php if($Rowstktype->stk_type_id==$stktype) echo 'selected="selected"';?>><?=$Rowstktype->stk_type_descr?></option>
														<?php
														}
														?>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Stakeholder<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input name="txtStkName" value="<?=$stkname?>" class="form-control input-medium" autocomplete="off" />
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
                                                        <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
                                                        <input type="hidden" name="hdnToDo" value="<?=$strDo?>" />
                                                        <input type="submit" value="<?=$strDo?>" class="btn btn-primary" />
                                                        <input name="btnAdd" type="button" id="btnCancel" class="btn btn-info" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
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
								<h3 class="heading">All Stakeholders</h3>
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
                                    	<td><div id="mygrid_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div></td>
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
        window.location="ManageStakeholders.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
            window.location="ManageStakeholders.php?Do=Delete&Id="+val;
        }	
    }
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Stakeholder Name'>Stakeholder</span>,<span title='Type'>Type</span>,<span title='Province'>Province</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
		mygrid.attachHeader("#text_filter,#select_filter,#select_filter,,");
		mygrid.setInitWidths("*,150,150,30,30");
		mygrid.setColAlign("left,left,left,center,center")
		mygrid.setColSorting("str,,,,");
		mygrid.setColTypes("ro,ro,ro,img,img");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/stakeholder.xml");
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