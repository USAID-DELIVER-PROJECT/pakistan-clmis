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
	if ( $lvl == 1 )
	{
		$level = 'National Level';
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
		$level = "Provincial Level ($provName)";
		$where .= " AND summary_table.province_id = $prov_id";
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
		$level = "District Level ($distName)";
		$where .= " AND summary_table.district_id = $dist_id";
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
    <div class="widget-head">
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
        ?>
            <li <?php echo $active;?>><a href="#AMC-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
        <?php
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
            <div class="tab-pane <?php echo $active;?>" id="AMC-<?php echo $counter;?>">
                <?php
                $xmlstore = '';
                $rrQry = "SELECT
                                B.itm_name,
                                ROUND(COALESCE(A.AMC, NULL, 0)) AS AMC
                            FROM
                                (
                                    SELECT
                                        summary_table.item_id,
                                        SUM(summary_table.avg_monthly_consumption) AS AMC
                                    FROM
                                        summary_table
                                    WHERE
                                        summary_table.stakeholder_id = " . $row['stkid'] . "
                                    AND summary_table.rpt_date = '$rptDate'
									$where
                                    GROUP BY
                                        summary_table.item_id
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
                            ) B ON A.item_id = B.itmrec_id
                            ORDER BY
                                B.frmindex";
                $rrQryRes = mysql_query($rrQry);
                $xmlstore = "<chart theme='fint' numberScaleValue='1000,1000,1000' numberScaleUnit='K,M,B' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='$sectorText $row[stkname] (All Products)' subCaption='$level - Average Monthly Consumption (AMC) ($heading)' exportFileName='AMC " . date('Y-m-d H:i:s') . "' yAxisName='Units' xAxisName='Products' showValues='1'>";
                while ($data = mysql_fetch_array($rrQryRes)) {
                    $xmlstore .= "<set label='$data[itm_name]' value='$data[AMC]' />";
                }
				
                $xmlstore .= "</chart>";

                FC_SetRenderer('javascript');
                echo renderChart("../FusionCharts/Charts/Column2D.swf", "", $xmlstore, 'AMC' . $row['stkname'], '100%', 350, false, false);
                ?>
            </div>
            <?php
            }
            ?>
            <!-- // Tab content END --> 
            
        </div>
    </div>
</div>