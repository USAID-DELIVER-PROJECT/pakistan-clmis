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


function checkDraft($draftMonth, $draftYear, $wh_Id)
{
    // See if this month data exists in drafts
    $qry = "SELECT
				COUNT(tbl_wh_data_draft.w_id) AS num
			FROM
				tbl_wh_data_draft
			WHERE
				tbl_wh_data_draft.report_month = $draftMonth
			AND tbl_wh_data_draft.report_year = $draftYear
			AND tbl_wh_data_draft.wh_id = $wh_Id";
    $qryRes = mysql_fetch_array(mysql_query($qry));
    if ($qryRes['num']>0)
    {
        $draft = ' (Draft)';
    }
    else
    {
        $draft = '';
    }
    return $draft;
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
                            <h3 class="heading">Satellite Camps Data Entry</h3>
                        </div>
                        <div class="widget-body">
                            <table width="1000px" id="myTable" cellpadding="5" align="center" class="table table-hover table-striped table-bordered">
                                <tr>
                                    <th width="5%" class="sb1NormalFont">Sr. No.</th>
                                    <th width="30%" class="sb1NormalFont">Store/Facility</th>
                                    <th width="17%" class="sb1NormalFont">Last Update</th>
                                    <th class="sb1NormalFont">Reporting Months</th>
                                </tr>
                                <?php
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
								
								// Health Facilities
								$_SESSION['LIMIT'] = 3;
								$counter = 1;
								$objwharehouse_user->m_stk_id=$stakeholder;
								$objwharehouse_user->m_prov_id=$province_id;
								
								$result1=$objwharehouse_user->GetwhuserHFSatelliteByIdc();
								if($result1!=FALSE && mysql_num_rows($result1)>0)
								{
									while($row = mysql_fetch_array($result1))
									{
									?>
									<tr>
									<?php
										$wh_Id = $row['wh_id'];
										$dataEntryURL = 'data_entry_hf_satellite.php';
										include('loadLast3MonthsHFSatellite.php');
									?>
									</tr>
									<?php
									}
								}
								?>
                            </table>
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