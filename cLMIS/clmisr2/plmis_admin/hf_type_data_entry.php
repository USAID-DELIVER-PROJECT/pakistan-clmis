<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid']))
{
    $userid=$_SESSION['userid'];
    $objwharehouse_user->m_npkId=$userid;
    $result=$objwharehouse_user->GetwhuserByIdc();
}
else
    echo "user not login or timeout";


if ( isset($_REQUEST['district']) )
{
	$districtId = $_REQUEST['district'];
	$_SESSION['dist_id'] = $districtId;
	$qry = "SELECT
					wh_user.sysusrrec_id
				FROM
					tbl_warehouse
				INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
				INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
				WHERE
					tbl_warehouse.dist_id = $districtId
				AND stakeholder.lvl = 3
				AND tbl_warehouse.stkid = 1";
	$row = mysql_fetch_array(mysql_query($qry));
	$distUserId = $row['sysusrrec_id'];
}
?>
<?php include "../plmis_inc/common/_header.php";?>
<style>
table#myTable {
	margin-top: 20px;
	border-collapse: collapse;
	border-spacing: 0;
}
table#myTable tr td, table#myTable tr th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	padding-left: 5px;
	text-align: left;
	border: 1px solid #999;
}
table#myTable tr td.TAR {
	text-align: right;
	padding: 5px;
	font-family: Arial, Helvetica, sans-serif;
	width: 50px !important;
}
.sb1NormalFont {
	color: #444444;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-decoration: none;
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
                            <h3 class="heading">Health Facility Type-wise Consumption Data Entry</h3>
                        </div>
                        <div class="widget-body">
                            <form name="frm" id="frm" action="" method="get">
                                <div class="row">               
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="control-group">
                                                <label class="control-label">District</label>
                                                <div class="controls">
                                                    <select name="district" id="district" class="form-control input-medium">
                                                        <option value="">Select</option>
                                                        <?php
														$qry = "SELECT DISTINCT
																	tbl_locations.PkLocID,
																	tbl_locations.LocName
																FROM
																	tbl_locations
																INNER JOIN tbl_warehouse ON tbl_warehouse.dist_id = tbl_locations.PkLocID
																INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
																INNER JOIN wh_user ON tbl_warehouse.wh_id = wh_user.wh_id
																WHERE
																	tbl_locations.LocLvl = 3
																AND tbl_locations.ParentID = ".$_SESSION['prov_id']."
																AND tbl_warehouse.stkid = 1
																AND stakeholder.lvl = 3
																ORDER BY
																	tbl_locations.LocName ASC";
														$qryRes = mysql_query($qry);
														while ( $row = mysql_fetch_array($qryRes) )
														{
															$sel = ($row['PkLocID'] == $districtId) ? 'selected="selected"' : '';
                                                        	echo "<option value='".$row['PkLocID']."' $sel>".$row['LocName']."</option>";
														}
														?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-left:5px;">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Go</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
							if ( isset($_REQUEST['district']) )
							{
							?>
                            <table width="1000px" id="myTable" cellpadding="5" align="center" class="table table-hover table-striped table-bordered">
                                <tr>
                                    <th width="20%" align="left" class="sb1NormalFont" style="font-size:13px;">Health Facility Type</th>
                                    <th width="17%" align="left" class="sb1NormalFont" style="font-size:13px;">Last Update</th>
                                    <th class="sb1NormalFont" style="font-size:13px;">Reporting Months</th>
                                </tr>
                                <?php
								// Limit to show data entry months
								$_SESSION['LIMIT'] = 3;
								$objwharehouse_user->m_npkId=$distUserId;
								$objwharehouse_user->m_stk_id = $_SESSION['userdata'][7];
								$objwharehouse_user->m_prov_id = $_SESSION['prov_id'];
								$result1=$objwharehouse_user->GetwhuserHFTypeByIdc();
								if($result1!=FALSE && mysql_num_rows($result1)>0)
								{
									while($row = mysql_fetch_array($result1))
									{
								?>
								<tr>
								<?php
									$wh_type_id = $row['pk_id'];
									include('loadLast3MonthsHFType.php');
								?>
								</tr>
                                <?php
									}
								}
								?>
                            </table>
                            <?php
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
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>