<?php

class clsUser {

    var $m_npkId;
    var $m_stkid;
    var $m_dist_id;
    var $m_prov_id;
    var $m_locid;
    var $m_usrlogin_id;
    var $m_sysusr_pwd;
    var $full_name;
    var $email_id;
    var $phone_no;
    var $fax_no;
    var $address;
    var $sysusr_photo;
    var $sysusr_dept;
    var $sysusr_deg;
    var $sysusr_type;
    var $m_whrec_id;
    var $m_sysusr_photo;

    function AddUser() {
        try {
            if ($this->m_whrec_id == '')
                $this->m_whrec_id = 'NULL';
            if ($this->m_stkid == '')
                $this->m_stkid = 0;
            if ($this->m_dist_id == '')
                $this->m_dist_id = 'NULL';
            if ($this->m_prov_id == '')
                $this->m_prov_id = '0';
            if ($this->m_usrlogin_id == '')
                $this->m_usrlogin_id = 'NULL';
            if ($this->m_full_name == '')
                $this->m_full_name = 'NULL';
            if ($this->m_email_id == '')
                $this->m_email_id = 0;
            if ($this->m_phone_no == '')
                $this->m_phone_no = 'NULL';
            if ($this->m_sysusr_photo == '')
                $this->m_sysusr_photo = 'NULL';
            if ($this->m_sysusr_dept == '')
                $this->m_sysusr_dept = 'NULL';
            if ($this->m_sysusr_deg == '')
                $this->m_sysusr_deg = 'NULL';
            if ($this->m_sysusr_type == '')
                $this->m_sysusr_type = 'NULL';

            $this->m_sysusr_pwd = base64_encode($this->m_sysusr_pwd);
            $hash = md5(strtolower($this->m_usrlogin_id) . '' . $this->m_sysusr_pwd);

            $strSql = "INSERT INTO sysuser_tab(acopen_dt,UserID,stkid,whrec_id,province,usrlogin_id,
		sysusr_name,sysusr_pwd,sysusr_status,sysusr_email,sysusr_ph,sysusr_cell,sysusr_addr,sysusr_dept,sysusr_deg,sysusr_type,sysusr_photo,auth)
		VALUES(NOW()," . ($this->GetMaxUserId() + 1) . "," . $this->m_stkid . "," . $this->m_whrec_id . "," . $this->m_prov_id . ",'" . $this->m_usrlogin_id . "','" . $this->m_full_name . "','" . $this->m_sysusr_pwd . "','Active','" . $this->m_email_id . "','" . $this->m_phone_no . "','" . $this->m_fax_no . "','" . $this->m_address . "','" . $this->m_sysusr_dept . "','" . $this->m_sysusr_deg . "','" . $this->m_sysusr_type . "','" . $this->m_sysusr_photo . "','" . $hash . "')";
            $rsSql = mysql_query($strSql) or die("Error AddUser");
            if (mysql_insert_id() > 0)
                return mysql_insert_id();
            else
                return 0;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
            exit;
        }
    }

    function EditUser() {
        //adding additional options

        $strSql = "UPDATE sysuser_tab SET UserID=" . $this->m_npkId;

        $stkid = ",stkid='" . $this->m_stkid . "'";
        if ($this->m_stkid != '')
            $strSql .=$stkid;

        $prov_id = ",province=" . $this->m_prov_id;
        if ($this->m_prov_id != '')
            $strSql .=$prov_id;

        $m_whrec_id = ",whrec_id=" . $this->m_whrec_id;
        if ($this->m_whrec_id != '')
            $strSql .=$m_whrec_id;

        $usrlogin_id = ",usrlogin_id='" . $this->m_usrlogin_id . "'";
        if ($this->m_usrlogin_id != '')
            $strSql .=$usrlogin_id;

        $sysusr_pwd = ",sysusr_pwd='" . $this->m_sysusr_pwd . "'";
        if ($this->m_sysusr_pwd != '')
            $strSql .=$sysusr_pwd;

        $full_name = ",sysusr_name='" . $this->m_full_name . "'";
        if ($this->m_full_name != '')
            $strSql .=$full_name;

        $phone_no = ",sysusr_ph='" . $this->m_phone_no . "'";
        if ($this->m_phone_no != '')
            $strSql .=$phone_no;

        $email_id = ",sysusr_email='" . $this->m_email_id . "'";
        if ($this->m_email_id != '')
            $strSql .=$email_id;

        $fax_no = ",sysusr_cell='" . $this->m_fax_no . "'";
        $strSql .=$fax_no;

        $address = ",sysusr_addr='" . $this->m_address . "'";
        $strSql .=$address;

        $sysusr_dept = ",sysusr_dept='" . $this->m_sysusr_dept . "'";
        if ($this->m_sysusr_dept != '')
            $strSql .=$sysusr_dept;

        $sysusr_deg = ",sysusr_deg='" . $this->m_sysusr_deg . "'";
        if ($this->m_sysusr_deg != '')
            $strSql .=$sysusr_deg;

        $sysusr_type = ",sysusr_type='" . $this->m_sysusr_type . "'";
        if ($this->m_sysusr_type != '')
            $strSql .=$sysusr_type;

        $m_sysusr_photo = ",sysusr_photo='" . $this->m_sysusr_photo . "'";
        if ($this->m_sysusr_photo != '')
            $strSql .=$m_sysusr_photo;

        if ($this->m_usrlogin_id != '' || $this->m_sysusr_pwd != '') {
            $hash = md5(strtolower($this->m_usrlogin_id) . '' . $this->m_sysusr_pwd);
            $strSql .= ",auth='" . $hash . "'";
        }

        $strSql .=" WHERE UserID=" . $this->m_npkId;     
        $rsSql = mysql_query($strSql) or die("Error 'EditUser' Query: $strSql");
        if (mysql_affected_rows())
            return $rsSql;
        else
            return FALSE;
    }

    function DeleteUser() {
        $strSql = "DELETE FROM  sysuser_tab WHERE UserID=" . $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("Error DeleteUser");
        if (mysql_affected_rows())
            return TRUE;
        else
            return FALSE;
    }

    function GetAllUser() {
        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName) AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
            FROM sysuser_tab
            LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
            LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
            LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
            INNER JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
            INNER JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllSubAdminUser() {
        $strSql = "SELECT * from sysuser_tab WHERE sysusr_type = 'UT-006'";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllUserprov2() {
        $strSql = "SELECT sysuser_tab.UserID,sysuser_tab.sysusr_type, sysuser_tab.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName) AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
            FROM sysuser_tab
            LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
            LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
            LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
            INNER JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
            INNER JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id where Province.PkLocID='" . $this->provid . "' AND sysuser_tab.sysusr_type='UT-002'  GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllUserprov() {
        $strSql = "SELECT
            sysuser_tab.UserID,
            sysuser_tab.stkid,
            stakeholder.stkname,
            District.LocName as Districts,
            Province.LocName as Provinces,
            sysuser_tab.whrec_id,
            tbl_warehouse.wh_name,
            tbl_warehouse.wh_id,
            sysuser_tab.usrlogin_id
            FROM sysuser_tab
            Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
            Left Join tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
            Inner Join tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
            Inner Join tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetUserByprovtitle() {
        $strSql = "SELECT
                    sysuser_tab.UserID,
                    sysuser_tab.stkid,
                    stakeholder.stkname,
                    sysuser_tab.whrec_id,
                    tbl_warehouse.wh_name,
                    district.PkLocID AS dist_id,
                    district.LocName AS district,
                    province.PkLocID AS prov_id,
                    province.LocName AS province,
                    tbl_warehouse.wh_id,
                    sysuser_tab.usrlogin_id
                    FROM
                    sysuser_tab
                    Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                    Left Join tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
                    Left Join tbl_locations AS district ON sysuser_tab.locid = district.PkLocID
                    Left Join tbl_locations AS province ON district.ParentID = province.PkLocID
                    ORDER BY province.PkLocID";
        $rsSql = mysql_query($strSql) or die("Error UserByprovtitle");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetUserByid() {
        $strSql = "SELECT
                    sysuser_tab.UserID,
                    sysuser_tab.stkid,
                    stakeholder.stkname,
                    sysuser_tab.whrec_id,
                    tbl_warehouse.wh_name,
                    district.PkLocID AS dist_id,
                    district.LocName AS district,
                    province.PkLocID AS prov_id,
                    province.LocName AS province,
                    tbl_warehouse.wh_id,
                    sysuser_tab.usrlogin_id
                    FROM
                    sysuser_tab
                    Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                    Left Join tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
                    Left Join tbl_locations AS district ON tbl_warehouse.locid = district.PkLocID
                    Left Join tbl_locations AS province ON district.ParentID = province.PkLocID
                    WHERE sysuser_tab.usrlogin_id='" . $this->m_usrlogin_id . "'";
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("Error GetUserByid");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetUserByUserID() {

        $strSql = "SELECT
                    sysuser_tab.UserID,
                    sysuser_tab.sysusr_pwd,
                    sysuser_tab.stkid,
                    sysuser_tab.sysusr_name,
                    sysuser_tab.sysusr_email,
                    sysuser_tab.sysusr_ph,
                    sysuser_tab.sysusr_cell,
                    sysuser_tab.sysusr_addr,
                    sysuser_tab.sysusr_dept,
                    sysuser_tab.sysusr_deg,
                    sysuser_tab.sysusr_type,
                    wh_user.sysusrrec_id,
                    GROUP_CONCAT(DISTINCT wh_user.wh_id) AS wh_ids,
                    stakeholder.stkname,
                    GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name,
                    GROUP_CONCAT(DISTINCT district.PkLocID) AS dist_id,
                    GROUP_CONCAT(DISTINCT district.LocName) AS district,
                    province.PkLocID AS prov_id,
                    province.LocName AS province,
                    tbl_warehouse.wh_id,
                    sysuser_tab.usrlogin_id
                    FROM
                    sysuser_tab
                    Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                    LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id 
                    LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
                    Left Join tbl_locations AS district ON district.PkLocID=tbl_warehouse.locid
                    Left Join tbl_locations AS province ON province.PkLocID=tbl_warehouse.prov_id
                    WHERE sysuser_tab.UserID='" . $this->m_npkId . "'";
        /* print $strSql;
          exit; */

        $rsSql = mysql_query($strSql) or die("Error GetUserByUserID");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAdminByAdminID() {


        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.sysusr_name,sysuser_tab.sysusr_name,sysuser_tab.sysusr_pwd,sysuser_tab.sysusr_ph,sysuser_tab.sysusr_cell, sysuser_tab.stkid,stakeholder.stkname,sysuser_tab.province, sysuser_tab.usrlogin_id,sysuser_tab.sysusr_email,sysuser_tab.sysusr_addr
                FROM sysuser_tab
                LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                where sysuser_tab.UserID='" . $this->m_npkId . "' AND (sysuser_tab.sysusr_type='UT-001' OR sysuser_tab.sysusr_type='5' ) GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die("Error GetAdminByAdminID");
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetMaxUserId() {
        $strSql = "SELECT IFNULL(max(UserID),0) as MaxID from sysuser_tab";
        //print $strSql; 
        //exit;
        $rsSql = mysql_query($strSql) or die("GetMaxUserId");
        if ($rsSql != FALSE && mysql_num_rows($rsSql) > 0) {
            while ($row = mysql_fetch_array($rsSql)) {
                $MaxID = $row['MaxID'];
            }
            return $MaxID;
        } else
            return 0;
    }

    function GetAllUser1() {
        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName) AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
                FROM sysuser_tab
                LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
                LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
                INNER JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
                INNER JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id where sysuser_tab.sysusr_type='UT-002' AND Province.PkLocID='" . $this->m_provid . "' AND sysuser_tab.stkid='" . $this->m_stkid . "' GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllUser2() {

        $stk = implode(",", $user_stk);
        $prov = implode(",", $user_prov);

        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName) AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
                FROM sysuser_tab
                LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
                LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
                INNER JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
                INNER JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id where sysuser_tab.sysusr_type='UT-002' AND Province.PkLocID IN('" . $prov . "') AND sysuser_tab.stkid IN ('" . $stk . "') GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllUserAdministrator() {
        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.sysusr_name,sysuser_tab.sysusr_name, sysuser_tab.stkid,stakeholder.stkname,sysuser_tab.province, Province.LocName AS Provinces, sysuser_tab.usrlogin_id,sysuser_tab.sysusr_email
                FROM sysuser_tab
                LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
                LEFT JOIN tbl_locations AS Province ON Province.PkLocID = sysuser_tab.province 
                where sysuser_tab.sysusr_type='UT-001' GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetAllprovAdministrator() {
        $strSql = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.sysusr_name,sysuser_tab.sysusr_name, sysuser_tab.stkid,stakeholder.stkname,sysuser_tab.province, Province.LocName AS Provinces, sysuser_tab.usrlogin_id,sysuser_tab.sysusr_email
            FROM sysuser_tab
            LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
            LEFT JOIN tbl_locations AS Province ON Province.PkLocID = sysuser_tab.province 
            where (sysuser_tab.sysusr_type='UT-001' AND Province.PkLocID='" . $this->provid . "' GROUP BY sysuser_tab.UserID";

        $rsSql = mysql_query($strSql) or die(mysql_error());
        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

    function GetSuperAdminbyID() {
        $m_npkId = $this->m_npkId;
        $strSql = "Select usrlogin_id,sysusr_name,sysusr_email,sysusr_ph,sysusr_cell,sysusr_addr from sysuser_tab where UserID='" . $m_npkId . "'";
        $rsSql = mysql_query($strSql) or die(mysql_error());

        if (mysql_num_rows($rsSql) > 0)
            return $rsSql;
        else
            return FALSE;
    }

}

?>
