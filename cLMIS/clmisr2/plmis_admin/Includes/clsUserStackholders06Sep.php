<?php

class clsUserStackholders {

    var $m_npkId;
    var $m_nuserId;
    var $m_nstkId;

    function GetStkByUserId() {
        $strSql = "SELECT * FROM user_stk WHERE user_id = ". $this->m_npkId;
        $rsSql = mysql_query($strSql) or die("Error GetStkByUserId");
        if (mysql_num_rows($rsSql) > 0) {
            while($row = mysql_fetch_array($rsSql)) {
                $array[] = $row['stk_id'];
            }
            return $array;
        }
        else {
            return FALSE;
        }           
    }

}

?>
