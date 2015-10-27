<?php
include("Includes/AllClasses.php");
include("../html/adminhtml.inc.php");

if (isset($_SESSION['userid']))
{
	$userid=$_SESSION['userid'];
	$objwharehouse_user->m_npkId=$userid;
	$result_province = $objwharehouse_user->GetProvinceIdByIdc();
}
else
echo "user not login or timeout";

if ( isset($_GET['e']) && $_GET['e'] == 'ok' )
{
	$wh_id = $_REQUEST['wh'];
	
	echo 'Your data has been successfully saved.';
?>
<script type="text/javascript" src="../plmis_js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">
	$(function(){
		$.ajax({
			url: '../update_summary_tables_ajax.php',
			data: {whId: '<?php echo $wh_id;?>'}
		})
		delay(function(){
			window.close();
			RefreshParent();
			//window.onbeforeunload = RefreshParent;
		}, 3000 );
	})
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	function RefreshParent() {
		if (window.opener != null && !window.opener.closed) {
			window.opener.location.reload();
		}
	}
</script>
<?php
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
#hideit {
	display: none;
}
input[type="text"] {
	text-align: right;
}
</style>
<title>Data Entry</title>
</head>
<body style="font-family:Verdana, Geneva, sans-serif; font-size: 0.8em;color:Black;">
<table width="980" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr id="hideit">
        <td><?php include(SITE_PATH."plmis_inc/common/top.php");?></td>
    </tr>
    <tr>
        <td valign="top"><table width="100%" height="146" valign="top">
                <tr>
                    <td width="80%" bgcolor="#E1E1E1" valign="top" id="showGrid"><? 
					$wh_id="";					
					if(isset($_REQUEST['Do']) && !empty($_REQUEST['Do']))
					{
						$temp=urldecode($_REQUEST['Do']);
						$tmpStr=substr($temp,1,strlen($temp)-1);
						$temp=explode("|",$tmpStr);
						
						//****************************************************************************
						$wh_id=$temp[0]-77000; // Warehouse ID
						$RptDate=$temp[1]; //Report Date
						$isNewRpt=$temp[2]; //if value=1 then new report
						$tt=explode("-",$RptDate); 
						$yy=$tt[0]; //Reprot year
						$mm=$tt[1];		//report Month
						
						// Check level
						$qryLvl = mysql_fetch_array(mysql_query("SELECT
																stakeholder.lvl,
																tbl_warehouse.hf_type_id,
																tbl_warehouse.prov_id
															FROM
																tbl_warehouse
															INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
															WHERE
																tbl_warehouse.wh_id = $wh_id"));
						$hfTypeId = $qryLvl['hf_type_id'];
						$whProvId = $qryLvl['prov_id'];
						$checkData = "SELECT
										tbl_hf_data.reporting_date
									FROM
										tbl_hf_data
									WHERE
										tbl_hf_data.warehouse_id = $wh_id
									ORDER BY
										tbl_hf_data.reporting_date ASC
									LIMIT 1";
						$checkDataRes = mysql_fetch_array(mysql_query($checkData));
						$openOB = ($checkDataRes['reporting_date'] == $RptDate) ? '' : $checkDataRes['reporting_date'];
						
						$month = date('M', mktime(0, 0, 0, $mm, 1));
						
						//****************************************************************************
						$objwarehouse->m_npkId=$wh_id;
						$stkid=$objwarehouse->GetStkIDByWHId($wh_id);
						print "<b>Store/Facility:</b> ".$objwarehouse->GetWarehouseNameById($wh_id);
						print "<b>&nbsp;&nbsp;&nbsp;&nbsp;Monthly Report:</b> ".$month.'-'.$yy;
						if ( $isNewRpt == 1 )
						{
							$PrevMonthDate=$objReports->GetPreviousMonthReportDate($RptDate);
						}
						else 
						{
							$PrevMonthDate=$RptDate;
						}
							
						include('data_entry_common.php');
						
					}
					
					else
					{
						//echo "<H1><- Please select warehouse for report</H1>";
					}
					?>
					</td>
                </tr>
                <tr>
                    <td colspan="8"><div id="eMsg" style="color:#060;"></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script src="<?php echo PLMIS_JS;?>dataentry/dataentry.js"></script>
<style>
.sb1NormalFontArial{ text-align:right !important;}
table#myTable tr td{padding:3px; text-align:left; border:1px solid #999;}
</style>
</body>
</html>