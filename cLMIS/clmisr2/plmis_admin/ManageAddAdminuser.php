<?php
$act=2;
include("Includes/AllClasses.php");
include("xml/xml_genaration_adminuser.php");
$strDo = "Add";
$nstkId =0;
$stkid=0;
$prov_id=0;
$dist_id=0;
$usrlogin_id="";
$sysusr_pwd="";
$stkname="";
$district="";
$province="";

if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
{
	$strDo = $_REQUEST['Do'];
}

if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
{
	$nstkId  = $_REQUEST['Id'];
}
if($strDo == "Edit" )
{

	$objuser->m_npkId=$nstkId;
	$rsuser=$objuser->GetAdminByAdminID(); 
	if($rsuser!=FALSE && mysql_num_rows($rsuser)>0)
	{
		$RowEditStk = mysql_fetch_object($rsuser);
		$stkid=$RowEditStk->stkid;
		$stkname=$RowEditStk->stkname;
		$province_id=$RowEditStk->province;
		$district = $RowEditStk->district;
		$usrlogin_id=$RowEditStk->usrlogin_id;
		$sysusr_pwd=$RowEditStk->sysusr_pwd;
		$sysusr_name=$RowEditStk->sysusr_name;
		$sysusr_email=$RowEditStk->sysusr_email;
		$sysusr_ph=$RowEditStk->sysusr_ph;
		$sysusr_fax=$RowEditStk->sysusr_fax;
		$sysusr_addr=$RowEditStk->sysusr_addr;
		$sysusr_type=$RowEditStk->sysusr_type;
		//retrieving user id
		$sysusr_UserID=$RowEditStk->UserID;
		//retrieving warehouse name
		
	}
}

if($strDo == "Delete" )
{
	$objuser->m_npkId=$nstkId; 	
	$objuser->DeleteUser();
	header("location:ManageAddAdminuser.php");
}

//search is selected
if(isset($_POST['Search']) && !empty($_POST['Search']) && !empty($_POST['providz']) ){
	$provinceid=$_REQUEST['providz'];
	$objuser->provid=$provinceid;
	$objuser1 = $objuser->GetAllprovAdministrator();
}

$rsStakeholders = $objstk->GetAllStakeholders();
$objloc->LocLvl=2;
$rsloc=$objloc->GetAllLocations();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Administrators</title>

<LINK HREF="<?php echo PLMIS_CSS;?>cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
<LINK HREF="<?php echo PLMIS_CSS;?>main.css" REL="STYLESHEET" TYPE="TEXT/CSS">
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
<!--<script type="text/javascript" src="lightbox/js/jquery.lightbox.js"></script>-->
<!--<script type="text/javascript" src="Scripts/jquery-1.7.min.js"></script>-->

<script>
    function editFunction(val){
        window.location="ManageAddAdminuser.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
            window.location="ManageAddAdminuser.php?Do=Delete&Id="+val;
        }	
    }
</script>

<script>
  var mygrid;
  function doInitGrid(){
   mygrid = new dhtmlXGridObject('mygrid_container');
   mygrid.selMultiRows = true;
   mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
   mygrid.setHeader("<span title='Stakeholder'>Stakeholder</span>,<span title='Province'>Province</span>,<span title='Login ID'>Login ID</span>,<span title='Full Name'>Full Name</span>,<span title='Email'>Email</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
   mygrid.attachHeader("#select_filter,#select_filter");
   mygrid.setInitWidths("150,150,150,150,160,30,30");
   mygrid.setColAlign("left,left,left,left,left")
   mygrid.setColSorting("str");
   mygrid.setColTypes("ro,ro,ro,ro,ro,img,img");
   //mygrid.enableLightMouseNavigation(true);
   mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
   mygrid.setSkin("light");
   mygrid.enablePaging(true, 10, 6, "recinfoArea");
   mygrid.setPagingSkin("toolbar", "dhx_skyblue");
   
   mygrid.init();
   mygrid.loadXML("xml/adminuser.xml");
  }
</script>  
<style>
label.error {
    color: #FF0000;
    display: inline-block;
    font-family: Verdana,Geneva,sans-serif;
    font-size: 11px;
    padding-left: 10px;
}
</style>
</head>
  <body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;" onload="doInitGrid()">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("header.php");?></td>
  </tr>
  <tr>
    <td>
      <form method="post" action="ManageAddAdminuserAction.php" name="manageadminuser" id="manageadminuser">
        <table width="100%" border="1" cellspacing="0" cellpadding="4" id="mytable1">   
        <tr>
            <td width="7%" height="41" >Stakeholder</td>
            <td width="20%"><select name="select" id="Stakeholders">
              <option value="">Choose...</option>
                          <?php
                if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
                {
                  while($RowGroups = mysql_fetch_object($rsStakeholders))
                  {
                ?>
              <option value="<?=$RowGroups->stkid?>" <?php if($RowGroups->stkid==$stkid) echo 'selected="selected"';?>>
              <?php echo $RowGroups->stkname; ?>
              </option>
                <?php
                  }
                }
                ?>
            </select>
            </td>
            <td width="10%"><p id="txtStk">Province</p></td>
            <td width="15%">
              <select name="select3" id="Provinces">
                <option value="">Choose...</option>
          <?php
         if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
            {
              while($RowLoc = mysql_fetch_object($rsloc))
              {
          ?>
                <option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$province_id) echo 'selected="selected"';?>>
                <?php echo $RowLoc->LocName; ?>
                </option>
                <?php
             }
            }
            ?>
              </select></td>
            <td>Login ID<a class="sb1Exception">*</a> </td>
            <td width="25%"><input type="text" name="usrlogin_id" value="<?=$usrlogin_id?>" id='usrlogin_id'></td>
            <td>Password<a class="sb1Exception">*</a></td>
            <td><input type="password" name="txtStkName2" id="txtStkName2" value="<?=$sysusr_pwd?>" /></td>
        </tr>
        <tr>
            <td>Confirm Password<a class="sb1Exception">*</a> </td>
            <td><input type="password" name="txtStkName22" value="<?=$sysusr_pwd?>" /></td>
            <td>Full Name<a class="sb1Exception">*</a></td>
            <td><input type="text" name="full_name" id='full_name' value="<?= $sysusr_name; ?>"></td>
            <td>Email<a class="sb1Exception">*</a></td>
            <td><input type="text" name="email_id" value="<?= $sysusr_email; ?>" id='email_id'></td>
            <td>Phone No.<a class="sb1Exception">*</a></td>
            <td><input type="text" name="phone_no" value="<?= $sysusr_ph; ?>" id='phone_no'></td>
         </tr>
         <tr>
            <td>Fax No.</td>
            <td><input type="text" name="fax_no" value="<?= $sysusr_fax; ?>" id='fax_no'></td>
            <td>Address</td>
            <td><input type="text" name="address" value="<?= $sysusr_addr; ?>" id='address'></td>
			 <td>User Type</td>
            <td><select name="sysusr_type" id="sysusr_type">
				 <?php if($sysusr_type==''){ ?>
			        <option value="UT-001">Administrator</option>
					<option value="5">Guest</option>
				 <?php } else if($sysusr_type=='UT-001'){ ?>
				 	<option value="UT-001">Administrator</option>
				 <?php } else if($sysusr_type=='5'){ ?>
				 	<option value="5">Guest</option>
				 <?php } ?>
			   </select>
			  </td>
			
            <td colspan="7"><input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
              <input type="submit" value="<?=$strDo?>" /><input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">           
              <?php 
              if(isset($_REQUEST['msg']) && !empty($_REQUEST['msg']))
                    {
                        print '<p style=\'color:#FF0000\'>Error:'.$_REQUEST['msg']."</p>";
                    }
              ?>
             </td>
         </tr>
       </table>
      </form>
    </td>
  </tr>
</table>
<table width="100%" >
<tr></tr><tr></tr>
<tr>
    <td width="18%"></td>
    <td>
      Choose skin to apply: 
      <select onChange="mygrid.setSkin(this.value)">
        <option value="light" selected>Light
        <option value="sbdark">SB Dark
        <option value="gray">Gray
        <option value="clear">Clear
        <option value="modern">Modern
        <option value="dhx_skyblue">Skyblue
    </select>
    </td>
    <td>
        <img title="Click here to export data to PDF file" style="cursor:pointer;" src="../plmis_img/pdf.bmp" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
       <img title="Click here to export data to Excel file" style="cursor:pointer; margin-left:-5px" src="../plmis_img/excel.bmp" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
   </td>
</tr>
</table>

<table width="820" cellpadding="0" cellspacing="0" align="center">
<tr>
    <td>
        <div id="mygrid_container" style="width:100%; height:355px; background-color:white;overflow:hidden"></div>
    </td>
</tr>
<tr>
    <td>
        <div id="recinfoArea"></div>
    </td>
</tr>
</table>
All Rights Reserved
<p>&nbsp;</p>
</body>
</html>
