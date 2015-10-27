<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid']))
{
	$userid=$_SESSION['userid'];
	$objwharehouse_user->m_npkId=$userid;
	$result=$objwharehouse_user->GetwhuserByIdc();
    $result_province = $objwharehouse_user->GetProvinceIdByIdc();
}
else
echo "user not login or timeout";

if ( isset($_GET['e']) && $_GET['e'] == 'ok' )
{
	$wh_id = $_REQUEST['wh'];
	
	echo 'Your data has been successfully saved.';
?>
<script type="text/javascript" src="../plmis_js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
	$(function(){
		$.ajax({
			url: '../update_summary_tables_ajax.php',
			data: {whId: '<?php echo $wh_id;?>'}
		})
		delay(function(){
			window.close();
			RefreshParent();
			//window.onbeforeunload = RefreshParent;
		}, 3000 );
	})
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	function RefreshParent() {
		if (window.opener != null && !window.opener.closed) {
			window.opener.location.reload();
		}
	}
</script>
<?php
	exit;
}
$isReadOnly = '';
$style = '';
if( $_SESSION['im_open'] == 1 )
{
	$isReadOnly = 'readonly="readonly"';
	$style = 'style="background:#CCC"';
}
else
{
	$isReadOnly = '';
	$style = '';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
#hideit {
	display: none;
}
input[type="text"] {
	text-align: right;
}
</style>
<title>Data Entry</title>
<SCRIPT LANGUAGE="JAVASCRIPT" TYPE="TEXT/JAVASCRIPT">
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
		var wholbc = (document.getElementById('WHOBLC'+itemId).value=="")? 0 : parseInt(document.getElementById('WHOBLC'+itemId).value);
	else
	var wholbc = 0;
	if(document.getElementById('WHOBLA'+itemId))	
	var wholba = (document.getElementById('WHOBLA'+itemId).value=="")? 0 : parseInt(document.getElementById('WHOBLA'+itemId).value);
	else
	var wholba = 0;
	if(document.getElementById('WHRecv'+itemId))	
	var WHRecv = (document.getElementById('WHRecv'+itemId).value=="")? 0 : parseInt(document.getElementById('WHRecv'+itemId).value);
	else
	var WHRecv = 0;
	if(document.getElementById('IsuueUP'+itemId))
		var IsuueUP = (document.getElementById('IsuueUP'+itemId).value=="")? 0 : parseInt(document.getElementById('IsuueUP'+itemId).value);
	else
	var IsuueUP = 0;
	//WH adj+
	if(document.getElementById('ReturnTo'+itemId))
		var ReturnTo = (document.getElementById('ReturnTo'+itemId).value=="")? 0 : parseInt(document.getElementById('ReturnTo'+itemId).value);
	else
	var ReturnTo = 0; 
	//WH adj-
	if(document.getElementById('Unusable'+itemId))
		var Unusable = (document.getElementById('Unusable'+itemId).value=="")? 0 : parseInt(document.getElementById('Unusable'+itemId).value);
	else
	var Unusable = 0;							
	if(document.getElementById('FLDOBLC'+itemId))
		var fldolbc  = (document.getElementById('FLDOBLC'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDOBLC'+itemId).value);
	else
	var fldolbc  = 0;
	if(document.getElementById('FLDOBLA'+itemId))	 
	var fldolba  = (document.getElementById('FLDOBLA'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDOBLA'+itemId).value);
	else
	var fldolba  = 0;
	if(document.getElementById('FLDRecv'+itemId))	 	
	var FLDRecv  = (document.getElementById('FLDRecv'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDRecv'+itemId).value);
	else
	var FLDRecv  = 0;
	if(document.getElementById('FLDIsuueUP'+itemId))	
	var FLDIsuueUP = (document.getElementById('FLDIsuueUP'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDIsuueUP'+itemId).value);
	else
	var FLDIsuueUP = 0;							
	/*if(document.getElementById('FLDmyavg'+itemId))	
	var FLDmyavg = (document.getElementById('FLDmyavg'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDmyavg'+itemId).value);
	else
	var FLDmyavg = 0;*/ 							
	//Fld adj+
	if(document.getElementById('FLDReturnTo'+itemId))
		var FLDReturnTo = (document.getElementById('FLDReturnTo'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDReturnTo'+itemId).value);
	else
	var FLDReturnTo = 0;
	//Fld adj-
	if(document.getElementById('FLDUnusable'+itemId))
		var FLDUnusable = (document.getElementById('FLDUnusable'+itemId).value=="")? 0 : parseInt(document.getElementById('FLDUnusable'+itemId).value);
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
		var divisible = parseInt(mycalavg[1]+FLDIsuueUP);
	else
	var divisible = parseInt(mycalavg[1]+IsuueUP);
	var divider = parseInt(mycalavg[0]+1);
	if(parseInt(divider)>0)
	{
		var myactualavg = parseInt(divisible)/parseInt(divider);
	}
	else {
		var myactualavg = parseInt(divisible)/1;
	}
	if(document.getElementById('WHCBLC'+itemId))
		document.getElementById('WHCBLC'+itemId).value = (wholbc+WHRecv+ReturnTo)-(IsuueUP+Unusable);
	if(document.getElementById('WHCBLA'+itemId))	
	document.getElementById('WHCBLA'+itemId).value = (wholba+WHRecv+ReturnTo)-(IsuueUP+Unusable);
	if(document.getElementById('MOS'+itemId) && document.getElementById('WHCBLA'+itemId))
	{
		if(parseInt(myactualavg)>0)
		{
			document.getElementById('MOS'+itemId).value = roundNumber(parseInt(document.getElementById('WHCBLA'+itemId).value)/parseInt(myactualavg),1);
		}
		else {
			document.getElementById('MOS'+itemId).value = roundNumber(parseInt(document.getElementById('WHCBLA'+itemId).value)/1,1);
		}
	}
	if(document.getElementById('FLDCBLC'+itemId))
		document.getElementById('FLDCBLC'+itemId).value = (fldolbc+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
	if(document.getElementById('FLDCBLA'+itemId))	
	document.getElementById('FLDCBLA'+itemId).value = (fldolba+FLDRecv+FLDReturnTo)-(FLDIsuueUP+FLDUnusable);
	if(document.getElementById('FLDMOS'+itemId) && document.getElementById('FLDCBLA'+itemId))
	{
		if(parseInt(myactualavg)>0)
		{
			document.getElementById('FLDMOS'+itemId).value = roundNumber(parseInt(document.getElementById('FLDCBLA'+itemId).value)/parseInt(myactualavg),1);
		}
		else {
			document.getElementById('FLDMOS'+itemId).value = roundNumber(parseInt(document.getElementById('FLDCBLA'+itemId).value)/1,1);
		}
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
				alert('Please Enter Recieved');
				document.getElementById(whrecv).focus();
				return false;
			}
			if(document.getElementById(whissue).value==0 || document.getElementById(whissue).value=='')
			{
				alert('Please Enter Recieved');
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
<table width="850" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr id="hideit">
        <td><?php //include("header.php");
include(SITE_PATH."plmis_inc/common/top.php");
?></td>
    </tr>
    <tr>
        <td valign="top"><table width="100%" height="146" valign="top">
                <tr>
                    <td width="80%" bgcolor="#E1E1E1" valign="top" id="showGrid"><? 
					$wh_id="";
					
					if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
					{
						$temp=urldecode($_REQUEST['Do']);
						$tmpStr=substr($temp,1,strlen($temp)-1);
						$temp=explode("|",$tmpStr);
						
						//****************************************************************************
						$wh_id=$temp[0]-77000; // Warehouse ID
						$RptDate=$temp[1]; //Report Date
						$isNewRpt=$temp[2]; //if value=1 then new report
						$tt=explode("-",$RptDate); 
						$yy=$tt[0]; //Reprot year
						$mm=$tt[1];		//report Month
						
						// Check level
						$qryLvl = mysql_fetch_array(mysql_query("SELECT
																stakeholder.lvl
															FROM
																tbl_warehouse
															INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
															WHERE
																tbl_warehouse.wh_id = $wh_id"));
						if ($qryLvl['lvl'] == 4)
						{
							$title = "Consumed";
						}
						else
						{
							$title = "Issued";
						}
						
						$month = date('M', mktime(0, 0, 0, $mm, 1));
						
						//****************************************************************************
						$objwarehouse->m_npkId=$wh_id;
						$stkid=$objwarehouse->GetStkIDByWHId($wh_id);
						print "<b>Store/Facility:</b> ".$objwarehouse->GetWarehouseNameById($wh_id);
						print "<b>&nbsp;&nbsp;&nbsp;&nbsp;Monthly Report:</b> ".$month.'-'.$yy;
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
                        <FORM NAME="frmF7" id="frmF7" ACTION="data_entry_action.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" onSubmit="return formvalidate1();">
                            <TABLE CELLPADDING="2" CELLSPACING="0" WIDTH="100%" BORDER="1" ALIGN="LEFT" CLASS="TableAreaSmall" BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE">
                                <TR>
                                    <TD rowspan="2" align="center" width="19" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">S.No.</TD>
                                    <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">Article</TD>
                                    <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month.">Opening balance</TD>
                                    <TD rowspan="2" align="center" width="62" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Quantity Of The Received Items From The CWH In This Month.">Recieved</TD>
                                    <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Quantity Of The Issued Items In This Month."><?php echo $title;?></TD>
                                    <TD colspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="">Adjustments</TD>
                                    <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Closing Balance Of The Stock For This Month.">Closing Balance</TD>
                                </TR>
                                <TR> 
                                    <TD width="53" align="center" CLASS="F7BCOLCAH" TITLE="This is Sum of Adjustments That Results  Increase In Stock."><b class="sb1FormLabel">(+)</b></TD>
                                    <TD width="53" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This is Sum of Adjustments That Results  Decrease In Stock."><b class="sb1FormLabel">(-)</b></TD>
                                </TR>
                                <TR class="sb1GreenInfoBoxMiddleText">
                                    <TD CLASS="TDLCOLCABSMALL">1</TD>
                                    <TD CLASS="TDLCOLCABSMALL">2</TD>
                                    <TD CLASS="TDLCOLCABSMALL">3</TD>
                                    <TD CLASS="TDLCOLCABSMALL">4</TD>
                                    <TD CLASS="TDLCOLCABSMALL">5</TD>
                                    <TD CLASS="TDLCOLCABSMALL">6</TD>
                                    <TD CLASS="TDLCOLCABSMALL">7</TD>
                                    <TD CLASS="TDLCOLCABSMALL">8</TD>
                                </TR>
								<?php
                                $isDraft = false;
                                // See if this month data exists in drafts
                                $qry = "SELECT
                                            COUNT(tbl_wh_data_draft.w_id) AS num
                                        FROM
                                            tbl_wh_data_draft
                                        WHERE
                                            tbl_wh_data_draft.report_month = $mm
                                        AND tbl_wh_data_draft.report_year = $yy
                                        AND tbl_wh_data_draft.wh_id = $wh_id";
                                $qryRes = mysql_fetch_array(mysql_query($qry));
                                if ($qryRes['num']>0)
                                {
                                    $isDraft = true;
                                }
                                $rsTemp1=mysql_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`='Current' AND `itm_id` IN (SELECT `Stk_item` FROM `stakeholder_item` WHERE `stkid` =$stkid) AND itminfo_tab.itm_category = 1 ORDER BY `frmindex`");
                                $SlNo=1;
                                $fldIndex=0;
                                $ItemTableName=$TableName;
                                while($rsRow1=mysql_fetch_array($rsTemp1))
                                {
                                    $SlNo=((strlen($SlNo)<2) ? $SlNo : $SlNo);
                                    
                                    if ( $isDraft ) // If draft then get from draft table
                                    {
                                        $qry="SELECT * FROM tbl_wh_data_draft WHERE `wh_id`='".$wh_id."' AND tbl_wh_data_draft.report_month = $mm AND tbl_wh_data_draft.report_year = $yy AND `item_id`='$rsRow1[itmrec_id]'";
                                        $rsTemp3=mysql_query($qry);	
                                        $rsRow2=mysql_fetch_array($rsTemp3);
                                    }
                                    else  // If Not drafted then get from tbl_wh_data
                                    {
                                        $qry="SELECT * FROM tbl_wh_data WHERE `wh_id`='".$wh_id."' AND RptDate='".$PrevMonthDate."' AND `item_id`='$rsRow1[itmrec_id]'";
                                        $rsTemp3=mysql_query($qry);	
                                        $rsRow2=mysql_fetch_array($rsTemp3);
                                    }
                                    //echo "<br><br>".$qry;
                                    $add_date = $rsRow2['add_date'];
                                    // if new report
                                    if ( $isNewRpt == 1 )
                                    {
                                        if ( $isDraft ) // If draft then get from draft table
                                        {
                                            $wh_issue_up = $rsRow2['wh_issue_up'];
                                            $wh_adja = $rsRow2['wh_adja'];
                                            $wh_adjb = $rsRow2['wh_adjb'];
                                            $wh_received = $rsRow2['wh_received'];
                                            $ob_c = $rsRow2['wh_obl_c'];
                                            $ob_a = $rsRow2['wh_obl_a'];
                                            $cb_c = $rsRow2['wh_cbl_c'];
                                            $cb_a = $rsRow2['wh_cbl_a'];
                                        }
                                        else
                                        {
                                            $wh_issue_up = 0;
                                            $wh_adja = 0;
                                            $wh_adjb = 0;
                                            $wh_received = 0;
                                            $ob_c = $rsRow2['wh_cbl_c'];
                                            $ob_a = $rsRow2['wh_cbl_a'];
                                            $cb_c = $rsRow2['wh_cbl_c'];
                                            $cb_a = $rsRow2['wh_cbl_a'];
                                        }
                                        ?>
                                <TR>
                                    <TD CLASS="sb1NormalFontArial" style="text-align:center !important"><? echo $SlNo;?></TD>
                                    <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                                        <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itmrec_id'];?>">
                                        <?php $itemid = explode('-',$rsRow1['itmrec_id']); ?></TD>
                                    <TD CLASS="TDLCOLRASMALLW" style="display:none;"><INPUT autocomplete="off" CLASS="sb1NormalFontArial" TYPE="TEXT" SIZE="8" NAME="FLDOBLC<?php echo $itemid[1];?>" ID="FLDOBLC<?php echo $itemid[1];?>" VALUE="<?php echo $ob_c;?>" readonly style="background-color:#CCC;"></TD>
                                    <TD CLASS="TextBoxSmallRA"><INPUT <?php  if (($RptDate=='2014-12-01' && ($wh_id == 166 || $wh_id == 165 || $wh_id == 3983)) || (($RptDate=='2015-04-01' && $wh_id == 3985)) || $stkid == 73) {} else { echo 'readonly'; echo ' STYLE="BACKGROUND-COLOR:#ccc"';} ?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDOBLA<?php echo $itemid[1];?>" ID="FLDOBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $ob_a;?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT <?php echo $isReadOnly . $style;?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $itemid[1];?>" ID="FLDRecv<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php echo $wh_received;?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD width="13" CLASS="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $itemid[1];?>" ID="FLDIsuueRWH<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" onKeyUp="cal_balance('<?php echo $itemid[1];?>');">
                                        <INPUT autocomplete="off" NAME="FLDIsuueUP<?php echo $itemid[1];?>" ID="FLDIsuueUP<?php echo $itemid[1];?>" VALUE="<?php echo $wh_issue_up;?>" TYPE="TEXT" CLASS="TextBoxSmallRA" SIZE="8" MAXLENGTH="10" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $itemid[1];?>" ID="FLDmyavg<?php echo $itemid[1];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itmrec_id']);?>">
                                        <INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $itemid[1];?>" ID="FLDReturnTo<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $wh_adja;?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $itemid[1];?>" ID="FLDUnusable<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $wh_adjb;?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial" style="display:none;"><INPUT autocomplete="off" CLASS="TextBoxSmallRO" TYPE="TEXT" NAME="FLDCBLC<?php echo $itemid[1];?>" ID="FLDCBLC<?php echo $itemid[1];?>" SIZE="8" VALUE="<?php echo $cb_c;?>" readonly style="background-color:#CCC;"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $itemid[1];?>" ID="FLDCBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $cb_a;?>"  style="background-color:#CCC;" readonly>
                                        <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $itemid[1];?>" ID="FLDrecord_id<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['w_id'];?>">
                                    </TD>
                                </TR>
                                <?php	
								}
								else // If oone of the 3 months 
								{
								?>
                                <TR>
                                    <TD CLASS="sb1NormalFontArial" style="text-align:center !important"><? echo $SlNo;?></TD>
                                    <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                                        <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itmrec_id'];?>">
                                        <?php $itemid = explode('-',$rsRow1['itmrec_id']); ?></TD>
                                    <TD CLASS="TextBoxSmallRA" style="display:none;"><INPUT autocomplete="off" CLASS="sb1NormalFontArial" TYPE="TEXT" SIZE="8" NAME="FLDOBLC<?php echo $itemid[1];?>" ID="FLDOBLC<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['wh_obl_c'];?>" readonly style="background-color:#CCC;"></TD>
                                    <TD CLASS="TextBoxSmallRA"><INPUT  <?php  if (($RptDate=='2014-12-01' && ($wh_id == 166 || $wh_id == 165 || $wh_id == 3983)) || (($RptDate=='2015-04-01' && $wh_id == 3985)) || $stkid == 73) {} else { echo 'readonly'; echo ' STYLE="BACKGROUND-COLOR:#ccc"';} ?>  autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDOBLA<?php echo $itemid[1];?>" ID="FLDOBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_obl_a'];?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT <?php echo $isReadOnly . $style;?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $itemid[1];?>" ID="FLDRecv<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php echo $rsRow2['wh_received'];?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD width="13" CLASS="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $itemid[1];?>" ID="FLDIsuueRWH<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onKeyUp="cal_balance('<?php echo $itemid[1];?>');">
                                        <INPUT autocomplete="off" NAME="FLDIsuueUP<?php echo $itemid[1];?>" ID="FLDIsuueUP<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['wh_issue_up'];?>" TYPE="TEXT" CLASS="TextBoxSmallRA" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'"  SIZE="8" MAXLENGTH="10" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $itemid[1];?>" ID="FLDmyavg<?php echo $itemid[1];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itmrec_id']);?>">
                                        <INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $itemid[1];?>" ID="FLDReturnTo<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_adja'];?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $itemid[1];?>" ID="FLDUnusable<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_adjb'];?>" onKeyUp="cal_balance('<?php echo $itemid[1];?>');"></TD>
                                    <TD class="sb1NormalFontArial" style="display:none;"><INPUT autocomplete="off" CLASS="TextBoxSmallRO" TYPE="TEXT" NAME="FLDCBLC<?php echo $itemid[1];?>" ID="FLDCBLC<?php echo $itemid[1];?>" SIZE="8" VALUE="<?php echo $rsRow2['wh_cbl_c'];?>" readonly style="background-color:#CCC;"></TD>
                                    <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $itemid[1];?>" ID="FLDCBLA<?php echo $itemid[1];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['wh_cbl_a'];?>"  style="background-color:#CCC;" readonly>
                                        <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $itemid[1];?>" ID="FLDrecord_id<?php echo $itemid[1];?>" VALUE="<?php echo $rsRow2['w_id'];?>">
                                    </TD>
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
                                    <TD colspan="10" align="right" CLASS="TDLCOLRASMALLW"><IMG SRC="<?php echo PLMIS_IMG; ?>CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmF7.reset()" CLASS="Himg" style=" cursor:pointer">
                                        <INPUT TYPE="image" name="saveBtn" id="saveBtn" SRC="<?php echo PLMIS_IMG; ?>CmdSave.gif">
                                    </TD>
                                </TR>
                            </TABLE>
                            <INPUT TYPE="hidden" NAME="ActionType" VALUE="Add">
                            <INPUT TYPE="HIDDEN" NAME="RptDate" VALUE="<?php echo $RptDate;?>">
                            <INPUT TYPE="HIDDEN" NAME="wh_id" VALUE="<?php echo $wh_id;?>">
                            <INPUT TYPE="HIDDEN" NAME="yy" VALUE="<?php echo $yy;?>">
                            <INPUT TYPE="HIDDEN" NAME="mm" VALUE="<?php echo $mm;?>">
                            <input type="hidden" name="mystake" id="mystake" />
                            <input type="hidden" name="Stake" id="Stake" value="<?php if($Stk !=''){echo $Stk;}else { echo $_GET['stakeHolder']; }	?>">
                            <input type="hidden" name="sysusr_type" id="sysusr_type" value="<?php echo $_GET['sysusr_type']; ?>">
                            <input type="hidden" name="cws1" id="hiddenField2" value="<?php echo $_GET['cws1']; ?>">
                            <input type="hidden" name="isNewRpt" id="isNewRpt" value="<?php echo $isNewRpt;?>" />
                            <input type="hidden" name="add_date" id="add_date" value="<?php echo $add_date;?>" />
                        </FORM>
                        <? 
						}
					else
					{
						//echo "<H1><- Please select warehouse for report</H1>";
					}
					?>
                    </td>
                </tr>
                <tr>
                    <td colspan="8"><div style="color:#F00;">Click save to confirm your changes. </div></td>
                </tr>
                <tr>
                    <td colspan="8"><div id="eMsg" style="color:#060;"></div></td>
                </tr>
            </table></td>
    </tr>
</table>
<script>
var form_clean;
$(document).ready(function() {
	form_clean = $("#frmF7").serialize();
	
	// Auto Save function call
	setInterval('autoSave()', 20000);
	
	$('input[type="text"]').each(function() {
		if ( $(this).val() == '' )
		{
			$(this).val(0);
		}
	});
	
	$('input[type="text"]').change(function(e) {
		if ($(this).val() == '')
		{
			$(this).val('0');
		}
	});
	$('input[type="text"]').focus(function(e) {
		if ($(this).val() == '0')
		{
			$(this).val('');
		}
	});
	$('input[type="text"]').focusout(function(e) {
		if ($(this).val() == '')
		{
			$(this).val('0');
		}
	});
	$('input[type="text"]').keydown(function(e) {
		if (e.shiftKey || e.ctrlKey || e.altKey) { // if shift, ctrl or alt keys held down
            e.preventDefault();         // Prevent character input
        } else {
            var n = e.keyCode;
            if (!((n == 8)              // backspace
            || (n == 9)                // Tab
            || (n == 46)                // delete
            || (n >= 35 && n <= 40)     // arrow keys/home/end
            || (n >= 48 && n <= 57)     // numbers on keyboard
            || (n >= 96 && n <= 105))   // number on keypad
            ) {
                e.preventDefault();     // Prevent character input
            }
        }
	});
});

function autoSave()
{
	var form_dirty = $("#frmF7").serialize();
	if(form_clean != form_dirty)
	{
		$('#saveBtn').attr('disabled', 'disabled');
		$("#eMsg").html('Saving...');
		$.ajax({
			type: "POST", 
			url: "data_entry_action_draft.php", 
			data: $('#frmF7').serialize(),
			cache: false, 
			success: function(){
				$("#eMsg").fadeTo(500,1,function(){
					$(this).show();
					$(this).html('Your data is saved is draft.').fadeTo(3000, 0, function(){
						$(this).hide();
						$('#saveBtn').removeAttr('disabled');
					});
				});
			}
		});
		form_clean = form_dirty;
	}
}
function formvalidate1()
{
	var itmLength = $("input[name^='flitmrec_id']").length;
    var itmArr = $("input[name^='flitmrec_id']");
    var FLDOBLAArr = $("input[name^='FLDOBLA']");
    var FLDRecvArr = $("input[name^='FLDRecv']");
    var FLDIsuueUPArr = $("input[name^='FLDIsuueUP']");
    var FLDCBLAArr = $("input[name^='FLDCBLA']");
    var FLDReturnToArr = $("input[name^='FLDReturnTo']");
    var FLDUnusableArr = $("input[name^='FLDUnusable']");
	/*
	var fieldval = document.frmaddF7.itmrec_id[i].value;
	fieldconcat = fieldval.split('-');
	var whobla = 'WHOBLA'+fieldconcat[1];
	var whrecv = 'WHRecv'+fieldconcat[1];
	var whissue = 'IsuueUP'+fieldconcat[1];
	var fldobla = 'FLDOBLA'+fieldconcat[1];
	var fldrecv = 'FLDRecv'+fieldconcat[1];
	var fldissue = 'FLDIsuueUP'+fieldconcat[1];
	*/
	for(i=0;i < itmLength;i++)
	{
		itm = itmArr.eq(i).val();
		var itmInfo = itm.split('-');
		itmId = itmInfo[1];
		var FLDOBLA = parseInt(FLDOBLAArr.eq(i).val());
		var FLDRecv = parseInt(FLDRecvArr.eq(i).val());
		var FLDIsuueUP = parseInt(FLDIsuueUPArr.eq(i).val());
		var FLDCBLA = parseInt(FLDCBLAArr.eq(i).val());
		var FLDReturnTo = parseInt(FLDReturnToArr.eq(i).val());
		var FLDUnusable = parseInt(FLDUnusableArr.eq(i).val());
		
		
		
		if ( (FLDIsuueUP + FLDUnusable) > (FLDOBLA + FLDRecv + FLDReturnTo) )
		{
			alert('Issue can not be greater than Opening Balance + Received + Adjustments(+)');
			FLDOBLAArr.eq(i).css('background', '#F45B5C');
			FLDRecvArr.eq(i).css('background', '#F45B5C');
			FLDIsuueUPArr.eq(i).css('background', '#F45B5C');
			FLDIsuueUPArr.eq(i).focus();
			return false;
		}
		var balance = (FLDOBLA + FLDRecv + FLDReturnTo) - (FLDIsuueUP + FLDUnusable);

		if ( balance != FLDCBLA )
		{
			alert('There is an error in the data. Please correct the data.');
			FLDOBLAArr.eq(i).css('background', '#F45B5C');
			FLDRecvArr.eq(i).css('background', '#F45B5C');
			FLDIsuueUPArr.eq(i).css('background', '#F45B5C');
			FLDCBLAArr.eq(i).css('background', '#F45B5C');
			FLDReturnToArr.eq(i).css('background', '#F45B5C');
			FLDUnusableArr.eq(i).css('background', '#F45B5C');
			return false;
		}
	}
	$('#saveBtn').attr('disabled', true);
}
</script>
<style>
.sb1NormalFontArial{ text-align:right !important;}
</style>
</body>
</html>