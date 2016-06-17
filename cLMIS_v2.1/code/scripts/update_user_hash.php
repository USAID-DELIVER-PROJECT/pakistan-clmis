<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");

// Get all districts
$qry = "SELECT
			sysuser_tab.usrlogin_id,
			sysuser_tab.sysusr_pwd
		FROM
			sysuser_tab";
$qryRes = mysql_query($qry);

while ($row = mysql_fetch_array($qryRes)) {
    $username = strtolower($row['usrlogin_id']);
    $password = base64_decode($row['sysusr_pwd']);
    $hash = md5($username . '' . $password);

    $updateQry = "UPDATE
					sysuser_tab
				SET 
					sysuser_tab.auth = '$hash'
				WHERE
					sysuser_tab.usrlogin_id = '" . $row['usrlogin_id'] . "' 
					AND sysuser_tab.sysusr_pwd = '" . $row['sysusr_pwd'] . "' ";
    mysql_query($updateQry);
}