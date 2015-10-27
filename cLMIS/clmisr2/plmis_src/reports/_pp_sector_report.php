<?php
//ob_start();
/***********************************************************************************************************
Developed by  Munir Ahmed
Email Id:    mnuniryousafzai@gmail.com
This is the file used to view the products details "Consumption, AMC, On Hand, MOS,	CYP". For viewing the details against a stakeholder, province or district.
The details are shown in a hirerchy i-e first of all it shows the product details against stakeholers, there from you select the stakeholder and then the product    details are shown against each province and then if user selects a province all the products details are shown against each district in that province.
/***********************************************************************************************************/
/* $report_id = "SNASUM";*/
$report_title = "Public Private Sector Report";
/*$actionpage = "";
$parameters = "T";
$parameter_width = "40%";*/


include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();



if(isset($_POST['go'])){

    if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
        $sel_month = $_POST['month_sel'];
    if (!empty($sel_month)){
        $reportMonth = date('F',mktime(0,0,0,$sel_month));
    }else {
        $reportMonth = "";
    }

    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];

    if(isset($_POST['level_type']) && !empty($_POST['level_type']))
        $lvl_type = $_POST['level_type'];

    if(isset($_POST['province']) && !empty($_POST['province']))
        $province = $_POST['province'];

    if(isset($_POST['district']) && !empty($_POST['district']))
        $district = $_POST['district'];


    // Get District name
    $getDist = mysql_fetch_array(mysql_query("SELECT
												tbl_locations.LocName
											FROM
												tbl_locations
											WHERE
												tbl_locations.PkLocID = '".$district."'"));
    $distName = $getDist['LocName'];

    // Get Province name
    $getProv = mysql_fetch_array(mysql_query("SELECT
												tbl_locations.LocName
											FROM
												tbl_locations
											WHERE
												tbl_locations.PkLocID = '".$province."'"));
    $provName = $getProv['LocName'];

    if ($lvl_type == 'national'){
        $title = 'National Level Public-Private Sector Report';
    }elseif ($lvl_type == 'provincial'){
        $title = "Provincial Level Public-Private Sector Report for Province = '$provName'";
    }elseif ($lvl_type == 'district'){
        $title = "District Level Public-Private Sector Report for Province = '$provName' and District = '$distName'";
    }elseif ($lvl_type == 'field'){
        $title = "Field Level Public-Private Sector Report for Province = '$provName' and District = '$distName'";
    }

}
?>

<?php include "../../plmis_inc/common/_header.php";?>
<script language="javascript">
    function frmvalidate(){
        if(document.getElementById('month_sel').value==''){
            alert('Please Select Month');
            document.getElementById('month_sel').focus();
            return false;
        }
        if(document.getElementById('year_sel').value==''){
            alert('Please Select Year');
            document.getElementById('year_sel').focus();
            return false;
        }
    }
    function functionCall(month, year, prod){
        window.location = "nationalreportSTK.php?month_sel="+month+"&year_sel="+year+"&item_sel="+prod;
    }
</script>
<link href="../../plmis_css/tab_menu.css" media="screen" rel="stylesheet" type="text/css" />
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>

<?php

if(isset($_POST['go']))
{
    $xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xmlstore .="<rows>\n";
    $queryPro = safe_query("SELECT itmrec_id,itm_name FROM `itminfo_tab` WHERE `itm_status`='Current' ORDER BY frmindex");
    $counter = 1;
    while($rsPro = mysql_fetch_array($queryPro))
    {
        $xmlstore .="\t<row id=\"$counter\">\n";

        /// If level type is national
        if ($lvl_type == 'national')
        {
            // For Public Sector
            $queryvals =  "SELECT REPgetData('CABMY','N','T','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
                include("incl_data_render_pp.php");
            }

            // For Private Sector
            $queryvals = "SELECT REPgetData('CABMY','S','X','$sel_month','$sel_year','".$rsPro['itmrec_id']."',0,0,0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                include("incl_data_render_pp.php");
            }

            $xmlstore .= "\t</row>\n";
        }

        /// If level type is provincial
        if ($lvl_type == 'provincial')
        {
            // For Public Sector
            $queryvals = "SELECT REPgetData('CABMY','R','TP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
                include("incl_data_render_pp.php");
            }

            // For Private Sector
            $queryvals = "SELECT REPgetData('CABMY','R','TSP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                include("incl_data_render_pp.php");
            }

            $xmlstore .= "\t</row>\n";
        }

        /// If level type is District
        if ($lvl_type == 'district')
        {
            // For Public Sector
            $queryvals = "SELECT REPgetData('CABMY','R','TPD','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',$district) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
                include("incl_data_render_pp.php");
            }

            // For Private Sector
            $queryvals = "SELECT REPgetData('CABMY','R','XPD','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',$district) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                include("incl_data_render_pp.php");
            }

            $xmlstore .= "\t</row>\n";
        }

        /// If level type is Field
        if ($lvl_type == 'field')
        {
            // For Public Sector
            $queryvals = "SELECT REPgetData('CABMY','R','FP','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                $xmlstore .="\t\t<cell>$rsPro[itm_name]</cell>\n";
                include("incl_data_render_pp.php");
            }

            // For Private Sector
            $queryvals = "SELECT REPgetData('CABMY','R','X','$sel_month','$sel_year','".$rsPro['itmrec_id']."','0','".$province."',0) AS Value FROM DUAL";
            $rsvals = mysql_query($queryvals) or die(mysql_error());
            while($rowvals = mysql_fetch_array($rsvals)) {

                $monthNum = "\"$sel_month\"";
                $yearNum = "\"$sel_year\"";
                $productName = "\"$rsPro[itmrec_id]\"";

                $tmp = explode('*',$rowvals['Value']);
                $sel_item = $rsPro['itmrec_id'];
                $sel_stk = 0;
                $sel_lvl = 1;

                include("incl_data_render_pp.php");
            }

            $xmlstore .= "\t</row>\n";
        }



        $counter++;
    }
    $xmlstore .="</rows>\n";
}
?>
<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");

        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo $title.' ('. date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?> </div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");

        mygrid.attachHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Product</div>,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Public</div>,#cspan,#cspan,#cspan,#cspan,<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Private</div>,#cspan,#cspan,#cspan,#cspan");

        mygrid.attachHeader("<span></span>,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan,<span title='Product Consumption'>Consumption</span>,<span title='Average Monthly Consumption'>AMC</span>,<span title='Product On Hand'>On Hand</span>,<span title='Month of Scale'>MOS</span>,#cspan");

        mygrid.setInitWidths("*,100,100,100,40,40,100,100,100,40,40");
        mygrid.setColAlign("left,right,right,right,center,center,right,right,right,center,center");
        //mygrid.setColSorting("str,int");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
        //mygrid.enableLightMouseNavigation(true);
        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.

        mygrid.setSkin("light");
        mygrid.init();
        mygrid.loadXML("xml/pp_sector_report.xml");
    }

</script>
<script>
    $(document).ready(function() {
        showProvinces();
    });
    function showProvinces()
    {
        var val = $('#level_type').val();
        var colId;
        var selectedVal = '';
        if (val == 'national')
        {
            $('#provincesCol').hide();
            $('#districtsCol').hide();
        }
        if (val == 'provincial')
        {
            $('#provincesCol').show();
            $('#districtsCol').hide();
            colId = 'provincesCol';
        }
        if (val == 'district' || val == 'field')
        {
            $('#provincesCol').show();$('#districtsCol').html('');
            $('#districtsCol').show();
            colId = 'provincesCol';
        }
        if (val == 'provincial' || val == 'district' || val == 'field')
        {
        <?php
        //if ( $_POST['go'] ){
        ?>
            selectedVal = "pId=<?php echo $province;?>";
        <?php
        //}
        ?>

            $.ajax({
                type: 'POST',
                url: 'ajax_calls.php',
                data: "val="+val+"&"+selectedVal,
                success: function(data) {
                    $("#"+colId).html(data);
                }
            });

        <?php
        if ( $_POST['go'] ){
            ?>
            showDistricts('<?php echo $province;?>');
            <?php
        }
        ?>

            /*if ( $('#province').val() != '' )
            {
                showDistricts();
            }*/
        }
    }

    function showDistricts(provId)
    {
        if ( $('#level_type').val() == 'provincial' )
        {
            $('#districtsCol').hide();
        }
        else
        {
            $('#districtsCol').show();
            $('#districtsCol1').show();
            //var val = $('#province').val();
            valStk=$('#stk_sel').val();

            var data = '';
            //var provinceId = (provId == '') ? val : provId;
            data = "dId=<?php echo $district;?>&provinceId="+provId;

            $.ajax({
                type: 'POST',
                url: 'ajax_calls.php',
                data: data,
                success: function(data) {
                    $("#districtsCol").html(data);
                }
            });
        }
    }

    // Form validation
    function formValidate()
    {
        if( $('#month_sel').val() == '' )
        {
            alert('Select month.');
            $('#month_sel').focus();
            $('#month_sel').css('border', '1px solid red');
            return false;
        }else
        {
            $('#month_sel').css('border', '1px solid #D1D1D1');
        }
        if( $('#year_sel').val() == '' )
        {
            alert('Select year.');
            $('#year_sel').focus();
            $('#year_sel').css('border', '1px solid red');
            return false;
        }else
        {
            $('#year_sel').css('border', '1px solid #D1D1D1');
        }
        if( $('#level_type').val() == '' )
        {
            alert('Select level.');
            $('#level_type').focus();
            $('#level_type').css('border', '1px solid red');
            return false;
        }
        else
        {
            $('#level_type').css('border', '1px solid #D1D1D1');
        }
        if( $('#level_type').val() != '' )
        {
            if( $('#province').val() == '' )
            {
                alert('Select province.');
                $('#province').focus();
                $('#province').css('border', '1px solid red');
                return false;
            }
            else
            {
                $('#province').css('border', '1px solid #D1D1D1');
            }
        }
    }

</script>

</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php include "../../plmis_inc/common/_top.php";?>


    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- BEGIN PAGE HEADER-->
            <div class="row">

                <div class="row">
                    <div class="col-md-12">
                        <div class="body_sec">

                            <div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
                            <div class="wrraper" style="height:auto; padding-left:5px">
                                <div class="content" align=""><br>
                                    <?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
                                    <table width="100%">
                                        <tr>
                                            <td colspan="2">
                                                <?php //include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>



                                                <form method="post" action="" id="searchfrm" name="searchfrm">
                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tbody>
                                                        <tr height="34">
                                                            <td align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; height:34px; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; font-size:14px;" colspan="2"><? echo 'Public Private Sector Report'.' '.$reportMonth.' '.$sel_year;?></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top:-2px">
                                                        <tbody>
                                                        <tr bgcolor="#FFFFFF">
                                                            <td style="padding-right:20px; padding-top: 10px;font-family: Arial, Verdana, Helvetica, sans-serif; 	color: #444444; 	font-size: 12px;" colspan="2"><br><span class="sb1NormalFont">MOS: </span><div style="display:inline-block;margin-left:5px;">Stock Out</div><div style="display:inline-block;width:15px; height:12px; background-color:#ff370f;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Under Stock</div><div style="display:inline-block;width:15px; height:12px; background-color:#0000ff;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Satisfactory</div><div style="display:inline-block;width:15px; height:12px; background-color:#008000;margin-left:5px;"></div> <div style="display:inline-block;margin-left:5px;">Over Stock</div><div style="display:inline-block;width:15px; height:12px; background-color:#6bceff;margin-left:5px;"></div> <br><br>   </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" bgcolor="#FFFFFF" colspan="2">
                                                                <table style="width:auto" cellspacing="3" cellpadding="3" border="0">
                                                                    <tbody>
                                                                    <tr bgcolor="#FFFFFF">
                                                                        <td width="100px" bgcolor="#FFFFFF" class="sb1NormalFont"><strong>Filter by</strong></td>
                                                                        <td bgcolor="#FFFFFF">
                                                                            <span class="sb1NormalFont">Month</span>
                                                                            <select class="input_select" id="month_sel" name="month_sel" required>
                                                                                <option value="">Select</option>
                                                                                <?php
                                                                                for ($i = 1; $i <= 12; $i++) {
                                                                                    ?>
                                                                                    <option value="<?php echo $i; ?>" <?php echo ($i == $sel_month) ? 'selected=selected' : '';?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td bgcolor="#FFFFFF">
                                                                            <span class="sb1NormalFont">Year</span>
                                                                            <select class="input_select" id="year_sel" name="year_sel" required>
                                                                                <option value="">Select</option>
                                                                                <?php
                                                                                for ($j = date('Y'); $j >= 2010; $j--){
                                                                                    ?>
                                                                                    <option value="<?php echo $j; ?>" <?php echo ($j == $sel_year) ? 'selected=selected' : '';?>><?php echo $j; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                        <td bgcolor="#FFFFFF">
                                                                            <span class="sb1NormalFont">Level</span>
                                                                            <select class="input_select" id="level_type" name="level_type" onChange="showProvinces()" required>
                                                                                <option value="national" <?php echo ($lvl_type == national) ? 'selected=selected' : '';?>>National</option>
                                                                                <option value="provincial" <?php echo ($lvl_type == provincial) ? 'selected=selected' : '';?>>Provincial</option>
                                                                                <option value="district" <?php echo ($lvl_type == district) ? 'selected=selected' : '';?>>District</option>
                                                                                <option value="field" <?php echo ($lvl_type == field) ? 'selected=selected' : '';?>>Field</option>
                                                                            </select>
                                                                        </td>
                                                                        <td id="provincesCol" bgcolor="#FFFFFF" style="display:none;"></td>
                                                                        <td id="districtsCol" bgcolor="#FFFFFF" style="display:none;"></td>
                                                                        <td bgcolor="#FFFFFF"><input type="submit" class="input_button" style="margin-top:15px;" value="GO" id="go" name="go"></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </form>



                                            </td>
                                        </tr>
                                        <?php
                                        if(isset($_POST['go']))
                                        {
                                            ?>
                                            <tr>
                                                <td align="right">
                                                    <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" title="Export to PDF"/>
                                                    <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" title="Export to Excel" />
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>

                                    <?php
                                    if(isset($_POST['go']))
                                    {
                                        ?>
                                        <table width="99%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <div id="mygrid_container" style="width:100%; height:430px; background-color:white;"></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                            //XML write function
                            function writeXML($xmlfile, $xmlData)
                            {
                                $xmlfile_path= REPORT_XML_PATH."/".$xmlfile;
                                $handle = fopen($xmlfile_path, 'w');
                                fwrite($handle, $xmlData);
                                //print "wasif".$xmlfile_path;
                            }

                            if(isset($_POST['go']))
                            {
                                writeXML('pp_sector_report.xml', $xmlstore);
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <div class="page-footer">
            <div class="page-footer-inner">
                2014 &copy; Metronic by keenthemes.
            </div>
            <div class="page-footer-tools">
		<span class="go-top">
		<i class="fa fa-angle-up"></i>
		</span>
            </div>
        </div>
        <!-- END FOOTER -->
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <!-- BEGIN CORE PLUGINS -->
        <!--[if lt IE 9]>
        <script src="../../assets/global/plugins/respond.min.js"></script>
        <script src="../../assets/global/plugins/excanvas.min.js"></script>
        <![endif]-->
        <script src="../../assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
        <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
        <script src="../../assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="../../assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
        <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
        <script src="../../assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="../../assets/global/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
        <script src="../../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
        <script src="../../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
        <script src="../../assets/admin/pages/scripts/index.js" type="text/javascript"></script>
        <script src="../../assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <script>
            jQuery(document).ready(function() {
                Metronic.init(); // init metronic core componets
                Layout.init(); // init layout
                QuickSidebar.init() // init quick sidebar
                Index.init();
                Index.initDashboardDaterange();
                Index.initJQVMAP(); // init index page's custom scripts
                Index.initCalendar(); // init index page's custom scripts
                Index.initCharts(); // init index page's custom scripts
                Index.initChat();
                Index.initMiniCharts();
                Index.initIntro();
                Tasks.initDashboardWidget();
            });
        </script>
        <script>
            $(function(){
                $('#sector').change(function(e) {
                    var val = $('#sector').val();
                    getStakeholder(val, '');
                });
                getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
            })
        </script>
        <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

<style>
    .input_select{
        border:#D1D1D1 1px solid;
        color:#474747;
        font-family:Arial, Helvetica, sans-serif;
        font-size:11px;
        height:24px;
        max-width:150px;
    }

    .input_button{
        border:#D1D1D1 1px solid;
        background-color:#006700;
        color:#FFFFFF;
        height:25px;
        font-family:Arial, Helvetica, sans-serif;
        vertical-align:bottom;
        width:60px;
    }
    .sb1NormalFont{display:block;}
</style>

