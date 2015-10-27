<?php
$test='false';
//XML write function
function writeXML($xmlfile)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	$qry =  "SELECT
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
				tbl_locations.LocLvl IN (3, 4)";
	$qryRes = mysql_query($qry);
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	while($row = mysql_fetch_array($qryRes))
	{
		$temp = "\"$row[PkLocID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell>".$row['LocName']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row['lvl_name']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row['LoctypeName']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row['Province']."</cell>\n";
		
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	
	$handle = fopen($xmlfile_path, 'w');
	fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('location.xml');
?>