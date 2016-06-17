<div class="header navbar"> 
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner"> 
        <!-- BEGIN LOGO --> 
        <a class="navbar-brand" href="<?php echo SITE_URL;?>" style="margin-top: -10px;"> <img src="<?php echo PUBLIC_URL;?>assets/admin/layout/img/landing-images/contraceptive-logo.png" height="55" width="323" alt="vaccine LMIS" /> </a> 
        <!-- END LOGO --> 
        <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="<?php echo PUBLIC_URL;?>assets/img/menu-toggler.png" alt=""/> </a>

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
                        <li> <a href="<?php echo APP_URL;?>default/changePassUser.php">Change Password</a> </li>
                        <li> <a href="<?php echo SITE_URL;?>Logout.php"> Sign Out </a> </li>
                    </ul>
                </li>
                <li class="white breadcrumb-border-left"><img src="<?php echo PUBLIC_URL;?>assets/admin/layout/img/landing-images/paki-fla.png" alt=""/></li>
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
        <ul class="page-sidebar-menu accordion" id="accordion">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper charcol-clr">
                <div class="sidebar-toggler hidden-phone"></div>
                <div class="dashboard-header"> <span class=" welcm"> Welcome<br>
                    <span class="title">Admin</span> </span> </div>
            </li>
			<li> <a href="<?php echo APP_URL;?>admin/AdminHome.php"> <i class="fa fa-home"></i> <span class="title">Home</span> </a> </li>
            <li> <a href="javascript:;"> <i class="fa fa-wrench"></i> <span class="title"> Manage ACL</span> </a>
                <ul class="sub-menu">
                    <li><a href="<?php echo APP_URL;?>admin/role_management.php">Role Management</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/resource_management.php">Resource Management</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/assign_resources.php">Assign Resources</a></li>
                </ul>
            </li>
            <li> <a href="javascript:;"> <i class="fa fa-user"></i> <span class="title"> Users</span> </a>
                <ul class="sub-menu">
                    <li><a href="<?php echo APP_URL;?>admin/ManageUser.php">Manage Users</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageSubAdmin.php">Sub Admins</a></li>
                </ul>
            </li>
			<li> <a href="<?php echo APP_URL;?>admin/ManageWarehouse.php"> <i class="fa fa-home"></i> <span class="title">Warehouses</span> </a> </li>
			<li> <a href="<?php echo APP_URL;?>admin/unlock-data-entry.php"> <i class="fa fa-unlock"></i> <span class="title">Unlock Data Entry</span> </a> </li>
			<li> <a href="<?php echo APP_URL;?>admin/ManageLocations.php"> <i class="fa fa-map-marker"></i> <span class="title">Locations</span> </a> </li>
            <li> <a href="javascript:;"> <i class="fa fa-users"></i> <span class="title"> Stakeholders</span> </a>
                <ul class="sub-menu">
                    <li><a href="<?php echo APP_URL;?>admin/ManageStakeholders.php">Manage Stakeholders</a> 
                    <li><a href="<?php echo APP_URL;?>admin/ManageStakeholdersOfficeTypes.php">Stakeholders Offices</a> </li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageStakeholdersItems.php">Stakeholders Products</a> </li>
                </ul>
            </li>
            <li><a href="javascript:;"> <i class="fa fa-cubes"></i> <span class="title"> Products</span> </a>
                <ul class="sub-menu">
                    <li><a href="<?php echo APP_URL;?>admin/ManageItemsGroups.php">Manage Product Group</a> </li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageProductType.php">Manage Product Unit</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageItems.php">Manage Product</a> </li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageItemsofGroups.php">Manage Group Product</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/MoSManage.php">Manage MoS Scale</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageProductCategory.php">Manage Category</a></li>
                    <li><a href="<?php echo APP_URL;?>admin/ManageProductStatus.php">Manage Status</a></li>
                </ul>
            </li>
            <!-- END FRONTEND THEME LINKS -->
            
        </ul>
        <!-- END SIDEBAR MENU --> 
    </div>
</div>
<!-- END SIDEBAR --> 
