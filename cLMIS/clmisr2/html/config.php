<?php
//error_reporting(0);
session_start();
date_default_timezone_set('Asia/Karachi');

define('MAINSITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmisr2/');

define('SITE_URL','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/clmisr2/');

//define('SITE_PATH','/home/lmispcgo/public_html/clmis/');
define('SITE_PATH',$_SERVER['DOCUMENT_ROOT'].'/clmisr2/');
define('PLMIS_INC',SITE_PATH.'plmis_inc/');
define('PLMIS_SRC',SITE_URL.'plmis_src/');
define('PLMIS_CSS',SITE_URL.'plmis_css/');
define('ADMIN_CSS',SITE_URL.'css/');
define('PLMIS_JS',SITE_URL.'plmis_js/');
define('PLMIS_IMG',SITE_URL.'plmis_img/');
define('ADMIN_IMG',SITE_URL.'images/');
define('REPORT_XML_PATH', SITE_PATH."plmis_src/reports/xml/");
define('GRID_XML_PATH', SITE_PATH."plmis_src/operations/xml/");
define('ASSETS',SITE_URL."assets/");
define('PLMIS_ADMIN',SITE_URL.'plmis_admin/');
define('ADMIN_IMGS',PLMIS_ADMIN.'images/');



include_once(PLMIS_INC.'common/Global.php');  //Include Global Variables File
include_once(PLMIS_INC.'common/DateTime.php');    //Include Date Function File
include_once(PLMIS_INC."common/CnnDb.php");   //Include Database Connection File
include_once(PLMIS_INC."common/FunctionLib.php"); //Include Global Function File
include_once(PLMIS_INC."form/plmis_form_globals.php");