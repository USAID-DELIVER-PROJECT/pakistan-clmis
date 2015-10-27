<?php
/* * *********************************************************************************************************
  Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
  This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
  for editing the record.
  we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted
  data entered through add form and fourth save the data enterd from the edit form
  /********************************************************************************************************** */

include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $objwharehouse_user->m_npkId = $userid;
    $result = $objwharehouse_user->GetwhuserByIdc();
} else
    echo "user not login or timeout";

$objwharehouse_user->m_stk_id = $_SESSION['userdata'][7];
$objwharehouse_user->m_prov_id = $_SESSION['prov_id'];

if (date('d') > 10) {
    $date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
} else {
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
//////////// GET FILE NAME FROM THE URL

$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "../plmis_src/operations/" . $basename;

//////// GET Read Me Title From DB.

$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '" . $filePath . "' and active = 1"));
$readMeTitle = $qryResult['extra'];

$qryRes = mysql_fetch_array(mysql_query("SELECT * FROM `tbl_warehouse` WHERE `wh_id`='" . $_POST['districts'] . "'"));

$qryStkLevel = mysql_fetch_array(mysql_query("SELECT stakeholder.lvl FROM tbl_warehouse INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid WHERE tbl_warehouse.wh_id = '" . $_POST['districts'] . "'"));
if($qryStkLevel['lvl']==4) $strTitle = "Facility"; elseif($qryStkLevel['lvl']==3) $strTitle = "Store";
        
$disMonth = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$tempVar = $_POST['report_month'] - 1;
?>
<?php include "../plmis_inc/common/_header.php"; ?>
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../plmis_src/operations/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
<!--<script type="text/javascript" src="Scripts/jquery-1.7.min.js"></script>-->
<script>
    var mygrid;
    function doInitGrid() {
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold;'>Monthly <?php echo $strTitle; ?> Report for <?php echo $qryRes['wh_name']; ?> (<?php echo $disMonth[$tempVar] . ' ' . $_POST['report_year']; ?>)</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<span title='Product name'>Product</span>,<span title='Opening Balance Calculated'>Opening Balance</span>,<span title='Balance received'>Received</span>,<span title='Balance issued'>Issued</span>,<span title='Adjustments'>Adjustments<span>,#cspan,<span title='Closing balance'>Closing Balance</span>,<span title='Last Modified'>Last Modified</span>");
        mygrid.attachHeader(",,,,(+),(-),,");
        mygrid.setInitWidths("*,120,120,100,80,80,120,120");
        mygrid.setColAlign("left,right,right,right,right,right,right,right");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
        mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
        //mygrid.loadXML("xml/whreport.xml");
        mygrid.clearAll();
        mygrid.loadXMLString('<?php echo $xmlstore; ?>');
    }

</script>
<script type="text/javascript">
    function fetchDistricts() {
        var val;
        var temp = "<select class='form-control input-sm'><option value=>--- Select Province First---</option></select>";
        val = $('#provinces').val();
        if (val == 0) {
            $('#divdists').html(temp);
        } else if (val != 0) {
            var html = $.ajax({
                beforeSend: function() {
                },
                url: "fetchWh.php?pid=" + val,
                data: "pid=" + val,
                async: false,
                complete: function() {
                    //alert(val);
                    //window.open("fetchDistricts.php?pid="+val);
                }
            }).responseText;
            $('#divdists').html(html);
        }
    }
    // Form validate
    function formValidate() {
        var whId = $('#districts').val();
        if (whId == '') {
            alert('Please select Store/Facility');
            return false;
        }
    }

</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid();">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php
        include "../plmis_inc/common/top_im.php";
        include "../plmis_inc/common/_top.php";
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
                                            <form action="" method="post" onSubmit="return formValidate()">
                                                <div class="col-md-12">
                                                    <div class="col-md-2">
                                                        <div class="control-group">
                                                            <label class="control-label">Month</label>
                                                            <div class="controls">
                                                                <?php if ($_POST['submit']) { ?>
                                                                    <SELECT NAME="report_month" id="report_month" CLASS="sb1GeenGradientBoxMiddle form-control input-sm" TABINDEX="3">
                                                                        <OPTION VALUE="1" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '1') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?> >JANUARY</OPTION>
                                                                        <OPTION VALUE="2" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '2') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>FEBRUARY</OPTION>
                                                                        <OPTION VALUE="3" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '3') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MARCH</OPTION>
                                                                        <OPTION VALUE="4" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '4') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>APRIL</OPTION>
                                                                        <OPTION VALUE="5" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '5') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MAY</OPTION>
                                                                        <OPTION VALUE="6" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '6') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JUN</OPTION>
                                                                        <OPTION VALUE="7" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '7') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JULY</OPTION>
                                                                        <OPTION VALUE="8" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '8') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>AUGUST</OPTION>
                                                                        <OPTION VALUE="9" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '9') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>SEPTEMBER</OPTION>
                                                                        <OPTION VALUE="10" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '10') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>OCTOBER</OPTION>
                                                                        <OPTION VALUE="11" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '11') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>NOVEMBER</OPTION>
                                                                        <OPTION VALUE="12" <?php
                                                                        if ($_SESSION['filterParam']['month'] == '12') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>DECEMBER</OPTION>
                                                                    </SELECT>
                                                                <?php } else { ?>
                                                                    <SELECT NAME="report_month" id="report_month" CLASS="sb1GeenGradientBoxMiddle form-control input-sm" TABINDEX="3">
                                                                        <OPTION VALUE="1" <?php
                                                                        if ($mymonth == '01') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?> >JANUARY</OPTION>
                                                                        <OPTION VALUE="2" <?php
                                                                        if ($mymonth == '02') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>FEBRUARY</OPTION>
                                                                        <OPTION VALUE="3" <?php
                                                                        if ($mymonth == '03') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MARCH</OPTION>
                                                                        <OPTION VALUE="4" <?php
                                                                        if ($mymonth == '04') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>APRIL</OPTION>
                                                                        <OPTION VALUE="5" <?php
                                                                        if ($mymonth == '05') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>MAY</OPTION>
                                                                        <OPTION VALUE="6" <?php
                                                                        if ($mymonth == '06') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JUN</OPTION>
                                                                        <OPTION VALUE="7" <?php
                                                                        if ($mymonth == '07') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>JULY</OPTION>
                                                                        <OPTION VALUE="8" <?php
                                                                        if ($mymonth == '08') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>AUGUST</OPTION>
                                                                        <OPTION VALUE="9" <?php
                                                                        if ($mymonth == '09') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>SEPTEMBER</OPTION>
                                                                        <OPTION VALUE="10" <?php
                                                                        if ($mymonth == '10') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>OCTOBER</OPTION>
                                                                        <OPTION VALUE="11" <?php
                                                                        if ($mymonth == '11') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>NOVEMBER</OPTION>
                                                                        <OPTION VALUE="12" <?php
                                                                        if ($mymonth == '12') {
                                                                            echo $chk2 = "Selected = 'Selected'";
                                                                        }
                                                                        ?>>DECEMBER</OPTION>
                                                                    </SELECT>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="control-group">
                                                            <label class="control-label">Year</label>    
                                                            <div class="controls">
                                                                <?php if ($_POST['submit']) { ?>
                                                                    <select name="report_year" id="report_year" class="sb1GeenGradientBoxMiddle form-control input-sm" tabindex="2">
                                                                        <?php
                                                                        $EndYear = 2008;
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
                                                                    <select name="report_year" id="report_year" class="sb1GeenGradientBoxMiddle form-control input-sm" tabindex="2">
                                                                        <?php
                                                                        //$WHNameArray[]=$rs['wh_name'];

                                                                        $EndYear = 2008;
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="control-group">
                                                            <label class="control-label">Store/Facility</label>    
                                                            <div class="controls">
                                                                <select name="districts" id="districts" class="form-control input-sm">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $wh_id = $_REQUEST['districts'];
                                                                    $objwharehouse_user->m_npkId = $userid;
                                                                    $result1 = $objwharehouse_user->GetwhuserHFByIdc();
                                                                    if ($result1 != FALSE && mysql_num_rows($result1) > 0) {
                                                                        while ($row = mysql_fetch_array($result1)) {
                                                                            ?>
                                                                            <option <?php if ($row['wh_id'] == $wh_id) echo 'selected="selected"'; ?> value="<?php echo $row['wh_id']; ?>"><?php echo $row['wh_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
																	
                                                                    $result1 = $objwharehouse_user->GetwhuserByIdc();
                                                                    if ($result1 != FALSE && mysql_num_rows($result1) > 0) {
                                                                        while ($row = mysql_fetch_array($result1)) {
                                                                            ?>
                                                                            <option <?php if ($row['wh_id'] == $wh_id) echo 'selected="selected"'; ?> value="<?php echo $row['wh_id']; ?>"><?php echo $row['wh_name']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="control-group">
                                                            <label class="control-label">&nbsp;</label>    
                                                            <div class="controls">
                                                                <input type="submit" value="Go" name="submit" class="btn btn-primary input-sm"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                            <img title="Click here to export data to PDF file" style="cursor:pointer;" src="../images/pdf-32.png" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');"/>
                                            <img title="Click here to export data to Excel file" style="cursor:pointer;" src="../images/excel-32.png" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');"/>
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
                                $qryRes = mysql_fetch_array(mysql_query("SELECT * FROM `tbl_warehouse` WHERE `wh_id`='" . $_POST['districts'] . "'"));
                                $disMonth = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                                $tempVar = $_POST['report_month'] - 1;
                                ?>
                                <script type="text/javascript"></script>
                                <div style="font-size:12px; font-weight:bold; color:#F00; text-align:left"><?php
                                    if (!empty($_POST['districts'])) {
                                        echo "No data entered for $qryRes[wh_name]($qryRes[wh_type_id]) in $disMonth[$tempVar], $_POST[report_year].";
                                    } else {
                                        echo "No data entered in $disMonth[$tempVar], $_POST[report_year].";
                                    }
                                    ?> </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "../plmis_inc/common/footer.php"; ?>
</body>
<!-- END BODY -->
</html>