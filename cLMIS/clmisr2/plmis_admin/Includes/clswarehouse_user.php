<?php
class clsIwh_user
{
	var $m_npkId;
	var $m_wh_user_id;
 	var $m_sysusrrec_id;
	var $m_wh_id;
	var $m_stk_id;
	var $m_prov_id;
 	
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
								stakeholder.lvl
							FROM
								sysuser_tab
							INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
							INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
							INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
							WHERE
								wh_user.sysusrrec_id = ".$this->m_npkId."
							AND stakeholder.lvl <= 4 
						) A
					GROUP BY
						A.wh_id
					ORDER BY
						A.lvl ASC,
						A.wh_name ASC";
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error Getwh_userById");
		//print mysql_num_rows($rsSql);
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetwhuserHFByIdc()
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
								stakeholder.lvl,
								tbl_hf_type_rank.hf_type_rank,
                                tbl_warehouse.wh_rank          
							FROM
								sysuser_tab
							INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
							INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
							INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
							INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
							WHERE
								wh_user.sysusrrec_id = ".$this->m_npkId."
							AND stakeholder.lvl > 4
							AND tbl_hf_type_rank.stakeholder_id = ".$this->m_stk_id."
							AND tbl_hf_type_rank.province_id = ".$this->m_prov_id."
						) A
					GROUP BY
						A.wh_id
					ORDER BY
                        IF(A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
						A.wh_rank,
						A.hf_type_rank ASC,
						A.wh_name ASC";
		//echo($strSql);
		$rsSql = mysql_query($strSql) or die("Error Getwh_userById");
		//print mysql_num_rows($rsSql);
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetwhuserHFSatelliteByIdc()
	{
		$strSql = "SELECT
						*
					FROM
						(
							SELECT
								wh_user.sysusrrec_id,
								sysuser_tab.sysusr_name,
								tbl_warehouse.wh_id,
								CONCAT('Satellite Camp - ', tbl_warehouse.wh_name) AS wh_name,
								sysuser_tab.usrlogin_id,
								stakeholder.lvl,
								tbl_hf_type_rank.hf_type_rank,
                                tbl_warehouse.wh_rank          
							FROM
								sysuser_tab
							INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
							INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
							INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
							INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
							WHERE
								wh_user.sysusrrec_id = ".$this->m_npkId."
							AND stakeholder.lvl > 4
							AND tbl_hf_type_rank.stakeholder_id = ".$this->m_stk_id."
							AND tbl_hf_type_rank.province_id = ".$this->m_prov_id."
							AND tbl_warehouse.hf_type_id IN (1, 2)
						) A
					GROUP BY
						A.wh_id
					ORDER BY
                        IF(A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
						A.wh_rank,
						A.hf_type_rank ASC,
						A.wh_name ASC";
		//exit($strSql);
		$rsSql = mysql_query($strSql) or die("Error Getwh_userById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	function GetwhuserHFTypeByIdc()
	{
		$strSql = "SELECT
						*
					FROM
						(
							SELECT
								wh_user.sysusrrec_id,
								sysuser_tab.sysusr_name,
								sysuser_tab.usrlogin_id,
								CONCAT(DATE_FORMAT(tbl_hf_type_data.last_update,'%d/%m/%Y'),' ',TIME_FORMAT(tbl_hf_type_data.last_update,'%r')) AS last_update,
								tbl_hf_type.pk_id,
								tbl_hf_type.hf_type,
								tbl_hf_type_rank.hf_type_rank
							FROM
								sysuser_tab
							INNER JOIN wh_user ON wh_user.sysusrrec_id = sysuser_tab.UserID
							INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
							LEFT JOIN tbl_hf_type_data ON tbl_warehouse.hf_type_id = tbl_hf_type_data.facility_type_id 
							AND tbl_warehouse.dist_id = tbl_hf_type_data.district_id
							LEFT JOIN tbl_hf_type ON tbl_warehouse.hf_type_id = tbl_hf_type.pk_id
							INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id
							WHERE
								wh_user.sysusrrec_id = ".$this->m_npkId."
							AND tbl_hf_type.hf_type IS NOT NULL
							AND tbl_warehouse.dist_id = ".$_SESSION['dist_id']."
							AND tbl_hf_type_rank.stakeholder_id = ".$this->m_stk_id."
							AND tbl_hf_type_rank.province_id = ".$this->m_prov_id."
							GROUP BY
								tbl_hf_type.pk_id
						) A
					GROUP BY
						A.pk_id
					ORDER BY
						A.hf_type_rank ASC";
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

function GetProvinceIdByIdc()
	{
		$strSql = "SELECT
                tbl_warehouse.prov_id
                FROM
                wh_user
                INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
                WHERE
                wh_user.sysusrrec_id = ".$this->m_npkId."
                LIMIT 1
                ";
		//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error Getwh_userById");
		//print mysql_num_rows($rsSql);
		if(mysql_num_rows($rsSql)>0)
			return mysql_fetch_array ($rsSql);
		else
			return FALSE;
	}
}
?>
