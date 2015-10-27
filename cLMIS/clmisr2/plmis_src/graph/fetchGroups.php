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
	
	
	$result .= " <SELECT NAME = \"groupcomp[]\" id = \"groups\" multiple = \"multiple\" size = \"5\"
                                style = \"width:150px;\">";
								
	if($pid !="0")
	{
	
	$qry  =  "SELECT
facilitygroupname.fac_group_name
FROM
facilitygroupname
where fac_stake_holder='".$pid."' ORDER BY
facilityfroupname.fac_group_name ASC" ; 
	
	
	
	
		if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();				
				$result .="<option value=\"".$row['fac_group_name']."\">".$row['fac_group_name']."</option>";
					
			}
		}
	}
	else
	{
	$qry  =  "SELECT
facilitygroupname.fac_group_name
FROM
facilitygroupname
ORDER BY
facilitygroupname.fac_group_name ASC";
	if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();				
				$result .="<option value=\"".$row['fac_group_name']."\">".$row['fac_group_name']."</option>";
					
			}
		}
	}
	$result .="</select>";

	
	
	
	echo $result;	
	$db->close();
?>