<?php
//include("../../html/adminhtml.inc.php");
include_once("../../plmis_inc/common/CnnDb.php");


$type = $_REQUEST["type"];
if ($type == '1')
{
	$stkFilter = 'AND stk_type_id = 1';
	$stk = '5';
}
else if ($type == '0')
{
	$stkFilter = ' AND stk_type_id = 0';
	$stk = '1';
}
else
{
	$stkFilter = ' AND stk_type_id IN (0, 1)';
}



       $query = "SELECT stkid,stkname FROM stakeholder Where ParentID is null $stkFilter order by stkorder";
        
       $rsstk = mysql_query($query);
      echo '<option value="all">All</option>';
       while ($result = mysql_fetch_array($rsstk))
{
	if ($stk == $result['stkid'])
		$sel = "selected='selected'";
	else
		$sel = "";
	?>
   <option value="<?php echo $result['stkid'];?>" <?php  echo $sel; ?> ><?php echo $result['stkname']; ?></option>
    <?php
}

      
?>