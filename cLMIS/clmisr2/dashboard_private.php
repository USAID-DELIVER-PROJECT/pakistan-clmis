<?php
include("html/adminhtml.inc.php");
Login();

include "plmis_inc/common/_header.php";
include("FusionCharts/Code/PHP/Includes/FusionCharts.php");

if ( !isset($_POST['year']) )
{
	if ( date('d') > 10 )
	{
		$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m'))));
	}
	else
	{
		$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m'))));
	}
}
$month = date('m', strtotime($date));
$year = date('Y', strtotime($date));
$level = $_SESSION['userdata'][9];
$province = $_SESSION['userdata'][10];
$district = $_SESSION['userdata'][11];
$itemId = 1;
$proFilter = 2;
?>
<style>
.widget-head ul{padding-left:0px !important;}
#map{width:100%;height:390px;position: relative}
#loader{display:none;width: 70px;height: 70px;position:absolute;left:45%;top:40%;z-index: 2000}
#inputForm{width:50%;height:25px;position: absolute;top:4px;left:10%;z-index: 2000}
#mapTitle{position:absolute;top:24%;left:2%;width:150px;height:15px;text-align:center;}
#legendDiv{display:none;position:absolute;padding:2px;border-radius:6px;font-size:8px;background-color:none;border:1px solid black;width:auto;height:auto;top:57%;left:70%;z-index: 3000;}
.pageLoader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('plmis_img/ajax-loader.gif') 50% 50% no-repeat rgb(249,249,249);
}
/*.col-md-6{min-height:450px !important;}*/
#loadingmessage{height:450px !important;}
#loadingmessage img{margin-top:150px !important;}
select.input-sm{padding:0px !important;}
</style>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="plmis_js/maps/cyp_dashlet.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="plmis_js/maps/dashlet_Interval.js"></SCRIPT>
<script>
	function exportChartStacked(exportFormat) {
		// checks if exportChart function is present and call exportChart function
		if (FusionCharts("myFirst").exportChart)
			FusionCharts("myFirst").exportChart({ "exportFormat":exportFormat });
		else
			alert("Please wait till the chart completes rendering...");
	}

	function exportChart(exportFormat, chartID) {
		// checks if exportChart function is present and call exportChart function
		if (FusionCharts(chartID).exportChart)
			FusionCharts(chartID).exportChart({ "exportFormat":exportFormat });
		else
			alert("Please wait till the chart completes rendering...");
	}
</script>
<!--[if IE]>
<style type="text/css">
    .box { display: block; }
    #box { overflow: hidden;position: relative; }
    b { position: absolute; top: 0px; right: 0px; width:1px; height: 251px; overflow: hidden; text-indent: -9999px; }
</style>

</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
<!--<div class="pageLoader"></div>-->
<!-- BEGIN HEADER -->


<div class="page-container">
	<?php include "plmis_inc/common/_top.php";?>
    <?php include "plmis_inc/common/top_im.php";?>
    
    <div class="page-content-wrapper">
        <div class="page-content">
			<div class="row">
            	<div class="col-md-12">
                	<div class="tabsbar">
                        <ul>
                            <li><a href="dashboard.php"> <b>Public Sector</b></a></li>
                            <li class="active"><a href="#"> <b>Private Sector</b></a></li>
                        </ul>
                    </div>
               	</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                	<form name="frm" id="frm" action="" method="post">
                        <div class="col-md-1">
                            <label for="month">Month</label>
                            <div class="form-group">
                                <select name="month" id="month" class="form-control input-sm">
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        if ($month == $i)
                                            $sel = "selected='selected'";
                                        else
                                            $sel = "";
                                    ?>
                                    <option value="<?php echo $i; ?>"<?php echo $sel; ?> ><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="year">Year</label>
                            <div class="form-group">
                                <select name="year" id="year" class="form-control input-sm" style="width:60px;">
                                    <?php
                                    for ($j = date('Y'); $j >= 2010; $j--) {
                                        if ($year == $j)
                                            $sel = "selected='selected'";
                                        else
                                            $sel = "";
                                    ?>
                                    <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="office-Level">Office Level</label>
                            <div class="form-group">
                                <select name="ofc_level" id="ofc_level" class="form-control input-sm">
                                	<option value="1" <?php echo ($level == 1) ? 'selected="selected"' : '';?>>National</option>
                                	<option value="2" <?php echo ($level == 2) ? 'selected="selected"' : '';?>>Provincial</option>
                                	<option value="3" <?php echo ($level == 3) ? 'selected="selected"' : '';?>>District</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="provinceArea" style="display:none;"></div>
                        <div class="col-md-2" id="districtArea" style="display:none;"></div>
                        <div class="col-md-2">
                            <label for="office-Level">Product</label>
                            <div class="form-group">
                                <select name="product_filter" id="product_filter" class="form-control input-sm">
                                	<option value="1" <?php echo ($proFilter == 1) ? 'selected="selected"' : '';?>>All With Condom</option>
                                	<option value="2" <?php echo ($proFilter == 2) ? 'selected="selected"' : '';?>>All Without Condom</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="firstname">&nbsp;</label>
                            <div class="form-group">
                                <button type="button" id="search" name="search" value="search" class="btn btn-primary input-sm">Go</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            
            <div class="widget widget-tabs">
                <!-- Tabs Heading -->
                <div class="widget-head" style="border-bottom:0;">
                    <ul>
                    <?php
                    // Get Stakeholders
                    $stk = "SELECT DISTINCT
								MainStk.stkid,
								MainStk.stkname
							FROM
								stakeholder
							INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
							WHERE
								stakeholder.stk_type_id = 1
							ORDER BY
								MainStk.stkorder ASC";
                    $stkQuery = mysql_query($stk);
                    $stkQuery1 = mysql_query($stk);
                    $counter = 1;
                    while ($row = mysql_fetch_array($stkQuery)) {
                        $active = ($counter == 1) ? 'class="active"' : '';
                        $counter++;
                        if($row['stkid'] != 8)
                        {
                    ?>
                        <li <?php echo $active;?>><a onClick="selectStk(<?php echo $counter;?>)" href="#stock-status-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
                    <?php
                        }
                    }
                    ?>
                    </ul>
                </div>
                <!-- // Tabs Heading END -->
            </div>
            
            <div class="row">
            	<div class="col-md-6" id="dashlet1" href='stock_status.php'></div>
                <div class="col-md-6" id="dashlet2" href='shipment_main_dash_private.php'></div>
			</div>
            
            <div class="row">
                <div class="col-md-6" id="dashlet3" href='consumption.php'></div>
            	<div class="col-md-6" id="dashlet4" href='cyp.php'></div>
			</div>
            
            <div class="row">
                <div class="col-md-6" id="dashlet6">
					<div class="widget widget-tabs">
                        <div id='map'>
                            <div id="legendDiv">
                                <table id='legend'></table>
                            </div>
                            <div id="mapTitle"></div> 
                            <img id="loader" src="plmis_img/ajax-loader.gif"/>
                            <div id='inputForm'>
                                <table>
                                    <tr>
                                        <td>
                                            <select name="stk_sel" id="stk_sel" width="150px" class="form-control input-small input-sm" style="width:120px">           
                                            <?php
                                            $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null AND stk_type_id IN (1) order by stkorder";
                                            $rsstk = mysql_query($querystk) or die();
                                            while ($rowstk = mysql_fetch_array($rsstk)) {
                                                if ($sel_stk == $rowstk['stkid'])
                                                    $sel = "selected='selected'";
                                                else
                                                    $sel = "";
                                            ?>
                                                <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
                                            <?php
                                            }
                                            ?>
                                            </select>
                                        </td>
                                        <td>
                                             <?php 
                                                $sel_prov = $_SESSION["prov_id"];
                                                if($sel_prov == "10"){
                                                    $sel_prov = "all";
                                                }
                                               ?> 
                                           <select name="prov_sel" id="prov_sel" class="input_select" style="display:none">
                                           <option value="all">All</option>
                                                        <?php
                                                         $queryprov = "SELECT tbl_locations.PkLocID as prov_id, tbl_locations.LocName as prov_title
                                                                       FROM tbl_locations where LocLvl=2 and parentid is not null";
                                                         $rsprov = mysql_query($queryprov) or die();
                                                         while ($rowprov = mysql_fetch_array($rsprov)) {
                                                           if ($sel_prov == $rowprov['prov_id'])
                                                             $sel = "selected='selected'";
                                                           else
                                                             $sel = "";
                                                           ?>
                                                           <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
                                                           <?php
                                                         }
                                                         ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php $sel_item = 'IT-001'; ?>
                                            <select name="prod_sel" id="prod_sel" class="form-control input-small input-sm" style="width:100px">
                                                
                                            <?php
                                            $querypro = "SELECT
                                                 itminfo_tab.itmrec_id,
                                                 itminfo_tab.itm_name,
                                                 itminfo_tab.itm_type
                                                        FROM
                                                                itminfo_tab
                                                        WHERE
                                                                itminfo_tab.itmrec_id NOT IN ('IT-010', 'IT-014', 'IT-012')
                                                        ORDER BY
                                                        itminfo_tab.frmindex ASC";
                                            $rspro = mysql_query($querypro) or die();
                                            while ($rowpro = mysql_fetch_array($rspro)) {
                                                if ($rowpro['itmrec_id'] == $sel_item)
                                                    $sel = "selected='selected'";
                                                else
                                                    $sel = "";
                                            ?>
                                            <option value="<?php echo $rowpro['itmrec_id']; ?>" <?php echo $sel; ?>><?php echo $rowpro['itm_name']; ?></option>
                                            <?php
                                            }
                                            ?>
                                            </select>
                                        </td>
                                         
                                        <td>
                                            <select name="year" id="year" class="form-control input-sm" style="display:none">
                                                        <?php
                                                        for ($j = date('Y'); $j >= 2010; $j--) {
                                                            if ($year == $j)
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
                                        <td>
                                            <input type="submit" name="go" id="go" onClick="getData()" value="GO" class="btn green input-sm" />
                                        </td>
                                    
                                    </tr>   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            
		</div>
    </div>
</div>

<a class="btn btn-primary" data-toggle="modal" href="#modal-simple" id="modalId" style="display:none;">Modal</a>
<div class="modal fade" id="modal-simple" tabindex="-1" role="basic" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">         
        <!-- Modal body -->
        <div class="modal-body" id="modalData" style="height:70% !important; overflow:scroll; overflow-x:hidden;"></div>
        <!-- // Modal body END --> 
        
        <!-- Modal footer -->
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a> </div>
    </div>
</div>
<!-- // Modal footer END -->
	
</div>
    
<?php include "plmis_inc/common/footer.php";?>
<script type="text/javascript">
	/*$(window).load(function() {
		$(".pageLoader").fadeOut("slow");
	})*/
	
	function selectStk(tabId)
	{
		$('a[href="#stock-shipment-'+tabId+'"]').trigger('click');
		$('a[href="#consumption-'+tabId+'"]').trigger('click');
		$('a[href="#CYP-'+tabId+'"]').trigger('click');
		return false;
	}
	$(function(){	
		if ( $('#ofc_level').val() == 1 )
		{
			loadDashlets();
			$("#provinceArea").hide();
			$("#districtArea").hide();
		}
		if ( $('#ofc_level').val() == 2 )
		{
			showProvinces1('<?php echo $province;?>');
			$("#provinceArea").show();
			$("#districtArea").hide();
		}
		if ( $('#ofc_level').val() == 3 )
		{
			showProvinces1('<?php echo $province;?>');
			$("#provinceArea").show();
			$("#districtArea").show();
		}
		
		// When search button is clicked
		$('#search').click(function(e) {
            loadDashlets();
        });
		// Show provinces
		$('#ofc_level').change(function(e) {
			var lvl = $('#ofc_level').val();
			if ( lvl == 1 )
			{
				$("#provinceArea").hide();
				$("#districtArea").hide();
			}
			if ( lvl == 2 )
			{
				showProvinces();
				$("#provinceArea").show();
				$("#districtArea").hide();
			}
			if ( lvl == 3 )
			{
				showProvinces();
				$("#provinceArea").show();
				$("#districtArea").show();
			}
        });
		
	})
	
	function loadDashlets()
	{
		$('.col-md-6').each(function(i, obj) {
			$('.widget-head ul li').removeClass('active');
			$('a[href="#stock-status-2"]').parent('li').addClass('active');
			
			var url = $(this).attr('href');
			var id = $(this).attr('id');
			if (id != 'dashlet6')
			{
				var dataStr;
				dataStr = 'month='+$('#month').val();
				dataStr += '&year='+$('#year').val();
				dataStr += '&lvl='+$('#ofc_level').val();
				dataStr += '&proFilter='+$('#product_filter').val();
				if ($('#ofc_level').val() == 2)
				{
					dataStr += '&prov_id='+$('#prov_id').val();
				}
				if ( $('#ofc_level').val() == 3 )
				{
					dataStr += '&dist_id='+$('#dist_id').val();
				}
				dataStr += '&sector=1';
				
				$('#'+id).html("<center><div id='loadingmessage'><img src='plmis_img/ajax-loader.gif'/></div></center>");

				$.ajax({
					type: "POST",
					url: './dashboard/' + url,
					data: dataStr,
					dataType: 'html',
					success: function(data) {
						//setTimeout(function () {
							$("#" + id).html(data);
						//}, 5000); 
					}
				});
			}
		});
	}
	function showProvinces(){
		$.ajax({
			type: "POST",
			url: './dashboard/ajax.php',
			data: {lvl: 2},
			success: function(data) {
				$("#provinceArea").html(data);
				showDistricts();
			}
		});
	}
	
	function showDistricts(){
		$.ajax({
			type: "POST",
			url: './dashboard/ajax.php',
			data: {lvl: 3, prov_id: $('#prov_id').val()},
			success: function(data) {
				$("#districtArea").html(data);
			}
		});
	}
	function showProvinces1(provId){
		$.ajax({
			type: "POST",
			url: './dashboard/ajax.php',
			data: {lvl: 2, provId: provId},
			success: function(data) {
				$("#provinceArea").html(data);
				showDistricts1('<?php echo $district;?>');
			}
		});
	}
	
	function showDistricts1(distId){
		$.ajax({
			type: "POST",
			url: './dashboard/ajax.php',
			data: {lvl: 3, prov_id: $('#prov_id').val(), distId: distId},
			success: function(data) {
				$("#districtArea").html(data);
				loadDashlets();
			}
		});
	}
	function showData(param){
		$.ajax({
			type: "POST",
			url: './dashboard/ajax.php',
			data: {stockStatus: param},
			success: function(data) {
				$("#modalData").html(data);
			}
		});
		$('#modalId').trigger('click');
	}
	function loadGraph(stkId, type, tabId)
	{
		var dataStr;
		dataStr = 'month='+$('#month').val();
		dataStr += '&year='+$('#year').val();
		dataStr += '&lvl='+$('#ofc_level').val();
		dataStr += '&proFilter='+$('#product_filter').val();
		if ($('#ofc_level').val() == 2)
		{
			dataStr += '&prov_id='+$('#prov_id').val();
		}
		if ( $('#ofc_level').val() == 3 )
		{
			dataStr += '&dist_id='+$('#dist_id').val();
		}
		dataStr += '&sector=0';
		dataStr = dataStr+'&stkId='+stkId+'&type='+type;
		$.ajax({
			type: "POST",
			url: './dashboard/stock_status_ajax.php',
			data: dataStr,
			success: function(data) {
				$("#stock-"+tabId).html(data);
			}
		});
	}
</script>
</body>
<!-- END BODY -->
</html>