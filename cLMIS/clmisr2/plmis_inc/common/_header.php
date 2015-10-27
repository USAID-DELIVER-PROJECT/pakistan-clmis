<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.1.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title>Pakistan Logistics Management Information System - Contraceptive</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo ASSETS;?>global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
    <link href="<?php echo ASSETS;?>global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGIN STYLES -->
    <!-- BEGIN PAGE STYLES -->
    <link href="<?php echo ASSETS;?>admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="<?php echo ASSETS;?>global/css/components.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" type="text/css"/>
    <?php /*?><link href="<?php echo ASSETS;?>global/css/plugins.css" rel="stylesheet" type="text/css"/><?php */?>
    <link href="<?php echo ASSETS;?>admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo ASSETS;?>admin/layout/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<!--<link href="--><?php //echo ASSETS;?><!--css/styles.css" rel="stylesheet" type="text/css"/>-->
    <link href="<?php echo ASSETS;?>css/style.css" rel="stylesheet" type="text/css"/>


    <link href="<?php echo ASSETS;?>admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <link href="<?php echo PLMIS_CSS; ?>jquery.notyfy.css" rel="stylesheet" />
    <link href="<?php echo SITE_URL;?>common/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />
    
    <style>
    	.pagination a{padding:0 5px !important; line-height:25px !important;}
		.dataTables_info{padding:8px 0 !important;}
		button.DTTT_button, div.DTTT_button, a.DTTT_button{padding:5px !important;}
		.pagination ul{padding-left:0px !important;}
		.dataTables_length label select{padding:0px; height: 24px; width:60px;}
    </style>
    <!-- Legacy JS Files -->
    <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
    <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
    <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
    <script src="<?php echo PLMIS_JS;?>jquery-1.4.4.js" type="text/javascript"></script>
    <script src="<?php echo PLMIS_JS;?>jquery.autoheight.js" type="text/javascript"></script>
    <link href="<?php echo PLMIS_JS;?>facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
    <script src="<?php echo PLMIS_JS;?>facebox/facebox.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox({
                loading_image : '<?php echo PLMIS_IMG;?>loading.gif',
                close_image   : '<?php echo PLMIS_IMG;?>closelabel.gif'
            })
			$('#mygrid_container').parent('td').addClass('hdrTable');
        })
    </script>
    <script src="http://maps.google.com/maps/api/js?v=3.5&amp;sensor=false"></script>
    <!-- Openlayer -->
    <link href="<?php echo PLMIS_JS;?>OpenLayers-2.13/theme/default/style.css" rel="stylesheet" />
    <script src="<?php echo PLMIS_JS;?>OpenLayers-2.13/OpenLayers.js"></script>
    <script src="<?php echo PLMIS_JS;?>OpenLayers-2.13/lib/OpenLayers/Control/DynamicMeasure.js"></script>

    <!-- END of Legacy JS Files -->