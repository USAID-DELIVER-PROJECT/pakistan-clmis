<?php

/**
 * xml User
* @package Admin
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Query for users
$qry = "SELECT
			user_stk.stk_id,
			user_prov.prov_id
		FROM
			user_stk
		JOIN user_prov ON user_stk.user_id = user_prov.user_id
		WHERE
			user_stk.user_id = " . $_SESSION['user_id'];
//query result
$qryRes = mysql_query($qry);
//stk
$arr['stk'] = array();
//prov
$arr['prov'] = array();
//getting results
while ($row = mysql_fetch_array($qryRes)) {
    if (!in_array($row['stk_id'], $arr['stk'])) {
        //stk
        $arr['stk'][] = $row['stk_id'];
    }
    if (!in_array($row['prov_id'], $arr['prov'])) {
        //prov
        $arr['prov'][] = $row['prov_id'];
    }
}
//Filters
$where = 'WHERE sysuser_tab.sysusr_type > 2';
$where .= (!empty($arr['stk'])) ? " AND tbl_warehouse.stkid IN (" . implode(',', $arr['stk']) . ")" : '';
$where .= (!empty($arr['prov'])) ? " AND Province.PkLocID IN (" . implode(',', $arr['prov']) . ")" : '';
//Query for users
$objuser1 = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, tbl_warehouse.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName SEPARATOR ', ') AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name SEPARATOR ', ') AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
FROM sysuser_tab
LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
LEFT JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
LEFT JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id
$where
GROUP BY
	sysuser_tab.UserID
ORDER BY
	stakeholder.stkorder ASC,
	Province.PkLocID ASC,
	District.LocName ASC";
//query result
$result_xmlw = mysql_query($objuser1);
//xml for grid
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";
//counter
$counter = 1;
//Populate xml
while ($row_xmlw = mysql_fetch_array($result_xmlw)) {
    $temp = "\"$row_xmlw[UserID]\"";
    $xmlstore .="<row>";
    $xmlstore .="<cell>" . $counter++ . "</cell>";
    //stkname
    $xmlstore .="<cell><![CDATA[" . $row_xmlw['stkname'] . "]]></cell>";
    //Provinces
    $xmlstore .="<cell><![CDATA[" . $row_xmlw['Provinces'] . "]]></cell>";
    //Districts
    $xmlstore .="<cell><![CDATA[" . $row_xmlw['Districts'] . "]]></cell>";
    //wh_name
    $xmlstore .="<cell><![CDATA[" . $row_xmlw['wh_name'] . "]]></cell>";
    //usrlogin_id
    $xmlstore .="<cell><![CDATA[" . $row_xmlw['usrlogin_id'] . "]]></cell>";
    $xmlstore .="<cell type=\"img\">" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>";
    if ($_SESSION['user_role'] == 1) {
        $xmlstore .="<cell type=\"img\">" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>";
    } else {
        $xmlstore .= "<cell type=\"str\"></cell>";
    }
    $xmlstore .="</row>";
}
//end xml
$xmlstore .="</rows>";
