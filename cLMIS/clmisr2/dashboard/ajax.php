<?php
include("../html/adminhtml.inc.php");
Login();

$level = $_POST['lvl'];
$provId = isset($_POST['provId']) ? $_POST['provId'] : '';
$distId = isset($_POST['distId']) ? $_POST['distId'] : '';

if ($level == 2)
{
	$qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_locations
			WHERE
				tbl_locations.LocLvl = 2
			AND tbl_locations.ParentID IS NOT NULL";
	$qryRes = mysql_query($qry);
	?>
	<label for="office-Level">Province</label>
        <div class="form-group">
            <select name="prov_id" id="prov_id" class="form-control input-sm" onchange="showDistricts()">
		<?php
        while ( $row = mysql_fetch_array($qryRes) )
        {
			$sel = ($provId == $row['PkLocID']) ? 'selected="selected"' : '';
            echo "<option value=\"$row[PkLocID]\" $sel>$row[LocName]</option>";
        }
        ?>
	
        </select>
    </div>
	<?php
}

if ($level == 3)
{
	$prov_id = $_POST['prov_id'];
	$qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_locations
			WHERE
				tbl_locations.ParentID = $prov_id
			ORDER BY
				tbl_locations.LocName ASC";
	$qryRes = mysql_query($qry);
	?>
	<label for="office-Level">Distict</label>
        <div class="form-group">
            <select name="dist_id" id="dist_id" class="form-control input-sm">
		<?php
        while ( $row = mysql_fetch_array($qryRes) )
        {
			$sel = ($distId == $row['PkLocID']) ? 'selected="selected"' : '';
            echo "<option value=\"$row[PkLocID]\" $sel>$row[LocName]</option>";
        }
        ?>
	
        </select>
    </div>
	<?php
}
// Stock Status
if ( $_REQUEST['stockStatus'] )
{
	$param = explode('|', base64_decode(mysql_real_escape_string($_REQUEST['stockStatus'])));
	$itemId = $param[0];
	$stkid = $param[1];
	$rptDate = $param[2];
	$type = $param[3];
	$lvl = $param[4];
	$where = '';
	
	if ( $lvl == 1 )
	{
		$level = 'All Pakistan Districts';
	}
	else if ( $lvl == 2 )
	{
		$prov_id = $param[5];
		$prov = mysql_fetch_array(mysql_query("SELECT
													tbl_locations.LocName
												FROM
													tbl_locations
												WHERE
													tbl_locations.PkLocID = $prov_id"));
		$provName = $prov['LocName'];
		$level = "$provName Districts";
		$where .= " AND summary_district.province_id = $prov_id";
	}
	else if ( $lvl == 3 )
	{
		$dist_id = $param[6];
		$dist = mysql_fetch_array(mysql_query("SELECT
													tbl_locations.LocName
												FROM
													tbl_locations
												WHERE
													tbl_locations.PkLocID = $dist_id"));
		$distName = $dist['LocName'];
		$level = "District $distName";
		$where .= " AND summary_district.district_id = $dist_id";
	}
	
	$gType = (!empty($param[7])) ? $param[7] : '';
	if ( $gType == 'field' )
	{
		if ( $type == 'SO' )
		{
			$title = 'Stock Out ';
			$qry = "SELECT * FROM (SELECT
						summary_district.district_id,
						tbl_locations.LocName AS districtName,
						ROUND(((summary_district.soh_district_lvl - summary_district.soh_district_store) / summary_district.avg_consumption), 2) AS MOS
					FROM
						summary_district
					INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
					INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
					WHERE
						summary_district.reporting_date = '$rptDate'
					AND summary_district.stakeholder_id = $stkid
					AND summary_district.item_id = '$itemId'
					$where
					GROUP BY
						summary_district.item_id,
						summary_district.district_id
					) A
					WHERE A.MOS <= REPgetMOSScale('$itemId', $stkid, 4, 'SO', 'E')
					ORDER BY A.districtName";
		}
		else if ( $type == 'OS' )
		{
			$title = 'Over Stock ';
			$qry = "SELECT * FROM (SELECT
						summary_district.district_id,
						tbl_locations.LocName AS districtName,
						ROUND(((summary_district.soh_district_lvl - summary_district.soh_district_store) / summary_district.avg_consumption), 2) AS MOS
					FROM
						summary_district
					INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
					INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
					$where
					WHERE
						summary_district.reporting_date = '$rptDate'
					AND summary_district.stakeholder_id = $stkid
					AND summary_district.item_id = '$itemId'
					GROUP BY
						summary_district.item_id,
						summary_district.district_id
					) A
					WHERE A.MOS >= REPgetMOSScale('$itemId', $stkid, 4, 'OS', 'S')
					ORDER BY A.districtName";
		}
	}
	else
	{
		if ( $type == 'SO' )
		{
			$title = 'Stock Out ';
			$qry = "SELECT * FROM (SELECT
						summary_district.district_id,
						tbl_locations.LocName AS districtName,
						ROUND((summary_district.soh_district_store / summary_district.avg_consumption), 2) AS MOS
					FROM
						summary_district
					INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
					INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
					WHERE
						summary_district.reporting_date = '$rptDate'
					AND summary_district.stakeholder_id = $stkid
					AND summary_district.item_id = '$itemId'
					$where
					GROUP BY
						summary_district.item_id,
						summary_district.district_id
					) A
					WHERE A.MOS <= REPgetMOSScale('$itemId', $stkid, 3, 'SO', 'E')
					ORDER BY A.districtName";
		}
		else if ( $type == 'OS' )
		{
			$title = 'Over Stock ';
			$qry = "SELECT * FROM (SELECT
						summary_district.district_id,
						tbl_locations.LocName AS districtName,
						ROUND((summary_district.soh_district_store / summary_district.avg_consumption), 2) AS MOS
					FROM
						summary_district
					INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
					INNER JOIN tbl_locations ON summary_district.district_id = tbl_locations.PkLocID
					$where
					WHERE
						summary_district.reporting_date = '$rptDate'
					AND summary_district.stakeholder_id = $stkid
					AND summary_district.item_id = '$itemId'
					GROUP BY
						summary_district.item_id,
						summary_district.district_id
					) A
					WHERE A.MOS >= REPgetMOSScale('$itemId', $stkid, 3, 'OS', 'S')
					ORDER BY A.districtName";
		}
	}
	
	$qryRes = mysql_query($qry);
	echo '<b>'.$title . ' '. $level.'</b>';
	?>
	<table class="table table-striped table-bordered table-condensed dataTable" style="margin-bottom:10px;">
    	<thead>
        	<tr>
            	<th width="10%">Sr. No.</th>
            	<th>District</th>
            	<th width="15%">MOS</th>
            </tr>
        </thead>
        <tbody>
		<?php
		$count = 1;
        while ( $row = mysql_fetch_array($qryRes) )
        {
        ?>
        	<tr>
            	<td class="center"><?php echo $count++;?></td>
            	<td><?php echo $row['districtName'];?></td>
            	<td class="right"><?php echo $row['MOS'];?></td>
            </tr>
		<?php
        }
        ?>
        </tbody>
    </table>
    * MOS - Month of Stock
	<?php
	
}