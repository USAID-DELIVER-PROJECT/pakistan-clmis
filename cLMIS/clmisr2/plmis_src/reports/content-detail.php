<?php
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file which will the contents from tbl_cms, this page will appear in facebox
/***********************************************************************************************************/

	//include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/CnnDb.php");	//Include Database Connection File
	include("../../plmis_inc/common/FunctionLib.php");	//Include Global Function File				
	include('../../plmis_inc/common/DateTime.php');	//Include Date Function File	
	include("../../plmis_inc/classes/cCms.php");
			
	$BST=BST_DtTm();			
	
	$objDB = new Database();
	$objDB->connect();
	$objCms = new cCms();	
	
	$cid	=$_REQUEST['title']; 
	
	$data_array = array();
	$sql = $objCms->Select("tbl_cms"," * ", "AND title = '$cid'");
	
	if($objDB->query($sql) and $objDB->get_num_rows()>0)
	{
		$data_array = $objDB->fetch_one_assoc();
			
	}

	$objDB->close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
	<HEAD>
		
		<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
			<!--
				var width = 280, height = 360;
				window.onerror = ScriptError;				
				function ScriptError()
					{
						//window.parent.location="../Error.php";
						//return true;
					}
				if((window.parent.document.LoginInfo.LogedID.value=="")||(window.parent.document.LoginInfo.LogedUser.value=="")||(window.parent.document.LoginInfo.LogedUserType.value==""))
					{
						//window.parent.location="../Error.php";
					}
				function ShowData(RowID)
					{
						document.frmData.ActionType.value="EditShow"
						document.frmData.PrvRecordID.value=RowID
						document.frmData.submit();
					}
				function DeleteData(RowID)
					{
						var msg = confirm("Are You Sure, Want To Delete This Record ?",true);
						if(!(msg)) 
						{
							return false;
						}
						else
						{
							document.frmData.ActionType.value="DeleteData"
							document.frmData.PrvRecordID.value=RowID
							document.frmData.submit();
                            window.parent.refreshNoToggle();
						}
					}
				function Logout()
					{
						window.parent.location="../Logout.php?Logid="+document.frmData.LogedID.value
					}
			//-->
		</SCRIPT>
        
        <script language="javascript">

		function CheckAll(){
			for (var a=0; a<document.frm.elements.length; a++){
				var e = document.frm.elements[a];
	
				if (e.name != 'selectUnselectAll'){
					e.checked = document.frm.selectUnselectAll.checked;
				}
			}
		}
	
</script>
		
		
	</HEAD>
	<BODY LEFTMARGIN="10" TOPMARGIN="5" MARGINWIDTH="0" MARGINHEIGHT="0" CLASS="WorkArea">
	<table width="650" height="650"  cellspacing="0" cellpadding="0" class="maintbl" align="center">
		
		<tr>
			<td class="topnav" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td class="middlearea" valign="top">
			<table cellspacing="0" cellpadding="10" width="100%" height="100%" >
				<tr>          			
                     
			        <td width="95%" valign="top" align="center">
                    <form name="frm" method="post" enctype="multipart/form-data">
					 
                    <table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0" class="tbllisting" >
                    	<tr class="mainhead">
                    	  <td height="67" colspan="8">Press Esc key to exit</td>
                   	  </tr>
                  
                    	<tr >
                    	  <td width="16%" height="29" align="left" valign="top" style="border-bottom:#d1d1d1 solid 1px;">
				     <td width="84%" valign="top" style="border-bottom:#d1d1d1 solid 1px;" ><?php echo $data_array['title1']; ?></td>
					  </tr>
								
								<tr>
							<td colspan="2" align="left" valign="top">
                            <div style="height:100%; overflow:auto; width:100%">
							<?php echo stripslashes($data_array['description']); ?>
                            </div>
                            </td>
								</tr> 
			</table>
                  </form></td>
			    </tr>
			</table></td>
		</tr>
		<tr>
			<td class="footer">&nbsp;</td>
		</tr>
	</table>
	</BODY>
</HTML>