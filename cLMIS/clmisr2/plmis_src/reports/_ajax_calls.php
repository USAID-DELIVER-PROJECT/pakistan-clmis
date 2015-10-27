<?php
include("../../html/adminhtml.inc.php");

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
		<span class="sb1NormalFont">Province:</span>
		<select name="province" id="province" class="input_select" onchange="showDistricts(this.value)" required>
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

	if (isset($_REQUEST['dId']))
	{
		$sel_district = $_REQUEST['dId'];
	}else 
	{
		$sel_district = '';
	}
//print $sel_district."---".$_REQUEST['provinceId'];

	$qry = "SELECT
				tbl_locations.PkLocID,
				tbl_locations.LocName
			FROM
				tbl_locations
			WHERE tbl_locations.LocLvl = 3 AND tbl_locations.ParentID = '".$_REQUEST['provinceId']."'
			ORDER BY tbl_locations.LocName";
	$qryRes = mysql_query($qry);
	?>
	<span class="sb1NormalFont">District:</span>
	<select name="district" id="district" class="input_select" required>
		<option value="">-Select-</option>
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

?>