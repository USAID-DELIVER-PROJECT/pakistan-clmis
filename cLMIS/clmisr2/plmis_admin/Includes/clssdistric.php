<?php
class clsdistric
{
	//var $m_npkId;
	var $m_whrec_id;
	var $m_wh_name;
 	var $m_wh_address;
 	var $m_contact_person;
 	var $m_contemail;
 	var $m_province;
	var $m_counter;
 	var $m_dist_id;
	 
  
	function Adddistric()
	{
		$strSql = "INSERT INTO  tbl_districts (whrec_id,wh_name,wh_address,contact_person,contemail,province,counter,dist_id) VALUES('".$this->m_whrec_id."','".$this->m_wh_name."','".$this->m_wh_address."','".$this->m_contact_person."','".$this->m_contemail."',".$this->m_province.",".$this->m_counter.",".$this->m_dist_id.")";
		$rsSql = mysql_query($strSql) or die("Error Adddistric");
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function Editdistric()
	{
		$strSql = "UPDATE tbl_districts SET whrec_id=".$this->m_whrec_id.",wh_name=".$this->m_wh_name.",wh_address=".$this->m_wh_address.",contact_person=".$this->m_contact_person.",contemail='".$this->m_contemail."',province='".$this->m_province."',counter='".$this->m_counter."' WHERE dist_id=".$this->m_dist_id;
		//echo $strSql; exit();
		$rsSql = mysql_query($strSql) or die("Error Editdistric");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	
	function Deletedistric()
	{
	
		$strSql = "DELETE FROM `lmisdbn`.`tbl_districts` WHERE `tbl_districts`.`dist_id`=".$this->m_dist_id;
		$rsSql = mysql_query($strSql) or die("Error Deletedistric".$this->m_dist_id);
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAlldistric()
	{
		$strSql = 	"SELECT
						tbl_districts.whrec_id,
						tbl_districts.wh_name,
						tbl_districts.wh_address,
						tbl_districts.contact_person,
						tbl_districts.contemail,
						province.prov_id,
						province.prov_title,
						tbl_districts.counter,
						tbl_districts.dist_id
						FROM
						tbl_districts
						Inner Join province ON tbl_districts.province = province.prov_id";
		$rsSql = mysql_query($strSql) or die("Error GetAlldistric");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetdistricById()
	{
		$strSql = "SELECT
						tbl_districts.whrec_id,
						tbl_districts.wh_name,
						tbl_districts.wh_address,
						tbl_districts.contact_person,
						tbl_districts.contemail,
						province.prov_id,
						province.prov_title,
						tbl_districts.counter,
						tbl_districts.dist_id
						FROM
						tbl_districts
						Inner Join province ON tbl_districts.province = province.prov_id
						WHERE dist_id=".$this->m_dist_id;
		$rsSql = mysql_query($strSql) or die("Error GetdistricById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetdistricByProvince()
	{
		$strSql = "SELECT
						tbl_districts.whrec_id,
						tbl_districts.wh_name,
						tbl_districts.wh_address,
						tbl_districts.contact_person,
						tbl_districts.contemail,
						province.prov_id,
						province.prov_title,
						tbl_districts.counter,
						tbl_districts.dist_id
						FROM
						tbl_districts
						Inner Join province ON tbl_districts.province = province.prov_id
						WHERE tbl_districts.province=".$this->m_province;
		$rsSql = mysql_query($strSql) or die("Error GetdistricById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
}
?>
