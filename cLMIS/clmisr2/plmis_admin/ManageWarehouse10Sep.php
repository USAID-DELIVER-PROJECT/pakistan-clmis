<?php
$act=2;
include("Includes/AllClasses.php");
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
	header("location:ManageWarehouse.php");
}


if($strDo == "Edit")
{

	$objwarehouse->m_npkId=$nstkId;
	$rswarehouse=$objwarehouse->GetWarehouseById(); 
	if($rswarehouse!=FALSE && mysql_num_rows($rswarehouse)>0)
	{
		$RowEditStk = mysql_fetch_object($rswarehouse);
		$stkid=$RowEditStk->stkid;
		$stkname=$RowEditStk->stkname;
		$wh_name=$RowEditStk->wh_name;
		$stkOfficeId = $RowEditStk->stkofficeid;
		$prov_id = $RowEditStk->prov_id;
		$dist_id = $RowEditStk->dist_id;
		$province=$RowEditStk->province;
		$district=$RowEditStk->district;
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Warehouse</title>
<LINK HREF="<?php echo PLMIS_CSS;?>cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
 <LINK HREF="<?php echo PLMIS_CSS;?>main.css" REL="STYLESHEET" TYPE="TEXT/CSS">
 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
 <link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
  
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


 <script>
    function editFunction(val){
        window.location="ManageWarehouse.php?Do=Edit&Id="+val;
    }
    function delFunction(val){
        if (confirm("Are you sure you want to delete the record?")){
            window.location="ManageWarehouse.php?Do=Delete&Id="+val;
        }	
    }
 </script>
 
  <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       mygrid.setHeader("<span title='Stakeholder'>Stakeholder</span>,<span title='Office type'>Office Type</span>,<span title='Province'>Province</span>,<span title='District'>District</span>,<span title='Warehouse'>Warehouse</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
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
       mygrid.loadXML("xml/warehouse.xml");
      }
 
     </script>  


</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;" onload="doInitGrid()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("header.php");?></td>
    <script>
	<?php if($test=='true'){ ?>
	
	
	$(document).ready(function Stakeholders() {

$("select#Stakeholders").change(function(){
   
 // if changed after last element has been selected, will reset last boxes choice to default
   $("select#Warehouses").html('<option value="" selected="selected">Choose...</option>'); 
   $("select#StakeholdersOffices").html("<option>Please wait...</option>");
   $("select#Warehouses").html("<option value=''>Please wait...</option>");
   
   var pid = $("select#Stakeholders option:selected").attr('value');

   $.post("getfromajax.php", {ctype:1,id:pid}, function(data){
       $("select#StakeholdersOffices").html(data);
   });
});

 $("select#Provinces").change(function(){
   $("select#districts").html("<option value=''>Please wait...</option>");
   
   var bid = $("select#Provinces option:selected").attr('value');
   $.post("getfromajax.php", {ctype:8,id:bid}, function(data){
       $("select#districts").html(data);
   });
  });
$("select#districts").change(function(){
   $("select#Warehouses").html("<option value=''>Please wait...</option>");
   var bid = $("select#districts option:selected").attr('value');
   var pid = $("select#StakeholdersOffices option:selected").attr('value');

   $.post("getfromajax.php", {ctype:5,id:bid,id2:pid}, function(data){
       $("select#Warehouses").html(data);
   });
  });
});
	

	
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

</script>
  </tr>
  <tr>
    <td><form method="post" action="ManagewarehouseAction.php" name="managewarehouses" id="managewarehouses">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr>
		  <?php if($test=='true'){ ?>
		  <td width="25%" height="32"><div align="left">Stakeholder Name<a class="sb1Exception">*</a></div></td>
            <td width="28%"><div align="left"><?=$stkname?>
              <select name="Stakeholders" id="Stakeholders">
                <option value="">Choose...</option>
                <?php
				if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
				{
				  while($RowGroups = mysql_fetch_object($rsStakeholders))
				  {
				?>
                <option value="<?=$RowGroups->stkid?>">
                <?=$RowGroups->stkname?>
                </option>
				<?php
                  }
                }
                ?>
		  <?php } else { ?>
            <td width="25%" height="32"><div align="left">Stakeholder Name<a class="sb1Exception">*</a></div></td>
            <td width="28%"><div align="left">
              <select name="Stakeholders" id="Stakeholders">
                <option value="<?=$stakeholder12;?>"><?php echo $stkname1;?></option>
              </select>
            </div></td>
			<?php } ?>
            <td width="20%"><div align="left">Office Type<a class="sb1Exception">*</a></div></td>
            <td width="27%" valign="middle"><p id="txtStk"></p><?=$stkOffice?>
              <select name="StakeholdersOffices" id="StakeholdersOffices">
                <option value="">Choose...</option>
              </select></td>
          </tr>
          <tr>
		  <?php if($test=='true'){ ?>
		  <td><div align="left">Province<a class="sb1Exception">*</a></div></td>
            <td height="26" align="left" valign="middle"><p id="txtProv"></p>
			<select name="Provinces" id="Provinces">
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
              </select></td>
           
		<?php } else { ?>
		 <td><div align="left">Province<a class="sb1Exception">*</a></div></td>
            <td height="26" align="left" valign="middle"><p id="txtProv"></p>
              <select name="Provinces" id="Provinces">     
                <option value="<?=$provinceidd1;?>"><?=$provincename1;?></option>
              </select></td>
		<?php } ?>
            <td><div align="left">District<a class="sb1Exception">*</a></div></td>
            <td valign="middle"><p id="txtDist"></p><?=$district?>
              <select name="districts" id="districts">
                <option value="">Choose...</option>
              </select></td>
          </tr>
          <tr>
            <td><div align="left">Warehouse / Store Name<a class="sb1Exception">*</a></div></td>
            <td colspan="4">&nbsp;&nbsp;
              <div align="left">
                <!-- <a href="" onclick="window.open('ManageCategory.php','','height=600,width=600,scrollbars=yes');return false;">Add Another</a>    -->            
                <input name="wh_name" id="wh_name" type="text" value="<?php echo $wh_name; ?>" size="30" />
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
