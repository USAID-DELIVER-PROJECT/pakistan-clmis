<?php
/***********************************************************************************************************
Developed by Syed Aun Irtaza email: aun.irtaza@hotmail.com
This is the file used to add/edit/delete the contents from tbl_cms. It has two forms one for adding the records and other
for editing the record.
we are taking 4 cases. one case to show add form, second case to show edit form, third case to save posted
data entered through add form and fourth save the data enterd from the edit form
/***********************************************************************************************************/
include("../../html/adminhtml.inc.php");

Login();



//var_dump($_SESSION);

$qryRes = mysql_query("SELECT
						CONCAT(DATE_FORMAT(clr_master.date_from, '%b-%Y'),' to ',DATE_FORMAT(clr_master.date_to, '%b-%Y')) AS duration,
						DATE_FORMAT(clr_master.requested_on,'%d/%m/%Y') AS requested_on,
						MONTH (clr_master.date_to) AS clrMonth,
						YEAR (clr_master.date_to) AS clrYear,
						clr_master.pk_id,
						clr_master.wh_id
					FROM
						clr_master
					WHERE
						clr_master.wh_id = ".$_SESSION['userdata'][5]."
					GROUP BY
						clr_master.date_from
					ORDER BY
						clrYear DESC,
						clrMonth DESC");
$num = mysql_num_rows($qryRes);
?>
<?php include "../../plmis_inc/common/_header.php"; ?>
<style>
    body{margin:0px !important;font-family:Arial,Helvetica,sans-serif; }
    table#myTable{margin-top:20px;border-collapse: collapse;border-spacing: 0;}
    table#myTable tr td, table#myTable tr th{font-size:13px;padding-left:5px; text-align:left; border:1px solid #999;}
    table#myTable tr td.TAR{text-align:right; padding:5px;width:50px !important;}
    .sb1NormalFont {
        color: #444444;
        font-family: Verdana,Arial,Helvetica,sans-serif;
        font-size: 13px;
        font-weight: bold;
        text-decoration: none;
    }
    p{margin-bottom:5px; font-size:13px !important; line-height:1 !important; padding:0 !important;}
    table#headerTable tr td{ font-size:13px;}
</style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content">
<!-- BEGIN HEADER -->
<div class="page-container">
  <?php  include "../../plmis_inc/common/top_im.php"; ?>
<?php include "../../plmis_inc/common/_top.php";?>


<div class="page-content-wrapper">
<div class="page-content">

<!-- BEGIN PAGE HEADER-->

<div class="row">
<div class="col-md-12">
    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                           <h3 class="heading">View Requisition</h3>
                        </div>
                        <div class="widget-body">
                <table width="100%" align="center" cellspacing="3" cellpadding="5" id="myTable" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th width="50">Sr. No.</th>
                        <th>Duration</th>
                        <th>Requested On</th>
                        <th width="50" style="text-align:center;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($num > 0)
                    {
                        $counter = 1;
                        while ($row = mysql_fetch_array($qryRes))
                        {
                            ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $counter++;?></td>
                            <td><?php echo $row['duration'];?></td>
                            <td><?php echo $row['requested_on'];?></td>
                            <td style="text-align:center;"><a href="clr_view.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">View</a></td>
                        </tr>
                            <?php
                        }
                    }
                    else
                    {
                        echo '<tr><td colspan="4">No record found</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<style>
    .input_select {
        border: #D1D1D1 1px solid;
        color: #474747;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 24px;
        max-width: 150px;
    }

    .input_button {
        border: #D1D1D1 1px solid;
        background-color: #006700;
        color: #FFFFFF;
        height: 25px;
        font-family: Arial, Helvetica, sans-serif;
        vertical-align: bottom;
        width: 60px;
    }
</style>

</div>
</div>
</div>


</div>


    <?php include "../../plmis_inc/common/footer_template.php";?>
    <?php include "../../plmis_inc/common/footer.php";?>
<script>
    $(function () {
        $('#sector').change(function (e) {
            var val = $('#sector').val();
            getStakeholder(val, '');
        });
        getStakeholder('<?php echo $rptType;?>', '<?php echo $sel_stk;?>');
    })
</script>

<?php
if (isset($_REQUEST['e']) && $_REQUEST['e'] == '1') {
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'CLR-6 is successfully saved',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php } ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>