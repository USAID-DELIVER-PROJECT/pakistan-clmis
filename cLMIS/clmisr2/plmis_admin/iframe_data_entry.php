<?php
session_start();
$_SESSION['userid'] = '0';

include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_REQUEST['e']) && $_REQUEST['e'] == 'ok')
{
	echo "Data has been successfully saved. ";
	exit;
}
?>
<!doCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
#hideit {
	display: none;
}
input[type="text"] {
	text-align: right;
}
</style>
<title>Data Entry</title>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<table width="980" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr id="hideit">
        <td><?php include(SITE_PATH."plmis_inc/common/top.php");?></td>
    </tr>
    <tr>
        <td valign="top"><table width="100%" height="146" valign="top">
                <tr>
                    <td width="80%" bgcolor="#E1E1E1" valign="top" id="showGrid">
					<?php
					$wh_id = "";
					if(isset($_REQUEST['do']) && !empty($_REQUEST['do']))
					{
						list($hfCode, $reportingDate, $dataViewType) = explode("|", base64_decode($_REQUEST['do']));
												
						$RptDate = trim($reportingDate).'-01'; //Report Date
						$isNewRpt = $dataViewType; //if value=1 then new report
						$date = explode("-",$RptDate); 
						$yy = $date[0]; //Reprot year
						$mm = $date[1]; //report Month
                                                
						if (strlen($reportingDate)!= 7 || !checkdate($mm, 1, $yy)){
							exit('Invalid date format.');
						}
						
						// Check if the facility exists
						$checkHF = "SELECT
										COUNT(tbl_warehouse.wh_id) AS num,
										tbl_warehouse.wh_id,
										tbl_warehouse.wh_name,
										tbl_warehouse.dhis_code
									FROM
										tbl_warehouse
									WHERE
										tbl_warehouse.dhis_code = '$hfCode' ";
						$checkHFRes = mysql_fetch_array(mysql_query($checkHF));
						if ( $checkHFRes['num'] > 0 )
						{
							$wh_id = $checkHFRes['wh_id'];
						}
						else // Add Health Facility
						{
							$url = "http://mnch.pk/cmw_db/getcmwinfo.php?cmwcode=$hfCode&month=$reportingDate";
							if (!function_exists('curl_init')){
								die('Sorry CURL is not installed.');
							}
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							$output = curl_exec($ch);
							curl_close($ch);
							$cmwInfo = json_decode($output);
							$hfName = trim($cmwInfo->cmwname);
							$districtId = $cmwInfo->districtcode;
							$provinceId = $cmwInfo->provincecode;
							$reportingStatus = $cmwInfo->reportingstatus;

							$qry = "INSERT INTO tbl_warehouse
								SET	 
									 tbl_warehouse.wh_name = '".$hfName."',
									 tbl_warehouse.dist_id = REPgetLmisLocationCode($districtId),
									 tbl_warehouse.prov_id = REPgetLmisLocationCode($provinceId),
									 tbl_warehouse.stkid = 73,
									 tbl_warehouse.locid = REPgetLmisLocationCode($districtId),
									 tbl_warehouse.stkofficeid = 111,
									 tbl_warehouse.hf_type_id = 19,
									 tbl_warehouse.dhis_code = '$hfCode'";
							mysql_query($qry);
							$wh_id = mysql_insert_id();
							// Get User ID
							$qry = "SELECT
										wh_user.sysusrrec_id
									FROM
										tbl_warehouse
									INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
									INNER JOIN sysuser_tab ON wh_user.sysusrrec_id = sysuser_tab.UserID
									WHERE
										tbl_warehouse.stkid = 73
									AND tbl_warehouse.dist_id = REPgetLmisLocationCode($districtId)
										LIMIT 1";
							$qryRes = mysql_fetch_array(mysql_query($qry));
							$userId = $qryRes['sysusrrec_id'];
							// Assign warehosue to the user
							$qry = "INSERT INTO wh_user
									SET
										wh_user.sysusrrec_id = $userId,
										wh_user.wh_id = $wh_id";
							mysql_query($qry);
						}
						if ( !empty($wh_id) )
						{
							// If 1st data entry month then open Opening Balance Field else Lock it
							$checkData = "SELECT
											tbl_hf_data.reporting_date
										FROM
											tbl_hf_data
										WHERE
											tbl_hf_data.warehouse_id = $wh_id
										ORDER BY
											tbl_hf_data.reporting_date ASC
										LIMIT 1";
							$checkDataRes = mysql_fetch_array(mysql_query($checkData));
							$openOB = ($checkDataRes['reporting_date'] == $RptDate) ? '' : $checkDataRes['reporting_date'];
							
							$month = date('M', mktime(0, 0, 0, $mm, 1));
							
							//****************************************************************************
							$objwarehouse->m_npkId=$wh_id;
							print "<b>Store/Facility:</b> ".$objwarehouse->GetWarehouseNameById($wh_id);//."[".$wh_id."]";
							
							$stkid=$objwarehouse->GetStkIDByWHId($wh_id);
							print "; ";
							print "<b>Monthly Report:</b> ".$month.'-'.$yy;  //"[".$wh_id."]";
							if ( $isNewRpt == 1 )
							{
								$PrevMonthDate=$objReports->GetPreviousMonthReportDate($RptDate);
							}
							else 
							{
								$PrevMonthDate=$RptDate;
							}
							
							$redirectURL = 'iframe_data_entry.php';
							include('data_entry_common.php');
							
						}
						
						else
						{
							//echo "<H1><- Please select warehouse for report</H1>";
						}
					}
					?>
					</td>
                </tr>
                <tr>
                    <td colspan="8"><div id="eMsg" style="color:#060;"></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script src="<?php echo PLMIS_JS;?>dataentry/dataentry.js"></script>
<style>
	.sb1NormalFontArial{ text-align:right !important;}
	table#myTable tr td{padding:3px; text-align:left; border:1px solid #999;}
</style>
</body>
</html>