<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$ID = $_REQUEST['ID'];
$query = "SELECT
			stkid,
			stkname,
			report_title1,
			report_title2,
			report_title3,
			report_logo,
			stkcode,
			stkorder
		FROM
			stakeholder";
if(!empty($ID))
{
	$query = $query." WHERE stkid ='$ID' ";
}
$query .= " ORDER BY stkname";
//print $query;
$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);
?>