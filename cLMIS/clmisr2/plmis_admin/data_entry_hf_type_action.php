<?php
include("Includes/AllClasses.php");
$province=$_SESSION['prov_id'];
$mainStk=$_SESSION['userdata'][7];

$districtId = $_SESSION['dist_id'];

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
	/*echo '<pre>';
	print_r($_POST);
	exit;*/
	// If one of the previous 3 months then delete first then add new	
	if ( $_POST['isNewRpt'] == 0 ){
		$delQry = "DELETE FROM tbl_hf_type_data WHERE facility_type_id = ".$_POST['wh_id']." AND reporting_date='".$_POST['RptDate']."' AND district_id = $districtId ";
		mysql_query($delQry) or die(mysql_error());
		$delQry = "DELETE FROM tbl_hf_type_mother_care WHERE district_id = ".$districtId." AND facility_type_id = ".$_POST['wh_id']." AND reporting_date='".$_POST['RptDate']."' ";
		mysql_query($delQry) or die(mysql_error());
		
		$addDate = $_POST['add_date'];
	}
	else
	{
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
		$FLDRecv="0".$_POST['FLDRecv'.$itemid[1]];
		$FLDIsuueUP="0".$_POST['FLDIsuueUP'.$itemid[1]];
		$FLDCBLA="0".$_POST['FLDCBLA'.$itemid[1]];
		$FLDReturnTo="0".$_POST['FLDReturnTo'.$itemid[1]];
		$FLDUnusable="0".$_POST['FLDUnusable'.$itemid[1]];
		$FLDnew="0".$_POST['FLDnew'.$itemid[1]];
		$FLDold="0".$_POST['FLDold'.$itemid[1]];
		
		$wh_id = $_POST['wh_id'];
		$itemid = $val;
		$report_year = $_POST['yy'];
		$report_month = $_POST['mm'];
		
		// Check if data already exists
		$checkData = "SELECT
						COUNT(tbl_hf_type_data.pk_id) AS num
					FROM
						tbl_hf_type_data
					WHERE
						tbl_hf_type_data.facility_type_id = ".$_POST['wh_id']."
					AND tbl_hf_type_data.reporting_date = ".$_POST['RptDate']."
					AND tbl_hf_type_data.item_id = '".$val."'
					AND district_id = $districtId";
		$data = mysql_fetch_array(mysql_query($checkData));
		if ($data['num'] == 0)
		{
			$queryadddata = "INSERT INTO tbl_hf_type_data(
								item_id,
								facility_type_id,
								district_id,
								opening_balance,
								received_balance,
								issue_balance,
								closing_balance,
								adjustment_positive,
								adjustment_negative,
								new,
								old,
								reporting_date,
								created_date,
								last_update,
								ip_address,
								created_by)
						Values(							
							'".$val."',
							".$_POST['wh_id'].",
							".$districtId.",
							".$FLDOBLA.",
							".$FLDRecv.",
							".$FLDIsuueUP.",
							".$FLDCBLA.",
							".$FLDReturnTo.",
							".$FLDUnusable.",
							".$FLDnew.",
							".$FLDold.",
							'".$_POST['RptDate']."',
							'".$addDate."',
							'".$lastUpdate."',
							'".$clientIp."',
							'".$_SESSION['userid']."'
						)";
			
			$rsadddata = mysql_query($queryadddata) or die(mysql_error());
			
			// If previous month is edited then it should carried to the last data entry month
			/* ----------------- Start ----------------- */
			$stDate = $report_year.'-'.$report_month.'-01';
			$startDate = date('Y-m-d', strtotime("+1 month", strtotime($stDate)));
			$DEQry = mysql_fetch_array(mysql_query("SELECT
						tbl_hf_type_data.reporting_date
					FROM
						tbl_hf_type_data
					WHERE
						tbl_hf_type_data.facility_type_id = $wh_id
					AND district_id = $districtId
					ORDER BY
						tbl_hf_type_data.reporting_date DESC
					LIMIT 1"));
			if ( !empty($DEQry['reporting_date']) )
			{
				$endDate = $DEQry['reporting_date'];
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
					$qry = "SELECT REPUpdateCarryForwardHFType('$date', '$itemid', $wh_id, $districtId) FROM DUAL";
					//echo $qry.';<br>';
					mysql_query($qry);
				}
			}
			/* ----------------- End ----------------- */
		}
		
	}
	$qry = "INSERT INTO tbl_hf_type_mother_care
		SET
			district_id = $districtId,
			facility_type_id = $wh_id,
			pre_natal_new = '".$_POST['pre_natal_new']."',
			pre_natal_old = '".$_POST['pre_natal_old']."',
			post_natal_new = '".$_POST['post_natal_new']."',
			post_natal_old = '".$_POST['post_natal_old']."',
			ailment_children = '".$_POST['ailment_child']."',
			ailment_adults = '".$_POST['ailment_adult']."',
			reporting_date = '".$_POST['RptDate']."' ";
	mysql_query($qry);
	
	header("location:data_entry_hf_type.php?e=ok");
	exit;
}