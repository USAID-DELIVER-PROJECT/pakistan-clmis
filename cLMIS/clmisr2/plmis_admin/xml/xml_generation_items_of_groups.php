<?php


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
		
		$csv='';
		
		$temp = "\"$row_xmlw[PKItemGroupID]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell>".$row_xmlw['ItemGroupName']."</cell>\n";
		
		$objMIs = mysql_query("SELECT
				itemgroups.PKItemGroupID,
				itemgroups.ItemGroupName,
				itemsofgroups.ItemID,
				itminfo_tab.itm_name
				FROM
				itemgroups
				Left Join itemsofgroups ON itemgroups.PKItemGroupID = itemsofgroups.GroupID
				Left Join itminfo_tab ON itemsofgroups.ItemID = itminfo_tab.itm_id
				Where itemgroups.PKItemGroupID=".$row_xmlw['PKItemGroupID']);
		if($objMIs!=FALSE && mysql_num_rows($objMIs)>0)
		{
			while($Rowranks = mysql_fetch_array($objMIs))
			{
				$csv.=$Rowranks['itm_name'].",";
			}		
		}
		if(strlen($csv)>0)
		{
			$csv=substr($csv,0,strlen($csv)-1); 
		}
		$xmlstore .="\t\t<cell>".$csv."</cell>\n";
		
		//$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
		//$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";
		//$xmlstore .="\t\t<cell>Delete^javascript:delFunction($temp);^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	
	$handle = fopen($xmlfile_path, 'w');
	fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('items_of_groups.xml');
?>