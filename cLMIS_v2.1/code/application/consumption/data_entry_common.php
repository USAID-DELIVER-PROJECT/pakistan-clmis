<?php
/**
 * data_entry_common
 * @package consumption
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
?>
<form name="frmF7" id="frmF7" method="post">
    <div class="row">
        <div class="col-md-12">
            <div id="errMsg"></div>
            <table class="table table-bordered">
                <tr>
                    <th rowspan="2" class="text-center">S.No.</th>
                    <th rowspan="2" class="text-center">Article</th>
                    <th rowspan="2" class="text-center">Opening balance</th>
                    <th rowspan="2" class="text-center">Received</th>
                    <th rowspan="2" class="text-center">Sold</th>
                    <th colspan="2" class="text-center">Adjustments</th>
                    <th rowspan="2" class="text-center">Closing Balance</th>
                </tr>
                <tr>
                    <th class="text-center">(+)</th>
                    <th class="text-center">(-)</th>
                </tr>
                <?php
                //query
                //gets
                //all from itminfo_tab
                $rsTemp1 = mysql_query("SELECT * FROM `itminfo_tab` WHERE `itm_status`=1 AND `itm_id` IN (SELECT `Stk_item` FROM `stakeholder_item` WHERE `stkid` =$stkid) ORDER BY `frmindex`");
                $SlNo = 1;
                $fldIndex = 0;
                //loop
                while ($rsRow1 = mysql_fetch_array($rsTemp1)) {
                    $SlNo = ((strlen($SlNo) < 2) ? $SlNo : $SlNo);
                    //query 
                    //gets
                    //all from tbl_hf_data
                    $qry = "SELECT * FROM tbl_hf_data WHERE `warehouse_id`='" . $wh_id . "' AND reporting_date='" . $PrevMonthDate . "' AND `item_id`='$rsRow1[itm_id]'";
                    //result
                    $rsTemp3 = mysql_query($qry);
                    $rsRow2 = mysql_fetch_array($rsTemp3);

                    $add_date = $rsRow2['created_date'];

                    // if new report
                    if ($isNewRpt == 1) {
                        $wh_issue_up = 0;
                        $wh_adja = 0;
                        $wh_adjb = 0;
                        $wh_received = 0;
                        //ob_a
                        $ob_a = $rsRow2['closing_balance'];
                        //cb_a
                        $cb_a = $rsRow2['closing_balance'];
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $SlNo; ?></td>
                            <td><?php echo $rsRow1['itm_name']; ?>
                                <input type="hidden" name="flitmrec_id[]" value="<?php echo $rsRow1['itm_id']; ?>">
                                <input type="hidden" name="flitm_category[]" value="<?php echo $rsRow1['itm_category']; ?>">
                                <input type="hidden" name="flitmname<?php echo $rsRow1['itm_id']; ?>" value="<?php echo $rsRow1['itm_name']; ?>"></td>
                            <td><input class="form-control input-sm text-right" <?php echo (!empty($openOB)) ? 'readonly="readonly"' : ''; ?> autocomplete="off"  type="text" name="FLDOBLA<?php echo $rsRow1['itm_id']; ?>" id="FLDOBLA<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $ob_a; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" <?php echo $isReadOnly . $style; ?> autocomplete="off"  type="text" name="FLDRecv<?php echo $rsRow1['itm_id']; ?>" id="FLDRecv<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10"  value="<?php echo $wh_received; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" name="FLDIsuueUP<?php echo $rsRow1['itm_id']; ?>" id="FLDIsuueUP<?php echo $rsRow1['itm_id']; ?>" value="<?php echo $wh_issue_up; ?>" type="text"  size="8" maxlength="10" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" type="text" name="FLDReturnTo<?php echo $rsRow1['itm_id']; ?>" id="FLDReturnTo<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $wh_adja; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" type="text" name="FLDUnusable<?php echo $rsRow1['itm_id']; ?>" id="FLDUnusable<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $wh_adjb; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" type="text" name="FLDCBLA<?php echo $rsRow1['itm_id']; ?>" id="FLDCBLA<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $cb_a; ?>" readonly="readonly"></td>
                        </tr>
        <?php //isNewRpt == 0
    } else if ($isNewRpt == 0) {
        ?>
                        <tr>
                            <td class="text-center"><?php echo $SlNo; ?></td>
                            <td><?php echo $rsRow1['itm_name']; ?>
                                <input type="hidden" name="flitmrec_id[]" value="<?php echo $rsRow1['itm_id']; ?>">
                                <input type="hidden" name="flitm_category[]" value="<?php echo $rsRow1['itm_category']; ?>">
                                <input type="hidden" name="flitmname<?php echo $rsRow1['itm_id']; ?>" value="<?php echo $rsRow1['itm_name']; ?>"></td>
                            <td><input class="form-control input-sm text-right" <?php echo (!empty($openOB)) ? 'readonly="readonly"' : ''; ?> autocomplete="off"  type="text" name="FLDOBLA<?php echo $rsRow1['itm_id']; ?>" id="FLDOBLA<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $rsRow2['opening_balance']; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" <?php echo $isReadOnly . $style; ?> autocomplete="off"  type="text" name="FLDRecv<?php echo $rsRow1['itm_id']; ?>" id="FLDRecv<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10"  value="<?php echo $rsRow2['received_balance']; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off" name="FLDIsuueUP<?php echo $rsRow1['itm_id']; ?>" id="FLDIsuueUP<?php echo $rsRow1['itm_id']; ?>" value="<?php echo $rsRow2['issue_balance']; ?>" type="text" size="8" maxlength="10" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off"  type="text" name="FLDReturnTo<?php echo $rsRow1['itm_id']; ?>" id="FLDReturnTo<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $rsRow2['adjustment_positive']; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off"  type="text" name="FLDUnusable<?php echo $rsRow1['itm_id']; ?>" id="FLDUnusable<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $rsRow2['adjustment_negative']; ?>" onKeyUp="cal_balance('<?php echo $rsRow1['itm_id']; ?>');"></td>
                            <td><input class="form-control input-sm text-right" autocomplete="off"  type="text" name="FLDCBLA<?php echo $rsRow1['itm_id']; ?>" id="FLDCBLA<?php echo $rsRow1['itm_id']; ?>" size="8" maxlength="10" value="<?php echo $rsRow2['closing_balance']; ?>" readonly="readonly"></td>
                        </tr>
        <?php
        //isNewRpt == 2
    } else if ($isNewRpt == 2) {
        ?>
                        <tr>
                            <td class="text-center"><?php echo $SlNo; ?></td>
                            <td><?php echo $rsRow1['itm_name']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['opening_balance']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['received_balance']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['issue_balance']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['adjustment_positive']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['adjustment_negative']; ?></td>
                            <td class="text-right"><?php echo $rsRow2['closing_balance']; ?></td>
                        </tr>
        <?php
    }
    $SlNo++;
    $fldIndex = $fldIndex + 13;
}
//free result
mysql_free_result($rsTemp1);
?>
            </table>
        </div>
    </div>
                <?php
                //isNewRpt != 2
                if ($isNewRpt != 2) {
                    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-10 text-right" style="padding-top: 10px">
                    <div id="eMsg" style="color:#060;"></div>
                </div>
                <div class="col-md-2 text-right">
                    <button class="btn btn-primary" id="saveBtn" name="saveBtn" type="button" onclick="return formvalidate1()"> Save </button>
                    <button class="btn btn-info" type="submit" onclick="document.frmF7.reset()"> Reset </button>
                </div>
            </div>
        </div>
    <?php
}
//Hidden
?>

    <input type="hidden" name="ActionType" value="Add">
    <input type="hidden" name="RptDate" value="<?php echo $RptDate; ?>">
    <input type="hidden" name="wh_id" value="<?php echo $wh_id; ?>">
    <input type="hidden" name="yy" value="<?php echo $yy; ?>">
    <input type="hidden" name="mm" value="<?php echo $mm; ?>">
    <input type="hidden" name="isNewRpt" id="isNewRpt" value="<?php echo $isNewRpt; ?>" />
    <input type="hidden" name="add_date" id="add_date" value="<?php echo $add_date; ?>" />
    <input type="hidden" name="redir_url" id="redir_url" value="<?php echo (isset($redirectURL)) ? $redirectURL : ''; ?>" />
</form>
