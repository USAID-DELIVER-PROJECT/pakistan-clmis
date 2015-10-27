<?php
//XML write function
function writeXML($xmlfile, $stk, $prov)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	//print "[".$stakeholder.":".$provinceidd."]";
	
	if(($stakeholder==0 && $provinceidd!=0)||($stakeholder!=0 && $provinceidd==0)){
	$objuser1="SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.stkid, stakeholder.stkname, tbl_locations.LocName AS Provinces, sysuser_tab.usrlogin_id
FROM sysuser_tab
LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
LEFT JOIN tbl_locations ON sysuser_tab.province = tbl_locations.PkLocID
WHERE sysuser_tab.sysusr_type = 'UT-002'
AND sysuser_tab.province IN ('".$prov."')
AND sysuser_tab.stkid IN ('".$stk."')
GROUP BY sysuser_tab.UserID";
	} else {
	$objuser1 = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.stkid, stakeholder.stkname, GROUP_CONCAT(DISTINCT District.LocName) AS Districts, Province.LocName AS Provinces, wh_user.sysusrrec_id, wh_user.wh_id, GROUP_CONCAT(DISTINCT tbl_warehouse.wh_name) AS wh_name, tbl_warehouse.wh_id, sysuser_tab.usrlogin_id
FROM sysuser_tab
LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
INNER JOIN tbl_locations AS District ON District.PkLocID = tbl_warehouse.locid
INNER JOIN tbl_locations AS Province ON Province.PkLocID = tbl_warehouse.prov_id where sysuser_tab.sysusr_type='UT-002' AND Province.PkLocID IN ('".$prov."') AND sysuser_tab.stkid IN ('".$stk."') GROUP BY sysuser_tab.UserID";
   }
	
	$result_xmlw = mysql_query($objuser1);	

	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$temp = "\"$row_xmlw[UserID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['Provinces']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['Districts']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['wh_name']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['usrlogin_id']."]]></cell>\n";
		
		/*$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";*/
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";
		//$xmlstore .="\t\t<cell>Delete^javascript:delFunction($temp);^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	
	$handle = fopen($xmlfile_path, 'w');
	fwrite($handle, $xmlstore);
}
?>