<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid']))
{
	$userid=$_SESSION['userid'];
	$objwharehouse_user->m_npkId=$userid;
	$result=$objwharehouse_user->GetwhuserByIdc();
}
else
echo "user not login or timeout"
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Data Entry</title>
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
	document.frmF7.PrvRecordID.value=RowID
	document.frmF7.submit();
}

function Logout()
{
	window.parent.location="../Logout.php?Logid="+document.frmF7.LogedID.value
}

function roundNumber(num, dec)
{
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
function formReset()
{
document.getElementById("AddEditF7").reset();
}

function cal_balance(itemId)
{
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
	if(document.getElementById('FLDmyavg'+itemId))
	{
		var myavg = document.getElementById('FLDmyavg'+itemId).value;
	}
	else {
		var myavg = document.getElementById('myavg'+itemId).value;
	}
	var mycalavg = myavg.split('-');
	if(document.getElementById('FLDIsuueUP'+itemId))
		var divisible = eval(mycalavg[1]+FLDIsuueUP);
	else
	var divisible = eval(mycalavg[1]+IsuueUP);
	var divider = eval(mycalavg[0]+1);
	if(eval(divider)>0)
	{
		var myactualavg = eval(divisible)/eval(divider);
	}
	else {
		var myactualavg = eval(divisible)/1;
	}
	if(document.getElementById('WHCBLC'+itemId))
		document.getElementById('WHCBLC'+itemId).value = (wholbc+WHRecv+ReturnTo)-(IsuueUP+Unusable);
	if(document.getElementById('WHCBLA'+itemId))	
	document.getElementById('WHCBLA'+itemId).value = (wholba+WHRecv+ReturnTo)-(IsuueUP+Unusable);
	if(document.getElementById('MOS'+itemId) && document.getElementById('WHCBLA'+itemId))
	{
		if(eval(myactualavg)>0)
		{
			document.getElementById('MOS'+itemId).value = roundNumber(eval(document.getElementById('WHCBLA'+itemId).value)/eval(myactualavg),1);
		}
		else {
			document.getElementById('MOS'+itemId).value = roundNumber(eval(document.getElementById('WHCBLA'+itemId).value)/1,1);
		}
	}
	if(document.getElementById('FLDCBLC'+itemId))
		document.getElementById('FLDCBLC'+itemId).value = (fldolbc+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
	if(document.getElementById('FLDCBLA'+itemId))	
	document.getElementById('FLDCBLA'+itemId).value = (fldolba+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
	if(document.getElementById('FLDMOS'+itemId) && document.getElementById('FLDCBLA'+itemId))
	{
		if(eval(myactualavg)>0)
		{
			document.getElementById('FLDMOS'+itemId).value = roundNumber(eval(document.getElementById('FLDCBLA'+itemId).value)/eval(myactualavg),1);
		}
		else {
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

function formvalidate()
{
	var arrlength = document.frmaddF7.itmrec_id.length;
	var firstrec = document.frmaddF7.itmrec_id[0].value;
	var firstval = firstrec.split('-');
	var atLeastOneEntry = false;
	var fldAtLeastOneEntry = false;
	var fldExists = false;
	for(var i=0; i<arrlength; i++)
	{
		var fieldval = document.frmaddF7.itmrec_id[i].value;
		fieldconcat = fieldval.split('-');
		var whobla = 'WHOBLA'+fieldconcat[1];
		var whrecv = 'WHRecv'+fieldconcat[1];
		var whissue = 'IsuueUP'+fieldconcat[1];
		var fldobla = 'FLDOBLA'+fieldconcat[1];
		var fldrecv = 'FLDRecv'+fieldconcat[1];
		var fldissue = 'FLDIsuueUP'+fieldconcat[1];
		if((document.getElementById(whobla).value!=0 || document.getElementById(whobla).value!=''))
		{
			if(document.getElementById(whrecv).value==0 || document.getElementById(whrecv).value=='')
			{
				alert('Please Enter Received');
				document.getElementById(whrecv).focus();
				return false;
			}
			if(document.getElementById(whissue).value==0 || document.getElementById(whissue).value=='')
			{
				alert('Please Enter Received');
				document.getElementById(whissue).focus();
				return false;
			}
		}
		if((document.getElementById(whobla).value!=0 || document.getElementById(whobla).value!=''))
		{
			atLeastOneEntry = true;		
			break;
		}
	}
	if(atLeastOneEntry == false)
	{
		alert('Please Enter Atleast one Entry');
		document.getElementById('WHOBLA'+firstval[1]).focus();
		return false;
	}
	}	
</SCRIPT>

</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<table width="1000px" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
    <td>
	<?php //include("header.php");
		include(SITE_PATH."plmis_inc/common/top.php");
	?>
	</td>
</tr>
<tr>
    <td valign="top">
		<table width="100%" height="146" valign="top">
            <tr>
                <td width="20%" valign="top" align="left"> <h1>Welcome:
                    <? 
if($result!=FALSE && mysql_num_rows($result)>0)
{
	$row = mysql_fetch_array($result);
	echo $row['usrlogin_id'];
}
?></h1>
                    <table valign="top" align="left" style="margin-top:5px;">
                       <!-- <tr>
                            <td width="88">Store</td>
                            <td width="111"></td>
                        </tr>-->
                        <tr>
                        	<td>Warehouse</td>
                            <td>
                            	<select name="wharehouse_id" id="wharehouse_id">
                                    <option value="">Select Warehouse</option>
                                    <?
									//retrieving warehouse if
									$temp=$_REQUEST['Do'];
		
									$temp=base64_decode(substr($temp,1,strlen($temp)-1));
		                            $temp=explode("|",$temp);
									$wh_id=$temp[0]; // Warehouse ID
									
									$objwharehouse_user->m_npkId=$userid;
									$result1=$objwharehouse_user->GetwhuserByIdc();
									if($result1!=FALSE && mysql_num_rows($result1)>0)
									{
										while($row = mysql_fetch_array($result1))
										{
										?>
                                    <option <?php if($row['wh_id']==$wh_id)echo 'selected="selected"'; ?> value="<?php echo $row['wh_id'];?>"><?php echo $row['wh_name'];?></option>
                                    <?php
										}
									}
										
										?>
                                </select>
                            </td>
                            <td id="showMonths">
                            	
                            </td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td width="80%" bgcolor="#E1E1E1" valign="top" id="showGrid">
	<? 
	$wh_id="";
	if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
	{
		$temp=$_REQUEST['Do'];
		
		$temp=base64_decode(substr($temp,1,strlen($temp)-1));
		$temp=explode("|",$temp);
		
		//****************************************************************************
		$wh_id=$temp[0]; // Warehouse ID
		$RptDate=$temp[1]; //Report Date
		$isNewRpt=$temp[2]; //if value=1 then new report
		$tt=explode("-",$RptDate); 
		$yy=$tt[0]; //Reprot year
		$mm=$tt[1];		//report Month
		
		if($mm=='1') $month="Jan";
		if($mm=='2') $month="Feb";
		if($mm=='3') $month="Mar";
		if($mm=='4') $month="Apr";
		if($mm=='5') $month="May";
		if($mm=='6') $month="Jun";
		if($mm=='7') $month="Jul";
		if($mm=='8') $month="Aug";
		if($mm=='9') $month="Sep";
		if($mm=='10') $month="Oct";
		if($mm=='11') $month="Nov";
		if($mm=='12') $month="Dec";
		
		//****************************************************************************
		$objwarehouse->m_npkId=$wh_id;
		print "<b>Warehouse/ Store Name:</b> ".$objwarehouse->GetWarehouseNameById($wh_id);  //"[".$wh_id."]";
		$stkid=$objwarehouse->GetStkIDByWHId($wh_id);
		print "; ";
		print "<b>Monthly Report:</b> ".$month.'-'.$yy;  //"[".$wh_id."]";
		if ( $isNewRpt == 1 )
		{
			$PrevMonthDate=$objReports->GetPreviousMonthReportDate($RptDate);
		}
		else 
		{
			$PrevMonthDate=$RptDate;
		}
		//print "Last Month".$PrevMonthDate;
	?>
                    <FORM NAME="frmF7" ACTION="data_entry_action.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" onSubmit="return ContinueValidate();">
                        <TABLE CELLPADDING="2" CELLSPACING="0" WIDTH="100%" BORDER="1" ALIGN="LEFT" CLASS="TableAreaSmall" BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE">
                            <TR>
                                <TD width="19" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">S.NO</TD>
                                <TD CLASS="sb1GeenGradientBoxMiddle" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">ARTICLE</TD>
                                <TD CLASS="F7BCOLCAH" align="center" COLSPAN="2" TITLE="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month."><B class="sb1FormLabel">Opening balance</B></TD>
                                <TD width="62" ROWSPAN="2" align="center" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Received Items From The CWH In This Month."><B class="sb1FormLabel">Received</B></TD>
                                <TD COLSPAN="3" rowspan="2" CLASS="F7BCOLCAH" TITLE="This Is The Quantity Of The Issued Items In This Month."><B class="sb1FormLabel">Issued</B></TD>
                                <TD colspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="">Adjustments</TD>
                                <TD CLASS="sb1GeenGradientBoxMiddle" COLSPAN="2" align="center" TITLE="This Is The Closing Balance Of The Stock For This Month."><B class="sb1FormLabel">Closing Balance</B></TD>
                            </TR>
                            <TR>
                                <TD width="19" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">&nbsp;</TD>
                                <TD CLASS="sb1GeenGradientBoxMiddle" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">&nbsp;</TD>
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
                                <TD CLASS="TDLCOLCABSMALL" COLSPAN="3">6</TD>
                                <TD CLASS="TDLCOLCABSMALL">7</TD>
                                <TD CLASS="TDLCOLCABSMALL">8</TD>
                                <TD CLASS="TDLCOLCABSMALL">9(C)</TD>
                                <TD CLASS="TDLCOLCABSMALL">9(A) </TD>
                            </TR>
                            <?php					
		$rsTemp1=mysql_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`='Current' AND `itm_id` IN (SELECT `Stk_item` FROM `stakeholder_item` WHERE `stkid` =$stkid) ORDER BY `frmindex`");
		$SlNo=1;
		$fldIndex=0;
		$ItemTableName=$TableName;
		while($rsRow1=mysql_fetch_array($rsTemp1))
		{
			$SlNo=((strlen($SlNo)<2) ? "0".$SlNo : $SlNo);
			$qry="SELECT * FROM tbl_wh_data WHERE `wh_id`='".$wh_id."' AND RptDate='".$PrevMonthDate."' AND `item_id`='$rsRow1[itmrec_id]'";
			//print $qry;
			$rsTemp3=mysql_query($qry);								

			$rsRow2=mysql_fetch_array($rsTemp3);
			
			// if new report
			if ( $isNewRpt == 1 )
			{
			?>
                            <TR>
                                <TD CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
                                <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                                    <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itmrec_id'];?>">
                                    <?php $itemid = explode('-',$rsRow1['itmrec_id']); ?></TD>
									
                                <TD CLASS="TDLCOLRASMALLW"><INPUT CLASS="sb1NormalFontArial" TYPE="TEXT" SIZE="8" NAME="FLDOBLC<?php echo $itemid[1];?>" ID="FLDOBLC<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['wh_cbl_c'];?>" readonly="1" style="background-color:#CCC;"></TD>
								
                                <TD><INPUT CLASS="sb1NormalFontArial" TYPE="TEXT" NAME="FLDOBLA<?php echo $itemid[1];?>" ID="FLDOBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_cbl_a'];?>" STYLE="BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
								
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $itemid[1];?>" ID="FLDRecv<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php //echo $rsRow2['wh_received'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="22" class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $itemid[1];?>" ID="FLDIsuueRWH<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="13" CLASS="sb1NormalFontArial"><INPUT NAME="FLDIsuueUP<?php echo $itemid[1];?>" ID="FLDIsuueUP<?php echo $itemid[1];?>" VALUE="<?php //echo $rsRow2['wh_issue_up'];?>" TYPE="TEXT" CLASS="TextBoxSmallRA" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'"  SIZE="8" MAXLENGTH="10" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="22" class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $itemid[1];?>" ID="FLDmyavg<?php echo $itemid[1];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itmrec_id']);?>" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $itemid[1];?>" ID="FLDReturnTo<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php //echo $rsRow2['wh_adja'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $itemid[1];?>" ID="FLDUnusable<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php //echo $rsRow2['wh_adjb'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRO" TYPE="TEXT" NAME="FLDCBLC<?php echo $itemid[1];?>" ID="FLDCBLC<?php echo $itemid[1];?>" SIZE="8" VALUE="<?php echo $rsRow2['wh_cbl_c'];?>" readonly="1" style="background-color:#CCC;"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $itemid[1];?>" ID="FLDCBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_cbl_a'];?>"  style="background-color:#CCC;" readonly="1">
                                    <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $itemid[1];?>" ID="FLDrecord_id<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['w_id'];?>"></TD>
                                <?php /*?> <?php if($_REQUEST['sysusr_type']=='UT-002')
			{
				?>
				<?php } ?><?php */?>
                            </TR>
                            <?php	
			}
			else // If oone of the 3 months 
			{
			?>
                            <TR>
                                <TD CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
                                <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                                    <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itmrec_id'];?>">
                                    <?php $itemid = explode('-',$rsRow1['itmrec_id']); ?></TD>
                                <TD CLASS="TDLCOLRASMALLW"><INPUT CLASS="sb1NormalFontArial" TYPE="TEXT" SIZE="8" NAME="FLDOBLC<?php echo $itemid[1];?>" ID="FLDOBLC<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['wh_obl_c'];?>" readonly="1" style="background-color:#CCC;"></TD>
                                <TD><INPUT CLASS="sb1NormalFontArial" TYPE="TEXT" NAME="FLDOBLA<?php echo $itemid[1];?>" ID="FLDOBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_obl_a'];?>" STYLE="BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $itemid[1];?>" ID="FLDRecv<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php echo $rsRow2['wh_received'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="22" class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $itemid[1];?>" ID="FLDIsuueRWH<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="13" CLASS="sb1NormalFontArial"><INPUT NAME="FLDIsuueUP<?php echo $itemid[1];?>" ID="FLDIsuueUP<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['wh_issue_up'];?>" TYPE="TEXT" CLASS="TextBoxSmallRA" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'"  SIZE="8" MAXLENGTH="10" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD width="22" class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $itemid[1];?>" ID="FLDmyavg<?php echo $itemid[1];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itmrec_id']);?>" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $itemid[1];?>" ID="FLDReturnTo<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_adja'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $itemid[1];?>" ID="FLDUnusable<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_adjb'];?>" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" ONCHANGE="cal_balance('<?php echo $itemid[1];?>');" onKeyPress="return numbersonly(this, event)"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRO" TYPE="TEXT" NAME="FLDCBLC<?php echo $itemid[1];?>" ID="FLDCBLC<?php echo $itemid[1];?>" SIZE="8" VALUE="<?php echo $rsRow2['wh_cbl_c'];?>" readonly="1" style="background-color:#CCC;"></TD>
                                <TD class="sb1NormalFontArial"><INPUT CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $itemid[1];?>" ID="FLDCBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_cbl_a'];?>"  style="background-color:#CCC;" readonly="1">
                                    <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $itemid[1];?>" ID="FLDrecord_id<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['w_id'];?>"></TD>
                                <?php /*?> <?php if($_REQUEST['sysusr_type']=='UT-002')
			{
				?>
				<?php } ?><?php */?>
                            </TR>
                            <?php 
            }
			
			?>
                            <?php		
			$SlNo++;
			$fldIndex=$fldIndex+13;
		}
		mysql_free_result($rsTemp1);
		?>
                            <TR>
                                <TD CLASS="sb1NormalFontArial">&nbsp;</TD>
                                <TD CLASS="TDLCOLLASMALL" NOWRAP></TD>
                                <TD CLASS="TDLCOLRASMALLW"><IMG SRC="<?php echo PLMIS_IMG; ?>CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmF7.reset()" CLASS="Himg" style=" cursor:pointer"></TD>
                                <TD><INPUT TYPE="image" SRC="<?php echo PLMIS_IMG; ?>CmdSave.gif">
                                    &nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD CLASS="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                                <TD class="sb1NormalFontArial">&nbsp;</TD>
                            </TR>
                        </TABLE>
                        <INPUT TYPE="hidden" NAME="ActionType" VALUE="Add">
                        <INPUT TYPE="HIDDEN" NAME="RptDate" VALUE="<?php echo $RptDate;?>">
                        <INPUT TYPE="HIDDEN" NAME="wh_id" VALUE="<?php echo $wh_id;?>">
                        <INPUT TYPE="HIDDEN" NAME="yy" VALUE="<?php echo $yy;?>">
                        <INPUT TYPE="HIDDEN" NAME="mm" VALUE="<?php echo $mm;?>">
                        <input type="hidden" name="mystake" id="mystake" />
                        <input type="hidden" name="Stake" id="Stake" value="<?php if($Stk !='')
		{
			echo $Stk;
		}
		else { echo $_GET['stakeHolder']; }	?>">
                        <input type="hidden" name="sysusr_type" id="sysusr_type" value="<?php echo $_GET['sysusr_type']; ?>">
                        <input type="hidden" name="cws1" id="hiddenField2" value="<?php echo $_GET['cws1']; ?>">
                        <input type="hidden" name="isNewRpt" id="isNewRpt" value="<?php echo $isNewRpt;?>" />
                    </FORM>
                    <? 
	}
	
	else
	{
		//echo "<H1><- Please select warehouse for report</H1>";
	}
	?></td>
            </tr>
        </table>
    </td>
</tr>
</table>    
	<script>
    $(function(){
		show3Months();
	});
    </script>
</body>
</html>