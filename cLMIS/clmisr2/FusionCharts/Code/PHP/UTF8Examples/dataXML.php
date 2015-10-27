<?php
//We've included ../Includes/FusionCharts.php, which contains functions
//to help us easily embed the charts.
include("../Includes/FusionCharts.php");
?>
<HTML>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <TITLE>
		FusionCharts - Simple Column 2D Chart - With Multilingual characters
        </TITLE>
        <link href="../assets/ui/css/style.css" rel="stylesheet" type="text/css" />

        <?php
       
        ?>
        <SCRIPT LANGUAGE="Javascript" SRC="../../FusionCharts/FusionCharts.js"></SCRIPT>

        <!--[if IE 6]>
        <script type="text/javascript" src="../assets/ui/js/DD_belatedPNG_0.0.8a-min.js"></script>

<script>
          /* select the element name, css selector, background etc */
          DD_belatedPNG.fix('img');

          /* string argument can be any CSS selector */
        </script>
        <![endif]-->

        <style type="text/css">
            h2.headline {
                font: normal 110%/137.5% "Trebuchet MS", Arial, Helvetica, sans-serif;
                padding: 0;
                margin: 25px 0 25px 0;
                color: #7d7c8b;
                text-align: center;
            }
            p.small {
                font: normal 68.75%/150% Verdana, Geneva, sans-serif;
                color: #919191;
                padding: 0;
                margin: 0 auto;
                width: 664px;
                text-align: center;
            }
        </style>

    </head>
    <BODY>

        <div id="wrapper">

            <div id="header">
                

               <div class="logo"><a class="imagelink"  href="http://www.fusioncharts.com/" target="_blank"><img src="../assets/ui/images/fusionchartsv3.2-logo.png" width="131" height="75" alt="FusionCharts XT logo" /></a></div>
                <h1 class="brand-name">FusionCharts XT</h1>
                <h1 class="logo-text">FusionCharts Examples</h1>
            </div>

            <div class="content-area">
                <div id="content-area-inner-main">
                    <h2 class="headline">Example using XML having multilingual text</h2>

                    <div class="gen-chart-render">

                        <CENTER>
                            <?php

                            //This page demonstrates the ease of generating charts using FusionCharts.
                            //For this chart, we've used a XML
                            //containing UTF-8 encoded multilingual text

                            //Create an XML data document in a string variable
                            $strXML  = "<chart caption='Monthly Sales Summary' subcaption='For the year 2008' ";
                            $strXML .= " xAxisName='Month' yAxisName='Sales' numberPrefix='$' showNames='1'";
                            $strXML .= " showValues='0' showColumnShadow='1' animation='1'";
                            $strXML .= " baseFontColor='666666' lineColor='FF5904' lineAlpha='85'";
                            $strXML .= " valuePadding='10' labelDisplay='rotate' useRoundEdges='1'>";
                            $strXML .= "<set label='januári' value='17400' />";
                            $strXML .= "<set label='Fevruários' value='19800' />";
                            $strXML .= "<set label='مارس' value='21800' />";
                            $strXML .= "<set label='أبريل' value='23800' />";
                            $strXML .= "<set label='五月' value='29600' />";
                            $strXML .= "<set label='六月' value='27600' />";
                            $strXML .= "<set label='תִּשׁרִי' value='31800' />";
                            $strXML .= "<set label='Marešwān' value='39700' />";
                            $strXML .= "<set label='settèmbre' value='37800' />";
                            $strXML .= "<set label='ottàgono' value='21900' />";
                            $strXML .= "<set label='novèmbre' value='32900' />";
                            $strXML .= "<set label='décembre' value='39800' />";
                            $strXML .= "<styles><definition><style name='myCaptionFont' type='font' size='12'/></definition>";
                            $strXML .= "<application><apply toObject='datalabels' styles='myCaptionFont' /></application></styles>";
                            $strXML .= "</chart>";

                            //Create the chart - Column 2D
                            echo renderChart("../../FusionCharts/Column2D.swf", "", $strXML, "myFirst", 500, 400, false, false);
                            ?>
                        </CENTER>
                    </div>
                    <div class="clear"></div>
                    <p>&nbsp;</p>
                    <p class="small"> <!--<p class="small">This dashboard was created using FusionCharts XT, FusionWidgets v3 and FusionMaps v3 You are free to reproduce and distribute this dashboard in its original form, without changing any content, whatsoever. <br />
            &copy; All Rights Reserved</p>
          <p>&nbsp;</p>-->
                    </p>

                    <div class="underline-dull"></div>
                </div>
            </div>

            <div id="footer">
                <ul>
                    <li><a href="../index.html"><span>&laquo; Back to list of examples</span></a></li>
                    <li class="pipe">|</li>
                    <li><a href="../NoChart.html"><span>Unable to see the chart above?</span></a></li>
                </ul>
            </div>
        </div>
    </BODY>
</HTML>
