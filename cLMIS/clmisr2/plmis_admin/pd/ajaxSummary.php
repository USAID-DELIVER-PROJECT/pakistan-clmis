<?php 
require_once("db.php");
//	  $province_id= province_id;
//    print_r($_POST);
	  
	$pov = '';
	if (isset($_POST['province_id']))
	{
		$pov =$_POST['province_id'];
	}
	
	$Province = "SELECT tbl_locations.LocName FROM tbl_locations WHERE tbl_locations.LocLvl= 2 AND province_id = $pov";
	$rsSql = mysql_query($Province) or die (mysql_error);	
	$result = mysql_fetch_assoc($rsSql); 
	//    print_r($result);  
	
	$Total_District = "SELECT Count(*) FROM tbl_locations WHERE tbl_locations.LocLvl=4 AND province_id = $pov";
	$rsSql1 = mysql_query($Total_District) or die (mysql_error);	
	$result1 = mysql_fetch_assoc($rsSql1); 
	//    print_r($result1);
	
	$Total_UC = "SELECT Count(*) FROM tbl_locations WHERE tbl_locations.LocLvl=6 AND province_id = $pov";
	$rsSql2 = mysql_query($Total_UC) or die (mysql_error);	
	$result2 = mysql_fetch_assoc($rsSql2); 
	//    print_r($result2);
	
	$Cluster_Leads = "SELECT Count(sysusr_name) FROM sysuser_tab WHERE sysuser_tab.province=$pov";
	$rsSql3 = mysql_query($Cluster_Leads) or die (mysql_error);	
	$result3 = mysql_fetch_assoc($rsSql3);	
	  
	 

?>
<table align="right"> 
    <tr>
        <td>Province:<?php echo $result['LocName'] ?> </td>
        <td>Total District:<?php echo $result1['Count(*)'] ?></td>
        <td>Total UC:<?php echo $result2['Count(*)'] ?></td>
        <td>Cluster Leads:<?php echo $result3['Count(sysusr_name)'] ?></td>
    </tr>						    
</table> 