<?php 
require_once("Includes/clsConfiguration.php");
$objConfiguration=new clsConfiguration();
$nStat=$objConfiguration->GetDB($strHost, $strDatabase, $strUserName, $strPassword);
?>