<?php
// retrieve values from values string

$vid = $tmp[0];
if ($vid != 'UNK') {
$vid = number_format($tmp[0] / PLMIS_CBL_UNIT, 0);
}

$vamc = $tmp[1];
if ($vamc != 'UNK') {
$vamc = number_format($tmp[1] / PLMIS_CBL_UNIT, 1);
}

$vcb = $tmp[2];
if ($vcb != 'UNK') {
$vcb = number_format($tmp[2] / PLMIS_CBL_UNIT, 0);
}

$vmos = $tmp[3];
if ($vmos != 'UNK') {
  $vmos = number_format($vmos / PLMIS_CBL_UNIT, 1);
}

$vcyp = $tmp[4];
if ($vcyp != 'UNK') {
$vcyp = number_format($tmp[4] / PLMIS_CBL_UNIT, 1);
}

$rs_mos = mysql_query("SELECT getMosColor('" . $vmos . "','" . $sel_item . "'," . $sel_stk . "," . $sel_lvl . ")");
$bgcolor = mysql_result($rs_mos, 0, 0);
?>
   <td class="sb1NormalFontArial" align="center"><?php echo $vid;?></td>
   <td class="sb1NormalFontArial" align="right" style="padding-right:10px"><?php echo $vamc;?></td>
   <td class="sb1NormalFontArial" align="center"><?php echo $vcb;?></td>
   <td class="sb1NormalFontArial" align="center"><?php echo $vmos;?></td>
   <td class="sb1NormalFontArial" align="center">
   <div style="width:10px; height:12px; background-color:<?php echo $bgcolor;?>">&nbsp;</div><?php ?></td>
   <td width="218" align="center" colspan="2" class="sb1NormalFontArial" style="padding-right:5px;"><?php echo $vcyp;?></td>
   <td>&nbsp;</td>
