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
	$query_xmlw = "SELECT id, title, heading, content,Stkid,province_id,logo from tbl_cms";
	$result_xmlw = mysql_query($query_xmlw);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
	
		//retrieving stakeholder name=
		$strSql = "SELECT stkname FROM stakeholder where stkid='".$row_xmlw['Stkid']."'";
		$rsSql = mysql_query($strSql) or die($strSql.mysql_error());
		$RowEditStk = mysql_fetch_object($rsSql);
	    $stkname=$RowEditStk->stkname;
		
		//retrieving province
		
		$strpro = "SELECT LocName FROM tbl_locations where LocLvl='2' AND PkLocID='".$row_xmlw['province_id']."'";
		$rspro = mysql_query($strpro) or die($strpro.mysql_error());
		$RowEditpro = mysql_fetch_object($rspro);
	    $proname=$RowEditpro->LocName;
		
		$temp = "\"$row_xmlw[id]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['title']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['heading']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$stkname."]]></cell>\n";
		$xmlstore .="\t\t<cell>".$proname."</cell>\n";
		$xmlstore .="\t\t<cell>".$row_xmlw['content']."</cell>\n";
		
		if(!empty($row_xmlw[logo])){
		$xmlstore .="\t\t<cell><![CDATA[<a href=\"../plmis_admin/images/$row_xmlw[logo]\" class=\"lightbox\">Show Logo</a>]]>^_self</cell>\n";
		}
		else{
		$xmlstore .="\t\t<cell></cell>\n";
		}
		
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
writeXML('content.xml');
?>