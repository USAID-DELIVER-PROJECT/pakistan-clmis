<?PHP

	
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
	
	$arrprovinces="0,";
	
	
	/*$sqlp = "select arrprovinces from tbl_favgraphsettings where user='Administrator'";
	if($db1->query($sqlp) && $db1->get_num_rows() > 0)
		{
			for($j=0;$j<$db1->get_num_rows();$j++)
			{
				$row = $db1->fetch_row_assoc();	
				$arrprovinces.=$row['arrprovinces'].",";
			}
		}
		echo $arrprovinces=substr($arrprovinces,0,-1);*/
	
	
		$result .= " <SELECT NAME = \"provinces[]\" id = \"provinces\" 
     
	                            style = \"width:150px;\" onchange=\"fetchDistrictsStake();\">";
	
								
	if($pid == 1)
	{	
	$qry  =  "select * from province where regionStatus=0" ; 
	}
	if($pid == 2)
	{	
	$qry  =  "select * from province where regionStatus=1" ; 
	}
	if($pid == 3 || $pid==4)
	{	
	$qry  =  "select * from province where 1=1" ; 
	}
		if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();	
				/*if(strpos($arrprovinces,$data_array[$i]['prov_id'])==true || strpos($arrprovinces,$data_array[$i]['prov_id'])===0)
				{ 			
				$result .="<option value=\"".$row['prov_id']."\" selected=\"selected\">".$row['prov_title']."</option>";
				}
				else
				{*/
				$result .="<option value=\"".$row['prov_id']."\">".$row['prov_title']."</option>";
	
				//}
			}
		}
	

	$result .="</select>";

	
	
	
	echo $result;	
	$db->close();
?>