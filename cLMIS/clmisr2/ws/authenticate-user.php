<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');

$query="SELECT
sysuser_tab.whrec_id as WHID,
tbl_warehouse.wh_name as WHName,
sysuser_tab.UserID as userId,
sysuser_tab.sysusr_name as userName
FROM
sysuser_tab
INNER JOIN tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
where sysuser_tab.auth = '$auth' ";

$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	$rows[] = $r;
}
print json_encode($rows);
// example: http://localhost/lmis/ws/locations.php?ID=4
?>