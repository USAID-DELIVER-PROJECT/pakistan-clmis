<?php
if ( date('d') > 10 )
{
	$date = date('Y-m', strtotime("-1 month", strtotime(date('Y-m-d'))));
}
else
{
	$date = date('Y-m', strtotime("-2 month", strtotime(date('Y-m-d'))));
}
$sel_month = date('m', strtotime($date));
$sel_year = date('Y', strtotime($date));


include "../../plmis_inc/common/top_im.php"; ?>
<table width="100%">
    <tr>
        <td class="col-md-2"><label class="control-label">Year</label>
            <select name="year_sel" id="year_sel" class="form-control input-sm">
                <?php                                   
                                  for ($j = date('Y'); $j >= 2010; $j--) {
                                    if ($sel_year == $j)
                                      $sel = "selected='selected'";
                                    else
                                      if ($j == date("Y"))
                                        $sel = "selected='selected'";
                                      else
                                        $sel = "";
                                    ?>
                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j; ?></option>
                <?php
                                  }
                                  ?>
            </select></td>
        <td class="col-md-2"><label class="control-label">Sector</label>
            <?php $rowsec = '0' ?>
            <select name="sector" class="form-control input-sm" id="sector">
            <?php
                                            $qry = "SELECT
                                                         stakeholder_type.stk_type_descr,
                                                         stakeholder_type.stk_type_id
                                                                FROM
                                                          stakeholder_type
                                                          WHERE stakeholder_type.stk_type_id NOT IN(2,3)";
                                            $qryRes = mysql_query($qry);
                                    while ($row = mysql_fetch_array($qryRes)) {
                                            $sel = ($rowsec == $row['stk_type_id']) ? 'selected="selected"' : '';
                            echo "<option value=\"$row[stk_type_id]\" $sel>$row[stk_type_descr]</option>";
                                                }
                                         ?>
            </select></td>
        <td class="col-md-2"><label class="control-label">Stakeholder</label>
            <select name="stk_sel" id="stk_sel" class="form-control input-sm">
            </select></td>
        <td class="col-md-2"><label class="control-label">Province/Region</label>
    <?php 
                     $sel_prov = $_SESSION["prov_id"];
                     if($sel_prov == "10"){
                         $sel_prov = "all";
                     }
                     ?>
    <select name="prov_sel" id="prov_sel" class="form-control input-sm">
        <option value="all">All</option>
        <?php
                              $queryprov = "SELECT tbl_locations.PkLocID as prov_id, tbl_locations.LocName as prov_title
                    FROM tbl_locations where LocLvl=2 and parentid is not null";
                              $rsprov = mysql_query($queryprov) or die();
                              while ($rowprov = mysql_fetch_array($rsprov)) {
                                if ($sel_prov == $rowprov['prov_id'])
                                  $sel = "selected='selected'";
                                else
                                  $sel = "";
                                ?>
        <option value="<?php echo $rowprov['prov_id']; ?>" <?php echo $sel; ?>><?php echo $rowprov['prov_title']; ?></option>
        <?php
                              }
                              ?>
            
                     </select>
            </td>
        <td class="col-md-2"><label class="control-label">Product</label>
        <select name="prod_sel" id="prod_sel" class="form-control input-sm">
</select>
    </td>
        <td class="col-md-2"><label class="control-label">Type</label>
			<select name="type_sel" id="type_sel" class="form-control input-sm">
			    <option value="C">Consumption</option>
			    <option value="A">Avg.Monthly Consumption</option>
			</select>
		    </td>
        <td class="col-md-2"><input type="submit" name="go" id="submit" value="GO" onclick="getData()" class="btn btn-primary input-sm" style="margin-top:28px;" /></td>
    </tr>
</table>

<div id="slider"></div>