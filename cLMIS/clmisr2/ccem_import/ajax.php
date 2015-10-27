<?php
include('config.php');

// Show districts
$type =  isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
if ( isset($_REQUEST['provId']) && $type == 'old' )
{
	$provId = $_REQUEST['provId'];
	$distId = $_REQUEST['distId'];
	$qry = "SELECT DISTINCT
				locations.pk_id,
				locations.location_name
			FROM
				import_facilities
			INNER JOIN locations ON import_facilities.ft_level3 = locations.location_name
			INNER JOIN pilot_districts ON pilot_districts.district_id = locations.pk_id
			WHERE
				locations.geo_level_id = 4
			AND locations.pk_id IN (
				SELECT DISTINCT
					warehouses.district_id
				FROM
					cold_chain
				INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			)
			AND locations.province_id = $provId
			ORDER BY
				locations.location_name ASC";
	$qryRes = mysql_query($qry);
	if (mysql_num_rows(mysql_query($qry)) > 0)
	{
		while ( $row = mysql_fetch_array($qryRes) )
		{
			$sel = ($distId == $row['pk_id']) ? 'selected' : '';
			echo "<option value='".$row['pk_id']."' $sel>".$row['location_name']."</option>";
		}
	}
	else
	{
		echo "<option value='0'>Select</option>";
	}
}
if ( isset($_REQUEST['provId']) && $type == 'new' )
{
	$provId = $_REQUEST['provId'];
	$distId = $_REQUEST['distId'];
	$qry = "SELECT DISTINCT
				locations.pk_id,
				locations.location_name
			FROM
				import_facilities
			INNER JOIN locations ON import_facilities.ft_level3 = locations.location_name
			INNER JOIN pilot_districts ON pilot_districts.district_id = locations.pk_id
			WHERE
				locations.geo_level_id = 4
			AND locations.pk_id NOT IN (
				SELECT DISTINCT
					warehouses.district_id
				FROM
					cold_chain
				INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
				INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
			)
			AND locations.province_id = $provId
			ORDER BY
				locations.location_name ASC";
	$qryRes = mysql_query($qry);
	if (mysql_num_rows(mysql_query($qry)) > 0)
	{
		while ( $row = mysql_fetch_array($qryRes) )
		{
			$sel = ($distId == $row['pk_id']) ? 'selected' : '';
			echo "<option value='".$row['pk_id']."' $sel>".$row['location_name']."</option>";
		}
	}
	else
	{
		echo "<option value='0'>Select</option>";
	}
}

// Update CCEM ID
if ( isset($_REQUEST['lmisId']) && isset($_REQUEST['ccemId']) )
{
	if (!empty($_REQUEST['lmisId']))
	{
		$qry = "UPDATE warehouses SET ccem_id = '".$_REQUEST['ccemId']."' WHERE pk_id = ".$_REQUEST['lmisId'];
	}
	else
	{
		$qry = "UPDATE warehouses SET ccem_id = NULL WHERE ccem_id = '".$_REQUEST['ccemId']."' ";
	}
	mysql_query($qry);
}

// Update CCEM District Name
if ( isset($_REQUEST['lmiDistName']) && isset($_REQUEST['ccemDistName']) )
{
	$qry = "UPDATE import_facilities SET ft_level3 = '".$_REQUEST['lmiDistName']."' WHERE ft_level3 = '".$_REQUEST['ccemDistName']."' ";
	mysql_query($qry);
	
	$qry = "UPDATE tbl_admin_areas SET ft_level3 = '".$_REQUEST['lmiDistName']."' WHERE ft_level3 = '".$_REQUEST['ccemDistName']."' ";
	mysql_query($qry);
}