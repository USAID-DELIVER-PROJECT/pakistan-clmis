<?php
include("../../html/adminhtml.inc.php");
include "../../plmis_inc/common/_header.php";
?>
<?php
$param = explode('|', base64_decode($_GET['param']));

$whId = $param[0];
$whName = $param[1];
$date = $param[2];
$type = $param[3];
$dateArr = explode('-', $date);
$month = $dateArr[0];
$year = $dateArr[1];


if ($type == 'Issue') {
    $qry = "SELECT
				tbl_stock_master.PkStockID,
				tbl_stock_master.TranNo,
				CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
				DATE_FORMAT(tbl_stock_master.TranDate, '%d/%m/%y') AS TranDate,
				tbl_stock_detail.IsReceived,
				tbl_stock_detail.adjustmentType
			FROM
				tbl_stock_master
			INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
			INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
                        INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
				tbl_stock_master.WHIDFrom = '$whId'
				AND tbl_stock_master.temp = 0
				AND DATE_FORMAT(tbl_stock_master.TranDate, '%m-%Y') = '" . $date . "'
				AND tbl_stock_master.TranTypeID = 2
			GROUP BY
				tbl_stock_master.TranNo
			ORDER BY
				tbl_stock_master.TranNo ASC";
    $whHead = 'to';
    $voucherLink = 'printIssue.php';
} else if ($type == 'Receive') {
    $qry = "SELECT
				tbl_stock_master.PkStockID,
				tbl_stock_master.TranNo,
                                CONCAT(tbl_warehouse.wh_name, ' (', stakeholder.stkname, ')') AS wh_name,
				DATE_FORMAT(tbl_stock_master.TranDate, '%d/%m/%y') AS TranDate
			FROM
				tbl_stock_master
			INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDFrom = tbl_warehouse.wh_id
                        INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			WHERE
				tbl_stock_master.WHIDTo = '$whId'
				AND tbl_stock_master.temp = 0
				AND DATE_FORMAT(tbl_stock_master.TranDate, '%m-%Y') = '" . $date . "'
				AND tbl_stock_master.TranTypeID = 1
			GROUP BY
				tbl_stock_master.TranNo
			ORDER BY
				tbl_stock_master.TranNo ASC";
    $whHead = 'from';
    $voucherLink = 'printReceive.php';
}

$qryRes = mysql_query($qry);

$title = $type . " Transaction Details for $whName($month/$year)";
?>

<style>
    table tr td{font-size:12px;}
    table tr td a{text-decoration:underline;}
</style>
<!-- Content -->
<div id="content" style="margin:10px;">
    <h3 class="page-title row-br-b-wp"><?php echo $title; ?></h3>
    <div class="innerLR">

        <table width="100%" cellpadding="3" class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th width="100">Sr. No.</th>
                    <th width="120">Transaction Date</th>
                    <th width="120">Transaction No.</th>
                    <th><?php echo $type . ' ' . $whHead; ?></th>
<?php if ($type == 'Issue') { ?>
                        <th>Status</th>
<?php } ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                    $counter = 1;
                    $color = '';
                    while ($row = mysql_fetch_array($qryRes)) {
                        if ($type == 'Issue') {
                            $color = ($row['IsReceived'] == 1 && $row['adjustmentType'] == 2) ? '#08B825' : '#e04545';
                        }
                        ?>
                    <tr class="gradeX">
                        <td align="center" style="color:<?php echo $color; ?>"><?php echo $counter; ?></td>
                        <td align="center" style="color:<?php echo $color; ?>"><?php echo $row['TranDate']; ?></td>
                        <td><a style="color:<?php echo $color; ?>" href="javascript:void(0);" onclick="window.open('../../plmis_admin/<?php echo $voucherLink; ?>?id=<?php echo $row['PkStockID']; ?>', '_blank', 'scrollbars=1,width=842,height=595');"><?php echo $row['TranNo']; ?></a></td>
                        <td style="color:<?php echo $color; ?>"><?php echo $row['wh_name']; ?></td>
                    <?php if ($type == 'Issue') { ?>
                        <td style="color:<?php echo $color; ?>"><?php echo ($row['IsReceived'] == 1 && $row['adjustmentType'] == 2) ? 'Received' : 'Pending'; ?>&nbsp;</td>
    <?php } ?>
                    </tr>
    <?php
    $counter++;
}
?>
            </tbody>
        </table>
    </div>
</div>