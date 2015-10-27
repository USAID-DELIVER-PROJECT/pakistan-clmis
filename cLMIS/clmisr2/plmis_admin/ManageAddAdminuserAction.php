<?php
include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;
$stkid=0;
$prov_id=0;
$dist_id=0;
$usrlogin_id="";
$sysusr_pwd="";
$wh_id=array('');

if(isset($_REQUEST['hdnstkId']) && !empty($_REQUEST['hdnstkId']))
{
	// in case of edit
	$nstkId = $_REQUEST['hdnstkId'];
}
if(isset($_REQUEST['hdnToDo']) && !empty($_REQUEST['hdnToDo']))
{
	//add or edit
	$strDo = $_REQUEST['hdnToDo'];
}
if(isset($_REQUEST['select']) && !empty($_REQUEST['select']))
{
	//stakeholder
	$stkid = $_REQUEST['select'];
}
else{
	$stkid=0;
}
if(isset($_REQUEST['select3']) && !empty($_REQUEST['select3']))
{
	//province
	$prov_id = $_REQUEST['select3'];
}
else{
	$prov_id = 0;
}

if(isset($_REQUEST['usrlogin_id']) && !empty($_REQUEST['usrlogin_id']))
{
	//login name
	$usrlogin_id = $_REQUEST['usrlogin_id'];
}
if(isset($_REQUEST['txtStkName2']) && !empty($_REQUEST['txtStkName2']))
{
	//password
	$sysusr_pwd = $_REQUEST['txtStkName2'];
}

if(isset($_REQUEST['full_name']) && !empty($_REQUEST['full_name']))
{
	//full name
	$full_name = $_REQUEST['full_name'];
}

if(isset($_REQUEST['email_id']) && !empty($_REQUEST['email_id']))
{
	//email id
	$email_id = $_REQUEST['email_id'];
}

if(isset($_REQUEST['phone_no']) && !empty($_REQUEST['phone_no']))
{
	//phone no
	$phone_no = $_REQUEST['phone_no'];
}

if(isset($_REQUEST['fax_no']) && !empty($_REQUEST['fax_no']))
{
	//fax no
	$fax_no = $_REQUEST['fax_no'];
}

if(isset($_REQUEST['address']) && !empty($_REQUEST['address']))
{
	//address
	$address = $_REQUEST['address'];
}

if(isset($_REQUEST['sysusr_type']) && !empty($_REQUEST['sysusr_type']))
{
   /* echo $_REQUEST['sysusr_type'];
	exit;*/
	//select user type
	$sysusr_type = $_REQUEST['sysusr_type'];
}

//Filling value in stakeholder objects variables
$objuser->m_npkId = $nstkId;
$objuser->m_stkid = $stkid;
$objuser->m_stkname = $stkname;
$objuser->m_prov_id = $prov_id;
$objuser->m_usrlogin_id = $usrlogin_id;
$objuser->m_sysusr_pwd = $sysusr_pwd;
  //adding additional options
$objuser->m_full_name = $full_name;
$objuser->m_email_id = $email_id;
$objuser->m_phone_no = $phone_no;
$objuser->m_fax_no = $fax_no;
$objuser->m_address = $address;
$objuser->m_sysusr_type = $sysusr_type;

if($strDo=="Edit")
{
   //edit user 
   $objuser->EditUser();
   //deleting already existing values
   $objwharehouse_user->m_npkId=$nstkId;   	
}
if($strDo=="Add")
{
	$sql="select usrlogin_id from sysuser_tab where usrlogin_id = '".$usrlogin_id."'";
	$result = mysql_query($sql);

	if($result!=FALSE && mysql_num_rows($result)>0)
	{
		header("location:ManageUser.php?msg=username+is+not+available");
		exit;
	} 
	//save user
	$objuser->AddUser();
	//get user ID 
	$rez=$objuser->GetUserByid();
	if($rez!=FALSE && mysql_num_rows($rez)>0)
	{
		while($Rowobjuser = mysql_fetch_object($rez))
		{
			$usrID=$Rowobjuser->UserID;
		}
	}
}
if($strDo=="Delete")
	{
	$objuser->DeleteUser();
	}
header("location:ManageAddAdminuser.php");
exit;
?>