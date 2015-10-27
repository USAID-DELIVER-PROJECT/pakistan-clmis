<?
class clsConfiguration
{

	function Checkfile($strFileName)
	{
		if(file_exists($strFileName))
		return true;
		else 
		return false;
	}

	function GetDB($strHost, $strDatabase, $strUser, $strPass)
	{
		$strLink=mysql_connect($strHost, $strUser, $strPass);
		if(!$strLink)
			return "Connection could not be made";
		$strDB=mysql_select_db($strDatabase,$strLink);
		if(!$strDB)
			return "Database not found.";
		return true;
	}

	function GetDBTables()
	{
		$strSql="show tables";
		$rsSql=mysql_query($strSql);
		return $rsSql;
	}
/*
	function DeleteDBTables($strDelTables)
	{
		if($strDelTables)
		{
			$rs=mysql_query("drop tables ".$strDelTables);
			return $rs;
		}
		else 
			return false;
	}

	function DeleteTable($strTable)
	{
		$strDelQry="Drop table ".$strTable;	
		$nCheck=mysql_query($strDelQry);
		return $nCheck;
	}

	function CreateDatabase($strDbName,$strHost,$strUser,$strPass)
	{	
	  $strLink=mysql_connect($strHost, $strUser, $strPass);
	//echo "here===>",$strDbName;
		$strDbQry="CREATE DATABASE `$strDbName`";	
		//echo "<br>",$strDbQry;exit;
		$nCheck=mysql_query($strDbQry);
		return $nCheck;
	}
*/
}
?>