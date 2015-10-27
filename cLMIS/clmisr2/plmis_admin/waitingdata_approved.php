<?php
include("../plmis_inc/common/CnnDb.php");    //Include Database Connection File
include("../plmis_inc/common/FunctionLib.php");    //Include Global Function File                
include('../plmis_inc/common/DateTime.php');    //Include Date Function File


$BST=BST_DtTm();
$objDB= new Database();
$objDB->connect();
$objDB2= new Database();
$objDB2->connect();
$objDB3= new Database();
$objDB3->connect();
$objDB4= new Database();
$objDB4->connect();
$objDB5= new Database();
$objDB5->connect();
foreach($_POST['chkbox'] as $id)
{
	//echo "select * from tbl_waiting_data where w_id=".$id;
	//exit;
	$sql = "select * from tbl_waiting_data where w_id=".$id;
	if($objDB2->query($sql) and  $objDB2->get_num_rows()>0)
	{
		$row=$objDB2->fetch_one_assoc();
				
		$getFldId = mysql_fetch_array(mysql_query("SELECT tbl_warehouse.wh_id
												FROM tbl_warehouse
												Inner Join stakeholder ON  tbl_warehouse.stkofficeid = stakeholder.stkid
												WHERE stakeholder.lvl = 4 AND tbl_warehouse.dist_id
												IN(SELECT tbl_warehouse.dist_id
													FROM
													tbl_warehouse
													WHERE
													tbl_warehouse.wh_id = '".$row['wh_id']."') "));
		
		mysql_query("DELETE FROM tbl_wh_data 
					WHERE report_month='".$row['report_month']."'
					AND report_year='".$row['report_year']."'
					AND item_id='".$row['item_id']."'
					AND wh_id IN ($getFldId[wh_id], $row[wh_id])") or die(mysql_error());
					
		$sql = "INSERT INTO tbl_wh_data set
					report_month='".$row['report_month']."',
					report_year='".$row['report_year']."',
					item_id='".$row['item_id']."',
					wh_id ='".$row['wh_id']."',
					wh_obl_a='".$row['wh_obl_a']."',
					wh_obl_c='".$row['wh_obl_c']."',
					wh_received='".$row['wh_received']."',
					wh_issue_up='".$row['wh_issue_up']."',
					wh_cbl_a='".$row['wh_cbl_a']."',
					wh_cbl_c='".$row['wh_cbl_c']."',
					wh_adja='".$row['wh_adja']."',
					wh_adjb='".$row['wh_adjb']."',
					RptDate = '".$row['report_month']."-".$row['report_month']."-01' ";
		
		$rs1 = mysql_query($sql);
		
		$sql = "INSERT INTO tbl_wh_data set
					report_month='".$row['report_month']."',
					report_year='".$row['report_year']."',
					item_id='".$row['item_id']."',
					wh_id ='".$getFldId['wh_id']."',
					wh_obl_a='".$row['fld_obl_a']."',
					wh_obl_c='".$row['fld_obl_c']."',
					wh_received='".$row['fld_recieved']."',
					wh_issue_up='".$row['fld_issue_up']."',
					wh_cbl_a='".$row['fld_cbl_a']."',
					wh_cbl_c='".$row['fld_cbl_c']."',
					wh_adja='".$row['fld_adja']."',
					wh_adjb='".$row['fld_adjb']."',
					RptDate = '".$row['report_month']."-".$row['report_month']."-01' ";
		
		$rs2 = mysql_query($sql);
		
		
		/*$qry = "update tbl_wh_data set
		report_month='".$row['report_month']."',
		report_year='".$row['report_year']."',
		item_id='".$row['item_id']."',
		wh_obl_a='".$row['wh_obl_a']."',
		wh_obl_c='".$row['wh_obl_c']."',
		wh_received='".$row['wh_received']."',
		wh_issue_up='".$row['wh_issue_up']."',
		wh_cbl_a='".$row['wh_cbl_a']."',
		wh_cbl_c='".$row['wh_cbl_c']."',
		mos='".$row['mos']."',
		wh_adja='".$row['wh_adja']."',
		wh_adjb='".$row['wh_adjb']."',
		fld_obl_a='".$row['fld_obl_a']."',
		fld_obl_c='".$row['fld_obl_c']."',
		fld_recieved='".$row['fld_recieved']."',
		fld_issue_up='".$row['fld_issue_up']."',
		fld_cbl_c='".$row['fld_cbl_c']."',
		fld_cbl_a='".$row['fld_cbl_a']."',
		fld_mos='".$row['fld_mos']."',
		fld_adja='".$row['fld_adja']."',
		fld_adjb='".$row['fld_adjb']."',
		wh_entry='".$row['wh_entry']."',
		fld_entry='".$row['fld_entry']."'
		WHERE report_month=".$row['report_month'].
		" AND report_year=".$row['report_year'].
		" AND item_id='".$row['item_id']."'
		AND wh_id=".$row['wh_id'];				
		if($objDB->execute($qry))
		{
			//echo "updated successfully <br>";
			$qry1="delete from tbl_waiting_data where w_id=".$id;
			if($objDB3->execute($qry1))
			{
				//echo "deleted successfully"; exit;
			}
		}*/
		if ( $rs1 && $rs2 )
		{
			$qry1="delete from tbl_waiting_data where w_id=".$id;
			if($objDB3->execute($qry1))
			{
				//echo "deleted successfully"; exit;
			}
		}
	}
}
/* echo"<SCRIPT>document.location='AddEditContent.php';</Script>";*/
header('location:view_admin_waitingdata.php?flag=1')
	//exit;
?>