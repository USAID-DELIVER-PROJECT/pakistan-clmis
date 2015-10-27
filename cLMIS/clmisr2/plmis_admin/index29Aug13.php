<?php


session_start();
require_once("Includes/Configuration.inc.php");
require_once("Includes/clsConfiguration.php");
require_once("Includes/clsDatabaseManager.php");
require_once("Includes/db.php");
require_once("Includes/clsLogin.php");
$strMsg = NULL;
	
if(isset($_REQUEST['login']) && !empty($_REQUEST['login']))
{
//print $_REQUEST['pass'];
	if(isset ($_REQUEST['pass']) && !empty($_REQUEST['pass']))
	{
		$objLogin = new clsLogin();
		$objLogin->m_strPass = $_REQUEST['pass'];
		$objLogin->m_login = $_REQUEST['login'];
		//print "before calling";
		$userid=$objLogin->Login();
		//print $userid[1];
		
		if(!empty($userid))
		{
		$UserType = $userid[1];
//		print "after calling";
//		print_r($userid);
		$_SESSION['userdata']=$userid;
		$_SESSION['userid']=$userid[0];
		$_SESSION['user'] = $userid[0];
		
 
		switch ($UserType)
			{
			case "UT-002":
			  $_SESSION['menu']="menuUser.php";
			  $_SESSION['vuser']=1;
			  header('location:../Cpanel.php');
			  break;
			 case "UT-005":
			  $_SESSION['menu']="menuUser.php";
			  $_SESSION['vuser']=1;
			  header('location:../Cpanel.php');
			  break;
			case "UT-001":
			  $_SESSION['menu']="menu.php";
			  $_SESSION['vuser']=1;
			  header('location:AdminHome.php');
			  break;
		    case "SA-001":
			  $_SESSION['menu']="menusadmin.php";
			  $_SESSION['vuser']=1;
			  header('location:AdminHome.php');
			  break;
			default:
			  header('location:../../index.php');
			  exit;
			}
			
	/*		header('location:../Cpanel.php');*/
			exit;
		}
		else 
		{ $strMsg = "Invalid Password.";
		 header('location:../../index.php');
			  exit;}
	}
	else
	$strMsg = "Please enter Login Details";
}
	else
	$strMsg = "Please enter Login Name";

if(isset($_REQUEST['strMsg']) && !empty($_REQUEST['strMsg']))
	$strMsg =$_REQUEST['strMsg'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login LMIS</title>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<div class="main content clearfix">
  <div class="wrapper">
    <table width="100%">
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td align="center"><img src="images/logo.png" alt="LMIS"  /> </td>
      </tr>
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td align="center"><h1>LMIS Administration Portal</h1></td>
      </tr>
      <tr>
        <td>&nbsp</td>
      </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" action="index.php">
            <label for="Email">Username</label>
            <input type="text" spellcheck="false" name="login"  value="" >
            <label>Password
            <input type="password" name="pass" id="pass" value="" />
            </label>
            <label>
            <input type="submit" name="Submit" id="Submit" value="Submit" />
            </label>
          </form></td>
      </tr>
      <tr>
        <td align="center"><span style="color:Red;"> <?php echo $strMsg;?> </span></td>
      </tr>
    </table>
  </div>
</div>
</div>
</body>
</html>
