<?php 
include "../html/config.php";
include "template/header.php";
include("Includes/AllClasses.php");
$formid = 'sub-admin';
$rsUsers = $objuser->GetAllSubAdminUser();



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
	$objuser->m_npkId=$nstkId;  
	$objuser->DeleteUser();
	
	$objuserprov->m_nuserId = $nstkId;
	$objuserprov->delete();
	
	$objuserstk->m_nuserId = $nstkId;
	$objuserstk->delete();
	
	$_SESSION['err']['text'] = 'Data has been successfully deleted.';
	$_SESSION['err']['type'] = 'success';
	
	//header("location:ManageSubAdmin.php");
	echo '<script>window.location="ManageSubAdmin.php"</script>';
	exit;
}

if($strDo == "Edit")
{

  $formid = 'sub-admin2';
  ///////////////
  $objuserprov->m_npkId = $nstkId;
  $user_prov = $objuserprov->GetProvByUserId();

  $objuserstk->m_npkId = $nstkId;
  $user_stk = $objuserstk->GetStkByUserId();
//////////////

  $objuser->m_npkId=$nstkId;
  $rsuser=$objuser->GetUserByUserID(); 
  if($rsuser!=FALSE && mysql_num_rows($rsuser)>0)
  {
    $RowEditStk = mysql_fetch_object($rsuser);
    $usrlogin_id=$RowEditStk->usrlogin_id;
    $sysusr_pwd=$RowEditStk->sysusr_pwd;
    $sysusr_name=$RowEditStk->sysusr_name;
    $sysusr_email=$RowEditStk->sysusr_email;
    $sysusr_ph=$RowEditStk->sysusr_ph;
    //retrieving user id
    $sysusr_UserID=$RowEditStk->UserID;
    //retrieving warehouse name
    
  }
}

$rsStakeholders = $objstk->GetAllStakeholders();

$strDo = ($strDo == 'Edit') ? $strDo : 'Add';

include("xml/xml_genaration_subadmins.php");
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
						<h3 class="page-title row-br-b-wp">Sub-admin Management</h3>
						<div class="widget" data-toggle="collapse-widget">
							<div class="widget-head">
								<h3 class="heading"><?php echo $strDo;?> Sub-admin</h3>
							</div>
							<div class="widget-body">
                            	<form method="post" action="ManageSubAdminAction.php" id="<?php echo $formid; ?>">
                                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Full Name<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="name" id="name" value="<?php echo $sysusr_name; ?>" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($strDo != 'Edit') : ?>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Login<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="login" id="login" value="<?php echo $usrlogin_id; ?>" class="form-control input-medium" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Password<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="password" name="password" id="password" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Confirm Password<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="password" name="cpassword" id="cpassword" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Contact No<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="contact_no" id="contact_no" value="<?php echo $sysusr_ph; ?>" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="control-group">
                                                	<label>Email<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <input type="text" name="email" id="email" value="<?php echo $sysusr_email; ?>" class="form-control input-medium">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="control-group">
                                                	<label>Stakeholders<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <div class="multi-select col-md-6" style="padding:0;">
                                                            <select multiple id="stackholders1" class="multi form-control input-medium">
														<?php
                                                        while ($row = mysql_fetch_array($rsStakeholders)){
                                                            if(!in_array($row['stkid'], $user_stk)){
                                                            ?>
                                                            <option value="<?php echo $row['stkid']; ?>"><?php echo $row['stkname']; ?></option>
                                                            <?php
															}
														}
															?>
                                                            </select>
                                                            <a href="#" id="stk-add">add >></a>
                                                        </div>
                                                        <div class="multi-select col-md-6">
                                                            <select multiple id="stackholders2" name="stkholders[]" class="multi form-control input-medium">
                                                        <?php 
                                                        $rsStakeholders = $objstk->GetAllStakeholders();
                                                        while ($row = mysql_fetch_array($rsStakeholders)) {
                                                            if(in_array($row['stkid'], $user_stk)) { ?>
                                                            <option value="<?php echo $row['stkid']; ?>"><?php echo $row['stkname']; ?></option>
                                                            <?php
                                                            }
                                                        }
                                                            ?>
                                                            </select>
                                                            <a href="#" id="stk-remove"><< remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="control-group">
                                                	<label>Province<font color="#FF0000">*</font></label>
                                                    <div class="controls">
                                                        <div class="multi-select col-md-6" style="padding:0;">
                                                            <select multiple id="provinces1" class="multi form-control input-medium">
                                                        <?php 
                                                        $objloc->LocLvl = 2;
                                                        $rsProvinces = $objloc->GetAllLocations();
                                                        while ($row = mysql_fetch_array($rsProvinces)) { 
                                                            if(!in_array($row['PkLocID'], $user_prov)) {
                                                        ?>
                                                            <option value="<?php echo $row['PkLocID']; ?>"><?php echo $row['LocName']; ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                            </select>
                                                            <a href="#" id="prov-add">add >></a>
                                                        </div>
                                                        <div class="multi-select col-md-6">
                                                            <select multiple id="provinces2" name="prov[]" class="multi form-control input-medium">
                                                            <?php 
                                                            $objloc->LocLvl = 2;
                                                            $rsProvinces = $objloc->GetAllLocations();
                                                            while ($row = mysql_fetch_array($rsProvinces)) { 
																if(in_array($row['PkLocID'], $user_prov)) {
																?>
																<option value="<?php echo $row['PkLocID']; ?>"><?php echo $row['LocName']; ?></option>
																<?php
																}
															}
															?>
                                                            </select>
                                                            <a href="#" id="prov-remove"><< remove</a>
                                                        </div>
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
													<?php if($strDo == 'Edit') : ?>
                                                    <input name="Id" value="<?php echo $sysusr_UserID; ?>" type="hidden" />
                                                    <input name="submit" value="Edit User" type="submit" id="submit" class="btn btn-primary" />
                                                    <input name="Do" value="Edit" type="hidden" id="Do" />
                                                    <?php else: ?>
                                                    <input name="Do" value="Add" type="hidden" id="Do" />
                                                    <input name="submit" value="Add User" type="submit" id="submit" class="btn btn-primary" />
                                                    <?php endif; ?>        
                                                    <input name="cancel" value="Cancel" type="button" id="cancel" class="btn btn-info" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';" />
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
                                            <img style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.setColumnHidden(5,true); mygrid.setColumnHidden(6,true);mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');mygrid.setColumnHidden(5,false); mygrid.setColumnHidden(6,false);" />
                                            <img style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.setColumnHidden(5,true); mygrid.setColumnHidden(6,true);mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');mygrid.setColumnHidden(5,false); mygrid.setColumnHidden(6,false);" />
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div></td>
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
<script src="../plmis_src/operations/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script type="text/javascript">
      $(function() {
        $('#<?php echo $formid; ?>').on('submit', function(e) {          
          $('#stackholders2 option').attr('selected', 'selected');
          $('#provinces2 option').attr('selected', 'selected');      
        });

        $('#stk-add').click(function() {
          return !$('#stackholders1 option:selected').remove().appendTo('#stackholders2');
        });
        $('#stk-remove').click(function() {
          return !$('#stackholders2 option:selected').remove().appendTo('#stackholders1');
        });

        $('#prov-add').click(function() {
          return !$('#provinces1 option:selected').remove().appendTo('#provinces2');
        });
        $('#prov-remove').click(function() {
          return !$('#provinces2 option:selected').remove().appendTo('#provinces1');
        });
      });
    </script>
<script>
function editFunction(val){
	window.location="ManageSubAdmin.php?Do=Edit&Id="+val;
}
function delFunction(val){
	if (confirm("Are you sure you want to delete the record?")){
		window.location="ManageSubAdmin.php?Do=Delete&Id="+val;
	} 
}

var mygrid;
function doInitGrid(){
	mygrid = new dhtmlXGridObject('mygrid_container');
	mygrid.selMultiRows = true;
	mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid.setHeader("<span title='Name'>Name</span>,<span title='Contact No'>Contact No</span>,<span title='Email'>Email</span>,<span title='Provinces'>Provinces</span>,<span title='Stakeholders'>Stakeholders</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
	mygrid.setInitWidths("120,150,150,*,120,30,30");
	mygrid.setColAlign("left,left,left,left,left")
	mygrid.setColSorting("str");
	mygrid.setColTypes("ro,ro,ro,ro,ro,img,img");
	//mygrid.enableLightMouseNavigation(true);
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid.setSkin("light");	
	mygrid.init();
	mygrid.loadXML("xml/subadmins.xml");
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