<?php
include("../../html/adminhtml.inc.php");
Login();


$selMonth = date('m');
$selYear = date('Y');
$whType = '';
$skOfcLvl = '';
$provId = '';
$distId = '';
$tehsilId = '';
$num = '';

if (isset($_POST['submit'])) {
    //Posted Data Collection
    $selMonth = !empty($_REQUEST['ending_month']) ? $_REQUEST['ending_month'] : '';
    $selYear = !empty($_REQUEST['year_sel']) ? $_REQUEST['year_sel'] : '';
    $whType = !empty($_REQUEST['wh_type']) ? $_REQUEST['wh_type'] : '';
    $skOfcLvl = !empty($_REQUEST['SkOfcLvl']) ? $_REQUEST['SkOfcLvl'] : '';
    $provId = !empty($_REQUEST['province']) ? $_REQUEST['province'] : '';
    $distId = !empty($_REQUEST['district']) ? $_REQUEST['district'] : '';
    $tehsilId = !empty($_REQUEST['tehsil']) ? $_REQUEST['tehsil'] : '';

    // Filters
    $provFilter = (!empty($provId) && $provId != 'all') ? " AND tbl_warehouse.prov_id = $provId" : '';
    $distFilter = (!empty($distId) && $distId != 'all') ? " AND tbl_warehouse.dist_id = $distId" : '';
    $tehsilFilter = (!empty($tehsilId) && $tehsilId != 'all') ? " AND tbl_locations.PkLocID = $tehsilId" : '';

    // Central
    if ($whType == 1) {
        $qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        tbl_warehouse.wh_id,
                        tbl_warehouse.wh_name,
                        SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                        SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                        tbl_stock_master.TranNo
                    FROM
                        tbl_warehouse
                    INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                    INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                    WHERE
                        stakeholder.lvl = 1
                        AND stakeholder.stkid = 1 
                        AND tbl_stock_master.temp = 0
                        AND stakeholder.stk_type_id != 2
                    GROUP BY
                        tbl_warehouse.wh_id,
                        tbl_stock_master.TranNo
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        $report_title = 'Central ' . $report_title;
    }
    // Provincial
    else if ($whType == 2) {
        $qry = "SELECT 
                    wh_id,
                    wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        tbl_warehouse.wh_id,
                        tbl_warehouse.wh_name,
                        SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                        SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                        tbl_stock_master.TranNo
                    FROM
                        tbl_warehouse
                    INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                    INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                    WHERE
                        stakeholder.lvl = 2
                        AND tbl_stock_master.temp = 0
                        $provFilter
                    GROUP BY
                        tbl_warehouse.wh_id,
                        tbl_stock_master.TranNo
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        $report_title = 'Provincial ' . $report_title;
    }
    // District

    else if ($whType == 3) {
        $qry = "SELECT 
                    wh_id,
                    CONCAT(wh_name, '(', stkname, ')') AS wh_name,
                    SUM(stockIssue) AS stockIssue,
                    SUM(stockRcv) AS stockRcv
                FROM (
                    SELECT
                        tbl_warehouse.wh_id,
                        tbl_warehouse.wh_name,
						stakeholder.stkname,
                        SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                        SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                        tbl_stock_master.TranNo
                    FROM
                        tbl_warehouse
                    INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                    INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                    WHERE
                        stakeholder.lvl = 3
                        AND tbl_stock_master.temp = 0
                        $provFilter
                        $distFilter
                    GROUP BY
                        tbl_warehouse.wh_id,
                        tbl_stock_master.TranNo
                    )AS A 
                GROUP BY
                    wh_id
                ORDER BY
                    wh_name";
        $report_title = 'District ' . $report_title;
    }
    $qryRes = mysql_query($qry);

    $date = '';
    $endDate1 = $selYear . '-' . ($selMonth) . '-01';
    $endDate = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($endDate1))));
    $startDate = date('Y-m-d', strtotime("-5 month", strtotime($endDate1)));

    // Start date and End date
    //$startDate = '2013-11-01';
    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);
    $diff = $begin->diff($end);
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($begin, $interval, $end);

    $dataArr = array();
    $getWH = mysql_query($qry);
    $num = mysql_num_rows($getWH);

    if ($num > 0) {
        while ($row = mysql_fetch_array($getWH)) {
            $dataArr[$row['wh_id']][] = $row['wh_name'];
            $count = 1;
            foreach ($period as $date) {
                $dataArr[$row['wh_id']][$count] = 0;
                $count++;
                $dataArr[$row['wh_id']][$count] = 0;
                $count++;
            }
        }

        $header = '';
        $header1 = '#rspan';
        $cspan = '';
        $width = '*';
        $ro = 'ro';

        $header .= 'Store';
        foreach ($period as $date) {
            $cspan .= ',#cspan,#cspan';
            $header .= ',<span>' . $date->format("M-y") . '</span>,#cspan';
            $monthArr[] = $date->format("m-Y") . '|Receive';
            $monthArr[] = $date->format("m-Y") . '|Issue';
            $width .= ',60,60';
            $ro .= ',ro,ro';
            $header1 .= ',<span>Receive</span>, <span>Issue</span>';
        }

        $count = 1;
        foreach ($period as $date) {
            if ($whType == 1) {
                $getData = "SELECT 
                                wh_id,
                                wh_name,
                                SUM(stockIssue) AS stockIssue,
                                SUM(stockRcv) AS stockRcv
                            FROM (
                                SELECT
                                    tbl_warehouse.wh_id,
                                    tbl_warehouse.wh_name,
                                    SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                                    SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                                    tbl_stock_master.TranNo
                                FROM
                                    tbl_warehouse
                                INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                                INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                                WHERE
                                    stakeholder.lvl = 1
                                    AND stakeholder.stkid = 1
                                    AND tbl_stock_master.temp = 0
                                    AND stakeholder.stk_type_id != 2
                                    AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m' ) = '" . $date->format("Y-m") . "'
                                GROUP BY
                                    tbl_warehouse.wh_id,
                                    tbl_stock_master.TranNo
                                )AS A 
                            GROUP BY
                                wh_id
                            ORDER BY
                                wh_name";
            } else if ($whType == 2) {
                $getData = "SELECT 
                                wh_id,
                                wh_name,
                                SUM(stockIssue) AS stockIssue,
                                SUM(stockRcv) AS stockRcv
                            FROM (
                                SELECT
                                    tbl_warehouse.wh_id,
                                    tbl_warehouse.wh_name,
                                    SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                                    SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                                    tbl_stock_master.TranNo
                                FROM
                                    tbl_warehouse
                                INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                                INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                                WHERE
                                    stakeholder.lvl = 2
                                    AND tbl_stock_master.temp = 0
                                    AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m' ) = '" . $date->format("Y-m") . "'
                                    $provFilter
                                GROUP BY
                                    tbl_warehouse.wh_id,
                                    tbl_stock_master.TranNo
                                )AS A
                            GROUP BY
                                wh_id
                            ORDER BY
                                wh_name";
            } else if ($whType == 3) {
                $getData = "SELECT 
                                wh_id,
                                wh_name,
                                SUM(stockIssue) AS stockIssue,
                                SUM(stockRcv) AS stockRcv
                            FROM (
                                SELECT
                                    tbl_warehouse.wh_id,
                                    tbl_warehouse.wh_name,
                                    SUM(IF(tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 2, 1, 0)) AS stockIssue,
                                    SUM(IF(tbl_stock_master.WHIDTo = tbl_warehouse.wh_id && tbl_stock_master.TranTypeID = 1, 1, 0)) AS stockRcv,
                                    tbl_stock_master.TranNo
                                FROM
                                    tbl_warehouse
                                INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                                INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDFrom OR tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
                                WHERE
                                    stakeholder.lvl = 3
                                    AND tbl_stock_master.temp = 0
                                    AND DATE_FORMAT(tbl_stock_master.TranDate, '%Y-%m' ) = '" . $date->format("Y-m") . "'
                                    $provFilter
                                    $distFilter
                                GROUP BY
                                    tbl_warehouse.wh_id,
                                    tbl_stock_master.TranNo
                                )AS A
                            GROUP BY
                                wh_id
                            ORDER BY
                                wh_name";
            }

            $getDataRes = mysql_query($getData);
            while ($row = mysql_fetch_array($getDataRes)) {
                $dataArr[$row['wh_id']][$count] = $row['stockRcv'];
                $count1 = $count + 1;
                $dataArr[$row['wh_id']][$count1] = $row['stockIssue'];
            }
            $count = $count + 2;
        }

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        $sumArr = array();
        foreach ($dataArr as $disId => $subArr) {
            $xmlstore .= "<row>";

            $param = base64_encode($startDate . '|' . $endDate . '|' . $disId . '|' . $subArr[0]);
            $xmlstore .= "<cell><![CDATA[<a href=javascript:showGraph(\"$param\")>$subArr[0]</a>]]>^_self</cell>";

            foreach ($subArr as $key => $value) {
                if (!isset($sumArr[$key])) {
                    $sumArr[$key] = 0;
                }
                $sumArr[$key] += $value;

                if ($key > 0) {

                    $param = base64_encode($disId . '|' . $subArr[0] . '|' . $monthArr[$key - 1]);
                    if ($value > 0) {
                        $xmlstore .= "<cell><![CDATA[<a href=javascript:functionCall(\"$param\")>" . number_format($value) . "</a>]]>^_self</cell>";
                    } else {
                        $xmlstore .= "<cell>" . number_format($value) . "</cell>";
                    }
                }
            }
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";
    }
}
?>
<?php include "../../plmis_inc/common/_header.php";?>

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
<script>
    var mygrid;
    function doInitGrid()
    {
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo $report_title . ' As on ' . date('d/m/Y'); ?></div><?php echo $cspan; ?>");
        mygrid.attachHeader("<?php echo $header; ?>");
        mygrid.attachHeader("<?php echo $header1; ?>");
        mygrid.setInitWidths("<?php echo $width; ?>");
        mygrid.setColTypes("<?php echo $ro; ?>");
        mygrid.enableRowsHover(true, 'onMouseOver'); // `onMouseOver` is the css cla ss name.
        mygrid.setSkin("light");
        mygrid.init();
        //mygrid.loadXML("xml/central_wh_report.xml");
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');		
    }
    function functionCall(param)
    {
        window.open('transaction_detail.php?param=' + param, '_blank', 'scrollbars=1,width=842,height=595');
    }
    function showGraph(param)
    {
        window.open('../graph/stock.php?param=' + param, '_blank', 'scrollbars=1,width=950,height=600');
    }
</script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<div class="page-container">
    <?php include "../../plmis_inc/common/_top.php";?>
    <?php include "../../plmis_inc/common/top_im.php";?>


    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title row-br-b-wp">Shipment Report</h3>
                                  
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Filter by</h3>
                        </div>
                        <div class="widget-body">
							<form name="frm" id="frm" action="" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>Ending Month</label>
                                                <div class="controls">
                                                    <select name="ending_month" id="ending_month" class="form-control input-sm">
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++) {
                                                        if ($selMonth == $i) {
                                                            $sel = "selected='selected'";
                                                        } else if ($i == 1) {
                                                            $sel = "selected='selected'";
                                                        } else {
                                                            $sel = "";
                                                        }
                                                        ?>
                                                        <option value="<?php echo date('m', mktime(0, 0, 0, $i, 1)); ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
	                                    </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>Year</label>
                                                <div class="controls">
                                                    <select name="year_sel" id="year_sel" class="form-control input-sm">
														<?php
                                                        for ($j = date('Y'); $j >= 2010; $j--) {
                                                            if ($selYear == $j) {
                                                                $sel = "selected='selected'";
                                                            } else if ($j == 1) {
                                                                $sel = "selected='selected'";
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
	                                    </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>Office Type</label>
                                                <div class="controls">
                                                    <select name="wh_type" id="wh_type" class="form-control input-sm">
                                                        <option value="1" <?php echo ($whType == 1) ? 'selected="selected"' : '';?>>Central</option>
                                                        <option value="2" <?php echo ($whType == 2) ? 'selected="selected"' : '';?>>Provincial</option>
                                                        <option value="3" <?php echo ($whType == 3) ? 'selected="selected"' : '';?>>District</option>
                                                    </select>
                                                </div>
                                            </div>
	                                    </div>
                                        <div class="col-md-2" id="province_combo" style="display:none;">
                                            <div class="control-group">
                                                <label>Province/Region</label>
                                                <div class="controls">
                                                    <select name="province" id="province" class="form-control input-sm">
                                                        <option value="">Loading...</option>
                                                    </select>
                                                </div>
                                            </div>
	                                    </div>
                                        <div class="col-md-2" id="district_combo" style="display:none;">
                                            <div class="control-group">
                                                <label>District</label>
                                                <div class="controls">
                                                    <select name="district" id="district" class="form-control input-sm">
                                                        <option value="">Loading...</option>
                                                    </select>
                                                </div>
                                            </div>
	                                    </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                                <label>&nbsp;</label>
                                                <div class="controls">
                                                    <input type="submit" name="submit" id="go" value="GO" class="btn btn-primary input-sm" />
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
                if ($_REQUEST['submit'])
                {
                    if ( $num > 0 )
                    {
                        ?>
                        <table width="100%" cellpadding="0" cellspacing="0" id="myTable">
                            <tr>
                                <td style="text-align:right; padding-right:5px;">
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
                        echo '<h6>No record found.</h6>';
                    }
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

//writeXML('shipment.xml', $xmlstore);
?>
	<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
<!-- END JAVASCRIPTS -->
<script>
    $(function() {
<?php if ($_POST['submit']) { ?>
            officeType('<?php echo $whType; ?>');
            showProv('<?php echo $whType; ?>');
            showDistricts('<?php echo $provId; ?>');
<?php } ?>

        $('#wh_type').change(function() {
            var whType = $(this).val();
            officeType(whType);
        });
        $('#province').change(function() {
            var provId = $(this).val();
            if (provId != 'all')
            {
                showDistricts(provId);
            }
            else
            {
                $('#district_combo').fadeOut();
                $('#district').empty();
                $('#tehsil_combo').fadeOut();
                $('#tehsil').empty();
            }
        });
    });
    function officeType(whType)
    {
        if (whType == 1)
        {
            $('#province_combo').fadeOut();
            $('#province').empty();
            $('#district_combo').fadeOut();
            $('#district').empty();
            $('#tehsil_combo').fadeOut();
            $('#tehsil').empty();
        }
        else
        {
            if (whType == 2)
            {
                $('#district_combo').fadeOut();
                $('#district').empty();
                $('#tehsil_combo').fadeOut();
                $('#tehsil').empty();
            }
            else if (whType == 3)
            {
                $('#district_combo').fadeOut();
                $('#district').empty();
                $('#tehsil_combo').fadeOut();
                $('#tehsil').empty();
            }
            showProv(whType);
        }
    }
    function showProv(whType)
    {
        if (whType != 1)
        {
            $('#province_combo').fadeIn();
            $('#province').html('<option value="">Loading...</option>');
            $.ajax({
                type: "POST",
                url: "ajax_combos.php",
                data: {SkOfcLvl: whType, provSelId: '<?php echo $provId; ?>'},
                dataType: 'html',
                success: function(data)
                {
                    $('#province').html(data);
					var selProv = $('#province').val();
					if ( selProv != 'all' )
					{
						showDistricts(selProv);
					}
                }
            });
        }
    }
    function showDistricts(provId)
    {
        var whType = $('#wh_type').val();
        if (whType == 3)
        {
            $('#district_combo').fadeIn();
            $('#district').html('<option value="">Loading...</option>');
            $.ajax({
                type: "POST",
                url: "ajax_combos.php",
                data: {SkOfcLvl: whType, provId: provId, distSelId: '<?php echo $distId; ?>'},
                dataType: 'html',
                success: function(data)
                {
                    $('#district').html(data);
                }
            });
        }
    }
</script>
</body>
<!-- END BODY -->
</html>