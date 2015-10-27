<?php
	include("../../plmis_inc/common/CnnDb.php");   //Include Database Connection File
	include("../../plmis_inc/common/FunctionLib.php"); 
	header("Content-type:text/xml");
	//echo $_GET['stk'];
	echo "<?xml version='1.0'?>";
	echo "\n<root>";
	
	//GET Stakeholders Products
	if($_GET['act']=='stk'){
	  $strSQL="SELECT DISTINCT(Stk_item),itminfo_tab.itm_name,itminfo_tab.itmrec_id FROM Stakeholder_item LEFT JOIN itminfo_tab ON itminfo_tab.itm_id=Stakeholder_item.Stk_item WHERE itm_status='Current' AND itminfo_tab.itm_name!='' AND Stkid IN (".$_GET['stk'].") ORDER BY itm_name ASC";
	  $rsTemp1=safe_query($strSQL);
	  while($rsRow1=mysql_fetch_array($rsTemp1))
	  {
		echo '<sel>';
		echo '<optvalue>'.$rsRow1['itmrec_id'].'#'.$rsRow1['itm_name'].'</optvalue>';	
		echo '<optlabel>'.$rsRow1['itm_name'].'</optlabel>';		
		echo '</sel>';
	  }
	}
	
	//GET All Products
	if($_GET['act']=='all'){
		$strSQL="SELECT itmrec_id,itm_name FROM itminfo_tab WHERE itm_status='Current' AND itm_name!='' ORDER BY itm_name ASC";
	    $rsTemp1=safe_query($strSQL);
	    while($rsRow1=mysql_fetch_array($rsTemp1))
	    {
	  	  echo '<sel>';
		  echo '<optvalue>'.$rsRow1['itmrec_id'].'#'.$rsRow1['itm_name'].'</optvalue>';	
		  echo '<optlabel>'.$rsRow1['itm_name'].'</optlabel>';		
		  echo '</sel>';
	    }
	}
	echo "</root>";
?>