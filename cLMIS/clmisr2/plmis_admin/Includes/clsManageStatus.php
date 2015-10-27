<?php
class clsItemStatus
{
	var $m_npkId;
	var $m_PKItemStatusID;
 	var $m_ItemStatusName;
 	
	function AddItemStatus()
	{
		if ($this->m_ItemStatusName=='') $this->m_ItemStatusName='NULL';
       
		$strSql = "INSERT INTO  tbl_product_status(ItemStatusName) VALUES('".$this->m_ItemStatusName."')";

		$rsSql = mysql_query($strSql) or die("Error AddItemStatus");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function EditItemStatus()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE tbl_product_status SET PKItemStatusID=".$this->m_npkId;  
		
		$ItemStatusName=",ItemStatusName='".$this->m_ItemStatusName."'";
		if ($this->m_ItemStatusName!='') $strSql .=$ItemStatusName;       
		
		$strSql .=" WHERE PKItemStatusID=".$this->m_npkId;
		
		$rsSql = mysql_query($strSql) or die("Error EditItemStatus");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteItemStatus()
	{
		$strSql = "DELETE FROM tbl_product_status WHERE PKItemStatusID=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemStatus");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllItemStatus()
	{
	
		$strSql = "SELECT
					tbl_product_status.PKItemStatusID,
					tbl_product_status.ItemStatusName
					FROM
					tbl_product_status";
		
	
		$rsSql = mysql_query($strSql) or die("Error GetAllManageItem");
		
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetItemStatusById()
	{
			$strSql = "
				SELECT
					tbl_product_status.PKItemStatusID,
					tbl_product_status.ItemStatusName
					FROM
					tbl_product_status
					WHERE tbl_product_status.PKItemStatusID=".$this->m_npkId;
	
		$rsSql = mysql_query($strSql) or die("Error GetItemStatusById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>
