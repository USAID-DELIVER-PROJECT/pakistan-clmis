<?php

/**
 * auth
 * @package api
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

//checking auth
if(isset($_GET['auth']) && !empty($_GET['auth']))
{
	$auth = mysql_real_escape_string($_GET['auth']);
	//Query for auth
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
                //If authentication fails
		print json_encode(array('message'=>'Authentication failed.'));
		exit;
	}
}
else
{
         //If authentication fails
	print json_encode(array('message'=>'Authentication failed.'));
	exit;
}
?>