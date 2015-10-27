<?php
$act=2;
include("Includes/AllClasses.php");
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
$wh_name="";
$test='false';

include("xml/xml_genaration_user.php");

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

if($stakeholder=='-1' && $provinceidd=='-1'){
	$test='true'; //checking it as administrator
}

function deleteFile($dir, $fileName)
{
	$handle=opendir($dir);
	
	while (($file = readdir($handle))!==false)
	{
		if ($file == $fileName)
		{
			@unlink($dir.'/'.$file);
		}
	}
	closedir($handle);
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
	//deleting image from the folder
	$sql="select sysusr_photo from sysuser_tab where UserID = '".$nstkId."'";	
	$result = mysql_fetch_array(mysql_query($sql));
	
	//deleting previous image
	if($result['sysusr_photo']){
		deleteFile('images/',$result['sysusr_photo']);
	}
	
	$objuser->m_npkId=$nstkId; 	
	$objuser->DeleteUser();
	
	//deleting from warehouse user table
	$objwharehouse_user->m_sysusrrec_id=$nstkId;
	$objwharehouse_user->Deletewh_userbyuserid();
	header("location:ManageUser.php");
}

if($strDo == "Edit")
{
	
	$objuser->m_npkId=$nstkId;
	$rsuser=$objuser->GetUserByUserID(); 
	if($rsuser!=FALSE && mysql_num_rows($rsuser)>0)
	{
		$RowEditStk = mysql_fetch_object($rsuser);
		$stkid=$RowEditStk->stkid;
		$stkname=$RowEditStk->stkname;
		$province22=$RowEditStk->province;
		//$stkOfficeId = $RowEditStk->stkofficeid;
		$district = $RowEditStk->district;
		//$dist_id = $RowEditStk->dist_id;
		//retrieving optional values
		$wh_id=$RowEditStk->wh_id;
		$wh_name=$RowEditStk->wh_name;
		$usrlogin_id=$RowEditStk->usrlogin_id;
		$sysusr_pwd=$RowEditStk->sysusr_pwd;
		$sysusr_name=$RowEditStk->sysusr_name;
		$sysusr_email=$RowEditStk->sysusr_email;
		$sysusr_ph=$RowEditStk->sysusr_ph;
		$sysusr_fax=$RowEditStk->sysusr_fax;
		$sysusr_addr=$RowEditStk->sysusr_addr;
		$sysusr_dept=$RowEditStk->sysusr_dept;
		$sysusr_deg=$RowEditStk->sysusr_deg;
		//retrieving user id
		$sysusr_UserID=$RowEditStk->UserID;
		//retrieving warehouse name
		
	}
}



$objuser->m_stkid=$stakeholder;
$objuser->m_provid=$provinceidd;
$objuser1 = $objuser->GetAllUser1();

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
<title>Manage Users</title>

<style>
body{
font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
font-size:0.8em;
color:Black;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}
/* ----------- My Form ----------- */
.myform{
margin:0 auto;
width:100%;
padding:5px;
height:330px;
}

/* ----------- stylized ----------- */
#stylized{
border:solid 1px #007000;
background:#DBE9D4;
}
#stylized h1 {
font-size:16px;
font-weight:bold;
margin-bottom:8px;
}
#stylized p{
font-size:10px;
color:#666666;
margin-bottom:10px;
border-bottom:solid 1px #007000;
padding-bottom:5px;
}
#stylized label{
display:block;
/*font-weight:bold;
text-align:center;*/
width:234px;
float:left;
}
#stylized .small{
color:#D43037;
display:block;
font-size:10px;
font-weight:normal;
/*text-align:center;
width:234px;*/
}
#stylized input{
/*float:left;
width:80px;
margin:2px 0 5px 10px;
*/
font-size:12px;
padding:4px 2px;
border:solid 1px #007000;
}

#Warehouses1{
height:200px;
width:200px;
border:1px dotted  #007000;
}

#districts{
height:200px;
width:200px;
border:1px dotted  #007000;

}

#stylized button{
clear:both;
margin-left:150px;
width:125px;
height:31px;
text-align:center;
line-height:31px;
color:#FFFFFF;
font-size:11px;
font-weight:bold;
}

#subbut{
float:left;
font-size:12px;
width:100px;
padding:4px 2px;
border:solid 1px #007000;
margin:10px 50px 0px 0px;
}

#btnCancel{
float:left;
font-size:12px;
width:100px;
padding:4px 2px;
border:solid 1px #007000;
margin:10px 0px 0px 0px;
}

</style>

<!--<LINK HREF="<?php echo PLMIS_CSS;?>cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
<LINK HREF="<?php echo PLMIS_CSS;?>main.css" REL="STYLESHEET" TYPE="TEXT/CSS">-->
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
	window.location="ManageUser.php?Do=Edit&Id="+val;
}
function delFunction(val){
	if (confirm("Are you sure you want to delete the record?")){
		window.location="ManageUser.php?Do=Delete&Id="+val;
		}	
}


</script>  
<script>
var mygrid;
function doInitGrid(){
	mygrid = new dhtmlXGridObject('mygrid_container');
	mygrid.selMultiRows = true;
	mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
	mygrid.setHeader("<span title='Stakeholder'>Stakeholder</span>,<span title='Province'>Province</span>,<span title='District'>District</span>,<span title='Warehouse'>Warehouse</span>,<span title='Username'>Username</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
	mygrid.attachHeader(",,#select_filter");
	mygrid.setInitWidths("120,150,150,*,120,30,30");
	mygrid.setColAlign("left,left,left,left,left")
		mygrid.setColSorting("str");
	mygrid.setColTypes("ro,ro,ro,ro,ro,img,img");
	//mygrid.enableLightMouseNavigation(true);
	mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	mygrid.setSkin("light");
	mygrid.enablePaging(true, 10, 6, "recinfoArea");
	mygrid.setPagingSkin("toolbar", "dhx_skyblue");
	
	mygrid.init();
	mygrid.loadXML("xml/user.xml");
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
<body onload="doInitGrid()">
<!--style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;"-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td><?php include("header.php");?></td>
<script>

<?php if($test=='true'){ ?>
	$(function() {
		//Disabling sub-combos start 
		$("#districts").attr('disabled', false);
		$("#Warehouses").attr('disabled', false);
		// end
		
		$("#Provinces").change(function(){
			$("#districts").html("<option>Please wait...</option>");
			
			var bid = $("#Provinces").val();
			$.post("getfromajax.php", {ctype:3,id:bid}, function(data){
			$("#districts").html(data);
			});
		});
	});
<?php } else { ?>
				$(function() {
					<?php if($stakeholder!=0){ ?>	
						
						var bid =<?php echo $provinceidd; ?>;
						$.post("getfromajax.php", {ctype:3,id:bid}, function(data){
						$("#districts").html(data);
						});
					
					
					<?php } ?>
				
				});
	<?php } ?>
///////////// Function that will remove NULL values from array
function removeEmptyElem(ary) {
	for (var i=ary.length;i>=0;i--) {
		if (ary[i] == undefined)  {
			ary.splice(i, 1);
			}       
	}
	return ary;
}

function showwarehouse(){
	var districts=new Array();
	var bid=new Array();
	var id=new Array();
	
	for(var i=0; i < document.manageuser.select4.length; i++){
		if(document.manageuser.select4[i].checked == true){
			districts[i] = document.manageuser.select4[i].value;
			}	
	}
	
	var bid = removeEmptyElem(districts);
	//alert(bid);
	var pid = $("#Stakeholders").val();
	//alert(pid);
	$.post("getfromajax.php", {ctype:6,id:bid,id2:pid}, function(data){
	$("#Warehouses1").html(data);
	});

}
</script>
</tr>
<tr>
	<td>
		<div id="stylized" class="myform">
		<form method="post" action="ManageUserAction.php" name="manageuser" id="manageuser" enctype='multipart/form-data'>
		<table width="100%" class="myform" cellspacing="0" cellpadding="4" id="mytable1">
<tr>
<?php if($test=='true'){ ?>
	<td > <label>Stakeholder
	<span class="small">Select...</span>
	</label></td>
	<td width="20%"><?=$stkname?><select name="select" id="Stakeholders">
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
	<td width="10%"><p id="txtStk">Province</p>
	</td>
	<td width="15%"><?=$province22?>
	<select name="select3" id="Provinces">
	<option value="">Choose...</option>
	<?php
	if($rsloc!=FALSE && mysql_num_rows($rsloc)>0)
	{
		while($RowLoc = mysql_fetch_object($rsloc))
		{
			?>
			<option value="<?=$RowLoc->PkLocID?>" <?php if($RowLoc->PkLocID==$PkLocID) echo 'selected="selected"';?>>
			<?php echo $RowLoc->LocName; ?>
			</option>
			<?php
		}
	}
	?>
	</select></td>
	
	<?php } else {?>
	
	<td width="7%" height="41" >Stakeholder</td>
	<td width="20%"><select name="select" id="Stakeholders">
	<option value="<?php echo $stakeholder; ?>"><?php echo $stkname; ?></option>
	</select>
	</td>
	<?php 
	$sql="select "
	?>
	<td width="10%"><p id="txtStk">Province</p></td>
	<td width="15%">
	<select name="select3" id="Provinces">
	<option value="<?=$provinceidd;?>">
	<?php echo $provincename; ?>
	</option>
	</select></td>
	<?php } ?>
<td width="15%">District</td>
<td width="11%"><?php echo $district;?>
<div style="height:100px;overflow:scroll;" id="districts">
</div>
<label class="error" for="select4[]" generated="true"></label>
</td>
<td width="6%" rowspan="2">Warehouse</td>
<td width="15%" rowspan="2"><?php echo $wh_name; ?>
<div style="height:100px;overflow:scroll;" id="Warehouses1"></div>
<label class="error" for="warehouses[]" generated="true"></label>
</td>
</tr>
<tr>
<td>Login ID<a class="sb1Exception">*</a> </td>
<td><input type="text" name="usrlogin_id" value="<?=$usrlogin_id?>" id='usrlogin_id'> <!--<input type='button' id='check_username_availability' value='Check Availability'><div id='username_availability_result'></div>--></td>
<td>Password<a class="sb1Exception">*</a></td>
<td><input type="password" name="txtStkName2" id="txtStkName2" value="<?=$sysusr_pwd?>" /></td>
<td>Confirm Password<a class="sb1Exception">*</a> </td>
<td><input type="password" name="txtStkName22" value="<?=$sysusr_pwd?>" /></td>
</tr>
<tr>
<td>Full Name<a class="sb1Exception">*</a></td>
<td><input type="text" name="full_name" id='full_name' value="<?= $sysusr_name; ?>"></td>
<td>Email<a class="sb1Exception">*</a></td>
<td><input type="text" name="email_id" value="<?= $sysusr_email; ?>" id='email_id'></td>
<td>Phone/Cell No. (Enter digits only)<a class="sb1Exception">*</a></td>
<td><input type="text" name="phone_no" value="<?= $sysusr_ph; ?>" id='phone_no'></td>
<td>Fax No.</td>
<td><input type="text" name="fax_no" value="<?= $sysusr_fax; ?>" id='fax_no'></td>
</tr>
<tr>
<td>Address</td>
<td><input type="text" name="address" value="<?= $sysusr_addr; ?>" id='address'></td>
<td>User Picture</td>
<td><input type="file" name="sysusr_photo" id="sysusr_photo" /></td>
<td>Department</td>
<td><?=$sysusr_dept?>
<select name="sysusr_dept" class="new_Input" tabindex="8" title="Select Department">
<option value="No Department">--- Select Department ---</option><?
$strSQL="select distinct sysusr_dept from sysuser_tab where sysusr_dept not like'' order by sysusr_dept";
$rsTemp1=mysql_query($strSQL);
while($rsRow1=mysql_fetch_array($rsTemp1))
	
{            
	echo "<OPTION VALUE='$rsRow1[sysusr_dept]'>$rsRow1[sysusr_dept]</OPTION>";
}
mysql_free_result($rsTemp1);
?><option value="New">
New Department</option>
</select> 
</td>
<td>Designation</td>
<td><?=$sysusr_deg?><select name="sysusr_deg" id="sysusr_deg" class="new_Input" tabindex="7" title="Select Designation Code">
<option value="No Designation" >--- Select ---</option>
<?
$strSQL="select distinct sysusr_deg from sysuser_tab where sysusr_deg not like'' order by sysusr_deg";
$rsTemp1=mysql_query($strSQL);
while($rsRow1=mysql_fetch_array($rsTemp1))
	
{            
	echo "<OPTION VALUE='$rsRow1[sysusr_deg]'>$rsRow1[sysusr_deg]</OPTION>";
}
mysql_free_result($rsTemp1);
?>
</select></td>
</tr>
<tr>
<td colspan="3"><input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
<input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
<input type="submit" value="<?=$strDo?>" /><input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">           
<?php 
if(isset($_REQUEST['msg']) && !empty($_REQUEST['msg']))
{
	print '<p style=\'color:#FF0000\'>Error:'.$_REQUEST['msg']."</p>";
}
?>
</td>
<td colspan="7"></td>
</tr>

</table>															
		</form>
		</div>
	</td>
</tr>
</table>

<table width="100%" >
<tr>
<td  width="40%"></td>
<td  width="40%">
<!--Choose skin to apply: 
<select onChange="mygrid.setSkin(this.value)">
<option value="light" selected>Light
<option value="sbdark">SB Dark
<option value="gray">Gray
<option value="clear">Clear
<option value="modern">Modern
<option value="dhx_skyblue">Skyblue
</select>-->
</td>
<td align="right" width="20%">
<img title="Click here to export data to PDF file" style="cursor:pointer;" src="../plmis_img/pdf.bmp" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
<img title="Click here to export data to Excel file" style="cursor:pointer; margin-left:-5px" src="../plmis_img/excel.bmp" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
</td>
</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
<div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div>
</td>
</tr>
<tr>
<td>
<div id="recinfoArea"></div>
</td>
</tr>
</table>
<p>&nbsp;</p>
</body>
</html>
