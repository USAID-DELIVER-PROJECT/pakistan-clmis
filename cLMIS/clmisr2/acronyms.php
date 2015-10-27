<?php
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file which will the contents from tbl_cms
/***********************************************************************************************************/
include ("plmis_inc/common/CnnDb.php");
include ("plmis_inc/classes/cCms.php");	

	
	
	$db = new Database();
	$db->connect();
	$objContents = new cCms();
	
	$articles_array = array();
	
	
	$sql = $objContents->Select("tbl_cms", " * "," and title='Acronyms'");
	if($db->query($sql) and $db->get_num_rows()){ 
		for($i=0;$i<$db->get_num_rows();$i++)
		{
		$row = $db->fetch_one_assoc();		
		array_push($articles_array,$row);
		}		
	}
	
	
include "plmis_inc/common/Global.php";

//include_once('config.inc.php');
include_once('html/html.inc.php');
startHtml($articles_array[0]['title']);
siteMenu("Acronyms");
leftContents(stripslashes($articles_array[0]['description']), $articles_array[0]['title']);
rightContents();
footer();
endHtml();


?>

