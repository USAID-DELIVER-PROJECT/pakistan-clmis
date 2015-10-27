<?php

include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File

$query="SELECT *
FROM
tbl_wh_data";
//print $ID.$YYYY.$MM;
	$rs = mysql_query($query) or die(print mysql_error());
	$rows = array();
	while($r = mysql_fetch_assoc($rs))
	{
	
	if ($r['fld_adja']=="") $fld_adja="null";
	if ($r['fld_adjb']=="")	$fld_adjb="null";

	if ($r['fld_obl_a']=="") $fld_obl_a="null"; else $fld_obl_a=$r['fld_obl_a'];
	if ($r['fld_obl_c']=="")	$fld_obl_c="null"; else $fld_obl_c=$r['fld_obl_c'];

	if ($r['fld_recieved']=="") $fld_recieved="null"; else $fld_recieved=$r['fld_recieved'];
	if ($r['fld_issue_up']=="")	$fld_issue_up="null"; else $fld_issue_up=$r['fld_issue_up'];

	if ($r['fld_cbl_c']=="") $fld_cbl_c="null"; else $fld_cbl_c=$r['fld_cbl_c'];
	if ($r['fld_cbl_a']=="")	$fld_cbl_a="null"; else $fld_cbl_a=$r['fld_cbl_a'];

	
	$strSQL="INSERT INTO tbl_wh_data(report_month,report_year,item_id,wh_id,wh_obl_a,wh_obl_c,wh_received,wh_issue_up,wh_cbl_c,wh_cbl_a,wh_adja,wh_adjb, lvl)
	VALUES ('".$r['report_month']."','".$r['report_year']."','".$r['item_id']."','".$r['wh_id']."',".$fld_obl_a.",".$fld_obl_c.",".$fld_recieved.",".$fld_issue_up.",".$fld_cbl_c.",".$fld_cbl_a.",".$fld_adja.",".$fld_adjb.",4)";
	print mysql_query($strSQL) or die(print mysql_error())."<BR>";
	}
$strSQL="update tbl_wh_data set lvl=3 where lvl is NULL";
	print mysql_query($strSQL) or die(print mysql_error())."<BR>";


?>