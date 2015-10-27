<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
$act=2;
$stakeid=array('');
$groupid=array('');
$strDo = "Add";
$nstkId =0;
$itm_name="";
$itm_type="";
$itm_category="";
$qty_carton=0;
$field_color="";
$itm_des="";
$itm_status="";
$frmindex=0;
$extra="";
$stkname="";
$stkorder=0;

include("xml/xml_generation_item.php");

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
	$objManageItem->m_npkId=$nstkId; 	
	
	$objManageItem->DeleteManageItem();
	
	//deleting value from stakeholder item
	$objstakeholderitem->m_stk_item=$nstkId;
	$objstakeholderitem->Deletestkholderitem();
	
	
	//deleting value from items of groups
	$ItemOfGroup->m_ItemID=$nstkId;	
	$ItemOfGroup-> DeleteItemGroup();
	
	$strDo='Add';
	
	$_SESSION['err']['text'] = 'Data has been successfully deleted.';
	$_SESSION['err']['type'] = 'success';
	//header("location:ManageItems.php");
	echo '<script>window.location="ManageItems.php"</script>';
	exit;
}

//retrieving maximum value of an index
$sql=mysql_query("Select MAX(frmindex) AS frmindex from itminfo_tab");
$sql_index=mysql_fetch_array($sql);
$frmindex=$sql_index['frmindex'] + 1;

if (isset($_SESSION['pk_id']))
{
	unset($_SESSION['pk_id']);
}
if($strDo == "Edit")
{
	$objManageItem->m_npkId=$nstkId;
	$_SESSION['pk_id'] = $nstkId;
	$rsEditstk = $objManageItem->GetManageItemById();
	if($rsEditstk!=FALSE && mysql_num_rows($rsEditstk)>0)
	{
		$n=0;
		
		while($RowEditStk = mysql_fetch_object($rsEditstk)){
		$itm_name=$RowEditStk->itm_name;
		$itm_type = $RowEditStk->itm_type;
		$itm_category = $RowEditStk->itm_category;
		//$qty_carton = $RowEditStk->qty_carton;
		//$field_color=$RowEditStk->field_color;
		$itm_des = $RowEditStk->itm_des;
		$itm_status = $RowEditStk->itm_status;
		$frmindex = $RowEditStk->frmindex;
		//$extra=$RowEditStk->extra;
//		$stkname = $RowEditStk->stkname;
		$stakeid[$n] = $RowEditStk->stkid;
//		$stkorder = $RowEditStk->stkorder;
        $groupid[$n]=$RowEditStk->GroupID;
		
		$n++;

		}
	}
}


$rsStakeholders = $objstk->GetAllStakeholders();
//$rsadditem = $objManageItem->GetAllManageItem();

$rsranks=$ItemGroup->GetAllItemGroup();

//retrieving product type
$ItmType = $objitemtype->GetAllItemType();

//retrieving product category
$ItmCategory = $objitemcategory->GetAllItemCategory();

//retrieving product status
$ItmStatus = $objitemstatus->GetAllItemStatus();

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
						<h3 class="page-title row-br-b-wp">Product Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Product</h3>
							</div>
							<div class="widget-body">
                            	<form name="manageitems" id="manageitems" method="post" action="ManageadditemAction.php">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Product<font color="#FF0000">*</font></label>
                                                    <div class="controls">
	                                                    <input autocomplete="off" type="text" name="txtStkName1" value="<?=$itm_name?>" id="txtStkName1" class="form-control input-medium" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Unit of Measure<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                    	<select name="txtStkName2" id="txtStkName2" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
                                                        while($RowItmType = mysql_fetch_object($ItmType))
                                                        {
                                                        ?>
                                                            <option value="<?=$RowItmType->ItemTypeName?>" <?php if($RowItmType->ItemTypeName==$itm_type) echo 'selected="selected"';?>><?=$RowItmType->ItemTypeName?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Category<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="txtStkName4" id="txtStkName4" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
                                                        while($RowItmCategory = mysql_fetch_object($ItmCategory))
                                                        {
                                                        ?>
                                                        	<option value="<?=$RowItmCategory->PKItemCategoryID?>" <?php if($RowItmCategory->PKItemCategoryID==$itm_category) echo 'selected="selected"';?>><?=$RowItmCategory->ItemCategoryName?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Status<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="txtStkName6" id="txtStkName6" class="form-control input-medium">
                                                        	<option value="">Select</option>
														<?php
                                                        while($RowItmStatus = mysql_fetch_object($ItmStatus))
                                                        {
                                                        ?>
                                                        	<option value="<?=$RowItmStatus->ItemStatusName?>" <?php if($RowItmStatus->ItemStatusName==$itm_status) echo 'selected="selected"';?>><?=$RowItmStatus->ItemStatusName?></option>
                                                        <?php
                                                        }
                                                        ?>
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
                                                	<label>Description</label>
                                                    <div class="controls">
                                                        <input type="text" name="txtStkName7" value="<?=$itm_des?>" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Index<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <!--<input type="text" name="txtStkName8" id="txtStkName8" class="form-control input-medium" value="<?=$frmindex?>" />
                                                        <img src="images/sort_asc.gif" alt="" onClick="update_counter()" />
                                                        <img src="images/sort_desc.gif" alt="" onClick="update_counter_down()" />-->
                                                        <input type="text" name="txtStkName8" id="spinner1" class="form-control input-small" style="border:1px solid #d8d9da;text-align:right; padding-right:5px;" value="<?=$frmindex?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Stakeholders<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="stkid[]" size="5" multiple="multiple" class="form-control input-medium">
                                                        <?php
                                                        while($RowStakeholders = mysql_fetch_object($rsStakeholders))
                                                        {
                                                        
                                                        ?>
                                                        	<option value="<?=$RowStakeholders->stkid?>" <?php if(in_array($RowStakeholders->stkid,$stakeid)) echo 'selected="selected"';?>><?=$RowStakeholders->stkname?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Product Group<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <select name="select2[]" size="5" multiple="multiple" class="form-control input-medium">
														<?php
                                                        while($Rowranks = mysql_fetch_object($rsranks))
                                                        {
                                                        ?>
                                                        	<option value="<?=$Rowranks->PKItemGroupID?>" <?php if(in_array($Rowranks->PKItemGroupID,$groupid)) echo 'selected="selected"';?>><?=$Rowranks->ItemGroupName?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                        </select>
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
                                                        <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
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
								<h3 class="heading">All Products</h3>
							</div>
							<div class="widget-body">
                                <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                    <tr>
                                        <td style="text-align:right;">
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(6,true); mygrid.setColumnHidden(7,true);mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');mygrid.setColumnHidden(6,false); mygrid.setColumnHidden(7,false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(6,true); mygrid.setColumnHidden(7,true);mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');mygrid.setColumnHidden(6,false); mygrid.setColumnHidden(7,false);" />
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
        window.location="ManageItems.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
            window.location="ManageItems.php?Do=Delete&Id="+val;
        }	
    }
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<span title='Product'>Product</span>,<span title='Type'>Type</span>,<span title='Category'>Category</span>,<span title='Status'>Status</span>,<span title='Description'>Description</span>,<span title='Index'>Index</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
		mygrid.attachHeader("#text_filter,#select_filter,#select_filter,#select_filter,,,,");
		mygrid.setInitWidths("*,150,150,150,150,50,30,30");
		mygrid.setColAlign("left,left,left,left,left,right,center,center")
		mygrid.setColSorting("str,,,,,int,,");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,img,img");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");		
		mygrid.init();
		mygrid.loadXML("xml/item.xml");
	}
	$('#spinner1').spinner();
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