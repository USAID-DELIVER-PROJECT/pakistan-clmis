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
	$delQry = "DELETE FROM tbl_hf_satellite_data WHERE warehouse_id = ".$_POST['wh_id']." AND reporting_date='".$_POST['RptDate']."' ";
	mysql_query($delQry) or die(mysql_error());
	
	$delQry = "DELETE FROM tbl_hf_satellite_mother_care WHERE warehouse_id = ".$_POST['wh_id']." AND reporting_date='".$_POST['RptDate']."' ";
	mysql_query($delQry) or die(mysql_error());
	
	$delQry = "DELETE FROM tbl_satellite_camps WHERE warehouse_id = ".$_POST['wh_id']." AND reporting_date='".$_POST['RptDate']."' ";
	mysql_query($delQry) or die(mysql_error());

	if ( $_POST['isNewRpt'] == 0 ){
		$addDate = $_POST['add_date'];
	}else{
		$addDate = date('Y-m-d H:i:s');
	}
	$lastUpdate = date('Y-m-d H:i:s');
	// Client IP
	$clientIp = getClientIp();
	
	if(isset($_POST['itmrec_id']) && !empty($_POST['itmrec_id']) && is_array($_POST['itmrec_id'])){
		 $postedArray = $_POST['itmrec_id'];
	}
	else{
		$postedArray = $_POST['flitmrec_id'];
	}
	
	$count = 0;
	foreach($postedArray as $itemid)
	{
		$staticCamp= $_POST['staticCamp'.$itemid];
		$itemCategory = $_POST['flitm_category'][$count++];
		$FLDIsuueUP="0".$_POST['FLDIsuueUP'.$itemid];
		$FLDnew="0".$_POST['FLDnew'.$itemid];
		$FLDold="0".$_POST['FLDold'.$itemid];
		
                
		$wh_id = $_POST['wh_id'];
		$report_year = $_POST['yy'];
		$report_month = $_POST['mm'];
		
		// Check if data already exists
		$checkData = "SELECT
						COUNT(tbl_hf_satellite_data.pk_id) AS num
					FROM
						tbl_hf_satellite_data
					WHERE
						tbl_hf_satellite_data.warehouse_id = ".$_POST['wh_id']."
					AND tbl_hf_satellite_data.reporting_date = ".$_POST['RptDate']."
					AND tbl_hf_satellite_data.item_id = '".$val."'";
		$data = mysql_fetch_array(mysql_query($checkData));
		if ($data['num'] == 0)
		{
			 $queryadddata = "INSERT INTO tbl_hf_satellite_data(
								item_id,
								warehouse_id,
								issue_balance,
								new,
								old,
								reporting_date,
								created_date,
								last_update,
								ip_address,
								created_by)
						Values(							
							'".$itemid."',
							".$_POST['wh_id'].",
							".$FLDIsuueUP.",
							".$FLDnew.",
							".$FLDold.",
							'".$_POST['RptDate']."',
							'".$addDate."',
							'".$lastUpdate."',
							'".$clientIp."',
							'".$_SESSION['userid']."'
						)";
			
						$rsadddata = mysql_query($queryadddata) or die(mysql_error());
						$hf_data_id = mysql_insert_id();
						$hf_type_id = $_POST['hf_type_id'];
						$counter = 1;
						foreach($hf_type_id as $hf_type) {
							// insertion in table hf data reference by
							//if ($province == 1 || $province == 3) {
                            if ($itemid == '31' || $itemid == '32') {
								$hf_type_id = $_POST['hf_type_id'];
								$reffered = $_POST['reffered'.$itemid.$hf_type];
								
								if ($counter == 1) {
									$query_reff = "INSERT INTO tbl_hf_satellite_data_reffered_by(
														tbl_hf_satellite_data_reffered_by.hf_data_id,
														tbl_hf_satellite_data_reffered_by.hf_type_id,
														tbl_hf_satellite_data_reffered_by.ref_surgeries,
														tbl_hf_satellite_data_reffered_by.static,
														tbl_hf_satellite_data_reffered_by.camp)
													Values(							
														'".$hf_data_id."',
														'".$hf_type."',
														'".$reffered."',    
														'".$staticCamp[0]."',
														'".$staticCamp[1]."'
														)";
								}
								else {
									$query_reff = "INSERT INTO tbl_hf_satellite_data_reffered_by(
														tbl_hf_satellite_data_reffered_by.hf_data_id,
														tbl_hf_satellite_data_reffered_by.hf_type_id,
														tbl_hf_satellite_data_reffered_by.ref_surgeries,
														tbl_hf_satellite_data_reffered_by.static,
														tbl_hf_satellite_data_reffered_by.camp)
													Values(							
														'".$hf_data_id."',
														'".$hf_type."',
														'".$reffered."',    
														'',
														''
									)";
								}
								$res_reff = mysql_query($query_reff) or die(mysql_error());
							}
                            $counter++;
						}
			
                      
			// If previous month is edited then it should carried to the last data entry month
			/* ----------------- Start ----------------- */
			$stDate = $report_year.'-'.$report_month.'-01';
			$startDate = date('Y-m-d', strtotime("+1 month", strtotime($stDate)));
			$DEQry = mysql_fetch_array(mysql_query("SELECT
						tbl_hf_satellite_data.reporting_date
					FROM
						tbl_hf_satellite_data
					WHERE
						tbl_hf_satellite_data.warehouse_id = $wh_id
					ORDER BY
						tbl_hf_satellite_data.reporting_date DESC
					LIMIT 1"));
			if ( !empty($DEQry['reporting_date']) && $itemCategory != 2 )
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
					$qry = "SELECT REPUpdateCarryForwardHFSatellite('$date', '$itemid', $wh_id) FROM DUAL";
					//echo $qry.';<br>';
					mysql_query($qry);
				}
			}
			/* ----------------- End ----------------- */
		}
		
	}
	
	 $qry = "INSERT INTO tbl_hf_satellite_mother_care
		SET
			warehouse_id = $wh_id,
			pre_natal_new = '".$_POST['pre_natal_new']."',
			pre_natal_old = '".$_POST['pre_natal_old']."',
			post_natal_new = '".$_POST['post_natal_new']."',
			post_natal_old = '".$_POST['post_natal_old']."',
			ailment_children = '".$_POST['ailment_child']."',
			ailment_adults = '".$_POST['ailment_adult']."',
			general_ailment =   '".$_POST['general_ailment']."',  
			reporting_date = '".$_POST['RptDate']."' ";
	mysql_query($qry);
	
	 $qry = "INSERT INTO tbl_satellite_camps
		SET
			warehouse_id = $wh_id,
			reporting_date = '".$_POST['RptDate']."',
			camps_target = '".$_POST['camps_target']."',
			camps_held = '".$_POST['camps_held']."' ";
	mysql_query($qry);
	
	header("location:data_entry_hf_satellite.php?e=ok");
	exit;
}