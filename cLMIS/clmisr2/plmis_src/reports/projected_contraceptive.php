<?php
include("../../html/adminhtml.inc.php");
Login();

if (isset($_POST['submit'])) {
	$year = mysql_real_escape_string($_POST['year']);
	$month = mysql_real_escape_string($_POST['month']);
	$demand_for = mysql_real_escape_string($_POST['demand_for']);
	$stk_type = mysql_real_escape_string($_POST['sector']);
	$stk_id = mysql_real_escape_string($_POST['stk_sel']);
	$province = mysql_real_escape_string($_POST['province']);
	$product = mysql_real_escape_string($_POST['product']);
	$sel_item = mysql_real_escape_string($_POST['product']);
    $reportingDate = $year . '-' . $month . '-01';
	
	if(!empty($stk_id) && $stk_id!='all'){
		$stkFilter = " AND summary_district.stakeholder_id = '".$stk_id."'";
	}else if ( $_POST['sector'] == 'public' && $_POST['stk_sel'] == 'all' ){
		$stkFilter = " AND stakeholder.stk_type_id = 0";
	}else if ( $_POST['sector'] == 'private' && $_POST['stk_sel'] == 'all' ){
		$stkFilter = " AND stakeholder.stk_type_id = 1";
	}
	
	if ( $province != 'all' ){
		$provFilter = " AND summary_district.province_id = $province";
	}
	if (!empty($sel_item)){
		$prodFilter = " AND summary_district.item_id = '$sel_item'";
	}

	$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	$xmlstore .="<rows>";
	$counter = 1;
	$qry_dist = "SELECT
					District.PkLocID AS distId,
					District.LocName AS distName
				FROM
					tbl_locations AS District
				WHERE
					District.LocLvl = 3
					AND District.ParentID = $province
				ORDER BY
					distName";
	$distRes = mysql_query($qry_dist);
	$getProvQry = "SELECT
						tbl_locations.LocName
					FROM
						tbl_locations
					WHERE
						tbl_locations.PkLocID = $province";
	$getProvQry = mysql_fetch_array(mysql_query($getProvQry));
	$provinceName = $getProvQry['LocName'];
	$qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName AS distName,
				Province.LocName AS provName,
				stakeholder.stkname,
				itminfo_tab.itm_name,
				itminfo_tab.qty_carton,
				summary_district.avg_consumption,
				summary_district.soh_district_store AS SOH_district,
				(summary_district.soh_district_lvl - summary_district.soh_district_store) AS SOH_field,
				summary_district.soh_district_lvl AS SOH_total
			FROM
			summary_district
			INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
			INNER JOIN tbl_locations AS Province ON summary_district.province_id = Province.PkLocID
			INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
			INNER JOIN itminfo_tab ON summary_district.item_id = itminfo_tab.itmrec_id
			WHERE
				summary_district.reporting_date = '$reportingDate'
			$prodFilter
			$provFilter
			$stkFilter
			GROUP BY
				summary_district.district_id,
				summary_district.stakeholder_id,
				summary_district.item_id
			ORDER BY
				Province.PkLocID ASC,
				tbl_locations.LocName ASC,
				stakeholder.stkorder ASC,
				itminfo_tab.frmindex ASC";
			$qryRes = mysql_query($qry);
			$num = mysql_num_rows(mysql_query($qry));
			$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
			$xmlstore .= "<rows>";
			while ( $row = mysql_fetch_array($qryRes) )
			{
				$cartonQty  = $row['qty_carton'];
				$desiredStock = $row['avg_consumption'] * $demand_for;
				$repRequest = ($desiredStock > $row['SOH_total']) ? $desiredStock - $row['SOH_total'] : 0;
				$xmlstore .= "<row>";
				$xmlstore .= "<cell><![CDATA[".$row['distName']."]]></cell>";
				$xmlstore .= "<cell><![CDATA[".$row['provName']."]]></cell>";
				$xmlstore .= "<cell><![CDATA[".$row['stkname']."]]></cell>";
				$xmlstore .= "<cell><![CDATA[".$row['itm_name']."]]></cell>";
				$xmlstore .= "<cell>".number_format($row['avg_consumption'])."</cell>";
				$xmlstore .= "<cell>".number_format($row['SOH_district'])."</cell>";
				$xmlstore .= "<cell>".number_format($row['SOH_field'])."</cell>";
				$xmlstore .= "<cell>".number_format($row['SOH_total'])."</cell>";
				$xmlstore .= "<cell>".number_format($desiredStock)."</cell>";
				$xmlstore .= "<cell>".number_format($repRequest)."</cell>";
				$xmlstore .= "<cell>".number_format($repRequest/$cartonQty)."</cell>";
				$xmlstore .= "</row>";
			}
			$xmlstore .= "</rows>";
}

$disabled = (isset($_GET['view']) && $_GET['view'] == 1) ? 'disabled="disabled"' : '';
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
    function doInitGrid() {
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center;'>Projected Contraceptive Requirement for Province='<?php echo $provinceName;?>' (<?php echo $reportMonth = date('F',mktime(0,0,0,$month)).' '.$year;;?>)</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("District, Province, Stakeholder, Product, <div style='text-align:center'>AMC<br><br><br><br>(A)</div>,<div title='Stock at the end of the month' style='text-align:center;'><?php echo "Stock at the end of ".date('M', mktime(0, 0, 0, $_POST['month'], 1))." ".$_POST['year'];?></div>,#cspan,#cspan,<div title='Desired stock level for' style='text-align:center;'><?php echo "Desired stock level for " . $_POST[demand_for] . " months<br><br>(E)"; ?></div>,<div title='Replenishment Requested' style='text-align:center;'>Replenishment Requested<br>(F= E-D)</div>,#cspan");
		mygrid.attachHeader("#rspan,#rspan,#rspan,#rspan,#rspan,<div style='text-align:center;'>District<br>(B)</div>,<div style='text-align:center;'>Field<br>(C)</div>,<div style='text-align:center;'>Total<br>(D)</div>,#rspan,<div style='text-align:center;'>Quantity</div>,<div style='text-align:center;'>Quantity (Cartons)</div>");
        mygrid.setColAlign("left,left,left,left,right,right,right,right,right,right,right");
        mygrid.setInitWidths("150,*,*,*,*,*,*,*,*,*,*");
        mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
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

            <!-- BEGIN PAGE HEADER-->

                <div class="row">
                    <div class="col-md-12">
                    	<h3 class="page-title row-br-b-wp">
                            <?php echo "Projected Contraceptive Requirement"; ?>
                            <span class="green-clr-txt"></span>
                        </h3>
                        
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Filter by</h3>
                        </div>
                        <div class="widget-body">
                            <form name="frm" id="frm" action="" method="POST">
                                
                                <table width="100%">
                                    <tr>
                                        <!--Month-->
                                        <td class="col-md-2">
                                            <label class="control-label">Ending Month</label>
                                            <select name="month" id="month" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) {
                                                    if ($_POST['month'] == $i)
                                                        $sel = "selected='selected'";
                                                    else
                                                        $sel = "";
                                                    ?>
                                                    <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <!--Year-->
                                        <td class="col-md-2">
                                            <label class="control-label">Year</label>
                                            <select name="year" id="year" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <?php
                                                for ($i = date('Y'); $i >= 2010; $i--) {
                                                    $sel = ($_POST['year'] == $i) ? 'selected="selected"' : '';
                                                    echo "<option value=\"$i\" $sel>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <!--Demand For (months)-->
                                        <td class="col-md-2">
                                            <label class="control-label">Demand For(Months)</label>
                                            <select name="demand_for" id="demand_for" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <?php
                                                for ($i = 1; $i < 8; $i++) {
                                                    $sel = ($_POST['demand_for'] == $i) ? 'selected="selected"' : '';
                                                    echo "<option value=\"$i\" $sel>$i</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>    
                                        <!--Type-->
                                        <td class="col-md-2">
                                            <label class="control-label">Sector</label>
                                            <select style="width:90%;" name="sector" id="sector" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="all" <?php echo ($stk_type == 'all') ? 'selected="selected"' : '';?>>All</option>
                                                <option value="public" <?php echo ($stk_type == 'public') ? 'selected="selected"' : '';?>>Public</option>
                                                <option value="private" <?php echo ($stk_type == 'private') ? 'selected="selected"' : '';?>>Private</option>
                                            </select>
                                        </td>
                                        <!--Stakeholder-->
                                        <td class="col-md-2">
                                            <label class="control-label">Stakeholder</label>
                                            <select name="stk_sel" id="stk_sel" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                            </select>
                                        </td>
        
                                        <!--Province-->
                                        <td class="col-md-2">
                                            <label class="control-label">Province/Region</label>
                                            <select name="province" id="province" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="all">All</option>
                                                <?php
                                                $qry = "SELECT
														tbl_locations.LocName AS prov_name,
														tbl_locations.PkLocID
													FROM
														tbl_locations
													WHERE
														tbl_locations.LocLvl = 2
													AND ParentID IS NOT NULL";
                                                $qryRes = mysql_query($qry);
                                                while ($row = mysql_fetch_array($qryRes)) {
                                                    $sel = ($_POST['province'] == $row['PkLocID']) ? 'selected="selected"' : '';
                                                    echo "<option value=\"$row[PkLocID]\" $sel>$row[prov_name]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <!--Product-->
                                        <td class="col-md-2">
                                            <label class="control-label">Product</label>
                                            <select name="product" id="product" class="form-control input-sm">
                                                <option value="">Select</option>
                                            </select>
                                        </td>
                                        <?php
                                        if (!isset($_GET['view'])) {
                                            ?>
                                            <td class="col-md-2" style="margin-left:20px; padding-top: 28px;" valign="middle">
                                                <input type="submit" id="submit" name="submit" value="Go" class="btn btn-primary input-sm" />
                                            </td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
						<?php
                        if ($num > 0)
                        {
                        ?>
                            <table width="100%" cellpadding="0" cellspacing="0" id="myTable">
                                <tr>
                                    <td style="float:left;">
                                        <label>Note: If D > E then F= 0 else F= E-D</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" style="padding-right:5px;">
                                        <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                                        <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div>
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

<script>
	$(function(){
		$('#sector').change(function(e) {
			$('#stk_sel').html('<option value="">Select</option>');
			$('#product').html('<option value="">Select</option>');
			var val = $('#sector').val();
			getStakeholder(val, '');
		});
		 $('#stk_sel').change(function(e) {
			$('#product').html('<option value="">Select</option>');
            showProducts('');
        });
		getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
	})
	getStakeholder('<?php echo $stk_type;?>', '<?php echo $stk_id;?>');
	
	function getStakeholder(val, stk)
	{
		if (val != '')
		{
			$.ajax({
				url: 'ajax_stk.php',
				data: {type:val, stk: stk},
				type: 'POST',
				success: function(data){
					$('#stk_sel').html(data);
					showProducts('<?php echo $sel_item;?>');
				}
			})
		}
	}
	
	function showProducts(pid){
		var stk = $('#stk_sel').val();
		if (typeof stk !== 'undefined')
		{
			$.ajax({
				url: 'ajax_calls.php',
				type: 'POST',
				data: {stakeholder: stk, productId:pid, validate : 'no'},
				success: function(data){
					$('#product').html(data);
				}
			})
		}
	}
</script>
<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
</body>
<!-- END BODY -->
</html>