<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();


if ($_REQUEST['submit'])
{
	$selYear = $_REQUEST['year_sel'];
	$selMonth = $_REQUEST['ending_month'];
	$selItem = $_REQUEST['item_id'];
	$selPro = $_REQUEST['prov_sel'];
	$selStk = $_REQUEST['stk_sel'];
	$type = $_REQUEST['type'];
	
	$stkCondition = ($selStk == 'all') ? '' : " AND stakeholder.MainStakeholder = '".$selStk."' ";
	$colName = ($type == 'Issue') ? 'wh_issue_up' : 'wh_received';
	
	if($selPro == 'all')
	{
		$provCondition = '';
		$provinceName = 'All';
	}
	else
	{
		$provCondition = "AND tbl_warehouse.prov_id = '".$selPro."' ";
		$provinceQryRes = mysql_fetch_array(mysql_query("SELECT LocName FROM tbl_locations WHERE PkLocID = '".$selPro."' "));
		$provinceName = "\'$provinceQryRes[LocName]\'";
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
				tbl_warehouse.stkofficeid
			FROM
				tbl_warehouse
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
					INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
			WHERE
				stakeholder.lvl = 3
				AND tbl_wh_data.item_id = '".$selItem."'
				$provCondition
				$stkCondition
				AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
			GROUP BY
				tbl_warehouse.dist_id,
				tbl_warehouse.stkofficeid
			ORDER BY
				DistrictName ASC ";
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows($qryRes);
	while($row = mysql_fetch_array($qryRes))
	{
		$dataArr[$row['DistrictID'].'-'.$row['stkofficeid']][] = $row['DistrictName'];
		$dataArr[$row['DistrictID'].'-'.$row['stkofficeid']][] = $row['stkname'];
		$count = 2;
		foreach ( $period as $date )
		{
			$dataArr[$row['DistrictID'].'-'.$row['stkofficeid']][$count] = 0;
			$count++;
		}
	}
	
	// Headers of the Grid
	$header = 'District, Stakeholder';
	$width = '*,60';
	$ro = 'ro,ro';
	
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
						tbl_warehouse.stkofficeid
					FROM
						tbl_warehouse
					INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
					INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
					INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
					INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
					WHERE
						stakeholder.lvl = 3
						AND tbl_wh_data.item_id = '".$selItem."'
						$provCondition
						$stkCondition
						AND DATE_FORMAT(tbl_wh_data.RptDate, '%Y-%m') = '".$date->format( "Y-m" )."'
					GROUP BY
						tbl_warehouse.dist_id,
						tbl_warehouse.stkofficeid
					ORDER BY
						DistrictName ASC";

		$qryRes = mysql_query($newQry);
		while($row = mysql_fetch_array($qryRes))
		{
			$dataArr[$row['DistrictID'].'-'.$row['stkofficeid']][$count] = $row['total'];
		}
		$count++;
	}

	$xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .= "<rows>\n";
	
	$sumArr = array();
	
	foreach( $dataArr as $disId=>$subArr )
	{
		$xmlstore .= "\t<row>\n";
		
		//$param = base64_encode($sel_indicator.'|'.$startDate.'|'.$endDate.'|'.$sel_item.'|'.$disId.'|'.$subArr[0]);
		//$xmlstore .= "\t\t<cell><![CDATA[<a href=javascript:functionCall('$param')>$subArr[0]</a>]]>^_self</cell>\n";
		$xmlstore .= "\t\t<cell>$subArr[0]</cell>\n";
		$xmlstore .= "\t\t<cell>$subArr[1]</cell>\n";
		
		foreach ($subArr as $key=>$value)
		{
			if(!isset($sumArr[$key]))
			{
				$sumArr[$key] = 0;
			}
			$sumArr[$key] += $value;

			if ( $key > 1 )
			{
				$xmlstore .= "\t\t<cell style=\"text-align:right\">".number_format($value)."</cell>\n";
			}
		}
		$xmlstore .="\t</row>\n";
	}
	$xmlstore .="</rows>";
	
}

startHtml($system_title." - District Stock Reports");?>

<style>
table tr td {font-size:12px; max-width:130px !important; padding-left:15px; text-align:left;}
.input_button{
	border:#D1D1D1 1px solid;
	background-color:#006700;
	color:#FFFFFF;
	height:25px;	
	font-family:Arial, Helvetica, sans-serif;
	vertical-align:bottom;
	width:60px;
}
table#myTable tr td{padding-left:5px;}
</style>

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
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "District Stock Yearly Report For Province/Region = $provinceName";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
        mygrid.attachHeader("<?php echo $header;?>");
        mygrid.setInitWidths("<?php echo $width;?>");
        mygrid.setColTypes("<?php echo $ro;?>");
		mygrid.setColAlign("left");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/stock.xml");
	}
 
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content" align=""><br>
		<?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
		
		<form name="frm" id="frm" action="" method="post">
            <table id="myTable">
                <tr>
                    <td style="width:110px;">
                        <label>Ending Month</label>
                        <select name="ending_month" id="ending_month" style="width:100px;">
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
                                <option value="<?php echo date('m', mktime(0, 0, 0, $i, 1));?>"<?php echo $sel;?> ><?php echo date('M', mktime(0, 0, 0, $i, 1));?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="width:80px;">
                        <label>Year</label>
                        <select name="year_sel" id="year_sel" class="span15">
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
                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="width:130px;">
                        <label>Province</label>
                        <select name="prov_sel" id="prov_sel" required style="width:120px;">
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
                    <td style="width:120px;">
                    	<label>Stakeholder</label>
                        <select name="stk_sel" id="stk_sel" required style="width:110px;">
                        <option value="">Select</option>
                        <option value="all" <?php echo ($selStk == 'all') ? "selected='selected'" : "";?>>All</option>
                        <?php
                        $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null order by stkorder";
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
                    <td style="width:120px;">
                        <label>Prroduct</label>
                        <select name="item_id" id="item_id" required style="width:110px;">
                        	<option value="">Select</option>
                        <?php
						$items = mysql_query("SELECT
												itminfo_tab.itmrec_id,
												itminfo_tab.itm_name
											FROM
												itminfo_tab
											ORDER BY
												itminfo_tab.frmindex ASC");
						while ($row = mysql_fetch_array($items))
						{
							if ($selItem == $row['itmrec_id'])
								$sel = "selected='selected'";
							else
								$sel = "";
                        ?>
                        	<option value="<?php echo $row['itmrec_id'];?>" <?php  echo $sel;?>><?php echo $row['itm_name'];?></option>
                        <?php
                        }
                        ?>                            
                        </select>
                    <td style="width:120px;">
                        <label>Type</label>
                        <select name="type" id="type" required style="width:110px;">
                        	<option value="">Select</option>
                            <option value="Issue" <?php echo ($type == 'Issue') ? "selected='selected'" : "";?>>Issue</option>
                            <option value="Receive" <?php echo ($type == 'Receive') ? "selected='selected'" : "";?>>Receive</option>
                        </select>
                    </td>
                    <td bgcolor="#FFFFFF" style="margin-left:20px; padding-top: 14px;" valign="middle">
                        <input type="submit" name="submit" id="go" value="GO" class="input_button" />
                    </td>
                </tr>
            </table>
        </form>

		<div style="clear:both;"></div>        
        <?php
		if ($_REQUEST['submit'])
		{
            if ( $num > 0 )
            {
        ?>
        <table width="99%" cellpadding="0" cellspacing="0" id="myTable">
        	<tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td style="text-align:right;">
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
			else
			{
				echo '<h6>No record found.</h6>';
			}
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
	}
	
	writeXML('stock.xml', $xmlstore);
	?>
    </div>
    
    <script type="text/javascript" src="../../plmis_js/zebra_datepicker.js"></script>
    <script>
    	$('#dateFrom, #dateTo').Zebra_DatePicker({
			 format: 'd/m/Y'
		});
    </script>
    
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>
