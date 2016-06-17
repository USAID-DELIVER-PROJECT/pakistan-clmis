<?php
include("../application/includes/classes/Configuration.inc.php");
include("../application/includes/classes/db.php");
include("../application/includes/common/FunctionLib.php");
?>
<?php

if ( $_POST['findDup'] )
{
	mysql_query("TRUNCATE temp_warehouse");
	$qry = "INSERT INTO temp_warehouse (
				temp_warehouse.wh_id,
				temp_warehouse.wh_name,
				temp_warehouse.wh_type_id,
				temp_warehouse.wh_prov_id
			) 
			SELECT
				tbl_warehouse.wh_id,
				tbl_warehouse.wh_name,
				tbl_warehouse.wh_type_id,
				tbl_warehouse.prov_id
			FROM
				tbl_warehouse
			JOIN (
				SELECT
					tbl_wh_data.wh_id
				FROM
					tbl_wh_data
				GROUP BY
					tbl_wh_data.report_month,
					tbl_wh_data.report_year,
					tbl_wh_data.item_id,
					tbl_wh_data.wh_id
				HAVING
					COUNT(tbl_wh_data.wh_id) > 1
			) A ON A.wh_id = tbl_warehouse.wh_id
			WHERE
				tbl_warehouse.prov_id = prov_id
			GROUP BY
				tbl_warehouse.wh_id";
	mysql_query($qry);
	header('Location: remove_duplications.php?e=1');
}

if (isset($_REQUEST['provinceId']) && !empty($_REQUEST['provinceId']))
{
	$provinceId = mysql_real_escape_string($_REQUEST['provinceId']);
	$warehouseId = mysql_real_escape_string($_REQUEST['warehouseId']);
	$qry = "SELECT
				temp_warehouse.wh_id,
				temp_warehouse.wh_name,
				temp_warehouse.wh_type_id
			FROM
				temp_warehouse
			WHERE
				temp_warehouse.wh_prov_id = ". $provinceId ."
			ORDER BY
				temp_warehouse.wh_name ASC";
	$warehouses = mysql_query($qry);
	$num = mysql_num_rows($warehouses);
	if ( $num > 0 )
	{
	echo '<select name="warehouse" id="warehouse">';
		while ($row = mysql_fetch_array($warehouses))
		{
			$sel = (!empty($warehouseId) && $warehouseId == $row['wh_id']) ? 'selected' : '';
			$whName = $row['wh_name'];
			$whName .= !empty($row['wh_type_id']) ? " ($row[wh_type_id])" : '';
			echo "<option value=\"$row[wh_id]\" $sel>$whName</option>";
		}
		echo '</select>';
	}
	else
	{
		echo '<select name="warehouse" id="warehouse">';
		echo "<option>No Warehouse With Duplicates Found</option>";
		echo '</select>';
	}
    exit;
}

// If form is submitted then show the duplicate data of the warehouse
if ( isset($_REQUEST['submit']) || isset($_REQUEST['del_submit']) )
{
	$proId = mysql_real_escape_string($_REQUEST['province']);
	$whId = mysql_real_escape_string($_REQUEST['warehouse']);
	
	// if Id then delete record
	if (!empty($_REQUEST['dIds']))
	{	
		$delIds = implode(',', $_POST['dIds']);
		$delQry = "DELETE FROM tbl_wh_data WHERE w_id IN ($delIds) AND wh_id = ".$whId." ";
		mysql_query($delQry);
	}
	
	// Get all duplicate data of the selected warehouse
	$dataQry = "SELECT
					tbl_wh_data.w_id,
					CONCAT(tbl_wh_data.report_year, '-', LPAD(tbl_wh_data.report_month, 2, 0)) AS rptMonthYear,
					tbl_wh_data.item_id,
					tbl_wh_data.wh_id,
					tbl_wh_data.wh_obl_a,
					tbl_wh_data.wh_obl_c,
					tbl_wh_data.wh_received,
					tbl_wh_data.wh_issue_up,
					tbl_wh_data.wh_cbl_c,
					tbl_wh_data.wh_cbl_a,
					tbl_wh_data.wh_adja,
					tbl_wh_data.wh_adjb,
					tbl_wh_data.lvl,
					DATE_FORMAT(tbl_wh_data.RptDate, '%d/%m/%Y') AS RptDate,
					itminfo_tab.itm_name
				FROM
				(
					SELECT
						COUNT(*),
						tbl_wh_data.report_month,
						tbl_wh_data.report_year,
						tbl_wh_data.item_id,
						tbl_wh_data.wh_id
					FROM
						tbl_wh_data
					WHERE
						tbl_wh_data.wh_id = ".$whId."
					GROUP BY
						tbl_wh_data.report_month,
						tbl_wh_data.report_year,
						tbl_wh_data.item_id,
						tbl_wh_data.wh_id
					HAVING
						COUNT(*) > 1
					) AS A
					INNER JOIN tbl_wh_data ON tbl_wh_data.report_month = A.report_month AND tbl_wh_data.report_year = A.report_year AND tbl_wh_data.item_id = A.item_id AND tbl_wh_data.wh_id = A.wh_id
					INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
					WHERE
						tbl_wh_data.wh_id = ".$whId."
					ORDER BY
						itminfo_tab.itm_name,
						rptMonthYear DESC";
	$dataRes = mysql_query($dataQry);
	$num = mysql_num_rows($dataRes);
	

	
	// Get all data of the selected warehouse (Not Duplicated)
	$dataQry1 = "SELECT
					tbl_wh_data.w_id,
					CONCAT(tbl_wh_data.report_year, '-', LPAD(tbl_wh_data.report_month, 2, 0)) AS rptMonthYear,
					tbl_wh_data.item_id,
					tbl_wh_data.wh_id,
					tbl_wh_data.wh_obl_a,
					tbl_wh_data.wh_obl_c,
					tbl_wh_data.wh_received,
					tbl_wh_data.wh_issue_up,
					tbl_wh_data.wh_cbl_c,
					tbl_wh_data.wh_cbl_a,
					tbl_wh_data.wh_adja,
					tbl_wh_data.wh_adjb,
					tbl_wh_data.lvl,
					DATE_FORMAT(tbl_wh_data.RptDate, '%d/%m/%Y') AS RptDate,
					itminfo_tab.itm_name
				FROM
				(
					SELECT
						COUNT(*),
						tbl_wh_data.report_month,
						tbl_wh_data.report_year,
						tbl_wh_data.item_id,
						tbl_wh_data.wh_id
					FROM
						tbl_wh_data
					WHERE
						tbl_wh_data.wh_id = ".$whId."
					GROUP BY
						tbl_wh_data.report_month,
						tbl_wh_data.report_year,
						tbl_wh_data.item_id,
						tbl_wh_data.wh_id
					HAVING
						COUNT(*) = 1
					) AS A
					INNER JOIN tbl_wh_data ON tbl_wh_data.report_month = A.report_month AND tbl_wh_data.report_year = A.report_year AND tbl_wh_data.item_id = A.item_id AND tbl_wh_data.wh_id = A.wh_id
					INNER JOIN itminfo_tab ON tbl_wh_data.item_id = itminfo_tab.itmrec_id
					WHERE
						tbl_wh_data.wh_id = ".$whId."
					ORDER BY
						rptMonthYear DESC";
	$dataRes1 = mysql_query($dataQry1);
	$num1 = mysql_num_rows($dataRes1);
	
}
else
{
	$proId = '';
	$whId = '';
	$num = '';
	$num1 = '';
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Remove Duplication</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
        <style>
			body {font-family: 'Open Sans', Helvetica, Arial, sans-serif;color: #333; font-size:14px;}
        	table#dataGrid{border:1px solid #555; border-collapse:collapse;}
			table#dataGrid tr td, table#dataGrid tr th {border:1px solid #555; padding:5px;}
			table#dataGrid th{text-align:center;}
			a { text-decoration:none; color:#09F;}
        </style>
		<script src="plmis_js/jquery-1.7.1.min.js"></script>
		<script>
			$(function(){
				showWarehouse();
				$('#province').change(function(){
					showWarehouse();
				})
			})
			
			// Get warehouses of the province
			function showWarehouse()
			{
				var province = $('#province').val();
				if (province != '') {
					$('#warehouseCol').html('<img src="plmis_img/loading.gif">');
					$('#submit').prop('disabled', 'disabled');
					$.ajax({
						url : 'remove_duplications.php',
						type : 'POST',
						data : {provinceId : province, warehouseId: '<?php echo $whId;?>'}
					}).done(function(data) {
						$('#warehouseCol').html(data);
						$('#submit').prop('disabled', false);
					})
				}
				else
				{
					$('#warehouseCol').html('<select name="warehouse" id="warehouse"><option value="">Select Warehouse First</option></select>');
				}
			}
			// Form validation
			function frmValidate()
			{
				if ($('#province').val() == '')
				{
					alert('Select province.');
					$('#province').focus();
					return false;
				}
			}
		</script>
	</head>
	<body>
		<div>
        	<form name="dupFrm" id="dupFrm" action="" method="post">
            	<input type="submit" name="findDup" value="Find Duplicates" />
            </form>
            
            <table width="100%">
            	<tr><td colspan="3" style="color:#093;"><?php echo (isset($_GET['e'])) ? 'Select Province and warehouse then press search.' : '';?></td></tr>
    			<tr>
                	<td colspan="3">
                    	<form name="frm" id="frm" action="" method="get" onSubmit="return frmValidate()">
                            <table width="100%">
                                <tr>
                                    <td width="200"><b>Province</b></td>
                                    <td width="200"><b>Warehouse</b></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="province" id="province">
                                            <option value="">Select</option>
                                        <?php
                                        $qry = "SELECT
                                                    tbl_locations.PkLocID,
                                                    tbl_locations.LocName
                                                FROM
                                                    tbl_locations
                                                WHERE
                                                    tbl_locations.LocLvl = 2 AND
                                                    tbl_locations.ParentID IS NOT NULL";
                                        $provinces = mysql_query($qry);
                                        while ($row = mysql_fetch_array($provinces))
                                        {	
                                            $sel = ($proId == $row['PkLocID']) ? 'selected' : '';
                                            echo "<option value=\"$row[PkLocID]\" $sel>$row[LocName]</option>";
                                        }
                                        ?>
                                        </select>
                                    </td>
                                    <td id="warehouseCol">
                                        <select name="warehouse" id="warehouse">
                                            <option value="">Select Warehouse First</option>
                                        </select>
                                    </td>
                                    <td align="left">
                                        <input type="submit" id="submit" name="submit" value="Search" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                	</td>
                </tr>
                    <?php
                    if ( $num > 0 )
					{
					?>
                    <tr><td colspan="3" align="center"><h2 style="color:#060;">Duplicate Records</h2></td></tr>
                    <tr>
                    	<td colspan="3">
                        	<form name="del_frm" id="del_frm" method="post" action="">
                                <table width="70%" border="0" id="dataGrid">
                                    <thead>
                                    <tr>
                                        <th rowspan="2"><b>S. No.</b></th>
                                        <th rowspan="2"><b>Action</b></th>
                                        <th rowspan="2"><b>Product</b></th>
                                        <th rowspan="2"><b>Date</b></th>
                                        <th colspan="2"><b>Opening Balance</b></th>
                                        <th rowspan="2"><b>Received</b></th>
                                        <th rowspan="2"><b>Issue</b></th>
                                        <th colspan="2"><b>Closing Balance</b></th>
                                        <th rowspan="2"><b>Reporting Date</b></th>
                                    </tr>
                                    <tr>
                                        <th><b>Calculated</b></th>
                                        <th><b>Actual</b></th>
                                        <th><b>Calculated</b></th>
                                        <th><b>Actual</b></th>
                                    </tr>
                                </thead>
                                    <tbody>
                                    <?php
                                    $count = 1;
                                    while ($dataRow = mysql_fetch_array($dataRes))
                                    {
                                    ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td align="center">
                                                <input type="checkbox" name="dIds[]" value="<?php echo $dataRow['w_id'];?>" />
                                            </td>
                                            <td><?php echo $dataRow['itm_name'];?></td>
                                            <td><?php echo date('M-y', strtotime($dataRow['rptMonthYear'].'-01'));?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_obl_c']);?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_obl_a']);?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_received']);?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_issue_up']);?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_cbl_c']);?></td>
                                            <td align="right"><?php echo number_format($dataRow['wh_cbl_a']);?></td>
                                            <td><?php echo $dataRow['RptDate'];?></td>
                                        </tr>
                                    <?php
                                        $count++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="9" align="right">
                                        	<input type="hidden" name="warehouse" value="<?php echo $_GET['warehouse'];?>" />
                                        	<input type="hidden" name="province" value="<?php echo $_GET['province'];?>" />
                                        	<input type="submit" name="del_submit" id="del_submit" value="Delete">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </td>
                    </tr>
                    <?php
					}
					
                    if ( $num1 > 0 )
					{
					?>
                    <tr><td colspan="3" align="center"><h2 style="color:#060;">Records With No Duplicate</h2></td></tr>
                    <tr>
                    	<td colspan="3">
                        	<table width="70%" border="0" id="dataGrid">
                            	<thead>
                                    <tr>
                                        <th rowspan="2"><b>S. No.</b></th>
                                        <th rowspan="2"><b>Product</b></th>
                                        <th rowspan="2"><b>Date</b></th>
                                        <th colspan="2"><b>Opening Balance</b></th>
                                        <th rowspan="2"><b>Received</b></th>
                                        <th rowspan="2"><b>Issue</b></th>
                                        <th colspan="2"><b>Closing Balance</b></th>
                                        <th rowspan="2"><b>Reporting Date</b></th>
                                    </tr>
                                    <tr>
                                        <th><b>Calculated</b></th>
                                        <th><b>Actual</b></th>
                                        <th><b>Calculated</b></th>
                                        <th><b>Actual</b></th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
                                $count1 = 1;
								while ($dataRow = mysql_fetch_array($dataRes1))
								{
								?>
                                    <tr>
                                        <td><?php echo $count1;?></td>
                                        <td><?php echo $dataRow['itm_name'];?></td>
                                        <td><?php echo date('M-y', strtotime($dataRow['rptMonthYear'].'-01'));?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_obl_c']);?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_obl_a']);?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_received']);?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_issue_up']);?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_cbl_c']);?></td>
                                        <td align="right"><?php echo number_format($dataRow['wh_cbl_a']);?></td>
                                        <td><?php echo $dataRow['RptDate'];?></td>
                                    </tr>
								<?php
									$count1++;
								}
								?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php
					}
					?>
    		</table>

		</div>
	</body>
</html>