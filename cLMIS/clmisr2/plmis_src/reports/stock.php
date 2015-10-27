<?php
include("../../html/adminhtml.inc.php");
Login();

if ( date('d') > 10 )
{
	$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
}
else
{
	$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
$selMonth = date('m', strtotime($date));
$selYear = date('Y', strtotime($date));
	
if ($_REQUEST['submit'])
{
	$selYear = $_REQUEST['year_sel'];
	$selMonth = $_REQUEST['ending_month'];
	$selItem = $_REQUEST['item_id'];
	$selPro = $_REQUEST['prov_sel'];
	$selStk = $_REQUEST['stk_sel'];
	$type = $_REQUEST['type'];
	$sector = $_REQUEST['sector'];
	
	$itmQry = mysql_fetch_array(mysql_query("SELECT
				itminfo_tab.itm_name
			FROM
				itminfo_tab
			WHERE
				itminfo_tab.itmrec_id = '$selItem'"));
	$proName = $itmQry['itm_name'];
	
	if($type == 'Issue')
	{
		$colName = 'wh_issue_up';
		$lvlFilter = ' AND stakeholder.lvl = 3';
	}
	else if ($type == 'Consumption')
	{
		$colName = 'wh_issue_up';
		$lvlFilter = ' AND stakeholder.lvl = 4';
	}
	else if($type == 'Receive')
	{
		$colName = 'wh_received';
		$lvlFilter = ' AND stakeholder.lvl = 3';
	}
	else if($type == 'SOH')
	{
		$colName = 'wh_cbl_a';
		$lvlFilter = ' AND stakeholder.lvl IN(3, 4)';
	}
	
	if($selPro == 'all')
	{
		$provFilter = '';
		$provinceName = 'All';
	}
	else
	{
		$provFilter = "AND tbl_warehouse.prov_id = '".$selPro."' ";
		$provinceQryRes = mysql_fetch_array(mysql_query("SELECT LocName FROM tbl_locations WHERE PkLocID = '".$selPro."' "));
		$provinceName = "\'$provinceQryRes[LocName]\'";
	}
	
	if ( $sector == 'All' ){
		 $rptType = 'All';
	}else{
		$rptType = $sector;
	}
	if(!empty($selStk) && $selStk != 'all'){
		$stkFilter = " AND MainStk.MainStakeholder = '".$selStk."'";
	}else if ( $rptType == 'public' && $selStk == 'all' ){
		$stkFilter = " AND MainStk.stk_type_id = 0";
	}else if ( $rptType == 'private' && $selStk == 'all' ){
		$stkFilter = " AND MainStk.stk_type_id = 0";
	}
	
    $endDate = $selYear.'-'.($selMonth).'-01';
	$endDate = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($endDate))));
	$startDate = date('Y-m-d', strtotime("-364 days", strtotime($endDate)));
	// Start date and End date
	$begin = new DateTime( $startDate );
	$end = new DateTime( $endDate );
	$diff = $begin->diff($end);
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	
	$dataArr = array();
	$qry = "SELECT
				tbl_locations.PkLocID AS DistrictID,
				tbl_locations.LocName AS DistrictName,
				MainStk.stkname,
				tbl_warehouse.stkid
			FROM
				tbl_warehouse
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
			INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
			WHERE
				tbl_wh_data.item_id = '".$selItem."'
				$provFilter
				$stkFilter
				$lvlFilter
				AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			GROUP BY
				tbl_warehouse.dist_id,
				tbl_warehouse.stkid
			ORDER BY
				DistrictName ASC ";
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows($qryRes);
	while($row = mysql_fetch_array($qryRes))
	{
		$dataArr[$row['DistrictID'].'-'.$row['stkid']][] = $row['DistrictName'];
		$dataArr[$row['DistrictID'].'-'.$row['stkid']][] = $row['stkname'];
		$count = 2;
		foreach ( $period as $date )
		{
			$dataArr[$row['DistrictID'].'-'.$row['stkid']][$count] = 0;
			$count++;
		}
	}
	
	// Headers of the Grid
	$header = 'District Id, District, Stakeholder';
	$width = '50,*,85';
	$ro = 'ro,ro,ro';
	
	$count = 2;
	foreach ( $period as $date )
	{
		$monthArr[] = $date->format( "Y-m" );
		$header .= ',<span>'.$date->format( "M-y" ).'</span>';
		$width .= ',65';
		$ro .= ',ro';
		$newQry = "SELECT
						tbl_locations.PkLocID AS DistrictID,
						tbl_locations.LocName AS DistrictName,
						SUM(tbl_wh_data.$colName) AS total,
						MainStk.stkname,
						tbl_warehouse.stkid
					FROM
						tbl_warehouse
					INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
					INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
					WHERE
						tbl_wh_data.item_id = '".$selItem."'
						$provFilter
						$stkFilter
						$lvlFilter
						AND DATE_FORMAT(tbl_wh_data.RptDate, '%Y-%m') = '".$date->format( "Y-m" )."'
					GROUP BY
						tbl_warehouse.dist_id,
						tbl_warehouse.stkid
					ORDER BY
						DistrictName ASC";
		$qryRes = mysql_query($newQry);
		while($row = mysql_fetch_array($qryRes))
		{
			$dataArr[$row['DistrictID'].'-'.$row['stkid']][$count] = $row['total'];
		}
		$count++;
	}

	$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$xmlstore .= "<rows>";
	
	$sumArr = array();
	
	foreach( $dataArr as $disId=>$subArr )
	{
		$xmlstore .= "<row>";
		
		list($distId, $stkOfcId) = explode('-', $disId);
		
		//$param = base64_encode($sel_indicator.'|'.$startDate.'|'.$endDate.'|'.$sel_item.'|'.$disId.'|'.$subArr[0]);
		//$xmlstore .= "\t\t<cell><![CDATA[<a href=javascript:functionCall('$param')>$subArr[0]</a>]]>^_self</cell>\n";
		$xmlstore .= "<cell>$distId</cell>";
		$xmlstore .= "<cell>$subArr[0]</cell>";
		$xmlstore .= "<cell>$subArr[1]</cell>";
		
		foreach ($subArr as $key=>$value)
		{
			if(!isset($sumArr[$key]))
			{
				$sumArr[$key] = 0;
			}
			$sumArr[$key] += $value;

			if ( $key > 1 )
			{
				$xmlstore .= "<cell style=\"text-align:right\">".number_format($value)."</cell>";
			}
		}
		$xmlstore .="</row>";
	}
	$xmlstore .="</rows>";
	
}

if ($selStk == 'all'){
    $stkName = "\'All\'";
}else{
    $stakeNameQryRes = mysql_fetch_array(mysql_query("SELECT stkname FROM stakeholder WHERE stkid = '".$selStk."' "));
    $stkName = "\'$stakeNameQryRes[stkname]\'";
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
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader(",<div style='text-align:center;'><?php echo "District Stock ".ucwords($type)." Yearly Report for Sector = '".ucwords($rptType)."' Stakeholder(s) = $stkName Province/Region = $provinceName And Product = '$proName'";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<?php echo $header;?>");
        mygrid.setInitWidths("<?php echo $width;?>");
        mygrid.setColTypes("<?php echo $ro;?>");
		mygrid.setColAlign("left");
		mygrid.setColumnHidden(0,true);
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		//mygrid.loadXML("xml/stock.xml");
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
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
                    <h3 class="page-title row-br-b-wp">District Stock Yearly Report</h3>
                                  
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Filter by</h3>
                        </div>
                        <div class="widget-body">
                              <form name="frm" id="frm" action="" method="post" role="form">
                                  <table id="myTable">
                                      <tr>
                                          <td class="col-md-2">
                                              <label class="sb1NormalFont">Ending Month</label>
                                              <select name="ending_month" id="ending_month" class="form-control input-sm">
                                                  <?php
                                                  for ($i = 1; $i <= 12; $i++) {
                                                      if ($selMonth == $i) {
                                                          $sel = "selected='selected'";
                                                      }else {
                                                          $sel = "";
                                                      }
                                                      ?>
                                                      <option value="<?php echo date('m', mktime(0, 0, 0, $i, 1));?>"<?php echo $sel;?> ><?php echo date('M', mktime(0, 0, 0, $i, 1));?></option>
                                                      <?php
                                                  }
                                                  ?>
                                              </select>
                                          </td>
                                          <td  class="col-md-2">
                                              <label class="sb1NormalFont">Year</label>
                                              <select name="year_sel" id="year_sel" class="form-control input-sm">
                                                  <?php
                                                  for ($j = date('Y'); $j >= 2010; $j--) {
                                                      if ($selYear == $j) {
                                                          $sel = "selected='selected'";
                                                      }else {
                                                          $sel = "";
                                                      }
                                                      ?>
                                                      <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j;?></option>
                                                      <?php
                                                  }
                                                  ?>
                                              </select>
                                          </td>
                                          <td class="col-md-2">
                                              <label class="sb1NormalFont">Province</label>
                                              <select name="prov_sel" id="prov_sel" required class="form-control input-sm">
                                                  <option value="">Select</option>
                                                  <option value="all" <?php echo ($selPro == 'all') ? "selected='selected'" : "";?>>All</option>
                                                  <?php
                                                  $queryprov = "SELECT
																	tbl_locations.PkLocID AS prov_id,
																	tbl_locations.LocName AS prov_title
																FROM
																	tbl_locations
																WHERE
																	LocLvl = 2
																AND parentid IS NOT NULL";
                                                  $rsprov = mysql_query($queryprov) or die();
                                                  while ($rowprov = mysql_fetch_array($rsprov))
                                                  {
                                                      if ($selPro == $rowprov['prov_id'])
                                                          $sel = "selected='selected'";
                                                      else
                                                          $sel = "";
                                                      ?>
                                                      <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel;?>><?php echo $rowprov['prov_title']; ?></option>
                                                      <?php
                                                  }
                                                  ?>
                                              </select>
                                          </td>
                                          <td class="col-md-2">
                                            <label class="sb1NormalFont">Sector</label>
                                            <select class="form-control input-sm" id="sector" name="sector">
                                                <option <?php echo ($rptType == 'all') ? 'selected="selected"' : '';?> value="all">All</option>
                                                <option <?php echo ($rptType == 'public') ? 'selected="selected"' : '';?> value="public">Public</option>
                                                <option <?php echo ($rptType == 'private') ? 'selected="selected"' : '';?> value="private">Private</option>
                                            </select>
                                          </td>
                                          <td class="col-md-2">
                                              <label class="sb1NormalFont">Stakeholder</label>
                                              <select name="stk_sel" id="stk_sel" required class="form-control input-sm">
                                                  <option value="">Select</option>
                                                  <option value="all" <?php echo ($selStk == 'all') ? "selected='selected'" : "";?>>All</option>
                                                  <?php
                                                  $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null AND stakeholder.stk_type_id IN (0,1) order by stkorder";
                                                  $rsstk = mysql_query($querystk) or die();
                                                  while ($rowstk = mysql_fetch_array($rsstk))
                                                  {
                                                      if ($selStk == $rowstk['stkid'])
                                                          $sel = "selected='selected'";
                                                      else
                                                          $sel = "";
                                                      ?>
                                                      <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel;?>><?php echo $rowstk['stkname']; ?></option>
                                                      <?php
                                                  }
                                                  ?>
                                              </select>
                                          </td>
                                          <td class="col-md-2">
                                              <label class="sb1NormalFont">Product</label>
                                              <select name="item_id" id="item_id" required class="form-control input-sm">
                                                  <option value="">Select</option>
                                              </select>
                                          </td>
                                          <td class="col-md-1">
                                              <label class="sb1NormalFont">Indicator</label>
                                              <select name="type" id="type" required style="width:110px;" class="form-control input-sm">
                                                  <option value="">Select</option>
                                                  <option value="Issue" <?php echo ($type == 'Issue') ? "selected='selected'" : "";?>>Issue</option>
                                                  <option value="Receive" <?php echo ($type == 'Receive') ? "selected='selected'" : "";?>>Receive</option>
                                                  <option value="Consumption" <?php echo ($type == 'Consumption') ? "selected='selected'" : "";?>>Consumption</option>
                                                  <option value="SOH" <?php echo ($type == 'SOH') ? "selected='selected'" : "";?>>Stock on Hand</option>
                                              </select>
                                          </td>
                                          <td class="col-md-1" style="margin-left:20px; padding-top: 20px;" valign="middle">
                                              <input type="submit" name="submit" id="go" value="GO" class="btn btn-primary input-sm" />
                                          </td>
                                      </tr>
                                  </table>
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
                                    <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.setColumnHidden(0,false); mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php'); mygrid.setColumnHidden(0,true);" />
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

<?php include "../../plmis_inc/common/footer.php";?>
<script>
    function getStakeholder(val, stk)
    {
        $.ajax({
            url: 'ajax_stk.php',
            data: {type:val, stk: stk},
            type: 'POST',
            success: function(data){
                $('#stk_sel').html(data);
				
				showProducts('<?php echo (!empty($selItem)) ? $selItem : '';?>');
            }
        })
    }
	
	$(function(){
        $('#stk_sel').change(function(e) {
            showProducts('');
        });
		
		$('#sector').change(function(e) {
			$('#item_id').html('<option>Select</option>');
			var val = $('#sector').val();
			getStakeholder(val, '');
		});
		getStakeholder('<?php echo $rptType;?>', '<?php echo $selStk;?>');
    })
	<?php
    if ( isset($selItem) && !empty($selItem) )
    {
        ?>
    showProducts('<?php echo $selItem;?>');
        <?php
    }
    ?>
	function showProducts(pid){
		var stk = $('#stk_sel').val();
		$.ajax({
			url: 'ajax_calls.php',
			type: 'POST',
			data: {stakeholder: stk, productId:pid},
			success: function(data){
				$('#item_id').html(data);
			}
		})
	}
</script>
</body>
<!-- END BODY -->
</html>