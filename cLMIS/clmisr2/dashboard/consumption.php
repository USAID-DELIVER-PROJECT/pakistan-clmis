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
		$rrWhere .= " AND tbl_warehouse.prov_id = $prov_id";
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
		$rrWhere .= " AND tbl_warehouse.dist_id = $dist_id";
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
            <li <?php echo $active;?>><a href="#consumption-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
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
            <div class="tab-pane <?php echo $active;?>" id="consumption-<?php echo $counter;?>">
                <?php
                $xmlstore = '';
				$itmName = '';
				$consumption = '';
				$AMC = '';
				$reportingRate = '';
				
				if ( $lvl == 1 )
				{
					$conSumm = "SELECT
									summary_national.item_id,
									COALESCE(SUM(summary_national.consumption), NULL, 0) AS consumption,
									COALESCE(SUM(summary_national.avg_consumption), NULL, 0) AS AMC,
									AVG(summary_national.reporting_rate) AS RR
								FROM
									summary_national
								INNER JOIN stakeholder ON summary_national.stakeholder_id = stakeholder.stkid
								WHERE
									summary_national.reporting_date = '$rptDate'
								AND summary_national.stakeholder_id = $row[stkid]
								GROUP BY
									summary_national.item_id";
				}
				else if ( $lvl == 2 )
				{
					$conSumm = "SELECT
									summary_province.item_id,
									COALESCE(SUM(summary_province.consumption), NULL, 0) AS consumption,
									COALESCE(SUM(summary_province.avg_consumption), NULL, 0) AS AMC,
									AVG(summary_province.reporting_rate) AS RR
								FROM
									summary_province
								INNER JOIN stakeholder ON summary_province.stakeholder_id = stakeholder.stkid
								WHERE
									summary_province.reporting_date = '$rptDate'
								AND summary_province.stakeholder_id = $row[stkid]
								AND summary_province.province_id = $prov_id
								GROUP BY
									summary_province.item_id";
				}
				else if ( $lvl == 3 )
				{
					$conSumm = "SELECT
									summary_district.item_id,
									COALESCE(SUM(summary_district.consumption), NULL, 0) AS consumption,
									COALESCE(SUM(summary_district.avg_consumption), NULL, 0) AS AMC,
									AVG(summary_district.reporting_rate) AS RR
								FROM
									summary_district
								INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
								WHERE
									summary_district.reporting_date = '$rptDate'
								AND summary_district.stakeholder_id = $row[stkid]
								AND summary_district.district_id = $dist_id
								GROUP BY
									summary_district.item_id";
				}
               	$consQry = "SELECT
								B.itmrec_id,
                                B.itm_name,
                                ROUND(SUM(COALESCE(A.consumption, NULL, 0))) AS consumption,
                                ROUND(SUM(COALESCE(A.AMC, NULL, 0))) AS AMC,
								ROUND(AVG(A.RR)) AS RR
                            FROM
                                (
                                    $conSumm
                                ) A
                            RIGHT JOIN (
                                SELECT
                                    itminfo_tab.itm_name,
                                    itminfo_tab.itmrec_id,
                                    itminfo_tab.frmindex
                                FROM
                                    itminfo_tab
                                INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
                                WHERE
                                    stakeholder_item.stkid = " . $row['stkid'] . "
								AND itminfo_tab.itm_category = 1
								$proFilter
                            ) B ON A.item_id = B.itmrec_id
                            GROUP BY
								B.itmrec_id
                            ORDER BY
								B.frmindex ASC";
                $consQryRes = mysql_query($consQry);
                while ($row1 = mysql_fetch_array($consQryRes))
				{
                    $itmName[$row1['itmrec_id']] = $row1['itm_name'];
                    $consumption[$row1['itmrec_id']] = $row1['consumption'];
                    $AMC[$row1['itmrec_id']] = $row1['AMC'];
                }				
				
				// Get Reporting Rate (All Warehouses)
				$reportedQry = "SELECT
									((A.reportedWH / B.totalWH) * 100) AS RR
								FROM
									(
										SELECT
											COUNT(DISTINCT tbl_warehouse.wh_id) AS reportedWH,
											tbl_warehouse.stkid
										FROM
											tbl_warehouse
										INNER JOIN tbl_wh_data ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
										INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
										WHERE
											tbl_wh_data.item_id = 'IT-001'
										AND tbl_wh_data.report_month = $month
										AND tbl_wh_data.report_year = $year
										AND tbl_warehouse.stkid = $row[stkid]
										AND stakeholder.lvl <= 4 $rrWhere
									) A
								JOIN (
									SELECT
										COUNT(tbl_warehouse.wh_id) AS totalWH,
										tbl_warehouse.stkid
									FROM
										tbl_warehouse
									INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
									WHERE
										tbl_warehouse.stkid = $row[stkid]
									AND stakeholder.lvl <= 4 $rrWhere
								) B ON A.stkid = B.stkid";
				$reportedQryRes = mysql_fetch_array(mysql_query($reportedQry));
				
				$reportingRate = round($reportedQryRes['RR'], 1);
				
				$xmlstore = "<chart showDivLineSecondaryValue='0' showSecondaryLimits='0' theme='fint' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$sectorText $row[stkname] (All Products) Reporting Rate ($reportingRate%)' subCaption='$level - Consumption ($heading)' exportFileName='Consumption " . date('Y-m-d H:i:s') . "' pyaxisname='Units'>";
                // Start Making Categories (Products)
                $xmlstore .= "<categories>";
                foreach ($itmName as $itemId => $itemName) {
                    $xmlstore .= "<category label='$itemName' />";
                }
                $xmlstore .= "</categories>";

                // Consumption
                $xmlstore .= "<dataset seriesName='Consumption' parentyaxis='P'>";
                foreach ($consumption as $itemId => $value) {
                    $xmlstore .= "<set value='$value' />";
                }
                $xmlstore .= "</dataset>";

                // AMC
                $xmlstore .= "<dataset seriesName='Average Monthly Consumption' parentyaxis='P' renderas='Line' showvalues='0'>";
                foreach ($AMC as $itemId => $value) {
                    $xmlstore .= "<set value='$value' />";
                }
                $xmlstore .= "</dataset>";
                
				/*$xmlstore .= "<trendlines>
								<line startvalue='$reportingRate' displayvalue='Reporting Rate' valueonright='0' color='009C00' />
							  </trendlines>";*/
				
                $xmlstore .= "</chart>";

                FC_SetRenderer('javascript');
                echo renderChart("../FusionCharts/Charts/MSCombiDY2D.swf", "", $xmlstore, 'Consumption' . $row['stkid'], '100%', 350, false, false);
                ?>
            </div>
            <?php
            }
            ?>
            <!-- // Tab content END --> 
            
        </div>
    </div>
</div>