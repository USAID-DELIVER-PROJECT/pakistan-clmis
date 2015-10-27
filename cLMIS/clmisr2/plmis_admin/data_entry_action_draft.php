<?php
include("Includes/AllClasses.php");

date_default_timezone_set("Asia/Karachi"); 

function getClientIp()
{
	/*$ch = curl_init();
	$opt = curl_setopt($ch, CURLOPT_URL, "YOUR_SOME_URL_ADDRESS"); 
	curl_exec($ch);
	$response = curl_getinfo($ch);
	$ip = $response["local_ip"];*/
	$ip = $_SERVER['REMOTE_ADDR'];
	return $ip;
}

if($_POST['ActionType']=='Add') 
{
	$delQry = "DELETE FROM tbl_wh_data_draft WHERE `wh_id`=".$_POST['wh_id']." AND report_month='".$_POST['mm']."' AND report_year='".$_POST['yy']."' ";
	mysql_query($delQry) or die(mysql_error());
	
	if ( $_POST['isNewRpt'] == 0 ){
		$addDate = $_POST['add_date'];
	}else{
		$addDate = date('Y-m-d H:i:s');
	}
	
	$lastUpdate = date('Y-m-d H:i:s');
	// Client IP
	$clientIp = getClientIp();
	
	if(isset($_POST['itmrec_id']) && !empty($_POST['itmrec_id']) && is_array($_POST['itmrec_id']))
		$postedArray = $_POST['itmrec_id'];
	else
		$postedArray = $_POST['flitmrec_id'];
	
	foreach($postedArray as $val)
	{
	$itemid = explode('-',$val);
		$FLDOBLA="0".$_POST['FLDOBLA'.$itemid[1]];
		//print $_POST['FLDOBLA'.$itemid[1]];
		//exit;
		$FLDOBLC="0".$_POST['FLDOBLC'.$itemid[1]];
		$FLDRecv="0".$_POST['FLDRecv'.$itemid[1]];
		$FLDIsuueUP="0".$_POST['FLDIsuueUP'.$itemid[1]];
		$FLDCBLA="0".$_POST['FLDCBLA'.$itemid[1]];
		$FLDCBLC="0".$_POST['FLDCBLC'.$itemid[1]];
		$FLDReturnTo="0".$_POST['FLDReturnTo'.$itemid[1]];
		$FLDUnusable="0".$_POST['FLDUnusable'.$itemid[1]];
				
		$queryadddata = "INSERT INTO tbl_wh_data_draft(report_month,report_year,item_id,wh_id,wh_obl_a,wh_obl_c,
		wh_received,wh_issue_up,wh_cbl_a,wh_cbl_c,wh_adja,wh_adjb,RptDate,add_date,last_update,ip_address)  Values(		
		".$_POST['mm'].",
		".$_POST['yy'].",							
		'".$val."',
		".$_POST['wh_id'].",
		".$FLDOBLA.",
		".$FLDOBLC.",
		".$FLDRecv.",
		".$FLDIsuueUP.",
		".$FLDCBLA.",
		".$FLDCBLC.",
		".$FLDReturnTo.",
		".$FLDUnusable.",
		'".$_POST['RptDate']."',
		'".$addDate."',
		'".$lastUpdate."',
		'".$clientIp."'
		)";
		
		$rsadddata = mysql_query($queryadddata) or die(mysql_error());
		//PRINT $queryadddata."<BR>";
	}
}
?>