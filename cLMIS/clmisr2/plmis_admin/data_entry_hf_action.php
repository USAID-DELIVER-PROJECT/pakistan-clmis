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
	$delQry = "DELETE FROM tbl_hf_data WHERE tbl_hf_data.warehouse_id = ".$_POST['wh_id']." AND tbl_hf_data.reporting_date = '".$_POST['RptDate']."'";
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
		$itemCategory = $_POST['flitm_category'][$count++];
             
		$FLDOBLA="0".$_POST['FLDOBLA'.$itemid];
		$FLDRecv="0".$_POST['FLDRecv'.$itemid];
		$FLDIsuueUP="0".$_POST['FLDIsuueUP'.$itemid];
		$FLDCBLA="0".$_POST['FLDCBLA'.$itemid];
		$FLDReturnTo="0".$_POST['FLDReturnTo'.$itemid];
		$FLDUnusable="0".$_POST['FLDUnusable'.$itemid];
                
		$wh_id = $_POST['wh_id'];
		$report_year = $_POST['yy'];
		$report_month = $_POST['mm'];
		
		// Check if data already exists
		$checkData = "SELECT
						COUNT(tbl_hf_data.pk_id) AS num
					FROM
						tbl_hf_data
					WHERE
						tbl_hf_data.warehouse_id = ".$_POST['wh_id']."
					AND tbl_hf_data.reporting_date = ".$_POST['RptDate']."
					AND tbl_hf_data.item_id = '".$val."'";
		$data = mysql_fetch_array(mysql_query($checkData));
		if ($data['num'] == 0)
		{
			 $queryadddata = "INSERT INTO tbl_hf_data(
								item_id,
								warehouse_id,
								opening_balance,
								received_balance,
								issue_balance,
								closing_balance,
								adjustment_positive,
								adjustment_negative,
								reporting_date,
								created_date,
								last_update,
								ip_address,
								created_by)
						Values(							
							'".$itemid."',
							".$_POST['wh_id'].",
							".$FLDOBLA.",
							".$FLDRecv.",
							".$FLDIsuueUP.",
							".$FLDCBLA.",
							".$FLDReturnTo.",
							".$FLDUnusable.",
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
						tbl_hf_data.reporting_date
					FROM
						tbl_hf_data
					WHERE
						tbl_hf_data.warehouse_id = $wh_id
					ORDER BY
						tbl_hf_data.reporting_date DESC
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
					$qry = "SELECT REPUpdateCarryForwardHF('$date', '$itemid', $wh_id) FROM DUAL";
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
	if ( !empty($_POST['redir_url']) )
	{
		$url = $_POST['redir_url'];
	}
	else
	{
		$url = 'data_entry_hf.php';
	}
	
	
	// Get Field Warehouse
	$qry = "SELECT
				A.wh_id
			FROM
				(
					SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.dist_id,
						tbl_warehouse.stkid
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					AND stakeholder.lvl = 4
					ORDER BY
						tbl_warehouse.wh_id ASC
				) A
			JOIN (
				SELECT
					*
				FROM
					(
						SELECT
							tbl_warehouse.dist_id,
							tbl_warehouse.stkid
						FROM
							tbl_warehouse
						WHERE
							tbl_warehouse.wh_id = $wh_id
					) A
			) B ON A.dist_id = B.dist_id
			AND A.stkid = B.stkid";
	$row = mysql_fetch_array(mysql_query($qry));
	$wh_id = $row['wh_id'];
	header("location:$url?e=ok&wh=$wh_id");
	exit;
}