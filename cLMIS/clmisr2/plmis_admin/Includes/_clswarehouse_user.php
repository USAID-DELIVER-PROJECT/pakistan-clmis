<?php
class clsIwh_user
{
	var $m_npkId;
	var $m_wh_user_id;
 	var $m_sysusrrec_id;
	var $m_wh_id;
 	
	function Addwh_user()
	{
		if ($this->m_sysusrrec_id=='') $this->m_sysusrrec_id=0;
		if ($this->m_wh_id=='') $this->m_wh_id=0;
		       
		$strSql = "INSERT INTO wh_user(sysusrrec_id,wh_id) VALUES(".$this->m_sysusrrec_id.",".$this->m_wh_id.")";
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error Addwh_user");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function Editwh_user()
	{
	/*print "inside EditStakeholder";*/
		
		$strSql = "UPDATE wh_user SET wh_user_id=".$this->m_npkId;  
		
		
		
		$sysusrrec_id=",sysusrrec_id='".$this->m_sysusrrec_id."'";
		if ($this->m_sysusrrec_id!='') $strSql .=$sysusrrec_id;
		
		$wh_id=",wh_id='".$this->m_wh_id."'";
		if ($this->m_wh_id!='') $strSql .=$wh_id;       
		
		$strSql .=" WHERE wh_user_id=".$this->m_npkId;
		
		
		
		
		//print $strSql; 
//		exit;
		$rsSql = mysql_query($strSql) or die("Error wh_user");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function Deletewh_user()
	{
		$strSql = "DELETE FROM  wh_user WHERE wh_user_id=".$this->m_npkId;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error Deletewh_user");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function Deletewh_userbyuserid()
	{
		$strSql = "DELETE FROM  wh_user WHERE sysusrrec_id=".$this->m_sysusrrec_id;
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error Deletewh_userbyuserid");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	
	function GetAllwh_user()
	{
	
	$strSql = "SELECT
					wh_user.sysusrrec_id,
					sysuser_tab.sysusr_name,
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					sysuser_tab.UserID,
					wh_user.wh_id,
					sysuser_tab.usrlogin_id,
					stakeholder.stkid,
					stakeholder.stkname
					FROM
					sysuser_tab
					Left Join wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
					Left Join tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
					Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid";
		$rsSql = mysql_query($strSql) or die("Error GetAllwh_user");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function Getwh_userById()
	{
			$strSql = "
				SELECT
					wh_user.sysusrrec_id,
					sysuser_tab.sysusr_name,
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					sysuser_tab.UserID,
					wh_user.wh_id,
					sysuser_tab.usrlogin_id,
					stakeholder.stkid,
					stakeholder.stkname
					FROM
					sysuser_tab
					Left Join wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
					Left Join tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
					Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
					WHERE wh_user.wh_user_id=".$this->m_npkId;
	
		$rsSql = mysql_query($strSql) or die("Error Getwh_userByIdhhhhhhh");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}

	function GetwhuserByIdc()
	{
		$strSql = "SELECT
						*
					FROM
						(
							SELECT
								wh_user.sysusrrec_id,
								sysuser_tab.sysusr_name,
								tbl_warehouse.wh_id,
								tbl_warehouse.wh_name,
								sysuser_tab.usrlogin_id,
								CONCAT(DATE_FORMAT(tbl_wh_data.last_update,'%d/%m/%Y'),' ',TIME_FORMAT(tbl_wh_data.last_update,'%r')) AS last_update,
								tbl_wh_data.w_id
							FROM
								sysuser_tab
							INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
							INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
							INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
							WHERE
								wh_user.sysusrrec_id = ".$this->m_npkId."
							ORDER BY
								tbl_wh_data.w_id DESC
						) A
					GROUP BY
						A.wh_id";
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error Getwh_userById");
		//print mysql_num_rows($rsSql);
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function Getwh_userinCSV()
	{
	$csv="";
		$objwharehouse_user = $this->Getwh_userById();
		if($objwharehouse_user!=FALSE && mysql_num_rows($objwharehouse_user)>0)
		{
			while($Rowranks = mysql_fetch_object($objwharehouse_user))
			{
				$csv.=$Rowranks->wh_name.",";
				//print $csv;
			}		
		}
		
		return $csv;
	}
	
	function wh_userdelete(){
		$strSql = "DELETE FROM wh_user WHERE sysusrrec_id=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error Deletewh_user");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
		
	}


}
?>
