<?php
$welcome_msg = '<h2 id="textBold">Welcome, ' . $_SESSION['user'] . '&nbsp;&nbsp;';
$welcome_msg .= '<img style="vertical-align:middle; height:14px; width:14px; margin-right:2px; margin-left:10px;" src="' . PLMIS_IMG . 'logout.jpg" /><a href="JavaScript:Logout()">Logout</a></h2>';

$page = basename($_SERVER['PHP_SELF']);
$IM = array(
    'new_receive.php',
    'new_receive_wh.php',
    'stock_receive.php',
    'stock_placement.php',
    'placement_locations.php',
    'batch_management.php',
    'new_issue.php',
    'stock_issue.php',
    'pick_stock.php',
    'add_adjustment.php',
    'stock_adjustment.php',
    'add_stock.php',
    'stock_location.php',
    'bin_card.php',
    'pipeline_consignment.php'
);
$gatePass = array('new_gatepass.php', 'view_gatepass.php');
$requisitions = array('requisitions.php');
$monthlyReport = array('view_admin_whreport.php', 'view_admin_whreport.php');
$explorer = array('view_admin_whreport1.php');
$reports = array(
    'nationalreport.php',
    'nationalreportSTK.php',
    'provincialreport.php',
    'diststkreport.php',
    'stock.php',
    'itemsreport.php',
    'non_report.php',
    'quarterly_rate.php',
    'province_rate.php',
    'projected_contraceptive.php',
    'central_warehouse_report.php',
    'provincial_warehouse_report.php',
    'private_sector_report.php',
    'pp_sector_report.php',
	'countrywise_distribution.php',
    'stock_sufficiency_report.php',
    'stock_status.php',
	'shipment.php'
);
$pbsReports = array(
    'form14.php',
    'clr11.php',
    'spr1.php',
    'spr2.php',
    'pwd3.php'
);
$provincialReports = array(
    'spr3.php',
    'outlet_cyp_comparison.php',
    'district_cyp_comparison.php'
);
$distReports = array(
    'spr8.php',
    'spr9.php',
    'spr10.php',
    'spr11.php',
    'clr13.php',
    'clr15.php',
    'dpw_f1.php',
    'sale_proceeds.php',
    'satellite.php'
);
$graphs = array('templategraphreport.php', 'templategraphreport2.php');
$maps = array('mos.php', 'consumption.php', 'cyp.php', 'cyp_pop.php', 'reporting_rate.php');
$clr = array('new_clr.php', 'clr6.php');

if (in_array($page, $IM)) {
    $IM_li_open = ' class="open"';
    $IM_sub_display = 'style="display: block;"';
    $IM_arrow_open = 'open';
}
if (in_array($page, $gatePass)) {
    $GP_li_open = ' class="open"';
    $GP_sub_display = 'style="display: block;"';
    $GP_arrow_open = 'open';
}
if (in_array($page, $requisitions)) {
    $RQ_li_open = ' class="open"';
    $RQ_sub_display = 'style="display: block;"';
    $RQ_arrow_open = 'open';
}
if (in_array($page, $monthlyReport)) {
    $MR_li_open = ' class="open"';
    $MR_sub_display = 'style="display: block;"';
    $MR_arrow_open = 'open';
}
if (in_array($page, $explorer)) {
    $EX_li_open = ' class="open"';
    $EX_sub_display = 'style="display: block;"';
    $EX_arrow_open = 'open';
}
if (in_array($page, $reports)) {
    $RPT_li_open = ' class="open"';
    $RPT_sub_display = 'style="display: block;"';
    $RPT_arrow_open = 'open';
}
if (in_array($page, $pbsReports)) {
    $pbs_RPT_li_open = ' class="open"';
    $pbs_RPT_sub_display = 'style="display: block;"';
    $pbs_RPT_arrow_open = 'open';
}
if (in_array($page, $provincialReports)) {
    $prov_RPT_li_open = ' class="open"';
    $prov_RPT_sub_display = 'style="display: block;"';
    $prov_RPT_arrow_open = 'open';
}
if (in_array($page, $distReports)) {
    $dist_RPT_li_open = ' class="open"';
    $dist_RPT_sub_display = 'style="display: block;"';
    $dist_RPT_arrow_open = 'open';
}
if (in_array($page, $graphs)) {
    $GRP_li_open = ' class="open"';
    $GRP_sub_display = 'style="display: block;"';
    $GRP_arrow_open = 'open';
}
if (in_array($page, $maps)) {
    $MAP_li_open = ' class="open"';
    $MAP_sub_display = 'style="display: block;"';
    $MAP_arrow_open = 'open';
}
if (in_array($page, $clr)) {
    $CLR_li_open = ' class="open"';
    $CLR_sub_display = 'style="display: block;"';
    $CLR_arrow_open = 'open';
}
?>
<?php
$sql = "SELECT
		sysuser_tab.sysusr_type,
		sysuser_tab.sysusr_name,
		sysuser_tab.stkid,
		sysuser_tab.province,
		tbl_warehouse.is_allowed_im
	FROM
		sysuser_tab
	INNER JOIN tbl_warehouse ON sysuser_tab.whrec_id = tbl_warehouse.wh_id
	WHERE
		UserID = " . $_SESSION['userid'] . "";
$sql2 = mysql_query($sql);
$row_logo = mysql_fetch_array($sql2);

$province = $row_logo['province'];
$stkid = $row_logo['stkid'];
$ut = $row_logo['sysusr_type'];
$uname = $row_logo['sysusr_name'];
$IM_allowed = $row_logo['is_allowed_im'];
$_SESSION['im_open'] = $IM_allowed;
$userType = $_SESSION['UserType'];

#var_dump($_SESSION);

if ($ut == 'UT-005') {

    $flagGuest = TRUE;
} else {
    $flagGuest = FALSE;
}

$query = mysql_query("select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='" . $stkid . "' AND province_id='" . $province . "'");
//print "select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='".$stkid."' AND province_id='".$province."'";
//exit;
$row_image = mysql_fetch_array($query);
$logo = $row_image['logo'];
if ($logo == '') {
    $logo = "pak_federal.jpg";
}
?>

<!--Wiki-->

<div class="header navbar"> 
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner"> 
        <!-- BEGIN LOGO --> 
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>" style="margin-top: -10px;"> <img src="<?php echo ASSETS; ?>admin/layout/img/landing-images/contraceptive-logo.png" height="55" width="323" alt="vaccine LMIS" /> </a> 
        <!-- END LOGO --> 
        <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="<?php echo ASSETS; ?>img/menu-toggler.png" alt=""/> </a>
        <?php
        if (($_SESSION['userdata'][9] == 1 || $_SESSION['userdata'][9] == 3) && $flagGuest == FALSE) {
            ?>
            <ul class="nav navbar-nav pull-right">
                <li> <a href="<?php echo SITE_URL; ?>manuals.php" class="ul-header"> <img src="<?php echo ASSETS; ?>img/header-icon/training_manuals.png" alt=""/> </a> </li>
            </ul>
            <?php
        }
        ?>
        <!-- END RESPONSIVE MENU TOGGLER --> 
    </div>
    <!-- END TOP NAVIGATION BAR --> 
</div>
<div class="header-menu"> 
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="row" style="margin:0px !important; background:#f7f7f7 !important;">
        <div class="col-md-12">
            <ul class="page-breadcrumb breadcrumb">
                <li class="btn-group pull-right">
                    <button type="button" class="btn lightgrey dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> <span><?php echo $uname . ' '; ?></span><i class="fa fa-angle-down"></i> </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <?php
                        if (isset($_SESSION['user']) && $flagGuest == FALSE) {
                            ?>
                            <li> <a href="<?php echo SITE_URL; ?>plmis_admin/changePassUser.php"> 
                                <!--                    <i class="icon-home"></i>--> 
                                    Change Password</a> </li>
                            <?php
                        }
                        ?>

                        <!--<li class="divider"> </li>-->
                        <li> <a href="<?php echo SITE_URL; ?>Logout.php"> Sign Out </a> </li>
                    </ul>
                </li>
                <li class="white breadcrumb-border-left"><img src="<?php echo ASSETS; ?>admin/layout/img/landing-images/paki-fla.png" alt=""/></li>
                <li>
                    <?php showBreadCrumb(); ?>
                    <div style="float:right; padding-right:3px">
                        <?php //echo readMeLinks($readMeTitle); ?>
                    </div>
                    <!--<i class="fa fa-home"></i>
                    <a href="dashboard">Home</a>
                    <i class="fa fa-angle-right"></i>--> 
                </li>
            </ul>
        </div>
    </div>
    <!-- END TOP NAVIGATION BAR --> 
</div>
<!-- END HEADER --> 

<!--contaiiner-->
<div id="container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper"> 
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing --> 
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse"> 
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper charcol-clr">
                    <div class="sidebar-toggler hidden-phone"></div>
                    <div class="dashboard-header"> <span class=" welcm"> Welcome<br>
                            <span class="title"><?php echo $uname . ' '; ?></span> </span> </div>
                </li>

                <!--<li class="sidebar-toggler-wrapper charcol-clr">
            <div class="sidebar-toggler hidden-phone"></div>
            <div class="dashboard-header">
            <img src="<?php /* echo SITE_URL; */ ?>assets/img/dashboard.png" alt=""/>
                               </span>
            </div>
        </li>-->
                <li> <a href="<?php echo SITE_URL; ?>dashboard.php"> <i class="icon-home"></i> <span class="title">Home</span> </a> </li>
                <?php
                if (($_SESSION['userdata'][9] == 1 || $_SESSION['userdata'][9] == 3) && $flagGuest == FALSE) {
                    if ($IM_allowed == 1) {
                        ?>
                        <li <?php echo $IM_li_open; ?>> <a href="javascript:;"> <i class="fa-size fa-icon-stock"></i> <span class="title"> Inventory Management</span> <span class="arrow <?php echo $IM_arrow_open; ?>"></span> </a>
                            <ul class="sub-menu" <?php echo $IM_sub_display; ?>>
                                <?php if ($userType != 'UT-007') { ?>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/new_receive.php"> Stock Receive (Supplier)</a> </li>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/new_receive_wh.php"> Stock Receive (Warehouse)</a> </li>
                                <?php } ?>
                                <li> <a href="<?php echo SITE_URL; ?>plmis_admin/stock_receive.php"> Stock Receive Search</a> </li>
                                <li> <a href="<?php echo SITE_URL; ?>plmis_admin/stock_placement.php"> Placement Locations</a> </li>
                                <?php if ($userType != 'UT-007') { ?>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/placement_locations.php"> Location Status</a> </li>
                                <?php } ?>
                                <li> <a href="<?php echo SITE_URL; ?>plmis_admin/batch_management.php"> Batch Management</a> </li>
                                <?php if ($userType != 'UT-007') { ?>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/new_issue.php"> Stock Issue</a> </li>
                                <?php } ?>
                                <li> <a href="<?php echo SITE_URL; ?>plmis_admin/stock_issue.php"> Stock Issue Search</a> </li>
                                <?php if ($userType != 'UT-007') { ?>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/pick_stock.php"> Stock Pick</a> </li>
                                    <li> <a href="<?php echo SITE_URL; ?>plmis_admin/add_adjustment.php"> New Adjustments</a> </li>
                                <?php } ?>
                                <li> <a href="<?php echo SITE_URL; ?>plmis_admin/stock_adjustment.php"> Search Adjustments</a> </li>
                                <li id=""><a href="<?php echo SITE_URL; ?>plmis_admin/bin_card.php">Bin Card</a></li>
                                <li id=""><a href="<?php echo SITE_URL; ?>plmis_admin/pipeline_consignment.php">Add Pipeline Consignment</a></li>
                            </ul>
                        </li>
                        <li <?php echo $GP_li_open; ?>> <a href="javascript:;"> <i class="fa-size fa-icon-gatepass"></i> <span class="title">Gate Pass</span> <span class="arrow <?php echo $GP_arrow_open; ?>"></span> </a>
                            <ul class="sub-menu" <?php echo $GP_sub_display; ?>>
                                <?php if ($userType != 'UT-007') { ?>
                                    <li> <a href="<?php echo PLMIS_SRC; ?>operations/new_gatepass.php">New Gate Pass</a> </li>
                                <?php } ?>
                                <li> <a href="<?php echo PLMIS_SRC; ?>operations/view_gatepass.php">View Gate Pass</a> </li>
                            </ul>
                        </li>
                        <?php
                    }
                }
                if (($_SESSION['userdata'][9] == 1 || $_SESSION['userdata'][9] == 2) && $flagGuest == FALSE) {
                    ?>
                    <li <?php echo $RQ_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-requisitions"></i> <span class="title">Requisitions</span> <span class="arrow <?php echo $RQ_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $RQ_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>operations/requisitions.php"> Requisition Requests</a> </li>
                        </ul>
                    </li>
                    <?php
                }
                if ($_SESSION['userdata'][9] == 3 && $flagGuest == FALSE) {
                    ?>
                    <li <?php echo $CLR_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-clr6"></i> <span class="title">Requisitions</span> <span class="arrow <?php echo $CLR_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $CLR_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>operations/new_clr.php"> New Requisition</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>operations/clr6.php"> View Requisitions</a> </li>
                        </ul>
                    </li>
                    <?php
                }
                if ($flagGuest == FALSE) {
                    if ($_SESSION['userdata'][9] == 3 || $_SESSION['userdata'][9] == 4) {
                        ?>
                        <li> <a href="<?php echo SITE_URL; ?>plmis_admin/wh_data_entry.php"> <i class="fa-size fa-icon-dataentry"></i> <span class="title">Data Entry</span> </a> </li>
                        <?php
                    }
					if ($province == 3 && $_SESSION['userdata'][9] == 3) {
                        ?>
                        <li> <a href="<?php echo SITE_URL; ?>plmis_admin/satellite_wh.php"> <i class="fa-size fa-icon-dataentry"></i> <span class="title">Satellite Camps Data Entry</span> </a> </li>
                        <?php
                    }
					
                    /*if ($_SESSION['userdata'][9] == 2) {
                        ?>
                        <li> <a href="<?php echo SITE_URL; ?>plmis_admin/hf_type_data_entry.php"> <i class="fa-size fa-icon-dataentry"></i> <span class="title">District Data Entry</span> </a> </li>
                        <?php
                    }*/
                    ?>
                    <li <?php echo $MR_li_open; ?>> <a href="javascript:;"> <i class="fa-size fa-icon-monthlyreport"></i> <span class="title">Monthly Reports</span> <span class="arrow <?php echo $MR_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $MR_sub_display; ?>>
                            <li> <a href="<?php echo SITE_URL; ?>plmis_admin/view_admin_whreport.php"> My Reports</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>operations/view_admin_whreport.php"> LMIS Explorer</a> </li>
                        </ul>
                    </li>
                    <?php
                }
                if ($flagGuest == TRUE) {
                    ?>
                    <li <?php echo $EX_li_open; ?>> <a href="<?php echo PLMIS_SRC; ?>operations/view_admin_whreport.php"> <i class="fa-size fa-icon-explorer"></i> <span class="title">LMIS Explorer</span> </a>
                        <?php /*?><ul class="sub-menu" <?php echo $EX_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>operations/view_admin_whreport1.php"> Monthly Store/Facility Report</a> </li>
                        </ul><?php */?>
                    </li>
                    <?php
                }
                ?>
                <!--    Reports  -->
                <li <?php echo $RPT_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-reports"></i> <span class="title"> Reports</span> <span class="arrow <?php echo $RPT_arrow_open; ?>"></span> </a>
                    <ul class="sub-menu" <?php echo $RPT_sub_display; ?>>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/nationalreport.php"> National Summary Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/nationalreportSTK.php"> Stakeholder Summary Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/provincialreport.php"> Provincial Summary Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/diststkreport.php"> District Summary Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/stock.php"> District Stock Yearly Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/itemsreport.php"> Stock Availability Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/non_report.php"> Non&#47;Reported Districts</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/quarterly_rate.php"> Quarterly Reporting Rate</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/province_rate.php"> Provincial Reporting Rate</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/projected_contraceptive.php"> Projected Contraceptive Requirements</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/central_warehouse_report.php"> Central/Provincial Warehouse</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/provincial_warehouse_report.php"> Provincial Yearly Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/private_sector_report.php"> Private Sector Yearly Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/pp_sector_report.php"> Public-Private Sector Report</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/stock_sufficiency_report.php"> Stock Sufficiency Report</a> </li>
                        <?php if ($flagGuest == FALSE && $_SESSION['wh_id'] == 123){?>
                        <li> <a href="<?php echo PLMIS_SRC; ?>reports/stock_status.php"> Stock Issuance Report</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/shipment.php"> Shipment Report</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/countrywise_distribution.php"> Country-wise Distribution</a> </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php
                if ($stkid == 1 && $flagGuest == FALSE && $_SESSION['wh_id'] != 123) {
                    ?>

                
                    <li <?php echo $pbs_RPT_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-reports"></i> <span class="title"> PBS Reports</span> <span class="arrow <?php echo $pbs_RPT_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $pbs_RPT_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/form14.php"> Form-14</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/clr11.php"> CLR-11</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr1.php"> SPR-1</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr2.php"> SPR-2</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/pwd3.php"> PWD-3</a> </li> 
                        </ul>
                    </li>                    
                    <li <?php echo $prov_RPT_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-reports"></i> <span class="title">Provincial Reports</span> <span class="arrow <?php echo $prov_RPT_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $prov_RPT_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr3.php"> SPR-3</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/outlet_cyp_comparison.php"> Outlet-wise CYP Performance</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/district_cyp_comparison.php"> District-wise CYP Performance</a> </li>
                        </ul>
                    </li>
                    <li <?php echo $dist_RPT_li_open; ?>> <a href="javascript:;"> <i class="fa-size  fa-icon-reports"></i> <span class="title">District Reports</span> <span class="arrow <?php echo $dist_RPT_arrow_open; ?>"></span> </a>
                        <ul class="sub-menu" <?php echo $dist_RPT_sub_display; ?>>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr8.php"> SPR-8</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr9.php"> SPR-9</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr10.php"> SPR-10</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/spr11.php"> SPR-11</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/clr13.php"> CLR-13</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/clr15.php"> CLR-15</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/dpw_f1.php"> DPW-F1</a> </li>
                            <li> <a href="<?php echo PLMIS_SRC; ?>reports/sale_proceeds.php"> Sale Proceeds</a> </li>
                        <?php
						if ($province == 3 && $_SESSION['userdata'][9] == 3) {
							?>
							<li> <a href="<?php echo PLMIS_SRC; ?>reports/satellite.php"> Satellite Camps</a> </li>
							<?php
						}
						?>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li <?php echo $GRP_li_open; ?>> <a href="javascript:;"> <i class="fa-size fa-icon-graphs"></i> <span class="title"> Graphs</span> <span class="arrow <?php echo $GRP_arrow_open; ?>"></span> </a>
                    <ul class="sub-menu" <?php echo $GRP_sub_display; ?>>
                        <li> <a href="<?php echo PLMIS_SRC; ?>graph/templategraphreport.php"> Comparison Graphs</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>graph/templategraphreport2.php"> Simple Graphs</a> </li>
                    </ul>
                </li>
                <li <?php echo $MAP_li_open; ?>> <a href="javascript:;"> <i class="fa-size fa-icon-maps"></i> <span class="title"> Maps</span> <span class="arrow <?php echo $MAP_arrow_open; ?>"></span> </a>
                    <ul class="sub-menu" <?php echo $MAP_sub_display; ?>>
                        <li> <a href="<?php echo PLMIS_SRC; ?>maps/mos.php"> Month of Stock</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>maps/consumption.php"> Consumption</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>maps/cyp.php"> Couple Year Protection (CYP)</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>maps/cyp_pop.php"> CYP By Population</a> </li>
                        <li> <a href="<?php echo PLMIS_SRC; ?>maps/reporting_rate.php"> Reporting rate</a> </li>
						<?php /*?><li> <a href="<?php echo PLMIS_SRC; ?>maps/stock_frequency.php"> Stock Out Frequency</a> </li><?php */?>
                    </ul>
                </li>
                <!-- END FRONTEND THEME LINKS -->

            </ul>
            <!-- END SIDEBAR MENU --> 
        </div>
    </div>
    <!-- END SIDEBAR --> 
