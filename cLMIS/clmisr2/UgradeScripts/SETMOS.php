<?php
include_once("../plmis_inc/common/CnnDb.php");          // Include Database Connection File
include_once("../plmis_inc/common/FunctionLib.php");    // Include Global Function File
$query="SELECT itmrec_id,itm_id FROM `itminfo_tab` WHERE `itm_status`='Current' ORDER BY frmindex";
$rs = mysql_query($query) or die(print mysql_error());
$rows = array();
while($r = mysql_fetch_assoc($rs))
{
	for($i=0;$i<=2;$i++)
	{
		//Over-stocked
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','OS','Over-stocked',3.1,100,'#0CF',".$i.",4,".$r['itm_id'].")";
		print mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','OS','Over-stocked',6.1,100,'#0CF',".$i.",3,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','OS','Over-stocked',12.1,100,'#0CF',".$i.",2,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','OS','Over-stocked',18.1,100,'#0CF',".$i.",1,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		//Satisfactory
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SAT','Satisfactory',1.01,3.0,'#090',".$i.",4,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SAT','Satisfactory',3.01,6.0,'#090',".$i.",3,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SAT','Satisfactory',6.01,12.0,'#090',".$i.",2,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SAT','Satisfactory',12.01,18.0,'#090',".$i.",1,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		//Under-Stocked
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','US','Under-Stocked',0.51,1.0,'#03C',".$i.",4,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','US','Under-Stocked',1.01,3.0,'#03C',".$i.",3,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','US','Under-Stocked',3.01,6.0,'#03C',".$i.",2,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','US','Under-Stocked',6.01,12.0,'#03C',".$i.",1,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		//Stock Out
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SO','Stock Out',0,0.5,'#F00',".$i.",4,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SO','Stock Out',0,1,'#F00',".$i.",3,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SO','Stock Out',0,3,'#F00',".$i.",2,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
		$strSQL="Insert into mosscale_tab(itmrec_id,shortterm,longterm,sclstart,sclsend,colorcode,stkid,lvl_id,item_id)
			Values ('".$r['itmrec_id']."','SO','Stock Out',0,6,'#F00',".$i.",1,".$r['itm_id'].")";
		print "<br>".mysql_query($strSQL) or die(print mysql_error())."</p>";
	}
}
?>