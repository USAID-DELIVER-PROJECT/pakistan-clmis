<?php
/**
 * wh_data_entry
 * @package consumption
 * 
 * @author     Muhammad Waqas Azeem 
 * @email <waqas@deliver-pk.org>
 * 
 * @version    2.2
 * 
 */
//include AllClasses
include("../includes/classes/AllClasses.php");
//include header
include(PUBLIC_PATH."html/header.php");
//get user_id
$userid=$_SESSION['user_id'];
//set user_id
$objwharehouse_user->m_npkId=$userid;
$dataEntryURL = '';
?>
<style>
	.wh_name, .wh_name a{cursor:pointer;color:#428bca !important;}
	.btn-sm,
	.btn-xs {
		margin-bottom:4px;
	}
</style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-quick-sidebar-over-content" >
<!-- BEGIN HEADER -->
<div class="page-container">
    <?php 
    //include top
    include PUBLIC_PATH."html/top.php";
    //include top_im
    include PUBLIC_PATH."html/top_im.php";?>
    <div class="page-content-wrapper">
        <div class="page-content">
        <?php
		$rpt_date = '';
		if($_SESSION['user_stakeholder'] == 73 && $_SESSION['user_province1'] == 1)
		{
		//report date	
                    $rpt_date = isset($_GET['rpt_date']) ? $_GET['rpt_date'] : '';
		?>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget" data-toggle="collapse-widget">
                        <div class="widget-head">
                            <h3 class="heading">Concumption Data Entry</h3>
                        </div>
                        <div class="widget-body">
                            <table width="99%">
                                <tr>
                                    <td><form action="" method="get" name="frm" id="frm">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <div class="control-group">
                                                        <label class="control-label">Reporting Month</label>
                                                        <div class="controls">
                                                            <select name="rpt_date" id="rpt_date" class="form-control input-medium" required>
                                                                <option value="">Select</option>
                                                                <?php
                                                                                                                                //start date
																$startDate = date('Y-m-d', strtotime("-7 month", strtotime(date('Y-m'))));
                                                                                                                                //end date
																$endDate = date('Y-m-01', strtotime("-1 month", strtotime(date('Y-m'))));
																
																$start = new DateTime($startDate);
																$end = new DateTime($endDate);
                                                                                                                                //date interval
																$i = DateInterval::createFromDateString('1 month');
                                                                                                                                //populate rpt_date combo
																while ($end >= $start) {
																	$selected = ($end->format("Y-m") == $rpt_date) ? 'selected="selected"' : '';
																	echo "<option value='".$end->format("Y-m")."' $selected>".$end->format("F Y")."</option>";
																	$end = $end->sub($i);
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 left">
                                                    <div class="control-group">
                                                        <label class="control-label">&nbsp;</label>
                                                        <div class="controls">
                                                            <input type="submit" value="Go" class="btn btn-primary"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            //get rpt_date
			if(isset($_GET['rpt_date']))
			{
		?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    //include mnch_data_entry
                    include('mnch_data_entry.php');
                    ?>
                </div>
            </div>
            <?php
			}
		}
		else
		{
		?>
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page-title row-br-b-wp">Consumption Data Entry</h3>
                    <?php
                    if( $_SESSION['user_id'] == 2006 )
                    {
                        //excel import
                        echo '<a href="import.php">Import from Excel file</a>';
                    }
                    else
                    {
						// Check if Facilties exists
                                                //get user_stakeholder1
						$stakeholder = $_SESSION['user_stakeholder1'];
                                                //get user_province1
						$province_id = $_SESSION['user_province1'];
                                                //set satake holder
						$objwharehouse_user->m_stk_id = $stakeholder;
						//set province id
						$objwharehouse_user->m_prov_id = $province_id;
						//Get wh user HF By Idc
                        $hfResult = $objwharehouse_user->GetwhuserHFByIdc();
						//total faciliteis
                        $totalFacilities = mysql_num_rows($hfResult);
						$hfText = ($stakeholder == 73) ? 'CMW Name' : 'Health Facility Name';
                     //set user id
                    $objwharehouse_user->m_npkId = $userid;
                    //Get wh user By Idc
                    $result = $objwharehouse_user->GetwhuserByIdc();
                    $num = mysql_num_rows($result);
                    //check if record exists
                    if($result != FALSE && $num>0 && $_SESSION['is_allowed_im'] == 0)
                    {
                        //load 3 months
                        $load3Months = 'loadLast3Months.php';
                    ?>
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption">District/Field Stores</div>
                            <div class="tools"> <a class="collapse" href="javascript:;"></a> </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="8%">Sr. No.</th>
                                        <th>Store Name</th>
                                        <?php if($totalFacilities == 0){?>
                                        <th width="8%">Sr. No.</th>
                                        <th width="42%">Store Name</th>
                                        <?php }?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                $counter = 1;
                                //fetch results
                                while($row = mysql_fetch_array($result))
                                {
                                    //wh id
                                    $wh_Id = $row['wh_id'];
                                    $dataEntryUrl = 'data_entry.php';
                                    if($row['lvl'] <= 3 || ($row['lvl'] == 4 && $totalFacilities == 0))
                                    {
                                        //check counter
                                        if ($counter % 2 != 0)
                                        {
                                            if ( $counter > 1 )
                                            {
                                                echo "</tr>";
                                            }
                                            echo "<tr>";
                                            echo "<td class=\"center\">".$counter++."</td>";
                                            echo "<td><span class='wh_name' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                            echo "<div class=\"whDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                            echo "</td>";
                                        }
                                        else if ($counter % 2 == 0)
                                        {
                                            echo "<td class=\"center\">".$counter++."</td>";
                                            echo "<td><span class='wh_name' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                            echo "<div class=\"whDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                            echo "</td>";
                                        }
                                    
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    }
					
					if($hfResult!=FALSE && $totalFacilities > 0)
					{
					//load 3 months	
                                            $load3Months = 'loadLast3MonthsHF.php';
					?>
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption"><?php echo ($stakeholder == 73) ? "CMW List" : 'Health Facilities';?></div>
                            <div class="tools"> <a class="collapse" href="javascript:;"></a> </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="8%">Sr. No.</th>
                                        <th width="42%"><?php echo $hfText;?></th>
                                        <th width="8%">Sr. No.</th>
                                        <th width="42%"><?php echo $hfText;?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                $counter = 1;
                                if ( $stakeholder == 1 ){
                                    //data entry url
                                    $dataEntryURL = 'data_entry_hf_pwd.php';
                                }else{
                                    //data entry url
                                    $dataEntryURL = 'data_entry_hf.php';
                                }
                                //fetch results from hfResult
                                while($row = mysql_fetch_array($hfResult))
                                {
                                    $wh_Id = $row['wh_id'];
                                ?>
                                    <?php
                                    //check counter
                                    if ($counter % 2 != 0)
                                    {
                                        if ( $counter > 1 )
                                        {
                                            echo "</tr>";
                                        }
                                        echo "<tr>";
                                        echo "<td class=\"center\">".$counter++."</td>";
                                        echo "<td><span class='wh_name' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                        echo "<div class=\"whDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                        echo "</td>";
                                    }
                                    else if ($counter % 2 == 0)
                                    {
                                        echo "<td class=\"center\">".$counter++."</td>";
                                        echo "<td><span class='wh_name' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                        echo "<div class=\"whDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                        echo "</td>";
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                }
            ?>
                </div>
            </div>
            <?php
		}
		?>
        </div>
    </div>
</div>
<?php 
//include footer
include PUBLIC_PATH."/html/footer.php";?>
<script>
    function openPopUp(pageURL)
    {
		var w = screen.width - 100;
		var h = screen.height - 100;
		var left = (screen.width/2)-(w/2);
		var top = 0;
		
		return window.open(pageURL, 'Data Entry', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
	function showReports(wharehouse_id, load3Months, dataEntryURL)
	{
		if($('div#'+wharehouse_id).is(':visible'))
		{
			$('div#'+wharehouse_id).hide();
			return false;
		}
		else
		{
			if(wharehouse_id)
			{
				$('.whDiv').hide();
				$.ajax({
					url: load3Months,
					data: {wharehouse_id: wharehouse_id, dataEntryURL: dataEntryURL},
					type: 'post',
					success: function(data){
						$('div#'+wharehouse_id).show().html(data);
					}
				})
			}
		}
	}
</script> 
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>