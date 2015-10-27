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
	
	$arrprovinces= "0,";	
    $arr = ",";
    
    if (isset($_SESSION['provinces']) && !empty($_SESSION['provinces']))
    {
        $arr = $_SESSION['provinces'];    
        foreach ($arr as &$value) {
        $arrprovinces .= $value.",";
        } 
        $arrprovinces=substr($arrprovinces,0,-1);
    }
    
    
	if($pid !=4)
	{
		$result .= " <SELECT NAME = \"provinces[]\" id = \"provinces\" multiple = \"multiple\" size = \"5\"
     
	                            style = \"width:200px;\">";
	}
	else
	{
		$result .= " <SELECT NAME = \"provinces[]\" id = \"provinces\" 
     
	                            style = \"width:150px;\" onchange=\"fetchDistricts();\">";
	}
								
	if($pid == 1)
	{	
	$qry  =  "SELECT
tbl_locations.LocName as prov_title,
tbl_locations.PkLocID as prov_id
FROM
tbl_locations
WHERE
tbl_locations.LocLvl = 2 AND
tbl_locations.LocType = 2" ; 
	}
	if($pid == 2)
	{	
	$qry  =  "SELECT
tbl_locations.LocName as prov_title,
tbl_locations.PkLocID as prov_id
FROM
tbl_locations
WHERE
tbl_locations.LocLvl = 2 AND
tbl_locations.LocType = 3" ; 
	}
	if($pid == 3 || $pid==4)
	{	
	$qry  =  "SELECT
tbl_locations.LocName as prov_title,
tbl_locations.PkLocID as prov_id
FROM
tbl_locations
WHERE
tbl_locations.LocLvl = 2 and Parentid is not null" ; 
	}
		if($db->query($qry) && $db->get_num_rows() > 0)
		{
            $sel = "";
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();	

				if(strpos($arrprovinces,$row['prov_id'])==true || strpos($arrprovinces,$row['prov_id'])===0)
				{ 			
				$result .="<option value=\"".$row['prov_id']."\" selected=\"selected\">".$row['prov_title']."</option>";
				}
				else
				{
				$result .="<option $sel value=\"".$row['prov_id']."\">".$row['prov_title']."</option>";
	
				}
			}
		}
	$result .="</select>";

	
	
	
	echo $result;	
	$db->close();
?>