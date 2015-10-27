<?php

class clsUserProvinces {

    var $m_npkId;
    var $m_nuserId;
    var $m_nprovId;

    function GetProvByUserId() {
        $array = array();
        $strSql = "SELECT * FROM user_prov WHERE user_id = ". $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("Error GetProvByUserId");
        if (mysql_num_rows($rsSql) > 0){
            while($row = mysql_fetch_array($rsSql)) {
                $array[] = $row['prov_id'];
            }
            return $array;
        } else {
            return FALSE;
        }            
    }

}

?>
