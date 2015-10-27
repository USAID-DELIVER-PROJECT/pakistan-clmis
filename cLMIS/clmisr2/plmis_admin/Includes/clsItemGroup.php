<?php
class clsItemGroup
{
	var $m_npkId;
	var $m_PKItemGroupID;
 	var $m_ItemGroupName;
 	
	function AddItemGroup()
	{
		if ($this->m_ItemGroupName=='') $this->m_ItemGroupName='NULL';
		

       
		$strSql = "INSERT INTO  itemgroups(ItemGroupName) VALUES('".$this->m_ItemGroupName."')";
//		print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error AddItemGroup");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function EditItemGroup()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE itemgroups SET PKItemGroupID=".$this->m_npkId;  
		
		
		
		$ItemGroupName=",ItemGroupName='".$this->m_ItemGroupName."'";
		if ($this->m_ItemGroupName!='') $strSql .=$ItemGroupName;       
		
		$strSql .=" WHERE PKItemGroupID=".$this->m_npkId;
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error EditItemGroup");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteItemGroup()
	{
		$strSql = "DELETE FROM  itemgroups WHERE PKItemGroupID=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemGroup");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllItemGroup()
	{
	
	$strSql = "
				SELECT
					itemgroups.PKItemGroupID,
					itemgroups.ItemGroupName
					FROM
					itemgroups";
		$rsSql = mysql_query($strSql) or die("Error GetAllManageItem");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetItemGroupById()
	{
			$strSql = "
				SELECT
					itemgroups.PKItemGroupID,
					itemgroups.ItemGroupName
					FROM
					itemgroups
					WHERE itemgroups.PKItemGroupID=".$this->m_npkId;
	
		$rsSql = mysql_query($strSql) or die("Error GetItemGroupById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>
