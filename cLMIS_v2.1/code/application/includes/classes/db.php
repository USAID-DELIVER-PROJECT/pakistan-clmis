<?php 
/**
 * db
 * @package includes/class
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */

include_once("clsConfiguration.php");
$objConfiguration=new clsConfiguration();
$nStat=$objConfiguration->GetDB($db_host, $db_name, $db_user, $db_password);
?>