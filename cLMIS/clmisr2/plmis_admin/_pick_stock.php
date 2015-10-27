<?php
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/top.php";
include "../plmis_inc/common/top_im.php";
include("Includes/AllClasses.php");
$wh_id = $_SESSION['wh_id'];
if(isset($_POST))
{
    $tran_no= $_POST['tran_no'];
    if(!empty($tran_no)){
        $strSql = "SELECT
        tbl_stock_master.TranDate,
        tbl_stock_detail.Qty,
        stock_batch.batch_no,
        stock_batch.batch_id,
		stock_batch.unit_price,
        stock_batch.batch_expiry,
        itminfo_tab.itm_name,
        itminfo_tab.itm_id,
        tbl_warehouse.wh_name,
        tbl_itemunits.UnitType,
        tbl_stock_master.PkStockID,
        tbl_stock_master.TranNo,
        tbl_stock_master.TranRef,
		tbl_stock_detail.PkDetailID
        FROM
        tbl_stock_master
        INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
        INNER JOIN stock_batch ON tbl_stock_detail.BatchID = stock_batch.batch_id
        INNER JOIN itminfo_tab ON stock_batch.item_id = itminfo_tab.itm_id
        INNER JOIN tbl_warehouse ON tbl_stock_master.WHIDTo = tbl_warehouse.wh_id
        INNER JOIN tbl_itemunits ON itminfo_tab.itm_type = tbl_itemunits.UnitType
        WHERE
        tbl_stock_detail.temp = 0 AND
        tbl_stock_master.WHIDFrom = '" . $wh_id . "' AND
		tbl_stock_master.TranTypeID =2 AND
		tbl_stock_master.PkStockID=  " . $tran_no . "";

        $result = mysql_query($strSql) or die($strSql);
    }
}
?>
<style>
    .btn-link {
        color: #fff !important;
        text-shadow: none;
    }
</style>
<!-- Content -->
<div id="content" class="body_sec">
    <h3 class="heading-mosaic">Stock Pick</h3>
    <!-- Widget -->
    <div class="widget">

        <!-- Widget heading -->
        <div class="widget-head"></div>
        <!-- // Widget heading END -->

        <div class="widget-body">
            Pick from Issue Voucher List<br />
            <form name="issue_voucher_detail" id="issue_voucher_detail" action=""
                  method="post">
                <select name="tran_no" id="tran_no">
                    <option value="">Select</option>
                    <?php  $strSqlList="SELECT distinct
	tbl_stock_master.TranNo,
	tbl_stock_master.PkStockID
	FROM
	tbl_stock_master
	INNER JOIN tbl_stock_detail ON tbl_stock_master.PkStockID = tbl_stock_detail.fkStockID
	WHERE
	tbl_stock_master.TranTypeID = 2 AND
	tbl_stock_master.WHIDFrom = $wh_id";

                    $issueList=mysql_query($strSqlList) or die("ERR Issue Voucher");

                    while($rowVouchers=mysql_fetch_assoc($issueList)){?>
                        <option value="<?php echo $rowVouchers['PkStockID']?>">
                            <?php echo $rowVouchers['TranNo']?>
                        </option>
                        <?php }?>
                </select>
                <button type="submit" class="btn btn-primary">Go</button>
            </form>
            <?php if(mysql_num_rows($result)>0){?>
            <table class="table table-striped table-bordered table-condensed">

                <!-- Table heading -->
                <thead>
                <tr>
                    <th width="5%">S.No.</th>
                    <th width="20%">Date</th>
                    <th width="20%">Product</th>
                    <th width="10%">Batch</th>
                    <th width="10%">Expiry</th>
                    <th width="20%">Issue Qty (Unit)</th>
                    <th width="35%">Action</th>
                </tr>
                </thead>
                <!-- // Table heading END -->

                <!-- Table body -->

                <!-- Table row -->
                <?php
                $counter=1;
                if(mysql_num_rows($result)>0)
                {
                    while ($row=mysql_fetch_object($result)) {
                        $_SESSION['itm_id']=$row->itm_id;
                        $_SESSION['itm_name']=$row->itm_name;
                        $_SESSION['batch_no']=$row->batch_no;
                        $_SESSION['expiry']=$row->batch_expiry;
                        ?>
                        <tr class="gradeX">
                            <td><?php echo $counter; ?>
                            </td>
                            <td><?php echo date("d/m/y", strtotime($row->TranDate)); ?>
                            </td>
                            <td><?php echo $row->itm_name; ?>
                            </td>
                            <td><?php echo $row->batch_no; ?>
                            </td>
                            <td><?php echo date("d/m/y", strtotime($row->batch_expiry)); ?>
                            </td>
                            <td><?php echo number_format(abs($row->Qty)); ?>
                            </td>
                            <td><a id="pick_<?php echo $row->itm_id?>_<?php echo $row->batch_id?>_<?php echo $row->batch_expiry?>_<?php echo $row->itm_name?>_<?php echo $row->PkDetailID?>" onclick="javascript:void(0);"
                                   data-toggle="modal" href="#modal-pick"
                                   class="btn btn-primary ">Pick</a>
                            </td>

                        </tr>

                        <?php
                        $counter++;} }?>

                <!-- // Table row END -->
                </tbody>
            </table>
            <?php }?>
            <div id="pick_stock"></div>
        </div>
    </div>
    <!-- Widget -->

</div>

<!-- // Content END -->
<?php include "../plmis_inc/common/footer.php";?>
<script
        src="<?php echo SITE_URL; ?>plmis_js/dataentry/jquery.mask.min.js"></script>
<script
        src="<?php echo SITE_URL; ?>plmis_js/jquery.inlineEdit.js"></script>
<script
        src="<?php echo SITE_URL; ?>plmis_js/dataentry/stockplacement.js"></script>
