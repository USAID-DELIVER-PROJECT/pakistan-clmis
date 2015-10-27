<?php
$act=2;
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;
$sysusrrec_id=0;
$sysusr_type=0;
$whrec_id=0;
$usrlogin_id="";
$usrlogin_id=0;

$stkid=0;
$stkname=0;
$province=0;
$prov_title="";
$whrec_id=0;

$dist_id=0;
$ParentID=0;
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
	$rsEditCat = $objuser->DeleteUser;
}

if($strDo == "Edit" || $strDo == "Delete" )
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
$objwarehouse1 = $objwarehouse->GetAllWarehouses();
//$objprovince = $objprovince->GetAllprovince();
//$objwhtype = $objwhtype->GetAllwhtype();

//$objdistric = $objdistric->GetAlldistric();
///$objstk->GetAllStakeholdersLeaves();
//$rsstktype = $objstkType->GetAllstk_types();
//$rsranks=$objstk->GetRanks();



$rsStakeholders = $objstk->GetAllStakeholders();
//$rsGroups = $objstk->GetStakeholderGroups();
//$rsstktype = $objstkType->GetAllstk_types();
//$rsranks=$objstk->GetRanks();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Passport</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
</script>
<script>
$(document).ready(function Stakeholders() {

//Disabling sub-combos start 
$("select#Provinces").attr('disabled', 'disabled');
$("select#StakeholdersOffices").attr('disabled', 'disabled');
$("select#districts").attr('disabled', 'disabled');
$("select#Warehouses").attr('disabled', 'disabled');
// end

$("select#Stakeholders").change(function(){
   
   $("select#Warehouses").attr('disabled', 'disabled'); // if changed after last element has been selected, will reset last boxes choice to default

   $("select#Warehouses").html('<option selected="selected">Choose...</option>'); 
   $("select#StakeholdersOffices").html("<option>Please wait...</option>");
   $("select#districts").html("<option>Please wait...</option>");
   $("select#Warehouses").html("<option>Please wait...</option>");
   
   var pid = $("select#Stakeholders option:selected").attr('value');

   $.post("getfromajax.php", {ctype:1,id:pid}, function(data){
       $("select#StakeholdersOffices").removeAttr("disabled");
       $("select#StakeholdersOffices").html(data);
   });
});

$("select#StakeholdersOffices").change(function(){
    $("select#Provinces").html("<option>Please wait...</option>");
     var bid = $("select#StakeholdersOffices option:selected").attr('value');

   $.post("getfromajax.php", {ctype:2,id:bid}, function(data){
       $("select#Provinces").removeAttr("disabled");
       $("select#Provinces").html(data);
   });
  });


 $("select#Provinces").change(function(){
   $("select#districts").html("<option>Please wait...</option>");
   
   var bid = $("select#Provinces option:selected").attr('value');
  // var pid = $("select#Stakeholders option:selected").attr('value');

   $.post("getfromajax.php", {ctype:3,id:bid}, function(data){
       $("select#districts").removeAttr("disabled");
       $("select#districts").html(data);
   });
  });
  


$("select#districts").change(function(){
   $("select#Warehouses").html("<option>Please wait...</option>");
   var bid = $("select#districts option:selected").attr('value');
   var pid = $("select#StakeholdersOffices option:selected").attr('value');

   $.post("getfromajax.php", {ctype:5,id:bid,id2:pid}, function(data){
       $("select#Warehouses").removeAttr("disabled");
       $("select#Warehouses").html(data);
   });
  });
  




});

</script>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php include("header.php");?></td>
  </tr>
  <tr>
    <td><form method="post" action="">
        <table width="100%" border="1" cellspacing="0" cellpadding="4">
		<tr>
            <td>Stakeholder</td>
            <td><select name="select" id="Stakeholders">
              <option value="0">Choose...</option>
              <?php
    if($rsStakeholders!=FALSE && mysql_num_rows($rsStakeholders)>0)
  	{
	  while($RowGroups = mysql_fetch_object($rsStakeholders))
	  {
	?>
              <option value="<?=$RowGroups->stkid?>" <?php if($RowGroups->stkid==$stkid) echo 'selected="selected"';?>>
              <?=$RowGroups->stkname?>
              </option>
              <?php
	  }
	}
	?>
            </select>
            </div></td>
            <td width="20%"><div align="left">Office Type</div></td>
            <td width="27%"><p id="txtStk"><?=$stkOffice?>
              <select name="select2" id="StakeholdersOffices">
                <option value="0">Choose...</option>
              </select>
            </p></td>
          </tr>
          <tr>
            <td><div align="left">Province</div></td>
            <td><p id="txtProv"></p><?=$province?>
              <select name="select3" id="Provinces">
                <option value="0">Choose...</option>
              </select></td>
            <td><div align="left">District</div></td>
            <td><p id="txtDist"></p><?=$district?>
              <select name="select4" id="districts">
                <option value="0">Choose...</option>
              </select></td>
          </tr>
          <tr>
            <td><div align="left">Warehouse</div> </td>
            <td colspan="3">
			<p id="txtWH">
			  <select name="select5" id="Warehouses" multiple="multiple" size="5">
                <option value="0">Choose...</option>
              </select>
			</p></td>
          </tr>
          <tr>
            <td>User Name </td>
            <td colspan="3"><input type="text" name="txtStkName" value="<?=$usrlogin_id?>" /></td>
          </tr>
          
		  
		  <tr>
            <td>Password</td>
            <td><input type="password" name="txtStkName2" value="<?=$sysusr_pwd?>" /></td>
            <td>Confirm Password </td>
            <td><input type="password" name="txtStkName22" value="<?=$sysusr_pwd?>" /></td>
		  </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="3"><input type="hidden" name="hdnstkId" value="<?=$nstkId?>" />
              <input  type="hidden" name="hdnToDo" value="<?=$strDo?>" />
              <input type="submit" value="<?=$strDo?>" />            </td>
          </tr>
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr style="background-color:#CCC">
                  <td>Stakeholder</td>
                  <td>Province </td>
                  <td>District</td>
                  <td>Warehouse</td>
				  <td>User Name </td>
                  <td>action</td>
                </tr>
                <?php
  if($objwarehouse1!=FALSE && mysql_num_rows($objwarehouse1)>0)
  {
	  while($Rowwarehouse = mysql_fetch_object($objwarehouse1))
	  {
		
  ?>
                <tr>
                  <td><?=$Rowwarehouse ->stkname?></td>
                  <td><?=$Rowwarehouse ->province?></td>
                  <td><?=$Rowwarehouse ->district?></td>
				  <td><?=$Rowwarehouse ->wh_name?></td>
				  <td></td>
                  <td><a href="<?=$_SERVER['PHP_SELF']?>?Do=Edit&amp;Id=<?=$RowStakeholders->stkid?>">Edit</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?Do=Delete&amp;Id=<?=$RowStakeholders->stkid?>">Delete </a></td>
                </tr>
                <?php
	  }
  }
  ?>
              </table></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr>
    <td>All Rights Reserved</td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
