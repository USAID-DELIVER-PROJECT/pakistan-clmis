<?php
include_once("DBCon.php");          // Include Database Connection File
include('auth.php');


function getProdName(){
$STK="1,2,7";
$STDATE="2013-06";
$EDDATE="2013-06";

if(isset($_REQUEST['STK']) && !empty($_REQUEST['STK']))
	{
	$STK=$_REQUEST['STK'];
	}


if(isset($_REQUEST['STDATE']) && !empty($_REQUEST['STDATE']))
	{
	$STDATE=$_REQUEST['STDATE'];
	}
	
	
if(isset($_REQUEST['EDDATE']) && !empty($_REQUEST['EDDATE']))
	{
	$EDDATE=$_REQUEST['EDDATE'];
	}	
	//$query = "SELECT itm_name FROM itminfo_tab";
	$query = "SELECT
				tbl_wh_data.report_month,
				tbl_wh_data.report_year,
				tbl_wh_data.wh_obl_a,
				tbl_wh_data.wh_received,
				tbl_wh_data.wh_issue_up,
				tbl_wh_data.wh_cbl_a,
				tbl_wh_data.wh_adjb,
				itminfo_tab.itm_name,
				districts.LocName AS district,
				province.LocName AS Province,
				tbl_wh_data.wh_adja,
				stakeholder.stkname
				FROM
				itminfo_tab
				INNER JOIN tbl_wh_data ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
				INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN tbl_locations AS districts ON tbl_warehouse.dist_id = districts.PkLocID
				INNER JOIN tbl_locations AS province ON tbl_warehouse.prov_id = province.PkLocID
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
				tbl_wh_data.RptDate BETWEEN '$STDATE-01' AND '$EDDATE-30' AND
				itminfo_tab.itm_id = 1 AND
				tbl_warehouse.stkid in ($STK)";
	$rs = mysql_query($query) or die(mysql_error());
	return $rs;
}

$sth=getProdName();
$rows = array();
while($r = mysql_fetch_assoc($sth)) {
	$rows[] = $r;
}
print json_encode($rows);
?>