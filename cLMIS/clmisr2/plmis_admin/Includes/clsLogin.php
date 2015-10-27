<?php

class clsLogin {

    var $m_strPass = "";
    var $m_login = "";

    function Update() {
        $hash = md5(strtolower($_SESSION['user_name']) . '' . $this->m_strPass);
        $strSql = "UPDATE sysuser_tab SET sysusr_pwd='" . $this->m_strPass . "', auth='" . $hash . "' WHERE UserID='" . $this->m_login . "'";
        $rsSql = mysql_query($strSql) or die("Error " . $strSql);
        if (mysql_affected_rows())
            return $rsSql;
        else
            return FALSE;
    }

    function Login() {
        $this->m_strPass = base64_encode($this->m_strPass);
        $strSql = "SELECT
                    sysuser_tab.UserID,
                    sysuser_tab.sysusr_type,
                    sysuser_tab.sysusr_name,
                    sysuser_tab.sysusr_dept,
                    sysuser_tab.sysusr_email,
                    sysuser_tab.whrec_id,
                    sysuser_tab.sysgroup_id,
                    tbl_warehouse.stkid,
                    tbl_warehouse.stkofficeid,
                    stakeholder.lvl,
                    tbl_warehouse.prov_id,
                    tbl_warehouse.dist_id,
					stakeholder.stk_type_id
            	FROM
					sysuser_tab
				LEFT JOIN tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
				LEFT JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					usrlogin_id = '" . $this->m_login . "'
				AND sysusr_pwd = '" . $this->m_strPass . "'
				AND sysusr_status = 'Active'";

        $rsSql = mysql_query($strSql) or die("Error");
        $r = mysql_fetch_row($rsSql);
        /* echo print_r($r);//mysql_num_rows($rsSql);
          exit; */
        if (mysql_num_rows($rsSql) > 0)
            return $r;
        else
            return "";
    }

    function getOldPass() {
        $strSql = "select sysusr_pwd from sysuser_tab 
		where UserID='" . $this->m_login . "' and sysusr_status='Active'";
        //echo $strSql;
        //exit;
        $rsSql = mysql_query($strSql) or die("Error");
        $r = mysql_fetch_row($rsSql);
        //echo print_r($r);//mysql_num_rows($rsSql);
        //exit;
        if (mysql_num_rows($rsSql) > 0)
            return $r[0];
        else
            return "";
    }

}

?>