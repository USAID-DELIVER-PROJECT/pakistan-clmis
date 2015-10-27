<?php

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
$stakeholder=$sql_row['stkid'];
$provinceidd=$sql_row['province'];
$stkname=$sql_row['stkname'];
$provincename=$sql_row['provincename'];

if($stakeholder=='0' && $provinceidd=='0'){
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

if($strDo == "Edit")
{

	$objloc->PkLocID=$nstkId;
	$rsloc=$objloc->GetLocationById(); 
	if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
	{
		$RowEditStk = mysql_fetch_object($rsloc);
		$location_level=$RowEditStk->LocLvl;
		$location_type=$RowEditStk->LocType;
		$province=$RowEditStk->ParentID;
		$location_name = $RowEditStk->LocName;
		
		//retrieving location name
		//retrieving level
		$sql=mysql_query("select lvl_name from tbl_dist_levels where lvl_id='".$location_level."'");
		$row1_level=mysql_fetch_array($sql);
		
		//retrieving type
		$sql=mysql_query("select LoctypeName from tbl_locationtype where LoctypeID='".$location_type."'");
		$row1_type=mysql_fetch_array($sql);
		
		//retrieving province 
		$sql=mysql_query("select LocName from tbl_locations where PkLocID='".$province."'");
		$row1_parent=mysql_fetch_array($sql);
	}
}
if($strDo == "Delete")
{
	$objloc->PkLocID = $nstkId;
	$objloc->DeleteLocation();
	header("location:ManageLocations.php");
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Locations</title>

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
        window.location="ManageLocations.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
            window.location="ManageLocations.php?Do=Delete&Id="+val;
        }	
    }
 </script>

 <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       mygrid.setHeader("<span title='Location Name'>Location Name</span>,<span title='Location Level'>Location Level</span>,<span title='Location Type'>Location Type</span>,<span title='Parent'>Parent</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
	   mygrid.attachHeader("#select_filter,#select_filter");
       mygrid.setInitWidths("190,190,190,190,30,30");
       mygrid.setColAlign("left,left,left,left")
       mygrid.setColSorting("str");
       mygrid.setColTypes("ro,ro,ro,ro,img,img");
	   //mygrid.enableLightMouseNavigation(true);
	   mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
       mygrid.setSkin("light");
       mygrid.enablePaging(true, 10, 5, "recinfoArea");
       mygrid.setPagingSkin("toolbar", "dhx_skyblue");
       
	   mygrid.init();
       mygrid.loadXML("xml/location.xml");
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
    <script>
$(document).ready(function Stakeholders() {

<?php if($_SESSION['menu']=='menusadmin.php'||$test=='true'){ ?>

 $("select#loc_level").change(function(){
	 
	   $("select#loc_type").html("<option value=''>Please wait...</option>");
	   
	   var bid = $("select#loc_level option:selected").attr('value');
	
	     
	 if(bid==2){
		$("#provinces").attr('disabled', 'disabled'); 
	 }
	 else{
		 $("#provinces").attr('disabled', false); 
	 }
	 
    $.post("getfromajax.php", {ctype:9,id:bid}, function(data){
      // $("select#districts").removeAttr("disabled");
       $("select#loc_type").html(data);
   });
   
  });
 <?php } else if($_SESSION['menu']=='menu.php'){?>
        
	var bid=$("select#loc_level option:selected").attr('value');
	
	  $.post("getfromajax.php", {ctype:9,id:bid}, function(data){
      // $("select#districts").removeAttr("disabled");
       $("select#loc_type").html(data);
   });
 
 <?php } ?>
  
});
</script>
  </tr>
  <tr>
    <td><form method="post" action="ManageLocationAction.php" name="managelocation" id="managelocation">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%" height="32"><div align="left">Location Level<a class="sb1Exception">*</a></div></td>
            <td width="28%"><div align="left"><?php echo $row1_level['lvl_name']; ?>
            <?php if($_SESSION['menu']=='menusadmin.php'||$test=='true'){ ?>
              <select name="loc_level" id="loc_level">
                   <option value="">Choose..</option>
               <?php
                          $strSql = "SELECT * FROM tbl_dist_levels WHERE lvl_id IN (2,3)";
                          $rsSql = mysql_query($strSql);
                           if(mysql_num_rows($rsSql)>0)
                                {
                                  while($RowLoc2 = mysql_fetch_array($rsSql))
                                      {
                                ?>
                                 <option value="<?php echo $RowLoc2['lvl_id'];?>" <?php if($RowLoc2['lvl_name']==$levelid) echo 'selected="selected"';?>>
                                  <?php echo $RowLoc2['lvl_name']; ?>
                                </option>
                                  <?php
                                     }
                               }
                         ?>
              </select>
            <?php } else if($_SESSION['menu']=='menu.php'){?>
             <select name="loc_level" id="loc_level">
               <?php
                          $strSql = "SELECT * FROM tbl_dist_levels WHERE lvl_id='3'";
                          $rsSql = mysql_query($strSql);
                           if(mysql_num_rows($rsSql)>0)
                                {
                                  while($RowLoc2 = mysql_fetch_array($rsSql))
                                      {
                                ?>
                                 <option value="<?php echo $RowLoc2['lvl_id'];?>" <?php if($RowLoc2['lvl_name']==$levelid) echo 'selected="selected"';?>>
                                  <?php echo $RowLoc2['lvl_name']; ?>
                                </option>
                                  <?php
                                     }
                               }
                         ?>
              </select>
            
            <?php } ?>
            </div></td>
            <td width="20%"><div align="left">Location Type<a class="sb1Exception">*</a></div></td>
            <td width="27%" valign="middle"><p id="txtStk"></p><?php echo $row1_type['LoctypeName']; ?>
              <select name="loc_type" id="loc_type">
                <option value="">Choose...</option>
              </select></td>
          </tr>
          <tr>
            <td><div align="left">Province<a class="sb1Exception">*</a></div></td>
            <td height="26" align="left" valign="middle"><p id="txtProv"></p><?=$row1_parent['LocName']?>
            <?php if($_SESSION['menu']=='menusadmin.php'||$test=='true'){ ?>
              <select name="provinces" id="provinces">
                <option value="">Choose...</option>
				<?php
    				if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
  						{
							  while($RowLoc = mysql_fetch_object($rsloc))
								  {
				?>
                <option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$PkLocID) echo 'selected="selected"';?>>
                <?=$RowLoc->LocName?>
                </option>
                <?php
							  }
						}
				?>
              </select>
              <?php } else if($_SESSION['menu']=='menu.php'){  ?>
               <select name="provinces" id="provinces">
                <option value="<?=$provinceidd;?>">
                <?php echo $provincename; ?>
                </option>
           
              </select>
              <?php } ?>
              </td>
            <td><div align="left">Location Name<a class="sb1Exception">*</a></div></td>
            <td colspan="4">&nbsp;&nbsp;
              <div align="left">
                <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            
                <input name="loc_name" id="loc_name" type="text" value="<?php echo $location_name;  ?>" size="30" />
              </div></td>
          </tr>
         
          <tr>
            <td>&nbsp;</td>
            <td colspan="4"><div align="left">
              <input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
              <input type="submit" value="<?=$strDo?>" />            
           <input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';"></div></td>
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
         
<table>          
  <tr>
    <td>All Rights Reserved</td>
  </tr>
</table>
</body>
</html>
