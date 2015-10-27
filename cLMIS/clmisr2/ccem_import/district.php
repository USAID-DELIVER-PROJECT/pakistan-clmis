<?php
include('config.php');
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
	function updateLocation(lmiDistName, ccemDistName)
	{
		if ( lmiDistName != '' && ccemDistName != '' )
		{
			$.ajax({
				url: 'ajax.php',
				data: {lmiDistName: lmiDistName, ccemDistName: ccemDistName},
				type: 'POST'
			}).done(function(data){
				//$('#district').html(data);
			})
		}
	}
</script>
</head>

<body>
    <table id="myTable" width="500">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>CCEM Facility Name</th>
                <th>LMIS Facility NAME</th>
            </tr>
        </thead>
        <tbody>
        <?php
		$ccemQryRes = mysql_query("SELECT DISTINCT
							import_facilities.ft_level3
						FROM
							import_facilities
						ORDER BY
							import_facilities.ft_level3 ASC");
        $counter = 1;
        while ($row = mysql_fetch_array($ccemQryRes))
        {
        ?>
            <tr>
                <td style="text-align:center;"><?php echo $counter++;?></td>
                <td><?php echo $row['ft_level3'];?></td>
                <td>
                    <select name="ucMap" id="<?php echo $row['ft_level3'];?>" onchange="updateLocation(this.value, '<?php echo $row['ft_level3']?>')">
                        <option value="">Select</option>
                    <?php
                    $lmisQry = "SELECT
									locations.location_name
								FROM
									locations
								WHERE
									locations.geo_level_id = 4
								ORDER BY
									locations.location_name ASC";
                    $lmisQryRes = mysql_query($lmisQry);
                    while ( $lmisRow = mysql_fetch_array($lmisQryRes) )
                    {
                        $sel = (strtoupper($lmisRow['location_name']) == strtoupper($row['ft_level3'])) ? 'selected' : '';
                        echo "<option value='".$lmisRow['location_name']."' $sel>".$lmisRow['location_name']."</option>";
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
    
	<a href="facility.php">Next</a>

</body>
</html>