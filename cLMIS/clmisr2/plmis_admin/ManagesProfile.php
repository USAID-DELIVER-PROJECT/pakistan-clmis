<?php
include("Includes/AllClasses.php");

$userid=$_SESSION['userid'];


if(isset($_REQUEST['usrlogin_id']) && !empty($_REQUEST['usrlogin_id']))
{
	//login name
	$usrlogin_id = $_REQUEST['usrlogin_id'];
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


//Filling value in stakeholder objects variables

$objuser->m_usrlogin_id = $usrlogin_id;
$objuser->m_full_name = $full_name;
$objuser->m_email_id = $email_id;
$objuser->m_phone_no = $phone_no;
$objuser->m_fax_no = $fax_no;
$objuser->m_address = $address;

$objuser->m_npkId = $_SESSION['userid'];

$objuser->EditUser();


header("location:sadminprofile.php");
exit;
?>