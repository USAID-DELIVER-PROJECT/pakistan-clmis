<?php
include('../config.php');

// Update Make
$ccmName = $_REQUEST['ccmName'];
if (isset($_REQUEST['submit']))
{
	$make = $_REQUEST['make'];
	//Check if Make already exists
	$qry = "SELECT
				ccm_makes.pk_id,
				COUNT(ccm_makes.pk_id) AS num
			FROM
				ccm_makes
			WHERE
				ccm_makes.ccm_make_name = '$make'";
	$row = mysql_fetch_array((mysql_query($qry)));
	if ( $row['num'] > 0 )
	{
		$makeId = $row['pk_id'];
	}
	else if($row['num'] == 0)
	{
		$qry = "INSERT INTO ccm_makes
			SET
				ccm_make_name = '$make',
				`status` = 1";
		mysql_query($qry);
		$makeId = mysql_insert_id();
	}
	
	// Update COldchain Table
	$qry = "UPDATE import_refrigerators
			SET MakeID = '".$makeId."'
			WHERE
				import_refrigerators.ft_manu_name = '".$make."'";
	mysql_query($qry);
	?>
	<script type="text/javascript">
        function RefreshParent() {
            if (window.opener != null && !window.opener.closed) {
                window.opener.location.reload();
            }
        }
		window.close();
		//RefreshParent();
		window.opener.location.href = window.opener.location.href;
    </script>
	<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Make</title>
<link href="style.css" type="text/css" rel="stylesheet" />
</head>

<body>
	<h3>Add Make</h3>
    <form name="frm" id="frm" action="" method="post">
        <table width="100%">
            <tbody>
                <tr>
                    <td width="80%"><input type="text" name="make" id="make" value="<?php echo $ccmName;?>" style="width:100%;" /></td>
                    <td align="left"><input type="submit" name="submit" id="submit" value="Add" /></td>
                </tr>
            </tbody>
        </table>
    </form>
</body>
</html>