<?php
class clswh_type
{
	var $m_wh_type_id;
	var $m_wh_desc;
	function Addwhtype()
	{
		$strSql = "INSERT INTO  tbl_wh_type(wh_desc) VALUES('".$this->$m_wh_desc."')";
		
		$rsSql = mysql_query($strSql) or die("Error whtype");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	function Editwhtype()
	{
		$strSql = "UPDATE tbl_wh_type SET wh_desc='".$this->$m_wh_desc."' WHERE wh_type_id=".$this->m_wh_type_id;
		//echo $strSql; exit();
		$rsSql = mysql_query($strSql) or die("Error whtype");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function Deletewhtype()
	{
		$strSql = "DELETE FROM  tbl_wh_type WHERE wh_type_id=".$this->m_wh_type_id;
		$rsSql = mysql_query($strSql) or die("Error Deletewhtype");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllwhtype()
	{
		$strSql = "SELECT wh_type_id,wh_desc FROM  tbl_wh_type";
		$rsSql = mysql_query($strSql) or die("Error Getwhtype");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetwhtypeById()
	{
		$strSql = "SELECT wh_type_id,wh_desc FROM  tbl_wh_type WHERE wh_type_id=".$this->m_wh_type_id;
		$rsSql = mysql_query($strSql) or die("Error Getwhtype");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>