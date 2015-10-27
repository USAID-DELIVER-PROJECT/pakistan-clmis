<?php 
error_reporting(0);
session_start();

if(!isset($_SESSION['userid']) || $_SESSION['userid']=="")
{
	//header("location:../index.php?strMsg=Please+login");	
	echo "<script>window.location='../index.php?strMsg=Please+login'</script>";
	exit;
}
require_once("Includes/database.php");
require_once("Includes/functions.php");
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
require_once("Includes/clsUserProvinces.php");
require_once("Includes/clsUserStackholders.php");
require_once("Includes/clsWarehouseData.php");

require_once("Includes/clsStockMaster.php");
require_once("Includes/clsStockDetail.php");
require_once("Includes/clsStockBatch.php");
//require_once("Includes/clsswarehouse.php");
require_once("Includes/clsTransTypes.php");
require_once("Includes/clsItemUnits.php");
require_once("Includes/clsFiscalYear.php");
require_once("Includes/clsvvmTypes.php");
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
$objReports = new ClsReports();
$objContent = new ClsContent();
$objManageLocations = new clsManagelocations();
$objitemtype = new clsItemType();
$objitemcategory = new clsItemCategory();
$objitemstatus = new clsItemStatus();
$objuserprov = new clsUserProvinces();
$objuserstk = new clsUserStackholders();
$objWhData = new clsWarehouseData();

$database = new MySQLDatabase();
$objStockMaster = new clsStockMaster();
$objStockDetail = new clsStockDetail();
$objStockBatch = new clsStockBatch();
$objTransType = new clsTransTypes();
$objItemUnits = new clsItemUnits();
$objvvmType = new clsvvmTypes();
$objFiscalYear = new clsFiscalYear();

//$objwarehouse = new clsswarehouse();

$lc='#E9D6DA';
$dc='#E6EEEA';

define('SITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmisr2/');
define('PLMIS_IMG',SITE_URL.'plmis_img/');
define ('PLMIS_WS',SITE_URL.'ws/');
define ('ADMIN_IMAGES',SITE_URL.'plmis_admin/images/');
  
?>