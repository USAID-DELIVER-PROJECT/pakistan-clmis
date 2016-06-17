<?php
include("../application/includes/classes/Configuration.inc.php");
include(APP_PATH."includes/classes/db.php");

// Show users
if($_POST['level'])
{
	// Get variable values
	$district = mysql_real_escape_string($_POST['district']);
	$level = mysql_real_escape_string($_POST['level']);
	$province = mysql_real_escape_string($_POST['province']);
	$stakeholder = mysql_real_escape_string($_POST['stakeholder']);
	$and = '';
	if(!empty($stakeholder)){
		$and .= " AND tbl_warehouse.stkid = $stakeholder";
	}
	if($level != 1 && !empty($province)){
		$and .= " AND tbl_warehouse.prov_id = $province";
	}
	if(!empty($district)){
		$and .= " AND tbl_warehouse.dist_id = $district";
	}
	
	$qry = "SELECT DISTINCT
				sysuser_tab.usrlogin_id,
				sysuser_tab.sysusr_pwd,
				tbl_warehouse.stkid,
				mainStk.stkname
			FROM
				tbl_warehouse
			INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
			INNER JOIN sysuser_tab ON wh_user.sysusrrec_id = sysuser_tab.UserID
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			INNER JOIN stakeholder AS mainStk ON tbl_warehouse.stkid = mainStk.stkid
			WHERE
				stakeholder.lvl = $level
			$and
			ORDER BY
				stakeholder.stkorder ASC,
				tbl_locations.LocName ASC";
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows(mysql_query($qry));
	if($num > 0)
	{
	?>
    <table class="table table-bordered table-condensed" style="width:40%">
    	<thead>
        	<tr>
            	<th class="text-center">Sr. No.</th>
                <th>Username</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
	<?php
	$counter = 1;
	$stakeholder = '';
	while ( $row = mysql_fetch_array($qryRes) )
	{
		if($row['stkname'] != $stakeholder)
		{
			$counter = 1;
			echo "<tr bgcolor=\"#D8E6FD\">";
				echo "<th colspan=\"3\">".$row['stkname']."</th>";
			echo "</tr>";	
		}
		echo "<tr>";
			echo "<td class=\"text-center\" width=\"60\">".$counter++."</td>";
			echo "<td>".$row['usrlogin_id']."</td>";
			echo "<td>".base64_decode($row['sysusr_pwd'])."</td>";
		echo "</tr>";
		$stakeholder = $row['stkname'];
	}
	?>
    	</tbody>
    </table>
	<?php
	}
	else
	{
		echo "No record found";
	}
}


// Show districts
if (isset($_REQUEST['provinceId'])) {
    $qry = "SELECT DISTINCT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_warehouse
			INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			WHERE
				tbl_warehouse.prov_id = " . $_REQUEST['provinceId'] . "
			$stkFilter
			ORDER BY
				tbl_locations.LocName ASC";

    $qryRes = mysql_query($qry);
    ?>
    <label class="control-label">District</label>
    <select name="district" id="district" class="form-control input-sm" <?php echo $validate; ?>>
        <option value="">All</option>
        <?php
        $sel = ($sel_district == 'all') ? 'selected' : '';
        echo (isset($_POST['allOpt']) && $_POST['allOpt'] == 'yes') ? "<option value='all' $sel>All</option>" : '';
        ?>
        <?php
        while ($row = mysql_fetch_array($qryRes)) {
            ?>
            <option value="<?php echo $row['PkLocID']; ?>" <?php echo ($sel_district == $row['PkLocID']) ? 'selected=selected' : '' ?>><?php echo $row['LocName']; ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}