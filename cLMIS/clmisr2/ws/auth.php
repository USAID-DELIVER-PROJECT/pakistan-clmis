<?php
if(isset($_GET['auth']) && !empty($_GET['auth']))
{
	$auth = mysql_real_escape_string($_GET['auth']);
	
	$query = "SELECT
				sysuser_tab.auth
			FROM
				sysuser_tab
			WHERE
				sysuser_tab.auth = '$auth' ";
	$num = mysql_num_rows(mysql_query($query));
	if( $num == 1 )
	{
		return true;
	}
	else
	{
		print json_encode(array('message'=>'Authentication failed.'));
		exit;
	}
}
else
{
	print json_encode(array('message'=>'Authentication failed.'));
	exit;
}
?>