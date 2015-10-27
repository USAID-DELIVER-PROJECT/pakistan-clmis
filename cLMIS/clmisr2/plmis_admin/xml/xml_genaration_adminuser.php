<?php

include("../Includes/AllClasses.php");

//XML write function
function writeXML($xmlfile)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	
	$objuser1 = "SELECT sysuser_tab.UserID, sysuser_tab.sysusr_type, sysuser_tab.sysusr_name,sysuser_tab.sysusr_name, sysuser_tab.stkid,stakeholder.stkname,sysuser_tab.province, Province.LocName AS Provinces, sysuser_tab.usrlogin_id,sysuser_tab.sysusr_email
FROM sysuser_tab
LEFT JOIN stakeholder ON sysuser_tab.stkid = stakeholder.stkid
LEFT JOIN tbl_locations AS Province ON Province.PkLocID = sysuser_tab.province 
where (sysuser_tab.sysusr_type='UT-001' OR sysuser_tab.sysusr_type='5') GROUP BY sysuser_tab.UserID";
	
	$result_xmlw = mysql_query($objuser1);
	

	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$temp = "\"$row_xmlw[UserID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['Provinces']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['usrlogin_id']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['sysusr_name']."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['sysusr_email']."</cell>\n";
		
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

//Put XML file name and mysql table name simultaniously
writeXML('adminuser.xml');
?>