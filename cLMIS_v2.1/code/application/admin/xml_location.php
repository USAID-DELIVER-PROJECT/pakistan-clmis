<?php

/**
 * xml Location
 * @package Admin
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Query for locations
$qry = "SELECT
			tbl_locations.PkLocID,
			tbl_locations.LocName,
			tbl_dist_levels.lvl_name,
			tbl_locationtype.LoctypeName,
			Parent.LocName AS Province
		FROM
			tbl_locations
		INNER JOIN tbl_locationtype ON tbl_locations.LocType = tbl_locationtype.LoctypeID
		INNER JOIN tbl_dist_levels ON tbl_locations.LocLvl = tbl_dist_levels.lvl_id
		INNER JOIN tbl_locations AS Parent ON tbl_locations.ParentID = Parent.PkLocID
		WHERE
			tbl_locations.LocLvl IN (3, 4)
		ORDER BY
			Parent.PkLocID ASC,
			tbl_locations.LocName ASC";
$qryRes = mysql_query($qry);
//xml for grid
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";
$counter = 1;
//populate grid
while ($row = mysql_fetch_array($qryRes)) {
    $temp = "\"$row[PkLocID]\"";
    $xmlstore .="<row>";
    $xmlstore .="<cell>" . $counter++ . "</cell>";
    //Province
    $xmlstore .="<cell>" . $row['Province'] . "</cell>";
    //lvl_name
    $xmlstore .="<cell>" . $row['lvl_name'] . "</cell>";
    //LoctypeName
    $xmlstore .="<cell>" . $row['LoctypeName'] . "</cell>";
    //LocName
    $xmlstore .="<cell>" . $row['LocName'] . "</cell>";
    $xmlstore .="<cell type=\"img\">" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>";
    $xmlstore .="<cell type=\"img\">" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^" . PUBLIC_URL . "dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>";
    $xmlstore .="</row>";
}
//end xml
$xmlstore .="</rows>";
