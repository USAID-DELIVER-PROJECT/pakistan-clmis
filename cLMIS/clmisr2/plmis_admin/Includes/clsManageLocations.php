<?php
class clsManagelocations
{
	var $PkLocID;

	function GetAllLocationsType()
	{
		
		
		$strSql = "SELECT LoctypeID,LoctypeName,TypeLvl FROM tbl_locationtype WHERE TypeLvl=".$this->TypeLvl;
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocationstype");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
	
?>