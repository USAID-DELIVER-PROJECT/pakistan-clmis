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
//define('ROOT_PATH', "/home/lmispcgo/public_html/plmis_src/operations/xml/");


function get($var){			  
	$StakeHolderName = mysql_fetch_row(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$var."' "));
	return $StakeHolderName[0];
}

//XML write function
function writeXML($xmlfile)
{
$xmlfile_path= 'xml/'.$xmlfile;

include("Includes/stkpro.php");
$query_xmlw = "SELECT tbl_waiting_data.w_id,
			tbl_waiting_data.report_month, 
			tbl_waiting_data.report_year, 
			itminfo_tab.itm_name, 
			tbl_warehouse.wh_name, 
			tbl_waiting_data.wh_obl_a,  
			tbl_waiting_data.wh_received, 
			tbl_waiting_data.wh_issue_up, 
			tbl_waiting_data.wh_cbl_a, 
			tbl_waiting_data.fld_obl_a, 
			tbl_waiting_data.fld_recieved, 
			tbl_waiting_data.fld_issue_up, 
			tbl_waiting_data.fld_cbl_c, 
			tbl_waiting_data.fld_cbl_a  
			FROM tbl_waiting_data JOIN itminfo_tab ON tbl_waiting_data.item_id=itminfo_tab.itmrec_id JOIN
			tbl_warehouse ON tbl_waiting_data.wh_id=tbl_warehouse.wh_id WHERE tbl_waiting_data.wh_id IN (SELECT
tbl_warehouse.wh_id
FROM
tbl_warehouse WHERE tbl_warehouse.prov_id=".$provinceidd.")";

			   


$result_xmlw = mysql_query($query_xmlw);
$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$xmlstore .="<rows>\n";
$counter = 0;

while($row_xmlw = mysql_fetch_array($result_xmlw)) {
	
		if ($row_xmlw['report_month'] == '1'){
		$month = 'January';	
		}
		else if ($row_xmlw['report_month'] == '2'){
		$month = 'February';	
		}
		else if ($row_xmlw['report_month'] == '3'){
		$month = 'March';	
		}
		else if ($row_xmlw['report_month'] == '4'){
		$month = 'April';	
		}
		else if ($row_xmlw['report_month'] == '5'){
		$month = 'May';	
		}
		else if ($row_xmlw['report_month'] == '6'){
		$month = 'June';	
		}
		else if ($row_xmlw['report_month'] == '7'){
		$month = 'July';	
		}
		else if ($row_xmlw['report_month'] == '8'){
		$month = 'August';	
		}
		else if ($row_xmlw['report_month'] == '9'){
		$month = 'September';	
		}
		else if ($row_xmlw['report_month'] == '10'){
		$month = 'October';	
		}
		else if ($row_xmlw['report_month'] == '11'){
		$month = 'November';	
		}
		else if ($row_xmlw['report_month'] == '12'){
		$month = 'December';	
		}
		
	$temp = "\"$row_xmlw[w_id]\"";
	$xmlstore .="\t<row id=\"$counter\">\n";
	$xmlstore .="\t\t<cell>".$month."</cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['report_year']."</cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['itm_name']."</cell>\n";
	$xmlstore .="\t\t<cell>".$row_xmlw['wh_name']."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_obl_a'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_received'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_issue_up'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['wh_cbl_a'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['fld_obl_a'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['fld_recieved'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['fld_issue_up'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['fld_cbl_c'])."</cell>\n";
	$xmlstore .="\t\t<cell>".number_format($row_xmlw['fld_cbl_a'])."</cell>\n";
	
	$xmlstore .="\t\t<cell><![CDATA[<input type='checkbox' name='chkbox[]' value=$temp onclick='CheckCheckAll(document.trackunread);'>]]>^_self</cell>\n";
	//$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";
	//$xmlstore .="\t\t<cell type=\"img\">dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
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
writeXML('waitingdata.xml');
?>