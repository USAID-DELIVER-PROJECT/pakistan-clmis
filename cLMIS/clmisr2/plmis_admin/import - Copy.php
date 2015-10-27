<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");
include "../plmis_inc/common/_header.php";

$month = '';
$year = '';
if ( $_POST['submit'] )
{
	$error = '';
	$row = 1;
	$month = mysql_real_escape_string($_POST['month']);
	$year = mysql_real_escape_string($_POST['year']);
	$filename = $_FILES['data_file']['tmp_name'];
	if (($handle = fopen($filename, "r")) !== FALSE)
	{
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
		{
			$num = count($data);
			
			if ( $num != 8 )
			{
				$error .= "Incorrect number of Columns in row $row <br />";
			}
			else
			{
				if( $row > 1 )
				{
					
					$ob = str_replace(',', '', empty($data[2]) ? 0 : $data[2]);
					$rcv = str_replace(',', '', empty($data[3]) ? 0 : $data[3]);
					$issue = str_replace(',', '', empty($data[4]) ? 0 : $data[4]);
					$adj_a = str_replace(',', '', empty($data[5]) ? 0 : $data[5]);
					$adj_b = str_replace(',', '', empty($data[6]) ? 0 : $data[6]);
					$cb = str_replace(',', '', empty($data[7]) ? 0 : $data[7]);
					$closing = ($ob + $rcv + $adj_a) - ($issue + $adj_b);
					
					if( !is_numeric($ob) || !is_numeric($rcv) || !is_numeric($issue) || !is_numeric($adj_a) || !is_numeric($adj_b) || !is_numeric($cb) )
					{
						$error .= "Invalid data in row $row. Make sure you have entered numeric data. <br />";
					}
					else if ($closing != $cb)
					{
						$error .= "Incorrect closing balance in row $row <br />";
					}
					else
					{
						$whIdArr[] = $data[0];
						$itmIdArr[] = $data[1];
						$obArr[] = (int) str_replace(',', '', $data[2]);
						$rcvArr[] = (int) str_replace(',', '', $data[3]);
						$issueArr[] = (int) str_replace(',', '', $data[4]);
						$adj_aArr[] = (int) str_replace(',', '', $data[5]);
						$adj_bArr[] = (int) str_replace(',', '', $data[6]);
						$cbArr[] = (int) str_replace(',', '', $data[7]);
					}
				}
			}
			$row++;
		}
		fclose($handle);
	}
	
	if ( $error == '' )
	{
		for( $i=0; $i<=count($whIdArr); $i++ )
		{
			// Check if data already exists
			$qry = "SELECT
						tbl_wh_data.w_id
					FROM
						tbl_wh_data
					INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
					WHERE
						tbl_wh_data.report_month = $month
					AND tbl_wh_data.report_year = $year
					AND tbl_wh_data.item_id = '".$itmIdArr[$i]."'
					AND tbl_wh_data.wh_id = $whId[$i]
					AND tbl_warehouse.stkid = '".$_SESSION['userdata'][7]."' ";
			
			if (mysql_num_rows(mysql_query($qry)) > 0)
			{
				$qry = "DELETE tbl_wh_data.*
					FROM
						tbl_wh_data,
						tbl_warehouse
					WHERE
						tbl_warehouse.wh_id = tbl_wh_data.wh_id
					AND tbl_wh_data.report_month = $month
					AND tbl_wh_data.report_year = $year
					AND tbl_wh_data.item_id = '".$itmIdArr[$i]."'
					AND tbl_wh_data.wh_id = $whId[$i]
					AND tbl_warehouse.stkid = '".$_SESSION['userdata'][7]."' ";
				mysql_query($qry);
			}
			if (!empty($whIdArr[$i]) && !empty($whIdArr[$i]))
			{
				$qry = "INSERT INTO tbl_wh_data
					SET
						tbl_wh_data.report_month = $month,
						tbl_wh_data.report_year = $year,
						tbl_wh_data.item_id = '".$itmIdArr[$i]."',
						tbl_wh_data.wh_id = '".$whIdArr[$i]."',
						tbl_wh_data.wh_obl_a = '".$obArr[$i]."',
						tbl_wh_data.wh_received = '".$rcvArr[$i]."',
						tbl_wh_data.wh_issue_up = '".$issueArr[$i]."',
						tbl_wh_data.wh_cbl_a = '".$cbArr[$i]."',
						tbl_wh_data.wh_adja = '".$adj_aArr[$i]."',
						tbl_wh_data.wh_adjb = '".$adj_bArr[$i]."',
						tbl_wh_data.RptDate = '".$year.'-'.$month."-01',
						tbl_wh_data.add_date = '".date('Y-m-d H:i:s')."',
						tbl_wh_data.last_update = '".date('Y-m-d H:i:s')."',
						tbl_wh_data.ip_address = '".$_SERVER['REMOTE_ADDR']."',
						tbl_wh_data.created_by = '".$_SESSION['userdata'][0]."'";
				mysql_query($qry);
			}
		}
		$_SESSION['msg'] = 'Data imported successfully.';
		echo "<script>window.location='import.php'</script>";
		exit;
	}
	else
	{
		$_SESSION['error'] = $error;
		echo "<script>window.location='import.php'</script>";
		exit;
	}
}

?>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" >
<!-- BEGIN HEADER -->
<div class="page-container">
<?php
include "../plmis_inc/common/top_im.php";
include "../plmis_inc/common/_top.php";
?>
    <div class="page-content-wrapper">
        <div class="page-content"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="widget">
                        <div class="widget-head">
                            <h3 class="heading">Import Data</h3>
                        </div>
                        <div class="widget-body">
							<form name="frm" id="frm" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                    	<a href="./template/sample.csv">Download Sample</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <div class="control-group">
                                            	<label class="control-label">Month</label>
                                                <div class="controls">
                                                	<select name="month" id="month" class="form-control input-sm">
                                                    <?php
                                                    for ($i = 1; $i <= 12; $i++)
													{	
														$sel = ((date('m')-1) == $i) ? 'selected="selected"' : '';
													?>
                                                    	<option value="<?php echo $i; ?>" <?php echo $sel;?>><?php echo date('M', mktime(0, 0, 0, $i, 1)); ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="control-group">
                                            	<label class="control-label">Month</label>
                                                <div class="controls">
                                                	<select name="year" id="year" class="form-control input-sm">
                                                    <?php
                                                    for ($i = date('Y'); $i >= 2010; $i--)
													{
													?>
                                                    	<option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group">
                                            	<label class="control-label">Select Data File(CSV)</label>
                                                <div class="controls">
                                                    <input type="file" name="data_file" id="data_file" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="control-group">
                                            	<label class="control-label">&nbsp;</label>
                                                <div class="controls">
                                                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Import</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="row">
                                	<div class="col-md-12">
                                        <div class="col-md-12" style="color:#F00;">
											<?php
                                            echo $_SESSION['error'];
											unset($_SESSION['error']);
											?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- // Content END --> 
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "../plmis_inc/common/footer.php";?>
<script>
$.validator.addMethod("extension", function(value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, $.validator.format("Please select csv file."));

$("#frm").validate({ 
    onfocusout: function(e) {
        this.element(e);
    },
    rules:{
        data_file:{
            required:true,
            extension: "csv"
        }
    }
});
</script>
<?php
if (!empty($_SESSION['msg']))
{
	?>
	<script>
		var self = $('[data-toggle="notyfy"]');
		notyfy({
			force: true,
			text: 'Data imported successfully',
			type: 'success',
			layout: self.data('layout')
		});
	</script>
<?php 
	unset($_SESSION['msg']);
} ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>