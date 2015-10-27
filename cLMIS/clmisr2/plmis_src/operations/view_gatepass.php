<?php
/***********************************************************************************************************
Developed by: Farjad Hasan
email: farjadjsi@gmail.com
This is the file used for gatepass
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");
include("../../plmis_admin/Includes/functions.php");
Login();

$whId = $_SESSION['wh_id'];

// If Gate Pass is deleted
if ( isset($_REQUEST['id']) )
{
	$gpId = base64_decode($_REQUEST['id']);
	// Delete gatepass master and detail
	mysql_query("DELETE FROM gatepass_detail WHERE gatepass_detail.gatepass_master_id = $gpId");
	mysql_query("DELETE FROM gatepass_master WHERE gatepass_master.pk_id = $gpId");
	
	$qry = "";
	$_SESSION['e'] = 1;
    echo "<script>window.location='view_gatepass.php'</script>";
	//header("Location: view_gatepass.php");
	exit;
}

$where = 'WHERE 1=1';

if (isset($_POST['submit'])) {
	
	$vehicleType = $_POST['vehicle_type'];
	$vehicleText = $_POST['vehicleText'];
	$vehicleList = $_POST['vehicleList'];
	
	if ( !empty($_POST['date_from']) && !empty($_POST['date_to']) )
	{
		$where .= " AND DATE_FORMAT(gatepass_master.transaction_date,'%d/%m/%Y') BETWEEN '" . $_POST['date_from'] . "' AND '" . $_POST['date_to'] . "'";
		$fromDate = $_POST['date_from'];
		$toDate = $_POST['date_to'];
	}
	if ( $vehicleType == -1 )
	{
		$where .= " AND gatepass_vehicles.number LIKE '%".$vehicleText."%' ";
	}
	else
	{
		$where .= " AND gatepass_vehicles.pk_id = " . $vehicleList;
	}
}
else
{	
	$fromDate = date('01/m/Y');
	$toDate = date('d/m/Y');
	$vehicleType = '';
	$vehicleText = '';
	$vehicleList = '';
	
	$where .= " AND DATE_FORMAT(gatepass_master.transaction_date,'%d/%m/%Y') BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
}

$qry = "SELECT
			@n := @n + 1 AS srNo,
			gatepass_master.pk_id AS gateID,
			gatepass_master.number AS gatepassNum,
			DATE_FORMAT(gatepass_master.transaction_date,'%d/%m/%Y') AS transDate,
			gatepass_vehicles.number AS vehNum
		FROM
			(select @n:=0) AS initvars,gatepass_master
			INNER JOIN gatepass_vehicles ON gatepass_master.gatepass_vehicle_id = gatepass_vehicles.pk_id
		$where";
$rs_qry = mysql_query($qry);
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
<link rel="stylesheet" type="text/css" media="all" href="../../plmis_css/jsDatePick_ltr.css"/>
<style>
table#myTable tr td{padding-left:10px;}
</style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-container">
<?php include "../../plmis_inc/common/_top.php";?>
<?php include "../../plmis_inc/common/top_im.php";?>


<div class="page-content-wrapper">
<div class="page-content">

<!-- BEGIN PAGE HEADER-->


<div class="row">
<div class="col-md-12">
<div class="widget" data-toggle="collapse-widget">
        <div class="widget-head">
            <h3 class="heading">View Gatepass</h3>
        </div>
        <div class="widget-body">

            <form name="frm" id="frm" action="" method="post">
                <table cellpadding="7" cellspacing="5" id="myTable">
                    <tr>
                        <td class="sb1NormalFont">
                            <label>Date From</label>
                            <input class="form-control input-small" type="text" name="date_from" id="date_from" value="<?php if (isset($fromDate)) echo $fromDate; ?>" readonly/>
                        </td>
                        <td class="sb1NormalFont">
                            <label>Date To</label>
                            <input class="form-control input-small" type="text" name="date_to" id="date_to" value="<?php if (isset($toDate)) echo $toDate; ?>" readonly/>
                        </td>
                        <td class="sb1NormalFont">
                            <label>Vehicle Type</label>
                            <select name="vehicle_type" id="vehicle_type" class="form-control input-medium">
                                <?php
                                $qry = mysql_query("SELECT
														gatepass_vehicle_types.pk_id,
														gatepass_vehicle_types.vehicle_type
													FROM
														gatepass_vehicle_types
													ORDER BY
													gatepass_vehicle_types.vehicle_type ASC");
                                while ($row = mysql_fetch_array($qry)) {
                                    ?>
                                    <option value="<?php echo $row['pk_id'];?>" <?php if ($row['pk_id'] == $vehicleType) echo 'selected="selected"'; else echo " "; ?>><?php echo $row['vehicle_type'];?></option>
                                    <?php
                                }
                                ?>
                                <option value="-1" <?php echo ($vehicleType == -1) ? 'selected="selected"' : ''; ?>>Other</option>
                            </select>
                        </td>
                        <td class="sb1NormalFont">
                            <label>Vehicle</label>
                            <select name="vehicleList" id="vehicleList" style="display:none;" class="form-control input-medium">
                                <option value="">Select</option>
                            </select>
                            <input name="vehicleText" id="vehicleText" style="display:none;" class="form-control input-medium" value="<?php echo $vehicleText;?>" />
                        </td>
                        <td colspan="4" style="padding-top:18px;"><input type="submit" name="submit" value="Search" class="btn btn-primary"/></td>
                    </tr>

                </table>
            </form>
        </div>
</div>
</div>
</div>
        
        <div class="row">
            	<div class="col-md-12">
            <div class="widget" data-toggle="collapse-widget">
        <div class="widget-head">
            <h3 class="heading">New Gatepass</h3>
        </div>
        <div class="widget-body">
            <?php
			if ($rs_qry != FALSE && mysql_num_rows($rs_qry) > 0) { ?>
            <table class="dynamicTable table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th style="text-align:center;" width="60px">Sr No.</th>
                    <th>Gate Pass Number</th>
                    <th>Transaction Date</th>
                    <th>Vehicle Number</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rsPro = mysql_fetch_array($rs_qry)) {
                        ?>
                    <tr>
                        <td style="text-align:center;"><?php echo $rsPro['srNo']; ?></td>
                        <td><a onClick="window.open('../../plmis_admin/printGatePass.php?id=<?php echo $rsPro['gateID'];?>', '_blank', 'scrollbars=1,width=842,height=595');" href="javascript:void(0);"><?php echo $rsPro['gatepassNum']; ?></a></td>
                        <td><?php echo $rsPro['transDate']; ?></td>
                        <td><?php echo $rsPro['vehNum']; ?></td>
                        <td class="center"><a href='view_gatepass.php?id=<?php echo base64_encode($rsPro['gateID']);?>' onClick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                        </td>
                    </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
		else
		{
			echo "New record found";
		}
            ?>
        </div>
            </div>
    </div>
</div>
</div></div></div>
<?php include "../../plmis_inc/common/right_inner.php";?>
<?php include "../../plmis_inc/common/footer.php";?>
<script type="text/javascript" src="../../plmis_js/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
    window.onload = function () {
         $('#date_from').datepicker({
		dateFormat: "dd/mm/yy",
		constrainInput: false,
		maxDate: 0,
        changeMonth: true,
        changeYear: true,
	});
        $('#date_to').datepicker({
		dateFormat: "dd/mm/yy",
		constrainInput: false,
		maxDate: 0,
        changeMonth: true,
        changeYear: true,
	});
    };
</script>
<script>
    $(function () {
        getVehicles('<?php echo $_POST['vehicle'];?>');

        $('#vehicle_type').change(function (e) {
            getVehicles('');
        });
        $('#searchBtn').click(function (e) {
            if ($('#date_from').val() == '') {
                alert('Please select date from');
                return false;
            }
            if ($('#date_to').val() == '') {
                alert('Please select date to');
                return false;
            }
            showTransactions();
        });
        $('#vehicle_other_option').click(function (e) {
            if ($('#vehicle_other_option').is(':checked')) {
                $('#vehicle_other').show();
                $('#vehicle').hide();
            }
            else {
                $('#vehicle_other').hide();
                $('#vehicle').show();
            }
        });
        $('#issue_no').change(function (e) {
            var issueNum = $('#issue_no').val();
            $.ajax({
                url:'gatepass_ajax.php',
                type:'POST',
                data:{issueNum:issueNum},
                success:function (data) {
                    $('#gridData').html(data);
                    $('#row3').show();
                }
            })
        });
    })
    function getVehicles(vNum) {
        var vehicleType = $('#vehicle_type').val();
        if (vehicleType != '' && vehicleType != -1) {
            $.ajax({
                url:'gatepass_ajax.php',
                type:'POST',
                data:{vehicleType:vehicleType, vNum:vNum},
                success:function (data) {
					$('#vehicleList').show();
					$('#vehicleText').hide();
                    $('#vehicleList').html(data);
                }
            })
        }
        else {
			//if (vehicleType == -1)
			{
				$('#vehicleList').hide();
				$('#vehicleText').show();
			}
			
        }
    }
    function showTransactions() {
        var dateFrom = $('#date_from').val();
        var dateTo = $('#date_to').val();
        $.ajax({
            url:'gatepass_ajax.php',
            type:'POST',
            data:{dateFrom:dateFrom, dateTo:dateTo},
            success:function (data) {
                if (data != '') {
                    $('#issue_no').html(data);
                    $('#row1').show();
                    $('#row2').show();
                    $('#eMsg').html('');
                }
                else {
                    $('#eMsg').html('No record found.');
                    $('#row1').hide();
                    $('#row2').hide();
                }
            }
        })
    }
</script>
</div>
</div>
</div>
</div>
<?php include "../../plmis_inc/common/footer_template.php";?>
<?php
if (isset($_SESSION['e'])) {
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'Gate Pass deleted successfully!',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php 
	unset($_SESSION['e']);
}
?>
</body>
<!-- END BODY -->
</html>