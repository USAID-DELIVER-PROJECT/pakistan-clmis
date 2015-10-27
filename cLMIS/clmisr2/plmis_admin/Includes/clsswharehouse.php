<?php

class clswarehouse {

    //var $m_npkId;

    var $m_npkId;
    var $m_wh_name;
    var $m_dist_id;
    var $m_prov_id;
    var $m_stkid;
    var $m_stkofficeid;
    var $hf_type_id;
    var $district = array('');

    function Addwarehouse() {
        if ($this->m_stkid == '')
            $this->m_stkid = 'NULL';
        if ($this->m_stkofficeid == '')
            $this->m_stkofficeid = 'NULL';
        if ($this->m_prov_id == '')
            $this->m_prov_id = 'NULL';
        if ($this->m_dist_id == '')
            $this->m_dist_id = 'NULL';
        if ($this->m_wh_name == '')
            $this->m_wh_name = 'NULL';

        $strSql = "INSERT INTO  tbl_warehouse (stkid,stkofficeid,prov_id,dist_id,wh_name,locid, hf_type_id) VALUES(" . $this->m_stkid . "," . $this->m_stkofficeid . "," . $this->m_prov_id . "," . $this->m_dist_id . ",'" . $this->m_wh_name . "'," . $this->m_dist_id . ",'" . $this->hf_type_id . "')";
//		print $strSql;
//		exit; 
        $rsSql = mysql_query($strSql) or die("Error Addwarehouse");

        if (mysql_insert_id() > 0)
            return mysql_insert_id();
        else
            return 0;
    }

    function Editwarehouse() {
        $strSql = "UPDATE tbl_warehouse SET wh_id=" . $this->m_npkId;

        $stkid = ",stkid='" . $this->m_stkid . "'";
        if ($this->m_stkid != '')
            $strSql .=$stkid;

        $stkofficeid = ",stkofficeid=" . $this->m_stkofficeid;
        if ($this->m_stkofficeid != '')
            $strSql .=$stkofficeid;

        $prov_id = ",prov_id=" . $this->m_prov_id;
        if ($this->m_prov_id != '')
            $strSql .=$prov_id;

        $dist_id = ",dist_id='" . $this->m_dist_id . "'";
        if ($this->m_dist_id != '')
            $strSql .=$dist_id;

        $wh_name = ",wh_name='" . $this->m_wh_name . "'";
        if ($this->m_wh_name != '')
            $strSql .=$wh_name;
			
        $hf_type_id = ",hf_type_id='" . $this->hf_type_id . "'";
        if ($this->hf_type_id != '')
            $strSql .=$hf_type_id;



        $strSql .=" WHERE wh_id=" . $this->m_npkId;

        //echo $strSql; exit();
        $rsSql = mysql_query($strSql) or die("Error Editwarehouse");
        if (mysql_affected_rows())
            return $rsSql;
        else
            return FALSE;
    }

    function Deletewarehouse() {

        $strSql = "DELETE FROM tbl_warehouse WHERE wh_id=" . $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("Error Deletewarehousehhhh");
        if (mysql_affected_rows())
            return TRUE;
        else
            return FALSE;
    }
function allowIM() {
		$getStatusQry="select is_allowed_im from tbl_warehouse where wh_id=". $this->m_npkId;
		$resQry=mysql_query($getStatusQry);
		$rowImAllowed=mysql_fetch_assoc($resQry);
		$allowed='';
		if($rowImAllowed['is_allowed_im']==1)
		{
			$allowed=0;
		}
		else{
			$allowed=1;
		}
         $strSql = "Update tbl_warehouse set is_allowed_im=$allowed WHERE wh_id=" . $this->m_npkId;;
        $rsSql = mysql_query($strSql) or die("Error allow_im_update");
        if (mysql_affected_rows())
            return TRUE;
        else
            return FALSE;
    }
    function GetAllWarehouses() {
        $strSql = "SELECT
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
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
    
    function GetAllUCCenters($id){
       $strSql = "SELECT
            DISTINCT tbl_warehouse.wh_id,
            tbl_warehouse.wh_name
            FROM
            tbl_warehouse
            WHERE
            tbl_warehouse.locid = $id";
        $rsSql = mysql_query($strSql) or die("Error GetAllUCCenters");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetUserWarehouses() {
     $strSql = "SELECT
				tbl_warehouse.wh_name,
				tbl_warehouse.wh_id
			FROM
				tbl_warehouse
			INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
			WHERE
				stakeholder.stk_type_id = 2
			ORDER BY
				stakeholder.stkorder ASC";

        $rsSql = mysql_query($strSql) or die("Error GetUserWarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
	
	function GetUserIssueToWH($wh_id){
		 $strSql = "SELECT
			CONCAT(tbl_warehouse.wh_name, '(', stakeholder.stkname, ')') AS wh_name,
			tbl_warehouse.wh_id
		FROM
		tbl_warehouse
		INNER JOIN tbl_stock_master ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		WHERE
		tbl_stock_master.WHIDFrom = $wh_id
		GROUP BY tbl_warehouse.wh_name";
		$rsSql = mysql_query($strSql) or die("Error GetUserIssueToWH");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
	}
	
	function DelGetUserIssueToWH($wh_id){
		$strSql = "SELECT
					tbl_warehouse.wh_name,
					tbl_warehouse.wh_id
				FROM
					tbl_warehouse
				INNER JOIN log_tbl_stock_master ON log_tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
				WHERE
					log_tbl_stock_master.TranTypeID = 2
				GROUP BY
					tbl_warehouse.wh_name
				ORDER BY
					tbl_warehouse.wh_name";
		$rsSql = mysql_query($strSql) or die("Error GetUserIssueToWH");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
	}
	
	function GetUserReceiveFromWH($wh_id){
		 $strSql = "SELECT
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_id
		FROM
		tbl_warehouse
		INNER JOIN tbl_stock_master ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
		WHERE
		tbl_stock_master.WHIDTo = $wh_id
		AND tbl_stock_master.TranTypeID = 1
		GROUP BY tbl_warehouse.wh_name";

		$rsSql = mysql_query($strSql) or die("Error GetUserReceiveFromWH");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
	}
	function DelGetUserReceiveFromWH($wh_id){
		$strSql = "SELECT
					tbl_warehouse.wh_name,
					tbl_warehouse.wh_id
				FROM
					tbl_warehouse
				INNER JOIN log_tbl_stock_master ON log_tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
				WHERE
					log_tbl_stock_master.TranTypeID = 1
				GROUP BY
					tbl_warehouse.wh_name
				ORDER BY
					tbl_warehouse.wh_name";

		$rsSql = mysql_query($strSql) or die("Error GetUserReceiveFromWH");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
	}
	
	function getFifthLvlWH($wh_id){
		$strSql = "SELECT
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_id
			FROM
			tbl_warehouse
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
			stakeholder.lvl = 5 AND
			tbl_warehouse.wh_id = $wh_id";
		$rsSql = mysql_query($strSql) or die("Error getFifthLvlWH");
        if (!empty($rsSql) && mysql_num_rows($rsSql) > 0)
            return TRUE;
        else
            return FALSE;
	}

    function GetLevelDownWarehouses() {
        $strSql = "SELECT
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_id
			FROM
			tbl_warehouse
			INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
			INNER JOIN wh_user ON wh_user.wh_id = tbl_warehouse.wh_id
			INNER JOIN sysuser_tab ON sysuser_tab.UserID = wh_user.sysusrrec_id
			WHERE
			stakeholder.IsSupplier = 1";
        $rsSql = mysql_query($strSql) or die("Error GetWarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWarehousesByProvStk($user_prov, $user_stk) {
        $stk = implode(",", $user_stk);
        $prov = implode(",", $user_prov);

        //$strSql = "SELECT * FROM tbl_warehouse WHERE stkid IN (".$stk.") AND prov_id IN (".$prov.") GROUP BY wh_name";
        $strSql = "SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.wh_type_id,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
					FROM
					tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
					INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
					WHERE tbl_warehouse.stkid IN (" . $stk . ") AND tbl_warehouse.prov_id IN (" . $prov . ")
					ORDER BY tbl_locations.LocName,tbl_warehouse.stkid,tbl_warehouse.stkofficeid";
        $rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetFederalWarehouses($user_stk) {

        $strSql = "SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND stakeholder.lvl = 1
					AND tbl_warehouse.wh_id != ".$_SESSION['wh_id']."
					ORDER BY
						tbl_warehouse.stkid ASC,
						tbl_warehouse.stkofficeid ASC";
		
        $rsSql = mysql_query($strSql) or die("Error GetFederalWarehouses: ".mysql_error());
        if (mysql_num_rows($rsSql) > 0){
			return $rsSql;
		} else {
			return FALSE;
		}            
    }

    function GetProvincialWarehouses($user_prov, $user_stk,$mainstk) {
       $strSql = " SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_warehouse.prov_id = $user_prov
				AND stakeholder.lvl = 2
				AND stakeholder.MainStakeholder = $mainstk
				AND tbl_warehouse.wh_id != ".$_SESSION['wh_id']."
				ORDER BY
					tbl_warehouse.wh_name ASC";
         						// $strSql;
         /* exit; */
        $rsSql = mysql_query($strSql) or die("Error GetProvincialWarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetDivsionalWarehousesofProvince($user_prov, $user_stk) {

        $strSql = " SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND tbl_warehouse.prov_id = $user_prov
					AND tbl_warehouse.wh_id != ".$_SESSION['wh_id']."
					AND stakeholder.lvl = 3
					ORDER BY
						tbl_warehouse.wh_name ASC";
         //		echo $strSql;
          
        $rsSql = mysql_query($strSql) or die("Error GetDivsionalWarehousesofProvince");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetDistrictWarehousesofProvince($user_prov, $user_stk,$mainstk) {

          $strSql = " SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_warehouse.prov_id = $user_prov
				AND stakeholder.lvl = 3
				AND stakeholder.MainStakeholder = $mainstk
				AND tbl_warehouse.wh_id != ".$_SESSION['wh_id']."
				ORDER BY
					tbl_warehouse.wh_name ASC";
        /* 						echo $strSql;
          exit; */
        $rsSql = mysql_query($strSql) or die($strSql);

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
function GetFieldWarehousesofProvince($user_prov, $user_stk,$mainstk) {

          $strSql = " SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_warehouse.prov_id = $user_prov
				AND stakeholder.lvl = 4
				AND stakeholder.MainStakeholder = $mainstk
				ORDER BY
					tbl_warehouse.wh_name ASC";
        /* 						echo $strSql;
          exit; */
        $rsSql = mysql_query($strSql) or die($strSql);

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
	
function GetHealthFacilityWarehousesofDistrict($distId, $mainstk) {

          $strSql = " SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_warehouse.dist_id = $distId
				AND stakeholder.lvl = 7
				AND stakeholder.MainStakeholder = $mainstk
				ORDER BY
					tbl_warehouse.wh_name ASC";
		/*echo $strSql;
		exit;*/
        $rsSql = mysql_query($strSql) or die($strSql);

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }	
function GetLevel8WarehousesofDistrict($distId, $mainstk) {

          $strSql = " SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.locid,
					tbl_warehouse.stkofficeid,
					stakeholder.stkname
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_warehouse.dist_id = $distId
				AND stakeholder.lvl = 8
				AND stakeholder.MainStakeholder = $mainstk
				ORDER BY
					tbl_warehouse.wh_name ASC";
		/*echo $strSql;
		exit;*/
        $rsSql = mysql_query($strSql) or die($strSql);

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
    
    function GetDistrictWarehousesofProvinceforShiftData($user_prov, $user_stk) {

        $strSql = " SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND tbl_warehouse.prov_id = $user_prov
					AND stakeholder.lvl = 4 AND tbl_warehouse.wh_id IN (SELECT DISTINCT
wh2.wh_id
FROM
tbl_stock_master
INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
INNER JOIN tbl_warehouse AS wh2 ON tbl_stock_master.WHIDTo = wh2.wh_id
INNER JOIN stakeholder AS stk2 ON wh2.stkofficeid = stk2.stkid
WHERE
stakeholder.stk_type_id = 1 AND
stk2.lvl > 1)
					ORDER BY
						tbl_warehouse.wh_name ASC";
 
        $rsSql = mysql_query($strSql) or die("Error GetProvincialWarehouses");

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
	
	function GetWarehousesofDivision($user_div, $user_stk) {

        $strSql = " SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND tbl_warehouse.locid = $user_div
					AND stakeholder.lvl = 3
					ORDER BY
						tbl_warehouse.stkid ASC,
						tbl_warehouse.stkofficeid ASC";
        /* 						echo $strSql;
          exit; */
        $rsSql = mysql_query($strSql) or die("Error GetProvincialWarehouses");

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetTehsilWarehousesofDistrict($user_dist, $user_stk) {

        $strSql = " SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND tbl_warehouse.dist_id = $user_dist
					AND stakeholder.lvl = 4
					ORDER BY
						tbl_warehouse.stkid ASC,
						tbl_warehouse.stkofficeid ASC";
        /* 						echo $strSql;
          exit; */
        $rsSql = mysql_query($strSql) or die("Error GetProvincialWarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetUCWarehousesofDistrict($user_dist, $user_stk) {

        $strSql = " SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.locid,
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						tbl_warehouse.stkid = $user_stk
					AND tbl_warehouse.dist_id = $user_dist
					AND stakeholder.lvl = 6
					ORDER BY
						tbl_warehouse.stkid ASC,
						tbl_warehouse.stkofficeid ASC";
        /* 						echo $strSql;
          exit; */
        $rsSql = mysql_query($strSql) or die("Error GetProvincialWarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWarehousesByLevel($from, $to) {
        $strSql = "SELECT
			tbl_warehouse.wh_name,
			tbl_warehouse.wh_id
			FROM
				tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
				INNER JOIN wh_user ON wh_user.wh_id = tbl_warehouse.wh_id
				INNER JOIN sysuser_tab ON sysuser_tab.UserID = wh_user.sysusrrec_id
			WHERE
			stakeholder.IsSupplier = 1 AND
			stakeholder.lvl >= " . $from . " AND
			stakeholder.lvl <= " . $to;

        $rsSql = mysql_query($strSql) or die("Error GetWarehousesByLevel");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }
	
	function GetWarehouseLevelById($id) {
        $strSql = "SELECT
			stakeholder.lvl
			FROM
			tbl_warehouse
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
			tbl_warehouse.wh_id = $id";

        $rsSql = mysql_query($strSql) or die("Error GetWarehouseLevelById");
        if (mysql_num_rows($rsSql) > 0){
			$row = mysql_fetch_object($rsSql);
			return $row->lvl;
		} else {
			return FALSE;
		}            
    }

    function GetAllwarehouseprovince() {
        $strSql = "SELECT
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
                    where province.PkLocID=" . $this->provid;

        $rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWarehouseById() {
        $strSql = "SELECT
                    tbl_warehouse.wh_id,
                    tbl_warehouse.wh_name,
                    tbl_warehouse.stkofficeid,
                    tbl_warehouse.hf_type_id,
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
                    Left Join tbl_locations AS province ON district.ParentID = province.PkLocID WHERE wh_id=" . $this->m_npkId;
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("Error GetwarehouseById");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWarehouseNameById() {
        $strSql = "SELECT tbl_warehouse.wh_name from tbl_warehouse
					 WHERE wh_id=" . $this->m_npkId;
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("GetWarehouseNameById");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $whName = $row['wh_name'];
            }
            return $whName;
        }
        else
            return FALSE;
    }
    function GetWarehouseTypeNameById() {
        $strSql = "SELECT
						tbl_hf_type.hf_type
					FROM
						tbl_hf_type
					WHERE
						tbl_hf_type.pk_id = " . $this->m_npkId;
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("GetWarehouseNameById");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $whName = $row['hf_type'];
            }
            return $whName;
        }
        else
            return FALSE;
    }

    function GetStkIDByWHId() {
        $strSql = "SELECT tbl_warehouse.Stkid from tbl_warehouse
					 WHERE wh_id=" . $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("GetStkIDByWHId");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $whName = $row['Stkid'];
            }
            return $whName;
        }
        else {
			return FALSE;
		}            
    }
    function GetStkIDByWHTypeId() {
        $strSql = "SELECT tbl_warehouse.Stkid from tbl_warehouse
					 WHERE hf_type_id=" . $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("GetStkIDByWHId");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $whName = $row['Stkid'];
            }
            return $whName;
        }
        else {
			return FALSE;
		}            
    }
    function GetWHByWHId($wh_id) {
        $strSql = "SELECT wh_name from tbl_warehouse
					 WHERE wh_id=".$wh_id;
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("GetWarehouseNameById");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $wh_name = $row['wh_name'];
            }
            return $wh_name;
        }
        else
            return FALSE;
    }

	function IsDivWHofProvWH($div_id,$prov_id) {
        $strSql = " SELECT
						tbl_warehouse.wh_id
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						prov_id = (
							SELECT
								prov_id
							FROM
								tbl_warehouse
							WHERE
								wh_id = $div_id
						)
					AND wh_id = $prov_id
					AND stakeholder.lvl = 2";
/*        print $strSql; 
        exit;*/
        $rsSql = mysql_query($strSql) or die("Error: IsDivWHofProvWH -> clsswharehouse.php");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) 
            return 1;
        else
            return 0;
    }
	
	function IsSubWHofDistWH($SubWH_id,$Dist_id) {
        $strSql = " SELECT
						tbl_warehouse.wh_id
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						prov_id = (
							SELECT
								prov_id
							FROM
								tbl_warehouse
							WHERE
								wh_id = $SubWH_id
						)
					AND wh_id = $Dist_id
					AND stakeholder.lvl = 4";
/*        print $strSql; 
        exit;*/
        $rsSql = mysql_query($strSql) or die("Error: IsSubWHofDistWH -> clsswharehouse.php");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) 
            return 1;
        else
            return 0;
    }
	
    function GetWarehouseBylocByStakeholderOffice() {
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
                    Left Join tbl_locations AS province ON district.ParentID = province.PkLocID WHERE tbl_warehouse.stkofficeid=" . $this->m_stkofficeid . " and tbl_warehouse.locid=" . $this->m_dist_id;
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("Error GetWarehouseBylocByStakeholderOfficee");


        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWarehouseBylocByStakeholder() {
        $district = $this->m_dist_id;
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
                    WHERE tbl_warehouse.stkid=" . $this->m_stkid . "
                    and tbl_warehouse.locid IN($district)";
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("Error GetWarehouseBylocByStakeholder");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllWarehouses1() {
        $strSql = "SELECT
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
                    WHERE tbl_warehouse.stkid='" . $this->m_stkid . "' AND province.PkLocID='" . $this->m_provid . "'
                    Order by province,district,officeName
                    ";

        $rsSql = mysql_query($strSql) or die("Error GetAllwarehouses");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllWarehouses0() {
        $strSql = "SELECT
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
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetWHTypes() {
        $strSql = "SELECT
						tbl_hf_type.pk_id,
						tbl_hf_type.hf_type
					FROM
						tbl_hf_type
					ORDER BY
						tbl_hf_type.hf_type ASC";

        $rsSql = mysql_query($strSql) or die("Error GetWHTypes");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

}

?>
