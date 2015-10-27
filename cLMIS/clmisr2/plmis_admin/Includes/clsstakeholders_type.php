<?php
class clsStk_Type
{
	var $m_stk_type_id;
	var $m_stk_type_descr;
	function AddStk_type()
	{
		$strSql = "INSERT INTO  stakeholder_type (stk_type_descr) VALUES('".$this->$m_stk_type_descr."')";
		
		$rsSql = mysql_query($strSql) or die("Error Addstk_type");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	function EditStk_type()
	{
		$strSql = "UPDATE stakeholder_type SET stk_type_descr='".$this->$m_stk_type_descr."' WHERE stk_type_id=".$this->m_stk_type_id ;
		//echo $strSql; exit();
		$rsSql = mysql_query($strSql) or die("Error Editstk_type");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteStk_type()
	{
		$strSql = "DELETE FROM  stakeholder_type WHERE stk_type_id=".$this->m_stk_type_id;
		$rsSql = mysql_query($strSql) or die("Error Deletestk_type");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllstk_types()
	{
		$strSql = "SELECT stk_type_id,stk_type_descr FROM  stakeholder_type";
		$rsSql = mysql_query($strSql) or die("Error GetAllstk_types");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetStk_type_descrById()
	{
		$strSql = "SELECT stk_type_id,stk_type_descr FROM  stakeholder_type WHERE stk_type_id=".$this->m_stk_type_id;
		$rsSql = mysql_query($strSql) or die("Error Getstk_typeById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
}
?>