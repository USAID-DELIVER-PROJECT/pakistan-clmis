<?php ob_start(); include( "../../html/adminhtml.inc.php"); include( "../../plmis_inc/common/plmis_common_constants.php"); Login(); ?>

<?php include "../../plmis_inc/common/_header.php";?>

<link href="../../plmis_css/map.css" rel="stylesheet" />
<SCRIPT LANGUAGE="Javascript" SRC="../../FusionCharts/Charts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../FusionCharts/themes/fusioncharts.theme.fint.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/html2canvas.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/download.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/symbology.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/Legend.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/reporting_rate.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/Filter.js"></SCRIPT>
<SCRIPT LANGUAGE="Javascript" SRC="../../plmis_js/maps/refineLegend.js"></SCRIPT>

</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN HEADER -->
    <div class="page-container">
        <?php include "../../plmis_inc/common/_top.php"; include "../../plmis_inc/common/top_im.php"; ?>

        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-title row-br-b-wp">Reporting Rate Map</h3>
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Filter by</h3>
                            </div>
                            <div class="widget-body">
                                <?php include(PLMIS_INC. "maps/reportingRateForm.php"); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%">
                            <tr>
                                <td style="width:100%" align="right">
                                    <img id='excel' src="../../images/excel-32.png" style="cursor:pointer;width:35px;height:35px" />
                                    <img id="image" src="../../images/map-icon.png" style="cursor:pointer;width:35px;height:35px" />
                                    <img id="print" src="../../images/print.png" style="cursor:pointer; margin-left:-5px;width:35px;height:35px" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div style="width:auto;height:auto">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="100%">
                                            <div id="map">
                                                <div id="customZoom">
                                                    <a href="#customZoomIn" id="customZoomIn">in</a>
                                                    <a href="#customZoomOut" id="customZoomOut">out</a>
                                                </div>
                                                <div id="legendDiv">
                                                    <div>
                                                        <table id='legend'></table>
                                                    </div>
                                                </div>
                                                <img id="loader" src="../../plmis_img/ajax-loader.gif" />
                                                <div id="mapTitle"></div>
                                                <div id="printedDate"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1">District Info</a>
                                </li>

                            </ul>

                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane fade active in">
                                    <table border='1' class='infoTable'>
                                        <tr>
                                            <td class='bold'>Province</td>
                                            <td id='prov'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>District</td>
                                            <td id='district'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>Stakeholder</td>
                                            <td id='stakeholder'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>Product</td>
                                            <td id='product'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>Total Warehouses</td>
                                            <td id='total_warehouses'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>Reported</td>
                                            <td id='reported'></td>
                                        </tr>
                                        <tr>
                                            <td class='bold'>Reporting rate ( % )</td>
                                            <td id='reporting_rate'></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                            <hr/>

                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">Reporting Rate Status</h3>
                                </div>
                                <div class="widget-body">
                                    <div id="pie"></div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <hr/>

                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">District Wise Reporting Rate Ranking</h3>
                                </div>
                                <div class="widget-body">
                                    <div id='districtRanking'></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="widget" data-toggle="collapse-widget">
                                <div class="widget-head">
                                    <h3 class="heading">District Wise Reporting Rate Status</h3>
                                </div>
                                <div class="widget-body">
                                    <div id='attributeGrid'></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <?php include "../../plmis_inc/common/footer.php";?>
</body>
<!-- END BODY -->

</html>