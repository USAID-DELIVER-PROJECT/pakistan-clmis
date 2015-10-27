<?php
ob_start();
include("../../html/adminhtml.inc.php");
include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File
Login();

if ( isset($_POST['submit']) )
{
	$year_sel = $_POST['year_sel'];
	$prov_sel = $_POST['prov_sel'];
	$stk_sel = $_POST['stk_sel'];
}

startHtml($system_title." - Stock Balance Comparison Report");?>
<style>
table tr td {font-size:13px; max-width:200px !important; padding-left:15px; text-align:left; padding:5px;}
.input_button{
	border:#D1D1D1 1px solid;
	background-color:#006700;
	color:#FFFFFF;
	height:25px;	
	font-family:Arial, Helvetica, sans-serif;
	vertical-align:bottom;
	width:60px;
}
table#myTable tr td{padding-left:5px;border:#D1D1D1 1px solid;}
</style>

<script>
function openPopUp(pageURL)
{
	window.open(pageURL, '_blank', 'scrollbars=1,width=600,height=595');	
}
</script>

<body text="#000000" bgColor="#FFFFFF" style="margin-left:0px;margin-top:0px,margin-right:0px;margin-bottom:0px; overflow:visible;">
	<?php include "../../plmis_inc/common/top.php";?>
    <?php //include "../../plmis_inc/common/left.php";?>
    <div class="body_sec">
  
		<div class="wrraper" style="height:auto; padding-left:5px">
		<div class="content" align=""><br>
		<?php  showBreadCrumb();?><div style="float:right; padding-right:3px"><?php //echo readMeLinks($readMeTitle);?></div><br><br>
		
		<form name="frm" id="frm" action="" method="post">
            <table id="myTable1">
                <tr>
                    <td style="width:80px;">
                        <label>Year</label>
                        <select name="year_sel" id="year_sel" class="span15">
                        <?php
                        for ($j = date('Y'); $j >= 2010; $j--) {
                            if ($year_sel == $j) {
                                $sel = "selected='selected'";
                            }else {
                                $sel = "";
                            }
                            ?>
                                <option value="<?php echo $j; ?>" <?php echo $sel; ?> ><?php echo $j;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td style="width:130px;">
                        <label>Province</label>
                        <select name="prov_sel" id="prov_sel" required style="width:120px;">
                        <option value="">Select</option> 
						<?php
                        $queryprov = "SELECT
										tbl_locations.PkLocID,
										tbl_locations.LocName
									FROM
										tbl_locations
									WHERE
										tbl_locations.ParentID IS NOT NULL
									AND tbl_locations.LocLvl = 2
									ORDER BY
										tbl_locations.PkLocID ASC";
                        $rsprov = mysql_query($queryprov) or die();
                        while ($rowprov = mysql_fetch_array($rsprov))
						{
							if ($prov_sel == $rowprov['PkLocID'])
								$sel = "selected='selected'";
							else
								$sel = "";
                        ?>
                            <option value="<?php echo $rowprov['PkLocID']; ?>" <?php echo $sel;?>><?php echo $rowprov['LocName']; ?></option>
						<?php
                        }
                        ?>
                        </select>
                    </td>
                    <td style="width:140px">
                    	<label>Stakeholder</label>
                        <select name="stk_sel" id="stk_sel" required style="width:130px">
                        	<option value="">Select</option>
							<?php
                            $querystk = "SELECT stkid,stkname FROM stakeholder Where ParentID is null $stkFilter order by stkorder";
                            $rsstk = mysql_query($querystk) or die();
                            while ($rowstk = mysql_fetch_array($rsstk))
							{
								if ($stk_sel == $rowstk['stkid'])
									$sel = "selected='selected'";
								else
									$sel = "";
                            ?>
                            <option value="<?php echo $rowstk['stkid'];?>" <?php  echo $sel; ?>><?php echo $rowstk['stkname']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td bgcolor="#FFFFFF" style="margin-left:20px; padding-top: 14px;" valign="middle">
                        <input type="submit" name="submit" id="go" value="GO" class="input_button" />
                    </td>
                </tr>
            </table>
        </form>

		<div style="clear:both; margin-top:20px;"></div>
        <table id="myTable">
            <thead>
                <tr>
                    <th width="100" align="left" style="padding-left:10px;">Date</th>
                    <th width="300" align="left" style="padding-left:10px;">District</th>
                    <th width="300" align="left" style="padding-left:3px;">Warehouse</th>
                </tr>
            </thead>
            <tbody>
        <?php
		$flag = true;
		if ( isset($_POST['submit']) )
		{			
			$endDate = $year_sel . '-12-31';
			$startDate = $year_sel . '-01-01';
			
			// Start date and End date
			$begin = new DateTime($startDate);
			$end = new DateTime($endDate);
			$diff = $begin->diff($end);
			$interval = DateInterval::createFromDateString('1 month');
			$period = new DatePeriod($begin, $interval, $end);
			$i = 0;
			foreach ($period as $date)
			{
				$i++;
				$dataMonth = $date->format("M-Y");
				$currMonth = $date->format("Y-m-d");
				$preMonth = date('Y-m-d', strtotime('-1 Month', strtotime($currMonth)));
				
				$qry = "SELECT DISTINCT
							A.wh_id,
							A.wh_name,
							A.wh_type_id,
							A.dist_id,
							tbl_locations.LocName AS DistrictName
						FROM
							(
								SELECT
									tbl_warehouse.wh_id,
									tbl_warehouse.wh_name,
									tbl_warehouse.dist_id,
									tbl_warehouse.wh_type_id,
									tbl_wh_data.item_id,
									tbl_wh_data.wh_obl_a
								FROM
									tbl_warehouse
								INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
								INNER JOIN stakeholder AS mainStk ON stakeholder.MainStakeholder = mainStk.stkid
								INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
								WHERE
									tbl_warehouse.prov_id = $prov_sel
								AND mainStk.stkid = $stk_sel
								AND tbl_wh_data.RptDate = '$currMonth'
							) A
						JOIN (
							SELECT
								tbl_warehouse.wh_id,
								tbl_wh_data.item_id,
								tbl_wh_data.wh_cbl_a
							FROM
								tbl_warehouse
							INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
							INNER JOIN stakeholder AS mainStk ON stakeholder.MainStakeholder = mainStk.stkid
							INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
							WHERE
								tbl_warehouse.prov_id = $prov_sel
							AND mainStk.stkid = $stk_sel
							AND tbl_wh_data.RptDate = '$preMonth'
						) B ON A.wh_id = B.wh_id
						AND A.item_id = B.item_id
						JOIN tbl_locations ON A.dist_id = tbl_locations.PkLocID
						WHERE
							A.wh_obl_a != B.wh_cbl_a
						ORDER BY
							tbl_locations.LocName ASC,
							A.wh_name ASC";
				//echo $qry.'<br><br><br>';
				$qryRes = mysql_query($qry);
				$rowSpan = mysql_num_rows($qryRes);
				if ( $rowSpan > 0 )
				{
					$flag = false;
				?>
				<tr>
                    <td width="100" style="font-weight:bold;"><?php echo $dataMonth;?></td>
                	<td colspan="2">
                    	<table id="myTable" width="100%">
                        <?php
                        while ( $row = mysql_fetch_array($qryRes) )
						{
						?>
							<tr>
                            	<td width="300"><?php echo $row['DistrictName'];?></td>
                            	<td width="300"><a href="#" onClick="openPopUp('balance_detail.php?wh=<?php echo $row['wh_id'];?>&date=<?php echo $currMonth;?>')"><?php echo $row['wh_name'];?></a></td>
                            </tr>
						<?php
						}
						?>
                        </table>
                    </td>
                </tr>
				<?php
				}
				else
				{
					if ( $i == 11 )
					{
						$text = 'No record found.';
					}
				}
			}
		}
		if ( $flag === true )
		{
		?>
        		<tr><td colspan="3"><?php echo $text;?></td></tr>
        <?php 
		}
		?>
        	</tbody>
        </table>
   		</div>
    </div>
</div>
    
    <?php include "../../plmis_inc/common/right_inner.php";?>
	<?php include "../../plmis_inc/common/footer.php";?>
</body>
</html>