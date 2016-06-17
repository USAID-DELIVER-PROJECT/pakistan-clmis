<?php

/**
 * index
 * @package default
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Including files
require("../includes/classes/Configuration.inc.php");
require(APP_PATH."includes/classes/clsConfiguration.php");
require(APP_PATH."includes/classes/clsDatabaseManager.php");
require(APP_PATH."includes/classes/db.php");
require(APP_PATH."includes/classes/clsLogin.php");
$strMsg = NULL;

if (isset($_REQUEST['login']) && !empty($_REQUEST['login'])) {
    //Getting pass
    if (isset($_REQUEST['pass']) && !empty($_REQUEST['pass'])) {
        $objLogin = new clsLogin();
        $objLogin->m_strPass = mysql_real_escape_string($_REQUEST['pass']);
        $objLogin->m_login = mysql_real_escape_string($_REQUEST['login']);
        $user = $objLogin->Login();
        
		if ($user['numOfRec'] > 0){
			$ip = $_SERVER['REMOTE_ADDR'];
			$qry = "INSERT INTO tbl_user_login_log
				SET
					user_id = ".$user['UserID'].",
					ip_address = '".$ip."',					
					login_time = NOW() ";
			mysql_query($qry);
			
			$user_role = $user['sysusr_type'];
			//UserID
			$_SESSION['user_id'] = $user['UserID'];
			//sysusr_type
			$_SESSION['user_role'] = $user['sysusr_type'];
			//sysusr_name
			$_SESSION['user_name'] = $user['sysusr_name'];
			//wh_id
			$_SESSION['user_warehouse'] = $user['wh_id'];
			//stkid
			$_SESSION['user_stakeholder'] = $user['stkid'];
			//stkid
			$_SESSION['user_stakeholder_office'] = $user['stkofficeid'];
			//user_level
			$_SESSION['user_level'] = $user['lvl'];
			// User Province
			$_SESSION['user_province'] = $user['prov_id'];
			//User district
			$_SESSION['user_district'] = $user['dist_id'];
			//IM Access
			$_SESSION['is_allowed_im'] = $user['is_allowed_im'];
			//stk_type_id
			$_SESSION['user_stakeholder_type'] = $user['stk_type_id'];
			//user_province
			$_SESSION['user_province1'] = $user['user_province'];
			//user_stk
			$_SESSION['user_stakeholder1'] = $user['user_stk'];
			//landing_page
			$_SESSION['landing_page'] = $user['landing_page'];
			
			if($user_role == 1){
				$_SESSION['menu'] = PUBLIC_PATH.'html/menu_superadmin.php';
			}else{	
				$_SESSION['menu'] = PUBLIC_PATH.'html/top.php';
			}
			
			$url = SITE_URL . $user['landing_page'];
			echo "<script>window.location='$url'</script>";
            exit;
        } else {
			$_SESSION['err'] = 'Username/Password is incorrect.';
            echo "<script>window.location='$url'</script>";
            exit;
        }
    }
	else{
		$_SESSION['err'] = 'Please enter Login Details';
        $url = SITE_URL . 'index.php';
		echo "<script>window.location='$url'</script>";
    }
}
else
{
        //Setting error message
	$_SESSION['err'] = 'Please enter username';
	$url = SITE_URL . 'index.php';
	echo "<script>window.location='$url'</script>";
}