<?php
$act=2;
include("Includes/AllClassesUnSecure.php");
$strDo = "Add";
$nstkId =0;
$stkid=0;
$prov_id=0;
$dist_id=0;
$usrlogin_id="";
$sysusr_pwd="";


$rsStakeholders = $objstk->GetAllStakeholders();
$objloc->LocLvl=2;
$rsloc=$objloc->GetAllLocations();


?>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
</script>
<script>
$(document).ready(function Stakeholders() {

//Disabling sub-combos start 
$("select#districts").attr('disabled', 'disabled');
$("select#Warehouses").attr('disabled', 'disabled');
// end

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
   var pid = $("select#Stakeholders option:selected").attr('value');

   $.post("getfromajax.php", {ctype:6,id:bid,id2:pid}, function(data){
       $("select#Warehouses").removeAttr("disabled");
       $("select#Warehouses").html(data);
   });
  });

});

</script>

        <table width="100%" border="1" cellspacing="0" cellpadding="4">
		<tr>
            <td width="7%" height="41">Stakeholder</td>
            <td width="20%"><?=$stkname?><select name="select" id="Stakeholders">
              <option value="0">Choose...</option>
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
            </div></td>
            <td width="10%"><p id="txtStk">Province</p></td>
            <td width="21%"><?=$province?>
              <select name="select3" id="Provinces">
                <option value="0">Choose...</option>
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
            <td width="17%">District</td>
            <td width="12%"><?=$district?>
              <select name="select4" id="districts">
                <option value="0">Choose...</option>
              </select></td>
		    <td width="6%">Warehouse</td>
		    <td width="7%"><?=$wh_name?><select name="select5" id="Warehouses" multiple="multiple" size="5">
            </select></td>
		</tr>
        </table>
     
