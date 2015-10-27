<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");

include("xml/xml_generation_items_of_groups.php");
$act=2;
$strDo = "Add";
$nstkId =0;
$itm_id=0;
$itm_name="";
$PKItemGroupID=0;
$ItemGroupName="";
$pkItemsofGroupsID=0;
$ItemID=0;
$GroupID=0;
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
	$ItemOfGroup->m_GroupID=$nstkId; 	
	$rsEditCat = $ItemOfGroup->DeleteItemOfGroup();
	//header("location:ManageItemsofGroups.php");
	echo '<script>window.location="ManageItemsofGroups.php"</script>';
	exit;
}
if($strDo == "Edit" )
{
	$ItemOfGroup->m_npkId=$nstkId; 	
	$objGIa = $ItemOfGroup->GetItemOfGroupById();
	if($objGIa!=FALSE && mysql_num_rows($objGIa)>0)
	{
		$RowEditStk = mysql_fetch_object($objGIa);
		$GroupName = $RowEditStk->ItemGroupName;
	}
	
	$objMI = $objManageItem->GetAllManageItem();
	}

$ItmOfGrp = $ItemGroup->GetAllItemGroup();
?>
</head>

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
	<div class="page-container">
	<?php include "template/menu.php";?>
	
		<div class="page-content-wrapper">
			<div class="page-content">
				<?php 
				if ( isset($_REQUEST['Do']) )
				{
				?>
                <div class="row">
					<div class="col-md-12">
						<h3 class="page-title row-br-b-wp">Items Group Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Group Items for '<?php echo $GroupName; ?>'</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManageManageItemsofGroupsAction.php">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Products<font color="#FF0000">*</font></label>
                                                    <div class="controls">
	                                                    <select name="ItemID[]" size="5" multiple="multiple" class="form-control input-medium">
                                                        <?php
                                                        while($Rowranks = mysql_fetch_object($objMI))
                                                        {
                                                        ?>
                                                        	<option value="<?=$Rowranks->itm_id?>" <?php 
															$ItemOfGroup->m_npkId=$nstkId; 	
															$objMIs = $ItemOfGroup->GetItemsOfGroupById();
															if($objMIs!=FALSE && mysql_num_rows($objMIs)>0)
															{
															while($Rowrankss = mysql_fetch_object($objMIs))
															{
															if($Rowranks->itm_id==$Rowrankss->ItemID) echo 'selected="selected"';
															}		
															}
															?>>
															<?=$Rowranks->itm_name?>
															</option>
                                                        
                                                        <?php
                                                        }?>
														</select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                    <div class="control-group">
                                                        <label>&nbsp;</label>
                                                        <div class="controls">
                                                            <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
                                                            <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
                                                            <input type="submit" value="<?=$strDo?>" class="btn btn-primary" />
                                                            <input name="btnAdd" type="button" id="btnCancel" class="btn btn-info" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
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
                <?php
				}
				?>
                <div class="row">
					<div class="col-md-12">
						<div class="widget">
							<div class="widget-head">
								<h3 class="heading">All Product's Group</h3>
							</div>
							<div class="widget-body">
                                <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td style="text-align:right;">
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(2,true);mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');mygrid.setColumnHidden(2,false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(2,true);mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');mygrid.setColumnHidden(2,false);" />
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
         window.location="ManageItemsofGroups.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
        	window.location="ManageItemsofGroups.php?Do=Delete&Id="+val;
        }	
    }
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Product Groups'>Groups</span>,<span title='List of all Products'>Products</span>,<span title='Use this column to perform the desired operation'>Actions</span>");
		mygrid.setInitWidths("150,*,60");
		mygrid.setColAlign("left,left,center")
		mygrid.setColSorting("str");
		mygrid.setColTypes("ro,ro,img");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/items_of_groups.xml");
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