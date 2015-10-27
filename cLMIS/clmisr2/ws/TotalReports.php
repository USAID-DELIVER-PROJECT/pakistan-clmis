<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$ID =  $_REQUEST['ID'];

if(!empty($ID))
{

$query="SELECT Count(a.m) as tot FROM
(SELECT distinct report_month as m,report_year
FROM
tbl_wh_data WHERE tbl_wh_data.wh_id ='$ID') a ";
	
//print $query;

$rs = mysql_query($query) or die(print mysql_error());
$r = mysql_fetch_assoc($rs);

if (!empty($r['tot']))
print $r['tot'];
else
print "0";

	}
	

else
print "-1";

//http://localhost/lmis/ws/warehousereport.php?ID=3743&YYYY=2011&MM=5
?>