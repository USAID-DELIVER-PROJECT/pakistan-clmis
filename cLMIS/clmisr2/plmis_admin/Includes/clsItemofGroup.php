<?php
class clsItemOfGroup
{
	var $m_npkId;
	var $m_pkItemsofGroupsID;
 	var $m_ItemID;
	var $m_GroupID;
	var $n_group;
	var $no;
 	
	function AddItemOfGroup()
	{
		if ($this->m_ItemID=='') $this->m_ItemID=0;
		if ($this->m_GroupID=='') $this->m_GroupID=0;
		       
		$strSql = "INSERT INTO itemsofgroups(ItemID,GroupID) VALUES(".$this->m_ItemID.",".$this->m_GroupID.")";
    	
		$rsSql = mysql_query($strSql) or die("Error AddItemOfGroup1");
		
		$n_group--;
		
	
		
		
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function AddItemOfGroup1()
	{
		if ($this->m_ItemID=='') $this->m_ItemID=0;
		if ($this->m_GroupID=='') $this->m_GroupID=0;
		
		$n_group=count($this->m_GroupID);
		
		$no=0;
		
		while($n_group!=0){
		       
		$strSql = "INSERT INTO itemsofgroups(ItemID,GroupID) VALUES(".$this->m_ItemID.",".$this->m_GroupID[$no].")";
    	
		$rsSql = mysql_query($strSql) or die("Error AddItemOfGroup1");
		
		$n_group--;
		
		$no++;
		
		}
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function EditItemGroup()
	{
		$strSql = "DELETE FROM  itemsofgroups WHERE ItemID=".$this->m_ItemID;
		
		$rsSql = mysql_query($strSql) or die("Error AddItemOfGroup1");
		
		if ($this->m_ItemID=='') $this->m_ItemID=0;
		if ($this->m_GroupID=='') $this->m_GroupID=0;
		
		$n_group=count($this->m_GroupID);
		
		$no=0;
		
		while($n_group!=0){
		       
		$strSql = "INSERT INTO itemsofgroups(ItemID,GroupID) VALUES(".$this->m_ItemID.",".$this->m_GroupID[$no].")";
	//	print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error AddItemOfGroup1");
		
		$n_group--;
		
		$no++;
		
		}
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
		
	}
	
	function EditItemOfGroup()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE itemsofgroups SET pkItemsofGroupsID=".$this->m_npkId;  
		
		
		
		$ItemID=",ItemID='".$this->m_ItemID."'";
		if ($this->m_ItemID!='') $strSql .=$ItemID;
		
		$GroupID=",GroupID='".$this->m_GroupID."'";
		if ($this->m_GroupID!='') $strSql .=$GroupID;       
		
		$strSql .=" WHERE pkItemsofGroupsID=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemOfGroup");
		
		//inserting values again
		
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error EditItemOfGroup");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteItemGroup()
	{
		$strSql = "DELETE FROM  itemsofgroups WHERE ItemID=".$this->m_ItemID;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemOfGroup");
	}
	function DeleteItemOfGroup()
	{
		$strSql = "DELETE FROM  itemsofgroups WHERE GroupID=".$this->m_GroupID;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemOfGroup");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function DeleteGroup()
	{
		$strSql = "DELETE FROM  itemgroups WHERE PKItemGroupID=".$this->m_GroupID;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemOfGroup1");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAllItemOfGroup()
	{
	
	$strSql = "SELECT
				itemgroups.PKItemGroupID,
				itemgroups.ItemGroupName,
				itemsofgroups.ItemID,
				itminfo_tab.itm_name
				FROM
				itemgroups
				Left Join itemsofgroups ON itemgroups.PKItemGroupID = itemsofgroups.GroupID
				Left Join itminfo_tab ON itemsofgroups.ItemID = itminfo_tab.itm_id";
		$rsSql = mysql_query($strSql) or die("Error GetAllItemOfGroup");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetItemOfGroupById()
	{
			$strSql = "
				SELECT
				itemgroups.PKItemGroupID,
				itemgroups.ItemGroupName,
				itemsofgroups.ItemID,
				itminfo_tab.itm_name
				FROM
				itemgroups
				Left Join itemsofgroups ON itemgroups.PKItemGroupID = itemsofgroups.GroupID
				Left Join itminfo_tab ON itemsofgroups.ItemID = itminfo_tab.itm_id
				Where itemgroups.PKItemGroupID=".$this->m_npkId;
	
		$rsSql = mysql_query($strSql) or die("Error GetItemOfGroupById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

	function GetItemsOfGroupById()
	{
			$strSql = "
				SELECT
				itemsofgroups.ItemID,itminfo_tab.itm_name
				FROM
				itemsofgroups 
				left Join itminfo_tab ON itemsofgroups.ItemID = itminfo_tab.itm_id
				Where itemsofgroups.GroupID=".$this->m_npkId;

		$rsSql = mysql_query($strSql) or die("Error GetItemsOfGroupById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetItemsofGroupinCSV()
	{
	$csv="";
		$objMIs = $this->GetItemsOfGroupById();
		if($objMIs!=FALSE && mysql_num_rows($objMIs)>0)
		{
			while($Rowranks = mysql_fetch_object($objMIs))
			{
				$csv.=$Rowranks->itm_name.",";
				//print $csv;
			}		
		}
		if(strlen($csv)>0)
		{
			$csv=substr($csv,0,strlen($csv)-1); 
		}
		return $csv;
	}


}
?>
