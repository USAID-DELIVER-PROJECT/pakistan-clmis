<?php
if(isset($_GET['Dt']))
{
	if(isset($_GET['prov']) && !empty($_GET['prov']))
	{
		if ($_GET['prov']!='all')
		{
			$prov = $_GET['prov'];
			$provWhere=" and tbl_warehouse.prov_id=".$prov_sel;			
		}			
		else
		{
			$prov = '0';
			$provWhere="";		
		}			
	}
	
	if(isset($_GET['stk']) && !empty($_GET['stk']))
	{
	if ($_GET['stk']!='all')
		{
			$stk = $_GET['stk'];
			$stkWhere=" and tbl_warehouse.stkid=".$stk;
		}			
		else
		{
			$stk = '0';
			$stkWhere="";		
		}			
	}
	
	if(isset($_GET['MOS']) && !empty($_GET['MOS']))
	{$MOS = $_GET['MOS'];}
	
	if(isset($_GET['Dt']) && !empty($_GET['Dt']))
	{$Dt = $_GET['Dt'];}
	
	if(isset($_GET['Prod']) && !empty($_GET['Prod']))
	{$Prod = $_GET['Prod'];}
	
	
	include("../../html/adminhtml.inc.php");
	include("../../plmis_inc/common/plmis_common_constants.php");	//Include Global Function File	
	
?>
	<html>
	<HEAD>
	<TITLE>Sotckout District List - Month of <?php echo date('M-Y',strtotime($Dt)); ?></TITLE>
	<link rel="stylesheet" href="../../plmis_css/nn_proj.css" type="text/css">
	</HEAD>
	
	<BODY text="#000000" bgColor="#FFFFFF" style="margin-left:10px;margin-top:0px,margin-right:0px;margin-bottom:0px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>  
	<tr>
	   <td colspan="2" align="left"> 
	           <table cellpadding="0" cellspacing="1" border="1" width="650">
	             <tr height="28">
				 	<td width="100px" align="center" class="sb1GreenInfoBoxMiddleText"><strong>S.No.</strong></td>
	                <td width="100px" align="center" class="sb1GreenInfoBoxMiddleText"><strong>District</strong></td>
	                <td width="100px" align="center" style="padding-left:5px;" class="sb1GreenInfoBoxMiddleText"><strong>MOS</strong>
	            </tr>
	<?php
	$counter=1;
	$queryvals =  "SELECT tbl_warehouse.wh_name, REPgetMOSDt('$Dt',wh_id,'".$Prod."') as MOS FROM tbl_warehouse INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
			    WHERE stakeholder.lvl = 3 AND REPgetMOSDt('$Dt',wh_id,'".$Prod."') < $MOS ".$stkWhere.$provWhere;
	$rsvals = mysql_query($queryvals) or die(mysql_error());                                                                    
	while($rowvals = mysql_fetch_array($rsvals))
	{                        ?>
	            <tr height="22" >
				<td bgcolor="<?php echo ($rowvals['MOS']>0?'#FFFFFF':'#ccc');?>" style="padding-left:5px;" class="sb1NormalFontArial">
	               <?php     
	                 echo $counter++;                            
	                ?>
	              </td>
	              <td bgcolor="<?php echo ($rowvals['MOS']>0?'#FFFFFF':'#ccc');?>" style="padding-left:5px;" class="sb1NormalFontArial">
	               <?php     
	                 echo $rowvals['wh_name'];                            
	                ?>
	              </td>
				  <td bgcolor="<?php echo ($rowvals['MOS']>0?'#FFFFFF':'#ccc');?>" style="padding-left:5px;" class="sb1NormalFontArial">
	               <?php     
	                 echo $rowvals['MOS'];                            
	                ?>
	              </td>
	            </tr>   
<?php } ?>                   
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

