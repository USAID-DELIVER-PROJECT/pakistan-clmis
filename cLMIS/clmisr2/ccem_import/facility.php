<?php
include('config.php');

$provId = '';
$distId = '';
// If form is submitted
if ( isset($_REQUEST['submit']) )
{
	$provId = $_REQUEST['province'];
	$distId = $_REQUEST['district'];
	
	$qry = "SELECT
					UCASE(locations.location_name) AS location_name
				FROM
					locations
				WHERE
					locations.pk_id = $distId";
	$qryRes = mysql_fetch_array(mysql_query($qry));
	$disName = $qryRes['location_name'];
	
	$ccemQry = "SELECT DISTINCT
					import_facilities.ft_facility_code AS ccmId,
					CONCAT(import_facilities.ft_facility_name, ' (', tbl_admin_areas.ft_level5, ')') AS ccmName
				FROM
					import_facilities
				INNER JOIN tbl_admin_areas ON import_facilities.ft_level5 = tbl_admin_areas.fi_admin_code
				WHERE
					import_facilities.ft_level3 = '$disName'
				ORDER BY
					import_facilities.ft_facility_name ASC";
	$ccemQryRes = mysql_query($ccemQry);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Locations</title>
<style>
	*{font-family:Verdana, Geneva, sans-serif;}
	table#myTable{margin-top:0px !important;}
	table#myTable{margin-top:20px;border-collapse: collapse;border-spacing: 0; border:1px solid #999;}
	table#myTable tr td{font-size:12px;padding:3px; text-align:left; border:1px solid #999;}
	table#myTable tr th{font-size:12px;padding:3px; text-align:center; border:1px solid #999;}
	table#myTable tr td.TAR{text-align:right; padding:5px;width:50px !important;}
	.sb1NormalFont {
		color: #444444;
		font-size: 11px;
		font-weight: bold;
		text-decoration: none;
	}
	p{margin-bottom:5px; font-size:11px !important; line-height:1 !important; padding:0 !important;}
	table#headerTable tr td{ font-size:12px;}
	h4{margin:0;}
	h5{margin:15px 0 5px 0;}
</style>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
	$(function(){
		
		showDistricts();
		
		$('select[name="whMap"]').change(function(e) {
			var ccemId = $(this).attr('id');
			var lmisId = $(this).val();
            updateLocation(lmisId, ccemId);
        });
		
		$('#province').change(function(e) {
            showDistricts();
        });
	})
	function showDistricts()
	{
		var provId = $('#province').val();
		if ( provId != '' )
		{
			$.ajax({
				url: 'ajax.php',
				data: {provId: provId, distId: '<?php echo $distId;?>', type: 'old'},
				type: 'POST'
			}).done(function(data){
				$('#district').html(data);
			})
		}
	}
	function updateLocation(lmisId, ccemId)
	{
		//if ( lmisId != '' && ccemId != '' )
		{
			$.ajax({
				url: 'ajax.php',
				data: {lmisId: lmisId, ccemId: ccemId},
				type: 'POST'
			}).done(function(data){
				//$('#district').html(data);
			})
		}
	}
</script>
</head>

<body>
	<form name="frm" id="frm" action="" method="post">
    	<table>
        	<tr>
            	<td>Province</td>
            	<td>District</td>
            	<td>&nbsp;</td>
            </tr>
        	<tr>
            	<td>
                	<select name="province" id="province">
                    	<option value="">Select</option>
                    <?php
                    $qry = "SELECT
								locations.pk_id,
								locations.location_name
							FROM
								locations
							WHERE
								locations.geo_level_id = 2
							AND locations.parent_id IS NOT NULL";
					$qryRes = mysql_query($qry);
					while ( $row = mysql_fetch_array($qryRes) )
					{
						$sel = ($provId == $row['pk_id']) ? 'selected' : '';
						echo "<option value='".$row['pk_id']."' $sel>".$row['location_name']."</option>";
					}
					?>
                    </select>
                </td>
            	<td>
                	<select name="district" id="district">
                    	<option value="">Select</option>
                    </select>
                </td>
                <td>
                	<input type="submit" name="submit" id="submit" value="Go" />
                </td>
            </tr>
        </table>
    </form>
    <br />
    <?php
	if ( isset($_REQUEST['submit']) && mysql_num_rows(mysql_query($ccemQry)) > 0 )
	{
	?>
		<table id="myTable" width="80%">
        	<thead>
        		<tr>
                	<th>Sr. No.</th>
                	<th>CCEM Facility Name</th>
                    <th>LMIS Facility NAME</th>
                </tr>
            </thead>
            <tbody>
            <?php
			$counter = 1;
			while ($row = mysql_fetch_array($ccemQryRes))
			{
			?>
            	<tr>
                	<td style="text-align:center;"><?php echo $counter++;?></td>
                	<td><?php echo $row['ccmName'];?></td>
                	<td>
                    	<select name="whMap" id="<?php echo $row['ccmId'];?>">
                        	<option value="">Select</option>
                        <?php
						$distList = ($distId == 78) ? '78, 10' : $distId;
                        $lmisQry = "SELECT DISTINCT
										warehouses.pk_id,
										warehouses.ccem_id,
										CONCAT(warehouses.warehouse_name, ' (', IFNULL(locations.location_name, ''), ')') AS warehouse_name
									FROM
										warehouses
									LEFT JOIN locations ON warehouses.location_id = locations.pk_id
									WHERE
										warehouses.district_id IN( $distList )
									AND warehouses.stakeholder_id = 1
									ORDER BY
										warehouses.warehouse_name ASC";
						$lmisQryRes = mysql_query($lmisQry);
						while ( $lmisRow = mysql_fetch_array($lmisQryRes) )
						{
							$sel = ($lmisRow['ccem_id'] == $row['ccmId']) ? 'selected' : '';
							echo "<option value='".$lmisRow['pk_id']."' $sel>".$lmisRow['warehouse_name']."</option>";
						}
						?>
                        </select>
                    </td>
                </tr>
            <?php
			}
			?>
            </tbody>
        </table>
	<?php
	}
	else if (isset($_REQUEST['submit']))
	{
		echo "No record found";
	}
	?>

	<a href="delete_old_data.php" onclick="return confirm('This will delete old data. Are you sure you want to continue?')">Next</a>
    
</body>
</html>