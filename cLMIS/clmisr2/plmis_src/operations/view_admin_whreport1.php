<?php
//echo "<div align='center' style='color:red'><h1>This page is under maintenance. Please come back some other time.</h1></div>";


/* * *********************************************************************************************************
  Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
  This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
  for editing the record.
  we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted
  data entered through add form and fourth save the data enterd from the edit form
  /********************************************************************************************************** */
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();
/////unset all sessions of form submission
unset($_SESSION['filterParam']['year']);
unset($_SESSION['filterParam']['month']);
unset($_SESSION['filterParam']['wh']);
unset($_SESSION['filterParam']['province']);
unset($_SESSION['numOfRows']);

if ( date('d') > 10 )
{
	$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
}
else
{
	$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
$mymonth = date('m', strtotime($date));
$myyear = date('Y', strtotime($date));

if (isset($_POST['submit'])) {
    include("xml/xml_genaration_whreport.php");
}


if (!ini_get('register_globals')) {
    $superglobals = array($_GET, $_POST, $_COOKIE, $_SERVER);
    if (isset($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
    }
    foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
    }
    ini_set('register_globals', true);
}

if (isset($_POST['stk_sel']) && $_POST['stk_sel'] != "") {
    $sel_stk = $_POST['stk_sel'];
}

if (isset($_POST['district']) && $_POST['district'] != "") {
    $where .= " AND tbl_wh_data.wh_id='" . $_POST['district'] . "'";
    $_SESSION['filterParam']['wh'] = $_POST['district'];
    $whid = $_POST['district'];
}
//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/operations/" . $basename;

//////// GET Read Me Title From DB.

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '" . $filePath . "' and active = 1"));
$readMeTitle = $qryResult['extra'];

if (isset($_POST['submit'])) {
    // Get District name
    $getDist = mysql_fetch_array(mysql_query("SELECT
												tbl_warehouse.wh_name
											FROM
												tbl_warehouse
											WHERE
												tbl_warehouse.wh_id = '" . $_POST['district'] . "' "));
    $whName = $getDist['wh_name'];
    $whName = empty($whName) ? 'All' : $whName;
    // Get Stakeholder name
    $getStk = mysql_fetch_array(mysql_query("SELECT
												stakeholder.stkname
											FROM
												stakeholder
											WHERE
												stakeholder.stkid = '" . $_POST['stk_sel'] . "'"));
    $stkName = $getStk['stkname'];
    $stkName = empty($stkName) ? 'All' : $stkName;

    // Get Province name
    $getProv = mysql_fetch_array(mysql_query("SELECT
												tbl_locations.LocName
											FROM
												tbl_locations
											WHERE
												tbl_locations.PkLocID = '" . $_POST['province'] . "'"));
    $provName = $getProv['LocName'];
    $provName = empty($provName) ? 'All' : $provName;
}
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script>
    $(document).ready(function() {
        showProvinces();
    });
</script>
<script>
    function showProvinces() {
        var colId;
        var selectedVal = '';
        $('#provincesCol').show();
        $('#provincesCol1').show();
        $('#districtsCol').html('<label class="control-label">Store/Facility</label><select name="district" id="district" class="form-control input-sm"><option value="">All</option></select>');
        $('#districtsCol1').hide();
        colId = 'provincesCol';
<?php
if ($_POST['submit']) {
    ?>
            selectedVal = "pId=<?php echo $province; ?>";
    <?php
} else {
    ?>
            selectedVal = "pId=0";
    <?php
}
?>

        //alert(selectedVal);
        $.ajax({
            type: 'POST',
            url: 'ajax_calls.php',
            data: selectedVal,
            success: function(data) {
                $("#" + colId).html(data);
            }
        });

<?php
if ($_POST['submit']) {
    ?>
            showDistricts('<?php echo $province; ?>');
    <?php
}
?>
    }


    function showDistricts(provId) {
        $('#districtsCol').show();
        $('#districtsCol1').show();
        //var val = $('#province').val();
        valStk = $('#stk_sel').val();

        var data = '';
        //var provinceId = (provId == '') ? val : provId;
        data = "dId=<?php echo $district; ?>&provinceId=" + provId + "&stk=" + valStk;

        $.ajax({
            type: 'POST',
            url: 'ajax_calls.php',
            data: data,
            success: function(data) {
                $("#districtsCol").html(data);
            }
        });
    }

</script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
<script>
    var mygrid;
    function doInitGrid() {
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Monthly Store/Facility Report for Stakeholder = <?php echo "'$stkName'"; ?> Province = <?php echo "'$provName'"; ?> and Store/Facility = '<?php echo $whName; ?>' <?php echo "(" . date('F', mktime(0, 0, 0, $_POST['report_month'])) . ' ' . $_POST['report_year'] . ")"; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<span title='Product name'>Product</span>,<span title='Store/Facility name'>Store/Facility</span>,<span title='Opening Balance Calculated'>Opening Balance</span>,<span title='Balance received'>Received</span>,<span title='Balance issued'>Issued</span>,<span title='Adjustments'>Adjustments<span>,#cspan,<span title='Closing balance'>Closing Balance</span>,<span title='Last Modified'>Last Modified</span>");
        mygrid.attachHeader(",,,,,(+),(-),,");
        mygrid.setInitWidths("*,120,110,110,120,80,80,120,120");
        mygrid.setColAlign("left,left,right,right,right,right,right,right,center");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro");
        mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
        //mygrid.loadXML("xml/whreport.xml");
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }

</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">

    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php
        include "../../plmis_inc/common/top_im.php";
        include "../../plmis_inc/common/_top.php";
        ?>


        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                     	<h3 class="page-title row-br-b-wp">View Monthly Store/Facility Report</h3>
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                   <h3 class="heading">Filter by</h3>
                                </div>
                                <div class="widget-body">
                                    <table width="99%">
                                        <tr>
                                            <td>
                                                <form action="" method="post">
                                                    <table>
                                                        <tr>
                                                            <td class="col-md-2">
                                                            	<label class="control-label">Month</label>
                                                                <?php if ($_POST['submit']) { ?>
                                                                    <SELECT NAME="report_month" id="report_month" class="form-control input-sm" TABINDEX="3">
                                                                        <OPTION VALUE="1" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '1') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?> >JANUARY
                                                                        </OPTION>
                                                                        <OPTION VALUE="2" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '2') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>FEBRUARY
                                                                        </OPTION>
                                                                        <OPTION VALUE="3" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '3') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MARCH
                                                                        </OPTION>
                                                                        <OPTION VALUE="4" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '4') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>APRIL
                                                                        </OPTION>
                                                                        <OPTION VALUE="5" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '5') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MAY
                                                                        </OPTION>
                                                                        <OPTION VALUE="6" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '6') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JUN
                                                                        </OPTION>
                                                                        <OPTION VALUE="7" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '7') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JULY
                                                                        </OPTION>
                                                                        <OPTION VALUE="8" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '8') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>AUGUST
                                                                        </OPTION>
                                                                        <OPTION VALUE="9" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '9') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>SEPTEMBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="10" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '10') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>OCTOBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="11" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '11') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>NOVEMBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="12" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '12') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>DECEMBER
                                                                        </OPTION>
                                                                    </SELECT>
                                                                <?php } else { ?>

                                                                    <SELECT NAME="report_month" id="report_month" class="form-control input-sm" TABINDEX="3">
                                                                        <OPTION VALUE="1" <?php
                                                                        if ($mymonth == '01') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?> >JANUARY
                                                                        </OPTION>
                                                                        <OPTION VALUE="2" <?php
                                                                        if ($mymonth == '02') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>FEBRUARY
                                                                        </OPTION>
                                                                        <OPTION VALUE="3" <?php
                                                                        if ($mymonth == '03') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MARCH
                                                                        </OPTION>
                                                                        <OPTION VALUE="4" <?php
                                                                        if ($mymonth == '04') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>APRIL
                                                                        </OPTION>
                                                                        <OPTION VALUE="5" <?php
                                                                        if ($mymonth == '05') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MAY
                                                                        </OPTION>
                                                                        <OPTION VALUE="6" <?php
                                                                        if ($mymonth == '06') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JUN
                                                                        </OPTION>
                                                                        <OPTION VALUE="7" <?php
                                                                        if ($mymonth == '07') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JULY
                                                                        </OPTION>
                                                                        <OPTION VALUE="8" <?php
                                                                        if ($mymonth == '08') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>AUGUST
                                                                        </OPTION>
                                                                        <OPTION VALUE="9" <?php
                                                                        if ($mymonth == '09') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>SEPTEMBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="10" <?php
                                                                        if ($mymonth == '10') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>OCTOBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="11" <?php
                                                                        if ($mymonth == '11') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>NOVEMBER
                                                                        </OPTION>
                                                                        <OPTION VALUE="12" <?php
                                                                        if ($mymonth == '12') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>DECEMBER
                                                                        </OPTION>
                                                                    </SELECT>
                                                                <?php } ?>
                                                            </td>
                                                            <td class="col-md-2">
                                                            	<label class="control-label">Year</label>
                                                                <?php if ($_POST['submit']) { ?>
                                                                    <select name="report_year" id="report_year" class="form-control input-sm" tabindex="2">
                                                                        <?php
                                                                        $EndYear = 2012;
                                                                        $StartYear = date('Y');
                                                                        for ($i = $StartYear; $i >= $EndYear; $i--) {
                                                                            if ($i == $_SESSION['filterParam']['year']) {
                                                                                $chk4 = "Selected = 'Selected'";
                                                                            } else {
                                                                                $chk4 = "";
                                                                            }
                                                                            echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select name="report_year" id="report_year" class="form-control input-sm" tabindex="2">
                                                                        <?php

                                                                        $EndYear = 2012;
                                                                        $StartYear = date('Y');
                                                                        for ($i = $StartYear; $i >= $EndYear; $i--) {
                                                                            if ($myyear == $i) {
                                                                                $chk4 = "Selected = 'Selected'";
                                                                            } else {
                                                                                $chk4 = "";
                                                                            }
                                                                            echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                <?php } ?>
                                                            </td>

                                                            <td class="col-md-2">
                                                            	<label class="control-label">Stakeholder</label>
                                                                <select name="stk_sel" id="stk_sel" class="form-control input-sm" onChange="showProvinces()">
                                                                    <option value="0">All</option>
                                                                    <?php
                                                                    $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null AND stk_type_id IN (0, 1) order by stkorder";
                                                                    $rsstk = mysql_query($querystk) or die();
                                                                    while ($rowstk = mysql_fetch_array($rsstk)) {
                                                                        if ($sel_stk == $rowstk['stkid'])
                                                                            $sel = "selected='selected'";
                                                                        else
                                                                            $sel = "";
                                                                        ?>
                                                                        <option value="<?php echo $rowstk['stkid']; ?>" <?php echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td id="provincesCol" class="col-md-2"></td>
                                                            <td id="districtsCol" class="col-md-2"></td>
                                                            <td class="col-md-2">
                                                                <input type="submit" value="Go" name="submit" class="btn btn-primary input-sm" style="margin-top:28px;" />
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-md-12">	
						<?php
                        if (isset($_POST['submit'])) {
                            if ($_SESSION['numOfRows'] > 0) {
                                ?>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="right" style="padding-right:5px;">
                                            <img title="Click here to export data to PDF file" style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');"/>
                                            <img title="Click here to export data to Excel file" style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div id="mygrid_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div>
                                        </td>
                                    </tr>
                                </table>
                                <?php
                            } else {
                                $qqry = "SELECT * FROM `tbl_warehouse` WHERE `wh_id`='" . $whid . "'";
                                $rez = mysql_query($qqry);

                                $disMonth = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                $tempVar = $_POST['report_month'] - 1;
                                ?>
                                <!--<script type="text/javascript">fetchDistricts();</script>-->
                                <div style="font-size:12px; font-weight:bold; color:#F00; text-align:left">
                                    <?php
                                    if (mysql_num_rows($rez) > 0) {
                                        $qryRes = mysql_fetch_array($rez);
                                        echo "No data entered for $qryRes[wh_name]($qryRes[wh_type_id]) in $disMonth[$tempVar], $_POST[report_year].";
                                    } else {
                                        echo "No data entered in $disMonth[$tempVar], $_POST[report_year].";
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                
            </div>
        </div>
	</div>

    <!-- END FOOTER -->
	<?php include "../../plmis_inc/common/footer.php"; ?>
    
</body>
<!-- END BODY -->
</html>