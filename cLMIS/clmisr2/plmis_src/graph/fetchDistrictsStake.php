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
	
	
	$result .= " <SELECT NAME = \"districts[]\" id = \"districts\" 
                                style = \"width:150px;\">";
								
	
	
	$qry  =  "SELECT * from tbl_districts where province=".$pid;
	
	
		if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();				
				$result .="<option value=\"".$row['whrec_id']."\">".$row['wh_name']."</option>";
					
			}
		}
	
	$result .="</select>";

	
	
	
	echo $result;	
	$db->close();
?>
