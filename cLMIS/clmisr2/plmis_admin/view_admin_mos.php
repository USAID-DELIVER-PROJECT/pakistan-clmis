<?php 


/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
include("../html/adminhtml.inc.php");
include("Includes/xml_genaration_mos.php");


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
<title>View MOS Scale</title>

     <LINK HREF="../../plmis_css/cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <LINK HREF="../../plmis_css/main.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
     <link rel="STYLESHEET" type="text/css" href="dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
     <link rel="stylesheet" type="text/css" href="dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
     
	 
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
     <script src='dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
     <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>	
	 <script src="dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
     <script src="dhtmlxGrid/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
     
 	 <script>
     	function editFunction(val){
		 	window.location="AddEditMosScale.php?ActionType=EditShow&mosid="+val;
		}
		function delFunction(val){
			if (confirm("Are you sure you want to delete the record?")){
				window.location="AddEditMosScale.php?ActionType=DeleteData&mosid="+val;
			}	
		}
     </script>
     <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       mygrid.setHeader("<span title='Product Name'>Product</span>,<span title='Stakeholder Name'>Stakeholder</span>,<span title='Distribution Level'>Distribution Level</span>,<span title='Stock Situation'>Long Term</span>,<span title='Starting Scale'>Scale Start</span>,<span title='Ending Scale'>Scale End</span>,<span title='Color Code'>Color Code</span>,<span title='Use this column to perform desired operation'>Actions</span>,#cspan");
	   mygrid.attachHeader("#select_filter,#select_filter,#select_filter,#select_filter");
       mygrid.setInitWidths("*,200,130,100,100,100,100,30,30");
       mygrid.setColAlign("left,left,left,left,left,left,left,right,left")
       mygrid.setColSorting("str,str,str,str,int,int,str");
       mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,img,img");
	   //mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
       mygrid.setSkin("light");
       mygrid.enablePaging(true, 10, 3, "recinfoArea");
       mygrid.setPagingSkin("toolbar", "dhx_skyblue");
       
	   mygrid.init();
       mygrid.loadXML("Includes/mos.xml");
      }
 
     </script>
 </head>
  <body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onload="doInitGrid()">
      <?php 
	?><?php siteMenu();?>
	<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
    	<div class="wrraper" style="height:auto; padding-left:60px">
		<div class="content" align="" style="min-height:679px;"><br />
        
		<?php showBreadCrumb1();?><div style="float:right; padding-right:33px"><?php echo readMeLinks($readMeTitle);?></div><br /><br />
        
        	<p><span><b style="color: #000000; font-size: 22px;font-weight: bold;">View MOS Scale</b></span><br />
        		<span><b style="font-size:15px">Use the action column to perform desired function.</b></span></p>
       
   <table width="100%">
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
        <td style="padding-right:28px;" align="right">
			<img title="Click here to export data to PDF file" style="cursor:pointer;" src="../plmis_img/pdf.bmp" onClick="mygrid.toPDF('dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
        	<img title="Click here to export data to Excel file" style="cursor:pointer; margin-left:-5px" src="../plmis_img/excel.bmp" onClick="mygrid.toExcel('dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
       </td>
    </tr>
   </table>

     
<table width="920" cellpadding="0" cellspacing="0">
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
             
   		</div>
    </div>
 
<?php footer();    ?>
<?php endHtml();?>