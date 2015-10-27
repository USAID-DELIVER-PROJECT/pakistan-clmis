<?php
include("Includes/AllClasses.php");
$province=$_SESSION['prov_id'];
$mainStk=$_SESSION['userdata'][7];
if(!isset($_SESSION['userid']))
{
	$location = SITE_URL.'index.php';?>
	
	<script type="text/javascript">
		window.location = "<?php echo $location;?>";
	</script>
<?php }
	
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
	$delQry = "DELETE FROM tbl_wh_data WHERE `wh_id`=".$_POST['wh_id']." AND RptDate='".$_POST['RptDate']."' ";
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
		
		$wh_id = $_POST['wh_id'];
		$itemid = $val;
		$report_year = $_POST['yy'];
		$report_month = $_POST['mm'];
		
		// Check if data already exists
		$checkData = "SELECT
						COUNT(tbl_wh_data.w_id) AS num
					FROM
						tbl_wh_data
					WHERE
						tbl_wh_data.wh_id = ".$_POST['wh_id']."
					AND tbl_wh_data.report_month = ".$_POST['mm']."
					AND tbl_wh_data.report_year = ".$_POST['yy']."
					AND tbl_wh_data.item_id = '".$val."'";
		$data = mysql_fetch_array(mysql_query($checkData));
		if ($data['num'] == 0)
		{
			$queryadddata = "INSERT INTO tbl_wh_data(report_month,report_year,item_id,wh_id,wh_obl_a,wh_obl_c,
			wh_received,wh_issue_up,wh_cbl_a,wh_cbl_c,wh_adja,wh_adjb,RptDate,add_date,last_update,ip_address,created_by)  Values(		
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
			'".$clientIp."',
			'".$_SESSION['userid']."'
			)";
			
			$rsadddata = mysql_query($queryadddata) or die(mysql_error());
			
			$queryadddata1 = "INSERT INTO tbl_wh_data_history(report_month,report_year,item_id,wh_id,wh_obl_a,
			wh_received,wh_issue_up,wh_cbl_a,wh_adja,wh_adjb,RptDate,add_date,ip_address,created_by) Values(		
			".$_POST['mm'].",
			".$_POST['yy'].",							
			'".$val."',
			".$_POST['wh_id'].",
			".$FLDOBLA.",
			".$FLDRecv.",
			".$FLDIsuueUP.",
			".$FLDCBLA.",
			".$FLDReturnTo.",
			".$FLDUnusable.",
			'".$_POST['RptDate']."',
			'".$lastUpdate."',
			'".$clientIp."',
			'".$_SESSION['userid']."'
			)";
			
			$rsadddata1 = mysql_query($queryadddata1) or die(mysql_error());
			
			// If previous month is edited then it should carried to the last data entry month
			/* ----------------- Start ----------------- */
			$stDate = $report_year.'-'.$report_month.'-01';
			$startDate = date('Y-m-d', strtotime("+1 month", strtotime($stDate)));
			$DEQry = mysql_fetch_array(mysql_query("SELECT
						tbl_wh_data.RptDate
					FROM
						tbl_wh_data
					WHERE
						tbl_wh_data.wh_id = $wh_id
					ORDER BY
						tbl_wh_data.RptDate DESC
					LIMIT 1"));
			if ( !empty($DEQry['RptDate']) )
			{
				$endDate = $DEQry['RptDate'];
				$endDate = date('Y-m-d', strtotime("+1 month", strtotime($endDate)));
				$begin = new DateTime($startDate);
				$end = new DateTime($endDate);
				$diff = $begin->diff($end);
				$totalMonths = (($diff->format('%y') * 12) + $diff->format('%m'));
				$interval = DateInterval::createFromDateString('1 month');
				$period = new DatePeriod($begin, $interval, $end);
				foreach ($period as $date)
				{
					$date = $date->format( "Y-m-d" );
					$qry = "SELECT REPUpdateCarryForward('$date', '$itemid', $wh_id) FROM DUAL";
					//echo $qry.';<br>';
					mysql_query($qry);
				}
			}
			/* ----------------- End ----------------- */
		}
		
	}

	// Track history
	$sql = "INSERT INTO tbl_wh_update_history
		SET
			wh_id = '$wh_id',
			reporting_date = '".$_POST['RptDate']."',
			update_on = NOW(),
			updated_by = '".$_SESSION['userdata'][5]."',
			ip_address = '".$clientIp."' ";
	mysql_query($sql);
	
	// Delete draft data
	$delQry = "DELETE FROM tbl_wh_data_draft WHERE `wh_id`=".$_POST['wh_id']." AND report_month='".$_POST['mm']."' AND report_year='".$_POST['yy']."' ";
	mysql_query($delQry) or die(mysql_error());
	
	if($_SESSION['UserType'] == 'UT-006'){
		header("location:data_entry_admin.php");
		exit;
	} else {
		header("location:data_entry.php?e=ok&wh=$wh_id");
		exit;
	}	
}

?>