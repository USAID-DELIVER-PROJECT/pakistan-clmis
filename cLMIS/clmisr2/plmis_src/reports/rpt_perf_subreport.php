<?php

$tempVar="nonstk=$notincludedStk&stktype=$StkType&prov=$prov_sel&Dt=$tdt";	

if(isset($_GET['Dt']))
{
	if(isset($_GET['prov']) && !empty($_GET['prov']))
	{
		if ($_GET['prov']!='all')
		{
			$prov_sel = $_GET['prov'];
			//$provWhere=" and tbl_warehouse.prov_id=".$prov_sel;			
		}			
		else
		{
			$prov_sel = '0';
			//$provWhere="";		
		}			
	}
	
	if(isset($_GET['nonstk']) && !empty($_GET['nonstk']))
	{
		$nonstk = $_GET['nonstk'];
	}
	
	if(isset($_GET['Dt']) && !empty($_GET['Dt']))
	{$Dt = $_GET['Dt'];}
	
	if(isset($_GET['stktype']) && !empty($_GET['stktype']))
	{$stktype = $_GET['stktype'];}
	
	
	include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
	
?>
	<html>
	<HEAD>
	<TITLE>Monthly Reported Data District List - Month of <?php echo date('M-Y',strtotime($Dt)); ?></TITLE>
	<link rel="stylesheet" href="../../plmis_css/nn_proj.css" type="text/css">
	</HEAD>
	
	<BODY text="#000000" bgColor="#FFFFFF" style="margin-left:10px;margin-top:0px,margin-right:0px;margin-bottom:0px;">
	<h1>Monthly Reported Data District List - Month of <?php echo date('M-Y',strtotime($Dt)); ?></h1>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>  
	<tr>
	   <td colspan="2" align="left"> 
	           <table cellpadding="0" cellspacing="1" border="1" width="650">
	             <tr height="28">
				 	<td width="100px" align="center" class="sb1GreenInfoBoxMiddleText"><strong>S.No.</strong></td>
	                <td width="100px" align="center" class="sb1GreenInfoBoxMiddleText"><strong>District</strong></td>
	                <td width="100px" align="center" style="padding-left:5px;" class="sb1GreenInfoBoxMiddleText"><strong>Reported Stakeholders</strong>
	            </tr>
	<?php
	$counter=1;
	if ($prov_sel==0)
	$queryvals = "SELECT tbl_locations.PkLocID, tbl_locations.LocName FROM tbl_locations 
					WHERE tbl_locations.LocLvl = 2 and ParentID is not null";
		else
	$queryvals = "SELECT tbl_locations.PkLocID, tbl_locations.LocName FROM tbl_locations 
					WHERE tbl_locations.LocLvl =3 and ParentID =".$prov_sel." Order by LocName";

	$rsvals = mysql_query($queryvals) or die(mysql_error());                                                                    
	while($rowvals = mysql_fetch_array($rsvals))
	{                       
	?>
        <tr height="22" >
		<td style="padding-left:5px;" class="sb1NormalFontArial">
           <?php     
             echo $counter++;                            
            ?>
          </td>
          <td style="padding-left:5px;" class="sb1NormalFontArial">
           <?php     
             echo $rowvals['LocName'];//."[".$rowvals['PkLocID']."]";                            
            ?>
          </td>
		  <?php
		$queryvals1 = "SELECT GROUP_CONCAT(B.stk) as C from (
						SELECT
						(
							SELECT stakeholder.stkname FROM stakeholder WHERE stakeholder.stkid=Office.MainStakeholder) as stk
							FROM  tbl_wh_data
							INNER JOIN tbl_warehouse ON tbl_warehouse.wh_id = tbl_wh_data.wh_id
							INNER JOIN stakeholder AS Office ON tbl_warehouse.stkofficeid = Office.stkid
							WHERE Office.lvl = 3 AND tbl_warehouse.dist_id = ".$rowvals['PkLocID']." 
								  AND tbl_wh_data.RptDate = '".$Dt."'
							GROUP BY Office.MainStakeholder 
						) 
						as B";
	
		$rsvals1 = mysql_query($queryvals1) or die(mysql_error());                                                                    
		while($rowvals1 = mysql_fetch_array($rsvals1))

		  {
		  ?>
		  <td style="padding-left:5px;" class="sb1NormalFontArial">
           <?php     
             echo $rowvals1['C'];                            
            ?>
          </td>
		  <?php
		  }
		  ?>

        </tr>   
<?php 
	} 
	
	
	?>                   
	            </table>
	  </td>
	</tr>               
	</tbody>
	</table>
	</body>
	</html>
<?php
}
else
{
	print "<h1>Nothing to display</h1>";
}    
?>

