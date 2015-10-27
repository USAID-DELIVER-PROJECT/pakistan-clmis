<?php
/**
* writeXML : Converting mysql table into XML
*
* @author : S.M. Saidur Rahman ,
* Moderator, joomla_experts (http://tech.groups.yahoo.com/group/joomla_experts/)
Moderator, cakephpexperts (http://tech.groups.yahoo.com/group/cakephpexperts/)
* URL: http://ranawd.wordpress.com/
* @version : 1.0
* @date 2008-07-09
* Purpose : Write XML file and collect data from mysql table
*/

/*
@Steps:
#Create a table name "sampletable"
#Create a XML file name "sample.xml"
#Correct root path as define value
#Call this function and Enjoy!
*/

//Here is an example of mysql table

//Define XML file root path
//define('ROOT_PATH', "C:\\wamp\\www\\myweb\\paklmis_final\\plmis_src\\operations\\xml\\");



//XML write function
function writeXML($xmlfile)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	$query_xmlw = "SELECT
					itemgroups.PKItemGroupID,
					itemgroups.ItemGroupName
					FROM
					itemgroups";
	$result_xmlw = mysql_query($query_xmlw);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$temp = "\"$row_xmlw[PKItemGroupID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell>".$row_xmlw['ItemGroupName']."</cell>\n";
		
		
		//$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";
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
writeXML('items_groups.xml');
?>