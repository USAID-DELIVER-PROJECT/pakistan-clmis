<?php
/* * *********************************************************************************************************
  Developed by: Farjad Hasan
  email: farjadjsi@gmail.com
  This is the file used for projected contraceptive requirements
  /********************************************************************************************************** */
include("../../html/adminhtml.inc.php");
define('REPORT_XML_PATH1', SITE_PATH . "plmis_src/operations/xml/");
Login();

$disabled = (isset($_GET['view']) && $_GET['view'] == 1) ? 'disabled="disabled"' : '';
?>
<?php include "../../plmis_inc/common/_header.php";?>
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
                                                <option value="all">All</option>
                                                <?php
                                                $qry = "SELECT
                                                                            stakeholder_type.stk_type_descr,
                                                                            stakeholder_type.stk_type_id
                                                                        FROM
                                                                            stakeholder_type
                                                                        WHERE
                                                                            stakeholder_type.stk_type_id IN (0,1)";
                                                $qryRes = mysql_query($qry);
                                                while ($row = mysql_fetch_array($qryRes)) {
                                                    $sel = ($_POST['sector'] == $row['stk_type_id']) ? 'selected="selected"' : '';
                                                    echo "<option value=\"$row[stk_type_id]\" $sel>$row[stk_type_descr]</option>";
                                                }
                                                ?>
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
                                            <select name="product" id="product" required="required" class="form-control input-sm">
                                                <option value="">Select</option>
                                            </select>
                                        </td>
                                        <?php
                                        if (!isset($_GET['view'])) {
                                            ?>
                                            <td class="col-md-2" style="margin-left:20px; padding-top: 28px;" valign="middle">
                                                <input type="submit" id="submit" value="Go" class="btn btn-primary input-sm" />
                                            </td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['demand_for']) && isset($_POST['product']) && isset($_POST['sector']) && isset($_POST['stk_sel']) && isset($_POST['province'])) {
                        $year = mysql_real_escape_string($_POST['year']);
                        $month = mysql_real_escape_string($_POST['month']);
                        $demand_for = mysql_real_escape_string($_POST['demand_for']);
                        $stk_type = mysql_real_escape_string($_POST['sector']);
                        $stk_id = mysql_real_escape_string($_POST['stk_sel']);
                        $province = mysql_real_escape_string($_POST['province']);
                        $product = mysql_real_escape_string($_POST['product']);

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
                                                ORDER BY distName";
                        $distRes = mysql_query($qry_dist);
						$getProvQry = "SELECT
											tbl_locations.LocName
										FROM
											tbl_locations
										WHERE
											tbl_locations.PkLocID = $province";
						$getProvQry = mysql_fetch_array(mysql_query($getProvQry));
						$provinceName = $getProvQry['LocName'];

                        while ($rsPro = mysql_fetch_array($distRes)) {

                            $xmlstore .="<row id=\"$counter\">";
                            $xmlstore .="<cell>$rsPro[distName]</cell>";

                            $product_name = mysql_fetch_array(mysql_query("SELECT itminfo_tab.itm_name
                                                                                FROM
                                                                                itminfo_tab
                                                                                WHERE
                                                                                        itminfo_tab.itmrec_id = '$product'"));

                            $xmlstore .="<cell>$product_name[itm_name]</cell>";

                            $stk_name = mysql_fetch_array(mysql_query("SELECT
                                                                                    stakeholder.stkname
                                                                            FROM
                                                                                    stakeholder
                                                                            WHERE
                                                                                    stakeholder.stkid = $stk_id"));

                            $xmlstore .="<cell>$stk_name[stkname]</cell>";

                            $consumption = mysql_fetch_array(mysql_query("SELECT REPgetConsumptionAVG('WSPD'," . $month . "," . $year . ",'" . $product . "', " . $stk_id . ", " . $province . ", " . $rsPro[distId] . ") AS Consumption FROM DUAL"));
                            $consumption = (!empty($consumption['Consumption'])) ? round($consumption['Consumption'], 0) : 0;

                            $xmlstore .="<cell style=\"text-align:right;\">".number_format($consumption)."</cell>";

                            $SOHDistrict = mysql_fetch_array(mysql_query("SELECT REPgetCB('WSPD'," . $month . "," . $year . ",'" . $product . "', " . $stk_id . ", " . $province . ", " . $rsPro[distId] . ") AS SOHDistrict FROM DUAL"));
                            $SOHDistrict = (!empty($SOHDistrict['SOHDistrict'])) ? round($SOHDistrict['SOHDistrict'], 0) : 0;

                            $xmlstore .="<cell style=\"text-align:right;\">".number_format($SOHDistrict)."</cell>";

                            $SOHField = mysql_fetch_array(mysql_query("SELECT REPgetCB('FSPD'," . $month . "," . $year . ",'" . $product . "', " . $stk_id . ", " . $province . ", " . $rsPro[distId] . ") AS SOHField FROM DUAL"));
                            $SOHField = (!empty($SOHField['SOHField'])) ? round($SOHField['SOHField'], 0) : 0;

                            $xmlstore .="<cell style=\"text-align:right;\">".number_format($SOHField)."</cell>";

                            $total = $SOHDistrict + $SOHField;
                            $xmlstore .="<cell style=\"text-align:right;\">".number_format($total)."</cell>";

                            $E = $consumption * $demand_for;
                            $xmlstore .="<cell style=\"text-align:right;\">".number_format($E)."</cell>";

                            $F = ($total > $E) ? $F = 0 : $F = round(($E - $total), 0);
                            $xmlstore .="<cell style=\"text-align:right;\">".  number_format($F)."</cell>";

                            $qty_carton = mysql_fetch_array(mysql_query("SELECT
																			itminfo_tab.qty_carton
																		FROM
																			itminfo_tab
																		WHERE
																			itminfo_tab.itmrec_id = '$product'"));
                            $qty_carton = $qty_carton[qty_carton];
                            if ($qty_carton > 0) {
                                $num_carton = round(($F / $qty_carton), 0);
                            } else {
                                $num_carton = '-';
                            }
                            $xmlstore .="<cell style=\"text-align:right;\">$num_carton</cell>";

                            $xmlstore .="</row>";
                            $counter++;
                        }
                        $xmlstore .="</rows>";
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
                    <?php
					//XML write function
                    function writeXML($xmlfile, $xmlData) {
                        $xmlfile_path = REPORT_XML_PATH1 . "/" . $xmlfile;
                        $handle = fopen($xmlfile_path, 'w');
                        fwrite($handle, $xmlData);
                    }

                    //writeXML('new_clr_results.xml', $xmlstore);
                    ?>
		</div>
		<?php
        $stk_sel12 = $_POST['stk_sel'];
        $sector = $_POST['sector'];

        if (empty($stk_sel12)) {
            $stk_sel12 = '';
        }
        ?>
        <script>
            $(function() {
                $('#sector').change(function(e) {
                    var val = $('#sector').val();
                    getStakeholder(val, '');
                });
                getStakeholder('<?php echo $sector; ?>', '<?php echo $stk_sel12; ?>');
            });
        </script>

	</div>
</div>


<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
<link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
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
<script src="dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
<script>
    var mygrid;
    function doInitGrid() {
        mygrid = new dhtmlXGridObject('mygrid_container');
        mygrid.selMultiRows = true;
        mygrid.setImagePath("dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
        mygrid.setHeader("<div style='text-align:center;font-size:14px;'>Projected Contraceptive Requirement for Province='<?php echo $provinceName;?>' (<?php echo $reportMonth = date('F',mktime(0,0,0,$month)).' '.$year;;?>)</div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<div>District</div>,<div title='Product' style='text-align:center;'>Product</div>,<div title='Stakeholder Name' style='text-align:center;'>Stakeholder</div>,<div title='Average Monthly Consumption' style='text-align:center;'>AMC<br><br><br><br>(A)</div>,<div title='Stock at the end of the month' style='text-align:center;'><?php echo "Stock at the end of ".date('M', mktime(0, 0, 0, $_POST[month], 1))." ".$_POST[year];?></div>,#cspan,#cspan,<div title='Desired stock level for' style='text-align:center;'><?php echo "Desired stock level for " . $_POST[demand_for] . " months<br><br>(E)"; ?></div>,<div title='Replenishment Requested' style='text-align:center;'>Replenishment Requested<br>(F= E-D)</div>,#cspan");
		mygrid.attachHeader("#rspan,#rspan,#rspan,#rspan,<div style='text-align:center;'>District<br>(B)</div>,<div style='text-align:center;'>Field<br>(C)</div>,<div style='text-align:center;'>Total<br>(D)</div>,#rspan,<div style='text-align:center;'>Qty(Pcs)</div>,<div style='text-align:center;'>Qty(Cartons)</div>");
        mygrid.setColAlign("left,center,center,center,center,center,center,center,center,center,center");
        mygrid.setInitWidths("150,*,*,*,*,*,*,*,*,*,*");
        mygrid.enableRowsHover(true, 'onMouseOver');   // `onMouseOver` is the css class name.
        mygrid.setSkin("light");
        mygrid.init();
       	//mygrid.loadXML("xml/new_clr_results.xml");
		mygrid.clearAll();
		mygrid.loadXMLString('<?php echo $xmlstore;?>');
    }
	function getStakeholder(val, stk)
	{
		$('#stk_sel').html('<option value="">Select</option>');
		if (val != '')
		{
			$.ajax({
				url: "ajax_stk.php",
				data: {type: val, stk: stk},
				type: 'POST',
				success: function(data) {
					$('#stk_sel').html(data);
					showProducts('<?php echo $product;?>');
				}
			});
		}
	}
	$(function(){
        $('#stk_sel').change(function(e) {
			$('#product').html('<option value="">Select</option>');
            showProducts('');
        });
    })
    function showProducts(pid){
        var stk = $('#stk_sel').val();
		$.ajax({
			url: 'ajax_calls.php',
			type: 'POST',
			data: {stakeholder: stk, productId:pid},
			success: function(data){
				$('#product').html(data);
			}
		})
    }
</script>
<!-- END FOOTER -->
<?php include "../../plmis_inc/common/footer.php";?>
</body>
<!-- END BODY -->
</html>