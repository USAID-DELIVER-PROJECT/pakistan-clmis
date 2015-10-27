<?php
class clsStakeholder
{
	var $m_npkId;
	var $m_stkname;
 	var $m_stkorder;
 	var $m_ParentID;
 	var $m_stk_type_id;
	var $m_lvl;
	var $m_MainStakeholder;

   function AddStakeholder() {
        if ($this->m_stkname == '')
            $this->m_stkname = 'NULL';
        if ($this->m_stkorder == '')
            $this->m_stkorder = 'NULL';
        if ($this->m_ParentID == '')
            $this->m_ParentID = 'NULL';
        if ($this->m_stk_type_id == '')
            $this->m_stk_type_id = 0;
        if ($this->m_lvl == '')
            $this->m_lvl = 1;
        if ($this->m_MainStakeholder == '')
            $this->m_MainStakeholder = 'NULL';


        $strSql = "INSERT INTO  stakeholder (stkname,stkorder,ParentID,stk_type_id,lvl,MainStakeholder) VALUES('" . $this->m_stkname . "'," . $this->m_stkorder . "," . $this->m_ParentID . "," . $this->m_stk_type_id . "," . $this->m_lvl . "," . $this->m_MainStakeholder . ")";

        $rsSql = mysql_query($strSql) or die("Error AddStakeholder");

        $id = mysql_insert_id();
        if ($id > 0) {
            if ($this->m_MainStakeholder == 'NULL') {
                $strSql1 = "Update stakeholder SET MainStakeholder=" . $id . " where stkid=" . $id;
                $rsSql = mysql_query($strSql1) or die($strSql1 . mysql_error());
            }
            return $id;
        } else
            return 0;
    }
	
	function EditStakeholder()
	{
	/*print "inside EditStakeholder";*/
		$strSql = "UPDATE stakeholder SET stkid=".$this->m_npkId;
		
		$stkname=",stkname='".$this->m_stkname."'";
		if ($this->m_stkname!='') $strSql .=$stkname;
		
		$stkorder=",stkorder=".$this->m_stkorder;
		if ($this->m_stkorder!='') $strSql .=$stkorder;
		
		$ParentID=",ParentID=".$this->m_ParentID;
		if ($this->m_ParentID!='') $strSql .=$ParentID;
		
		if(isset($this->m_stk_type_id) && !empty($this->m_stk_type_id))
		{
			$stk_type_id=",stk_type_id=".$this->m_stk_type_id;
			$strSql .=$stk_type_id;
		}

		$lvl=",lvl=".$this->m_lvl;
		if ($this->m_lvl!='') $strSql .=$lvl;
		
		$MainStakeholder=",MainStakeholder=".$this->m_MainStakeholder;
		if ($this->m_MainStakeholder!='') $strSql .=$MainStakeholder;
		
		$strSql .=" WHERE stkid=".$this->m_npkId;

		
		//print $strSql; 
		//exit;
		$rsSql = mysql_query($strSql) or die("Error EditStakeholder");
		if(mysql_affected_rows())
			return $rsSql;
		else
			return FALSE;
	}
	function DeleteStakeholder()
	{
		$strSql = "DELETE FROM  stakeholder WHERE stkid=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die("Error DeleteStakeholder");
		if(mysql_affected_rows())
			return TRUE;
		else
			return FALSE;
	}
	function GetAllStakeholders()
	{
		$strSql = 	"SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null
						ORDER BY stakeholder.stkid";
		$rsSql = mysql_query($strSql) or die("Error GetAllStakeholdersttttttttt");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetStakeholdersByUserId($stkIds)
	{
		$strSql = 	"SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null AND stakeholder.stkid IN (".$stkIds.")
						ORDER BY stakeholder.stkid";
		$rsSql = mysql_query($strSql) or die("Error GetAllStakeholdersttttttttt");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetStakeholdersLowersLevels()
	{
	if ($this->m_npkId!='')
	$whereclause="WHERE stakeholder.stkid=".$this->m_npkId;
	else
	$whereclause='';
	
		$strSql = 	"SELECT lvl_id,lvl_name from tbl_dist_levels where tbl_dist_levels.lvl_id > (
					SELECT ifnull(max(lvl),0) as toplvl FROM stakeholder ".$whereclause.")";
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetStakeholdersLowersLevels");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetAllLevels()
	{
		$strSql = 	"SELECT lvl_id,lvl_name from tbl_dist_levels";
		$rsSql = mysql_query($strSql) or die("Error GetAllLevels");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetAllStakeholdersOfficeTypes()
	{
		$strSql = 	"SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name,
					stakeholder.MainStakeholder
					FROM
					stakeholder
					Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
					Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
					Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
					ORDER BY stakeholder.stkid";
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllStakeholdersOfficeTypes");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
		function GetAllStakeholdersAtParentlevel()
	{
	$lvl=$this->m_lvl;
	if ($lvl>1 && $lvl!=7)
		$lvl=$lvl-1;
	else if ($lvl==7)
		$lvl=4;


		$strSql = 	"SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name,
					stakeholder.MainStakeholder
					FROM
					stakeholder
					Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
					Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
					Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
					where stakeholder.lvl=".$lvl." and stakeholder.MainStakeholder =".$this->m_MainStakeholder." ORDER BY stakeholder.stkid";
					//return $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllStakeholdersAtlevel");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetAllStakeholderslddddd()
	{
		$strSql = 	"SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name,
					tbl_dist_levels.lvl_id
					FROM
										stakeholder
										Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
										Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
										Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
										where lvl_id !=3";
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetAllStakeholderslvll");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	
	
	
	
	function GetStakeholdersById()
	{
		$strSql = "
		SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name,
					stakeholder.MainStakeholder
					FROM
					stakeholder
					Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
					Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
					Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
					WHERE stakeholder.stkid=".$this->m_npkId;
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetStakeholderById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetStakeholdersFamilyById()
	{
		$strSql = "
		SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name
					FROM
					stakeholder
					Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
					Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
					Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
					WHERE stakeholder.MainStakeholder=".$this->m_npkId;
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetStakeholdersFamilyById");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	function GetStakeholdersFamilynatandpro()
	{
		$strSql = "SELECT
					stakeholder.stkid,
					stakeholder.stkname,
					stakeholder.stkorder,
					stakeholder_type.stk_type_descr,
					IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
					stakeholder.ParentID,
					stakeholder.stk_type_id,
					stakeholder.lvl,
					tbl_dist_levels.lvl_name,
					tbl_dist_levels.lvl_id
					FROM
										stakeholder
										Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
										Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
										Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
										WHERE tbl_dist_levels.lvl_id=".$this->m_npkId. "OR tbl_dist_levels.lvl_id=".$this->m_npkId;
		
					//print $strSql;
		$rsSql = mysql_query($strSql) or die("Error GetStakeholdersFamilynatandpro");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function GetRanks()
	{
		$strSql = "SELECT DISTINCT stkorder FROM stakeholder order by stkorder DESC";
		$rsSql = mysql_query($strSql) or die("Error GetRanks");
		if(mysql_num_rows($rsSql)>0)
			return $rsSql;
		else
			return FALSE;
	}
	
	
	function get_stakeholder_name()
	{
		$stkname='';
		$strSql = "SELECT stkname FROM stakeholder where stkid=".$this->m_npkId;
		$rsSql = mysql_query($strSql) or die($strSql.mysql_error());
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			$RowEditStk = mysql_fetch_object($rsSql);
			$stkname=$RowEditStk->stkname;
		}
		return $stkname;
	}
	
	function GetMaxRank()
	{
		$MaxRank=0;
		$strSql = "SELECT Max(stkorder) as MaxOrder FROM stakeholder ";
		$rsSql = mysql_query($strSql) or die("Error GetMaxRank");
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			$RowEditStk = mysql_fetch_object($rsSql);
			$MaxRank=(int) ($RowEditStk->MaxOrder+1);
		}
		return $MaxRank;
	}
	
	function GetMaxGroupID()
	{
		$MaxRank=0;
		$strSql = "SELECT Max(stkgroupid) as MaxOrder FROM stakeholder ";
		$rsSql = mysql_query($strSql) or die("Error GetMaxGroupID");
		if($rsSql!=FALSE && mysql_num_rows($rsSql)>0)
		{
			$RowEditStk = mysql_fetch_object($rsSql);
			$MaxRank=(int) ($RowEditStk->MaxOrder);
		}
		return $MaxRank;
	}
	
  function GetAllManufacturersUpdate($item_pack_size_id) {
        $strSql = "SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
                                                Inner Join stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null
                                                and stakeholder.stk_type_id='2'
                                                and stakeholder_item.stk_item = " . $item_pack_size_id . "
						ORDER BY stakeholder.stkid";

        $stSql = mysql_query($strSql) or die("Error GetAllStakeholdersttttttttt");
        //print $strSql;

        if (mysql_num_rows($stSql) > 0) {
            return $stSql;
        } else {
            return FALSE;
        }
    }
     function GetAllManufacturers($item_pack_size_id) {
        $strSql = "SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
                                                Inner Join stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null
                                                and stakeholder.stk_type_id='3'
                                             GROUP BY
						stakeholder_item.stk_item
						ORDER BY stakeholder.stkid";

        $result_set = mysql_query($strSql) or die("Error GetAllStakeholdersttttttttt");

        $object_array = array();
        while ($row = mysql_fetch_object($result_set)) {
            $object_array[] = $row;
        }
        return $object_array;
    }

   /* function GetAllManufacturersUpdate($item_pack_size_id) {
        $strSql = "SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
                                                Inner Join stakeholder_item ON stakeholder.stkid = stakeholder_item.stkid
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null
                                                and stakeholder.stk_type_id='2'
                                                and stakeholder_item.stk_item = " . $item_pack_size_id . "
						ORDER BY stakeholder.stkid";

        $stSql = mysql_query($strSql) or die("Error GetAllStakeholdersttttttttt");

        if (mysql_num_rows($stSql) > 0) {
            return $stSql;
        } else {
            return FALSE;
        }
    }*/
     function GetAllMainStakeholders() {
        $strSql = "SELECT
						stakeholder.stkid,
						stakeholder.stkname
					FROM
						stakeholder
					WHERE
						stakeholder.ParentID IS NULL
					AND stakeholder.stk_type_id IN (0, 1)
					ORDER BY
						stakeholder.stkorder ASC";

        $strSql = mysql_query($strSql) or die("Error GetAllMainStakeholders");

        if (mysql_num_rows($strSql) > 0) {
            return $strSql;
        } else {
            return FALSE;
        }
    }
    
}
?>
