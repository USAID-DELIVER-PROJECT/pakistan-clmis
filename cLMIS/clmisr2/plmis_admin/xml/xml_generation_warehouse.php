<?php

include("Includes/AllClasses.php");

//XML write function
function writeXML($xmlfile)
{

	
	$sql=mysql_query("SELECT
	sysuser_tab.UserID,
	sysuser_tab.stkid,
	sysuser_tab.province,
	stakeholder.stkname AS stkname,
	province.LocName AS provincename
	FROM
	sysuser_tab
	Left Join stakeholder ON sysuser_tab.stkid = stakeholder.stkid
	Left Join tbl_locations AS province ON sysuser_tab.province = province.PkLocID
	WHERE sysuser_tab.UserID='".$_SESSION['userid']."'");
	
	$sql_row=mysql_fetch_array($sql);
	$stakeholder=$sql_row['stkid'];
	$provinceidd=$sql_row['province'];
	$stkname=$sql_row['stkname'];
	$provincename=$sql_row['provincename'];
	
	$xmlfile_path= 'xml/'.$xmlfile;
	if($provinceidd=='-1' && $stakeholder=='-1'){
		
		$objuser1 = "SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.stkofficeid,
						stakeholder.stkname,
						tbl_warehouse.stkid,
						district.PkLocID AS dist_id,
						district.LocName AS district,
						province.PkLocID AS prov_id,
						province.LocName AS province,
						office.stkname AS officeName,
						tbl_warehouse.is_allowed_im
					FROM
						tbl_warehouse
						INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
						INNER JOIN stakeholder AS office ON tbl_warehouse.stkofficeid = office.stkid
						INNER JOIN tbl_locations AS district ON tbl_warehouse.dist_id = district.PkLocID
						INNER JOIN tbl_locations AS province ON tbl_warehouse.prov_id = province.PkLocID
					ORDER BY
						province,
						district,
						officeName";
	}
	else{
		
	
	
	$objuser1 = "SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.stkofficeid,
						stakeholder.stkname,
						tbl_warehouse.stkid,
						district.PkLocID AS dist_id,
						district.LocName AS district,
						province.PkLocID AS prov_id,
						province.LocName AS province,
						office.stkname AS officeName,
						tbl_warehouse.is_allowed_im
					FROM
						tbl_warehouse
						INNER JOIN stakeholder ON tbl_warehouse.stkid = stakeholder.stkid
						INNER JOIN stakeholder AS office ON tbl_warehouse.stkofficeid = office.stkid
						INNER JOIN tbl_locations AS district ON tbl_warehouse.dist_id = district.PkLocID
						INNER JOIN tbl_locations AS province ON tbl_warehouse.prov_id = province.PkLocID
					WHERE 
						tbl_warehouse.stkid='".$stakeholder."' 
					AND province.PkLocID='".$provinceidd."'
					ORDER BY
						province,
						district,
						officeName";
					}
	
	$result_xmlw = mysql_query($objuser1);
	

	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	$checked="";
	while($row_xmlw = mysql_fetch_assoc($result_xmlw)) {
		if(($row_xmlw['is_allowed_im'])==1)
		{ 
			$checked="checked=checked";
		}
		else
		{
			$checked = '';
		}
		$inputCheckbox='<input type="checkbox" name="im_allowed_'.$row_xmlw['wh_id'].'_'.$row_xmlw['is_allowed_im'].'"  '.$checked.' onclick="javascript:imAllowedFunction('.$row_xmlw['wh_id'].', this.name)"/> ';
		$temp = "\"$row_xmlw[wh_id]\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['stkname']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['officeName']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['province']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$row_xmlw['district']."]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[".$inputCheckbox.$row_xmlw['wh_name']."]]></cell>\n";
		
		/*$xmlstore .="\t\t<cell>Edit^javascript:editFunction($temp);^_self</cell>\n";*/
		
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/edit.gif^javascript:editFunction($temp)^_self</cell>\n";
		$xmlstore .="\t\t<cell type=\"img\">../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^".SITE_URL."plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/Delete.gif^javascript:delFunction($temp)^_self</cell>\n";
		$xmlstore .="\t\t<cell>Delete^javascript:delFunction($temp);^_self</cell>\n";
		$xmlstore .="\t</row>\n";
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	
	$handle = fopen($xmlfile_path, 'w');
	fwrite($handle, $xmlstore);
}

//Put XML file name and mysql table name simultaniously
writeXML('warehouse.xml');
?>