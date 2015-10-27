<?php ob_start();
///////////**************** //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






//session_start();


include("Includes/AllClasses.php");

/***********************************************************************************************************
Developed by ESOLPK "www.esolpk.com"
This is the file used to add/edit/delete the reports. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted 
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/  
 
 ///////////**************** 
 //include("../../html/adminhtml.inc.php");
 
 
 ///////////**************** /////////////////////////////////////////////////////////////////////////////
 
 
 
 
 //Login();
 
 
 
 
 
 

    //////////// GET FILE NAME FROM THE URL
 	$basename = basename($_SERVER['REQUEST_URI']);
	$filePath = "plmis_src/reports/".$basename;
	
	//////// GET Read Me Title From DB. 
	
	$qryResult = mysql_fetch_array(mysql_query("select extra from sysmenusub_tab where menu_filepath = '".$filePath."' and active = 1"));
	$readMeTitle = $qryResult['extra'];
	
	
//include("plmis_inc/common/CnnDb.php");	//Include Database Connection File
//include("plmis_inc/common/FunctionLib.php");
	//Include Global Function File
//echo "itemRec=".$_POST['itmrec_id'];
//echo "ActionType=".$_POST['ActionType'];
if($_POST['ActionType']=='Add') {
	
	/*echo '<pre>';
	print_r($_POST); 
	exit;*/
	if(isset($_POST['itmrec_id']) && !empty($_POST['itmrec_id']) && is_array($_POST['itmrec_id']))
		$postedArray = $_POST['itmrec_id'];
	else
		$postedArray = $_POST['flitmrec_id'];
		
	foreach($postedArray as $val){
	
		$itemid = explode('-',$val);
		$queryadddata = "INSERT INTO tbl_wh_data SET
							report_month = '".$_POST['report_month']."',
							report_year = '".$_POST['report_year']."',							
							item_id = '".$val."',
							wh_id = '".$_POST['wh_id']."',
							wh_obl_a = '".$_POST['WHOBLA'.$itemid[1]]."',
							wh_obl_c = '".$_POST['WHOBLC'.$itemid[1]]."',
							wh_received = '".$_POST['WHRecv'.$itemid[1]]."',
							wh_issue_up = '".$_POST['IsuueUP'.$itemid[1]]."',
							wh_cbl_c = '".$_POST['WHCBLC'.$itemid[1]]."',
							mos = '".$_POST['MOS'.$itemid[1]]."',
							wh_cbl_a = '".$_POST['WHCBLA'.$itemid[1]]."',
							wh_adja = '".$_POST['ReturnTo'.$itemid[1]]."',
							wh_adjb = '".$_POST['Unusable'.$itemid[1]]."',
							fld_obl_a = '".$_POST['FLDOBLA'.$itemid[1]]."',
							fld_obl_c = '".$_POST['FLDOBLC'.$itemid[1]]."',
							fld_recieved = '".$_POST['FLDRecv'.$itemid[1]]."',
							amc = '".$_POST['myavg'.$itemid[1]]."',
							fld_issue_up = '".$_POST['FLDIsuueUP'.$itemid[1]]."',
							fld_cbl_c = '".$_POST['FLDCBLC'.$itemid[1]]."',
							fld_cbl_a = '".$_POST['FLDCBLA'.$itemid[1]]."',
							fld_mos = '".$_POST['FLDMOS'.$itemid[1]]."',																												
							fld_adja = '".$_POST['FLDReturnTo'.$itemid[1]]."',
							fld_adjb = '".$_POST['FLDUnusable'.$itemid[1]]."'";
		$rsadddata = mysql_query($queryadddata) or die(mysql_error());
		
	}
	header("location:AddEditF7.php?action=Update121");
	exit;

}



if($_POST['ActionType']=='Update') {
	
	if(isset($_POST['itmrec_id']) && !empty($_POST['itmrec_id']) && is_array($_POST['itmrec_id']))
		$postedArray = $_POST['itmrec_id'];
	else
		$postedArray = $_POST['flitmrec_id'];
	
	foreach($postedArray as $val){
/*		echo '<pre>';
		print_r($_POST);
		exit;*/
		$itemid = explode('-',$val);
		//if(isset($_POST['WHOBLA'.$itemid[1]]) && !empty($_POST['WHOBLA'.$itemid[1]]) && isset($_POST['WHOBLC'.$itemid[1]]) && !empty($_POST['WHOBLC'.$itemid[1]])) {
		if(isset($_POST['whentry']) && $_POST['whentry']==1){
			 $queryupddata = "UPDATE tbl_wh_data SET
								wh_obl_a = '".$_POST['WHOBLA'.$itemid[1]]."',
								wh_obl_c = '".$_POST['WHOBLC'.$itemid[1]]."',
								wh_received = '".$_POST['WHRecv'.$itemid[1]]."',
								wh_issue_up = '".$_POST['IsuueUP'.$itemid[1]]."',
								wh_cbl_c = '".$_POST['WHCBLC'.$itemid[1]]."',
								mos = '".$_POST['MOS'.$itemid[1]]."',
								wh_cbl_a = '".$_POST['WHCBLA'.$itemid[1]]."',
								wh_adja = '".$_POST['ReturnTo'.$itemid[1]]."',
								wh_adjb = '".$_POST['Unusable'.$itemid[1]]."',
								amc = '".$_POST['myavg'.$itemid[1]]."'
							WHERE 
								report_month = '".$_POST['report_month']."' AND
								report_year = '".$_POST['report_year']."' AND					
								item_id = '".$val."' AND
								wh_id = '".$_POST['wh_id']."'";
			$rsupddata = mysql_query($queryupddata) or die(mysql_error());
		}
		
		//if(isset($_POST['FLDOBLA'.$itemid[1]]) && !empty($_POST['FLDOBLA'.$itemid[1]]) && isset($_POST['FLDOBLC'.$itemid[1]]) && !empty($_POST['FLDOBLC'.$itemid[1]])) {
		if(isset($_POST['fldentry']) && $_POST['fldentry']==1){
			$queryupddata1 = "UPDATE tbl_wh_data SET
								fld_obl_a = '".$_POST['FLDOBLA'.$itemid[1]]."',
								fld_obl_c = '".$_POST['FLDOBLC'.$itemid[1]]."',
								fld_recieved = '".$_POST['FLDRecv'.$itemid[1]]."',
								amc = '".$_POST['FLDmyavg'.$itemid[1]]."',
								fld_issue_up = '".$_POST['FLDIsuueUP'.$itemid[1]]."',
								fld_cbl_c = '".$_POST['FLDCBLC'.$itemid[1]]."',
								fld_cbl_a = '".$_POST['FLDCBLA'.$itemid[1]]."',
								fld_mos = '".$_POST['FLDMOS'.$itemid[1]]."',																												
								fld_adja = '".$_POST['FLDReturnTo'.$itemid[1]]."',
								fld_adjb = '".$_POST['FLDUnusable'.$itemid[1]]."'
							WHERE 
								report_month = '".$_POST['report_month']."' AND
								report_year = '".$_POST['report_year']."' AND					
								item_id = '".$val."' AND
								wh_id = '".$_POST['wh_id']."'";
			$rsupddata1 = mysql_query($queryupddata1) or die(mysql_error());
		}
	}
	header("location:AddEditF7.php?action=Update121");
	exit;
}
?>
		
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $system_title." - Distribution And Stock Balance Reports"; ?></title>
    <link href="<?php echo PLMIS_CSS;?>style.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>main.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>cpanel.css" rel="STYLESHEET" type="TEXT/CSS">
	<link href="<?php echo PLMIS_CSS;?>_forms.css" rel="STYLESHEET" type="TEXT/CSS">
	<LINK ID="GridCSS" href="<?php echo PLMIS_CSS;?>Grid.css" TYPE="TEXT/CSS" REL="STYLESHEET">
    <link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/dhtmlxgrid.css">
    <link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_blue.css">
    <link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxToolbar/codebase/skins/dhtmlxtoolbar_dhx_skyblue.css">
    <link rel="stylesheet" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_skyblue.css">
    <link rel="STYLESHEET" type="text/css" href="../operations/dhtmlxGrid/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn_bricks.css">
 	
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>FunctionLib.js"></SCRIPT>
	 <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>ClockTime.js"></SCRIPT>
     <SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT" SRC="<?php echo PLMIS_JS;?>cms.js"></SCRIPT>
     <script src="<?php echo PLMIS_JS;?>jquery-1.4.4.js" type="text/javascript"></script>
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
 
    </head>
<BODY text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;">
		
		<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
			   // document.getElementById('myStakeholder').innerHTML = window.parent.document.LoginInfo.Stakeholder.value;
				var width = 910, height = 300;
				var IT;
				/*var str = '1-3456';
				var straray = str.split('-');
				alert(straray[1]);*/
				//docment.write(str.split('-'))
				window.onerror = ScriptError;				
				function ScriptError()
					{
						//window.parent.location="../Error.php";
						//return true;
					}
				
				function FilterData()
					{
						document.frmF7.ActionType.value="Filter"
						document.frmF7.submit();
					}
				function ShowData(RowID)
					{
						document.frmF7.ActionType.value="EditShow"
						//document.frmF7.Stake.value=stk
						document.frmF7.PrvRecordID.value=RowID
						document.frmF7.submit();
					}				
				function Logout()
					{
						window.parent.location="../Logout.php?Logid="+document.frmF7.LogedID.value
					}
				
				function roundNumber(num, dec) {
					var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
					return result;
				}
				
				function cal_balance(itemId){
						
						//alert(itemId);
						/*alert('hi');
						alert(itemId);
						//alert(document.getElementById('myavg'+itemId).value);
						return false;*/
						
						//alert('called');
						if(document.getElementById('WHOBLC'+itemId))
							var wholbc = (document.getElementById('WHOBLC'+itemId).value=="")? 0 : eval(document.getElementById('WHOBLC'+itemId).value);
						else
							var wholbc = 0;
							
						if(document.getElementById('WHOBLA'+itemId))	
							var wholba = (document.getElementById('WHOBLA'+itemId).value=="")? 0 : eval(document.getElementById('WHOBLA'+itemId).value);
						else
							var wholba = 0;
							
						if(document.getElementById('WHRecv'+itemId))	
							var WHRecv = (document.getElementById('WHRecv'+itemId).value=="")? 0 : eval(document.getElementById('WHRecv'+itemId).value);
						else
							var WHRecv = 0;
							  
						if(document.getElementById('IsuueUP'+itemId))
							var IsuueUP = (document.getElementById('IsuueUP'+itemId).value=="")? 0 : eval(document.getElementById('IsuueUP'+itemId).value);
						else
							var IsuueUP = 0;
							
						//WH adj+
						if(document.getElementById('ReturnTo'+itemId))
							var ReturnTo = (document.getElementById('ReturnTo'+itemId).value=="")? 0 : eval(document.getElementById('ReturnTo'+itemId).value);
						else
							var ReturnTo = 0; 


						//WH adj-
						if(document.getElementById('Unusable'+itemId))
							var Unusable = (document.getElementById('Unusable'+itemId).value=="")? 0 : eval(document.getElementById('Unusable'+itemId).value);
						else
							var Unusable = 0;							
						
						if(document.getElementById('FLDOBLC'+itemId))
							var fldolbc  = (document.getElementById('FLDOBLC'+itemId).value=="")? 0 : eval(document.getElementById('FLDOBLC'+itemId).value);
						else
							var fldolbc  = 0;
						
						if(document.getElementById('FLDOBLA'+itemId))	 
							var fldolba  = (document.getElementById('FLDOBLA'+itemId).value=="")? 0 : eval(document.getElementById('FLDOBLA'+itemId).value);
						else
							var fldolba  = 0;
						
						if(document.getElementById('FLDRecv'+itemId))	 	
							var FLDRecv  = (document.getElementById('FLDRecv'+itemId).value=="")? 0 : eval(document.getElementById('FLDRecv'+itemId).value);
						else
							var FLDRecv  = 0;
							
						if(document.getElementById('FLDIsuueUP'+itemId))	
							var FLDIsuueUP = (document.getElementById('FLDIsuueUP'+itemId).value=="")? 0 : eval(document.getElementById('FLDIsuueUP'+itemId).value);
						else
							var FLDIsuueUP = 0;							
							
						/*if(document.getElementById('FLDmyavg'+itemId))	
							var FLDmyavg = (document.getElementById('FLDmyavg'+itemId).value=="")? 0 : eval(document.getElementById('FLDmyavg'+itemId).value);
						else
							var FLDmyavg = 0;*/ 							
						
						//Fld adj+
						if(document.getElementById('FLDReturnTo'+itemId))
							var FLDReturnTo = (document.getElementById('FLDReturnTo'+itemId).value=="")? 0 : eval(document.getElementById('FLDReturnTo'+itemId).value);
						else
							var FLDReturnTo = 0;
						
						//Fld adj-
						if(document.getElementById('FLDUnusable'+itemId))
							var FLDUnusable = (document.getElementById('FLDUnusable'+itemId).value=="")? 0 : eval(document.getElementById('FLDUnusable'+itemId).value);
						else
							var FLDUnusable = 0; 
												
						
						if(document.getElementById('FLDmyavg'+itemId)){
							var myavg = document.getElementById('FLDmyavg'+itemId).value;
						} else {
							var myavg = document.getElementById('myavg'+itemId).value;
						}

						var mycalavg = myavg.split('-');

						if(document.getElementById('FLDIsuueUP'+itemId))
							var divisible = eval(mycalavg[1]+FLDIsuueUP);
						else
							var divisible = eval(mycalavg[1]+IsuueUP);
						
						var divider = eval(mycalavg[0]+1);

						if(eval(divider)>0){
							var myactualavg = eval(divisible)/eval(divider);
						} else {
							var myactualavg = eval(divisible)/1;
						}
						//alert(myactualavg);
						//alert(document.getElementById('WHCBLA'+itemId).value+'-'+myactualavg);
						/*else
							var myavg = 1;*/
							
							
						//var WHCBLC = (document.getElementById('WHCBLC'+itemId).value=="")? 0 : eval(document.getElementById('WHCBLC'+itemId).value);
						//var WHCBLA = (document.getElementById('WHCBLA'+itemId).value=="")? 0 : eval(document.getElementById('WHCBLA'+itemId).value);
						//var MOS = (document.getElementById('MOS'+itemId).value=="")? 0 : eval(document.getElementById('MOS'+itemId).value);
						if(document.getElementById('WHCBLC'+itemId))
							document.getElementById('WHCBLC'+itemId).value = (wholbc+WHRecv+ReturnTo)-(IsuueUP+Unusable);

						if(document.getElementById('WHCBLA'+itemId))	
							document.getElementById('WHCBLA'+itemId).value = (wholba+WHRecv+ReturnTo)-(IsuueUP+Unusable);
							
						if(document.getElementById('MOS'+itemId) && document.getElementById('WHCBLA'+itemId)){	
							if(eval(myactualavg)>0) {						
								document.getElementById('MOS'+itemId).value = roundNumber(eval(document.getElementById('WHCBLA'+itemId).value)/eval(myactualavg),1);
							} else {
								document.getElementById('MOS'+itemId).value = roundNumber(eval(document.getElementById('WHCBLA'+itemId).value)/1,1);
							}
						}
						
						//var FLDCBLC = (document.getElementById('FLDCBLC'+itemId).value=="")? 0 : eval(document.getElementById('FLDCBLC'+itemId).value);
						//var FLDCBLA = (document.getElementById('FLDCBLA'+itemId).value=="")? 0 : eval(document.getElementById('FLDCBLA'+itemId).value);
						//var FLDMOS = (document.getElementById('FLDMOS'+itemId).value=="")? 0 : eval(document.getElementById('FLDMOS'+itemId).value);
						if(document.getElementById('FLDCBLC'+itemId))
							document.getElementById('FLDCBLC'+itemId).value = (fldolbc+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
						
						if(document.getElementById('FLDCBLA'+itemId))	
							document.getElementById('FLDCBLA'+itemId).value = (fldolba+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
						
						if(document.getElementById('FLDMOS'+itemId) && document.getElementById('FLDCBLA'+itemId)){
							if(eval(myactualavg)>0) {
								document.getElementById('FLDMOS'+itemId).value = roundNumber(eval(document.getElementById('FLDCBLA'+itemId).value)/eval(myactualavg),1);
							} else {
									document.getElementById('FLDMOS'+itemId).value = roundNumber(eval(document.getElementById('FLDCBLA'+itemId).value)/1,1);
							}
						}
												
				}
	
			function ContinueValidate()
				{
					if (document.frmF7.wh_id.value== "")
						{
							alert("Please Select A WAREHOUSE/DRS");
							document.frmF7.wh_id.focus();
							return false;
						}
					
					if (document.frmF7.report_year.value== "")
						{
							alert("Please Select A Year");
							document.frmF7.report_year.focus();
							return false;
						}		
					
					if (document.frmF7.report_month.value== "")
						{
							alert("Please Select A Month");
							document.frmF7.report_month.focus();
							return false;
						}	
				}
			
			function formvalidate(){
			
				var arrlength = document.frmaddF7.itmrec_id.length;
				var firstrec = document.frmaddF7.itmrec_id[0].value;
				var firstval = firstrec.split('-');
								
				var atLeastOneEntry = false;
				var fldAtLeastOneEntry = false;
				var fldExists = false;
				
				for(var i=0; i<arrlength; i++){
					
					var fieldval = document.frmaddF7.itmrec_id[i].value;
					fieldconcat = fieldval.split('-');
					var whobla = 'WHOBLA'+fieldconcat[1];
					var whrecv = 'WHRecv'+fieldconcat[1];
					var whissue = 'IsuueUP'+fieldconcat[1];
					var fldobla = 'FLDOBLA'+fieldconcat[1];
					var fldrecv = 'FLDRecv'+fieldconcat[1];
					var fldissue = 'FLDIsuueUP'+fieldconcat[1];

					if((document.getElementById(whobla).value!=0 || document.getElementById(whobla).value!='')){
						
						if(document.getElementById(whrecv).value==0 || document.getElementById(whrecv).value==''){
							alert('Please Enter Received');
							document.getElementById(whrecv).focus();
							return false;
						} 
						
						if(document.getElementById(whissue).value==0 || document.getElementById(whissue).value==''){
						alert('Please Enter Received');
							document.getElementById(whissue).focus();
							return false;
						
						}
					}
					
					if((document.getElementById(whobla).value!=0 || document.getElementById(whobla).value!='')){
						atLeastOneEntry = true;		
						break;										
					}
					
					
					/*if(document.getElementById('fldentry')){
						if((document.getElementById(fldobla).value!=0 || document.getElementById(fldobla).value!='')){
							
							if(document.getElementById(fldrecv).value==0 || document.getElementById(fldrecv).value==''){
								alert('Please Enter Received');
								document.getElementById(fldrecv).focus();
								return false;
							} 
							
							if(document.getElementById(fldissue).value==0 || document.getElementById(fldissue).value==''){
							alert('Please Enter Received');
								document.getElementById(fldissue).focus();
								return false;
							
							}
						}
						
						if((document.getElementById(fldobla).value!=0 || document.getElementById(fldobla).value!='')){
							fldAtLeastOneEntry = true;		
							break;										
						} 
					}*/
					
					
				// && (document.getElementById(whrecv).value!=0 || document.getElementById(whrecv).value!='') && (document.getElementById(whissue).value!=0 || document.getElementById(whissue).value!='')					
				}
				if(atLeastOneEntry == false){
					alert('Please Enter Atleast one Entry');
					document.getElementById('WHOBLA'+firstval[1]).focus();
					return false;	
				}
				
				/*if(fldAtLeastOneEntry == false){
					alert('Please Enter Atleast one Entry');
					document.getElementById('FLDOBLA'+firstval[1]).focus();
					return false;	
				}*/				
				
				//return false;
			
			}	
		</SCRIPT>
		
     <?php
	 
	 ///////////**************** /////////////////////////////////////////////////////////////////////////////////
	 
	 
	  //siteMenu();
	  
	  
	  ?>
		<?php		
		
			function GetProNameByID($id)
			{
				$Query = safe_query("select prov_id from `tbl_warehouse` where `wh_id`='$id' and stkid='3' order by `prov_id`");
				$test = mysql_fetch_array($Query);
		
				return $test["prov_id"];	
			}
			
			function GetProNameByID2($id)
			{
				$Query = safe_query("select prov_title from `province` where `prov_id`='$id' order by `prov_id`");
				$test = mysql_fetch_array($Query);
				return $test["prov_title"];	
			}
			
			function getStakeHolderName($id){
				$query = "SELECT tbl_districts.wh_name FROM tbl_warehouse LEFT JOIN tbl_districts ON tbl_districts.whrec_id = tbl_warehouse.dist_id  WHERE wh_id=$id";
				$rs = mysql_query($query) or die(mysql_error);				
				$row = mysql_fetch_array($rs);
				
				
			///////////**************** ///////////////////////////////////////////////////////////////////////////////////////////////////////////
				//return $row[0];
				
			}
			//echo $_REQUEST['sysusr_type']; 
			//print_r($_SESSION);
			//echo "type=".$sysusr_type;
			//echo $_REQUEST['sysusr_type'];
			//echo $_SESSION['usertype_plmis'];
			//$_REQUEST['sysusr_type']=$_SESSION['usertype_plmis'];
			//echo 'ddd'.$_SESSION['cws'];
			
			
			///////////**************** ////////////////////////////////////////////////////////////////////////////////////////////////////////
//			if($_SESSION['usertype_plmis']=="Central User"){
//				$rsTemp1=safe_query("SELECT DISTINCT(dist_id),wh_id,wh_name FROM tbl_warehouse $WHcondition GROUP BY dist_id");	
//			
//			} else {
//				
//				if($_SESSION['usertype_plmis']=="UT-001"){
//				  $cws = $_SESSION['cws'];
//				  $rsTemp1=safe_query("SELECT wh_id,dist_id,wh_name FROM tbl_warehouse $WHcondition WHERE `wh_id` = '$cws' ORDER BY wh_name");	
//				 // $rsTemp1=safe_query("SELECT wh_id,dist_id,wh_name FROM tbl_warehouse $WHcondition WHERE wh_type_id = 'CWH' ORDER BY wh_name");		
//				
//				} 
//				else if($_SESSION['usertype_plmis']=="UT-003"){
//				
//				 $rsTemp1=safe_query("SELECT wh_id,wh_name FROM tbl_warehouse WHERE wh_type_id = 'PPIU' AND wh_id='".base64_decode($_SESSION['user']['LogedUserWH'])."'");							
//				}
//				else {
//				
//    			  $cws = $_SESSION['cws'];
//				  $rsTemp1=safe_query("SELECT wh_id,dist_id,wh_name FROM tbl_warehouse $WHcondition WHERE `wh_id` = '$cws' ORDER BY wh_name");	
//	
//				}
//			}
//			
//			while($rsRow1=mysql_fetch_array($rsTemp1))
//				{						
//					$WHRecArray[]=$rsRow1['wh_id'];
//					
//					if(!empty($rsRow1['wh_name']))
//						$WHNameArray[]=$rsRow1['wh_name'];
//					else{
//						$qWRName = "SELECT whrec_id,wh_name FROM tbl_districts WHERE whrec_id='".$rsRow1['dist_id']."'";
//						$rWRName = mysql_query($qWRName) or die(mysql_error());
//						$rsWRName = mysql_fetch_array($rWRName);
//						$WHNameArray[]=$rsWRName['wh_name'];
//					} 	
//				}
//			mysql_free_result($rsTemp1);
			
			/*echo "<pre>";
				echo "here will be the results";
				print_r($WHNameArray);
			echo "</pre>";*/
			
	?>		

<?php 
//startHtml($system_title." - Distribution And Stock Balance Reports");
?>


<td><?php include("header.php");?></td>

<div style="clear:both"></div>
<div class="wrraper" style="padding-left:60px; height:auto">
		<div class="content" align="">

		<?php 
			function getConsumptionAvg($month, $year, $wh_id, $itemid){
				
				if($month=='01'){
					$prev_month = 12;
					$prev_year = $year-1;
				}else{
					$prev_month = $month-1;
					$prev_year = $year;								
				}
					
				//	1
				//	echo $wh_id;
			
				$query7 = "SELECT COUNT(DISTINCT(report_month)) FROM tbl_wh_data WHERE wh_id='$wh_id'";
				$rs7 = mysql_query($query7) or die(mysql_error());
				$row = mysql_fetch_array($rs7);
				$row7 = $row[0];
				
				if($row7==0){
					$sum = 0;
					return $sum.'-'.$sum; 
				}
				
				else if($row7==1){
					
					$counter = 0;
					$sum = 0;
					
					//while($counter != $row7){
					  $query1 = "SELECT fld_issue_up FROM tbl_wh_data WHERE report_month='$prev_month' and report_year='$prev_year' and wh_id='$wh_id' and item_id='$itemid'";
					  $rs1 = mysql_query($query1) or die(mysql_error());
					  $num1 = mysql_num_rows($rs1);

					  if($num1>0){ 	
					  
					  	$row1 = mysql_fetch_array($rs1);

						if($row1['fld_issue_up'] !=0){
						
							$sum = $sum+$row1['fld_issue_up'];
							$counter = $counter+1;
							$prev_month = $prev_month-1;	
							
						} else {
						
							$prev_month = $prev_month-1;		
							
						}
						
					  }	
					//}
					return $row7.'-'.$sum;
					
				} 
				
				else {	
				
					$counter = 0;
					$loopcounter = 0;
					$sum = 0;
					
					for($i=0;$i<6;$i++){
					
						$query1 = "SELECT fld_issue_up FROM tbl_wh_data WHERE report_month='$prev_month' and report_year='$prev_year' and wh_id='$wh_id' and item_id='$itemid'";
						$rs1 = mysql_query($query1) or die(mysql_error());
						$row1 = mysql_fetch_array($rs1);

						if($row1['fld_issue_up'] !=0 && $counter!=2){
							
							$sum = $sum+$row1['fld_issue_up'];
							$counter++;
							$prev_month = $prev_month-1;	
							
						} else {
							 $prev_month = $prev_month-1;		
						}
					
					}
					
					
				return '2-'.$sum;
				}
				
			} 
			
			
			if($_REQUEST['action']=="Update121")
			{
				echo  '<div id="errMsg" align="center" style="color:#060">Update Reports Successfully.</div>';	
			}
			echo "<br>";
			
			
			
			
			
			
			
			///////////**************** //////////////////////////////////////////////////////////////////////////
			
			
			
			
			
			
			
			
			//showBreadCrumb();
			
			
			
			
			
			echo "<br>";
			echo "<br>";
			if(empty($_POST['wh_id'])) { 
			?>
			
<div style="float:right; padding-right:92px"><?php //echo readMeLinks($readMeTitle);?></div><br /><br />
            <!--Search Form-->
            <?php /*?>The following code wibb be executed for searching the reports <?php */?>
			<FORM NAME="frmF7" ACTION="AddEditF7.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" onSubmit="return ContinueValidate();">
            <TABLE  CELLPADDING="5"  CELLSPACING="0" BORDER="1" CLASS="TableArea" BORDERCOLOR="#000000" STYLE="overflow:hidden; " width="769">			
			
            <TR>
				<TD COLSPAN="12" valign="middle" align="center" CLASS="sb1GreenInfoBoxLabel">SEARCH MONTHLY ISSUE, DISTRIBUTION AND STOCK BALANCE REPORT</TD>
			</TR>
            
            
			<TR>
				<TD CLASS="TDLCOLLAB" NOWRAP><span class="sb1SmallerFont" title="Faculty Name">Facility Name</span> <A CLASS="sb1Exception">*</A><?php echo $DecLogedUser ?></TD>
				<TD CLASS="TDRCOLLAN"  title="Select Faculty Name">
				
                	<SELECT NAME="wh_id" id="wh_id" CLASS="sb1GeenGradientBoxMiddle" TABINDEX="1">
						<OPTION VALUE="">--- Select ---</OPTION>
						<?
							for($i=0;$i<sizeof($WHRecArray);$i++)
								{
								if($WHRecArray[$i]==$cws1)
								{
								$chk = "Selected = 'Selected'";	
								}
								else
								{
								$chk = "";	
								}
									echo"<OPTION VALUE=\"$WHRecArray[$i]\" $chk>$WHNameArray[$i]</OPTION>";
								}
						?>
					</SELECT>				</TD>				
				<TD CLASS="TDLCOLLAB" NOWRAP><span class="sb1SmallerFont" title="Year">Year</span> <A CLASS="sb1Exception">*</A></TD>
				<TD CLASS="TDRCOLLAN"  title="Select Year"><select name="report_year" id="report_year" class="sb1GeenGradientBoxMiddle" tabindex="2">
                  <?php
                    		$q = "SELECT report_month,report_year FROM tbl_wh_data WHERE wh_id=".base64_decode($_SESSION['user']['LogedUserWH'])." ORDER BY w_id DESC";
							$r = mysql_query($q) or die(mysql_error());
							$rs = mysql_fetch_array($r);
							
							if($rs['report_month']=='12')
							{
							$mymonth = '01';	
							$myyear = $rs['report_year']+1;	
								
							}
							
							else
							
							{
							$mymonth = $rs['report_month']+1;	
							$myyear = $rs['report_year'];		
								
							}
							
							
							//$WHNameArray[]=$rs['wh_name'];
                            ?>
                  <option value="">Year</option>
                  <? 
							$EndYear=2002;
							$StartYear=date('Y')+2;													
							for($i=$StartYear;$i>=$EndYear;$i--) 
								{
									
										if($myyear==$i)
								{
								$chk4 = "Selected = 'Selected'";	
								}
								else
								{
								$chk4 = "";	
								}
								
									echo"<OPTION VALUE='$i' $chk4>$i</OPTION>";
								}
							
								
						?>
                </select></TD>
				<TD CLASS="TDLCOLLAB" NOWRAP><span class="sb1SmallerFont" title="Month">Month</span> <A CLASS="sb1Exception">*</A></TD>
				<TD CLASS="TDRCOLLAN"  title="Select Month ">
					<SELECT NAME="report_month" id="report_month" CLASS="sb1GeenGradientBoxMiddle" TABINDEX="3">
                
						<OPTION VALUE="">Month </OPTION>
						<OPTION VALUE="01" <?php 
						if($mymonth=='01') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?> >JANUARY</OPTION>	
						<OPTION VALUE="02" <?php 
						if($mymonth=='02') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>FEBRUARY</OPTION>
						<OPTION VALUE="03" <?php 
						if($mymonth=='03') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>MARCH</OPTION>
						<OPTION VALUE="04" <?php 
						if($mymonth=='04') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>APRIL</OPTION>	
						<OPTION VALUE="05" <?php 
						if($mymonth=='05') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>MAY</OPTION>
						<OPTION VALUE="06" <?php 
						if($mymonth=='06') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>JUN</OPTION>
						<OPTION VALUE="07" <?php 
						if($mymonth=='07') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>JULY</OPTION>
						<OPTION VALUE="08" <?php 
						if($mymonth=='08') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>AUGUST</OPTION>
						<OPTION VALUE="09" <?php 
						if($mymonth=='09') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>SEPTEMBER</OPTION>
						<OPTION VALUE="10" <?php 
						if($mymonth=='10') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>OCTOBER</OPTION>	
						<OPTION VALUE="11" <?php 
						if($mymonth=='11') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>NOVEMBER</OPTION>	
						<OPTION VALUE="12" <?php 
						if($mymonth=='12') 
						{
						echo $chk2 = "Selected = 'Selected'";	
								}
								 ?>>DECEMBER</OPTION>
					</SELECT>				</TD>				
			</TR>
			<TR>
			  <TD COLSPAN="12" ALIGN="CENTER" CLASS="TableHead">
                <INPUT TYPE="IMAGE" SRC="<?php echo PLMIS_IMG; ?>CmdShow.gif" TABINDEX="5" TITLE="Click Here For Show The F7 Report Entry Form" onClick="">
                <IMG SRC="<?php echo PLMIS_IMG; ?>CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmF7.reset()" CLASS="Himg" TABINDEX="6" style=" cursor:pointer" title="Reset all values">
                <INPUT TYPE="HIDDEN" NAME="ActionType" VALUE="Filter">
                <INPUT TYPE="HIDDEN" NAME="PrvRecordID" VALUE="">		        <a href="AddEditF7.php"></a></TD>
			</TR>			
		</TABLE>
        	<INPUT TYPE="HIDDEN" NAME="LogedUser" VALUE="<?php echo $LogedUser;?>">
			<INPUT TYPE="HIDDEN" NAME="LogedID" VALUE="<?php echo $LogedID;?>">
			<INPUT TYPE="HIDDEN" NAME="LogedUserWH" VALUE="<?php echo $LogedUserWH;?>">
			<INPUT TYPE="HIDDEN" NAME="LogedUserType" VALUE="<?php echo $LogedUserType;?>">		
			<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">		
			</SCRIPT>
            <input type="hidden" name="mystake" id="mystake" />
            <input type="hidden" name="Stake" id="Stake" value="<?php if($Stk !='') { echo $Stk; } else { echo $_GET['stakeHolder']; } ?>">
	        <input type="hidden" name="sysusr_type" id="sysusr_type" value="<?php echo $_GET['sysusr_type']; ?>">		         
            <input type="hidden" name="cws1" id="hiddenField2" value="<?php echo $_GET['cws1']; ?>">
		</FORM>
    <?php 
			}
			if(isset($_POST['ActionType'])){
				
				if($_POST['ActionType']=='Filter') {
					if(($_POST['Stake']==4 || $_POST['Stake']==5 || $_POST['Stake']==6) && ($_POST['sysusr_type']=='UT-001'))
						$whid = $_POST['cws1'];
					else
						$whid = $_POST['wh_id'];
					
					//$whid = base64_decode($_SESSION['user']['LogedUserWH']);
						
					$ChkRecord = "SELECT w_id FROM tbl_wh_data WHERE report_month=".$_POST['report_month']." AND report_year=".$_POST['report_year']." AND wh_id=".$whid."";
					$rChkRecord = mysql_query($ChkRecord) or die(mysql_error());
					$numChkRecord = mysql_num_rows($rChkRecord);
					
					if($numChkRecord>0) {
					
					$qStake = "SELECT stkid,wh_type_id FROM tbl_warehouse WHERE wh_id=".$whid;
					$rStake = mysql_query($qStake) or die(mysql_error());
					$rsStake = mysql_fetch_array($rStake);
					?>
        			<!--Record Found. Display Data-->
                     
					 
					 <?php /*?>The following code will be executed for displayijng Aggregated Monthly LMIS Report and Aggregated Monthly LMIS Report  */?>
					 
				
					 <FORM NAME="frmaddF7" ACTION="AddEditF7.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA">
						<TABLE CELLPADDING="5" CELLSPACING="0"  BORDER="1" CLASS="TableArea"  BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE;padding-left:8%;" width="769">			
                        <TR class="report_header_border">
                          <TD align="center" valign="middle" CLASS="sb1ColumnHeader">
                          <?php if($rsStake['stkid']==4 || $rsStake['stkid']==5 || $rsStake['stkid']==6) {?>
	                          	Private Sector
                          <?php } else { ?>
    	                      	Government Of Pakistan
                          <?php } ?>
                          </TD>
                        </TR>
                        <TR>
                            <TD align="center" valign="middle" CLASS="sb1GreenInfoBoxLabel">
                            <?php if($rsStake['stkid']==4 || $rsStake['stkid']==5 || $rsStake['stkid']==6) {?>
							<strong><?php echo ChangeNameByID($rsStake['stkid']); ?></strong> Aggregated Monthly LMIS Report
                            <?php } else { ?>
							<strong><?php echo ChangeNameByID($rsStake['stkid']); ?></strong> District Aggregated Monthly LMIS Report                            
                            <?php } ?></TD>
                        </TR>		
                        <TR>
                          <TD>
							<TABLE CELLPADDING="0" CELLSPACING="0" WIDTH="100%" BORDER="0" ALIGN="LEFT" CLASS="TableAreaSmall">
								<?php $Stk = $_REQUEST['Stake']; ?>							
								<TR class="sb1GreenInfoBoxMiddleText">		
									<TD width="11%" ALIGN=LEFT>Period: <strong><?php echo date("F",mktime(0,0,0,$_REQUEST['report_month']));?>, <?php echo $_REQUEST['report_year'];?></strong><INPUT TYPE="HIDDEN" NAME="Month" VALUE="<?php echo $Month;?>"><INPUT TYPE="HIDDEN" NAME="Year" VALUE="<?php echo $_POST['GridYear'];?>"></TD>
									<TD width="18%" ALIGN=LEFT><span class="sb1SmallerFont">Stakeholder :</span><strong><?php if($rsStake['stkid']==3 && GetProNameByID($_POST['wh_id'])>4){echo 'Directorate of Health';} else {echo ChangeNameByID($rsStake['stkid']);} ?> </strong><span class="sb1Exception"><?php echo GetProNameByID2(GetProNameByID($_POST['wh_id'])); ?></span></TD>
								</TR>
							</TABLE>
						</TD>
					</TR>		
	                    <?php if($rsStake['wh_type_id']!='Private Sector') { ?>
                        <!--District Table-->
                        <TR>
						<TD><TABLE CELLPADDING="2" CELLSPACING="0" WIDTH="100%" BORDER="1" ALIGN="LEFT" CLASS="TableAreaSmall" BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE">
						  <?php if($_REQUEST['sysusr_type']=='UT-002'){ 
						  			$f7colspan = 12;
								}else {
									$f7colspan = 11;
								}
						  ?>
                          <TR>
						    <TD width="19" ROWSPAN="4" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">Sl.<BR>
						      No</TD>
						    <TD CLASS="sb1GeenGradientBoxMiddle" ROWSPAN="4" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">ARTICLE</TD>
						    <TD COLSPAN="<?php echo $f7colspan;?>" align="center" valign="middle" CLASS="TableHead">
							<span class="sb1GeenGradientBoxMiddle">
							<?php if($rsStake['wh_type_id']=='CWH'){ 
									echo 'Central Warehouse';
								  } 
								  else if($rsStake['wh_type_id']=='PPIU'){
									echo 'PPIU'; 
								  }	
								  else {
									 echo strtoupper(getStakeHolderName($_POST['wh_id']));?> District</span>							<?php } ?>
                            </TD>
					      </TR>
						  <TR>
						    <TD COLSPAN="<?php echo $f7colspan;?>" align="center" valign="middle" CLASS="sb1GreenInfoBoxMiddle">USABLE ARTICLES</TD>
					      </TR>
						  <TR>
						    <TD CLASS="F7BCOLCAH" align="left" COLSPAN="2" TITLE="This Is The Opening Balance Of The Month. i.e. The Closing balance Of The Previous Month."><B class="sb1FormLabel">Opening balance</B></TD>
						    <TD width="62" ROWSPAN="2" align="center" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Received Items From The CWH In This Month."><B class="sb1FormLabel">Received</B></TD>
						    <!--<TD CLASS="F7BCOLCAH" ROWSPAN="2" TITLE="This Is The Quantity Of The Items Returned In This Month."><B class="sb1FormLabel">Returned from <BR>
						      SDPs/DWH</B></TD>-->
						    <TD  rowspan="2" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Issued Items In This Month."><B class="sb1FormLabel">Issued</B></TD>
						    <TD colspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="">Adjustments</TD>
						    <TD CLASS="sb1GeenGradientBoxMiddle" COLSPAN="2" align="center" TITLE="This Is The Closing Balance Stock For This Month."><B class="sb1FormLabel">Closing Balance</B></TD>
						   <?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
                            <TD width="23" rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Stock of Month.">MOS</TD>
                            <?php } ?>
					      </TR>
						  <TR>
						    <TD width="66" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Stock Opening Balance, Which Is The Previous Month Stock Closing Balance And Automatically Showing Form The Database. This Field Value Are Not Editable.">Calculated</TD>
						    <TD width="42" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Actual store/warehouse Stock Opening Balance, Which Will Be Entered From The warehouse Report Data.">Actual</TD>
						    <TD width="53" align="center" CLASS="F7BCOLCAH" TITLE="This is Sum of Adjustments That Results  Increase In Stock."><b class="sb1FormLabel">(+)</b></TD>
						    <TD width="53" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This is Sum of Adjustments That Results  Decrease In Stock."><b class="sb1FormLabel">(-)</b></TD>
						    <TD width="73" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is the Store/Warehouse Stock Opening Balance, Which Is The Previous Month Store/Warehouse Stock Closing Balance and Automatically Showing From The Database. This Fields Value Are Not Editable.">Calculated</TD>
						    <TD width="77" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Actual Store/Warehouse Stock Opening Balance, Which Will Be Entered From The Monthly Report Data.">Actual</TD>
					      </TR>
						  <TR class="sb1GreenInfoBoxMiddleText">
						    <TD CLASS="TDLCOLCABSMALL">1</TD>
						    <TD CLASS="TDLCOLCABSMALL">2</TD>
						    <TD CLASS="TDLCOLCABSMALL">3(C)</TD>
						    <TD CLASS="TDLCOLCABSMALL">3(A)</TD>
						    <TD CLASS="TDLCOLCABSMALL">4</TD>
						    <!--<TD CLASS="TDLCOLCABSMALL">5</TD>-->
						    <TD CLASS="TDLCOLCABSMALL" >6</TD>
						    <TD CLASS="TDLCOLCABSMALL">7</TD>
						    <TD CLASS="TDLCOLCABSMALL">8</TD>
						    <TD CLASS="TDLCOLCABSMALL">9(C)</TD>
						    <TD CLASS="TDLCOLCABSMALL">9(A)</TD>
							<?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
                            <td CLASS="TDLCOLCABSMALL"></td>
                            <?php } ?>
					      </TR>
						  <?php					
							if($_POST['report_month']=='01'){
								$prev_month = 12;
								$prev_year = $_POST['report_year']-1;
							}else{
								$prev_month = $_POST['report_month']-1;
								$prev_year = $_POST['report_year'];								
							}
							$rsTemp1=safe_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`='Current' AND `itm_id` IN (SELECT `stk_item` FROM `stakeholder_item` WHERE `stkid` ='".$rsStake['stkid']."') ORDER BY `frmindex`");
							$SlNo=1;
							while($rsRow1=mysql_fetch_array($rsTemp1))
								{										
									$SlNo=((strlen($SlNo)<2) ? "0".$SlNo : $SlNo);
									$rsTemp2=safe_query("SELECT * FROM tbl_wh_data WHERE `wh_id`='".$_POST['wh_id']."' AND `report_month`='".$_POST['report_month']."' AND report_year='".$_POST['report_year']."' AND `item_id`='".$rsRow1['itmrec_id']."'");								
									$rsRow2=mysql_fetch_array($rsTemp2);
									//echo "SELECT * FROM tbl_wh_data WHERE `wh_id`='".$_POST['wh_id']."' AND `report_month`='".$_POST['report_month']."' AND report_year='".$_POST['report_year']."' AND `item_id`='".$rsRow1['itmrec_id']."'";									
						  ?>
                          
                      
						  <TR>
						    <TD CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
						    <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span></TD>
						    <TD CLASS="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_obl_c']);?></TD>
                            <TD CLASS="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_obl_a']);?></TD>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_received']);?></TD>
						    <TD width="13" CLASS="sb1NormalFontArial" ><?php echo number_format($rsRow2['wh_issue_up']);?></TD>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_adja']);?></TD>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_adjb']);?></TD>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_cbl_c']);?></TD>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['wh_cbl_a']);?></TD>
                            <?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
						    <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['mos'],1);?></TD>
                            <?php } ?>
                          </TR>
						    <?php		
								$SlNo++;
							}
							mysql_free_result($rsTemp1);
							?>
				    </TABLE></TD>
                </TR>
               			<?php }
                		 if($rsStake['wh_type_id']!='CWH' && $rsStake['wh_type_id']!='PPIU') { ?>
                        <!--Field Table-->
                        <TR>
                            <TD><TABLE CELLPADDING="2" CELLSPACING="0" WIDTH="100%" BORDER="1" ALIGN="LEFT" CLASS="TableAreaSmall" BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE">
                              <TR>
                                <TD width="19" ROWSPAN="4" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">Sl.<BR>
                                  No</TD>
                                <TD CLASS="sb1GeenGradientBoxMiddle" ROWSPAN="4" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">ARTICLE</TD>
                                <TD COLSPAN="12" align="center" valign="middle" CLASS="TableHead"><PRE class="sb1RowDataEven"><strong>Field Data</strong></PRE></TD>
                              </TR>
                              <TR>
                                <TD COLSPAN="12" align="center" valign="middle" CLASS="sb1GreenInfoBoxMiddle">USABLE ARTICLES</TD>
                              </TR>
                              <TR>
                                <TD CLASS="F7BCOLCAH" align="center" COLSPAN="2" TITLE="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month."><B class="sb1FormLabel">Opening balance</B></TD>
                                <TD width="62" ROWSPAN="2" align="center" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Received Items From The CWH In This Month."><B class="sb1FormLabel">Received</B></TD>
                                <!--<TD CLASS="F7BCOLCAH" ROWSPAN="2" TITLE="This Is The Quantity Of The Items Returned From The Thana/RWH In This Month."><B class="sb1FormLabel">Returned from <BR>
                                  SDPs/DWH</B></TD>-->
                                <TD COLSPAN="3" rowspan="2" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Issued Items In This Month."><B class="sb1FormLabel">Issued</B></TD>
                                <TD colspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Quantity Of The Items Returned To CWH In This Month.">Adjustments</TD>
                                <TD CLASS="sb1GeenGradientBoxMiddle" COLSPAN="2" align="center" TITLE="This Is The Closing Balance Of The Stock For This Month."><B class="sb1FormLabel">Closing Balance</B></TD>
                                <TD width="23" rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Stock of Month.">MOS</TD>
                              </TR>
                              <TR>
                                <TD width="66" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Stock Opening Balance, Which Is The Previous Month Stock Closing Balance And Automatically Showing Form The Database. This Field Value Are Not Editable.">Calculated</TD>
                                <TD width="42" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Actual store/warehouse Stock Opening Balance, Which Will Be Entered From The warehouse Report Data.">Actual</TD>
                                <TD width="53" align="center" CLASS="F7BCOLCAH" TITLE="This is Sum of Adjustments That Results  Increase In Stock."><b class="sb1FormLabel">(+)</b></TD>
                                <TD width="53" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This is Sum of Adjustments That Results  Decrease In Stock."><b class="sb1FormLabel">(-)</b></TD>
                                <TD width="73" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is the Store/Warehouse Stock Opening Balance, Which Is The Previous Month Store/Warehouse Stock Closing Balance and Automatically Showing From The Database. This Fields Value Are Not Editable.">Calculated</TD>
                                <TD width="77" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Actual Store/Warehouse Stock Opening Balance, Which Will Be Entered From The Monthly Report Data.">Actual</TD>
                              </TR>
                              <TR class="sb1GreenInfoBoxMiddleText">
                                <TD CLASS="TDLCOLCABSMALL">1</TD>
                                <TD CLASS="TDLCOLCABSMALL">2</TD>
                                <TD CLASS="TDLCOLCABSMALL">3(C)</TD>
                                <TD CLASS="TDLCOLCABSMALL">3(A)</TD>
                                <TD CLASS="TDLCOLCABSMALL">4</TD>
                                <!--<TD CLASS="TDLCOLCABSMALL">5</TD>-->
                                <TD CLASS="TDLCOLCABSMALL" COLSPAN="3">6</TD>
                                <TD CLASS="TDLCOLCABSMALL">7</TD>
                                <TD CLASS="TDLCOLCABSMALL">8</TD>
                                <TD CLASS="TDLCOLCABSMALL">9(C)</TD>
                                <TD colspan="2" CLASS="TDLCOLCABSMALL">9(A)</TD>
                              </TR>
                              <?php					
                                if($_POST['report_month']=='01'){
                                    $prev_month = 12;
                                    $prev_year = $_POST['report_year']-1;
                                }else{
                                    $prev_month = $_POST['report_month']-1;
                                    $prev_year = $_POST['report_year'];								
                                }
                                $rsTemp1=safe_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`='Current' AND `itm_id` IN (SELECT `Stk_item` FROM `stakeholder_item` WHERE `stkid` ='".$rsStake['stkid']."') ORDER BY `frmindex`");
                                $SlNo=1;
                                while($rsRow1=mysql_fetch_array($rsTemp1))
                                    {										
                                        $SlNo=((strlen($SlNo)<2) ? "0".$SlNo : $SlNo);
                                       // $rsprev=safe_query("SELECT fld_obl_c FROM tbl_wh_data WHERE `wh_id`='".$_POST['wh_id']."' AND `report_month`='$prev_month' AND report_year='$prev_year' AND `item_id`='$rsRow1[itmrec_id]'");								
                                       // $rowprev=mysql_fetch_array($rsprev);
                                        $rsTemp2=safe_query("SELECT * FROM tbl_wh_data WHERE `wh_id`='".$_POST['wh_id']."' AND `report_month`='".$_POST['report_month']."' AND report_year='".$_POST['report_year']."' AND `item_id`='$rsRow1[itmrec_id]'");								
                                        $rsRow2=mysql_fetch_array($rsTemp2);
                              ?>
                              
                          
                              <TR>
                                <TD CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
                                <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span></TD>
                                <TD CLASS="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_obl_c']);?></TD>
                                <TD CLASS="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_obl_a']);?></TD>
                                <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_recieved']);?></TD>
                                <TD width="13" CLASS="sb1NormalFontArial" colspan="3"><?php echo number_format($rsRow2['fld_issue_up']);?></TD>                                
                                <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_adja']);?></TD>
                                <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_adjb']);?></TD>
                                <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_cbl_c']);?></TD>
                                <TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_cbl_a']);?></TD>
								<TD class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_mos'],1);?></TD>
							  </TR>
                                <?php		
                                    $SlNo++;
                                }
                                mysql_free_result($rsTemp1);
                                ?>
                       			 </TABLE></TD>
              			  </TR>
						<?php } ?>
                        <TR>
                          <TD CLASS="TableHead"><INPUT TYPE="image" SRC="<?php echo PLMIS_IMG; ?>CmdUpdate.gif">
                           <a href="AddEditF7.php"><img src="../../plmis_img/cancel.gif" width="83" height="21" border="0" alt="Reset" onclick="document.frmData.reset()" class="Himg" tabindex="18" style="cursor:pointer" title="Reset all values" /></a></TD>
                        </TR>
                        
						</TABLE>
                        <INPUT TYPE="hidden" NAME="ActionType" VALUE="Edit">
                        <input type="hidden" name="Stake" id="Stake" value="<?php echo $rsStake['stkid'];?>">
                        <input type="hidden" name="report_month" id="report_month" value="<?php echo $_REQUEST['report_month'];?>">
                        <input type="hidden" name="report_year" id="report_year" value="<?php echo $_REQUEST['report_year'];?>">
                        <input type="hidden" name="wh_id" id="wh_id" value="<?php echo $_POST['wh_id'];?>">  
                        <input type="hidden" name="cws1" id="cws1" value="<?php echo $_REQUEST['cws1'];?>">
                        <input type="hidden" name="sysusr_type" id="sysusr_type" value="<?php echo $_REQUEST['sysusr_type'];?>">
	</FORM>		
		<?php		} else {	?>
        			<!--Add District Data-->
					
					
					 <?php /*?>The following code will be executed for adding Aggregated Monthly LMIS Report and Aggregated Monthly LMIS Report  */?>
					<?php
							$qStake = "SELECT stkid,wh_type_id FROM tbl_warehouse WHERE wh_id=".$whid;
							$rStake = mysql_query($qStake) or die(mysql_error());
							$rsStake = mysql_fetch_array($rStake);
						?>
                    <FORM NAME="frmaddF7" ACTION="AddEditF7.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" onSubmit="if(event.keyCode == 13){return false;}">
                    </FORM>
        <?php		
					}
				}
			if($_POST['ActionType']=='Edit') {
			/*echo $_POST['Stake'];
				echo "<br>";
			echo $_POST['sysusr_type'];
				echo "<br>";
			echo "wh_id=".$_POST['wh_id'];
				echo "<br>";
			echo "cws1=".$_POST['cws1'];
				echo "<br>";
				*/
			if(($_POST['Stake']==4 || $_POST['Stake']==5 || $_POST['Stake']==6) && ($_POST['sysusr_type']=='UT-001'))
				$whid = $_POST['cws1'];
			else
				$whid = $_POST['wh_id'];
			
			$qStake = "SELECT stkid,wh_type_id FROM tbl_warehouse WHERE wh_id=".$whid;
			$rStake = mysql_query($qStake) or die(mysql_error());
			$rsStake = mysql_fetch_array($rStake);
		 ?>
			<!--Update District Data-->
	     
		  <?php /*?>The following code will be executed for  MONTHLY ISSUE, DISTRIBUTION AND STOCK BALANCE REPORT*/?>
		    <FORM NAME="frmaddF7" ACTION="AddEditF7.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA">
		    </FORM>
		<?php	
			}
		}
		?>
		</div>
		</div>
<?php 
///////////**************** /////////////////////////////////////////////////////////////////////////////////////////


//footer();   ?>
<?php


///////////**************** ////////////////////////////////////////////////////////////////////////////////////////

 //endHtml();?>