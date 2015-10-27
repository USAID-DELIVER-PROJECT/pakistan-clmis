<!-- BEGIN FOOTER -->
<div class="clearfix"></div>
<?php 
$pageArr = array('contactus.php', 'faqs.php');
$currPage = basename($_SERVER['PHP_SELF']);
if(!in_array($currPage, $pageArr))
{
?>
<div class="alert alert-info" style="color:#000">
    <div class="footer-inner">
        <ul>
            <li>
            	<b> Average Monthly Consumption(AMC):</b> Average Monthly Consumption is calculated as average of the aggregated consumption for last three non-zero consumption months of contraceptive and related products.
            </li>
            <li>
            	<b> Month of Stock(MOS):</b> Month of Stock is the estimate for number of months the stock will last. This is obtained by dividing Stock on Hand by average monthly consumption.
            </li>
            <li>
            	<b>Stock on Hand(SOH):</b> Stock on Hand is the amount of product on hand, in order to monitor stock positions and anticipate stock-out in advance.
            </li>
            <li>
            	<b>Couple Year Protection(CYP):</b> CYP is the estimated protection provided by contraceptive methods during a one-year period, based upon the volume of all contraceptives consumed during that period.
            </li>
        </ul>
         <p>
         	Analysis and reports are based on the online reported storage and consumption data entered by > 450 trained cLMIS operators (demographers, store keepers and account supervisors) from Health and Population Departments (DOH/PWD), People's Primary Healthcare Initiative, GSM, MSS and FPAP at the district, provincial and Central Warehouse & Supplies levels.
         </p>
    </div>
</div>
<?php }?>
<div class="footer">
    <div class="footer-inner" style="width:95%;">
       <p style="float:left;">For any comments and suggestions please write to <a href="mailto:support@lmis.gov.pk" style="color:#FFF;">support@lmis.gov.pk</a></p>
       <p style="float:right;"><a style="color:white;" href="http://lmis.gov.pk">http://lmis.gov.pk</a></p>
    </div>
    <div class="footer-tools">
		<span class="go-top">
			<a href="#"><i class="fa fa-angle-up"></i></a>
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
    <script src="<?php echo ASSETS;?>global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    <script src="<?php echo ASSETS;?>global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
            type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
    <?php /*?><script src="<?php echo ASSETS;?>global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script><?php */?>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    <script src="<?php echo ASSETS;?>global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
    <script src="<?php echo ASSETS;?>global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/jquery-easypiechart/jquery.easypiechart.min.js"
            type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="<?php echo PLMIS_JS; ?>jquery.notyfy.js"></script>
    <script src="<?php echo ASSETS;?>global/scripts/metronic.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/layout/scripts/layout.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/pages/scripts/index.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS;?>admin/pages/scripts/tasks.js" type="text/javascript"></script>
    
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function () {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            QuickSidebar.init() // init quick sidebar
           // Index.init();
            //Index.initDashboardDaterange();
            //Index.initJQVMAP(); // init index page's custom scripts
            //Index.initCalendar(); // init index page's custom scripts
            //Index.initCharts(); // init index page's custom scripts
            //Index.initChat();
            //Index.initMiniCharts();
            //Index.initIntro();
            Tasks.initDashboardWidget();
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
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo SITE_URL;?>common/theme/scripts/demo/tables.js" type="text/javascript"></script>


<script src="<?php echo SITE_URL; ?>plmis_js/jquery.price_format.js"></script>
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.js"></script>
<!-- Column Table Tools min -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/TableTools.min.js"></script>
<!-- Column Table Tools zero Clipboard -->
<script src="<?php echo SITE_URL; ?>common/theme/scripts/plugins/tables/DataTables/extras/TableTools/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL;?>plmis_admin/Scripts/custom.js"></script>

<script type="text/javascript" src="<?php echo PLMIS_JS?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo PLMIS_JS; ?>jquery.notyfy.js"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-55062070-1', 'auto');
ga('send', 'pageview');
</script>