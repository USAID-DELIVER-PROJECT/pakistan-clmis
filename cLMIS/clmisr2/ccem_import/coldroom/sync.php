<?php
include('../config.php');

// Select distinct Makes
$qry = "SELECT
			ccm_models.pk_id,
			ccm_models.ccm_make_id,
			ccm_models.ccm_model_name,
			ccm_models.gross_capacity_20,
			ccm_models.gross_capacity_4,
			ccm_models.net_capacity_20,
			ccm_models.net_capacity_4,
			cold_chain.warehouse_id
		FROM
			ccm_models
		INNER JOIN cold_chain ON ccm_models.pk_id = cold_chain.ccm_model_id
		WHERE
			ccm_models.ccm_make_id IN (
				SELECT DISTINCT
					import_cold_room.MakeID
				FROM
					import_cold_room
			)
		AND ccm_models.ccm_asset_type_id = 3
		ORDER BY
			ccm_models.ccm_model_name ASC";
$rows = mysql_query($qry);
while( $row = mysql_fetch_array($rows) )
{
	$models[$row['pk_id']]['whId'] = $row['warehouse_id'];
	$models[$row['pk_id']]['make'] = $row['ccm_make_id'];
	$models[$row['pk_id']]['model'] = $row['ccm_model_name'];
	$models[$row['pk_id']]['gross4'] = $row['gross_capacity_4'];
	$models[$row['pk_id']]['gross20'] = $row['gross_capacity_20'];
	$models[$row['pk_id']]['net4'] = $row['net_capacity_4'];
	$models[$row['pk_id']]['net20'] = $row['net_capacity_20'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Make</title>
<link href="style.css" type="text/css" rel="stylesheet" />

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
	function updateFunc(lmisName, ccemName)
	{		
		if ( lmisName != '' && ccemName != '' && lmisName != '-1' )
		{
			$.ajax({
				url: 'update_make.php',
				data: {lmisName: lmisName, ccemName: ccemName},
				type: 'POST'
			})
		}
		else if ( lmisName == '-1' )
		{
			window.open('add_make.php?ccmName='+ccemName, '_blank', 'scrollbars=1,width=400,height=200');
		}
	}
</script>
</head>

<body>
	<h3>Add/Update Assets</h3>
    <table id="myTable" cellpadding="3" width="100%">
        <thead>
            <tr>
                <th rowspan="2">Sr. No.</th>
                <th rowspan="2">Warehouse</th>
                <th rowspan="2">Asset Type</th>
                <th rowspan="2">Make</th>
                <th rowspan="2">Model</th>
                <th colspan="2">Capacity 4&deg;</th>
                <th colspan="2">Capacity 20&deg;</th>
                <th rowspan="2">Action</th>
            </tr>
            <tr>
            	<th>Net</th>
            	<th>Gross</th>
            	<th>Net</th>
            	<th>Gross</th>
            </tr>
        </thead>
        <tbody>
        <?php
		$ccmQry = "SELECT
					warehouses.pk_id,
					warehouses.warehouse_name,
					ccm_asset_types.asset_type_name,
					import_cold_room.MakeID,
					ccm_makes.ccm_make_name,
					import_cold_room.ft_model_name,
					import_cold_room.fn_gross_volume_4deg AS gross4,
					import_cold_room.fn_net_volume_4deg AS net4,
					import_cold_room.fn_gross_volume_20deg AS gross20,
					import_cold_room.fn_net_volume_20deg AS net20
				FROM
					import_cold_room
				INNER JOIN warehouses ON import_cold_room.ft_facility_code = warehouses.ccem_id
				INNER JOIN ccm_makes ON import_cold_room.MakeID = ccm_makes.pk_id
				INNER JOIN ccm_asset_types ON import_cold_room.asset_sub_type = ccm_asset_types.pk_id";
		$rows = mysql_query($ccmQry);
        $counter = 1;
        while ($row = mysql_fetch_array($rows))
        {
			$ccmTotal = 0;
			$whId = $row['pk_id'];
			$ccmMake = $row['MakeID'];
			$ccmModel = $row['ft_model_name'];
			$ccmTotal = round($row['gross4'] + $row['gross20'] + $row['net4'] + $row['net20'], 0) * 1000;
        ?>
            <tr bgcolor="#CCCC00">
                <td style="text-align:center;"><?php echo $counter++;?></td>
                <td><?php echo $row['warehouse_name'];?></td>
                <td><?php echo $row['asset_type_name'];?></td>
                <td><?php echo $row['ccm_make_name'];?></td>
                <td>
					<select name="model" id="model<?php echo $counter;?>">
                    	<option value="">Select</option>
					<?php
                    foreach( $models as $modelId=>$info )
					{
						$sel = '';
						$lmisTotal = 0;
						$lmisTotal = round($info['gross4'] + $info['gross20'] + $info['net4'] + $info['net20'], 0);
						//echo 'CCEM : ' . $ccmTotal . ' - ' . 'LMIS : ' . $lmisTotal . '<br>';
						if ($lmisTotal == $ccmTotal && $ccmModel == $info['model'] && $ccmMake == $info['make'] )
						{
							$sel = 'selected="selected"';
							?>
							<script>
                            	$(function(){
									$('#model<?php echo $counter;?>').parent().parent().css('background', 'green');
									return false;
								})
                            </script>
							<?php
						}
						echo "<option value=\"$modelId\" $sel>".$info['model']."</option>";
					}
                    ?>
                    </select>
                </td>
                <td><?php echo $row['net4'];?></td>
                <td><?php echo $row['gross4'];?></td>
                <td><?php echo $row['net20'];?></td>
                <td><?php echo $row['gross20'];?></td>
                <td></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</body>
</html>
<?php
echo '<a href="asset_sub_type.php">Continue</a>';