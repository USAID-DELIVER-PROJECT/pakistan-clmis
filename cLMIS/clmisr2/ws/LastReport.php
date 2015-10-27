<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$ID =  $_REQUEST['ID'];
if(!empty($ID))
{
$query="SELECT 
( SELECT max(report_year) 
FROM tbl_wh_data 
WHERE tbl_wh_data.wh_id ='$ID') as ryear,
(SELECT max(report_month) as rmonth 
FROM tbl_wh_data 
WHERE tbl_wh_data.wh_id ='$ID' 
and report_year=
( (SELECT max(report_year) as rmonth 
FROM tbl_wh_data 
WHERE tbl_wh_data.wh_id ='$ID') )) as rmonth";
	
	//print $query;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
if (empty($r['rmonth']))
$m="1";
else
$m=$r['rmonth'];


if (empty($r['ryear']))
	$y="1991";
else
	$y=$r['ryear'];

		print $m."/1/".$y." 12:00:00 AM";

	}
	
}
else
print "1/1/1990";

//http://localhost/lmis/ws/LastReport.php?ID=3743
?>