<?PHP

session_start();	
include ("../../plmis_inc/common/CnnDb.php");
include ("../../plmis_inc/classes/cCms.php");

	
	$db = new Database();
	$db->connect();
	$db1 = new Database();
	$db1->connect();
	
	/** Room object **/
	
	$pid = ($_REQUEST['pid']);
	$result = "";
	$objCat = new cCms();
	
	//$_SESSION['arrproducts']="";
	$result .= " <SELECT NAME = \"products[]\" id = \"products\" multiple = \"multiple\" size = \"5\"
                                style = \"width:200px;\">";
								
	if($pid !="0"){
		$sql = "select stkid from `stakeholder` where stkid='".$pid."'";
		$pid = $db1->executeScalar($sql);		
		
		$qry  =  "SELECT * FROM `stakeholder_item` JOIN itminfo_tab ON stakeholder_item.stk_item = itminfo_tab.itm_id WHERE stakeholder_item.stkid = '".$pid."' ORDER BY frmindex";
		$qryRes = mysql_query($qry);
		while ($row1 = mysql_fetch_array($qryRes)){
			if(strpos($_SESSION['arrproducts1'],$row1['itmrec_id'])==true || strpos($_SESSION['arrproducts1'],$row1['itmrec_id'])===0){
				$result .="<option value=\"".$row1['itmrec_id']."\" selected='selected' >".$row1['itm_name']."</option>";
			}else {
				$result .="<option value=\"".$row1['itmrec_id']."\" >".$row1['itm_name']."</option>";
			}
		}
	
	
		/*if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();
				$sql = "select itmrec_id from `itminfo_tab` where itm_id='".$row['stk_item']."'";
				$row['itmrec_id'] = $db1->executeScalar($sql);
				$sql = "select itm_name from `itminfo_tab` where itm_id='".$row['stk_item']."'";
				$row['itm_name'] = $db1->executeScalar($sql);
				
				if ($row['itmrec_id'] != ""){
					if(strpos($_SESSION['arrproducts1'],$row['itmrec_id'])==true || strpos($_SESSION['arrproducts1'],$row['itmrec_id'])===0)
					{						
						$result .="<option value=\"".$row['itmrec_id']."\" selected='selected'>".$row['itm_name']."</option>";
					}else{
						$result .="<option value=\"".$row['itmrec_id']."\" >".$row['itm_name']."</option>";
					}
				}
					
			}
		}*/
	}
	else{
		$qry  =  "select * from `itminfo_tab` where itm_status='Current' ORDER BY frmindex" ;
		if($db->query($qry) && $db->get_num_rows() > 0){
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();	
				if(strpos($_SESSION['arrproducts1'],$row['itmrec_id'])==true || strpos($_SESSION['arrproducts1'],$row['itmrec_id'])===0)
				{		
				
				$result .="<option value=\"".$row['itmrec_id']."\" selected='selected'>".$row['itm_name']."</option>";
				}
				else
				{
					
				$result .="<option value=\"".$row['itmrec_id']."\">".$row['itm_name']."</option>";
				}
					
			}
		}
	}
	$result .="</select>";

	
	/*echo "<script language=\"javascript\" type=\"text/javascript\"> alert('no12');</script>"; */
	
	echo $result;	
	$db->close();
?>