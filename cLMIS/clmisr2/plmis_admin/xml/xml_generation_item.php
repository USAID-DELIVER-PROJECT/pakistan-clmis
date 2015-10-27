<?php

include("Includes/AllClasses.php");

//XML write function
function writeXML($xmlfile)
{
	$xmlfile_path= 'xml/'.$xmlfile;
	
	$objitem="SELECT
					itminfo_tab.itmrec_id,
					itminfo_tab.itm_id,
					itminfo_tab.itm_name,
					itminfo_tab.itm_type,
					itminfo_tab.itm_category,
					itminfo_tab.qty_carton,
					itminfo_tab.field_color,
					itminfo_tab.itm_des,
					itminfo_tab.itm_status,
					itminfo_tab.frmindex,
					itminfo_tab.extra
					FROM
					itminfo_tab";
	
	$result_xmlw = mysql_query($objitem);
	

	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 0;
	
	while($Rowrsadditem = mysql_fetch_object($result_xmlw)) {
		
		//manage type 
		$id=$Rowrsadditem->itm_type;
		$itmtype1=mysql_query("SELECT
					tbl_product_type.PKItemTypeID,
					tbl_product_type.ItemTypeName
					FROM
					tbl_product_type
					WHERE tbl_product_type.PKItemTypeID=".$id);
					
		$rowitemtype=mysql_fetch_object($itmtype1);	
			
		//manage category
		$id_category=$Rowrsadditem->itm_category;
		$itemcategory1=mysql_query("SELECT
					tbl_product_category.PKItemCategoryID,
					tbl_product_category.ItemCategoryName
					FROM
					tbl_product_category
					WHERE tbl_product_category.PKItemCategoryID=".$id_category);
		$rowitemcategory=mysql_fetch_object($itemcategory1);
		
		  //manage status
		$id_status=$Rowrsadditem->itm_status;
		$itemstatus1=mysql_query("SELECT
					tbl_product_status.PKItemStatusID,
					tbl_product_status.ItemStatusName
					FROM
					tbl_product_status
					WHERE tbl_product_status.PKItemStatusID=".$id_status);
		$rowitemstatus=mysql_fetch_object($itemstatus1);	
		
		
		$temp = "\"$Rowrsadditem->itm_id\"";
		$xmlstore .="\t<row id=\"$counter\">\n";
		
		$xmlstore .="\t\t<cell>".$Rowrsadditem->itm_name."</cell>\n";
		$xmlstore .="\t\t<cell>".$rowitemtype->ItemTypeName."</cell>\n";
		$xmlstore .="\t\t<cell>".$rowitemcategory ->ItemCategoryName."</cell>\n";
		$xmlstore .="\t\t<cell>".$rowitemstatus ->ItemStatusName."</cell>\n";
		$xmlstore .="\t\t<cell>".$Rowrsadditem ->itm_des."</cell>\n";
		$xmlstore .="\t\t<cell>".$Rowrsadditem ->frmindex."</cell>\n";
		
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
writeXML('item.xml');
?>