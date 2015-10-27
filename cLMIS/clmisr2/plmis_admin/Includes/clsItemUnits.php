<?php
class clsItemUnits
{
	public $m_npkId;
 	public $m_unit_type;
	
	function AddItemUnit()
	{
		if ($this->m_unit_type =='') $this->m_unit_type='NULL';
       
		$strSql = "INSERT INTO tbl_itemunits (UnitType) VALUES ('".$this->m_unit_type."')";
		$rsSql = mysql_query($strSql) or die("Error AddItemUnit");
		$number=mysql_insert_id();
		
		if($number!=0)
		return $number;
	}
	
	function EditItemUnit()
	{	
		$strSql = "UPDATE tbl_itemunits SET UnitType=".$this->m_unit_type;
		$strSql .=" WHERE pkUnitID=".$this->m_npkId;
		
		$rsSql = mysql_query($strSql) or die("Error EditItemUnit");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	
	function DeleteItemUnit()
	{
		$strSql = "DELETE FROM  tbl_itemunits WHERE pkUnitID=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error DeleteItemUnit");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAllItemUnits()
	{
		$strSql = 	"SELECT * FROM	tbl_itemunits";
		$rsSql = mysql_query($strSql) or die("Error: GetAllItemUnits");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetItemUnitById()
	{
		$strSql="SELECT
			tbl_itemunits.UnitType,				
			FROM
			tbl_itemunits
			WHERE tbl_itemunits.pkUnitID=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error: GetItemUnitById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetUnitByItemId($item_id)
	{
		 $strSql="SELECT
			tbl_itemunits.pkUnitID,
			tbl_itemunits.UnitType
			FROM
			tbl_itemunits
			INNER JOIN itminfo_tab ON itminfo_tab.itm_type = tbl_itemunits.UnitType
			WHERE
			itminfo_tab.itm_id = ".$item_id."";
		$rsSql = mysql_query($strSql) or die("Error: GetUnitByItemId");
		if(mysql_num_rows($rsSql)>0){
			$row = mysql_fetch_object($rsSql);
			return array(
				'id' => $row->pkUnitID,
				'type' => $row->UnitType
			);
		} else{
			return FALSE;
		}			
	}
}
?>
