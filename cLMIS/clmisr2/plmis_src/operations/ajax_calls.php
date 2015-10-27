<?php
include("../../html/adminhtml.inc.php");

// Show provinces
if (isset($_REQUEST['provId']) && isset($_REQUEST['stkId']))
{
	$provId = $_REQUEST['provId'];
	$stkId = $_REQUEST['stkId'];
	$distId = $_REQUEST['distId'];
	
	$qry = "SELECT DISTINCT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_locations
			INNER JOIN tbl_warehouse ON tbl_locations.PkLocID = tbl_warehouse.dist_id
			INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
			WHERE
				tbl_locations.LocLvl = 3
			AND tbl_locations.ParentID = $provId
			AND tbl_warehouse.stkid = $stkId
			ORDER BY
				tbl_locations.LocName ASC";
	$qryRes = mysql_query($qry);
	?>
	<label class="control-label">District</label>
	<select name="district" id="district" class="form-control input-sm">
		<option value="">All</option>
	<?php
	while ( $row = mysql_fetch_array($qryRes) )
	{
	?>
		<option value="<?php echo $row['PkLocID'];?>" <?php echo ($distId == $row['PkLocID']) ? 'selected=selected' : ''?>><?php echo $row['LocName'];?></option>
	<?php
	}
	?>
	</select>
<?php
}else 
{
	$sel_province = '';
}

// Show Stores/Facilities
if (isset($_REQUEST['distId']) && !empty($_REQUEST['distId']) && isset($_REQUEST['whId']))
{
	$whId = (!empty($_REQUEST['whId'])) ? $_REQUEST['whId'] : '';
	$stkId = $_REQUEST['stkId'];
	$distId = $_REQUEST['distId'];
	$qry  = "SELECT
					*
				FROM
					(
						SELECT
							tbl_warehouse.wh_id,
							tbl_warehouse.wh_name,
							stakeholder.lvl,
							tbl_hf_type_rank.hf_type_rank,
							tbl_warehouse.wh_rank
						FROM
							wh_user
						INNER JOIN tbl_warehouse ON wh_user.wh_id = tbl_warehouse.wh_id
						INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
						LEFT JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
						AND tbl_warehouse.prov_id = tbl_hf_type_rank.province_id
						AND tbl_warehouse.stkid = tbl_hf_type_rank.stakeholder_id
						WHERE
							tbl_warehouse.dist_id = ".$distId."
						AND tbl_warehouse.stkid = ".$stkId."
					) A
				GROUP BY
					A.wh_id
				ORDER BY
					IF (A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
					A.wh_rank,
					IF (A.hf_type_rank = '' OR A.hf_type_rank IS NULL, 1, 0),
					A.hf_type_rank ASC,
					A.wh_name ASC";
	$qryRes = mysql_query($qry);
	$num = mysql_num_rows($qryRes);
	?>
    <label class="control-label">Store/Facility</label>
	<select name="warehouse" id="warehouse" class="form-control input-sm">
    	<option value="">All</option>
	<?php
	while ( $row = mysql_fetch_array($qryRes) )
	{
	?>
		<option value="<?php echo $row['wh_id'];?>" <?php echo ($whId == $row['wh_id']) ? 'selected=selected' : ''?>><?php echo $row['wh_name'];?></option>
	<?php
	}
	?>
	</select>
	<?php
}

if (isset($_REQUEST['stakeholder']))
{
	$stk = $_REQUEST['stakeholder'];
	$pro = $_REQUEST['productId'];
	
	if (!empty($stk) && $stk != 'all'){
		$stkFilter = " AND stakeholder_item.stkid = $stk";
	}else if (empty($stk)){
		$stkFilter = " AND stakeholder_item.stkid = 0";
	}
	
	$querypro = "SELECT DISTINCT
					itminfo_tab.itmrec_id,
					itminfo_tab.itm_id,
					itminfo_tab.itm_name
				FROM
					itminfo_tab
				INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
				WHERE
					itminfo_tab.itm_status = 'Current'
				$stkFilter
				AND itminfo_tab.itm_category = 1
				ORDER BY
					itminfo_tab.frmindex ASC";
	$rspro = mysql_query($querypro) or die();
	echo '<option value="">Select</option>';
	while ($rowpro = mysql_fetch_array($rspro))
	{
		if ($rowpro['itmrec_id'] == $pro)
			$sel = "selected='selected'";
		else
			$sel = "";
		?>
		<option value="<?php echo $rowpro['itmrec_id']; ?>" <?php echo $sel; ?>><?php echo $rowpro['itm_name']; ?></option>
		<?php
	}
}
?>