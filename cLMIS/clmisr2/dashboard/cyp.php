<?php
include("../html/adminhtml.inc.php");
Login();

include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");

if ( $_POST['year'] )
{
	$where = '';
	$sector = $_POST['sector'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$rptDate = $year.'-'.$month.'-01';
	$lvl = $_POST['lvl'];
	$proFilter = $_POST['proFilter'];
	
	if ($proFilter == 2)
	{
		$proFilter = " AND itminfo_tab.itmrec_id != 'IT-001' ";
	}
	else
	{
		$proFilter = "";
	}
	if ( $lvl == 1 )
	{
		$level = 'All Pakistan Districts';
	}
	else if ( $lvl == 2 )
	{
		$prov_id = $_POST['prov_id'];
		$prov = mysql_fetch_array(mysql_query("SELECT
													tbl_locations.LocName
												FROM
													tbl_locations
												WHERE
													tbl_locations.PkLocID = $prov_id"));
		$provName = $prov['LocName'];
		$level = "$provName Districts";
	}
	else if ( $lvl == 3 )
	{
		$dist_id = $_POST['dist_id'];
		$dist = mysql_fetch_array(mysql_query("SELECT
													tbl_locations.LocName
												FROM
													tbl_locations
												WHERE
													tbl_locations.PkLocID = $dist_id"));
		$distName = $dist['LocName'];
		$level = "District $distName";
	}
	if ($sector == '0')
	{
		$sectorText = 'Public Sector:';
	}
	else if ($sector == '1')
	{
		$sectorText = 'Private Sector:';
	}
}
$heading = date('M Y', strtotime($rptDate));
?>
<div class="widget widget-tabs">
    <!-- Tabs Heading -->
    <div class="widget-head" style="display:none;">
        <ul>
        <?php
        // Get Stakeholders
        $stk = "SELECT DISTINCT
                            MainStk.stkid,
                            MainStk.stkname
                        FROM
                            stakeholder
                        INNER JOIN stakeholder AS MainStk ON stakeholder.MainStakeholder = MainStk.stkid
                        WHERE
                            stakeholder.stk_type_id = $sector
						ORDER BY
							MainStk.stkorder ASC";
        $stkQuery = mysql_query($stk);
        $stkQuery1 = mysql_query($stk);
        $counter = 1;
        while ($row = mysql_fetch_array($stkQuery)) {
            $active = ($counter == 1) ? 'class="active"' : '';
            $counter++;
			if($row['stkid'] != 8)
			{
        ?>
            <li <?php echo $active;?>><a href="#CYP-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
        <?php
			}
        }
        ?>
        </ul>
    </div>
    <!-- // Tabs Heading END -->
    
    <div class="widget-body">
        <div class="tab-content"> 
            <?php
            $counter = 1;
            while ($row = mysql_fetch_array($stkQuery1)) {
                $active = ($counter == 1) ? 'active' : '';
                $counter++;
            ?>    
            <!-- Tab content -->
            <div class="tab-pane <?php echo $active;?>" id="CYP-<?php echo $counter;?>">
                <?php
                $xmlstore = '';
				if ( $lvl == 1 )
				{
					$cypSumm = "SELECT
									summary_national.item_id,
									SUM(summary_national.consumption) AS consumption
								FROM
									summary_national
								WHERE
									summary_national.stakeholder_id = " . $row['stkid'] . "
								AND summary_national.reporting_date = '$rptDate'
								GROUP BY
									summary_national.item_id";
				}
				else if ( $lvl == 2 )
				{
					$cypSumm = "SELECT
									summary_province.item_id,
									SUM(summary_province.consumption) AS consumption
								FROM
									summary_province
								WHERE
									summary_province.stakeholder_id = " . $row['stkid'] . "
								AND summary_province.reporting_date = '$rptDate'
								AND summary_province.province_id = $prov_id
								GROUP BY
									summary_province.item_id";
				}
				else if ( $lvl == 3 )
				{
					$cypSumm = "SELECT
									summary_district.item_id,
									SUM(summary_district.consumption) AS consumption
								FROM
									summary_district
								WHERE
									summary_district.stakeholder_id = " . $row['stkid'] . "
								AND summary_district.reporting_date = '$rptDate'
								AND summary_district.district_id = $dist_id
								GROUP BY
									summary_district.item_id";
				}
                $rrQry = "SELECT
                                B.itm_name,
                                ROUND(COALESCE(A.consumption * B.extra, NULL, 0)) AS CYP
                            FROM
                                (
                                    $cypSumm
                                ) A
                            RIGHT JOIN (
                                SELECT
                                    itminfo_tab.itm_name,
                                    itminfo_tab.itmrec_id,
                                    itminfo_tab.frmindex,
                                    itminfo_tab.extra
                                FROM
                                    itminfo_tab
                                INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
                                WHERE
                                    stakeholder_item.stkid = " . $row['stkid'] . "
								AND itminfo_tab.itm_category = 1
								$proFilter
                            ) B ON A.item_id = B.itmrec_id
                            ORDER BY
                                B.frmindex";
                $rrQryRes = mysql_query($rrQry);
                $xmlstore = "<chart theme='fint' numberScaleValue='1000,1000,1000' numberScaleUnit='K,M,B' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$sectorText $row[stkname] (All Products)' subCaption='$level - Couple Year Protection (CYP) ($heading)' exportFileName='CYP " . date('Y-m-d H:i:s') . "' yAxisName='Years' xAxisName='Products' showValues='1'>";
                while ($data = mysql_fetch_array($rrQryRes)) {
                    $xmlstore .= "<set label='$data[itm_name]' value='$data[CYP]' />";
                }
				
                $xmlstore .= "</chart>";

                FC_SetRenderer('javascript');
                echo renderChart("../FusionCharts/Charts/Column2D.swf", "", $xmlstore, 'CYP' . $row['stkid'], '100%', 350, false, false);
                ?>
            </div>
            <?php
            }
            ?>
            <!-- // Tab content END --> 
            
        </div>
    </div>
</div>