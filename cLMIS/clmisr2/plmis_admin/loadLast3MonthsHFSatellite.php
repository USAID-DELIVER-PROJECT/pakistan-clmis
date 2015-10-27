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
    $wh_Id = $wh_Id;
}

$objReports->wh_id = $wh_Id;
$objReports->province_id = $province;
$LastReportDate = $objReports->GetLastReportDateHFSatellite();

if ($LastReportDate != "") {    
    $LRD_dt = new DateTime($LastReportDate);
    $NewReportDate = $objReports->GetPendingReportMonthHFSatellite();
    
    if ($NewReportDate != "") {
		
        $NRD_dt = new DateTime($NewReportDate);
        echo "<tr>";
        echo "<td class=\"sb1NormalFont\" style=\"text-align:center;\">" . $counter++ . "</td>";
        echo "<td class=\"sb1NormalFont\">" . $row['wh_name'] . "</td>";
        echo "<td>" . $row['last_update'] . "</td>";
        //$do="Z".base64_encode($wh_Id.'|'.$NRD_dt->format('Y-m-').'01|1');
        $do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
        ?>
        <td>
            <?php
            // Show last three months for which date is entered
            $allMonths1 = '';
            $last3Months = $objReports->GetLast3MonthsHFSatellite();
            for ($i = 0; $i < sizeof($last3Months); $i++) {
                $L3M_dt = new DateTime($last3Months[$i]);
                $do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');
                $url = $dataEntryURL . "?Do=" . $do3Months;
                $allMonths1 .= "<a href=\"#\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "</a>" . ", ";
            }
            echo substr($allMonths1, 0, -2);
            $url = $dataEntryURL . "?Do=" . $do;
            echo " <a href=\"#\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report</a>";
            $flag1 = TRUE;
            ?>

        </td>
        <?php
        echo "</tr>";
    } else {
        echo "<tr>";
        echo "<td class=\"sb1NormalFont\" style=\"text-align:center;\">" . $counter++ . "</td>";
        echo "<td class=\"sb1NormalFont\">" . $row['wh_name'] . "</td>";
        echo "<td>" . $row['last_update'] . "</td>";
        ?>
        <td>
            <?php
            // Show last three months for which date is entered
            $allMonths = '';
            $last3Months = $objReports->GetLast3MonthsHFSatellite();
            for ($i = 0; $i < sizeof($last3Months); $i++) {
                $L3M_dt = new DateTime($last3Months[$i]);
                $do3Months = urlencode("Z" . ($wh_Id + 77000) . '|' . $L3M_dt->format('Y-m-') . '01|0');

                $url = $dataEntryURL . "?Do=" . $do3Months;
                $allMonths .= "<a href=\"#\" onclick=\"openPopUp('$url')\">" . $L3M_dt->format('M-Y') . "</a>" . ", ";
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
        	echo "<td class=\"sb1NormalFont\" style=\"text-align:center;\">" . $counter++ . "</td>";
            echo "<td class=\"sb1NormalFont\">" . $row['wh_name'] . "</td>";
            echo "<td>" . $row['last_update'] . "</td>";
            $do = urlencode("Z" . ($wh_Id + 77000) . '|' . $NRD_dt->format('Y-m-') . '01|1');
            $url = $dataEntryURL . "?Do=" . $do;
            echo "<td><a href=\"#\" onclick=\"openPopUp('$url')\" style=\"color: blue\"> Add " . $NRD_dt->format('M-y') . " Report </a></td>";
        }
    }
    echo "</tr>";
}
?>