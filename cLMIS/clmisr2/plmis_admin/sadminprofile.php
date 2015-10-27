<?php 
include("Includes/AllClasses.php");
require_once("Includes/clsLogin.php");
include_once("fckeditor/fckeditor.php") ;

$objuser->m_npkId=$_SESSION['userid'];

$rsuser=$objuser->GetSuperAdminbyID();
	
if($rsuser!=FALSE && mysql_num_rows($rsuser)>0){
	
	
	$RowEditStk = mysql_fetch_object($rsuser);
	$loginid=$RowEditStk->usrlogin_id;
	$fullname=$RowEditStk->sysusr_name;
	$emailid=$RowEditStk->sysusr_email;
	$phoneno=$RowEditStk->sysusr_ph;
	$faxno=$RowEditStk->sysusr_fax;
	$address=$RowEditStk->sysusr_addr;
	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator's Profile</title>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><?php include("header.php");?></td>
      </tr>
      <tr>
        <td>
        	<form id="manage_profile" name="manage_profile" method="post" action="ManagesProfile.php" enctype='multipart/form-data'>
                <table width="70%" align="center">
                    <tr>
                        <td width="200"><label>Login ID</label></td>
                        <td><input type="text" name="usrlogin_id" id="usrlogin_id" value="<?php echo $loginid;?>" size="30px"/>
                         </td>
                    </tr>
                    <tr>
                        <td><label>Full Name</label></td>
                        <td><input type="text" name="full_name" id="full_name" value="<?php echo $fullname;?>" size="30px"/></td>
                    </tr>
                    <tr>
                        <td valign="top"><label>Email</label></td>
                        <td>
                       <input type="text" name="email_id" id='email_id' value="<?php echo $emailid;  ?>" size="30px">
                        </td>
                    </tr>
                    <tr>
                    	<td>Phone No</td>
                        <td><input type="text" name="phone_no" id='phone_no' value="<?php echo $phoneno;?>" size="30px">
            			</td>
                    </tr>
                    <tr>
                       <td>Fax No.</td>
                      <td><input type="text" name="fax_no" id='fax_no' value="<?php echo $faxno;?>" size="30px"></td>
                    </tr>
                    <tr> <td>Address</td>
            <td><input type="text" name="address" id='address' value="<?php echo $address;?>" size="30px"></td>
                    </tr>
                    <tr>
                    	<td>
             			<input type="submit" value="Update" /><input name="btnAdd" type="button" id="btnCancel" value="Cancel" OnClick="window.location='<?=$_SERVER["PHP_SELF"];?>';">
                        </td>
                        <td>
                       </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
    <tr>
        <td>All Rights Reserved</td>
    </tr>
</table>
</body>
</html>