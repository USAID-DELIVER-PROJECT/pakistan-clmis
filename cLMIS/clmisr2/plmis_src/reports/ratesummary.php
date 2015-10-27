<?php
$rin_type = $in_type;
$rin_month = $in_month;   
$rin_year = $in_year;
$rin_item = $in_item;
$rin_stk = $in_stk;   
$rin_prov = $in_prov;
if(isset($lvl_stktype) || $lvl_stktype != '')
$rin_lvlType=$lvl_stktype;
else
$rin_lvlType=0;

//print $rin_type.':'.$rin_month.':'.$rin_year.':'.$rin_item.':'.'F'.':'.$rin_stk.':'.$rin_prov.':'.$rin_dist.':'.$rin_lvlType;

$rin_dist = 0; 

$fieldReportingRate = getReportingRateStr($rin_type,$rin_month,$rin_year,$rin_item,'F',$rin_stk,$rin_prov,$rin_dist,$rin_lvlType)."&#37;";
/*print $fieldReportingRate;*/
$warehouseReportingRate = getReportingRateStr($rin_type,$rin_month,$rin_year,$rin_item,'W',$rin_stk,$rin_prov,$rin_dist,$rin_lvlType)."&#37;";
$fieldAvailabilityRate = getAvailabilityRateStr($rin_type,$rin_month,$rin_year,$rin_item,'F',$rin_stk,$rin_prov,$rin_dist,$rin_lvlType)."&#37;";
$warehouseAvailabilityRate = getAvailabilityRateStr($rin_type,$rin_month,$rin_year,$rin_item,'W',$rin_stk,$rin_prov,$rin_dist,$rin_lvlType)."&#37;";
if(empty($rin_item))
	$myitem =  'IT-001';
else
$myitem = $rin_item;
/*if(empty($rin_prov))
	$myprov = 0;
else
$myprov = $rin_prov;*/
if(!empty($_GET['prov_sel']))
	$myprov = $_GET['prov_sel'];	
else if(!empty($rin_stk))
	$myprov = $rin_prov;
else
$myprov = 0;
if(!empty($_GET['stkid']))
	$mystk = $_GET['stkid'];	
else if(!empty($rin_stk))
	$mystk = $rin_stk;
else
$mystk = 0;
?>

<div class="col-md-12">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="dashboard-stat_custom">
            <div class="visual" style="width: auto;">
                <i class="fa fa-report-icon"></i>
            </div>
            <div class="details" style="float: left !important; left:0px !important;">
                <div class="dashboard-title-1"> Reporting </div>
                <div class="dashboard-title-2"> Rate <a href="non_report.php?tp=f&report_month=<?php echo $rin_month;?>&report_year=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="25" border="0" height="15"></a></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="dashboard-stat_custom pull-right">
            <div class="visual" style="width: auto;">
                <i class="fa fa-report-icon"></i>
            </div>
            <div class="details" style="float: right !important;">
                <div class="dashboard-title-1"> Availability </div>
                <div class="dashboard-title-2"> Rate <a href="itemsreport.php?tp=f&report_month=<?php echo $rin_month;?>&report_year=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="25" border="0" height="15"></a></div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 row-br-tb">
    <div class="pull-left">
        <ul class="nav navbar-nav report-value-tab-left">
            <li>
                <div class="report-value-orange">
                    <?php echo $fieldReportingRate;?> <a href="../reports/non_report.php?lvl_type=4&month_sel=<?php echo $rin_month;?>&year_sel=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="20" border="0" height="10"></a>
                </div>
                Field
            </li>
            <li>
                <div class="report-value-green">
                    <?php echo $warehouseReportingRate;?><a href="../reports/non_report.php?lvl_type=3&month_sel=<?php echo $rin_month;?>&year_sel=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="20" border="0" height="10"></a>
                </div>
                Store/Facility:
            </li>
        </ul>
    </div>
    <div class="pull-right">
        <ul class="nav navbar-nav report-value-tab-right">
            <li>
                <div class="report-value-orange">
                    <?php echo $fieldAvailabilityRate;?><a href="../reports/itemsreport.php?lvl_type=4&report_month=<?php echo $rin_month;?>&report_year=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="20" border="0" height="10"></a>
                </div>
                Field
            </li>
            <li>
                <div class="report-value-green">
                    <?php echo $warehouseAvailabilityRate;?><a href="../reports/itemsreport.php?lvl_type=3&report_month=<?php echo $rin_month;?>&report_year=<?php echo $rin_year;?>&item_id=<?php echo $myitem;?>&stk_id=<?php echo $mystk ;?>&prov_id=<?php echo $myprov;?>"><img src="../../plmis_img/book02.gif" title="Detail" width="20" border="0" height="10"></a>
                </div>
                Store/Facility:
            </li>
        </ul>
    </div>
</div>

