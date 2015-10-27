<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();
$sCriteria = '';
$type = '2';
$stk = '';
$locID = '';
$dateFrom = '';
$dateTo = '';

if ($_REQUEST['go'])
{
	$date = '';
	
	$type = $_REQUEST['type'];
	$stk = $_REQUEST['stk'];
	$locID = $_REQUEST['locID'];
	$dateFrom = $_REQUEST['dateFrom'];
	$dateTo = $_REQUEST['dateTo'];
	
	if ( !empty($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom']) )
	{
		$fromArr = explode('/', $_REQUEST['dateFrom']);
		$toArr = explode('/', $_REQUEST['dateTo']);
		$date .= " AND stock_master.CreatedOn BETWEEN '".$fromArr[2].'-'.$fromArr[1].'-'.$fromArr[0]."' AND '".$toArr[2].'-'.$toArr[1].'-'.$toArr[0]."' ";
	}
	if ( $type == 1 )
	{
		$qry = "SELECT
					stakeholder.stkname,
					WHFrom.wh_name AS WHFrom,
					WHFrom.wh_type_id AS WHFromType,
					stock_master.TranDate,
					stock_master.TranNo,
					stock_master.TranTypeID,
					stock_master.TranRef,
					WHTo.wh_name AS WHTo,
					WHTo.wh_type_id AS WHToType,
					stock_detail.Qty,
					stock_batch.batch_no,
					stock_batch.batch_expiry,
					itminfo_tab.itm_name,
					tbl_locations.LocName
				FROM
					stakeholder
				INNER JOIN tbl_warehouse AS WHTo ON stakeholder.stkid = WHTo.stkid
				INNER JOIN stock_master ON WHTo.wh_id = stock_master.WHIDTo
				INNER JOIN tbl_warehouse AS WHFrom ON stock_master.WHIDFrom = WHFrom.wh_id
				INNER JOIN stock_detail ON stock_detail.fkStockID = stock_master.PkStockID
				INNER JOIN stock_batch ON stock_batch.batch_id = stock_detail.BatchID
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				INNER JOIN tbl_locations ON WHTo.prov_id = tbl_locations.PkLocID
				WHERE
					stakeholder.stkid = ".$stk."
					AND WHTo.prov_id = ".$locID."
					AND stock_master.TranTypeID = 1
					$date
				ORDER BY
					stock_master.TranNo,
					itminfo_tab.frmindex ASC";
	}
	else if ( $type == 2 )
	{
		$qry = "SELECT
					stakeholder.stkname,
					WHFrom.wh_name AS WHFrom,
					WHFrom.wh_type_id AS WHFromType,
					stock_master.TranDate,
					stock_master.TranNo,
					stock_master.TranTypeID,
					stock_master.TranRef,
					WHTo.wh_name AS WHTo,
					WHTo.wh_type_id AS WHToType,
					stock_detail.Qty,
					stock_batch.batch_no,
					stock_batch.batch_expiry,
					itminfo_tab.itm_name,
					tbl_locations.LocName
				FROM
					stakeholder
				INNER JOIN tbl_warehouse AS WHFrom ON stakeholder.stkid = WHFrom.stkid
				INNER JOIN stock_master ON WHFrom.wh_id = stock_master.WHIDFrom
				INNER JOIN tbl_warehouse AS WHTo ON WHTo.wh_id = stock_master.WHIDTo
				INNER JOIN stock_detail ON stock_master.PkStockID = stock_detail.fkStockID
				INNER JOIN stock_batch ON stock_batch.batch_id = stock_detail.BatchID
				INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
				INNER JOIN tbl_locations ON WHTo.prov_id = tbl_locations.PkLocID
				WHERE
					stakeholder.stkid = ".$stk."
					AND WHFrom.prov_id = ".$locID."
					AND stock_master.TranTypeID = 2
					$date
				ORDER BY
					stock_master.TranNo,
					itminfo_tab.frmindex ASC";
	}
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows($qryRes);
	
	$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xmlstore .="<rows>\n";
	$counter = 1;
	$counter2 = 1;
	$trnsNum = '000000';
	while($row = mysql_fetch_array($qryRes))
	{
		$whNameFrom = $row['stk'];
		$provName = $row['LocName'];
		$stkName = $row['stkname'];
		
		if ( $row['TranNo'] != $trnsNum )
		{
			$trnsNum = $row['TranNo'];
			
			$xmlstore .="\t<row id=\"$counter\">\n";
			$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:left;\">$trnsNum</div>]]></cell>\n";
			$xmlstore .="\t</row>\n";
			$counter2 = 1;
			$counter++;
		}
		
		$whFrom = $row['WHFrom'].' ('.$row['WHFromType'].')';
		$whFrom = $row['WHFrom'];
		$whTo = $row['WHTo'];
		$whTo .= !empty($row['WHToType']) ? ' ('.$row['WHToType'].')' : '';
		
		$xmlstore .="\t<row id=\"$counter\">\n";
		$xmlstore .="\t\t<cell></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:center;\">$counter2</div>]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:left;\">$whFrom</div>]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:left;\">$whTo</div>]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:center;\">".date('d/m/Y', strtotime($row['TranDate']))."</div>]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:left;\">$row[itm_name]</div>]]></cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:right;\">".number_format($row[Qty])."</div>]]></cell>\n";
		$xmlstore .="\t\t<cell>$row[batch_no]</cell>\n";
		$xmlstore .="\t\t<cell><![CDATA[<div style=\"text-align:center;\">".date('d/m/Y', strtotime($row['batch_expiry']))."</div>]]></cell>\n";
		$xmlstore .="\t</row>\n";
		
		$counter2++;
		$counter++;
	}
	
	$xmlstore .="</rows>\n";
	writeXML('ship_report.xml', $xmlstore);
	
	$trType = ($_REQUEST['type'] == 1) ? 'Receive' : 'Issue';
	$sCriteria .= "Type = '$trType' ";
	$sCriteria .= "Stakeholder = '$stkName' ";
	$sCriteria .= "Province = '$provName' ";
	
	if ( !empty($_REQUEST['dateFrom']) && !empty($_REQUEST['dateFrom']) )
	{
		$sCriteria .= " Date = '".date('d/m/Y', strtotime($_REQUEST['dateFrom']))." To ".date('d/m/Y', strtotime($_REQUEST['dateTo']))."' ";
	}
}

startHtml($system_title." - Stock Shipment Reports");?>

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
</style>

<link rel="stylesheet" href="../../css/default.css" type="text/css">
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
		//mygrid.setHeader("Province,Consumption,AMC,On Hand,MOS,#cspan");
		mygrid.setHeader("<div style='text-align:center; font-size:13px; font-weight:bold; font-family:Helvetica'>Stock Shipment Report<br><?php echo $sCriteria;?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span title='Transaction Number'>Issue No.</span>,<span title='Serial Number'>Serial</span>,<span title='Warehouse Stock Issued From'>Warehouse From</span>,<span title='Warehouse Stock Issued To'>Warehouse To</span>,<span title='Transaction Date'>Date</span>,<span title='Product Name'>Product</span>,<span title='Quantity'>Quantity</span>,<span title='Batch No'>Batch No</span>,<span title='Expiry Date'>Expiry Date</span>");
		mygrid.setInitWidths("100,50,150,150,70,*,80,70,80");
		//mygrid.setInitWidths("*,50,50,50,50,50,50,50,50,50,50,50,50");
		mygrid.setColAlign("center,center,center,center,center,center,center,center,center");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro");
		//mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/ship_report.xml");
	}
</script>
</head>
<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">

	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content" align=""><br>
		<?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
		
		<form name="frm" id="frm">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
                <tr height="34">
                    <td colspan="7" align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF;">Stock Ship Report</td>
                </tr>
                <tr><td colspan="7">&nbsp;</td></tr>
                <tr>
                    <td class="sb1NormalFont" bgcolor="#FFFFFF" width="100px" rowspan="2"><strong>Filter by:</strong></td>
                	<td align="center">
                        <div class="sb1NormalFont">Type:</div><strong></strong>
                    </td>
                	<td align="center">
                        <div class="sb1NormalFont">Stakeholder:</div><strong></strong>
                    </td>
                	<td align="center">
                        <div class="sb1NormalFont">Province:</div><strong></strong>
                    </td>
                	<td align="center">
                        <div class="sb1NormalFont">Date From:</div><strong></strong>
                    </td>
                	<td align="center">
                        <div class="sb1NormalFont">Date To:</div><strong></strong>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            	<tr>
                    <td class="normalFont">
                        <input type="radio" name="type" id="type" value="2" <?php echo ($type == 2) ? 'checked="checked"' : '';?> />Issue
                        <input type="radio" name="type" id="type" value="1" <?php echo ($type == 1) ? 'checked="checked"' : '';?> />Receive
                    </td>
                    <td>
                    	<select name="stk" id="stk" class="input_select" style="width:100%;">
                            <?php
                            $whFrmQry = mysql_query("SELECT
														stakeholder.stkid,
														stakeholder.stkname
													FROM
														stakeholder");
							while ( $row = mysql_fetch_array($whFrmQry))
							{
								$sel = ($stk == $row['stkid']) ? 'selected="selected"' : '';
								echo "<option value=\"$row[stkid]\" $sel>$row[stkname]</option>";
							}
							?>
                        </select>
                    </td>
                    <td>
                    	<select name="locID" id="locID" class="input_select" style="width:100%;">
                            <?php
                            $whFrmQry = mysql_query("SELECT
														tbl_locations.PkLocID,
														tbl_locations.LocName
													FROM
														tbl_locations
													WHERE
														tbl_locations.ParentID IS NOT NULL
													AND tbl_locations.LocLvl = 2");
							while ( $row = mysql_fetch_array($whFrmQry))
							{
								$sel = ($locID == $row['PkLocID']) ? 'selected="selected"' : '';
								echo "<option value=\"$row[PkLocID]\" $sel>$row[LocName]</option>";
							}
							?>
                        </select>
                    </td>
                    <td>
                    	<input type="date" name="dateFrom" id="dateFrom" class="input_text" value="<?php echo $dateFrom;?>" />
                    </td>
                    <td>
                    	<input type="date" name="dateTo" id="dateTo" class="input_text" value="<?php echo $dateTo;?>" />
                    </td>
                    <td>
                    	<input type="submit" name="go" id="go" value="Search" class="input_button" />
                    </td>
                </tr>
            </table>
        </form>

		<div style="clear:both;"></div>        
        <?php
		if ($_REQUEST['go'])
		{
		?>
        <table width="99%" cellpadding="0" cellspacing="0">
            <?php
            if ($num > 0)
			{
			?>
            <tr>
                <td style="text-align:right; padding-right:5px;">
                    <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                    <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
               </td>
            </tr>
            <tr>
                <td>
                    <div id="mygrid_container" style="width:100%; height:390px; background-color:white;overflow:hidden"></div>
                </td>
            </tr>
            <?php 
			}
			else
			{
				echo '<tr><td colspan="2">No record found.</td></tr>';
			}
			?>
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
	}
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
