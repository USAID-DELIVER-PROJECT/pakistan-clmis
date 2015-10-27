<div class="clearfix"></div>
<div class="footer">
    <div class="footer-inner">
        © Pak LMIS.com, 2013. All Rights Reserved.
    </div>
    <div class="footer-tools">
		<span class="go-top">
			<i class="fa fa-angle-up"></i>
		</span>
    </div>
</div>
<!-- END FOOTER -->
<script type="text/javascript">
var basePath = "<?php echo SITE_URL; ?>";
</script>
<?php /*
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/jquery.min.js"></script>
<!-- JQueryUI -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<!-- Modernizr -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/modernizr.js"></script>
<!-- Bootstrap
<script src="<?php echo SITE_URL; ?>common/bootstrap/js/bootstrap.min.js"></script>-->
<!-- SlimScroll Plugin -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- Tables Demo Script -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/demo/tables.js"></script>
<!-- Common Demo Script -->
<!--<script src="--><?php //echo SITE_URL; ?><!--common/theme/scripts/demo/common.js?1369414385"></script>-->
<!-- Uniform Forms Plugin -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>
<!-- PrettyPhoto -->
<!--<script src="--><?php //echo SITE_URL; ?><!--common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>-->
<!-- DataTables Tables Plugin -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo SITE_URL; ?>plmis_js/jquery.price_format.1.8.min.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js"></script>
<!-- Column Table Tools min -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<!-- Column Table Tools zero Clipboard -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/jquery.validate.js"></script>
<!--<script type="text/javascript" src="--><?php //echo SITE_URL;?><!--plmis_admin/Scripts/custom.js"></script>-->

<script type="text/javascript" src="<?php echo PLMIS_JS?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo PLMIS_JS; ?>jquery.notyfy.js"></script>

    <div class="footer">Copyright &copy; <a href="#">Pakistan LMIS</a>, <?php echo date('Y')?>. All Rights Reserved.

</div>
 * 
 */
?>

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    <script src="<?php echo ASSETS;?>global/plugins/respond.min.js"></script>
    <script src="<?php echo ASSETS;?>global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo ASSETS;?>global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?php echo ASSETS;?>global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="<?php echo ASSETS;?>global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>

    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function () {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            QuickSidebar.init() // init quick sidebar

        });
    </script>
    
    <!-- END JAVASCRIPTS -->
    
    <!-- JAVA Script files that were  in old code but are not present in current code -->
    <!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<!-- Modernizr -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/system/modernizr.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/demo/common.js?1369414385"></script>
<!-- PrettyPhoto -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/gallery/prettyphoto/js/jquery.prettyPhoto.js"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo SITE_URL; ?>plmis_js/jquery.price_format.1.8.min.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js"></script>
<!-- Column Table Tools min -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<!-- Column Table Tools zero Clipboard -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/custom.js"></script>

<script type="text/javascript" src="<?php echo PLMIS_JS?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo PLMIS_JS; ?>jquery.notyfy.js"></script>
