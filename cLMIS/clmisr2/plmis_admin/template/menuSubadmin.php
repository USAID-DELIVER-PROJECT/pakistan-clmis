<div class="header navbar"> 
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner"> 
        <!-- BEGIN LOGO --> 
        <a class="navbar-brand" href="<?php echo SITE_URL;?>" style="margin-top: -10px;"> <img src="<?php echo ASSETS;?>admin/layout/img/landing-images/contraceptive-logo.png" height="55" width="323" alt="vaccine LMIS" /> </a> 
        <!-- END LOGO --> 
        <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="<?php echo ASSETS;?>img/menu-toggler.png" alt=""/> </a>

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
                    <button type="button" class="btn lightgrey dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> <span>Admin</span><i class="fa fa-angle-down"></i> </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li> <a href="<?php echo PLMIS_ADMIN;?>changePass.php">Change Password</a> </li>
                        <li> <a href="<?php echo SITE_URL;?>Logout.php"> Sign Out </a> </li>
                    </ul>
                </li>
                <li class="white breadcrumb-border-left"><img src="<?php echo ASSETS;?>admin/layout/img/landing-images/paki-fla.png" alt=""/></li>
                <li>
                    <?php  //showBreadCrumb();?>
                    <div style="float:right; padding-right:3px">
                        <?php //echo readMeLinks($readMeTitle);?>
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
                    <span class="title">Admin</span> </span> </div>
            </li>
			<li> <a href="<?php echo PLMIS_ADMIN;?>AdminHome.php"> <i class="icon-home"></i> <span class="title">Home</span> </a> </li>
			<?php /*?><li> <a href="<?php echo PLMIS_ADMIN;?>ManageUserSub.php"> <i class="icon-home"></i> <span class="title">Users</span> </a> </li><?php */?>
			<li> <a href="<?php echo PLMIS_ADMIN;?>data_entry_admin.php"> <i class="icon-home"></i> <span class="title">Data Entry</span> </a> </li>
            <!-- END FRONTEND THEME LINKS -->
            
        </ul>
        <!-- END SIDEBAR MENU --> 
    </div>
</div>
<!-- END SIDEBAR --> 
