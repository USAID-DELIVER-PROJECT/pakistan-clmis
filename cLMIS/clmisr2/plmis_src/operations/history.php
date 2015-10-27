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
function dateToDbFormat($date)
{
	if(!empty($date)){
		list($dd,$mm,$yy) = explode("/",$date);
		return $yy."-".$mm."-".$dd;
	}    
}
if (isset($_REQUEST['submit']))
{
	$where = 'WHERE 1=1';
	if (isset($_REQUEST['province']) && !empty($_REQUEST['province']))
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
		
		$where .= " AND clr_master.requested_on BETWEEN '$date_from1' AND '$date_to1'";
	}
	$where .= " AND clr_master.requisition_to = ".$_SESSION['userdata'][5]." ";
	
	$qry = "SELECT
				stakeholder.stkname,
				clr_master.pk_id,
				clr_master.requisition_num,
				clr_master.wh_id,
				clr_master.fk_stock_id,
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
}


$num = mysql_num_rows($qryRes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $system_title." - "?>CLR-6</title>
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
.input_button{
	border:#D1D1D1 1px solid;
	background-color:#006700;
	color:#FFFFFF;
	height:25px;	
	font-family:Arial, Helvetica, sans-serif;
	vertical-align:bottom;
	width:auto;
}
input[type="text"]{
	width:90px;
}
select{
	width: 110px;
	max-width:110px !important;
}
input[type="text"], select, textarea {
    border: 1px solid #999999 !important;
	-moz-box-shadow:0px 0px 3px #999999 !important;
    -webkit-box-shadow:0px 0px 3px #999999 !important;
    box-shadow:0px 0px 3px #999999 !important;
}
p{padding:0px !important;}
</style>
</head>
<body>
<?php include "../../plmis_inc/common/top.php";?>
<link rel="stylesheet" type="text/css" media="all" href="../../plmis_css/jsDatePick_ltr.css" />

    <div class="body_sec">
        <div class="wrraper" style="height:auto; padding-left:5px">
            <div class="content" style="min-height:679px;"><br />
                <?php showBreadCrumb();?>
                <br />
                <br />
                <form name="frm" id="frm" action="" method="get">
                    <table align="center" cellspacing="3" cellpadding="5" style="text-align:left; font-size:13px;">
                        <thead>
                            <tr>
                                <th width="130">Province</th>
                                <th width="130">District</th>
                                <th width="130">Stakeholder</th>
                                <th width="130" style="display:none;">Item</th>
                                <th width="130">Status</th>
                                <th width="130">From</th>
                                <th width="130">To</th>
                                <th width="130">Requisitions #</th>
                                <th width="200">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="province" id="province">
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
                                </td>
                                <td id="districtsCol">
                                    <select name="districts" id="districts">
                                    	<option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="stakeholder" id="stakeholder">
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
                                </td>
                                <td style="display:none;">
                                    <select name="item" id="item">
                                    	<option value="">Select</option>
									<?php
                                    $querypro = "SELECT
													itmrec_id,
													itm_id,
													itm_name
												FROM
													itminfo_tab
												WHERE
													itm_status = 'Current'
												AND itminfo_tab.itmrec_id NOT IN ('IT-010', 'IT-014', 'IT-012')
												ORDER BY
													frmindex";
                                    $rspro = mysql_query($querypro) or die();
                                    while ($rowpro = mysql_fetch_array($rspro))
									{
										if ($rowpro['itmrec_id'] == $sel_item)
											$sel = "selected='selected'";
										else
											$sel = "";
                                    ?>
                                    	<option value="<?php echo $rowpro['itmrec_id']; ?>" <?php echo $sel; ?>><?php echo $rowpro['itm_name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="status" id="status">
                                    	<option value="">Select</option>
                                    	<option value="All" <?php echo ($status == 'All') ? 'selected="selected"' : '';?>>All</option>
                                    	<option value="Pending" <?php echo ($status == 'Pending') ? 'selected="selected"' : '';?>>Pending</option>
                                    	<option value="Issued" <?php echo ($status == 'Issued') ? 'selected="selected"' : '';?>>Issued</option>
                                    </select>
                                </td>
                                <td>
                                	<input type="text" name="date_from" id="date_from" class="input_select" value="<?php echo $date_from;?>" readonly="readonly" />
                                </td>
                                <td>
                                	<input type="text" name="date_to" id="date_to" value="<?php echo $date_to;?>" readonly="readonly" />
                                </td>
                                <td>
                                	<input type="text" name="req_num" id="req_num" value="<?php echo $req_num;?>" />
                                </td>
                                <td>
                                	<input type="submit" name="submit" value="Go" class="input_button" />
                                	<input type="button" onclick="window.location='<?php echo $_SERVER['PHP_SELF'];?>'" value="Reset" class="input_button" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <table width="100%" align="center" cellspacing="3" cellpadding="5" id="myTable">
                	<thead>
                    	<tr>
                        	<th width="50">Sr. No.</th>
                        	<th width="100">Stakeholder</th>
                        	<th width="">Store Name</th>
                        	<th width="150">District</th>
                        	<th width="150">Requested Time</th>
                        	<th width="70">Status</th>
                        	<th width="150" style="text-align:center;">Action</th>
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
                        	<td><?php echo !empty($row['fk_stock_id']) ? "Issued" : 'Pending';;?></td>
                        	<td style="text-align:center;">
                            	<a href="clr_view.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">Details</a> |
                            	<a href="issue.php?id=<?php echo $row['pk_id'];?>&wh_id=<?php echo $row['wh_id'];?>">Issue</a> | 
                            	<a href="">History</a>
                            </td>
                        </tr>
                    <?php
					}
				}
				else
				{
					echo '<tr><td colspan="7">No record found</td></tr>';
				}
					?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include "../../plmis_inc/common/right_inner.php";?>
<?php include "../../plmis_inc/common/footer.php";?>
<script type="text/javascript" src="../../plmis_js/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"date_from",
			dateFormat:"%d/%m/%Y"
			/*selectedDate:{				This is an example of what the full configuration offers.
				day:5,						For full documentation about these settings please see the full version of the code.
				month:9,
				year:2006
			},
			yearsRange:[1978,2020],
			limitToToday:false,
			cellColorScheme:"beige",
			dateFormat:"%m-%d-%Y",
			imgPath:"img/",
			weekStartDay:1*/
		});
		
		new JsDatePick({
			useMode:2,
			target:"date_to",
			dateFormat:"%d/%m/%Y"
		});
	};
</script>
<script>
	<?php
	if ( isset($_REQUEST['districts']) && !empty($_REQUEST['districts']) )
	{
	?>
		showDistricts('<?php echo $_REQUEST['districts'];?>');
	<?php
	}
	?>

	$(function(){
		$('#province').change(function(e) {
            showDistricts('');
        });
	})
	
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
				}
			})
		}
	}
</script>
</body>
</html>