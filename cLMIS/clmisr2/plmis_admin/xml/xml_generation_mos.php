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
	itminfo_tab.itm_name,stakeholder.stkname,
	tbl_dist_levels.lvl_name,
	mosscale_tab.shortterm,
	mosscale_tab.longterm,
	mosscale_tab.sclstart,
	mosscale_tab.sclsend,
	mosscale_tab.colorcode,mosscale_tab.row_id
	FROM
	mosscale_tab
	Inner Join stakeholder ON mosscale_tab.stkid = stakeholder.stkid
	Inner Join tbl_dist_levels ON mosscale_tab.lvl_id = tbl_dist_levels.lvl_id
	Inner Join itminfo_tab ON itminfo_tab.itmrec_id = mosscale_tab.itmrec_id";
	$result_xmlw = mysql_query($query_xmlw);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
	
		$colorcodebg=$row_xmlw['colorcode'];
		
		$temp = "\"$row_xmlw[row_id]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['itm_name']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['lvl_name']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['shortterm']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['longterm']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['sclstart']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['sclsend']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<a href=\"#\" style=\"text-decoration:none;color:$colorcodebg\">$colorcodebg</a>]]>^_self</cell>\n";
	/*	$xmlstore .="\t\t<cell>".$row_xmlw['colorcode']."</cell>\n";*/
	
		
		/*if(!empty($row_xmlw[logo])){
		$xmlstore .="\t\t<cell><![CDATA[<a href=\"../plmis_admin/images/$row_xmlw[logo]\" class=\"lightbox\">Show Logo</a>]]>^_self</cell>\n";
		}
		else{
		$xmlstore .="\t\t<cell></cell>\n";
		}*/
		
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
writeXML('mos.xml');
?>