<?php ob_start();
session_start();

include("Includes/AllClasses.php");
ini_set('max_execution_time', 1000);
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
//include("../../../html/adminhtml.inc.php");
//Login();
//echo "user name =".$_SESSION['user'];
if(isset($_REQUEST['ActionType']) && $_REQUEST['ActionType']=="DeleteData"){
	$PrvRecordID = $_GET['DelRecId'];
	mysql_query("delete from tbl_waiting_data where w_id=$PrvRecordID");
	echo '<script>window.location="view_admin_waitingdata.php?msg=00;</script>';
}


include("xml/xml_genaration_waitingdata.php");



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
<title><?php echo $system_title. " - ";?>Waiting Data</title>

     <LINK HREF="../../plmis_css/cpanel.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <LINK HREF="../../plmis_css/main.css" REL="STYLESHEET" TYPE="TEXT/CSS">
     <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
	 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
	 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
	 <link rel="stylesheet" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
	 <link rel="STYLESHEET" type="text/css" href="../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">

	  
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
	 

 	 <script>
     	function editFunction(val){
			window.location="AddEditUser.php?ActionType=EditShow&pruser_id="+val;
		}
		function delFunction(val){
			if (confirm("Are you sure you want to delete the record?")){
				window.location="view_admin_waitingdata.php?ActionType=DeleteData&DelRecId="+val;
			}	
		}
     </script>
     <script>
      var mygrid;
      function doInitGrid(){
       mygrid = new dhtmlXGridObject('mygrid_container');
   	   mygrid.selMultiRows = true;
       mygrid.setImagePath("../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/codebase/imgs/");
       mygrid.setHeader("<span title='Month of which data is shown'>Month</span>,<span title='Year of which data is shown'>Year</span>,<span title='Product name'>Product</span>,<span title='Warehouse name'>Warehouse</span>,<span title='Opening balance'>Opening Balance</span>,<span title='Balance received'>Received</span>,<span title='Balance issued'>Issued</span>,<span title='Closing balance'>Closing Balance</span>,<span title='Field Open Balance'>Field Open Balance</span>,<span title='Field balance received'>Field Received</span>,<span title='Field balance issued'>Field Issued</span>, <span title='Field closing balance'>Field Closing Balance</span>,<span title='Closing balance'>Closing Balance</span>,<span title='Use this column to perform desired operation'>Actions</span>,#cspan");
	   mygrid.attachHeader("#select_filter,#select_filter,#select_filter,#select_filter,,,,,,,,,,<input type='checkbox' class='chkbox_class' onclick='CheckAll(document.trackunread);' value='Check All' name='allbox' style='border:inset 1px;'>,,");
       mygrid.setInitWidths("60,60,*,90,60,65,60,60,60,65,60,60,60,30,30");
       mygrid.setColAlign("left,left,left,left,right,right,right,right,right,right,right,right,right,center,left");
       mygrid.setColSorting("str,str,str,str");
       mygrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,co,ro,img");
	   //mygrid.enableLightMouseNavigation(true);
		mygrid.enableRowsHover(true,'onMouseOver');   // `onMouseOver` is the css class name.
	   mygrid.setSkin("light");
       mygrid.enablePaging(true, 10, 3, "recinfoArea");
       mygrid.setPagingSkin("toolbar", "dhx_skyblue");
	   
	   mygrid.init();
       mygrid.loadXML("xml/waitingdata.xml");
      }
 
     </script>
	 <script type="text/javascript">
	 //==========================================
// Check All boxes
//==========================================
function CheckAll(fmobj)
{
	//alert(fmobj.elements.length);
	for (var i=0;i<fmobj.elements.length;i++)
	{
		var e = fmobj.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox') && (!e.disabled))
		{
			e.checked = fmobj.allbox.checked;
		}
	}//alert(fmobj.elements.length);
}

//==========================================
// Check all or uncheck all?
//==========================================
function CheckCheckAll(fmobj)
{	
	var TotalBoxes = 0;
	var TotalOn = 0;
	for (var i=0;i<fmobj.elements.length;i++)
	{
		var e = fmobj.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			TotalBoxes++;
			if (e.checked)
			{
				TotalOn++;
			}
		}
	}
	
	if (TotalBoxes==TotalOn)
	{
		fmobj.allbox.checked=true;
	}
	else
	{
		fmobj.allbox.checked=false;
	}
}
	 
	 </script>
	<SCRIPT TYPE="text/javascript">
function formValidation(){
	if( $('input[type="checkbox"]:checked').length > 0 ){
	   return true;
	}else if( $('input[type="checkbox"]:checked').length == 0 ){
	   alert("Check atleast 1 record.");
	   return false;
	}
}
</SCRIPT>

</head>
<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;" onLoad="doInitGrid()">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><?php include("header.php");?></td>
		</tr>
		<tr>
			<td width="100%">
				<div align="center" id="errMsg" style="color:#060"><?php if (isset($_GET['msg']) && $_GET['msg'] == 02){echo "Record has been successfully updated.";}else if (isset($_GET['msg']) && $_GET['msg'] == 01){echo "Record has been successfully added.";}else if (isset($_GET['msg']) && $_GET['msg'] == 00){echo "Record has been successfully deleted.";}?>
				</div>
			    <div style="text-align:center"> 
					<font style="color:#99CC33; font-weight:bold;" >
					<?php if(!empty($_GET['flag'])){
							echo "Data Approved Successfully";
					} ?>
					</font>
		        </div>
	        
	            <!--<div id="mygrid_container" style="width:820px;height:300px;"></div>
	             <div id="recinfoArea"></div>-->
	  
	   			<table width="75%" align="center">
				   	<tr>
						<td colspan="2">
					        <p>
								<span><b style="color: #000000; font-size: 22px;font-weight: bold;" title="Here is the data waiting for administrator approval.">Waiting Data</b></span><br />
				        		<span><b style="font-size:15px; color:#545353;">Use the action column to perform desired function.</b></span>
							</p>
						</td>
					</tr>
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
			                <img title="Click here to export data to PDF file" style="cursor:pointer;" src="../plmis_img/pdf.bmp" onClick="mygrid.toPDF('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2pdf/server/generate.php');" />
			               <img title="Click here to export data to Excel file" style="cursor:pointer; margin-left:-5px" src="../plmis_img/excel.bmp" onClick="mygrid.toExcel('../plmis_src/operations/dhtmlxGrid/dhtmlxGrid/grid2excel/server/generate.php');" />
			           </td>
				    </tr>
				   </table>
	
	     
				<table width="75%" align="center" cellpadding="0" cellspacing="0">
				    <tr>
				        <td>
				          <form name="trackunread" action="waitingdata_approved.php" method="post" onSubmit="return formValidation()" >
						   		<div id="mygrid_container" style="height:350px; background-color:white;overflow:hidden"></div>
								<div align="center" style="margin-top:5px;"><input type="submit" name="submit" value="Approve Data" /></div>
						  
						    </form>
				        </td>
				    </tr>
				    <tr>
				        <td>
				            <div id="recinfoArea"></div>
				        </td>
				    </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>All Rights Reserved</td>
		</tr>
	</table>
</body>
</html>