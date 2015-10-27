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

    function GetProvInfoByUserId() {
        $array = array();
        $strSql = "SELECT * FROM user_prov WHERE user_id = ". $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("Error GetProvByUserId");
        if (mysql_num_rows($rsSql) > 0){
            while($row = mysql_fetch_array($rsSql)) {
                $array[] = array(
                    'id' => $row['prov_id'],
                    'name' => $row['prov_id']
                    );
            }
            return $array;
        } else {
            return FALSE;
        }            
    }

    function insert() {

        $strSql = "INSERT INTO  user_prov (user_id, prov_id) VALUES (". $this->m_nuserId.", ". $this->m_nprovId.")";
        $rsSql = mysql_query($strSql) or die("Error insert");
        if ($rsSql){
            return TRUE;
        } else {
            return FALSE;
        }            
    }

    function delete(){
        $strSql = "DELETE FROM user_prov WHERE user_id = ".$this->m_nuserId;
        $rsSql = mysql_query($strSql) or die("Error delete");
        if ($rsSql){
            return TRUE;
        } else {
            return FALSE;
        }   
    }

}

?>
