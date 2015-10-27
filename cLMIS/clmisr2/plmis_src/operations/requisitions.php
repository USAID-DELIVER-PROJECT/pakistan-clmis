<?php
include("../../html/adminhtml.inc.php");
Login();
function dateToDbFormat($date)
{
    if(!empty($date)){
        list($dd,$mm,$yy) = explode("/",$date);
        return $yy."-".$mm."-".$dd;
    }
}
// If delete request
if (isset($_REQUEST['did']))
{
	$id = $_REQUEST['did'];
	$qry = "DELETE FROM clr_master WHERE pk_id = $id";
	mysql_query($qry);
	header("Location: requisitions.php");
	$_SESSION['e'] = 0;
	exit;
}

$where = 'WHERE 1=1';
// If Provincial User
if ( $_SESSION['userdata'][9] == 2 )
{
	$is_provincial_user = true;
	$sel_prov = $_SESSION['prov_id'];
	$where = " AND tbl_warehouse.prov_id = $sel_prov";
}

if (isset($_REQUEST['submit']))
{	
	$where = 'WHERE 1=1';
	if ( $is_provincial_user )
	{
		$sel_prov = $_SESSION['prov_id'];
        $where .= " AND tbl_warehouse.prov_id = $sel_prov";
	}
    else if (isset($_REQUEST['province']) && !empty($_REQUEST['province']))
    {
        $sel_prov = $_REQUEST['province'];
        $where .= " AND tbl_warehouse.prov_id = $sel_prov";
    }
    if (isset($_REQUEST['districts']) && !empty($_REQUEST['districts']))
    {
        $sel_dist = $_REQUEST['districts'];
        $where .= " AND tbl_warehouse.dist_id = $sel_dist";
    }
    if (isset($_REQUEST['stakeholder']) && !empty($_REQUEST['stakeholder']))
    {
        $sel_stk = $_REQUEST['stakeholder'];
        $where .= " AND stakeholder.stkid = $sel_stk";
    }
    if (isset($_REQUEST['item']) && !empty($_REQUEST['item']))
    {
        $sel_item = $_REQUEST['item'];
        $where .= " AND clr_details.itm_id = '$sel_item'";
    }
    if (isset($_REQUEST['req_num']) && !empty($_REQUEST['req_num']))
    {
        $req_num = $_REQUEST['req_num'];
        $where .= " AND clr_master.requisition_num = '$req_num'";
    }
    if (isset($_REQUEST['status']) && !empty($_REQUEST['status']))
    {
        $status = $_REQUEST['status'];
        if ($status == 'Pending')
        {
            $where .= " AND clr_master.fk_stock_id IS NULL";
        }
        else if ($status == 'Issued')
        {
            $where .= " AND clr_master.fk_stock_id IS NOT NULL";
        }
    }
    if (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from']) && isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to']))
    {
        $date_from = $_REQUEST['date_from'];
        $date_to = $_REQUEST['date_to'];

        $date_from1 = dateToDbFormat($_REQUEST['date_from']);
        $date_to1 = dateToDbFormat($_REQUEST['date_to']);

        $where .= " AND DATE_FORMAT(clr_master.requested_on, '%Y-%m-%d') BETWEEN '$date_from1' AND '$date_to1'";
    }
	if ( !$is_provincial_user )
	{
    	$where .= " AND clr_master.requisition_to = ".$_SESSION['userdata'][5]." ";
	}

}
else
{
	$date_from = date('01/m/Y');
	$date_to = date('d/m/Y');
	
	$date_from1 = dateToDbFormat($date_from);
	$date_to1 = dateToDbFormat($date_to);
	
	$where .= " AND DATE_FORMAT(clr_master.requested_on, '%Y-%m-%d') BETWEEN '$date_from1' AND '$date_to1'";
	if ( !$is_provincial_user )
	{
    	$where .= " AND clr_master.requisition_to = ".$_SESSION['userdata'][5]." ";
	}
}

$qry = "SELECT
			stakeholder.stkname,
			clr_master.pk_id,
			clr_master.requisition_num,
			clr_master.wh_id,
			clr_master.fk_stock_id,
			clr_master.approval_status,
			MONTH (clr_master.date_to) AS clrMonth,
			YEAR (clr_master.date_to) AS clrYear,
			tbl_warehouse.wh_type_id,
			tbl_warehouse.wh_name,
			tbl_locations.LocName,
			CONCAT(DATE_FORMAT(clr_master.requested_on, '%d/%m/%Y'), ' ', TIME_FORMAT(clr_master.requested_on, '%h:%i:%s %p')) AS requested_on
		FROM
			clr_master
		INNER JOIN stakeholder ON clr_master.stk_id = stakeholder.stkid
		INNER JOIN tbl_warehouse ON clr_master.wh_id = tbl_warehouse.wh_id
		INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
		INNER JOIN clr_details ON clr_details.pk_master_id = clr_master.pk_id
		$where
		GROUP BY
			clr_master.requisition_num";
$qryRes = mysql_query($qry);
$num = mysql_num_rows($qryRes);
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-container">
	<?php include "../../plmis_inc/common/_top.php";?>
    <?php include "../../plmis_inc/common/top_im.php";?>
    
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <form name="frm" id="frm" action="" method="get">
                    <div class="col-md-12">
                        <div class="widget" data-toggle="collapse-widget">
                            <div class="widget-head">
                                <h3 class="heading">Requisition Search</h3>
                            </div>
                            <div class="widget-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <?php
									if ($is_provincial_user){
										$display = 'style="display:none"';
									}else
									{
										$display = 'style="display:block"';
									}
									?>
                                        <div class="col-md-3" <?php echo $display;?>>
                                            <div class="control-group ">
                                                <label class="control-label">Province</label>
                                                <div class="controls">
                                                    <select name="province" id="province" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $queryprov = "SELECT
                                                                            tbl_locations.PkLocID AS prov_id,
                                                                            tbl_locations.LocName AS prov_title
                                                                        FROM
                                                                            tbl_locations
                                                                        WHERE
                                                                            LocLvl = 2
                                                                        AND parentid IS NOT NULL";
                                                        $rsprov = mysql_query($queryprov) or die();
                                                        while ($row = mysql_fetch_array($rsprov))
                                                        {
                                                            if ($sel_prov == $row['prov_id'])
                                                                $sel = "selected='selected'";
                                                            else
                                                                $sel = "";
                                                            ?>
                                                            <option value="<?php echo $row['prov_id']; ?>" <?php echo $sel; ?>><?php echo $row['prov_title']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">District</label>
                                                <div class="controls" id="districtsCol">
                                                    <select name="districts" id="districts" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Stakeholder</label>
                                                <div class="controls">
                                                    <select name="stakeholder" id="stakeholder" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
                                                        $querystk = "SELECT
                                                                        stkid,
                                                                        stkname
                                                                    FROM
                                                                        stakeholder
                                                                    WHERE
                                                                        ParentID IS NULL
                                                                        AND stakeholder.stk_type_id = 0
                                                                    ORDER BY
                                                                        stkorder";
                                                        $rsstk = mysql_query($querystk) or die();
                                                        while ($row = mysql_fetch_array($rsstk))
                                                        {
                                                            if ($sel_stk == $row['stkid'])
                                                                $sel = "selected='selected'";
                                                            else
                                                                $sel = "";
                                                            ?>
                                                            <option value="<?php echo $row['stkid'];?>" <?php  echo $sel; ?>><?php echo $row['stkname']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Product</label>
                                                <div class="controls">
                                                    <select name="item" id="item" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Status</label>
                                                <div class="controls">
                                                    <select name="status" id="status" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <option value="All" <?php echo ($status == 'All') ? 'selected="selected"' : '';?>>All</option>
                                                        <option value="Pending" <?php echo ($status == 'Pending') ? 'selected="selected"' : '';?>>Pending</option>
                                                        <option value="Issued" <?php echo ($status == 'Issued') ? 'selected="selected"' : '';?>>Issued</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Date From</label>
                                                <div class="controls">
                                                    <input type="text" name="date_from" id="date_from" class="form-control input-medium" value="<?php echo $date_from;?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Date To</label>
                                                <div class="controls">
                                                    <input type="text" name="date_to" id="date_to" value="<?php echo $date_to;?>" readonly class="form-control input-medium" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group ">
                                                <label class="control-label">Requisitions #</label>
                                                <div class="controls">
                                                    <input type="text" name="req_num" id="req_num" value="<?php echo $req_num;?>" class="form-control input-medium" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 right">
                                        <div class="control-group">
                                            <label class="control-label">&nbsp;</label>
                                            <div class="controls">
                                                <input type="submit" name="submit" value="Search" class="btn btn-primary" />
                                                <input type="button" onClick="window.location='<?php echo $_SERVER['PHP_SELF'];?>'" value="Reset" class="btn btn-info" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Requisitions</h3>
                        </div>
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="dynamictable table table-striped table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Requisition No.</th>
                                                <th>Stakeholder</th>
                                                <th>Store Name</th>
                                                <th>District</th>
                                                <th>Requested On</th>
                                                <th>Status</th>
                                                <th>Issue Vouchers</th>
                                                <?php if ($userType != 'UT-007'){?>
                                                <th>Action</th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        //if ($num > 0)
                                        {
                                            $counter = 1;
                                            while ($row = mysql_fetch_array($qryRes))
                                            {
                                            ?>
                                            <input type="hidden" name="warehouse" id="warehouse" value="<?php echo $row['wh_id']?>"/>
                                            <input type="hidden" name="clr6_id" id="clr6_id" value="<?php echo $row['pk_id']?>"/>
                                            <input type="hidden" name="rq_no" value="<?php echo $requisitionNum?>"/>
                                            <tr>
                                                <td style="text-align:center;"><?php echo $counter++;?></td>
                                                <td>
                                                <?php 
													if ( !$is_provincial_user )
													{
														if($row['approval_status']!='Pending' && $row['approval_status']!='Denied'){ ?><a href="<?php echo SITE_URL?>plmis_admin/approve_clr6.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>&rq=<?php echo $row['requisition_num'];?>"><?php echo $row['requisition_num'];?></a> <?php }else {echo $row['requisition_num'];}
													}else
													{
														echo $row['requisition_num'];
													}
												?>
                                                </td>
                                                <td><?php echo $row['stkname'];?></td>
                                                <td>
                                                    <?php
                                                    $whName = $row['wh_name'];
                                                    $whName .= !empty($row['wh_type_id']) ? " ($row[wh_type_id])" : '';
                                                    echo $whName;
                                                    ?>
                                                </td>
                                                <td><?php echo $row['LocName'];?></td>
                                                <td><?php echo $row['requested_on'];?></td>
                                                <td><?php echo $row['approval_status'];?></td>
                                                <td>
                                                    <?php
                                                    $getStockIssues=mysql_query("SELECT DISTINCT
                                                                                                tbl_stock_master.TranNo,
                                                                                                tbl_stock_master.PKStockId
                                                                                                FROM
                                                                                                clr_details
                                                                                                INNER JOIN tbl_stock_master ON clr_details.stock_master_id = tbl_stock_master.PkStockID
                                                                                                WHERE
                                                                                                tbl_stock_master.TranTypeID = 2 AND
                                                                                                clr_details.pk_master_id = ".$row['pk_id']) or die("Err GetStockIssueId");
                                    
                                    
                                                    if(mysql_num_rows($getStockIssues)>0)
                                                    {
                                                        while($resStockIssues=mysql_fetch_assoc($getStockIssues))
                                                        {
                                                            ?><a onClick="window.open('<?php echo SITE_URL?>plmis_admin/printIssue.php?id=<?php echo $resStockIssues['PKStockId'] ?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $resStockIssues['TranNo'].', ';?></a>
                                                            <?php 
														}
                                                    }
                                    
                                                    else {echo "N/A";}
                                                    ?>
                                                </td>
                                                <?php if ($userType != 'UT-007'){?>
                                                <td class="left">
                                                    <a href="clr_view.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">View</a> | 
                                                    <a onClick="return confirm('Are you sure, you want to delete this record?')" href="requisitions.php?did=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">Delete</a>
                                                    <?php
													if ( !$is_provincial_user )
													{
														if($row['approval_status']=='Pending'){ ?>| <a href="<?php echo SITE_URL?>plmis_admin/approve_clr6.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>&rq=<?php echo $row['requisition_num']?>">Approve</a> <?php }
														else if($row['approval_status']=='Approved'){ ?>| <a href="<?php echo SITE_URL?>plmis_src/operations/issue.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>&rq=<?php echo $row['requisition_num']?>">Issue</a> <?php }
														else if($row['approval_status']=='Issue in Process'){ ?>| <a href="<?php echo SITE_URL?>plmis_src/operations/issue.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>&rq=<?php echo $row['requisition_num']?>">Issue</a> <?php }
														else if ($row['approval_status'] == 'Issued'){?>| <a href="clr7_view.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">CLR-7</a><?php }
													}
													?>
                                                </td>
                                                <?php }?>
                                            </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../plmis_inc/common/right_inner.php";?>
<?php include "../../plmis_inc/common/footer.php";?>

<script type="text/javascript">
    $(function(){
		$("#date_from, #date_to").datepicker({
			dateFormat: 'dd/mm/yy',
			constrainInput: false,
			changeMonth: true,
			changeYear: true
		});
	})
</script>
<script>
    <?php
    if ( isset($_REQUEST['districts']) && !empty($_REQUEST['districts']) || $_SESSION['userdata'][9] == 2 )
    {
        ?>
    showDistricts('<?php echo $_REQUEST['districts'];?>');
        <?php
    }
    if ( isset($_REQUEST['item']) && !empty($_REQUEST['item']) )
    {
        ?>
    showProducts('<?php echo $_REQUEST['item'];?>');
        <?php
    }
    ?>

    $(function(){
        $('#province').change(function(e) {
            showDistricts('');
        });
    })
	$(function(){
        $('#stakeholder').change(function(e) {
			$('#item').html('<option value="">Select</option>');
            showProducts('');
        });
    })
    $("#approve_status").click(function(){
		var id,warehouse;
		id=$("#clr6_id").val();
		warehouse=$("#warehouse").val();
		window.open('../../plmis_admin/print_approve_clr6.php?id='+id+'&wh_id='+warehouse,'_blank', 'scrollbars=1,width=842,height=595');
	});
    function showDistricts(did){
        var pid = $('#province').val();
        if ( pid != '' )
        {
            $.ajax({
                url: 'fetchDistricts.php',
                type: 'POST',
                data: {pid: pid, distId:did},
                success: function(data){
                    $('#districtsCol').html(data);
					var test = '<option value="">All</option>'+$('#districts').html();
					$('#districts').html(test);
                }
            })
        }
    }
    function showProducts(pid){
        var stk = $('#stakeholder').val();
		$.ajax({
			url: 'ajax_calls.php',
			type: 'POST',
			data: {stakeholder: stk, productId:pid},
			success: function(data){
				$('#item').html(data);
			}
		})
    }
</script>
<?php
if (isset($_SESSION['e']) && $_SESSION['e'] == '0') {
    ?>
        <script>
            var self = $('[data-toggle="notyfy"]');
            notyfy({
                force: true,
                text: 'Requisition has been successfully deleted',
                type: 'success',
                layout: self.data('layout')
            });
        </script>
<?php } ?>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>