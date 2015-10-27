<?php
include("../../html/adminhtml.inc.php");

function dateToDbFormat($date) {
    if (!empty($date)) {
        list($dd, $mm, $yy) = explode("/", $date);
        return $yy . "-" . $mm . "-" . $dd;
    }
}

if ( isset($_REQUEST['val']) )
{
	// Show provinces
	if ($_REQUEST['val'] == 'provincial' || $_REQUEST['val'] == 'district' || $_REQUEST['val'] == 'field')
	{
		if (isset($_REQUEST['pId']))
		{
			$sel_province = $_REQUEST['pId'];
		}else 
		{
			$sel_province = '';
		}
		
		$qry = "SELECT  PkLocID, LocName
				FROM    tbl_locations
				WHERE   LocLvl = 2 AND ParentID IS NOT NULL";
		$qryRes = mysql_query($qry);
		?>
        <label class="control-label">Province</label>
		<select name="province" id="province" class="form-control input-sm" onchange="showDistricts(this.value)" required>
			<option value="">-Select-</option>
		<?php
		while ( $row = mysql_fetch_array($qryRes) )
		{
		?>
			<option value="<?php echo $row['PkLocID'];?>" <?php echo ($sel_province == $row['PkLocID']) ? 'selected=selected' : ''?>><?php echo $row['LocName'];?></option>
		<?php
		}
		?>
		</select>
		<?php
	}
}

// Show districts
if (isset($_REQUEST['provinceId']))
{
	$sel_district = (isset($_REQUEST['dId'])) ? $sel_district = $_REQUEST['dId'] : '';
	$stkFilter = (isset($_REQUEST['stkId']) && !empty($_REQUEST['stkId']) && $_REQUEST['stkId'] != 'all') ? " AND tbl_warehouse.stkid = ".$_REQUEST['stkId']." " : '';
	
	$validate = ($_POST['validate'] == 'no') ? '' : 'required';
	$select = ($_POST['validate'] == 'no') ? 'All' : 'Select';
	
	$qry = "SELECT DISTINCT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_warehouse
			INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
			INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
			WHERE
				tbl_warehouse.prov_id = ".$_REQUEST['provinceId']."
			$stkFilter
			ORDER BY
				tbl_locations.LocName ASC";
	$qryRes = mysql_query($qry);
	?>
	<label class="control-label">District</label>
	<select name="district" id="district" class="form-control input-sm" <?php echo $validate;?>>
		<option value="" <?php echo ($sel_district == '') ? 'selected' : '';?>><?php echo $select;?></option>
	<?php
	while ( $row = mysql_fetch_array($qryRes) )
	{
	?>
		<option value="<?php echo $row['PkLocID'];?>" <?php echo ($sel_district == $row['PkLocID']) ? 'selected=selected' : ''?>><?php echo $row['LocName'];?></option>
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
	$select = ($_POST['validate'] == 'no') ? 'All' : 'Select';
	
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
	$sel = ($pro == '') ? 'selected' : '';
	echo "<option value='' $sel>$select</option>";
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

// Warehouses
if ( isset($_REQUEST['stkId']) && isset($_REQUEST['provId']) && isset($_REQUEST['distId']) )
{
	$whId = $_REQUEST['whId'];
	$distId = $_REQUEST['distId'];
	$provId = $_REQUEST['provId'];
	$stkId = $_REQUEST['stkId'];
	$product = $_REQUEST['product'];
	$dateFrom = dateToDbFormat($_REQUEST['dateFrom']);
	$dateTo = dateToDbFormat($_REQUEST['dateTo']);
	
	$where = '1=1';
	$where .= (!empty($distId)) ? " AND tbl_warehouse.dist_id = $distId" : '';
	$where .= (!empty($provId)) ? " AND tbl_warehouse.prov_id = $provId" : '';
	$where .= (!empty($stkId) && $stkId != 'all') ? " AND tbl_warehouse.stkid = $stkId" : '';
	$where .= (!empty($product) && $product != 'all') ? " AND stock_batch.item_id = $product" : '';
	$where .= (!empty($dateFrom) && !empty($dateTo)) ? " AND tbl_stock_master.TranDate BETWEEN '$dateFrom' AND '$dateTo' " : '';
	
	$qry = "SELECT DISTINCT
				tbl_warehouse.wh_id,
				CONCAT(tbl_warehouse.wh_name,	'(', stakeholder.stkname, ')') AS wh_name
			FROM
				tbl_warehouse
			INNER JOIN tbl_stock_master ON tbl_warehouse.wh_id = tbl_stock_master.WHIDTo
			INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
			INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
			INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
				$where
			ORDER BY
				tbl_warehouse.wh_name ASC";
	$qryRes = mysql_query($qry);
	echo '<option value="">Select</option>';
	echo '<option value="all">All</option>';
	while ($row = mysql_fetch_array($qryRes))
	{
		$sel = ($row['wh_id'] == $whId) ? 'selected="selected"' : '';
		echo "<option value=\"$row[wh_id]\" $sel>$row[wh_name]</option>";
	}
}


// Show HF types of the selected Province
if ( isset($_REQUEST['hfTypeId']) && $_REQUEST['provId'] )
{
	$hfType = $_REQUEST['hfTypeId'];
	$provId = $_REQUEST['provId'];
	$qry = "SELECT
				tbl_hf_type.pk_id,
				tbl_hf_type.hf_type
			FROM
				tbl_hf_type
			INNER JOIN tbl_hf_type_rank ON tbl_hf_type.pk_id = tbl_hf_type_rank.hf_type_id
			WHERE
				tbl_hf_type_rank.stakeholder_id = 1
			AND tbl_hf_type_rank.province_id = $provId
			ORDER BY
				tbl_hf_type_rank.hf_type_rank ASC";
	$qryRes = mysql_query($qry) or die();
	
	$sel = ($hfType == 0) ? 'selected="selected"' : '';
	echo "<option value=''>Select</option>";
	echo "<option value='0' $sel>All</option>";
	
	while ($row = mysql_fetch_array($qryRes))
	{
		if ($row['pk_id'] == $hfType)
			$sel = "selected='selected'";
		else
			$sel = "";
		?>
		<option value="<?php echo $row['pk_id']; ?>" <?php echo $sel; ?>><?php echo $row['hf_type']; ?></option>
		<?php
	}
}
// Show Satellite Camps for the Selected District
if ( isset($_REQUEST['campId']) && $_REQUEST['districtId'] )
{
	$campId = $_REQUEST['campId'];
	$districtId = $_REQUEST['districtId'];
	$qry = "SELECT
				A.wh_id,
				A.wh_name
			FROM
				(
					SELECT
						tbl_warehouse.wh_id,
						tbl_warehouse.wh_name,
						tbl_hf_type_rank.hf_type_rank,
						tbl_warehouse.wh_rank
					FROM
						tbl_warehouse
					INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
					INNER JOIN tbl_hf_type_rank ON tbl_warehouse.hf_type_id = tbl_hf_type_rank.hf_type_id
					WHERE
						stakeholder.lvl > 4
					AND tbl_warehouse.dist_id = $districtId
					AND tbl_hf_type_rank.stakeholder_id = 1
					AND tbl_warehouse.hf_type_id IN (1, 2)
				) A
			GROUP BY
				A.wh_id
			ORDER BY
				IF(A.wh_rank = '' OR A.wh_rank IS NULL, 1, 0),
				A.wh_rank,
				A.hf_type_rank ASC,
				A.wh_name ASC";
	$qryRes = mysql_query($qry) or die();
	?>
    <option value="">Select</option>
    <option value="all" <?php echo ($campId == 'all') ? 'selected="selected"' : '';?>>All (FWCs & MSUs)</option>
    <optgroup label="Type wise">
        <option value="fwc" <?php echo ($campId == 'fwc') ? 'selected="selected"' : '';?>>All FWCs</option>
        <option value="msu" <?php echo ($campId == 'msu') ? 'selected="selected"' : '';?>>All MSUs</option>
    </optgroup>
    <optgroup label="Individual Satellite Camp">
	<?php
	while ($row = mysql_fetch_array($qryRes))
	{
		if ($row['wh_id'] == $campId)
			$sel = "selected='selected'";
		else
			$sel = "";
		?>
		<option value="<?php echo $row['wh_id']; ?>" <?php echo $sel; ?>><?php echo $row['wh_name']; ?></option>
		<?php
	}?>
    </optgroup>
	<?php
}