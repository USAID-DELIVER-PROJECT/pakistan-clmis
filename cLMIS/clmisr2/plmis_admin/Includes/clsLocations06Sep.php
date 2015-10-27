<?php
class clslocations
{
	var $PkLocID;
	var $LocName;
	var $LocLvl;
	var $ParentID;
	var $LocType;
		
	function Addlocations()
	{
		if ($this->LocName=='') $this->LocName='NULL';
		if ($this->ParentID=='') $this->ParentID=0;
		if ($this->LocType=='') $this->LocType=0;
		if ($this->LocLvl=='') $this->LocLvl=1;

		$strSql = "INSERT INTO  tbl_locations (LocName,LocLvl,ParentID,LocType) VALUES('".$this->LocName."',".$this->LocLvl.",".$this->ParentID.",".$this->LocType.")";
	//	print $strSql; exit;
		$rsSql = mysql_query($strSql) or die("Error Addlocations");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function Editlocations()
	{
		
$strSql = "UPDATE tbl_locations SET PkLocID=".$this->PkLocID; 
		
		$LocName=",LocName='".$this->LocName."'";
		if ($this->LocName!='') $strSql .=$LocName;
		
		$LocLvl=",LocLvl=".$this->LocLvl;
		if ($this->LocLvl!='') $strSql .=$LocLvl;
		
		$ParentID=",ParentID=".$this->ParentID;
		if ($this->ParentID!='') $strSql .=$ParentID;
		
		$LocType=",LocType=".$this->LocType;
		$strSql .=$LocType;
				
		$strSql .=" WHERE PkLocID=".$this->PkLocID;

		
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error Editlocations");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}

	function DeleteLocation()
	{
		$strSql = "DELETE FROM  tbl_locations WHERE PkLocID=".$this->PkLocID;
		$rsSql = mysql_query($strSql) or die("Error Delete location");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}

	function GetAllLocations()
	{
		
		
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE LocLvl=".$this->LocLvl;
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocations");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetLocationsById($provIds)
	{
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE LocLvl=".$this->LocLvl." AND PkLocID IN (".$provIds.")";
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocations");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetAllLocations1()
	{
		
		
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations";
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocations");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

	function GetAllLocationsfromParent()
	{
		if($this->ParentID!='10')
				$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE LocLvl> 2 and ParentID=".$this->ParentID." Order by LocName";
		else
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE LocLvl> 2 Order by LocName";

		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocations");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
		
	function GetAllLocationsWithType()
	{
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE LocLvl=".$this->LocLvl." and LocType=".$this->LocType;
		$rsSql = mysql_query($strSql) or die("Error GetAllLocations");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

	
	function GetLocationById()
	{
		$strSql = "SELECT PkLocID,LocName,LocLvl,ParentID,LocType FROM tbl_locations WHERE PkLocID=".$this->PkLocID;
		$rsSql = mysql_query($strSql) or die("Error GetLocationById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function get_location_name()
	{
		$LocName='';
		$strSql = "SELECT LocName FROM tbl_locations WHERE PkLocID=".$this->PkLocID;
		$rsSql = mysql_query($strSql) or die("Error get_location_name");
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			$RowLoc = mysql_fetch_object($rsSql);
			$LocName=$RowLoc->LocName;
		}
		return $LocName;
	}
}
?>