<?php
class clsstakeholderitem
{
	var $m_npkId;
	var $m_stk_id;
 	var $m_stkid;
	var $m_stk_item;
	var $m_type;
 	var $m_itm_id;
	var $m_itm_name;
	var $m_stkname;
	var $stk_n;
	var $no;
 	
function Addstakeholderitem()
	{
		//if ($this->m_stk_id=='') $this->m_stk_id=0;
		if ($this->m_stkid=='') $this->m_stkid=0;
		if ($this->m_stk_item=='') $this->m_stk_item=0;
		
		$stk_n=count($this->m_stkid);
		$no=0;
		
		//if ($this->m_type=='') $this->m_type=0;
		       
		while($stk_n!=0){
		
		$strSql = "INSERT INTO stakeholder_item(stkid,stk_item) VALUES(".$this->m_stkid[$no].",".$this->m_stk_item.")";
		
		
                $rsSql = mysql_query($strSql) or die("Error Addstakeholderitem");
		
		
		$no++;
		
		$stk_n--;
		
		}
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function Addstakeholderitem1()
	{
		
		if ($this->m_stkid=='') $this->m_stkid=0;
		if ($this->m_stk_item=='') $this->m_stk_item=0;
		
		$strSql = "INSERT INTO stakeholder_item(stkid,stk_item) VALUES(".$this->m_stkid.",".$this->m_stk_item.")";
		$rsSql = mysql_query($strSql) or die("Error Addstakeholderitem");
		
		
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function Editstkholderitem()
	{
		$strSql = "DELETE FROM  stakeholder_item WHERE stk_item=".$this->m_stk_item;
		
		$rsSql = mysql_query($strSql) or die("Error Deletestakeholderitem");
		
		//insert values again
		
		//if ($this->m_stk_id=='') $this->m_stk_id=0;
		if ($this->m_stkid=='') $this->m_stkid=0;
		if ($this->m_stk_item=='') $this->m_stk_item=0;
		
		$stk_n=count($this->m_stkid);
		$no=0;
		
		//if ($this->m_type=='') $this->m_type=0;
		       
		while($stk_n!=0){
		
		$strSql = "INSERT INTO stakeholder_item(stkid,stk_item) VALUES(".$this->m_stkid[$no].",".$this->m_stk_item.")";
		$rsSql = mysql_query($strSql) or die("Error Addstakeholderitem");
		
		$no++;
		
		$stk_n--;
		
		}
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
		
		
		
	}
	
	function Editstakeholderitem()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE stakeholder_item SET stk_id=".$this->m_npkId;  
		
		
		
		$stkid=",stkid='".$this->m_stkid."'";
		if ($this->m_stkid!='') $strSql .=$stkid;
		
		$stk_item=",stk_item='".$this->m_stk_item."'";
		if ($this->m_stk_item!='') $strSql .=$stk_item;
		
		$type=",type='".$this->m_type."'";
		if ($this->m_type!='') $strSql .=$type;        
		
		$strSql .=" WHERE stk_id=".$this->m_npkId;
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error stakeholderitem");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function Deletestakeholderitem()
	{
		$strSql = "DELETE FROM  stakeholder_item WHERE stkid=".$this->m_stk_id;
		
		$rsSql = mysql_query($strSql) or die("Error Deletestakeholderitem");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function Deletestkholderitem()
	{
		$strSql = "DELETE FROM  stakeholder_item WHERE stk_item=".$this->m_stk_item;
		$rsSql = mysql_query($strSql) or die("Error Deletestakeholderitem");
	}
	function GetAllstakeholderitem()
	{
	
	$strSql = "SELECT
				stakeholder_item.stk_id,
				stakeholder_item.stkid,
				stakeholder.stkname,
				stakeholder_item.stk_item,
				itminfo_tab.itm_name,
				stakeholder_item.type
				FROM
				stakeholder_item
				Left Join stakeholder ON stakeholder.stkid = stakeholder_item.stkid
				Left Join itminfo_tab ON itminfo_tab.itm_id = stakeholder_item.stk_item";
		$rsSql = mysql_query($strSql) or die("Error GetAllstakeholderitem");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetAllstakeholder()
	{
	
	$strSql = "SELECT DISTINCT stakeholder_item.stkid,stakeholder.stkname
				FROM
				stakeholder_item
				inner Join stakeholder ON stakeholder.stkid = stakeholder_item.stkid
				WHERE stakeholder.ParentID is null ORDER BY stakeholder.stkname DESC";
		$rsSql = mysql_query($strSql) or die("Error GetAllstakeholderitem");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetstakeholderitemById()
	{
			$strSql = "
				SELECT
					stakeholder_item.stk_id,
					stakeholder_item.stkid,
					stakeholder.stkname,
					stakeholder_item.stk_item,
					itminfo_tab.itm_name,
					stakeholder_item.type
					FROM
					stakeholder_item
					Left Join stakeholder ON stakeholder.stkid = stakeholder_item.stkid
					Left Join itminfo_tab ON itminfo_tab.itm_id = stakeholder_item.stk_item
					WHERE stakeholder_item.stkid=".$this->m_npkId;
					
			/*print $strSql;
			exit;*/
	
		$rsSql = mysql_query($strSql) or die("Error GetstakeholderitemById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

	function GetstakeholderItemsById()
	{
			$strSql = "
				SELECT
					stakeholder_item.stk_id,
					itminfo_tab.itm_name,
					itminfo_tab.itm_id
				FROM
					stakeholder_item
					Left Join itminfo_tab ON itminfo_tab.itm_id = stakeholder_item.stk_item
				Where stakeholder_item.stkid=".$this->m_npkId;
//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetstakeholderItemsById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetIstakeholderinCSV()
	{
	$csv="";
		$objMIs = $this->GetstakeholderItemsById();
		if($objMIs!=FALSE && mysql_num_rows($objMIs)>0)
		{
			while($Rowranks = mysql_fetch_object($objMIs))
			{
			if(strlen($Rowranks->itm_name)>0)
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
