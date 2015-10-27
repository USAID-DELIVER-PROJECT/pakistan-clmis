<?php
class clsContent
{
	var $m_npkId;
	var $m_page_title;
	var $m_page_heading;
 	var $m_page_description;
	var $m_stakeholders;
 	var $m_provinces;	
	var $m_logo;
	var $m_homepage;
	
	function Addlogocontent(){
try{
		if ($this->m_page_title=='') $this->m_page_title='NULL';
		if ($this->m_stakeholders=='') $this->m_stakeholders=0;
		if ($this->m_page_heading=='') $this->m_page_heading='NULL';
		if ($this->m_page_description=='') $this->m_page_description='NULL';
		if ($this->m_provinces=='') $this->m_provinces='NULL';	
		if ($this->m_logo=='') $this->m_logo='';	
		if ($this->m_homepage=='') $this->m_homepage='NULL';	
		//$vary=str_replace("'","&#039",$this->m_page_description);
		$vary=htmlEntities($this->m_page_description, ENT_QUOTES);
		//$page_description=htmlentities($vary);
		//echo $vary;
  //exit;
  //$this->m_page_description = $this->m_page_description;
  
  
  //print $this->m_page_description;
  //exit;
  
		$strSql = "INSERT INTO tbl_cms(title,heading,Stkid,province_id,logo,homepage_chk,description) VALUES('".$this->m_page_title."','".$this->m_page_heading."','".$this->m_stakeholders."','".$this->m_provinces."','".$this->m_logo."','".$this->m_homepage."','".$vary."')";
		
		

		$rsSql = mysql_query($strSql) or die("Error Add Content1");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
}
			catch(Exception $e)
  {
  echo 'Message: ' .$e->getMessage();
  exit;
  }
	}
	
	
	function Editlogocontent()
	{
		//adding additional options
		if ($this->m_page_description !=''){
		$vary=htmlEntities($this->m_page_description, ENT_QUOTES);
				}
		
		 $strSql = "UPDATE tbl_cms SET id=".$this->m_npkId;
		
		$page_title=",title='".$this->m_page_title."'";
		if ($this->m_page_title!='') $strSql .=$page_title;
		
		$page_heading=",heading='".$this->m_page_heading."'";
		if ($this->m_page_heading !='') $strSql .=$page_heading;
		
		$page_description=",description='".$vary."'";
		if ($this->m_page_description !='') $strSql .=$page_description;
		
		$stakeholders=",Stkid='".$this->m_stakeholders."'";
		if ($this->m_stakeholders!='') $strSql .=$stakeholders;
		
		$provinces=",province_id='".$this->m_provinces."'";
		if ($this->m_provinces!='') $strSql .=$provinces;
		
		$logo=",logo='".$this->m_logo."'";
		if ($this->m_logo!='') $strSql .=$logo;

		$homepage=",homepage_chk=".$this->m_homepage;
		if ($this->m_homepage!='') $strSql .=$homepage;


		$strSql .=" WHERE id=".$this->m_npkId;
		
		
		$rsSql = mysql_query($strSql) or die("Error Edit Content");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	
	function Deletelogocontent()
	{
		$strSql = "DELETE FROM tbl_cms WHERE id=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error Delete Content");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
}
	
	
?>