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
		$level = 'All Pakistan District Stores';
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
		$level = "$provName District Stores";
		$where .= " AND summary_district.province_id = $prov_id";
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
		$where .= " AND summary_district.district_id = $dist_id";
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
    <div class="widget-head" style="display:none">
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
            <li <?php echo $active;?>><a href="#stock-status-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
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
            <div class="tab-pane <?php echo $active;?>" id="stock-status-<?php echo $counter;?>">
                <div style="width:100%;">
                    <select name="rptType" id="rptType" class="form-control input-sm" style="width:100px; float:right; margin-bottom:2px;" onchange="loadGraph('<?php echo $row['stkid'];?>', this.value, '<?php echo $counter?>')">
                        <option value="1">District</option>
                        <option value="2">Field</option>
                    </select>
                </div>
                <div id="stock-<?php echo $counter;?>">
                <?php
				$xmlstore = '';
				$itmName = '';
				$overStock = '';
				$stockOut = '';
				$reportingRate = '';
                $dataQry = "SELECT
                                A.item_id,
                                itminfo_tab.itm_name,
                                CEIL((SUM(IF(A.MOS >= REPgetMOSScale(itminfo_tab.itmrec_id, $row[stkid], 3, 'OS', 'S'), 1, 0)) / COUNT(A.district_id) * 100)) AS OverStockPer,
                                CEIL((SUM(IF(A.MOS <= REPgetMOSScale(itminfo_tab.itmrec_id, $row[stkid], 3, 'SO', 'E'), 1, 0)) / COUNT(A.district_id) * 100)) AS StockOutPer
                            FROM
                                (
                                    SELECT
                                        summary_district.district_id,
                                        summary_district.item_id,
                                        ROUND((summary_district.soh_district_store / summary_district.avg_consumption), 2) AS MOS
                                    FROM
                                        summary_district
                                    INNER JOIN stakeholder ON summary_district.stakeholder_id = stakeholder.stkid
                                    WHERE
                                        summary_district.reporting_date = '$rptDate'
                                    AND summary_district.stakeholder_id = $row[stkid]
									$where
                                    GROUP BY
                                        summary_district.item_id,
                                        summary_district.district_id
                                ) A
                            JOIN itminfo_tab ON A.item_id = itminfo_tab.itmrec_id
							INNER JOIN stakeholder_item ON stakeholder_item.stk_item = itminfo_tab.itm_id
                            WHERE
                                stakeholder_item.stkid = $row[stkid]
							AND itminfo_tab.itm_category = 1
							$proFilter
                            GROUP BY A.item_id
                            ORDER BY itminfo_tab.frmindex ASC";
                $qryRes = mysql_query($dataQry);
                while ($row1 = mysql_fetch_array($qryRes)) {
                    $itmName[$row1['item_id']] = $row1['itm_name'];
                    $overStock[$row1['item_id']] = $row1['OverStockPer'];
                    $stockOut[$row1['item_id']] = $row1['StockOutPer'];
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
				
                $xmlstore = "<chart theme='fint' yAxisMaxValue='100' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$sectorText $row[stkname] Stakeholder (All Products) Reporting Rate ($reportingRate%)' subCaption='$level - Stock Out Vs Over Stock ($heading)' exportFileName='Stock Status " . date('Y-m-d H:i:s') . "' yAxisName='District Stores Percentage' xAxisName='Products' numberSuffix='%' showValues='1' formatNumberScale='0'>";
                // Start Making Categories (Products)
                $xmlstore .= "<categories>";
                foreach ($itmName as $itemId => $itemName) {
                    $xmlstore .= "<category label='$itemName' />";
                }
                $xmlstore .= "</categories>";

                // Stock Out Series
                $xmlstore .= "<dataset seriesName='Stock Out'>";
                foreach ($stockOut as $itemId => $value) {
					$param = base64_encode($itemId.'|'.$row['stkid'].'|'.$rptDate.'|SO'.'|'.$lvl.'|'.$prov_id.'|'.$dist_id);
                    $xmlstore .= "<set value='$value' link=\"JavaScript:showData('$param');\" />";
                }
                $xmlstore .= "</dataset>";

                // Over Stock Series
                $xmlstore .= "<dataset seriesName='Over Stock'>";
                foreach ($overStock as $itemId => $value) {					
					$param = base64_encode($itemId.'|'.$row['stkid'].'|'.$rptDate.'|OS'.'|'.$lvl.'|'.$prov_id.'|'.$dist_id);
                    $xmlstore .= "<set value='$value' link=\"JavaScript:showData('$param');\" />";
                }
                $xmlstore .= "</dataset>";
                
				$xmlstore .= "<trendlines>
								<line startvalue='$reportingRate' displayvalue='Reporting Rate' valueonright='1' showvalue='1' showontop='1' color='009C00' />
							  </trendlines>";
				
				
				$xmlstore .= "</chart>";

                FC_SetRenderer('javascript');
                echo renderChart("../FusionCharts/Charts/MSColumn2D.swf", "", $xmlstore, 'stockStatus' . $row['stkid'], '100%', 350, false, false);
                unset($itemName);
                unset($stockOut);
                unset($overStock);
                ?>
                </div>
            </div>
            <?php
            }
            ?>
            <!-- // Tab content END --> 
            
        </div>
    </div>
</div>