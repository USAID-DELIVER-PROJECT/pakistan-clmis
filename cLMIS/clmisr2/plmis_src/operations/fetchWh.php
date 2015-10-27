<?PHP
/*include ("../../plmis_inc/common/CnnDb.php");
include ("../../plmis_inc/classes/cCms.php");


	$db = new Database();
	$db->connect();
	$db1 = new Database();
	$db1->connect();
	*/
	/** Room object **/
	include("../../html/adminhtml.inc.php");
	$pid = ($_REQUEST['pid']);//exit ;
	$stk== ($_REQUEST['stk']);
	$temp=($_REQUEST['wh']);
	$result = "";
/*	$objCat = new cCms();*/
	
	
	$result .= " <select name = \"districts\" id = \"districts\" style = \"width:200px;\">";
	
	$qry  = "SELECT
stakeholder.stkname,
tbl_warehouse.wh_id,
tbl_warehouse.wh_name,
tbl_dist_levels.lvl_name
FROM
tbl_warehouse
INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkid 
INNER JOIN stakeholder AS Office ON Office.stkid = tbl_warehouse.stkofficeid Inner join 
tbl_dist_levels ON  Office.lvl = tbl_dist_levels.lvl_id WHERE tbl_warehouse.stkid=".$stk." and tbl_warehouse.prov_id=".$pid." Order by stakeholder.stkname,wh_name";
	$rsfd = mysql_query($qry) or die(mysql_error()); 
	
	while($row = mysql_fetch_array($rsfd)){
		if ($row['wh_id'] == $_SESSION['filterParam']['wh']){
			$temp = "selected=selected";	
		}else{
			$temp = "";	
		}
		$result .="<option value=\"".$row['wh_id']."\" $temp>".$row['wh_name']."</option>"; //.$row['lvl_name'].' Warehouse -> '
	}
	
	$result .="</select>";
	
	echo $result;

	
//	$db->close();
?>