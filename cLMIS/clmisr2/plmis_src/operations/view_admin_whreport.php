<?php
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
$myyear = date('Y', strtotime($date));
$sel_stk = '';
$sel_prov = '';
$sel_dist = '';
$sel_wh = '';
$where = '';

if (isset($_POST['submit'])) {
	if ( !empty($_REQUEST['report_month']) ){
		$sel_month = $_REQUEST['report_month'];
		$where[] = "tbl_wh_data.report_month = ".$_POST['report_month']." ";
		$where1[] = "MONTH (tbl_hf_data.reporting_date) = ".$_POST['report_month']." ";
		
	}if ( !empty($_REQUEST['report_year']) ){
		$sel_year = $_REQUEST['report_year'];
		$where[] = "tbl_wh_data.report_year = ".$_POST['report_year']." ";
		$where1[] = "YEAR (tbl_hf_data.reporting_date) = ".$_POST['report_year']." ";
	}
	if ( !empty($_REQUEST['stk_sel']) ){
		$sel_stk = $_REQUEST['stk_sel'];
		$where[] = "tbl_warehouse.stkid = ".$sel_stk." ";
		$where1[] = "tbl_warehouse.stkid = ".$sel_stk." ";
		$where2 .= " AND tbl_hf_type_rank.stakeholder_id = ".$sel_stk." ";
	}
	if ( !empty($_REQUEST['province']) ){
		$sel_prov = $_REQUEST['province'];
		$where[] = "tbl_warehouse.prov_id = ".$sel_prov." ";
		$where1[] = "tbl_warehouse.prov_id = ".$sel_prov." ";
		$where2 .= " AND tbl_hf_type_rank.province_id = ".$sel_prov." ";
	}
	if ( !empty($_REQUEST['district']) ){
		$sel_dist = $_REQUEST['district'];
		$where[] = "tbl_warehouse.dist_id = ".$_POST['district']." ";
		$where1[] = "tbl_warehouse.dist_id = ".$_POST['district']." ";
	}
	if ( !empty($_REQUEST['warehouse']) ){
		$sel_wh = $_REQUEST['warehouse'];
		$where[] = "tbl_warehouse.wh_id = ".$_POST['warehouse']." ";
		$where1[] = "tbl_warehouse.wh_id = ".$_POST['warehouse']." ";
	}
	$where = implode(' AND ', $where);
	$where1 = implode(' AND ', $where1);
	
	include("xml/xml_genaration_whreport.php");
	
    // Get Store name
    $getDist = mysql_fetch_array(mysql_query("SELECT
												tbl_warehouse.wh_name
											FROM
												tbl_warehouse
											WHERE
												tbl_warehouse.wh_id = '" . $sel_wh . "' "));
    $whName = $getDist['wh_name'];
    $whName = empty($whName) ? 'All' : $whName;
	
    // Get Stakeholder name
    $getStk = mysql_fetch_array(mysql_query("SELECT
												stakeholder.stkname
											FROM
												stakeholder
											WHERE
												stakeholder.stkid = '" . $sel_stk . "'"));
    $stkName = $getStk['stkname'];
    $stkName = empty($stkName) ? 'All' : $stkName;

    // Get District name
    $getDist = mysql_fetch_array(mysql_query("SELECT
												tbl_locations.LocName
											FROM
												tbl_locations
											WHERE
												tbl_locations.PkLocID = '" . $sel_dist . "'"));
    $distName = $getDist['LocName'];
    $distName = empty($distName) ? 'All' : $distName;

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
        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Monthly Store/Facility Report for Stakeholder = <?php echo "'$stkName'"; ?> Province = <?php echo "'$provName'"; ?> District = <?php echo "'$distName'"; ?> and Store/Facility = '<?php echo $whName; ?>' <?php echo "(" . date('F', mktime(0, 0, 0, $_POST['report_month'])) . ' ' . $_POST['report_year'] . ")"; ?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<span title='Province name'>Province</span>,<span title='District name'>District</span>,<span title='Stakeholder'>Stakeholder</span>,<span title='Store/Facility name'>Store/Facility</span>,<span title='Product name'>Product</span>,<span title='Opening Balance Calculated'>Opening Balance</span>,<span title='Balance received'>Received</span>,<span title='Balance issued'>Issued</span>,<span title='Adjustments'>Adjustments<span>,#cspan,<span title='Closing balance'>Closing Balance</span>,<span title='Last Modified'>Last Modified</span>");
        mygrid.attachHeader("#rspan,#rspan,#rspan,#rspan,#rspan,#rspan,#rspan,#rspan,<div style='text-align:center;'>(+),<div style='text-align:center;'>(-),#rspan,#rspan");
		mygrid.attachHeader("#select_filter,#select_filter,#select_filter,,#select_filter,,,,,,,");
        mygrid.setInitWidths("*,100,110,110,100,70,70,70,50,50,70,120");
        mygrid.setColAlign("left,left,left,left,left,right,right,right,right,right,right,center");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
        mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
        //mygrid.loadXML("xml/whreport.xml");
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }

</script>
<script>
	$(function(){
		showDistricts('<?php echo $sel_prov;?>', '<?php echo $sel_stk;?>');
		showStores('<?php echo $sel_dist;?>');
		
		$('#province, #stk_sel').change(function(e) {
            $('#district').html('<option value="">All</option>');
            $('#warehouse').html('<option value="">All</option>');
            showDistricts($('#province').val(), $('#stk_sel').val());
        });
		$('#stk_sel').change(function(e) {
            $('#warehouse').html('<option value="">All</option>');
        });
		
		$(document).on('change', '#province, #stk_sel, #district', function(){
			 showStores($('#district option:selected').val());
		})
	})
	function showDistricts(prov, stk) {
		if( stk != '' && prov != '' )
		{
			$.ajax({
				type: 'POST',
				url: 'ajax_calls.php',
				data: {provId: prov, stkId: stk, distId: '<?php echo $sel_dist;?>'},
				success: function(data) {
					$("#districts").html(data);
				}
			});
		}
	}
	function showStores(dist) {
		var stk = $('#stk_sel').val();
		if( stk != '' && dist != '' )
		{
			$.ajax({
				type: 'POST',
				url: 'ajax_calls.php',
				data: {distId: dist, stkId: stk, whId: '<?php echo $sel_wh;?>'},
				success: function(data) {
					$("#stores").html(data);
				}
			});
		}
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
                                                                <SELECT NAME="report_month" id="report_month" class="form-control input-sm" TABINDEX="3">
                                                                <?php
                                                                for ($i = 1; $i <= 12; $i++) {
                                                                    if ($sel_month == $i)
                                                                        $sel = "selected='selected'";
                                                                    elseif ($i == 1)
                                                                        $sel = "selected='selected'";
                                                                    else
                                                                        $sel = "";
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                                </SELECT>
                                                            </td>
                                                            <td class="col-md-2">
                                                            	<label class="control-label">Year</label>
                                                                <select name="report_year" id="report_year" class="form-control input-sm" tabindex="2">
                                                                    <?php
                                                                    $EndYear = 2012;
                                                                    $StartYear = date('Y');
                                                                    for ($i = $StartYear; $i >= $EndYear; $i--) {
                                                                        if ($i == $sel_year) {
                                                                            $chk4 = "Selected = 'Selected'";
                                                                        } else {
                                                                            $chk4 = "";
                                                                        }
                                                                        echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td class="col-md-2">
                                                            	<label class="control-label">Stakeholder</label>
                                                                <select name="stk_sel" id="stk_sel" class="form-control input-sm">
                                                                    <?php
                                                                    $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null AND stk_type_id IN (0, 1) order by stkorder";
                                                                    $rsstk = mysql_query($querystk) or die();
                                                                    while ($rowstk = mysql_fetch_array($rsstk)) {
                                                                        ?>
                                                                        <option value="<?php echo $rowstk['stkid']; ?>" <?php echo ($sel_stk == $rowstk['stkid']) ? 'selected=selected' : ''?>><?php echo $rowstk['stkname']; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td class="col-md-2">
                                                            <?php	
                                                                $qry = "SELECT
																			tbl_locations.PkLocID,
																			tbl_locations.LocName
																		FROM
																			tbl_locations
																		WHERE
																			tbl_locations.LocLvl = 2
																		AND tbl_locations.ParentID IS NOT NULL";
                                                                $qryRes = mysql_query($qry);
                                                                ?>
                                                                <label class="control-label">Province/Region</label>
                                                                <select name="province" id="province" class="form-control input-sm" required="required">
                                                                    <option value="">Select</option>
                                                                <?php
                                                                while ( $row = mysql_fetch_array($qryRes) )
                                                                {
                                                                ?>
                                                                    <option value="<?php echo $row['PkLocID'];?>" <?php echo ($sel_prov == $row['PkLocID']) ? 'selected=selected' : ''?>><?php echo $row['LocName'];?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                                </select>
                                                            </td>
                                                            <td id="districts" class="col-md-2">
                                                            	<label class="control-label">District</label>
                                                                <select name="district" id="district" class="form-control input-sm">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </td>
                                                            <td id="stores" class="col-md-2">
                                                            	<label class="control-label">Store/Facility</label>
                                                                <select name="warehouse" id="warehouse" class="form-control input-sm">
                                                                    <option value="">Select</option>
                                                                </select>
                                                            </td>
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
                            if ($numOfRows > 0) {
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