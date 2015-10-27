<?php 

/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
	include("Includes/AllClasses.php");
	include("header.php");
	
	if (isset($_SESSION['userid']))
	{
		$userid=$_SESSION['userid'];
		$objwharehouse_user->m_npkId=$userid;
		$result=$objwharehouse_user->GetwhuserByIdc();
	}
	else
	echo "user not login or timeout";


/////unset all sessions of form submission
	unset($_SESSION['filterParam']['year']);
	unset($_SESSION['filterParam']['month']);
	unset($_SESSION['filterParam']['wh']);
	unset($_SESSION['filterParam']['province']);
	unset($_SESSION['numOfRows']);


	include("xml/xml_genaration_content.php");
	
		function deleteFile($dir, $fileName)
	{
		$handle=opendir($dir);
		
		while (($file = readdir($handle))!==false)
		{
			if ($file == $fileName)
			{
				@unlink($dir.'/'.$file);
			}
		}
		closedir($handle);
	} 

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
	
	if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
	{
		$strDo = $_REQUEST['Do'];
	}
	
	if(isset($_REQUEST['Id']) && !empty($_REQUEST['Id']))
	{
		$nstkId  = $_REQUEST['Id'];
	}
	
	if($strDo=='Delete'){
		
		$sql="select logo from tbl_cms where id = '".$nstkId."'";	
		$result = mysql_fetch_array(mysql_query($sql));

		//deleting previous image
		if(!empty($result['logo'])){
		deleteFile('images/',$result['logo']);
		}
		
		$objContent->m_npkId=$nstkId;
		$objContent->Deletelogocontent();
		header("location:view_admin_content.php");
		
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
<title><?php echo $system_title."View Contents";?></title>

     <LINK HREF="<?php echo PLMIS_CSS;?>cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <LINK HREF="<?php echo PLMIS_CSS;?>main.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
     <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
     <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
     <link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
     <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
     <link rel="stylesheet" type="text/css" href="lightbox/themes/default/jquery.lightbox.css" />
	  
	 <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
     <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
     <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
     <script src='../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/client/dhtmlxgrid_export.js'></script>
     <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
     <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
	 <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>	
	 <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
     <script src="../plmis_src/operations/dhtmlxToolbar/codebase/dhtmlxtoolbar.js"></script>
	 <script src="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
     <script type="text/javascript" src="lightbox/js/jquery.lightbox.js"></script>
     <!--<script type="text/javascript" src="Scripts/jquery-1.7.min.js"></script>-->
   
 	 <script>
     	function editFunction(val){
			window.location="AddEditContent.php?Do=Edit&Id="+val;
		}
		function delFunction(val){
			if (confirm("Are you sure you want to delete the record?")){
				window.location="view_admin_content.php?Do=Delete&Id="+val;
			}	
		}
     </script>
     <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       mygrid.setHeader("<span title='Title of the page'>Title</span>,<span title='heading of the page'>Page Heading</span>,<span title='Stakeholder'>Stakeholder</span>,<span title='Province'>Province</span>,<span title='content type'>Content Type</span>,<span title='Logo Image'>Logo Image</span>,<span title='Use this column to perform the desired operation'>Actions</span>,#cspan");
	   mygrid.attachHeader("#select_filter",'','','','','');
       mygrid.setInitWidths("150,150,150,150,120,120,30,30");
       mygrid.setColAlign("left,left,left,right,left,left")
       mygrid.setColSorting("str");
       mygrid.setColTypes("ro,ro,ro,ro,ro,ro,img,img");
	   //mygrid.enableLightMouseNavigation(true);
	   mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
       mygrid.setSkin("light");
       mygrid.enablePaging(true, 10, 7, "recinfoArea");
       mygrid.setPagingSkin("toolbar", "dhx_skyblue");
       
	   mygrid.init();
       mygrid.loadXML("xml/content.xml");
      }
 
     </script>  
 </head>
  <body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;height:650px; " onload="doInitGrid()">
      <?php 
	?>
	<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?></div>
    	<div class="wrraper" style="padding-left:60px; height:655px">
		<div class="content" align=""><br />
        
		<?php //showBreadCrumb();?><div style="float:right; padding-right:130px"><?php //echo readMeLinks($readMeTitle);?></div><br /><br />
        
        	<p><span><b style="color: #000000; font-size: 22px;font-weight: bold;">View Content</b></span><br/>
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
        <td style="padding-right:150px;" align="right">
			<img title="Click here to export data to PDF file" style="cursor:pointer;" src="../plmis_img/pdf.bmp" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
           <img title="Click here to export data to Excel file" style="cursor:pointer; margin-left:-5px" src="../plmis_img/excel.bmp" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
       </td>
    </tr>
   </table>

     
<table width="820" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div id="mygrid_container" style="width:100%; height:355px; background-color:white;overflow:hidden"></div>
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
 <script type="text/javascript">
		$.noConflict();
		//$(document).ready(function(){
			$('.lightbox').lightbox();
		//});
	</script>
<?php //footer();    ?>
<?php //endHtml();?>