<?php

include("Includes/AllClasses.php");

//XML write function
function writeXML($xmlfile)
{
	$objuser1 = "SELECT
		sysuser_tab.usrlogin_id,
		sysuser_tab.sysusr_name,
		getUserProvinces(sysuser_tab.UserID) AS provinces,
		getUserStakeholders(sysuser_tab.UserID) AS stakeholders,
		sysuser_tab.sysusr_email,
		sysuser_tab.sysusr_ph,
		sysuser_tab.UserID
		FROM
		sysuser_tab
		WHERE
		sysuser_tab.sysusr_type = 'UT-006'";
	
	$result_xmlw = mysql_query($objuser1);
	$xmlfile_path= 'xml/'.$xmlfile;	

	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$temp = "\"$row_xmlw[UserID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['sysusr_name']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['sysusr_ph']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['sysusr_email']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['provinces']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stakeholders']."]]></cell>\n";
		
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
writeXML('subadmins.xml');
?>