<?php

//XML write function
function writeXML($xmlfile)
{
	$csv="";
	$xmlfile_path= 'xml/'.$xmlfile;
	$query_xmlw = "SELECT
						stakeholder.stkid,
						stakeholder.stkname,
						stakeholder.stkorder,
						stakeholder_type.stk_type_descr,
						IF(ifnull(parentstk.stkname,stakeholder.stkname) ='', parentstk.stkname, ifnull(parentstk.stkname,stakeholder.stkname) ) AS Parent,
						stakeholder.ParentID,
						stakeholder.stk_type_id,
						stakeholder.lvl,
						tbl_dist_levels.lvl_name,
						stakeholder.MainStakeholder
						FROM
						stakeholder
						Left Join stakeholder AS parentstk ON stakeholder.ParentID = parentstk.stkid
						Inner Join stakeholder_type ON stakeholder.stk_type_id = stakeholder_type.stk_type_id
						Left Join tbl_dist_levels ON stakeholder.lvl = tbl_dist_levels.lvl_id
						where stakeholder.ParentID is null
						ORDER BY stakeholder.stkid";
	$result_xmlw = mysql_query($query_xmlw);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		$csv='';
		
		$temp = "\"$row_xmlw[stkid]\"";
		$objMIs= mysql_query("SELECT
					stakeholder_item.stk_id,
					stakeholder_item.stkid,
					stakeholder.stkname,
					stakeholder_item.stk_item,
					itminfo_tab.itm_name,
					stakeholder_item.type
					FROM
					stakeholder_item
					Left Join stakeholder ON stakeholder.stkid = stakeholder_item.stkid
					Left Join itminfo_tab ON itminfo_tab.itm_id = stakeholder_item.stk_item
					WHERE stakeholder_item.stkid=".$temp." ORDER BY itminfo_tab.frmindex ASC");
					
		
			if($objMIs!=FALSE && mysql_num_rows($objMIs)>0)
		{
			while($Rowranks = mysql_fetch_object($objMIs))
			{
				if(strlen($Rowranks->itm_name)>0){
					$csv.=$Rowranks->itm_name.", ";
					//print $csv;
				}		
			}
		}
		if(strlen($csv)>0)
		{
			$csv=substr($csv,0,strlen($csv)-2); 
		}
			
	
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>\n";
		
		$xmlstore .="\t\t<cell>".$csv."</cell>\n";
		
		
		//$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
		/*$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";*/
		//$xmlstore .="\t\t<cell>Delete^javascript:delFunction($temp);^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	
	$handle = fopen($xmlfile_path, 'w');
	fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('stakeholder_items.xml');
?>