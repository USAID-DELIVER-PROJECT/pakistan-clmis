<?php
include_once("../../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../../plmis_inc/common/FunctionLib.php");    // Include Global Function File
include('../auth.php');

$ID =  $_REQUEST['ID'];
$YYYY= $_REQUEST['YYYY'];
$MM= $_REQUEST['MM'];

$query="SELECT
tbl_wh_data.w_id as A,
tbl_wh_data.report_month  as B,
tbl_wh_data.report_year  as C,
tbl_wh_data.item_id  as D,
tbl_wh_data.wh_id  as E,
tbl_wh_data.wh_obl_a  as F,
tbl_wh_data.wh_obl_c  as G,
tbl_wh_data.wh_received  as H,
tbl_wh_data.wh_issue_up  as I,
tbl_wh_data.wh_cbl_c as J,
tbl_wh_data.mos   as K,
tbl_wh_data.wh_cbl_a  as L,
tbl_wh_data.wh_adja  as M,
tbl_wh_data.wh_adjb  as N,
tbl_wh_data.fld_obl_a  as O,
tbl_wh_data.fld_obl_c  as P,
tbl_wh_data.fld_recieved  as Q,
tbl_wh_data.amc  as R,
tbl_wh_data.fld_issue_up  as S,
tbl_wh_data.fld_cbl_c  as T,
tbl_wh_data.fld_cbl_a  as U,
tbl_wh_data.fld_mos  as V,
tbl_wh_data.fld_adja  as W,
tbl_wh_data.fld_adjb  as X,
tbl_wh_data.wh_entry  as Y,
tbl_wh_data.fld_entry  as Z
FROM
tbl_wh_data";
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