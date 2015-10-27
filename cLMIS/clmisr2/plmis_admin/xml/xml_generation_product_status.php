<?php


//XML write function
function writeXML($xmlfile)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	$query_xmlw = "SELECT
					tbl_product_status.PKItemStatusID,
					tbl_product_status.ItemStatusName
					FROM
					tbl_product_status";
	$result_xmlw = mysql_query($query_xmlw);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$temp = "\"$row_xmlw[PKItemStatusID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell>".$row_xmlw['ItemStatusName']."</cell>\n";
		
		
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
writeXML('product_status.xml');
?>