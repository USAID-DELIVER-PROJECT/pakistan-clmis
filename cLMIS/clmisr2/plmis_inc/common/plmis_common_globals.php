<?php
/* --------------------------------------------------------------------------------------------------------------
PakLMIS (PLMIS) - Common Constants

Author: Muhammad Ahmed

Modification History (LIFO)
---------------------------

//-------------------------------------------------------------------------------------------------------------- */

if (!defined('PLMIS_COMMON_CONSTANTS'))
{
define('PLMIS_COMMON_CONSTANTS',true);
//---------------------------------------------------------------------------------------------------------------

// IMPORTANT:
// By the time we get here, we've established TTS_INC_PREFIX, but we have to bootstrap the other constants

// This is a kluge, but sugarcrmtest web site is mapped to resolve names in a way that makes this reasonable
$localserver = ( ! ( false === strpos($_SERVER['HTTP_HOST'],"localhost")));

if ( !$localserver )
{
	define('PLMIS_ROOT','http://'.$_SERVER['HTTP_HOST'].'/');
	define('PLMIS_CONTROL',true);
	define('PLMIS_INC_BASE',$_SERVER['DOCUMENT_ROOT'].'/'); //
}
else
{
	define('PLMIS_SUGAR_ROOT','http://localhost/paklmis/');
	define('PLMIS_SUGAR_CONTROL',false);
	define('PLMIS_INC_BASE','../../'); //
}

// Jul 12, 2007 - xxx
define("TTS_ID_NOT_FOUND", -1000000);  // couldn't find based on look-up options

// May 29, 2007 - xxx - Create 1 place to change for export path - add 1 line
define('PLMIS_EXPORT_INC_PREFIX',TTS_INC_BASE.'plmis_output/export/');

// May 29, 2007 - xxx - Create 1 place to change for export path - add 1 line
define('PLMIS_EXPORT_HTTP_PREFIX',PLMIS_ROOT.'plmis_output/export/');

// May 29, 2007 - xxx - Create 1 place to change for export path - add 1 line
define('PLMIS_IMAGE_PATH',PLMIS_ROOT.'plmis_img/');
define('PLMIS_JS_PATH',PLMIS_ROOT.'plmis_js/');
define('PLMIS_CSS_PATH',PLMIS_ROOT.'plmis_css/');
define('PLMIS_INC_PATH',PLMIS_ROOT.'plmis_inc/');


//------------------------------------------------------------------------------
// For connecting to database (local only)
//
//------------------------------------------------------------------------------
if ( TTS_SUGAR_CONTROL )
{
	define('PLMIS_DB','nvtst1');
	define('PLMIS_DB_USER','sugarcrm');
	define('PLMIS_DB_PWD','sugarcrmtst');
	define('PLMIS_DB_HOST','maverick.jsi.com');
}
else
{
	define('PLMIS_DB','nvtst1');
	define('PLMIS_DB_USER','sugarcrm');
	define('PLMIS_DB_PWD','sugarcrmtst');
	define('PLMIS_DB_HOST','maverick.jsi.com');
}
// For error reporting

// General Error messages
define("PLMIS_ERR_UNSUPPORTED_PHP_VERSION","The version of PHP you are using is not supported by this application");
define("PLMIS_ERR_LOGIN_REQUIRED","A login is required to use this facility");

define("PLMIS_ERR_ASSERT_FAILED","BUG - a function or operation returned a value that violated a processing state assertion");

// MYSQL Error messages
define("PLMIS_ERR_SQL_PARSE","Error while attempting to parse sql");
define("PLMIS_ERR_SQL_EXECUTE","Error while attempting to execute sql");

} // if (defined('PLMIS_COMMON_CONSTANTS'))
?>