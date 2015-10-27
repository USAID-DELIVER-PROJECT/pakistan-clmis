<?php
error_reporting(0);
session_start();

define('MAINSITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmis/');

define('SITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmis/');

//define('SITE_PATH','/home/lmispcgo/public_html/clmis/');
define('SITE_PATH',$_SERVER['DOCUMENT_ROOT'].'/clmis/');
define('PLMIS_INC',SITE_PATH.'plmis_inc/');
define('PLMIS_SRC',SITE_URL.'plmis_src/');
define('PLMIS_CSS',SITE_URL.'plmis_css/');
define('ADMIN_CSS',SITE_URL.'css/');
define('PLMIS_JS',SITE_URL.'plmis_js/');
define('PLMIS_IMG',SITE_URL.'plmis_img/');
define('ADMIN_IMG',SITE_URL.'images/');
define('REPORT_XML_PATH', SITE_PATH."plmis_src/reports/xml/");
define('GRID_XML_PATH', SITE_PATH."plmis_src/operations/xml/");

define('PLMIS_ADMIN',SITE_URL.'plmis_admin/');
define('ADMIN_IMGS',PLMIS_ADMIN.'images/');



include_once(PLMIS_INC.'common/Global.php');  //Include Global Variables File
include_once(PLMIS_INC.'common/DateTime.php');    //Include Date Function File
include_once(PLMIS_INC."common/CnnDb.php");   //Include Database Connection File
include_once(PLMIS_INC."common/FunctionLib.php"); //Include Global Function File
include_once(PLMIS_INC."form/plmis_form_globals.php"); 
			
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<?php
function startHtml($title = "")

{?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo !empty($title)?''.$title:''; ?></title>
    
    <?php /*?><link href="<?php echo PLMIS_CSS;?>style.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>main.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>cpanel.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>new_forms.css" rel="STYLESHEET" type="TEXT/CSS">
	<LINK ID="GridCSS" href="<?php echo PLMIS_CSS;?>Grid.css" TYPE="TEXT/CSS" REL="STYLESHEET"><?php */?>
    
 	
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
	 <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
     <script src="<?php echo PLMIS_JS;?>jquery-1.4.4.js" type="text/javascript"></script>
     <script src="<?php echo PLMIS_JS;?>jquery.autoheight.js" type="text/javascript"></script>
     <link href="<?php echo PLMIS_JS;?>facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
     <script src="<?php echo PLMIS_JS;?>facebox/facebox.js" type="text/javascript"></script> 
     <script type="text/javascript">
                jQuery(document).ready(function($) {
                  $('a[rel*=facebox]').facebox({
                    loading_image : '<?php echo PLMIS_IMG;?>loading.gif',
                    close_image   : '<?php echo PLMIS_IMG;?>closelabel.gif'
                  }) 
                })
     </script>
 
    </head>
	<?php }
	
function siteMenu() { //echo PLMIS_INC;?>
    <?php include(SITE_PATH.'header.php');
}
function contents($contents){?>
	<div class="wrraper">
		<div class="content">
    		<?php echo $contents;?>       
   		</div>
    </div>
<?php }

function footer(){?>
	<div style="clear:both"></div>
<div class="footer">
</div>

<?php }
function endHtml(){?>
 


	</body>
</html>
<?php } ?>
<?php
function Login(){
 	if(!isset($_SESSION['user']))
	{
		$location = MAINSITE_URL.'index.php';?>
		
        <script type="text/javascript">
			window.location = "<?php echo $location;?>";
        </script>
	<?php }
 }

//////////////////////////    CRUMB FUNCTION 
 
function showBreadCrumb(){
	$trail = explode('/', $_SERVER['PHP_SELF']);
	$url = '/';
	$imgPath = PLMIS_IMG."arrow011.gif";
	$homeLink = SITE_URL."Cpanel.php";
	/*echo "<pre>";
	print_r($trail);
	echo "</pre>";*/?>
    
	<style type="text/css">
		.myLinkClass a {color:#006700; font-size:11px; font-weight:bold;}
		.myLinkClass:hover {text-decoration: underline; color:#09F;}
	</style>
<?php 
	$returnString = "<span class=\"myLinkClass\"><a href='$homeLink' class=\"myLinkClass\">Home</a></span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
	
	for($i=2; $i<count($trail)-1; $i++)
	{
		if($trail[$i] == "plmis_src")continue;
		else{
			if ($trail[$i] == "reports" || $trail[$i] == "graph" || $trail[$i] == "operations" || $trail[$i] == "dataMigration"){
				if ($trail[$i+1] == "view_admin_whreport.php" || $trail[$i+1] == "view_admin_freport.php"){
					$returnString .= "<span style=\"color:#006700; font-size:11px; font-weight:bold;\">LMIS</span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";	
				}else{
					$returnString .= "<span class=\"breadCrumb\" style=\"color:#006700; font-size:11px; font-weight:bold;\">" . directoryNames($trail[$i])."</span>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
				}
			}else{
				$url .= $trail[$i].'/';
				$returnString .= "<a href='$url' style=\"color:#006700; font-size:11px; font-weight:bold;\">" . directoryNames($trail[$i])."</a>&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;";
			}
		}
		
	}
	
	if($trail[$i] == "view_admin_user.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditUser.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditUser.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_user.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_group.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditUserGroup.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditUserGroup.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_group.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_item.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditItem.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditItem.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_item.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_warehouse.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditWarehouse.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditWarehouse.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_warehouse.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_district.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditDistrict.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditDistrict.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_district.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_mos.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditMosScale.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditMosScale.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_mos.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_menu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditMenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditMenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_menu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_submenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditSubmenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditSubmenu.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_submenu.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "view_admin_content.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='AddEditContent.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}else if($trail[$i] == "AddEditContent.php"){
		$returnString .= "<span class=\"myLinkClass\"><a href='view_admin_content.php' class=\"myLinkClass\">".linkNames($trail[$i])."</a></span>";
	}
	
	
	else{	
		$returnString .= "<span class=\"breadCrumb\" style=\"color:#006700; font-size:11px; font-weight:bold;\">".linkNames($trail[$i]);
	}
	echo $returnString;
}
function directoryNames($dir){
	$directory = array();
	$directory['paklmis_final']='Home';
	$directory['reports']='Reports';
	$directory['graph']='Graph Reports';
	$directory['operations']='Manage';
	$directory['dataMigration']='Data Migration';
	
	
	if($directory[$dir]!='')
	{
		$dir = $directory[$dir];
	}
	return $dir;
}
function linkNames($pageLink){
	$imgPath = PLMIS_IMG."arrow011.gif";
	$linkArray = array();
	$linkArray['nationalreport.php']="National Report";
	$linkArray['nationalreportSTK.php']='National Report By Stakeholder';
	$linkArray['provincialreport.php']='Provincial Report';
	$linkArray['diststkreport.php']='District Report';
	$linkArray['fieldstkreport.php']='Field Report';
	$linkArray['itemsreport.php']='Item Availability Report';
	$linkArray['non_report.php']='Non&#47;Reported Warehouses Report';
	$linkArray['templategraphreport.php']='Comparison Graphs';
	$linkArray['templategraphreport2.php']='Simple Graphs';
	$linkArray['view_admin_user.php']='Add User';
	$linkArray['AddEditUser.php']='View Users';
	$linkArray['view_admin_group.php']='Add User Group';
	$linkArray['AddEditUserGroup.php']='View User Group';
	$linkArray['view_admin_item.php']='Add Item';
	$linkArray['AddEditItem.php']='View Items';
	$linkArray['view_admin_warehouse.php']='Add Warehouse';
	$linkArray['AddEditWarehouse.php']='View Warehouse';
	$linkArray['view_admin_district.php']='Add District';
	$linkArray['AddEditDistrict.php']='View Districts';
	$linkArray['view_admin_mos.php']='Add MOS Scale';
	$linkArray['AddEditMosScale.php']='View MOS Scale';
	$linkArray['view_admin_menu.php']='Add Menu';
	$linkArray['AddEditMenu.php']='View Menus';
	$linkArray['view_admin_submenu.php']='Add Sub Menu';
	$linkArray['AddEditSubmenu.php']='View Sub Menus';
	$linkArray['view_admin_whreport.php']='Monthly Warehouse Report';
	$linkArray['view_admin_freport.php']='Monthly Field Report';
	$linkArray['view_admin_content.php']='Add Content';
	$linkArray['AddEditContent.php']='View Contents';
	$linkArray['AddEditF7.php']='LMIS Report';
	$linkArray['mainImport.php']='Import';
	$linkArray['mainExport.php']='Export';
	$linkArray['importdata.php']='Import';
	$linkArray['exportdata.php']='Export';
	$linkArray['view_admin_waitingdata.php']='Waiting Data';
	$linkArray['provincial_warehouse_report.php']='Provincial Yearly Report';
	$linkArray['central_warehouse_report.php']='Central/Provincial Warehouse Report';
	$linkArray['private_sector_report.php']='Private Sector Report';
	$linkArray['nationalreport_ncp.php']="National Report&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;Non-Contraceptives";
	$linkArray['nationalreport_tb.php']="National Report&nbsp;&nbsp;<img src=$imgPath>&nbsp;&nbsp;TB-Drugs";
	$linkArray['new_clr.php']="CLR-6";
	$linkArray['clr6.php']="CLR-6";
	$linkArray['stock.php']="District Stock";
	
	
	if($linkArray[$pageLink]!='')
	{
		$linkTitle = $linkArray[$pageLink];
	}
	return $linkTitle;
}
 ?>