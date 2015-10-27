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
	
    $arrdistricts= "0,"; 
        
    $arr = ","; 
  
  
    if (isset($_SESSION['dists']) && !empty($_SESSION['dists']))
    {
        $arr = $_SESSION['dists'];    
  
    foreach ($arr as &$value) {
     $arrdistricts .= $value.",";
    } 
    $arrdistricts=substr($arrdistricts,0,-1);
	}
    
	$result .= " <SELECT NAME = \"districts[]\" id = \"districts\" multiple = \"multiple\" size = \"5\"
                                style = \"width:200px;\">";
								
	
	if ($pid ==0)
    {

 /*     $qry =  "SELECT whrec_id, concat(province.prov_title, ' |- ',tbl_districts.wh_name) as wh_name FROM province
            Inner Join tbl_districts ON province.prov_id = tbl_districts.province order by province.prov_title, wh_name";*/
			
		 
		$qry =  "SELECT
					tbl_locations.PkLocID AS whrec_id,
					concat(
						prov.LocName,
						' |- ',
						tbl_locations.LocName
					) AS wh_name
				FROM
					tbl_locations
				INNER JOIN tbl_locations AS prov ON tbl_locations.ParentID = prov.PkLocID
				WHERE
					tbl_locations.LocLvl = 3
				ORDER BY
					prov.LocName,
					tbl_locations.LocName ASC";
    } 
	else
    {
      $qry  =  "SELECT
					tbl_locations.PkLocID AS whrec_id,
					tbl_locations.LocName AS wh_name
				FROM
					tbl_locations
				WHERE
					tbl_locations.ParentID = $pid
				ORDER BY
					tbl_locations.LocName ASC";    
    }
	
		if($db->query($qry) && $db->get_num_rows() > 0)
		{
			for($j=0;$j<$db->get_num_rows();$j++)
			{
				$row = $db->fetch_row_assoc();
                
                if(strpos($arrdistricts,$row['whrec_id'])==true || strpos($arrdistricts,$row['whrec_id'])===0)
                {             
                $result .="<option value=\"".$row['whrec_id']."\" selected=\"selected\">".$row['wh_name']."</option>";
                }
                else
                {
                $result .="<option $sel value=\"".$row['whrec_id']."\">".$row['wh_name']."</option>";
    
                }
					
			}
		}
	
	$result .="</select>";
	
	echo $result;	
	$db->close();
?>
