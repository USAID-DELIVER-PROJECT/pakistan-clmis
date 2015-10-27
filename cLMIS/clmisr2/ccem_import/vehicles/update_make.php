<?php
include('../config.php');
$table = "import_transport";

// Update Make
if (isset($_REQUEST['lmisName']) && isset($_REQUEST['ccemName'])) {
    $qry = "UPDATE $table
            SET MakeID = '" . $_REQUEST['lmisName'] . "'
            WHERE ft_make = '" . $_REQUEST['ccemName'] . "'";
    mysql_query($qry);
    exit;
}

// Update Makes
mysql_query("UPDATE $table,
            ccm_makes
            SET MakeID = pk_id
            WHERE
                ft_make = ccm_make_name");
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
                if (lmisName != '' && ccemName != '' && lmisName != '-1')
                {
                    $.ajax({
                        url: 'update_make.php',
                        data: {lmisName: lmisName, ccemName: ccemName},
                        type: 'POST'
                    })
                }
                else if (lmisName == '-1')
                {
                    window.open('add_make.php?ccmName=' + ccemName, '_blank', 'scrollbars=1,width=400,height=200');
                }
            }
        </script>
    </head>

    <body>
        <h3>Update Makes</h3>
        <table id="myTable" width="700">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>CCEM Manufacturer Name</th>
                    <th>LMIS Manufacturer Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ccmQry = "SELECT DISTINCT
                                MakeID,
                                ft_make
                            FROM
                                $table";
                //echo $ccmQry;die;
                $rows = mysql_query($ccmQry);
                $counter = 1;
                while ($row = mysql_fetch_array($rows)) {
                    ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $counter++; ?></td>
                        <td><?php echo $row['ft_make']; ?></td>
                        <td>
                            <select name="ccmMap" id="<?php echo $row['ft_make']; ?>" onchange="updateFunc(this.value, '<?php echo $row['ft_make'] ?>')">
                                <option value="">Select</option>
                                <?php
                                $lmisQry = "SELECT
                                                pk_id,
                                                ccm_make_name
                                            FROM
                                                ccm_makes
                                            ORDER BY
                                                ccm_make_name ASC";
                                $lmisQryRes = mysql_query($lmisQry);
                                while ($lmisRow = mysql_fetch_array($lmisQryRes)) {
                                    $sel = ($lmisRow['pk_id'] == $row['MakeID']) ? 'selected="selected"' : '';
                                    echo "<option value='" . $lmisRow['pk_id'] . "' $sel>" . $lmisRow['ccm_make_name'] . "</option>";
                                }
                                ?>
                                <option value="-1">New</option>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
<a href="asset_sub_type.php">Next</a>