<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<table cellpadding="2" cellspacing="0" width="100%" border="1" align="left" class="TableAreaSmall" bordercolor="#000000" style="BORDER-COLLAPSE: COLLAPSE">
  
  <tr>
    <td width="19" rowspan="2" class="sb1GeenGradientBoxMiddle" title="This Is Just A Simple Serial Number.">Sl.<br />
No</td>
    <td width="150" rowspan="2" class="sb1GeenGradientBoxMiddle" title="This Is The Reported Article/Item Name.">ARTICLE</td>
    <td class="F7BCOLCAH" align="center" colspan="2" title="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month."><b class="sb1FormLabel">Opening balance</b></td>
    <td width="62" rowspan="2" align="center" class="F7BCOLCAH" title="This Is The Quantity Of The Received Items From The CWH In This Month."><b class="sb1FormLabel">Received</b></td>
    <!--<TD CLASS="F7BCOLCAH" ROWSPAN="2" TITLE="This Is The Quantity Of The Items Returned From The Thana/RWH In This Month."><B class="sb1FormLabel">Returned from <BR>
                                  SDPs/DWH</B></TD>-->
    <td colspan="3" rowspan="2" class="F7BCOLCAH" title="This Is The Quantity Of The Issued Items In This Month."><b class="sb1FormLabel">Issued</b></td>
    <td colspan="2" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Quantity Of The Items Returned To CWH In This Month.">Adjustments</td>
    <td class="sb1GeenGradientBoxMiddle" colspan="2" align="center" title="This Is The Closing Balance Of The Stock For This Month."><b class="sb1FormLabel">Closing Balance</b></td>
  </tr>
  <tr>
    <td width="66" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Stock Opening Balance, Which Is The Previous Month Stock Closing Balance And Automatically Showing Form The Database. This Field Value Are Not Editable.">Calculated</td>
    <td width="42" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Actual store/warehouse Stock Opening Balance, Which Will Be Entered From The warehouse Report Data.">Actual</td>
    <td width="53" align="center" class="F7BCOLCAH" title="This is Sum of Adjustments That Results  Increase In Stock."><b class="sb1FormLabel">(+)</b></td>
    <td width="53" align="center" class="sb1GeenGradientBoxMiddle" title="This is Sum of Adjustments That Results  Decrease In Stock."><b class="sb1FormLabel">(-)</b></td>
    <td width="73" align="center" class="sb1GeenGradientBoxMiddle" title="This Is the Store/Warehouse Stock Opening Balance, Which Is The Previous Month Store/Warehouse Stock Closing Balance and Automatically Showing From The Database. This Fields Value Are Not Editable.">Calculated</td>
    <td width="77" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Actual Store/Warehouse Stock Opening Balance, Which Will Be Entered From The Monthly Report Data.">Actual</td>
  </tr>
  <tr class="sb1GreenInfoBoxMiddleText">
    <td class="TDLCOLCABSMALL">1</td>
    <td class="TDLCOLCABSMALL">2</td>
    <td class="TDLCOLCABSMALL">3(C)</td>
    <td class="TDLCOLCABSMALL">3(A)</td>
    <td class="TDLCOLCABSMALL">4</td>
    <!--<TD CLASS="TDLCOLCABSMALL">5</TD>-->
    <td class="TDLCOLCABSMALL" colspan="3">6</td>
    <td class="TDLCOLCABSMALL">7</td>
    <td class="TDLCOLCABSMALL">8</td>
    <td class="TDLCOLCABSMALL">9(C)</td>
    <td class="TDLCOLCABSMALL">9(A)</td>
  </tr>
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
  <tr>
    <td class="sb1NormalFontArial"><? echo $SlNo;?></td>
    <td class="TDLCOLLASMALL" nowrap="nowrap"><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_obl_c']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_obl_a']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_recieved']);?></td>
    <td width="13" class="sb1NormalFontArial" colspan="3"><?php echo number_format($rsRow2['fld_issue_up']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_adja']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_adjb']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_cbl_c']);?></td>
    <td class="sb1NormalFontArial"><?php echo number_format($rsRow2['fld_cbl_a']);?></td>
  </tr>
  <?php		
                                    $SlNo++;
                                }
                                mysql_free_result($rsTemp1);
                                ?>
</table>



<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table cellpadding="2" cellspacing="0" width="100%" border="1" align="left" class="TableAreaSmall" bordercolor="#000000" style="BORDER-COLLAPSE: COLLAPSE">
  <?php if($_REQUEST['sysusr_type']=='UT-002'){ $f7colspan =12;} else {$f7colspan =11;}?>
  
  <tr>
    <td width="19" class="sb1GeenGradientBoxMiddle" title="This Is Just A Simple Serial Number.">Sl.<br />
No</td>
    <td class="sb1GeenGradientBoxMiddle" width="150" title="This Is The Reported Article/Item Name.">ARTICLE</td>
    <td class="F7BCOLCAH" align="center" colspan="2" title="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month."><b class="sb1FormLabel">Opening balance</b></td>
    <td width="62" rowspan="2" align="center" class="F7BCOLCAH" title="This Is The Quantity Of The Received Items From The CWH In This Month."><b class="sb1FormLabel">Received</b></td>
    <!--<TD CLASS="F7BCOLCAH" ROWSPAN="2" TITLE="This Is The Quantity Of The Items Returned From The Thana/RWH In This Month."><B class="sb1FormLabel">Returned from <BR>
                                      SDPs/DWH</B></TD>-->
    <td rowspan="2" class="F7BCOLCAH" title="This Is The Quantity Of The Issued Items In This Month."><b class="sb1FormLabel">Issued</b></td>
    <td colspan="2" align="center" class="sb1GeenGradientBoxMiddle" title="">Adjustments</td>
    <td class="sb1GeenGradientBoxMiddle" colspan="2" align="center" title="This Is The Closing Balance Of The Stock For This Month."><b class="sb1FormLabel">Closing Balance</b></td>
    <?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
    <?php } ?>
  </tr>
  <tr>
    <td width="19" class="sb1GeenGradientBoxMiddle" title="This Is Just A Simple Serial Number.">&nbsp;</td>
    <td class="sb1GeenGradientBoxMiddle" width="150" title="This Is The Reported Article/Item Name.">&nbsp;</td>
    <td width="66" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Stock Opening Balance, Which Is The Previous Month Stock Closing Balance And Automatically Showing Form The Database. This Field Value Are Not Editable.">Calculated</td>
    <td width="42" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Actual store/warehouse Stock Opening Balance, Which Will Be Entered From The warehouse Report Data.">Actual</td>
    <td width="53" align="center" class="F7BCOLCAH" title="This is Sum of Adjustments That Results  Increase In Stock."><b class="sb1FormLabel">(+)</b></td>
    <td width="53" align="center" class="sb1GeenGradientBoxMiddle" title="This is Sum of Adjustments That Results  Decrease In Stock."><b class="sb1FormLabel">(-)</b></td>
    <td width="73" align="center" class="sb1GeenGradientBoxMiddle" title="This Is the Store/Warehouse Stock Opening Balance, Which Is The Previous Month Store/Warehouse Stock Closing Balance and Automatically Showing From The Database. This Fields Value Are Not Editable.">Calculated</td>
    <td width="77" align="center" class="sb1GeenGradientBoxMiddle" title="This Is The Actual Store/Warehouse Stock Opening Balance, Which Will Be Entered From The Monthly Report Data.">Actual</td>
  </tr>
  <tr class="sb1GreenInfoBoxMiddleText">
    <td class="TDLCOLCABSMALL">1</td>
    <td class="TDLCOLCABSMALL">2</td>
    <td class="TDLCOLCABSMALL">3(C)</td>
    <td class="TDLCOLCABSMALL">3(A)</td>
    <td class="TDLCOLCABSMALL">4</td>
    <!--<TD CLASS="TDLCOLCABSMALL">5</TD>-->
    <td class="TDLCOLCABSMALL">6</td>
    <td class="TDLCOLCABSMALL">7</td>
    <td class="TDLCOLCABSMALL">8</td>
    <td class="TDLCOLCABSMALL">9(C)</td>
    <td class="TDLCOLCABSMALL">9(A)</td>
    <?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
    <?php } ?>
  </tr>
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
        
                                    $fldIndex=$Index;
                                    //$TabIndex=1;
                                    $ItemTableName=$TableName;
                                    while($rsRow1=mysql_fetch_array($rsTemp1))
                                        {										
                                            $SlNo=((strlen($SlNo)<2) ? "0".$SlNo : $SlNo);
                                            $rsTemp2=safe_query("SELECT * FROM tbl_wh_data WHERE `wh_id`='".$whid."' AND `report_month`='$prev_month' AND report_year='$prev_year' AND `item_id`='$rsRow1[itmrec_id]'");								
                                            $rsRow2=mysql_fetch_array($rsTemp2);
                                  ?>
  <tr>
    <td class="sb1NormalFontArial"><? echo $SlNo;?></td>
    <td class="TDLCOLLASMALL" nowrap="nowrap"><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
        <input type="hidden" name="flitmrec_id[]" value="<?php echo $rsRow1['itmrec_id'];?>" />
        <?php $itemid = explode('-',$rsRow1['itmrec_id']); ?>    </td>
    <td class="TDLCOLRASMALLW"><input class="sb1NormalFontArial" type="text" size="8" name="FLDOBLC<?php echo $itemid[1];?>" id="FLDOBLC<?php echo $itemid[1];?>" value="<?php echo (empty($rsRow2['fld_cbl_a']))?'0':$rsRow2['fld_cbl_a'];?>" readonly="1" style="background-color:#CCC;" />    </td>
    <td><input class="sb1NormalFontArial" type="text" name="FLDOBLA<?php echo $itemid[1];?>" id="FLDOBLA<?php echo $itemid[1];?>" size="8" maxlength="10" value="<?php //echo (empty($rsRow2['fld_cbl_a']))?'0':$rsRow2['fld_cbl_a'];?>" style="BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />    </td>
    <td class="sb1NormalFontArial"><input class="TextBoxSmallRA" type="text" name="FLDRecv<?php echo $itemid[1];?>" id="FLDRecv<?php echo $itemid[1];?>" size="8" maxlength="10"  value="" style=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />    </td>
    <input class="TextBoxSmallRA" type="hidden" name="FLDIsuueRWH<?php echo $itemid[1];?>" id="FLDIsuueRWH<?php echo $itemid[1];?>" size="8" maxlength="10"  value="0" style=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />
    <td width="13" class="sb1NormalFontArial"><input name="FLDIsuueUP<?php echo $itemid[1];?>" id="FLDIsuueUP<?php echo $itemid[1];?>" type="text" class="TextBoxSmallRA" style=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'"  size="8" maxlength="10" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />    </td>
    <input class="TextBoxSmallRA" type="hidden" name="FLDmyavg<?php echo $itemid[1];?>" id="FLDmyavg<?php echo $itemid[1];?>" size="5" maxlength="10"  value="<?php echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itmrec_id']);?>" onkeypress="return numbersonly(this, event)" />
    <td class="sb1NormalFontArial"><input class="TextBoxSmallRA" type="text" name="FLDReturnTo<?php echo $itemid[1];?>" id="FLDReturnTo<?php echo $itemid[1];?>" size="8" maxlength="10" value="" style=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />    </td>
    <td class="sb1NormalFontArial"><input class="TextBoxSmallRA" type="text" name="FLDUnusable<?php echo $itemid[1];?>" id="FLDUnusable<?php echo $itemid[1];?>" size="8" maxlength="10" value="" style=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onchange="cal_balance('<?php echo $itemid[1];?>');" onkeypress="return numbersonly(this, event)" />    </td>
    <td class="sb1NormalFontArial"><input class="TextBoxSmallRO" type="text" name="FLDCBLC<?php echo $itemid[1];?>" id="FLDCBLC<?php echo $itemid[1];?>" size="8" value="<?php echo $rsRow2['fld_cbl_a'];?>" readonly="1" style="background-color:#CCC;" />    </td>
    <td class="sb1NormalFontArial"><input class="TextBoxSmallRA" type="text" name="FLDCBLA<?php echo $itemid[1];?>" id="FLDCBLA<?php echo $itemid[1];?>" size="8" maxlength="10" value=""  style="background-color:#CCC;" readonly="1" />
        <input type="hidden" name="FLDrecord_id<?php echo $itemid[1];?>" id="FLDrecord_id<?php echo $itemid[1];?>" value="<?php echo $rsRow2['w_id'];?>" /></td>
    <?php if($_REQUEST['sysusr_type']=='UT-002'){ ?>
    <?php } ?>
  </tr>
  <?php		
										$SlNo++;
									}
									mysql_free_result($rsTemp1);
								  ?>
</table>
<p>&nbsp;</p>
</body>
</html>
