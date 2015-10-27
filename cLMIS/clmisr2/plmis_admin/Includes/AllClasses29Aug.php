<?php 

session_start();

if(!isset($_SESSION['userid']) || $_SESSION['userid']=="")
{
	header("location:index.php?strMsg=Please+login");	
	exit;
}
require_once("Includes/Configuration.inc.php");
require_once("Includes/clsConfiguration.php");
require_once("Includes/clsDatabaseManager.php");
require_once("Includes/db.php");
require_once("Includes/clsStakeholders.php");
require_once("Includes/clsManageUser.php");
require_once("Includes/clsstakeholders_type.php");
require_once("Includes/clsswharehouse.php");
require_once("Includes/clsLocations.php");
require_once("Includes/clsManageitem.php");
require_once("Includes/clsDistriclevel.php");
require_once("Includes/clsItemGroup.php");
require_once("Includes/clsItemofGroup.php");
require_once("Includes/clsIstakeholder_item.php");
require_once("Includes/clswarehouse_user.php");
require_once("Includes/clsReports.php");
require_once("Includes/clsManageContent.php");
require_once("Includes/clsManageLocations.php");
require_once("Includes/clsProductType.php");
require_once("Includes/clsProductCategory.php");
require_once("Includes/clsManageStatus.php");


$objstk = new clsstakeholder();
$objstkType = new clsStk_Type();
$objuser = new clsUser();
$objwarehouse = new clswarehouse();
$objloc = new clslocations();
$objlvl=new clstbl_dist_levels();
$objManageItem = new clsManageItem();
$ItemGroup = new clsItemGroup();
$ItemOfGroup = new clsItemOfGroup();
$objstakeholderitem = new clsstakeholderitem();
$objwharehouse_user = new clsIwh_user();
$objReports=new ClsReports();
$objContent=new ClsContent();
$objManageLocations=new clsManagelocations();
$objitemtype=new clsItemType();
$objitemcategory=new clsItemCategory();
$objitemstatus=new clsItemStatus();

$lc='#E9D6DA';
$dc='#E6EEEA';

define('SITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmis/');
define('PLMIS_IMG',SITE_URL.'plmis_img/');
define ('PLMIS_WS',SITE_URL.'ws/');
define ('ADMIN_IMAGES',SITE_URL.'plmis_admin/images/');
  
?>