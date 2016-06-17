<?php
// Get last Update data
$lastUpdateQry = "SELECT
					DATE_FORMAT(cron_log_time.last_run, '%d/%m/%Y %h:%i %p') AS last_run
				FROM
					cron_log_time
				ORDER BY
					cron_log_time.pk_id DESC
				LIMIT 1";
$lastUpdateQryRes = mysql_fetch_array(mysql_query($lastUpdateQry));
$lastUpdateDate = $lastUpdateQryRes['last_run'];
$lastUpdateText = "Note: This report is based on data as on " . $lastUpdateDate;

$welcome_msg = '<h2 id="textBold">Welcome, ' . $_SESSION['user_id'] . '&nbsp;&nbsp;';
$welcome_msg .= '<img style="vertical-align:middle; height:14px; width:14px; margin-right:2px; margin-left:10px;" src="' . PUBLIC_URL . 'images/logout.jpg" /><a href="JavaScript:Logout()">Logout</a></h2>';

$sql = "SELECT
			sysuser_tab.sysusr_type,
			sysuser_tab.sysusr_name,
			sysuser_tab.stkid,
			sysuser_tab.province,
			tbl_warehouse.is_allowed_im
		FROM
			sysuser_tab
		LEFT JOIN wh_user ON sysuser_tab.UserID = wh_user.sysusrrec_id
		LEFT JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
		WHERE
			UserID = " . $_SESSION['user_id'] . "
		GROUP BY
			sysuser_tab.UserID";
$sql2 = mysql_query($sql);
$row_logo = mysql_fetch_array($sql2);
$IM_allowed = $row_logo['is_allowed_im'];
$_SESSION['im_open'] = $IM_allowed;

/*$query = mysql_query("select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='" . $stkid . "' AND province_id='" . $province . "'");
//print "select Stkid,province_id,logo from tbl_cms where homepage_chk=1 and Stkid='".$stkid."' AND province_id='".$province."'";
//exit;
$row_image = mysql_fetch_array($query);
$logo = $row_image['logo'];
if ($logo == '') {
    $logo = "pak_federal.jpg";
}*/
//var_dump($_SESSION);
$assignedArr = array();
$class = '';
function sub($reourceItems, $id){
	echo "<ul class=\"sub-menu\">";
	foreach($reourceItems as $item){
		if($item['parent_id'] == $id){
			$class = (basename($item['resource_name']) == basename($_SERVER['PHP_SELF'])) ? 'class="active"' : '';
			if(strpos($item['resource_name'], '.php') == true){
				
				if(basename($item['resource_name']) == 'satellite_wh.php'){
					if($_SESSION['user_province'] == 3){
					?>
	                    <li <?php echo $class;?>> <a href="<?php echo SITE_URL.$item['resource_name'];?>"> <i class="<?php echo $item['icon_class'];?>"></i> <span class="title"><?php echo $item['page_title'];?></span> </a> </li>
                    <?php
					}
				}else{
			?>
				<li <?php echo $class;?>> <a href="<?php echo SITE_URL.$item['resource_name'];?>"> <i class="<?php echo $item['icon_class'];?>"></i> <span class="title"><?php echo $item['page_title'];?></span> </a> </li>
			<?php
				}
			}else{
			?>
				<li <?php echo $class;?>> <a href="javascript:;"> <i class="<?php echo $item['icon_class'];?>"></i> <span class="title"><?php echo $item['page_title'];?></span> </a>
			<?php
			}
			sub($reourceItems, $item['pk_id']);
			echo "</li>";
		}
	}
	echo "</ul>";
}
?>

<!--Wiki-->

<div class="header navbar"> 
    <!-- BEGIN TOP NAVIGATION BAR -->
    <div class="header-inner"> 
        <!-- BEGIN LOGO --> 
        <a class="navbar-brand" href="<?php echo SITE_URL;?>" style="margin-top: -10px;"> <img src="<?php echo PUBLIC_URL;?>assets/admin/layout/img/landing-images/contraceptive-logo.png" height="55" width="323" alt="vaccine LMIS" /> </a> 
        <!-- END LOGO --> 
        <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <img src="<?php echo PUBLIC_URL;?>assets/img/menu-toggler.png" alt=""/> </a>
        <?php
        if (($_SESSION['user_role'] != 16)) {
            ?>
            <ul class="nav navbar-nav pull-right">
                <li> <a href="<?php echo SITE_URL;?>manuals.php" class="ul-header"> <img src="<?php echo PUBLIC_URL;?>assets/img/header-icon/training_manuals.png" alt=""/> </a> </li>
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
                    <button type="button" class="btn lightgrey dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> <span><?php echo $_SESSION['user_name'];?></span><i class="fa fa-angle-down"></i> </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <?php
                        if (isset($_SESSION['user_id']) && (!in_array($_SESSION['user_role'], array(16, 17)))) {
                            ?>
                            <li> <a href="<?php echo APP_URL;?>default/changePassUser.php"> 
                                <!--                    <i class="icon-home"></i>--> 
                                    Change Password</a> </li>
                            <?php
                        }
                        ?>

                        <!--<li class="divider"> </li>-->
                        <li> <a href="<?php echo SITE_URL;?>Logout.php"> Sign Out </a> </li>
                    </ul>
                </li>
                <li class="white breadcrumb-border-left"><img src="<?php echo PUBLIC_URL;?>assets/admin/layout/img/landing-images/paki-fla.png" alt=""/></li>
                <li>
                    <?php //showBreadCrumb();?>
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
            <ul class="page-sidebar-menu accordion" id="accordion">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper charcol-clr">
                    <div class="sidebar-toggler hidden-phone"></div>
                    <div class="dashboard-header"> <span class=" welcm"> Welcome<br>
                            <span class="title"><?php echo $_SESSION['user_name'];?></span> </span> </div>
                </li>
                <?php /*?><li> <a href="<?php echo APP_URL;?>dashboard/dashboard.php"> <i class="icon-home"></i> <span class="title">Home</span> </a> </li><?php */?>
			<?php
           	$resourcesQry = "SELECT
								resources.pk_id,
								IF(ISNULL(resources.page_title), resources.resource_name, resources.page_title) AS page_title,
								resources.resource_name,
								resources.icon_class,
								resources.parent_id
							FROM
								resources
							INNER JOIN role_resources ON resources.pk_id = role_resources.resource_id
							WHERE
								role_resources.role_id = '".$_SESSION['user_role']."'
							AND resources.resource_type_id = 2
							ORDER BY
								role_resources.rank ASC";
			$resourcesQryRes = mysql_query($resourcesQry);
			
			while ( $row = mysql_fetch_assoc($resourcesQryRes) )
			{
				$reourceItems[] = $row;
			}
			
			foreach($reourceItems as $item){
				if(is_null($item['parent_id'])){
					$class = (basename($item['resource_name']) == basename($_SERVER['PHP_SELF'])) ? 'class="active"' : '';					
					if(strpos($item['resource_name'], '.php') == true){
					?>
                    	<li <?php echo $class;?>> <a href="<?php echo SITE_URL.$item['resource_name'];?>"> <i class="<?php echo $item['icon_class'];?>"></i> <span class="title"><?php echo $item['page_title'];?></span> </a> </li>
                    <?php
					}else{
					?>
                    	<li <?php echo $class;?>> <a href="javascript:;"> <i class="<?php echo $item['icon_class'];?>"></i> <span class="title"><?php echo $item['page_title'];?></span> </a>
					<?php
					}

					$id = $item['pk_id'];
					sub($reourceItems, $id);
					echo "</li>";
				}
			}
            ?>
            </ul>
            <!-- END SIDEBAR MENU --> 
        </div>
    </div>
    <!-- END SIDEBAR -->