<?php
include("../../html/adminhtml.inc.php");

$wh_id = $_REQUEST['wh'];
$currMonth = $_REQUEST['date'];
$preMonth = date('Y-m-d', strtotime('-1 Month', strtotime($currMonth)));

// Get Warehouse information
$getWHInfo = "SELECT
			tbl_warehouse.wh_name AS whName,
			tbl_locations.LocName AS distName
		FROM
			tbl_warehouse
		INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
		WHERE
			tbl_warehouse.wh_id = $wh_id";
$whInfo = mysql_fetch_array(mysql_query($getWHInfo));

$qry = "SELECT
			itminfo_tab.itm_name,
			A.wh_obl_a,
			B.wh_cbl_a
		FROM
			(
				SELECT
					tbl_wh_data.item_id,
					tbl_wh_data.wh_obl_a
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				WHERE
					tbl_wh_data.RptDate = '$currMonth'
				AND tbl_warehouse.wh_id = $wh_id
			) AS A
		JOIN (
			SELECT
				tbl_wh_data.item_id,
				tbl_wh_data.wh_cbl_a
			FROM
				tbl_warehouse
			INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
			WHERE
				tbl_wh_data.RptDate = '$preMonth'
			AND tbl_warehouse.wh_id = $wh_id
		) AS B ON A.item_id = B.item_id
		JOIN itminfo_tab ON A.item_id = itminfo_tab.itmrec_id
		ORDER BY
			itminfo_tab.frmindex";
$qryRes = mysql_query($qry);
?>
<style>
*{font-family:Verdana,Arial,Helvetica,sans-serif;; font-size:13px; line-height:1.5;}
table#myTable{border-collapse: collapse;border-spacing: 0;}
table#myTable tr:hover{background: #FFFACD}
table#myTable tr:nth-child(even) {background: #DFF2A9}
table#myTable tr:nth-child(odd) {background: #FFF}
table#myTable tr th{padding-left:5px; border:1px solid #999; background:#BDDD83;}
table#myTable tr td{padding-left:5px; border:1px solid #999;}
table#myTable tr td.TAR{text-align:right; padding:5px;width:120px !important;}
table#myTable tr td.TAC{text-align:center; padding:5px;width:120px !important;}
.sb1NormalFont {
	color: #444444;
	font-family: Verdana,Arial,Helvetica,sans-serif;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
}
</style>
<h2 class="sb1NormalFont">District: <?php echo $whInfo['distName'];?></h2>
<h2 class="sb1NormalFont">Warehouse: <?php echo $whInfo['whName'];?></h2>
<table width="100%" cellpadding="3" id="myTable">
    <thead>
        <tr>
            <th width="60">Sr. No.</th>
            <th>Product</th>
            <th>Closing Balance</th>
            <th>Opening Balance</th>
        </tr>
    </thead>
    <tbody>
	<?php
	$count = 1;
    while ( $row = mysql_fetch_array($qryRes) )
    {
    ?>
		<tr>
        	<td style="text-align:center;"><?php echo $count++;?></td>
        	<td><?php echo $row['itm_name'];?></td>
        	<td class="TAR"><?php echo number_format($row['wh_cbl_a']);?></td>
        	<td class="TAR"><?php echo number_format($row['wh_obl_a']);?></td>
        </tr>
	<?php
    }
    ?>
	</tbody>
</table>