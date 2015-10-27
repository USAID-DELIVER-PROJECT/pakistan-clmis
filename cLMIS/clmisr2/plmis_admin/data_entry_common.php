<FORM NAME="frmF7" id="frmF7" ACTION="data_entry_hf_action.php" METHOD="POST" ENCTYPE="MULTIPART/FORM-DATA" onSubmit="return formvalidate1();">
    <TABLE CELLPADDING="2" CELLSPACING="0" WIDTH="100%" BORDER="1" ALIGN="LEFT" CLASS="TableAreaSmall" BORDERCOLOR="#000000" STYLE="BORDER-COLLAPSE: COLLAPSE">
        <TR>
            <TD rowspan="2" width="19" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is Just A Simple Serial Number.">S.No.</TD>
            <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" WIDTH="150" TITLE="This Is The Reported Article/Item Name.">Article</TD>
            <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Opening Balance Of The Month,i.e. The Closing balance Of The Previous Month.">Opening balance</TD>
            <TD rowspan="2" align="center" width="62" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Quantity Of The Received Items From The CWH In This Month.">Received</TD>
            <TD rowspan="2" align="center" CLASS="sb1GeenGradientBoxMiddle" TITLE="This Is The Quantity Of The Issued Items In This Month.">Issue</TD>
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
        $rsTemp1=mysql_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`='Current' AND `itm_id` IN (SELECT `Stk_item` FROM `stakeholder_item` WHERE `stkid` =$stkid) ORDER BY `frmindex`");
        $SlNo=1;
        $fldIndex=0;
        $ItemTableName=$TableName;
        while($rsRow1=mysql_fetch_array($rsTemp1))
        {
            $SlNo=((strlen($SlNo)<2) ? $SlNo : $SlNo);
                                                
            $qry="SELECT * FROM tbl_hf_data WHERE `warehouse_id`='".$wh_id."' AND reporting_date='".$PrevMonthDate."' AND `item_id`='$rsRow1[itm_id]'";
            $rsTemp3=mysql_query($qry);	
            $rsRow2=mysql_fetch_array($rsTemp3);

            $add_date = $rsRow2['created_date'];

            // if new report
            if ( $isNewRpt == 1 )
            {
                $wh_issue_up = 0;
                $wh_adja = 0;
                $wh_adjb = 0;
                $wh_received = 0;
                $ob_a = $rsRow2['closing_balance'];
                $cb_a = $rsRow2['closing_balance'];
                ?>
            <TR>
                                                   
                <TD style="text-align:center !important;" CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
                <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                    <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itm_id'];?>">
                     <INPUT TYPE="HIDDEN" NAME="flitm_category[]" VALUE="<?php echo $rsRow1['itm_category'];?>">
                </TD>
                <TD CLASS="TextBoxSmallRA"><INPUT <?php echo (!empty($openOB)) ? 'readonly="readonly" style="background-color:#CCC;"' : '';?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDOBLA<?php echo $rsRow1['itm_id'];?>" ID="FLDOBLA<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $ob_a;?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
                <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $rsRow1['itm_id'];?>" ID="FLDRecv<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php echo $wh_received;?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
                <TD width="13" CLASS="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $rsRow1['itm_id'];?>" ID="FLDIsuueRWH<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');">
                    <INPUT autocomplete="off" NAME="FLDIsuueUP<?php echo $rsRow1['itm_id'];?>" ID="FLDIsuueUP<?php echo $rsRow1['itm_id'];?>" VALUE="<?php echo $wh_issue_up;?>" TYPE="TEXT" CLASS="TextBoxSmallRA" SIZE="8" MAXLENGTH="10" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
                <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $rsRow1['itm_id'];?>" ID="FLDmyavg<?php echo $rsRow1['itm_id'];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itm_id']);?>">
                    <INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $rsRow1['itm_id'];?>" ID="FLDReturnTo<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $wh_adja;?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
                <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $rsRow1['itm_id'];?>" ID="FLDUnusable<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $wh_adjb;?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
                <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $rsRow1['itm_id'];?>" ID="FLDCBLA<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $cb_a;?>"  style="background-color:#CCC;" readonly>
                    <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $rsRow1['itm_id'];?>" ID="FLDrecord_id<?php echo $rsRow1['itm_id'];?>" VALUE="<?php echo $rsRow2['w_id'];?>">
                </TD>
            </TR>
            <?php
        }
        else if ( $isNewRpt == 0 )
        {
        ?>
        <TR>
            <TD style="text-align:center !important;" CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
            <TD CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span>
                <INPUT TYPE="HIDDEN" NAME="flitmrec_id[]" VALUE="<?php echo $rsRow1['itm_id'];?>">
                <INPUT TYPE="HIDDEN" NAME="flitm_category[]" VALUE="<?php echo $rsRow1['itm_category'];?>">
            </TD>
            <TD CLASS="TextBoxSmallRA"><INPUT <?php echo (!empty($openOB)) ? 'readonly="readonly" style="background-color:#CCC;"' : '';?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDOBLA<?php echo $rsRow1['itm_id'];?>" ID="FLDOBLA<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['opening_balance'];?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
            <TD class="sb1NormalFontArial"><INPUT <?php echo $isReadOnly . $style;?> autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDRecv<?php echo $rsRow1['itm_id'];?>" ID="FLDRecv<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10"  VALUE="<?php echo $rsRow2['received_balance'];?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
            <TD width="13" CLASS="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDIsuueRWH<?php echo $rsRow1['itm_id'];?>" ID="FLDIsuueRWH<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10"  VALUE="0" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');">
                <INPUT autocomplete="off" NAME="FLDIsuueUP<?php echo $rsRow1['itm_id'];?>" ID="FLDIsuueUP<?php echo $rsRow1['itm_id'];?>" VALUE="<?php echo $rsRow2['issue_balance'];?>" TYPE="TEXT" CLASS="TextBoxSmallRA" STYLE=" BACKGROUND-COLOR='<?php echo $rsRow1[field_color];?>'"  SIZE="8" MAXLENGTH="10" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
            <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="hidden" NAME="FLDmyavg<?php echo $rsRow1['itm_id'];?>" ID="FLDmyavg<?php echo $rsRow1['itm_id'];?>" SIZE="5" MAXLENGTH="10"  VALUE="<?php //echo getConsumptionAvg($_POST['report_month'], $_POST['report_year'], $_POST['wh_id'], $rsRow1['itm_id']);?>">
                <INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDReturnTo<?php echo $rsRow1['itm_id'];?>" ID="FLDReturnTo<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['adjustment_positive'];?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
            <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDUnusable<?php echo $rsRow1['itm_id'];?>" ID="FLDUnusable<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['adjustment_negative'];?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id'];?>');"></TD>
            <TD class="sb1NormalFontArial"><INPUT autocomplete="off" CLASS="TextBoxSmallRA" TYPE="TEXT" NAME="FLDCBLA<?php echo $rsRow1['itm_id'];?>" ID="FLDCBLA<?php echo $rsRow1['itm_id'];?>" SIZE="8" MAXLENGTH="10" VALUE="<?php echo $rsRow2['closing_balance'];?>"  style="background-color:#CCC;" readonly>
                <INPUT TYPE="HIDDEN" NAME="FLDrecord_id<?php echo $rsRow1['itm_id'];?>" ID="FLDrecord_id<?php echo $rsRow1['itm_id'];?>" VALUE="<?php echo $rsRow2['w_id'];?>">
            </TD>
        </TR>
        <?php
        }
        else if ( $isNewRpt == 2 )
        {
		?>
        <TR>
            <TD  style="text-align:center !important;" CLASS="sb1NormalFontArial"><? echo $SlNo;?></TD>
            <TD width="100" CLASS="TDLCOLLASMALL" NOWRAP><span class="sb1GeenGradientBoxMiddle"><?php echo $rsRow1['itm_name'];?></span></TD>
            <TD width="100" CLASS="sb1NormalFontArial"><?php echo $rsRow2['opening_balance'];?></TD>
            <TD width="100" class="sb1NormalFontArial"><?php echo $rsRow2['received_balance'];?></TD>
            <TD width="100"CLASS="sb1NormalFontArial"><?php echo $rsRow2['issue_balance'];?></TD>
            <TD width="100" class="sb1NormalFontArial"><?php echo $rsRow2['adjustment_positive'];?></TD>
            <TD width="100" class="sb1NormalFontArial"><?php echo $rsRow2['adjustment_negative'];?></TD>
            <TD width="100" class="sb1NormalFontArial"><?php echo $rsRow2['closing_balance'];?></TD>
        </TR>
		<?php
		}
        $SlNo++;
        $fldIndex=$fldIndex+13;
    }
    mysql_free_result($rsTemp1);
    ?>
    <?php
    if ( $isNewRpt != 2 )
	{
	?>
        <TR>
            <TD colspan="10" align="right" CLASS="TDLCOLRASMALLW">
                <IMG SRC="<?php echo PLMIS_IMG; ?>CmdReset.gif" WIDTH="83" HEIGHT="21" BORDER="0" ALT="Reset" ONCLICK="document.frmF7.reset()" CLASS="Himg" style=" cursor:pointer">
                <INPUT TYPE="image" name="saveBtn" id="saveBtn" SRC="<?php echo PLMIS_IMG; ?>CmdSave.gif">
            </TD>
        </TR>
    <?php
	}
	?>
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
    <input type="hidden" name="redir_url" id="redir_url" value="<?php echo (isset($redirectURL)) ? $redirectURL : '';?>" />
</FORM>