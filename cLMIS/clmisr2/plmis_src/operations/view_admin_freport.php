<?php 
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
	
	unset($_SESSION['filterParam']['year']);
	unset($_SESSION['filterParam']['month']);
	unset($_SESSION['filterParam']['wh']);
	unset($_SESSION['filterParam']['province']);
	unset($_SESSION['numOfRows']);
	
if(isset($_POST['submit'])){
	include("xml/xml_genaration_freport.php");
}
Login();

    if (!ini_get('register_globals')) {
      $superglobals = array( $_GET,  $_POST, $_COOKIE, $_SERVER );
      if (isset ($_SESSION)) {
        array_unshift($superglobals, $_SESSION);
      }
      foreach ($superglobals as $superglobal) {
        extract($superglobal, EXTR_SKIP);
      }
      ini_set('register_globals', true);
    }
	
	//////////// GET FILE NAME FROM THE URL
	
	$arr = explode("?", basename($_SERVER['REQUEST_URI']));
	$basename = $arr[0];
	$filePath = "plmis_src/operations/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $system_title." - "?>View Monthly Field report</title>
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
     <link rel="stylesheet" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
     
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
     <script src='dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>	
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
     <script src="dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
    
    <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
	 <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
     <script src="<?php echo PLMIS_JS;?>jquery.js" type="text/javascript"></script>
     <script src="<?php echo PLMIS_JS;?>jquery.autoheight.js" type="text/javascript"></script>
     <link href="<?php echo PLMIS_JS;?>facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
     <script src="<?php echo PLMIS_JS;?>facebox/facebox.js" type="text/javascript"></script> 
     <script type="text/javascript">
                jQuery(document).ready(function($) {
                  $('a[rel*=facebox]').facebox({
                    loading_image : '<?php echo PLMIS_IMG;?>loading.gif',
                    close_image   : '<?php echo PLMIS_IMG;?>closelabel.gif'
                  }) 
                })
     </script>
 	 <script>
     	function editFunction(val){
			window.location="AddEditUser.php?ActionType=EditShow&pruser_id="+val;
		}
		function delFunction(val){
			if (confirm("Are you sure you want to delete the record?")){
				window.location="AddEditWaitingData.php?ActionType=DeleteData&id="+val;
			}	
		}
     </script>
     <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       //mygrid.setHeader("Month,Year,Product,Warehouse,Opening Balance,Received,Issued,Closing Balance");
	   mygrid.setHeader("<span title='Month of which data is shown'>Month</span>,<span title='Year of which data is shown'>Year</span>,<span title='Product name'>Product</span>,<span title='Warehouse name'>Warehouse</span>,<span title='Opening Balance Calculated'>Opening Balance</span>,<span title='Balance received'>Received</span>,<span title='Balance issued'>Issued</span>,Adjustments,#cspan,<span title='Closing balance'>Closing Balance</span>");
	   mygrid.attachHeader(",,#select_filter,#select_filter,,,,(+),(-),");
	   mygrid.setInitWidths("100,80,130,*,120,100,100,50,50,100");
       mygrid.setColAlign("left,left,left,left,right,right,right,right,right,right");
       mygrid.setColSorting(",,str,str");
       mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
	  //mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	   mygrid.setSkin("light");
       mygrid.enablePaging(true, 10, 3, "recinfoArea");
       mygrid.setPagingSkin("toolbar", "dhx_skyblue");
	   
	   mygrid.init();
       mygrid.loadXML("xml/freport.xml");
      }
 
     </script>
	<script type="text/javascript">
		function fetchDistricts(){
			var val;
			var temp = "<select title=Select Province style=width:200px;><option value=>--- Select Province First---</option></select>";
			val = $('#provinces').val();
			if (val == 0){
				$('#divdists').html(temp);	
			}else if(val != 0){		
				var html = $.ajax({
				beforeSend: function(){
				// Handle the beforeSend event
				//alert('before Send!');
			},
			url: "fetchWh.php?pid="+val,
			data: "pid="+val,			 
			async: false,
			complete: function(){
			//alert(val);
			//window.open("fetchDistricts.php?pid="+val);
			}
			}).responseText;
			$('#divdists').html(html);
			}
		}
	</script>
</head>
<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onload="doInitGrid();fetchDistricts()">

	<?php include "../../plmis_inc/common/top.php";?>
    <?php include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
        </div>
    	<div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" align="" style="min-height:679px;"><br />
            
            <?php showBreadCrumb();?><div style="float:right; padding-right:35px"><?php //echo readMeLinks($readMeTitle);?></div><br /><br />
            
                <p><span><b style="color: #000000; font-size: 22px;font-weight: bold;">View Monthly Field Report</b></span><br />
           
            
                <!--<div id="mygrid_container" style="width:820px;height:300px;"></div>
                 <div id="recinfoArea"></div>-->
      
               <table width="99%">
               <tr>
                    <td>
                    <form action="" method="post">
                    <table>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td title="Month of which you wanted to see data" class="sb1NormalFont">Month</td>
                        <td title="Year of which you wanted to see data" class="sb1NormalFont">Year</td>
                        <td title="Province/Region Name" class="sb1NormalFont">Province/Region</td>
                        <td title="Wharehouse Name" class="sb1NormalFont">Warehouse</td>
                    </tr>
                    <tr>
                    <td>  
                                <?php
                                    $q = "SELECT report_month,report_year FROM tbl_wh_data ORDER BY w_id DESC";
                                    $r = mysql_query($q) or die(mysql_error());
                                    $rs = mysql_fetch_array($r);
                                    
                                    if($rs['report_month']=='12') {
                                        $mymonth = '01';	
                                        $myyear = $rs['report_year'];
                                    }else{
                                        $mymonth = $rs['report_month'];	
                                        $myyear = $rs['report_year'];											
                                    }
                                    ?>
            
                                <?php if ($_POST['submit']){?>
                                         <SELECT NAME="report_month" id="report_month" CLASS="sb1GeenGradientBoxMiddle" TABINDEX="3">
                                    <OPTION VALUE="1" <?php 
                                    if($_SESSION['filterParam']['month']=='1') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?> >JANUARY</OPTION>	
                                    <OPTION VALUE="2" <?php 
                                    if($_SESSION['filterParam']['month']=='2'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>FEBRUARY</OPTION>
                                    <OPTION VALUE="3" <?php 
                                    if($_SESSION['filterParam']['month']=='3'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>MARCH</OPTION>
                                    <OPTION VALUE="4" <?php 
                                    if($_SESSION['filterParam']['month']=='4'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>APRIL</OPTION>	
                                    <OPTION VALUE="5" <?php 
                                    if($_SESSION['filterParam']['month']=='5'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>MAY</OPTION>
                                    <OPTION VALUE="6" <?php 
                                    if($_SESSION['filterParam']['month']=='6'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>JUN</OPTION>
                                    <OPTION VALUE="7" <?php 
                                    if($_SESSION['filterParam']['month']=='7') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>JULY</OPTION>
                                    <OPTION VALUE="8" <?php 
                                    if($_SESSION['filterParam']['month']=='8'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>AUGUST</OPTION>
                                    <OPTION VALUE="9" <?php 
                                    if($_SESSION['filterParam']['month']=='9') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>SEPTEMBER</OPTION>
                                    <OPTION VALUE="10" <?php 
                                    if($_SESSION['filterParam']['month']=='10') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>OCTOBER</OPTION>	
                                    <OPTION VALUE="11" <?php 
                                    if($_SESSION['filterParam']['month']=='11'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>NOVEMBER</OPTION>	
                                    <OPTION VALUE="12" <?php 
                                    if($_SESSION['filterParam']['month']=='12'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>DECEMBER</OPTION>
                                </SELECT>							
                                <?php }else{?>
                                
                                <SELECT NAME="report_month" id="report_month" CLASS="sb1GeenGradientBoxMiddle" TABINDEX="3">
                                    <OPTION VALUE="1" <?php 
                                    if($mymonth=='01') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?> >JANUARY</OPTION>	
                                    <OPTION VALUE="2" <?php 
                                    if($mymonth=='02'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>FEBRUARY</OPTION>
                                    <OPTION VALUE="3" <?php 
                                    if($mymonth=='03'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>MARCH</OPTION>
                                    <OPTION VALUE="4" <?php 
                                    if($mymonth=='04'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>APRIL</OPTION>	
                                    <OPTION VALUE="5" <?php 
                                    if($mymonth=='05'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>MAY</OPTION>
                                    <OPTION VALUE="6" <?php 
                                    if($mymonth=='06'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                    ?>>JUN</OPTION>
                                    <OPTION VALUE="7" <?php 
                                    if($mymonth=='07') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>JULY</OPTION>
                                    <OPTION VALUE="8" <?php 
                                    if($mymonth=='08'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>AUGUST</OPTION>
                                    <OPTION VALUE="9" <?php 
                                    if($mymonth=='09') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>SEPTEMBER</OPTION>
                                    <OPTION VALUE="10" <?php 
                                    if($mymonth=='10') {
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>OCTOBER</OPTION>	
                                    <OPTION VALUE="11" <?php 
                                    if($mymonth=='11'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>NOVEMBER</OPTION>	
                                    <OPTION VALUE="12" <?php 
                                    if($mymonth=='12'){
                                        echo $chk2 = "Selected = 'Selected'";	
                                    }
                                     ?>>DECEMBER</OPTION>
                                </SELECT>
                                <?php }?>
                        </td>
                        <td>  
            
                        <?php if ($_POST['submit']){?>
                                <select name="report_year" id="report_year" class="sb1GeenGradientBoxMiddle" tabindex="2">
                              <?php						
                                    $EndYear=2008;
                                    $StartYear=date('Y');								
                                    for($i=$StartYear;$i>=$EndYear;$i--){
                                        if($i==$_SESSION['filterParam']['year']){
                                            $chk4 = "Selected = 'Selected'";
                                        }else{
                                            $chk4 = "";	
                                        }
                                        echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
                                    }
                                    ?>
                            </select>
                            <?php }else {?>
                        <select name="report_year" id="report_year" class="sb1GeenGradientBoxMiddle" tabindex="2">
                              <?php
                                    $q = "SELECT report_month,report_year FROM tbl_wh_data ORDER BY w_id DESC";
                                    $r = mysql_query($q) or die(mysql_error());
                                    $rs = mysql_fetch_array($r);
                                    
                                    if($rs['report_month']=='12'){
                                        $mymonth = '01';	
                                        $myyear = $rs['report_year']+1;	
                                    }else{
                                        $mymonth = $rs['report_month'];	
                                        $myyear = $rs['report_year'];
                                    }
                                    
                                    
                                    //$WHNameArray[]=$rs['wh_name'];
                                
                                    $EndYear=2008;
                                    $StartYear=date('Y');													
                                    for($i=$StartYear;$i>=$EndYear;$i--){
                                        if($myyear==$i){
                                            $chk4 = "Selected = 'Selected'";	
                                        }else{
                                            $chk4 = "";	
                                        }
                                        echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
                                    }
                                    ?>
                            </select>
                             <?php }?>
                        </td>
                        <td>
                        <select title="Select Province" name="provinces" tabindex="6" id="provinces" style="width:200px;" onchange="fetchDistricts();">
                              <option value="0">--- Select Province---</option>
                              <?
                                    $strSQL="select prov_id,prov_title from province order by prov_id";
                                    $rsTemp1=safe_query($strSQL);
                                    while($rsRow1=mysql_fetch_array($rsTemp1)) {
                                        if ($rsRow1['prov_id'] == $_SESSION['filterParam']['province']){
                                            $var = "selected=selected";	
                                        }else{
                                            $var = "";	
                                        }
                                        echo "<OPTION VALUE='$rsRow1[prov_id]' $var>$rsRow1[prov_title]</OPTION>";
                                    }
                                    mysql_free_result($rsTemp1);
                                ?>
                            </select>              
                        </td>
                        
                        <td><div id="divdists"><select title="Select Warehouse" style="width:200px;">
                            <option value="">--- Select Province First---</option>
                          </select></div>
                        </td>
                        <td>
                        <input type="submit" value="Go" name="submit"/>			
                        </td>
                    </tr>
                    </table>
                    
                    </form>
                    </td>
                </tr>
                     <?php if(isset($_POST['submit'])){if ($_SESSION['numOfRows'] > 0){?>
                <tr>
                    <td>
                      Choose skin to apply: 
                      <select onChange="mygrid.setSkin(this.value)">
                        <option value="light" selected>Light
                        <option value="sbdark">SB Dark
                        <option value="gray">Gray
                        <option value="clear">Clear
                        <option value="modern">Modern
                        <option value="dhx_skyblue">Skyblue
                    </select>
                    </td>
                
                    <td align="right">
                        <img title="Click here to export data to PDF file" style="cursor:pointer;" src="../../images/pdf-32.png" onClick="mygrid.toPDF('dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
                        <img title="Click here to export data to Excel file" style="cursor:pointer;" src="../../images/excel-32.png" onClick="mygrid.toExcel('dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
                   </td>
                <td>&nbsp;</td>
                </tr>
                     <?php }}?>
               </table>
    
            <?php if(isset($_POST['submit'])){if ($_SESSION['numOfRows'] > 0){?>
                <table width="99%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                        
                            <div id="mygrid_container" style="width:100%; height:350px; background-color:white;overflow:hidden"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="recinfoArea"></div>
                        </td>
                    </tr>
                </table>
            <?php }else { 
                        $qryRes = mysql_fetch_array(mysql_query("SELECT * FROM `tbl_warehouse` WHERE `wh_id`='".$_POST['districts']."'"));
                        $disMonth = array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $tempVar = $_POST['report_month']-1;
                    ?>
                        <script type="text/javascript">fetchDistricts();</script>
                        <div style="font-size:12px; font-weight:bold; color:#F00; text-align:left"><?php if (!empty($_POST['districts'])){echo "No data entered for $qryRes[wh_name]($qryRes[wh_type_id]) in $disMonth[$tempVar], $_POST[report_year].";}else {echo "No data entered in $disMonth[$tempVar], $_POST[report_year].";}?> </div>
                    <?php }
            
            } ?>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>