<?PHP
include ("../plmis_inc/common/CnnDb.php");
include ("../plmis_inc/classes/cCms.php");


	$db = new Database();
	$db->connect();
	$db1 = new Database();
	$db1->connect();
	
	/** Room object **/
	
	$pid = ($_REQUEST['pid']);//exit ;
	$result = "";
	$objCat = new cCms();
	
	
	$result .= " <select name = \"districts\" id = \"districts\" style = \"width:200px;\">";
	
	$qry  = "SELECT * FROM tbl_warehouse WHERE prov_id=".$pid." ORDER BY wh_name";
	$rsfd = mysql_query($qry) or die(mysql_error()); 
	
	while($row = mysql_fetch_array($rsfd)){
		if ($row['wh_id'] == $_SESSION['filterParam']['wh']){
			$temp = "selected=selected";	
		}else{
			$temp = "";	
		}
		$result .="<option value=\"".$row['wh_id']."\" $temp>".$row['wh_name']."(".$row['wh_type_id'].")</option>";
	}
	
	$result .="</select>";
	
	echo $result;

	
	$db->close();
?>