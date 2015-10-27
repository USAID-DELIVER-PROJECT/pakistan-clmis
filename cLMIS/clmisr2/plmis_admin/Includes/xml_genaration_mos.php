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


function get($var){			  
	$StakeHolderName = mysql_fetch_row(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$var."' "));
	return $StakeHolderName[0];
}

//XML write function
function writeXML($xmlfile)
{
$xmlfile_path= GRID_XML_PATH."/".$xmlfile;
$query_xmlw = "SELECT m.row_id, i.itm_name , dl.lvl_name, m.longterm, m.sclstart, m.sclsend, m.colorcode FROM mosscale_tab m JOIN tbl_dist_levels dl ON m.lvl_id=dl.lvl_id JOIN itminfo_tab i ON m.itmrec_id=i.itmrec_id ";
$result_xmlw = mysql_query($query_xmlw);

$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlstore .="<rows>\n";
$counter = 0;

while($row_xmlw = mysql_fetch_array($result_xmlw)) {

	$Stakeholder = mysql_fetch_row(mysql_query("Select stkid FROM mosscale_tab WHERE row_id='".$row_xmlw['row_id']."'"));
	$StakeHolderName = 	get($Stakeholder[0]);


	$temp = "\"$row_xmlw[row_id]\"";
	$xmlstore .="\t<row id=\"$counter\">\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['itm_name']."</cell>\n";
	$xmlstore .="\t\t<cell><![CDATA[ ".$StakeHolderName."]]></cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['lvl_name']."</cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['longterm']."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['sclstart'],1)."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['sclsend'],1)."</cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['colorcode']."</cell>\n";
	
	//$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";
	$xmlstore .="\t\t<cell type=\"img\">dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
	$xmlstore .="\t\t<cell type=\"img\">dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";
	//$xmlstore .="\t\t<cell>Delete^javascript:delFunction($temp);^_self</cell>\n";
	$xmlstore .="\t</row>\n";
	$counter++;
}

$xmlstore .="</rows>\n";

$handle = fopen($xmlfile_path, 'w');

fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('mos.xml');
?>