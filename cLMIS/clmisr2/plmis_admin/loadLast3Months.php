<?php
include("Includes/AllClasses.php");
$flag1 = FALSE;

if (isset($_SESSION['userid'])) {
	$userid = $_SESSION['userid'];
	$objwharehouse_user->m_npkId = $userid;
	$province = $_SESSION['prov_id'];
	$mainStk = $_SESSION['userdata'][7];
	//$result = $objwharehouse_user->GetwhuserByIdc();
}

if (isset($_REQUEST['wharehouse_id'])) {
	$wh_Id = $_REQUEST['wharehouse_id'];
} else {
	die('No warehouses selected.');
}
if (isset($_REQUEST['dataEntryURL'])) {
	$dataEntryURL = $_REQUEST['dataEntryURL'];
}

function checkDraft($draftMonth, $draftYear, $wh_Id)
{
    // See if this month data exists in drafts
    $qry = "SELECT
				COUNT(tbl_wh_data_draft.w_id) AS num
			FROM
				tbl_wh_data_draft
			WHERE
				tbl_wh_data_draft.report_month = $draftMonth
			AND tbl_wh_data_draft.report_year = $draftYear
			AND tbl_wh_data_draft.wh_id = $wh_Id";
    $qryRes = mysql_fetch_array(mysql_query($qry));
    if ($qryRes['num']>0)
    {
        $draft = ' (Draft)';
    }
    else
    {
        $draft = '';
    }
    return $draft;
}

// Last Update
$lastUpQry = "SELECT
				CONCAT(DATE_FORMAT(tbl_wh_data.last_update,'%d/%m/%Y'),' ',TIME_FORMAT(tbl_wh_data.last_update,'%r')) AS last_update
			FROM
				tbl_wh_data
			WHERE
				tbl_wh_data.wh_id = $wh_Id
			ORDER BY
				tbl_wh_data.w_id DESC
			LIMIT 1";
$lastUpQryRes = mysql_fetch_array(mysql_query($lastUpQry));
$lastUpdate = (!empty($lastUpQryRes['last_update'])) ? $lastUpQryRes['last_update'] : 'Not yet reported';

$objReports->wh_id = $wh_Id;
$objReports->province_id = $province;
$LastReportDate = $objReports->GetLastReportDate();

if ($LastReportDate != "")
{
	$LRD_dt = new DateTime($LastReportDate);
	$NewReportDate = $objReports->GetPendingReportMonth();
	
	if ($NewReportDate != "") 
	{
		$NRD_dt = new DateTime($NewReportDate);
		echo 'Last Update: '.$lastUpdate.'<br>';
		$do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
		
		// Show last three months for which date is entered
		$allMonths1 = '';
		$last3Months = $objReports->GetLast3Months();
		for ($i = 0; $i < sizeof($last3Months); $i++)
		{
			$L3M_dt = new DateTime($last3Months[$i]);
			$draftYear = $L3M_dt->format('Y');
			$draftMonth = $L3M_dt->format('m');
			
			$draft = checkDraft($draftMonth, $draftYear, $wh_Id);
			$do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');
			$url = "data_entry.php?Do=" . $do3Months;
			$allMonths1 .= "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "$draft" . "</a>" . ", ";
		}
		echo substr($allMonths1, 0, -2);
		
		$draftYear = $NRD_dt->format('Y');
		$draftMonth = $NRD_dt->format('m');
		$draft = checkDraft($draftMonth, $draftYear, $wh_Id);
		$url = "data_entry.php?Do=" . $do;
		echo " <a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report$draft</a>";
		$flag1 = TRUE;
	}
	else
	{
		echo 'Last Update: '.$lastUpdate.'<br>';
		$allMonths = '';
		$last3Months = $objReports->GetLast3Months();
		for ($i = 0; $i < sizeof($last3Months); $i++)
		{
			$L3M_dt = new DateTime($last3Months[$i]);
			$draftYear = $L3M_dt->format('Y');
			$draftMonth = $L3M_dt->format('m');
			$draft = checkDraft($draftMonth, $draftYear, $wh_Id);
			$do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');
			$url = "data_entry.php?Do=" . $do3Months;
			$allMonths .= "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "$draft </a>" . ", ";
		}
		echo substr($allMonths, 0, -2);
	}
}
if ($flag1 != TRUE)
{
	$NRD_dt = new DateTime($objReports->GetThisMonthReportDate());
	if (substr($LastReportDate, 0, 7) != $NRD_dt->format('Y-m'))
	{
		if (substr($LastReportDate, 0, 7) < $NRD_dt->format('Y-m'))
		{
			echo 'Last Update: '.$lastUpdate.'<br>';
			$draftYear = $NRD_dt->format('Y');
			$draftMonth = $NRD_dt->format('m');
			$draft = checkDraft($draftMonth, $draftYear, $wh_Id);
			$do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
			$url = "data_entry.php?Do=" . $do;
			echo "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report$draft </a>";
		}
	}
}
?>