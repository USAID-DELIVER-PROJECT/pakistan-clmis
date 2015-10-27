<?php

//exit("You are not allowed to access this file");
include("Includes/AllClasses.php");


$strSql1 = "SELECT PkLocID from tbl_locations where ParentID=3";

$rsSql1 = mysql_query($strSql1);

/*
while ($result1 = mysql_fetch_object($rsSql1)) {



    $strSql2 = "SELECT * from kpk_data_import";

    $rsSql2 = mysql_query($strSql2);
    while ($result2 = mysql_fetch_object($rsSql2)) {
        $strSql3 = "INSERT INTO tbl_warehouse (wh_name,dist_id,prov_id,stkid,locid,stkofficeid,is_allowed_im,hf_type_id)"
                . " Values ('" . $result2->warehouse_name . "','" . $result1->PkLocID . "',3,1,'" . $result1->PkLocID . "',96,0,'" . $result2->hf_type . "')";
        mysql_query($strSql3);
    }
}
 * /
 */


while ($result1 = mysql_fetch_object($rsSql1)) {
     $strSql20 = "SELECT
sysuser_tab.UserID
FROM
tbl_warehouse
INNER JOIN sysuser_tab ON tbl_warehouse.wh_id = sysuser_tab.whrec_id
WHERE
tbl_warehouse.prov_id = 3 AND
tbl_warehouse.dist_id = $result1->PkLocID AND
tbl_warehouse.stkofficeid = 17 AND
tbl_warehouse.stkid = 1";
  
    $rsSql20 = mysql_query($strSql20);
    while ($result20 = mysql_fetch_object($rsSql20)) {

        $strSql21 = "SELECT
tbl_warehouse.wh_id
FROM
tbl_warehouse
WHERE
tbl_warehouse.wh_id > 10331 AND
tbl_warehouse.dist_id = $result1->PkLocID";
        $rsSql21 = mysql_query($strSql21);

        while ($result21 = mysql_fetch_object($rsSql21)) {
            $strSql3 = "INSERT INTO wh_user (sysusrrec_id,wh_id)"
                    . " Values ('" . $result20->UserID . "','" . $result21->wh_id . "')";
            mysql_query($strSql3);
        }
    }
} 
 
?>
