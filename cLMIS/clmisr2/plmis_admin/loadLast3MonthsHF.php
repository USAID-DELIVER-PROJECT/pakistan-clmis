<?php
include("Includes/AllClasses.php");
$flag1 = FALSE;

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $objwharehouse_user->m_npkId = $userid;
    $province = $_SESSION['prov_id'];
    $mainStk = $_SESSION['userdata'][7];
   // $result = $objwharehouse_user->GetwhuserHFByIdc();
}

if (isset($_REQUEST['wharehouse_id'])) {
    $wh_Id = $_REQUEST['wharehouse_id'];
} else {
    die('No warehouses selected.');
}
if (isset($_REQUEST['dataEntryURL'])) {
    $dataEntryURL = $_REQUEST['dataEntryURL'];
}

// Last Update
$lastUpQry = "SELECT
				CONCAT(DATE_FORMAT(tbl_hf_data.last_update,'%d/%m/%Y'),' ',TIME_FORMAT(tbl_hf_data.last_update,'%r')) AS last_update
			FROM
				tbl_hf_data
			WHERE
				tbl_hf_data.warehouse_id = $wh_Id
			ORDER BY
				tbl_hf_data.pk_id DESC
			LIMIT 1";
$lastUpQryRes = mysql_fetch_array(mysql_query($lastUpQry));
$lastUpdate = (!empty($lastUpQryRes['last_update'])) ? $lastUpQryRes['last_update'] : 'Not yet reported';

$objReports->wh_id = $wh_Id;
$objReports->stk = $mainStk;
$objReports->province_id = $province;
$LastReportDate = $objReports->GetLastReportDateHF();

if ($LastReportDate != "") {    
    $LRD_dt = new DateTime($LastReportDate);
    $NewReportDate = $objReports->GetPendingReportMonthHF();
    
    if ($NewReportDate != "") {
        $NRD_dt = new DateTime($NewReportDate);
        echo 'Last Update: '.$lastUpdate.'<br>';
        $do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
        ?>
        <td>
            <?php
            // Show last three months for which date is entered
            $allMonths1 = '';
            $last3Months = $objReports->GetLast3MonthsHF();
            for ($i = 0; $i < sizeof($last3Months); $i++) {
                $L3M_dt = new DateTime($last3Months[$i]);
                $do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');
                $url = $dataEntryURL . "?Do=" . $do3Months;
                $allMonths1 .= "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "</a>" . ", ";
            }
            echo substr($allMonths1, 0, -2);
            $url = $dataEntryURL . "?Do=" . $do;
            echo " <a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report</a>";
            $flag1 = TRUE;
            ?>

        </td>
        <?php
    } else {
		echo 'Last Update: '.$lastUpdate.'<br>';
        ?>
        <td>
            <?php
            // Show last three months for which date is entered
            $allMonths = '';
            $last3Months = $objReports->GetLast3MonthsHF();
            for ($i = 0; $i < sizeof($last3Months); $i++) {
                $L3M_dt = new DateTime($last3Months[$i]);
                $do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');

                $url = $dataEntryURL . "?Do=" . $do3Months;
                $allMonths .= "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "</a>" . ", ";
            }
            echo substr($allMonths, 0, -2);
            ?>

        </td>
        <?php
    }
}
if ($flag1 != TRUE) {
    $NRD_dt = new DateTime($objReports->GetThisMonthReportDate());
    if (substr($LastReportDate, 0, 7) != $NRD_dt->format('Y-m')) {
        if (substr($LastReportDate, 0, 7) < $NRD_dt->format('Y-m')) {
			echo 'Last Update: '.$lastUpdate.'<br>';
            $do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
            $url = $dataEntryURL . "?Do=" . $do;
            echo "<a href=\"javascript:void(0);\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report </a>";
        }
    }
}
?>