<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();

startHtml($system_title." - Completeness Of Reporting");?>

 <style>
.input_select{
	border:#D1D1D1 1px solid;
	color:#474747;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	height:24px;
	max-width:150px;
}

.input_button{
	border:#D1D1D1 1px solid;
	background-color:#006700;
	color:#FFFFFF;
	height:25px;	
	font-family:Arial, Helvetica, sans-serif;
	vertical-align:bottom;
	width:60px;
/*	font-size:11px;*/

}
</style>

<link href="../../plmis_css/map.css" rel="stylesheet" />
<script src="../../plmis_js/maps/html2canvas.js"></script>
<script src="../../plmis_js/maps/download.js"></script>
<script src="../../plmis_js/maps/symbology.js"></script>
<script src="../../plmis_js/maps/completeness_of_reporting.js"></script>
<script  src="../../plmis_js/maps/refineLegend.js"></script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;">
	<?php include "../../plmis_inc/common/top.php";?>	
    <div class="body_sec">
	<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content"><br/>
<!--		  --><?php // showBreadCrumb();?><!-- <div style="float:right; padding-right:3px">--><?php ////echo readMeLinks($readMeTitle);?><!--</div> <br><br>-->
		  <?php include(PLMIS_INC."maps/compeltenessForm.php"); ?>	
                    <br/>
                    <br/>
		    <table width="100%">
			<tr style="background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x;">
                            <td style="width:30%" align="right">
                                <div align="left" id="controls" class="olControlEditingToolbar"></div>
                            </td>
			    <td style="width:70%" align="right">
				<img id="image" src="../../plmis_img/image.png"/>
                                <img id="print"  style="cursor:pointer; margin-left:-5px;width:30px;height:30px" src="../../plmis_img/print.png"/>
			    </td>
			</tr>
		    </table>
                    <div style="width:auto;height:auto">
		    <table width="100%" cellpadding="0" cellspacing="0">
			<tr>
			    <td>
                                <div id="map">
                                    <div id="infoDiv"> 
                                        <h6 align="center"><b><font color="#008000">Click on the Feature to  View the <br/> information</font></b></h6>
                                        <div id="info"></div>   
                                    </div>
                                    <img id="loader" src="../../plmis_img/ajax-loader.gif"/>
                                </div>
			    </td>
			</tr>
		    </table>
                    </div>
		</div>
	</div> 
    </div>
  <?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>