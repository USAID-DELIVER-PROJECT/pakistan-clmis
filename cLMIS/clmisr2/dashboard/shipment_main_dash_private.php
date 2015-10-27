<?php
include("../html/adminhtml.inc.php");
Login();

include("../FusionCharts/Code/PHP/Includes/FusionCharts.php");

if ( $_POST['year'] )
{
	$where = '';
	$year = $_POST['year'];
	$month = $_POST['month'];
	$startDate = $year.'-'.$month.'-01';
	$endDate = date('Y-m-t', strtotime($startDate));
	$proFilter = $_POST['proFilter'];
	
	if ($proFilter == 2)
	{
		$proFilter = " AND itminfo_tab.itmrec_id != 'IT-001' ";
	}
	else
	{
		$proFilter = "";
	}
}
$heading = date('M Y', strtotime($startDate));
?>
<div class="widget widget-tabs">
    <!-- Tabs Heading -->
    <div class="widget-head" style="display:none;">
        <ul>
        <?php
        // Get Stakeholders
        $stk = "SELECT
					MainStk.stkid,
					MainStk.stkname,
					tbl_warehouse.wh_id
				FROM
					stakeholder
				INNER JOIN tbl_warehouse ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN stakeholder AS MainStk ON tbl_warehouse.stkid = MainStk.stkid
				WHERE
					stakeholder.lvl = 1
				AND stakeholder.stk_type_id = 1
				ORDER BY
					MainStk.stkorder ASC";
        $stkQuery = mysql_query($stk);
        $stkQuery1 = mysql_query($stk);
        $counter = 1;
        while ($row = mysql_fetch_array($stkQuery)) {
            $active = ($counter == 1) ? 'class="active"' : '';
            $counter++;
        ?>
            <li <?php echo $active;?>><a href="#stock-shipment-<?php echo $counter;?>" data-toggle="tab"><?php echo $row['stkname'];?></a></li>
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
            while ($row1 = mysql_fetch_array($stkQuery1)) {
                $active = ($counter == 1) ? 'active' : '';
                $counter++;
				$stkId = $row1['stkid'];
				$stkname = $row1['stkname'];
				$whId = $row1['wh_id'];
            ?>    
            <!-- Tab content -->
            <div class="tab-pane <?php echo $active;?>" id="stock-shipment-<?php echo $counter;?>">
                <?php
				$xmlstore = '';
				$data = '';
                $dataQry = "SELECT
								itminfo_tab.itmrec_id AS itm_id,
								itminfo_tab.itm_name,
								SUM(tbl_wh_data.wh_issue_up) AS Issue,
								SUM(tbl_wh_data.wh_cbl_a) AS CB
							FROM
								itminfo_tab
							INNER JOIN stakeholder_item ON itminfo_tab.itm_id = stakeholder_item.stk_item
							INNER JOIN tbl_wh_data ON itminfo_tab.itmrec_id = tbl_wh_data.item_id
							WHERE
								stakeholder_item.stkid = $stkId
							AND itminfo_tab.itm_category = 1
							AND tbl_wh_data.wh_id = $whId
							AND tbl_wh_data.RptDate BETWEEN '$startDate' AND '$endDate'
							$proFilter
							GROUP BY
								itminfo_tab.itm_id";
                $qryRes = mysql_query($dataQry);
				$xmlstore = "<chart theme='fint' numberScaleValue='1000,1000,1000' numberScaleUnit='K,M,B' labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Private Sector: $stkname Distribution and Stock on Hand(SOH)' subCaption='All Products ($heading)' exportFileName='Product wise Shipment " . date('Y-m-d H:i:s') . "' yAxisName='Units' xAxisName='Products' showValues='1'>";
				while ( $row = mysql_fetch_array($qryRes) )
				{
					$data[$row['itm_id']]['name'] = $row['itm_name'];
					$data[$row['itm_id']]['issue'] = (is_null($row['Issue'])) ? 0 : $row['Issue'];
					$data[$row['itm_id']]['CB'] = (is_null($row['CB'])) ? 0 : $row['CB'];
				}
				$xmlstore .= "<categories>";
				foreach( $data as $key=>$name )
				{
					$xmlstore .= "<category label='$name[name]' />";
				}
				$xmlstore .= "</categories>";
				
				$xmlstore .= "<dataset seriesName='Issue'>";
				foreach( $data as $key=>$name )
				{
					$xmlstore .= "<set value='$name[issue]' />";
				}
				$xmlstore .= "</dataset>";
				
				$xmlstore .= "<dataset seriesName='Stock on Hand'>";
				foreach( $data as $key=>$name )
				{
					$xmlstore .= "<set value='$name[CB]' />";
				}
				$xmlstore .= "</dataset>";
				
				$xmlstore .= "</chart>";
				
				FC_SetRenderer('javascript');
				echo renderChart("../FusionCharts/Charts/MSColumn2D.swf", "", $xmlstore, 'shipmentStatus' . $stkId, '100%', 380, false, false);
                ?>
            </div>
            <?php
            }
            ?>
            <!-- // Tab content END --> 
            
        </div>
    </div>
</div>