<?php
class clsLogin
{
	var $m_strPass="";
	var $m_login="";
	
	function Update()
	{
		$strSql = "UPDATE sysuser_tab SET sysusr_pwd='".$this->m_strPass."' WHERE UserID='".$this->m_login."'";
		$rsSql = mysql_query($strSql) or die("Error ".$strSql);
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	
	function Login()
	{
		$this->m_strPass=base64_encode($this->m_strPass); 
		$strSql = "select UserID,sysusr_type,sysusr_name, sysusr_dept,sysusr_email, whrec_id, sysgroup_id from sysuser_tab 
		where usrlogin_id='".$this->m_login."' and sysusr_pwd='".$this->m_strPass."' and sysusr_status='Active'";
		
		$rsSql = mysql_query($strSql) or die("Error ".$strSql);
		$r=mysql_fetch_row($rsSql);
		//echo print_r($r);//mysql_num_rows($rsSql);
		//exit;
		if(mysql_num_rows($rsSql)>0)
			return $r;
		else
			return "";
	}
	function getOldPass()
	{
		$strSql = "select sysusr_pwd from sysuser_tab 
		where UserID='".$this->m_login."' and sysusr_status='Active'";
		//echo $strSql;
		//exit;
		$rsSql = mysql_query($strSql) or die("Error ".$strSql);
		$r=mysql_fetch_row($rsSql);
		//echo print_r($r);//mysql_num_rows($rsSql);
		//exit;
		if(mysql_num_rows($rsSql)>0)
			return $r[0];
		else
			return "";
	}
}
?>