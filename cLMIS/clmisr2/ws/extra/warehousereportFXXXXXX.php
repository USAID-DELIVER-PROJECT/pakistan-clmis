<?php
include_once("../DBCon.php");          // Include Database Connection File
include('../auth.php');

$ID =  $_REQUEST['ID'];
$YYYY= $_REQUEST['YYYY'];
$MM= $_REQUEST['MM'];

$query="SELECT
itminfo_tab.itm_id,
tbl_wh_data.fld_obl_a  as obl_a,
tbl_wh_data.fld_obl_c  as obl_c,
tbl_wh_data.fld_recieved  as recieved,
tbl_wh_data.fld_issue_up  as issue_up,
tbl_wh_data.fld_adja  as adja,
tbl_wh_data.fld_adjb  as adjb,
tbl_wh_data.fld_cbl_a  as cbl_a,
tbl_wh_data.fld_cbl_c  as cbl_c,
tbl_wh_data.w_id as Uploaded
FROM
tbl_wh_data Inner Join itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id";
//print $ID.$YYYY.$MM;
if(!empty($ID) && !empty($YYYY) && !empty($MM))
{
	$query=$query." WHERE tbl_wh_data.wh_id ='$ID' and tbl_wh_data.report_year='$YYYY' and tbl_wh_data.report_month='$MM'";
	
	//print $query;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
		$rows[] = $r;
	}
	print json_encode($rows);
}
else
print "-1";

//http://localhost/lmis/ws/warehousereport.php?ID=3743&YYYY=2011&MM=5
?>