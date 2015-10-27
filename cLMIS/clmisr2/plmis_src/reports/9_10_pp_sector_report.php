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

if ( date('d') > 10 )
{
	$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
}
else
{
	$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
$sel_month = date('m', strtotime($date));
$sel_year = date('Y', strtotime($date));

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
            $('#provincesCol').parent().hide();
            $('#districtsCol').parent().hide();
        }
        if (val == 'provincial')
        {
            $('#provincesCol').show();
            $('#districtsCol').hide();
            $('#provincesCol').parent().show();
            $('#districtsCol').parent().hide();
            colId = 'provincesCol';
        }
        if (val == 'district' || val == 'field')
        {
            $('#provincesCol').show();
			$('#districtsCol').html('');
            $('#districtsCol').show();
            $('#provincesCol').parent().show();
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
            $('#districtsCol').parent().hide();
        }
        else
        {
            $('#districtsCol').show();
            $('#districtsCol1').show();
            $('#provincesCol').parent().show();
            $('#districtsCol').parent().show();
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
    <?php include "../../plmis_inc/common/top_im.php";
    include "../../plmis_inc/common/_top.php";?>


    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title row-br-b-wp">
                        <?php echo "Public Private Sector Report "; ?>
                        <span class="green-clr-txt"><?php echo ' '.$reportMonth.' '.$sel_year; ?></span>
                    </h3>
                	<div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Filter by</h3>
                        </div>
                        <div class="widget-body">
                            <form method="post" action="" id="searchfrm" name="searchfrm">
        						<div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label">Month</label>
                                                <div class="controls">
                                                    <select class="form-control input-sm" id="month_sel" name="month_sel" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++) {
                                                        ?>
                                                        <option value="<?php echo $i; ?>" <?php echo ($i == $sel_month) ? 'selected=selected' : '';?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label">Year</label>
                                                <div class="controls">
                                                    <select class="form-control input-sm" id="year_sel" name="year_sel" required>
                                                    <option value="">Select</option>
                                                    <?php
                                                    for ($j = date('Y'); $j >= 2010; $j--){
                                                        ?>
                                                        <option value="<?php echo $j; ?>" <?php echo ($j == $sel_year) ? 'selected=selected' : '';?>><?php echo $j; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label">Level</label>
                                                <div class="controls">
                                                    <select class="form-control input-sm" id="level_type" name="level_type" onChange="showProvinces()" required>
                                                    <option value="national" <?php echo ($lvl_type == national) ? 'selected=selected' : '';?>>National</option>
                                                    <option value="provincial" <?php echo ($lvl_type == provincial) ? 'selected=selected' : '';?>>Provincial</option>
                                                    <option value="district" <?php echo ($lvl_type == district) ? 'selected=selected' : '';?>>District</option>
                                                    <option value="field" <?php echo ($lvl_type == field) ? 'selected=selected' : '';?>>Field</option>
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="display:none;">
                                            <div class="control-group" id="provincesCol"></div>
                                        </div>
                                        <div class="col-md-2" style="display:none;">
                                            <div class="control-group" id="districtsCol"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label class="control-label">&nbsp;</label>
                                                <div class="controls">
                                                    <input type="submit" class="btn btn-primary input-sm" value="GO" id="go" name="go">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-md-12">
                    <?php
                    if(isset($_POST['go']))
                    {
                        ?>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="right" style="padding-right:5px;">
                                    <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                    <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="mygrid_container" style="width:100%; height:390px;"></div>
                                </td>
                            </tr>
                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
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
<?php include "../../plmis_inc/common/footer.php";?>

        <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>