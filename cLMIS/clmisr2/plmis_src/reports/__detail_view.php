<?php
include("../../html/adminhtml.inc.php");
$report_id = "SDISTRICTREPORT";
$report_title = "District Report";
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File


if (isset($_REQUEST['param']))
{
	$_POST['repIndicators'].'|'.$_POST['year_sel'].'|'.$i.'|'.$_POST['stk_sel'].'|'.$_POST['prov_sel'];
	$var = explode('|', ($_REQUEST['param']));
	$ind = $var[0];
	$year = $var[1];
	$month = $var[2];
	$stk = $var[3];
	$prov = $var[4];
	$itm = $var[5];
	$itmName = $var[6];
	$extra = !empty($var[7]) ? $var[7] : '';
	
	if ($prov != 'all')
	{
		$provClause = " AND tbl_warehouse.prov_id = '".$prov."' ";
		// Get Province name
		$getProv = mysql_fetch_array(mysql_query("SELECT
													tbl_locations.LocName
												FROM
													tbl_locations
												WHERE
													tbl_locations.PkLocID = $prov"));
		$province = $getProv['LocName'];
	}
	else
	{
		$provClause = '';
		$province = 'All';
	}
	
	if ($stk != 'all')
	{
		$stkClause = " AND tbl_warehouse.stkid = '".$stk."' ";
		// Get stakeholder name
		$getStk = mysql_fetch_array(mysql_query("SELECT
													stakeholder.stkname
												FROM
													stakeholder
												WHERE
													stakeholder.stkid = $stk"));
		$stakeholder = $getStk['stkname'];
	}
	else
	{
		$stkClause = '';
		$stakeholder = 'All';
	}
	
	if($ind == 1)
	{
		$indicator = 'Consumption';
		$qry = "SELECT
					SUM(tbl_wh_data.wh_issue_up) AS total,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					tbl_wh_data.item_id = '".$itm."'
				AND tbl_wh_data.report_month = '".$month."'
				AND tbl_wh_data.report_year = '".$year."'
				AND stakeholder.lvl = 4
				".$provClause."
				".$stkClause."
				GROUP BY
					tbl_warehouse.dist_id";
	}
	
	if($ind == 2)
	{
		$indicator = 'Stock on Hand';
		$qry = "SELECT
					SUM(tbl_wh_data.wh_cbl_a) AS total,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					tbl_wh_data.item_id = '".$itm."'
				AND tbl_wh_data.report_month = '".$month."'
				AND tbl_wh_data.report_year = '".$year."'
				AND stakeholder.lvl >= 2
				".$provClause."
				".$stkClause."
				GROUP BY
					tbl_warehouse.dist_id";
	}
	
	if($ind == 3)
	{
		$indicator = 'CYP';
		$qry = "SELECT
					SUM(tbl_wh_data.wh_issue_up) AS total,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					tbl_wh_data.item_id = '".$itm."'
				AND tbl_wh_data.report_month = '".$month."'
				AND tbl_wh_data.report_year = '".$year."'
				AND stakeholder.lvl = 4
				".$provClause."
				".$stkClause."
				GROUP BY
					tbl_warehouse.dist_id";
	}
	
	if($ind == 4)
	{
		$indicator = 'Received(District)';
		$qry = "SELECT
					SUM(tbl_wh_data.wh_received) AS total,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					tbl_wh_data.item_id = '".$itm."'
				AND tbl_wh_data.report_month = '".$month."'
				AND tbl_wh_data.report_year = '".$year."'
				AND stakeholder.lvl = 3
				".$provClause."
				".$stkClause."
				GROUP BY
					tbl_warehouse.dist_id";
	}
	
	if($ind == 5)
	{
		$indicator = 'Received(Field)';
		$qry = "SELECT
					SUM(tbl_wh_data.wh_received) AS total,
					tbl_locations.LocName
				FROM
					tbl_warehouse
				INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN tbl_locations ON tbl_warehouse.dist_id = tbl_locations.PkLocID
				WHERE
					tbl_wh_data.item_id = '".$itm."'
				AND tbl_wh_data.report_month = '".$month."'
				AND tbl_wh_data.report_year = '".$year."'
				AND stakeholder.lvl = 4
				".$provClause."
				".$stkClause."
				GROUP BY
					tbl_warehouse.dist_id";
	}
	
	$qryRes = mysql_query($qry);
}
?><style>
*{font-family:Verdana,Arial,Helvetica,sans-serif;; font-size:13px; line-height:1.5;}
table#myTable{margin-top:20px;border-collapse: collapse;border-spacing: 0;}
table#myTable tr:hover{background: #FFFACD}
table#myTable tr:nth-child(even) {background: #DFF2A9}
table#myTable tr:nth-child(odd) {background: #FFF}
table#myTable tr th{padding-left:5px; border:1px solid #999; background:#BDDD83;}
table#myTable tr td{padding-left:5px; border:1px solid #999;}
table#myTable tr td.TAR{text-align:right; padding:5px;width:50px !important;}
table#myTable tr td.TAC{text-align:center; padding:5px;width:50px !important;}
.sb1NormalFont {
	color: #444444;
	font-family: Verdana,Arial,Helvetica,sans-serif;
	font-size: 12px;
	font-weight: bold;
	text-decoration: none;
}
</style>
<!-- Content -->
<div id="content" style="margin-left:0;">    
	<h2 align="center" style=" background:url(../../plmis_img/grn-top-bg.jpg); background-repeat:repeat-x; font-family:Verdana, Geneva, sans-serif; font-weight:bold; color:#FFF; height:21px;">District wise <?php echo $indicator;?></h2>    
    
	<b class="sb1NormalFont">Province: </b><?php echo $province;?> 
	<b class="sb1NormalFont">Stakeholder: </b><?php echo $stakeholder;?>
	<b class="sb1NormalFont">Product: </b><?php echo $itmName;?>
    <b class="sb1NormalFont">Date: </b><?php echo date('M-Y', strtotime($year.'-'.$month));?>
    
    <table width="100%"cellpadding="3" id="myTable">
        <thead>
            <tr>
                <th width="30">Sr. No.</th>
                <th>District</th>
                <th width="130"><?php echo $indicator;?></th>
            </tr>
        </thead>
        <tbody>
        <?php
		$counter = 1;
		$total = 0;
        while ($row = mysql_fetch_array($qryRes))
		{
			$val = ($ind == 3) ? ($row['total'] * $extra) : $row['total'];
			
			$total += $val;
		?>
        	<tr>
            	<td class="TAC"><?php echo $counter++;?></td>
                <td><?php echo $row['LocName'];?></td>
                <td class="TAR"><?php echo ($ind == 3) ? number_format($val, 3) : number_format($val);?></td>
            </tr>
        <?php
		}
		?>
        </tbody>
        <tfoot>
        	<tr>
            	<th colspan="2" align="right">Total</th>
            	<th style="text-align:right;"><?php echo number_format($total);?></th>
            </tr>
        </tfoot>
    </table>
</div>