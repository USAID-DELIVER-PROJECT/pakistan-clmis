<?php
include("../../html/adminhtml.inc.php");

if ($_POST['type'] == 'private')
{
	$stkFilter = ' AND stk_type_id = 1';
}
else if ($_POST['type'] == 'public')
{
	$stkFilter = ' AND stk_type_id = 0';
}
else
{
	$stkFilter = '';
}

$querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null $stkFilter order by stkorder";
	echo '<option value="all">All</option>';
$rsstk = mysql_query($querystk) or die();
while ($rowstk = mysql_fetch_array($rsstk))
{
	if ($_POST['stk'] == $rowstk['stkid'])
		$sel = "selected='selected'";
	else
		$sel = "";
	?>
    <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
    <?php
}