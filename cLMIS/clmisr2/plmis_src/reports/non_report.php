<?php
include("../../html/adminhtml.inc.php");
Login();

$report_id = "SNONREPDIST";
$actionpage = "non_report.php";
$parameters = "TSP";

// If form is submitted
if(isset($_REQUEST['month_sel']))
{
    $sel_month = $_REQUEST['month_sel'];
    $sel_year = $_REQUEST['year_sel'];
    $sel_stk = $_REQUEST['stk_sel'];
    $sel_prov = $_REQUEST['prov_sel'];
    $sel_district = $_REQUEST['district'];
    $sel_report_type = $_REQUEST['rptType'];
    $sel_lvl_type = $_REQUEST['lvl_type'];

    if($sel_prov != 'all'){
        $prov_filter = "AND tbl_warehouse.prov_id = ".$sel_prov;
	}
    if($sel_stk != 'all'){
        $stk_filter = "AND tbl_warehouse.stkid = ".$sel_stk;
	}
    if($sel_district != ''){
        $dist_filter = "AND tbl_warehouse.dist_id = ".$sel_district;
	}
	
	if ($sel_lvl_type == 'all'){
		$lvl_filter = ' AND stakeholder.lvl IN (3, 4, 7)';
	}else if ($sel_lvl_type == 'df'){
		$lvl_filter = ' AND stakeholder.lvl IN (3, 4)';
	}else{
		$lvl_filter = "AND stakeholder.lvl = $sel_lvl_type";
	}
}
else
{
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
    $sel_report_type = 'reported';
    $sel_lvl_type = '3';
    $sel_stk = $_SESSION['userdata'][7];
    $sel_prov = ($_SESSION['userdata'][0] == 2054) ? 1 : $_SESSION['userdata'][10];
    $prov_filter = ($sel_prov != 10) ? (" AND tbl_warehouse.prov_id = ".$sel_prov) : '';
    $stk_filter = " AND tbl_warehouse.stkid = ".$sel_stk;
	$lvl_filter = ' AND stakeholder.lvl = 3';
    $_POST['rptType'] = 'reported';
	$sel_prov = ($sel_prov != 10) ? $sel_prov : 'all';
}

// Make Query and Execute
$newDate = date('Y-m-16', strtotime("+1 month", strtotime(date($sel_year.'-'.$sel_month))));
if ( isset($sel_report_type) && $sel_report_type == 'ontime' ){
    $reportingAnd = " AND DATE_FORMAT(tbl_wh_data.add_date, '%Y-%m-%d') <= '$newDate' ";
    $reportingAnd1 = " AND DATE_FORMAT(tbl_hf_data.created_date, '%Y-%m-%d') <= '$newDate' ";
}else if ( isset($sel_report_type) && $sel_report_type == 'late' ){
    $reportingAnd = " AND DATE_FORMAT(tbl_wh_data.add_date, '%Y-%m-%d') > '$newDate' ";
    $reportingAnd1 = " AND DATE_FORMAT(tbl_hf_data.created_date, '%Y-%m-%d') > '$newDate' ";
}

$qry = "SELECT
			B.provinceId,
			B.province,
			B.districtId,
			B.district,
			B.stkMain,
			B.stkOffice,
			B.wh_id,
			B.wh_name,
			B.wh_rank,
			DATE_FORMAT(A.add_date, '%Y-%m-%d') AS add_date,
			CONCAT(DATE_FORMAT(A.last_update, '%d/%m/%Y'), ' ', TIME_FORMAT(A.last_update, '%h:%i:%s %p'))AS last_update,
			A.ip_address
		FROM
			(
				SELECT
					tbl_warehouse.wh_id,
					tbl_warehouse.wh_name,
					tbl_warehouse.dist_id,
					tbl_warehouse.prov_id,
					tbl_warehouse.stkid,
					tbl_warehouse.stkofficeid,
					tbl_warehouse.wh_rank,
					tbl_wh_data.add_date,
					tbl_wh_data.last_update,
					tbl_wh_data.ip_address
				FROM
					tbl_warehouse
				INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				WHERE
					tbl_wh_data.report_month = $sel_month
				AND tbl_wh_data.report_year = $sel_year
				AND tbl_wh_data.item_id = 'IT-001'
				$stk_filter
				$prov_filter
				$dist_filter
				$lvl_filter
				GROUP BY
					tbl_warehouse.wh_id
				UNION
					SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_warehouse.dist_id,
						tbl_warehouse.prov_id,
						tbl_warehouse.stkid,
						tbl_warehouse.stkofficeid,
						tbl_warehouse.wh_rank,
						tbl_hf_data.created_date AS add_date,
						tbl_hf_data.last_update,
						tbl_hf_data.ip_address
					FROM
						tbl_warehouse
					INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
					INNER JOIN tbl_hf_data ON tbl_warehouse.wh_id = tbl_hf_data.warehouse_id
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					WHERE
						MONTH(tbl_hf_data.reporting_date) = $sel_month
					AND YEAR(tbl_hf_data.reporting_date) = $sel_year
					AND tbl_hf_data.item_id = 1
					$stk_filter
					$prov_filter
					$dist_filter
					$lvl_filter
					GROUP BY
						tbl_warehouse.wh_id
			) A
		RIGHT JOIN (
			SELECT
				DISTINCT
				tbl_warehouse.wh_id,
				tbl_warehouse.wh_name,
				tbl_warehouse.dist_id,
				tbl_warehouse.prov_id,
				tbl_warehouse.stkid,
				tbl_warehouse.stkofficeid,
				tbl_warehouse.wh_rank,
				MainStk.stkorder,
				MainStk.stkname AS stkMain,
				stakeholder.stkname AS stkOffice,
				District.PkLocID AS districtId,
				District.LocName AS district,
				Province.PkLocID AS provinceId,
				Province.LocName AS province
			FROM
				tbl_warehouse
			INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN stakeholder AS MainStk ON tbl_warehouse.stkid = MainStk.stkid
			INNER JOIN tbl_locations AS District ON tbl_warehouse.dist_id = District.PkLocID
			INNER JOIN tbl_locations AS Province ON tbl_warehouse.prov_id = Province.PkLocID
			WHERE
				1 = 1
				$stk_filter
				$prov_filter
				$dist_filter
				$lvl_filter
		) B ON A.wh_id = B.wh_id
		AND A.prov_id = B.prov_id
		AND A.dist_id = B.dist_id
		AND A.stkid = B.stkid
		AND A.stkofficeid = B.stkofficeid
		ORDER BY
			B.provinceId ASC,
			B.district ASC,
			B.stkorder ASC,
			IF(A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
			A.wh_rank,
			A.wh_name ASC";
$qryRes = mysql_query($qry);
$count = 1;
$data = array();
$totalWH = 0;
$ontime = 0;
$late = 0;
$reportedAll = 0;
$nonReported = 0;
while ( $row = mysql_fetch_assoc($qryRes) )
{
	$totalWH++;
	if(!empty($row['add_date'])){ // Reported Count (Ontime and Late)
		$data['reported'][] = $row;
		$reportedAll++;
	}
	
	if(!empty($row['add_date']) && $row['add_date'] < $newDate){ // Ontime Reported
		$data['ontime'][] = $row;
		$ontime++;
	}else if(!empty($row['add_date']) && $row['add_date'] >= $newDate){ // Late Reported
		$data['late'][] = $row;
		$late++;
	}
	
	if(empty($row['add_date'])){ // Non-Reported Count
		$data['non-reported'][] = $row;
		$nonReported++;
	}
}


// Create XML for the Grid
$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
$xmlstore .= "<rows>";
if ( $sel_report_type == 'reported' ){
	$dataArr = $data['reported'];
	$report_title = "All Reported Stores/Facilities Report for";
	if($row['add_date'] < $newDate){
		$color = 'color:#009900;';
		$rpt_status = 'On-time';
	}else{
		$color = 'color:#FF0000;';
		$rpt_status = 'Late';
	}
}else if ( $sel_report_type == 'ontime' ){
	$dataArr = $data['ontime'];
	$report_title = "On-time Reported Stores/Facilities Report for";
	$color = 'color:#009900;';
    $rpt_status = 'Late';
}else if ( $sel_report_type == 'late' ){
	$dataArr = $data['late'];
	$report_title = "Late Reported Stores/Facilities Report for";
	$color = 'color:#FF0000;';
    $rpt_status = 'Late';
}else if ( $sel_report_type == 'non-reported' ){
	$dataArr = $data['non-reported'];
	$report_title = "Non-reported Stores/Facilities Report for";
	$color = 'color:#FF0000;';
    $rpt_status = 'Non-reported';
}

$num = count($dataArr);

foreach( $dataArr as $row )
{
	$stkName = $row['stkMain'];
	$provinceName = $row['province'];
	
	if ( $sel_report_type == 'reported' ){
		if($row['add_date'] < $newDate){
			$color = 'color:#009900;';
			$rpt_status = 'On-time';
		}else{
			$color = 'color:#FF0000;';
			$rpt_status = 'Late';
		}
	}else if ( $sel_report_type == 'ontime' ){
		$color = 'color:#009900;';
		$rpt_status = 'On-time';
	}else if ( $sel_report_type == 'late' ){
		$color = 'color:#FF0000;';
		$rpt_status = 'Late';
	}else if ( $sel_report_type == 'non-reported' ){
		$color = 'color:#FF0000;';
		$rpt_status = 'Non-reported';
	}
	
	$xmlstore .= "<row>";
	$xmlstore .= "<cell>". $count++ ."</cell>";
	$xmlstore .= "<cell><![CDATA[".$row['province']."]]></cell>";
	$xmlstore .= "<cell><![CDATA[".$row['district']."]]></cell>";
	$xmlstore .= "<cell><![CDATA[".$row['stkMain']."]]></cell>";
	$xmlstore .= "<cell><![CDATA[".$row['stkOffice']."]]></cell>";
    if ($sel_report_type != 'non-reported')
    {
        $tempVar = "\"$row[wh_id]&month=$sel_month&year=$sel_year\"";
        $xmlstore .="<cell><![CDATA[<a href=javascript:showData($tempVar)>".$row['wh_name']."</a>]]>^_self</cell>";
    }
    else
    {
        $xmlstore .="<cell><![CDATA[".$row['wh_name']."]]></cell>";
    }
	$xmlstore .= "<cell><![CDATA[".$row['last_update']."]]></cell>";
	$xmlstore .= "<cell><![CDATA[".$row['ip_address']."]]></cell>";
	$xmlstore .="<cell style=\"$color\"><![CDATA[".$rpt_status."]]></cell>";
	$xmlstore .= "</row>";
}
$xmlstore .= "</rows>";

$provinceName = ($sel_prov == 'all') ? 'All' : $provinceName;
$stkName = ($sel_stk == 'all') ? 'All' : $stkName;

if($sel_lvl_type == 3){
	$storeType = 'District Stores';
}elseif($sel_lvl_type == 4){
	$storeType = 'Field Stores';
}elseif($sel_lvl_type == 7){
	$storeType = 'Health Facilities';
}elseif($sel_lvl_type == 'all'){
	$storeType = 'Stores/Facilities';
}elseif($sel_lvl_type == 'df'){
	$storeType = 'District and Field Stores';
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
<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="../../plmis_js/FunctionLib.js"></SCRIPT>
<script>
    var mygrid;
    function doInitGrid(){
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center;'><?php echo "$report_title Stakeholder = $stkName And Province/Region = $provinceName (".date('F',mktime(0,0,0,$sel_month)).' '.$sel_year.")";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("S. No., Province/Region, District, Stakeholder, Warehouse/Store Type, Warehouse/Store Name, Last Updated, IP Address, Status");
        mygrid.attachHeader(",#select_filter,#select_filter,#select_filter,#select_filter,,,,");
        mygrid.attachFooter("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'>Late reporting means reported after 15th of the reporting month</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan")
        mygrid.setInitWidths("50,120,110,90,150,*,160,100,80");
        mygrid.setColAlign("center,left,left,left,left,left,left,center");
        mygrid.setColSorting("int,str,str,str,str,str,str,str,str");
        mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
		mygrid.enableMultiline(true);
        mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }

    function functionCall(ip)
    {
        window.open('ip_info.php?ip='+ip, '_blank', 'scrollbars=1,width=650,height=600');
    }

    function showData(p)
    {
        window.open('wh_info.php?whId='+p, '_blank', 'scrollbars=1,width=900,height=500');
    }
</script>

<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" onLoad="doInitGrid()">
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php include "../../plmis_inc/common/_top.php";?>


    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                     <table width="100%">
                        <tr>
                            <td colspan="3" style="width:100% !important;">
                                <?php include(PLMIS_INC."report/reportheader.php");    //Include report header file ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="140"><h4>Total <?php echo $storeType;?>: <?php echo $totalWH;?></h4></td>
                            <?php
							if ( isset($sel_report_type) && $sel_report_type == 'non-reported' )
							{
							?>
							<td width="240"><h4>Non Reported <?php echo $storeType;?>: <?php echo $nonReported;?></h4></td>
                            <td width="147"><h4>Non Reporting Rate: <?php echo number_format(($nonReported / $totalWH) * 100, 2);?>%</h4></td>
							<?php
							}
							?>
                            <?php
							if ( isset($sel_report_type) && $sel_report_type == 'reported' )
							{
							?>
							<td width="240"><h4>Reported <?php echo $storeType;?>: <?php echo $reportedAll;?></h4></td>
                            <td width="147"><h4>Reporting Rate: <?php echo number_format(($reportedAll / $totalWH) * 100, 2);?>%</h4></td>
							<?php
							}
							?>
                            <?php
							if ( isset($sel_report_type) && $sel_report_type == 'ontime' )
							{
							?>
							<td width="240"><h4>On-time Reported <?php echo $storeType;?>: <?php echo $ontime;?></h4></td>
                            <td width="147"><h4>On-time Reporting Rate: <?php echo number_format(($ontime / $totalWH) * 100, 2);?>%</h4></td>
							<?php
							}
							?>
                            <?php
							if ( isset($sel_report_type) && $sel_report_type == 'late' )
							{
							?>
							<td width="240"><h4>Late Reported <?php echo $storeType;?>: <?php echo $late;?></h4></td>
                            <td width="147"><h4>Late Reporting Rate: <?php echo number_format(($late / $totalWH) * 100, 2);?>%</h4></td>
							<?php
							}
							?>
                        </tr>
                    </table>
					<?php 
					if($num > 0)
					{
					?>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="right" style="padding-right:5px;" colspan="8">
                                <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="mygrid_container" style="width:100%; height:470px;"></div>
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
<?php include "../../plmis_inc/common/footer.php";?>

<script>
	$(function(){
		$('#districts_td_id').hide();
		
		$('#sector').change(function(e) {
			var val = $('#sector').val();
			getStakeholder(val, '');
		});
		getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
	})
	function getStakeholder(val, stk)
	{
		$.ajax({
			url: 'ajax_stk.php',
			data: {type:val, stk: stk},
			type: 'POST',
			success: function(data){
				$('#stk_sel').html(data)
			}
		})
	}
	$(function(){
		showDistricts('<?php echo $sel_district;?>');
		$('#prov_sel').change(function(e) {
			showDistricts('');
		});
	})
	function showDistricts(dId)
	{
		var provinceId = $('#prov_sel').val();
		$.ajax({
			url: 'ajax_calls.php',
			type: 'POST',
			data: {provinceId: provinceId, validate: 'no', dId: dId, stkId: $('#stk_sel').val()},
			success: function(data){
				$('#districtsCol').html(data);
			}
		});
	}
</script>

</body>
<!-- END BODY -->
</html>