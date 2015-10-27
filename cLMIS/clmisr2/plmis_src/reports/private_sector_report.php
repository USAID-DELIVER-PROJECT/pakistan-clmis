<?php
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();

if(isset($_POST['go'])){
	
    if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
        $sel_year = $_POST['year_sel'];
    if(isset($_POST['stk_sel']) && !empty($_POST['stk_sel']))
        $sel_stk = $_POST['stk_sel'];
    if(isset($_POST['repIndicators']) && !empty($_POST['repIndicators']))
        $sel_indicator = $_POST['repIndicators'];
	
	if ( $sel_stk != 'all' ){
		$stkFilter = " AND tbl_warehouse.stkid = $sel_stk";
	}else{
		$stkFilter = " AND stakeholder.stk_type_id = 1";
	}
	
	// Check indicator
    if ($sel_indicator == 1){
        $ind = "\'Consumption\'";
		$colName = 'SUM(tbl_wh_data.wh_issue_up) AS total';
		$lvlFilter = " AND stakeholder.lvl = 4";
    }else if ($sel_indicator == 2){
        $ind = "\'Stock on Hand\'";
		$colName = 'SUM(tbl_wh_data.wh_cbl_a) AS total';
		$lvlFilter = " AND stakeholder.lvl >= 2";
    }else if ($sel_indicator == 3){
        $ind = "\'Received\'";
		$colName = 'SUM(tbl_wh_data.wh_received) AS total';
		$lvlFilter = " AND stakeholder.lvl = 4";
    }
	
	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	
	$qry = "SELECT
				itminfo_tab.itm_name,
				tbl_wh_data.RptDate,
				$colName
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
			INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			$stkFilter
			$lvlFilter
			AND itminfo_tab.itm_category = 1
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";

} else {
	if ( date('d') > 10 )
	{
		$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
	}
	else
	{
		$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
	}
	$sel_year = date('Y', strtotime($date));
    $sel_item = "IT-001";
    $Stkid = "";
    $sel_stk = ($_SESSION['userdata'][12] == 1) ? $_SESSION['userdata'][7] : 'all';
    $stkFilter = ($sel_stk != 'all') ? " AND tbl_warehouse.stkid = $sel_stk" : ' AND stakeholder.stk_type_id = 1';
	$startDate = $sel_year.'-01-01';
	$endDate = $sel_year.'-12-01';
	$endDate1 = ($sel_year + 1).'-01-01';
	
	$qry = "SELECT
				itminfo_tab.itm_name,
				tbl_wh_data.RptDate,
				SUM(tbl_wh_data.wh_issue_up) AS total
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
			INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
			INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
			WHERE
				stakeholder.lvl = 4
			AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			AND itminfo_tab.itm_category = 1
			$stkFilter
			GROUP BY
				tbl_wh_data.RptDate,
				tbl_wh_data.item_id
			ORDER BY
				itminfo_tab.frmindex ASC";

    $ind = "\'Consumption\'";

}

if($sel_stk==0){
    $in_type   = 'N';
    $in_stk    = 0;
} else {
    $in_type   = 'S';
    $in_id     = $sel_stk;
    $in_stk    = $sel_stk;
}
$in_year   = $sel_year;

// Execute uery
$qryRes = mysql_query($qry);
$num = mysql_num_rows(mysql_query($qry));
$data = array();
while ( $row = mysql_fetch_array($qryRes) )
{
	$data[$row['itm_name']][$row['RptDate']] = $row['total'];
}

// Create XML
$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .="<rows>";

$begin = new DateTime($startDate);
$end = new DateTime($endDate1);
$diff = $begin->diff($end);
$totalMonths = (($diff->format('%y') * 12) + $diff->format('%m'));
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
foreach( $data as $itemName=>$prodData )
{
	
	$xmlstore .= "<row>";
	$xmlstore .= "<cell><![CDATA[".$itemName."]]></cell>";
	foreach ($period as $date)
	{
		$xmlstore .= "<cell>".number_format($prodData[$date->format( "Y-m-d" )])."</cell>";
	}
	$xmlstore .= "</row>";
}

$xmlstore .="</rows>";

////////  Stakeholders for Grid Header
if ($sel_stk == 'all'){
    $stkName = "\'All\'";
}else{
    $stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$sel_stk."' "));
    $stkName = "\'$stakeNameQryRes[stkname]\'";
}

?>
<?php include "../../plmis_inc/common/_header.php";?>
<style>.tdpadding{ padding-left: 10px !important;}</style>
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
<link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
<link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

<script type="text/javascript">
    function func(){
        var val = $('#stk_sel').val();

        if(val == 2){
            $('#ppiuList').show("slow");
            $('#ppiuList1').show("slow");
        }else {
            $('#ppiuList').hide("slow");
            $('#ppiuList1').hide("slow");
        }
    }
</script>

<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
<script src='../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
<script src="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center;'><?php echo "Private Sector Yearly Report for Stakeholder(s) = $stkName And Indicator = $ind (".$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("Product, Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec");
        mygrid.setInitWidths("*,60,60,60,60,60,60,60,60,60,60,60,60");
        mygrid.setColAlign("left,right,right,right,right,right,right,right,right,right,right,right,right");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }
</script>
</head>

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
                        <?php echo "Private Sector Yearly Report for "; ?>
                        <span class="green-clr-txt"><?php echo $sel_year;?></span>
                    </h3>
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Filter by</h3>
                        </div>
                        <div class="widget-body">
                            <form method="post" action="" id="searchfrm" name="searchfrm">
                                <table cellspacing="3" cellpadding="3" border="0">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-2">
                                                <label class="control-label">Year</label>
                                                <select class="form-control input-sm" id="year_sel" name="year_sel">
                                                    <?php
                                                    for ($j = date('Y'); $j >= 2010; $j--) {
                                                        if ($sel_year == $j)
                                                            $sel = "selected='selected'";
                                                        else if ($j == date("Y"))
                                                            $sel = "selected='selected'";
                                                        else
                                                            $sel = "";
                                                        ?>
                                                        <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <!-- Stakeholder -->
                                            <td class="col-md-2">
                                                <label class="control-label">Stakeholder</label>
                                                <?php
                                                /// Get private Sectors Stakeholders
                                                $pvtQry = mysql_query("SELECT stkid, stkname FROM stakeholder WHERE stk_type_id = 1 and parentid is null GROUP BY stkid");
                                                ?>
                                                <select class="form-control input-sm" id="stk_sel" name="stk_sel">
                                                    <option value="all">All</option>
                                                    <?php while($pvtRow = mysql_fetch_array($pvtQry)){?>
                                                    <option value="<?php echo $pvtRow['stkid'];?>" <?php if ($pvtRow['stkid'] == $sel_stk){echo "selected=selected";}?>><?php echo $pvtRow['stkname'];?></option>
                                                    <?php }?>
                                                </select>
                                            </td>
                                            <td class="col-md-2">
                                                <label class="control-label">Indicator</label>
                                                <select id="repIndicators" name="repIndicators" class="form-control input-sm">
                                                    <option value="1"<?php if ($_POST['repIndicators'] == 1){echo "selected=selected";}?>>Consumption</option>
                                                    <option value="2"<?php if ($_POST['repIndicators'] == 2){echo "selected=selected";}?>>Stock on Hand</option>
                                                    <option value="3"<?php if ($_POST['repIndicators'] == 3){echo "selected=selected";}?>>Received</option>
                                                </select>
                                            </td>
                                            <td class="col-md-2"><input type="submit" class="btn btn-primary input-sm" value="GO" id="go" name="go" style="margin-left: 15px; margin-top:28px;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
						</div>
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-md-12">
                <?php
				if ( $num > 0 )
				{?>
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
				else
				{
					echo "No record found.";
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
}

//writeXML('private_sector.xml', $xmlstore);
?>
<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
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