<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid']))
{
    $userid=$_SESSION['userid'];
    $objwharehouse_user->m_npkId=$userid;
    //$result=$objwharehouse_user->GetwhuserByIdc();
}
else
{
    echo "user not login or timeout";
	exit;
}
?>
<?php include "../plmis_inc/common/_header.php";?>
<style>
table#myTable {
	/*margin-top: 20px;*/
	border-collapse: collapse;
	border-spacing: 0;
}
table#myTable tr td, table#myTable tr th {
	font-size: 13px;
	padding: 5px;
	text-align: left;
	border: 1px solid #999;
}
table#myTable tr td.TAR {
	text-align: right;
}table#myTable tr td.TAC {
	text-align: center;
}
.sb1NormalFont {
	color: #444444;
	font-size: 13px;
	font-weight: normal;
	text-decoration: none;
}
.healthFacility{
	color: #009C00;
	font-weight:bold;
	cursor:pointer;
}
.hfDiv{
	margin-top:10px !important;
}
</style>
<script>
    function openPopUp(pageURL)
    {
		var w = 1002;
		var h = 595;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		return window.open(pageURL, 'Data Entry', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>
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
                            <h3 class="heading">Consumption Data Entry</h3>
                        </div>
                        <div class="widget-body">
                        <?php 
						if( $_SESSION['userid'] == 2006 )
						{
							echo '<a href="import.php">Import from Excel file</a>';
						}
						else 
						{
							// Limit to show reporting Months
							$_SESSION['LIMIT'] = 2;
							
							$qry = "SELECT
										tbl_warehouse.stkid,
										tbl_warehouse.prov_id
									FROM
										tbl_warehouse
									INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
									WHERE
										tbl_warehouse.wh_id =".$_SESSION['userdata'][5];
							$check = mysql_fetch_array(mysql_query($qry));
							$stakeholder = $check['stkid'];
							$province_id = $check['prov_id'];
							$objwharehouse_user->m_stk_id = $stakeholder;
							$objwharehouse_user->m_prov_id = $province_id;
							$result1 = $objwharehouse_user->GetwhuserHFByIdc();
							$totalFacilities = mysql_num_rows($result1);
							$hfText = ($stakeholder == 73) ? 'CMW Name' : 'Health Facility Name';
							if($result1!=FALSE && $totalFacilities > 0)
							{
								$load3Months = 'loadLast3MonthsHF.php';
							?>
                                <h4><?php echo ($stakeholder == 73) ? "CMW List" : 'Health Facilities';?></h4>
                                <table width="80%" id="myTable">
                                    <tr>
                                        <th width="8%">Sr. No.</th>
                                        <th width="42%"><?php echo $hfText;?></th>
                                        <th width="8%">Sr. No.</th>
                                        <th width="42%"><?php echo $hfText;?></th>
                                    </tr>
                                    <?php
                                    $counter = 1;
                                    if ( $stakeholder == 1 ){
                                        $dataEntryURL = 'data_entry_hf_pwd.php';
                                    }else{
                                        $dataEntryURL = 'data_entry_hf.php';
                                    }
                                    while($row = mysql_fetch_array($result1))
                                    {
                                        $wh_Id = $row['wh_id'];
                                    ?>
                                        <?php
                                        if ($counter % 2 != 0)
                                        {
                                            if ( $counter > 1 )
                                            {
                                                echo "</tr>";
                                            }
                                            echo "<tr>";
                                            echo "<td class=\"sb1NormalFont TAC\">".$counter++."</td>";
                                            echo "<td class=\"sb1NormalFont\"><span class='healthFacility' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                            echo "<div class=\"hfDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                            echo "</td>";
                                        }
                                        else if ($counter % 2 == 0)
                                        {
                                            echo "<td class=\"sb1NormalFont TAC\">".$counter++."</td>";
                                            echo "<td class=\"sb1NormalFont\"><span class='healthFacility' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
                                            echo "<div class=\"hfDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
                                            echo "</td>";
                                        }
                                    }
                                    ?>
                                </table>
                            <?php
							}
							?>    
                            
                            <?php
							$objwharehouse_user->m_npkId = $userid;
							$result1 = $objwharehouse_user->GetwhuserByIdc();
							$num = mysql_num_rows($result1);
							if($result1 != FALSE && $num>0)
							{
								$load3Months = 'loadLast3Months.php';
							?>
                            <br>
                        	<h4>District/Field Stores</h4>
                            <table width="80%" id="myTable">
                            	<tr>
                                	<th width="8%">Sr. No.</th>
                                	<th>Store Name</th>
                                    <?php if($totalFacilities == 0){?>
                                	<th width="8%">Sr. No.</th>
                                	<th width="42%">Store Name</th>
                                    <?php }?>
                                </tr>
                                <?php
								$counter = 1;
								while($row = mysql_fetch_array($result1))
								{
									$wh_Id = $row['wh_id'];
									$dataEntryUrl = 'data_entry.php';
									if ( $_SESSION['im_open'] == 0 )
									{
										if($row['lvl'] == 3 || ($row['lvl'] == 4 && $totalFacilities == 0))
										{
											if ($counter % 2 != 0)
											{
												if ( $counter > 1 )
												{
													echo "</tr>";
												}
												echo "<tr>";
												echo "<td class=\"sb1NormalFont TAC\">".$counter++."</td>";
                                            	echo "<td class=\"sb1NormalFont\"><span class='healthFacility' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
												echo "<div class=\"hfDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
												echo "</td>";
											}
											else if ($counter % 2 == 0)
											{
												echo "<td class=\"sb1NormalFont TAC\">".$counter++."</td>";
                                            echo "<td class=\"sb1NormalFont\"><span class='healthFacility' onClick=\"showReports('$wh_Id', '$load3Months', '$dataEntryURL')\">" . $row['wh_name'] . "</span>";
												echo "<div class=\"hfDiv\" id=\"$wh_Id\" style=\"display:none;\"></div>";
												echo "</td>";
											}
										
										}
									}
								}
								?>
                            </table>
                        <?php
							}
						}
						?>
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
			$('.hfDiv').hide();
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