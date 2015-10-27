<?php

include("Includes/AllClasses.php");
$strDo = "Add";
$nstkId =0;

function deleteFile($dir, $fileName)
{
	$handle=opendir($dir);
	
	while (($file = readdir($handle))!==false)
	{
		if ($file == $fileName)
		{
			@unlink($dir.'/'.$file);
		}
	}
	closedir($handle);
	} 

$homepage="0";
if(isset($_REQUEST['ishomepage']) && !empty($_REQUEST['ishomepage']))
{
	// in case of homepage
	$homepage = $_REQUEST['ishomepage'];
	if($homepage=='on'){
		
		$homepage=1;
	}
}

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
if(isset($_REQUEST['page_title']) && !empty($_REQUEST['page_title']))
{
	// page title
	$page_title = $_REQUEST['page_title'];
}
if(isset($_REQUEST['page_heading']) && !empty($_REQUEST['page_heading']))
{
	// page heading 
	$page_heading = $_REQUEST['page_heading'];
}
if(isset($_REQUEST['page_description']) && !empty($_REQUEST['page_description']))
{
	// page description
	$page_description = $_REQUEST['page_description'];
}
if(isset($_REQUEST['stakeholders']) && !empty($_REQUEST['stakeholders']))
{
	// stakeholders
	$stakeholders = $_REQUEST['stakeholders'];
}
else
	$stakeholders="0";

if(isset($_REQUEST['provinces']) && !empty($_REQUEST['provinces']))
{
	//provinces
	$provinces = $_REQUEST['provinces'];
}
else
	$provinces="0";

//Filling value in stakeholder objects variables
$objContent->m_npkId = $nstkId;
$objContent->m_page_title = $page_title;
$objContent->m_page_heading = $page_heading;
$objContent->m_page_description = $page_description;
$objContent->m_stakeholders = $stakeholders;
$objContent->m_provinces = $provinces;
$objContent->m_homepage=$homepage;

if($strDo=="Edit")
{
	if(!empty($_FILES['logo']['name'])){
		
		//deleting previous image
		
		$sql="select logo from tbl_cms where id = '".$nstkId."'";	
		$result = mysql_fetch_array(mysql_query($sql));
		
		if(!empty($result['logo'])){
			deleteFile('images/',$result['logo']);
		}
		
		$ext = explode('.',$_FILES['logo']['name']);
		$logoimg = time().'.'.$ext[1];
		move_uploaded_file($_FILES['logo']['tmp_name'],'images/'.$logoimg);
		$objContent->m_logo = $logoimg;
	}
	//edit content 
	//deleting already existing values
	$objContent->m_npkId=$nstkId;
	$objContent->Editlogocontent();
	
	//editing values of warehouses
	
	
}
if($strDo=="Add")
{
	if($homepage==1){
	$sql="select * from tbl_cms where Stkid = '".$stakeholders."' AND province_id='".$provinces."' AND homepage_chk='".$homepage."'";
	$result = mysql_fetch_array(mysql_query($sql));
	
	$result = mysql_fetch_array(mysql_query($sql));
	
	$result = mysql_query($sql);
	
	if($result!=FALSE && mysql_num_rows($result)>0)
	{
		header("location:AddEditContent.php?msg=stakeholder+and+province+not+available");
		exit;
	}
	}
	// Add image
	if(!empty($_FILES['logo']['name'])){
		$ext = explode('.',$_FILES['logo']['name']);
		$logoimg = time().'.'.$ext[1];
		move_uploaded_file($_FILES['logo']['tmp_name'],'images/'.$logoimg);
		$objContent->m_logo = $logoimg;
		$logoField->m_logo = $logoimg;
	}
	//save user
	$objContent->Addlogocontent();
	
}
header("location:view_admin_content.php");

exit;
?>