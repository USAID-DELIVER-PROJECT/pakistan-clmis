<?php
class clsItemType
{
	var $m_npkId;
	var $m_PKItemTypeID;
 	var $m_ItemTypeName;
 	
	function AddItemType()
	{
		if ($this->m_ItemTypeName=='') $this->m_ItemTypeName='NULL';
		

       
		$strSql = "INSERT INTO  tbl_product_type(ItemTypeName) VALUES('".$this->m_ItemTypeName."')";

		$rsSql = mysql_query($strSql) or die("Error AddItemType");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function EditItemType()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE tbl_product_type SET PKItemTypeID=".$this->m_npkId;  
		
		
		
		$ItemTypeName=",ItemTypeName='".$this->m_ItemTypeName."'";
		if ($this->m_ItemTypeName!='') $strSql .=$ItemTypeName;       
		
		$strSql .=" WHERE PKItemTypeID=".$this->m_npkId;
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error EditItemType");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteItemType()
	{
		$strSql = "DELETE FROM tbl_product_type WHERE PKItemTypeID=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemType");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllItemType()
	{
	
		$strSql = "SELECT
					tbl_product_type.PKItemTypeID,
					tbl_product_type.ItemTypeName
					FROM
					tbl_product_type";
		
	
		$rsSql = mysql_query($strSql) or die("Error GetAllManageItem");
		
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetItemTypeById()
	{
			$strSql = "
				SELECT
					tbl_product_type.PKItemTypeID,
					tbl_product_type.ItemTypeName
					FROM
					tbl_product_type
					WHERE tbl_product_type.PKItemTypeID=".$this->m_npkId;
					
			/*print $strSql;
			exit;*/
	
		$rsSql = mysql_query($strSql) or die("Error GetItemTypeById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>
