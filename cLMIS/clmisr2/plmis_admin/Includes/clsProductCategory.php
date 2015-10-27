<?php
class clsItemCategory
{
	var $m_npkId;
	var $m_PKItemCategoryID;
 	var $m_ItemCategoryName;
 	
	function AddItemCategory()
	{
		if ($this->m_ItemCategoryName=='') $this->m_ItemCategoryName='NULL';
       
		$strSql = "INSERT INTO  tbl_product_category(ItemCategoryName) VALUES('".$this->m_ItemCategoryName."')";

		$rsSql = mysql_query($strSql) or die("Error AddItemCategory");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function EditItemCategory()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE tbl_product_category SET PKItemCategoryID=".$this->m_npkId;  
		
		$ItemCategoryName=",ItemCategoryName='".$this->m_ItemCategoryName."'";
		if ($this->m_ItemCategoryName!='') $strSql .=$ItemCategoryName;       
		
		$strSql .=" WHERE PKItemCategoryID=".$this->m_npkId;
		
		$rsSql = mysql_query($strSql) or die("Error EditItemCategory");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteItemCategory()
	{
		$strSql = "DELETE FROM tbl_product_category WHERE PKItemCategoryID=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemCategory");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllItemCategory()
	{
	
		$strSql = "SELECT
					tbl_product_category.PKItemCategoryID,
					tbl_product_category.ItemCategoryName
					FROM
					tbl_product_category";
		
	
		$rsSql = mysql_query($strSql) or die("Error GetAllManageItem");
		
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetItemCategoryById()
	{
			$strSql = "
				SELECT
					tbl_product_category.PKItemCategoryID,
					tbl_product_category.ItemCategoryName
					FROM
					tbl_product_category
					WHERE tbl_product_category.PKItemCategoryID=".$this->m_npkId;
	
		$rsSql = mysql_query($strSql) or die("Error GetItemCategoryById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>
