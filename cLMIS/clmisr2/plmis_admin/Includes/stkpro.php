<?php

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

?>