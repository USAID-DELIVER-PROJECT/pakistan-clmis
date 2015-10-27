<?php
ob_start();
function get($var){			  
	$StakeHolderName = mysql_fetch_row(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$var."' "));
	return $StakeHolderName[0];
}
$where = "";
if(isset($_POST['report_year']) && $_POST['report_year'] != ""){
	$where .="tbl_wh_data.report_year='".$_POST['report_year']."' ";
	$_SESSION['filterParam']['year'] = $_POST['report_year'];
}if(isset($_POST['report_month']) && $_POST['report_month'] != ""){
	$where .=" AND tbl_wh_data.report_month='".$_POST['report_month']."' ";
	$_SESSION['filterParam']['month'] = $_POST['report_month'];
}
if(isset($_POST['districts']) && $_POST['districts'] != ""){
	$where .=" AND tbl_wh_data.wh_id='".$_POST['districts']."'";
	$_SESSION['filterParam']['wh'] = $_POST['districts'];
}
// Check Level of the warehouse
$lvlQry = "SELECT
			stakeholder.lvl
		FROM
			tbl_warehouse
		INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
		WHERE
			tbl_warehouse.wh_id = ".$_POST['districts']." ";
$lvlQryRes = mysql_fetch_array(mysql_query($lvlQry));
$lvl = $lvlQryRes['lvl'];

if ( $lvl <= 4 )
{
	$query_xmlw = "SELECT  tbl_wh_data.w_id,
						itminfo_tab.itm_name,
						tbl_wh_data.wh_obl_a,
						tbl_wh_data.wh_received,
						tbl_wh_data.wh_issue_up,
						tbl_wh_data.wh_adja,
						tbl_wh_data.wh_adjb,
						tbl_wh_data.wh_cbl_a,
						tbl_wh_data.last_update
					FROM  tbl_wh_data 
					LEFT JOIN itminfo_tab ON tbl_wh_data.item_id=itminfo_tab.itmrec_id 
					LEFT JOIN tbl_warehouse ON tbl_wh_data.wh_id=tbl_warehouse.wh_id 
					WHERE $where 
					AND itminfo_tab.itm_category = 1
					ORDER BY frmindex";
}
else 
{
	$query_xmlw = "SELECT
						tbl_hf_data.pk_id AS w_id,
						itminfo_tab.itm_name,
						tbl_hf_data.opening_balance AS wh_obl_a,
						tbl_hf_data.received_balance AS wh_received,
						tbl_hf_data.issue_balance AS wh_issue_up,
						tbl_hf_data.adjustment_positive AS wh_adja,
						tbl_hf_data.adjustment_negative AS wh_adjb,
						tbl_hf_data.closing_balance AS wh_cbl_a,
						tbl_hf_data.last_update
					FROM
						tbl_hf_data
					INNER JOIN itminfo_tab ON tbl_hf_data.item_id = itminfo_tab.itm_id
					WHERE
						tbl_hf_data.warehouse_id = ".$_SESSION['filterParam']['wh']."
					AND YEAR (tbl_hf_data.reporting_date) = ".$_SESSION['filterParam']['year']."
					AND MONTH (tbl_hf_data.reporting_date) = ".$_SESSION['filterParam']['month']."
					AND itminfo_tab.itm_category = 1
					ORDER BY
						itminfo_tab.frmindex ASC";
}
	
$result_xmlw = mysql_query($query_xmlw);
$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";
$counter = 0;
$numOfRows = mysql_num_rows($result_xmlw);
$_SESSION['numOfRows'] = $numOfRows;
if($numOfRows>0){
	while($row_xmlw = mysql_fetch_array($result_xmlw)) {
		
		$lastModified = (is_null($row_xmlw['last_update'])) ? '' : date('d/m/Y h:i A', strtotime($row_xmlw['last_update']));
		
		$temp = "\"$row_xmlw[w_id]\"";
		$xmlstore .="<row>";
		$xmlstore .="<cell>".$row_xmlw['itm_name']."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_obl_a'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_received'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_issue_up'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_adja'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_adjb'])."</cell>";
		$xmlstore .="<cell>".number_format($row_xmlw['wh_cbl_a'])."</cell>";
		$xmlstore .="<cell>".$lastModified."</cell>";
		
		$xmlstore .="</row>";
		$counter++;
	}
}
$xmlstore .="</rows>";
?>