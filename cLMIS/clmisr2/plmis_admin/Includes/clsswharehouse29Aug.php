<?php
class clswarehouse
{
	//var $m_npkId;
	
	var $m_npkId;
	var $m_wh_name;
 	var $m_dist_id;
 	var $m_prov_id;
 	var $m_stkid;
 	var $m_stkofficeid;
	var $district=array('');
	

	function Addwarehouse()
	{
	if ($this->m_stkid=='') $this->m_stkid='NULL';
		if ($this->m_stkofficeid=='') $this->m_stkofficeid='NULL';
		if ($this->m_prov_id =='') $this->m_prov_id='NULL';
		if ($this->m_dist_id=='') $this->m_dist_id='NULL';
		if ($this->m_wh_name=='') $this->m_wh_name='NULL';

		$strSql = "INSERT INTO  tbl_warehouse (stkid,stkofficeid,prov_id,dist_id,wh_name,locid) VALUES(".$this->m_stkid.",".$this->m_stkofficeid.",".$this->m_prov_id.",".$this->m_dist_id.",'".$this->m_wh_name."',".$this->m_dist_id.")";
//		print $strSql;
//		exit; 
		$rsSql = mysql_query($strSql) or die("Error Addwarehouse");
		
		if(mysql_insert_id()>0)
			return mysql_insert_id();
		else
			return 0;
	}
	
	function Editwarehouse()
	{
		$strSql = "UPDATE tbl_warehouse SET wh_id=".$this->m_npkId;
		
		$stkid=",stkid='".$this->m_stkid."'";
		if ($this->m_stkid!='') $strSql .=$stkid;
		
		$stkofficeid=",stkofficeid=".$this->m_stkofficeid;
		if ($this->m_stkofficeid!='') $strSql .=$stkofficeid;
		
		$prov_id=",prov_id=".$this->m_prov_id;
		if ($this->m_prov_id !='') $strSql .=$prov_id;
		
		$dist_id=",dist_id='".$this->m_dist_id."'";
		if ($this->m_dist_id!='') $strSql .=$dist_id;
		
		$wh_name=",wh_name='".$this->m_wh_name."'";
		if ($this->m_wh_name!='') $strSql .=$wh_name;
		
		

		$strSql .=" WHERE wh_id=".$this->m_npkId;
		
		//echo $strSql; exit();
		$rsSql = mysql_query($strSql) or die("Error Editwarehouse");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	
	function Deletewarehouse()
	{

		$strSql = "DELETE FROM tbl_warehouse WHERE wh_id=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error Deletewarehousehhhh");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAllWarehouses()
	{
		$strSql = 	"SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province,
					office.stkname as officeName
					FROM
					tbl_warehouse
					Left Join stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					Left Join stakeholder as office ON tbl_warehouse.stkofficeid = office.stkid
					Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					Left Join tbl_locations AS province ON district.ParentID = province.PkLocID
					Order by province,district,officeName
					";
		$rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
		function GetAllwarehouseprovince()
	{
		$strSql = 	"SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province,
					office.stkname as officeName
					FROM
					tbl_warehouse
					Left Join stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					Left Join stakeholder as office ON tbl_warehouse.stkofficeid = office.stkid
					Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					Left Join tbl_locations AS province ON district.ParentID = province.PkLocID
					where province.PkLocID=".$this->provid;
		$rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetWarehouseById()
	{
		$strSql = "SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province
					FROM
					tbl_warehouse
					Left Join stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					Left Join tbl_locations AS province ON district.ParentID = province.PkLocID WHERE wh_id=".$this->m_npkId;
						//print $strSql; 
						//exit;
		$rsSql = mysql_query($strSql) or die("Error GetwarehouseById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

function GetWarehouseNameById()
	{
		$strSql = "SELECT tbl_warehouse.wh_name from tbl_warehouse
					 WHERE wh_id=".$this->m_npkId;
						//print $strSql; 
						//exit;
		$rsSql = mysql_query($strSql) or die("GetWarehouseNameById");
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			while($row = mysql_fetch_array($rsSql))
			{
			$whName=$row['wh_name'];
			}
		return $whName;
		}
		else
			return FALSE;
	}

function GetStkIDByWHId()
	{
		$strSql = "SELECT tbl_warehouse.Stkid from tbl_warehouse
					 WHERE wh_id=".$this->m_npkId;
						//print $strSql; 
						//exit;
		$rsSql = mysql_query($strSql) or die("GetWarehouseNameById");
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			while($row = mysql_fetch_array($rsSql))
			{
			$whName=$row['Stkid'];
			}
		return $whName;
		}
		else
			return FALSE;
	}
	function GetWarehouseBylocByStakeholderOffice()
	{
		$strSql = "SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province
					FROM
					tbl_warehouse
					Left Join stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					Left Join tbl_locations AS province ON district.ParentID = province.PkLocID WHERE tbl_warehouse.stkofficeid=".$this->m_stkofficeid." and tbl_warehouse.locid=".$this->m_dist_id;
						//print $strSql; 
						//exit;
		$rsSql = mysql_query($strSql) or die("Error GetWarehouseBylocByStakeholderOfficee");
		
		
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetWarehouseBylocByStakeholder()
	{
		$district=$this->m_dist_id;
		$strSql = "SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province
					FROM
					tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					INNER JOIN tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					INNER JOIN tbl_locations AS province ON tbl_warehouse.prov_id = province.PkLocID 
					WHERE tbl_warehouse.stkid=".$this->m_stkid."
					and tbl_warehouse.locid IN($district)";
						//print $strSql; 
						//exit;
		$rsSql = mysql_query($strSql) or die("Error GetWarehouseBylocByStakeholder");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetAllWarehouses1()
	{
		$strSql = 	"SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname,
					tbl_warehouse.stkid,
					district.PkLocID AS dist_id,
					district.LocName AS district,
					province.PkLocID AS prov_id,
					province.LocName AS province,
					office.stkname as officeName
					FROM
					tbl_warehouse
					Left Join stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					Left Join stakeholder as office ON tbl_warehouse.stkofficeid = office.stkid
					Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					Left Join tbl_locations AS province ON district.ParentID = province.PkLocID
					WHERE tbl_warehouse.stkid='".$this->m_stkid."' AND province.PkLocID='".$this->m_provid."'
					Order by province,district,officeName
					";
		
		$rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetAllWarehouses0()
	{
		$strSql = 	"SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.stkofficeid,
						stakeholder.stkname,
						tbl_warehouse.stkid,
						district.PkLocID AS dist_id,
						district.LocName AS district,
						province.PkLocID AS prov_id,
						province.LocName AS province,
						office.stkname AS officeName
					FROM
						tbl_warehouse
					LEFT JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					LEFT JOIN stakeholder AS office ON tbl_warehouse.stkofficeid = office.stkid
					LEFT JOIN tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
					LEFT JOIN tbl_locations AS province ON district.ParentID = province.PkLocID
					ORDER BY
						province,
						district,
						officeName";
		
		$rsSql = mysql_query($strSql) or die("Error GetAllwarehouses0");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
}
?>
