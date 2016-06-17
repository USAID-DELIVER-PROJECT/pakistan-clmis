<?php
/**
 * iframe_data_entry
 * @package consumption
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//Start session
session_start();
$_SESSION['user_id'] = '0';
//Including AllClasses file
include("../includes/classes/AllClasses.php");
//Including header file
include(PUBLIC_PATH . "html/header.php");
//Including top_im file
include PUBLIC_PATH . "html/top_im.php";
//Checking if data is saved
if (isset($_REQUEST['e']) && $_REQUEST['e'] == 'ok') {
    //Display message
    echo "Data has been successfully saved. ";
    exit;
}
?>
<link href="<?php echo PUBLIC_URL; ?>css/styles.css" rel="stylesheet" type="text/css"/>
</head><body class="page-header-fixed page-quick-sidebar-over-content">
    <div class="modal"></div>
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="page-content" style="margin-left:0px !important">
                <?php
                $wh_id = "";
                if (isset($_REQUEST['do']) && !empty($_REQUEST['do'])) {
                    list($hfCode, $reportingDate, $dataViewType) = explode("|", base64_decode($_REQUEST['do']));
                    //Report Date
                    $RptDate = trim($reportingDate) . '-01';
                    //if value=1 then new report
                    $isNewRpt = $dataViewType;
                    $date = explode("-", $RptDate);
                    //Reprot year
                    $yy = $date[0];
                    //report Month
                    $mm = $date[1];

                    if (strlen($reportingDate) != 7 || !checkdate($mm, 1, $yy)) {
                        exit('Invalid date format.');
                    }
                    //***********************************
                    // Check if the facility exists
                    //***********************************
                    //Gets
                    //num
                    //wh_id
                    //wh_name
                    //dhis_code
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
                    if ($checkHFRes['num'] > 0) {
                        $wh_id = $checkHFRes['wh_id'];
                    } else {
                        //***********************************
                        // Add Health Facility
                        //***********************************
                        $url = "http://mnch.pk/cmw_db/getcmwinfo.php?cmwcode=$hfCode&month=$reportingDate";
                        //***********************************
                        //Checking curl installation
                        //***********************************
                        if (!function_exists('curl_init')) {
                            //Display error
                            die('Sorry CURL is not installed.');
                        }
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        //Decode from json
                        $cmwInfo = json_decode($output);
                        //cmw name
                        $hfName = trim($cmwInfo->cmwname);
                        //district code
                        $districtId = $cmwInfo->districtcode;
                        //province code
                        $provinceId = $cmwInfo->provincecode;
                        //reporting status
                        $reportingStatus = $cmwInfo->reportingstatus;
                        //Insert query
                        $qry = "INSERT INTO tbl_warehouse
						SET	 
							 tbl_warehouse.wh_name = '" . $hfName . "',
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
                        //***********************************
                        // Assign warehosue to the user
                        //***********************************
                        $qry = "INSERT INTO wh_user
							SET
								wh_user.sysusrrec_id = $userId,
								wh_user.wh_id = $wh_id";
                        mysql_query($qry);
                    }
                    if (!empty($wh_id)) {
                        //***********************************************************************
                        // If 1st data entry month then open Opening Balance Field else Lock it
                        //***********************************************************************
                        //Gets reporting_date
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
                        $objwarehouse->m_npkId = $wh_id;
                        //Get Stakeholder ID By WH Id
                        $stkid = $objwarehouse->GetStkIDByWHId($wh_id);
                        //Get Warehouse Name By Id
                        $whName = $objwarehouse->GetWarehouseNameById($wh_id);
                        echo "<h3 class=\"page-title row-br-b-wp\">" . $whName . " <span class=\"green-clr-txt\">(" . $month . ' ' . $yy . ")</span> </h3>";
                        //If new report
                        if ($isNewRpt == 1) {
                            //Get Previous Month Report Date
                            $PrevMonthDate = $objReports->GetPreviousMonthReportDate($RptDate);
                        } else {
                            $PrevMonthDate = $RptDate;
                        }

                        $redirectURL = 'iframe_data_entry.php';
                        //Including file
                        include('data_entry_common.php');
                    }
                }
                ?>       	
            </div>
        </div>
    </div>
    <script src="<?php echo PUBLIC_URL; ?>assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script> 
    <script src="<?php echo PUBLIC_URL; ?>js/dataentry/dataentry.js"></script>
    <script>
        function get_browser_info() {
            var ua = navigator.userAgent, tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if (/trident/i.test(M[1])) {
                tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
                return {name: 'IE', version: (tem[1] || '')};
            }
            if (M[1] === 'Chrome') {
                tem = ua.match(/\bOPR\/(\d+)/)
                if (tem != null) {
                    return {name: 'Opera', version: tem[1]};
                }
            }
            M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
            if ((tem = ua.match(/version\/(\d+)/i)) != null) {
                M.splice(1, 1, tem[1]);
            }
            return {
                name: M[0],
                version: M[1]
            };
        }
        var browser = get_browser_info();
        //alert(browser.name + ' - ' + browser.version);
        if (browser.name == 'Firefox' && browser.version < 30)
        {
            alert('You are using an outdated version of the Mozilla Firefox. Please update your browser for data entry.');
            window.close();
        }
        else if (browser.name == 'Chrome' && browser.version < 35)
        {
            alert('You are using an outdated version of the Chrome. Please update your browser for data entry.');
            window.close();
        }
        else if (browser.name == 'Opera' && browser.version < 28)
        {
            alert('You are using an outdated version of the Opera. Please update your browser for data entry.');
            window.close();
        }
        else if (browser.name == 'MSIE')
        {
            alert('Please use Mozilla Firefox, Chrome or Opera for data entry.');
            window.close();
        }
    </script>
</body>
</html>