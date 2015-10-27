<?php
include("../../html/adminhtml.inc.php");
Login();

//////////// GET FILE NAME FROM THE URL
$arr = explode("?", basename($_SERVER['REQUEST_URI']));
$basename = $arr[0];
$filePath = "plmis_src/reports/".$basename;



$report_id = "BATCHEXPIRY";

	$report_title = "Batch Expiry Report";

$actionpage = "batch_expiry_report.php";
//$parameters = "TSP";
$where="";
if(isset($_POST))
	{
		if(isset($_POST['month_sel']) && !empty($_POST['month_sel']))
		{
			$sel_month = $_POST['month_sel'];
			$where.="where month(stock_batch.batch_expiry) =".$sel_month;
		}
		if(isset($_POST['year_sel']) && !empty($_POST['year_sel']))
		{
			$sel_year = $_POST['year_sel'];
			$where.=" AND year(stock_batch.batch_expiry) ='".$sel_year."'";
		}
		if(isset($_POST['prod_sel']) && !empty($_POST['prod_sel']))
		{
			$sel_item = $_POST['prod_sel'];
			$where.=" AND stock_batch.item_id =".$sel_item;
		}
		
	}
?>
<?php startHtml($system_title." - $report_title");?>

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



    <?php
			$counter = 1;
			   $querywh = "SELECT
								stock_batch.batch_id,
								stock_batch.batch_no,
								stock_batch.batch_expiry,
								stock_batch.item_id,
								sum(stock_batch.Qty) as Qty,
								stock_batch.`status`,
								stock_batch.production_date,
								stock_batch.wh_id,
								itminfo_tab.itm_name as product,
								itminfo_tab.itm_type
								FROM
								stock_batch
								INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
								$where
								GROUP BY
								stock_batch.batch_no					
								";

			$rswh = mysql_query($querywh) or die(mysql_error());
			$xmlstore="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			$xmlstore .="<rows>\n";
			$proDate='';
			while($rsRow2 = mysql_fetch_array($rswh)) {
				if(!empty($rsRow2[production_date]))
				{
				$proDate=date('d/m/Y',strtotime($rsRow2[production_date]));
				}
				$xmlstore .="\t<row id=\"$counter\">\n";
			
			$xmlstore .="\t\t<cell style=\"text-align:center;;\">$counter</cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[batch_no]]]></cell>\n";  
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[product]]]></cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[Qty]]]></cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$rsRow2[itm_type]]]></cell>\n";  
			$xmlstore .="\t\t<cell><![CDATA[".date('d/m/Y',strtotime($rsRow2[batch_expiry]))."]]></cell>\n";
			$xmlstore .="\t\t<cell><![CDATA[$proDate]]></cell>\n";
			$xmlstore .="\t</row>\n";
			//print $xmlstore;
			$counter++;
				}
				$xmlstore .="</rows>\n";
	//echo getReportDescriptionFooter($report_id); 

	////////////// GET Product Name
?> 
<script>
	var mygrid;
	function doInitGrid(){
		mygrid = new dhtmlXGridObject('mygrid_container');
		mygrid.selMultiRows = true;
		mygrid.setImagePath("../operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
		mygrid.setHeader("<div style='text-align:center; font-size:14px; font-weight:bold; font-family:Helvetica'><?php echo "Batch Expiry Date Report";?></div>,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan");
		mygrid.attachHeader("<span>S. No.</span>,<span>Batch No.</span>,<span>Product</span>,<span>Quantity</span>,<span>Units</span>,<span>Expiry</span>,<span>Production Date</span>");
		mygrid.setInitWidths("100,250,180,150,140,140,140");
		mygrid.setColAlign("left,left,left,left,left,left,left");
		//mygrid.setColSorting("int,int,int,int,int,int");
		mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
		mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
		mygrid.setSkin("light");
		mygrid.init();
		mygrid.loadXML("xml/batch_expiry.xml");
	}
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
  	
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align="" style="min-height:679px;"><br>
    
            <?php showBreadCrumb();?><div style="float:right"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
    
            <table width="99%">
                <tr>
                    <td colspan="8">
						<form name="filter" id="filter" method="post" action="">
								 <div class="sb1NormalFont">Product</div>
                                    <select name="prod_sel" id="prod_sel" class="input_select" required>
	                            
                                <option value="">Select</option>  
                                  <?php
                                  $querypro = "SELECT itmrec_id,itm_id,itm_name FROM itminfo_tab WHERE itm_status='Current' ORDER BY frmindex";
                                  $rspro = mysql_query($querypro) or die();
                                  while ($rowpro = mysql_fetch_array($rspro)) {
                                    if ($rowpro['itm_id'] == $sel_item)
                                      $sel = "selected='selected'";
                                    else
                                      $sel = "";
                                    ?>
                                    <option value="<?php echo $rowpro['itm_id']; ?>" <?php echo $sel; ?>><?php echo $rowpro['itm_name']; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                            <select name="month_sel" id="month_sel" class="input_select" style="width:160px" required>

                                <option value="">Select Expiry Month</option>  
                                  <?php
                                  for ($i = 1; $i <= 12; $i++) {
                                    if ($sel_month == $i)
                                      $sel = "selected='selected'";
                                    else
                                        $sel = "";
                                    ?>
                                <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                            <select name="year_sel" id="year_sel" class="input_select" style="width:160px" required>

                                <option value="">Select Expiry Year</option>  
                                  <?php                                   
                                  for ($j = date('Y'); $j >= 2010; $j--) {
                                    if ($sel_year == $j)
                                      $sel = "selected='selected'";
                                    else
                                        $sel = "";
                                    ?>
                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                    <?php
                                  }
                                  ?>
                            </select>
                            <input type="submit" name="submit" value="GO" class="input"/>
                            </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="sb1NormalFont">
                      Choose skin to apply: 
                      <select onChange="mygrid.setSkin(this.value)">
                        <option value="light" selected>Light
                        <option value="sbdark">SB Dark
                        <option value="gray">Gray
                        <option value="clear">Clear
                        <option value="modern">Modern
                        <option value="dhx_skyblue">Skyblue
                    </select>
                    </td>
                    <td align="right" colspan="6">
                        <img style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('../operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                        <img style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('../operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                   </td>
                </tr>
            </table>
    		
         	<?php //if($numr2>0){?>
            <table width="99%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="mygrid_container" style="width:100%; height:470px; background-color:white;overflow:hidden"></div>
                    
                    </td>
                </tr>
 
            </table>
                 
            </div>
        </div>
		<?php
		
        //XML write function
        function writeXML($xmlfile, $xmlData)
        {
			$xmlfile_path= REPORT_XML_PATH.$xmlfile;
			$handle = fopen($xmlfile_path, 'w');
			fwrite($handle, $xmlData);
        }
        
        writeXML('batch_expiry.xml', $xmlstore);
		?>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>