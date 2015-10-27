<?php

include_once("../../plmis_inc/common/CnnDb.php");

$year       = $_REQUEST["year"];
$month      = $_REQUEST["month"];
$stk        = $_REQUEST["stk"];
$sector     = $_REQUEST["sector"];
$province   = $_REQUEST["province"];
$product    = $_REQUEST["product"];

if($province == "all"){
    $provFilter = '';
    $provFilter2='';
}
else{
    $provFilter = 'AND tbl_warehouse.prov_id ='.$province;
    $provFilter2 = 'AND map_district_mapping.province_id='.$province;
}

 if($sector == "0" &&  $stk == "all"){
        $stkType = "AND stakeholder.stk_type_id = 0";
        $stkId = "";
        $stk = "0";
    }
    else if($sector == "1" &&  $stk == "all"){
        $stkType = "AND stakeholder.stk_type_id = 1" ;
        $stkId = "";
        $stk = "0";
    }
    else{
        $stkType ='';
        $stkId = "AND tbl_warehouse.stkid = ".$stk;
    }
        
$query = "SELECT
            D.mapping_id,
            D.district_id,
            D.district_name,
            COALESCE(C.RptWH,null,0) AS reported,
            COALESCE(C.TotalWH,null,0) AS total_warehouse,
            ROUND((COALESCE (C.RptWH, NULL, 0) / C.TotalWH) * 100) AS reporting_rate
            FROM
                (
		SELECT
			A.district_id,
			A.district_name,
			A.TotalWH,
			COALESCE (B.RptWH, NULL, 0) AS RptWH
		FROM
			(
				SELECT DISTINCT
					map_district_mapping.district_id,
					map_district_mapping.district_name,
					D.TotalWH
				FROM
					(
					    SELECT
							map_district_mapping.mapping_id,
							COUNT(tbl_warehouse.wh_id) AS TotalWH
						FROM
							tbl_locations
						INNER JOIN tbl_warehouse ON tbl_locations.PkLocID = tbl_warehouse.dist_id
						INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
						INNER JOIN map_district_mapping ON tbl_warehouse.dist_id = map_district_mapping.district_id
						INNER JOIN tbl_locations AS Mapping ON map_district_mapping.mapping_id = Mapping.PkLocID
						WHERE
						stakeholder.lvl IN (3,4)
                                                $stkType  
                                                $stkId
						AND map_district_mapping.stakeholder_id = ".$stk."
                                                AND stakeholder.is_reporting = 1
						$provFilter
						GROUP BY
							map_district_mapping.mapping_id
					) D
				INNER JOIN map_district_mapping ON map_district_mapping.mapping_id = D.mapping_id
				INNER JOIN tbl_locations ON map_district_mapping.district_id = tbl_locations.PkLocID
				WHERE
					map_district_mapping.stakeholder_id = ".$stk."
			) A
		LEFT JOIN (
			SELECT DISTINCT
				map_district_mapping.district_id,
				E.RptWH
			FROM
				(
                                 SELECT Q.mapping_id, SUM(Q.RptWH) AS RptWH FROM (
                                    SELECT
                                                map_district_mapping.mapping_id,
                                                COUNT(tbl_wh_data.w_id) AS RptWH
                                                FROM
                                                tbl_wh_data
                                                INNER JOIN tbl_warehouse ON tbl_wh_data.wh_id = tbl_warehouse.wh_id
                                                INNER JOIN stakeholder ON tbl_warehouse.stkofficeid = stakeholder.stkid
                                                INNER JOIN map_district_mapping ON map_district_mapping.district_id = tbl_warehouse.dist_id
                                                WHERE
                                                stakeholder.lvl IN (3,4)
                                                $stkType  
                                                $stkId
                                                AND tbl_wh_data.report_month = $month
                                                AND tbl_wh_data.report_year = $year
                                                AND tbl_wh_data.item_id = '".$product."' 
                                                $provFilter
                                                AND map_district_mapping.stakeholder_id = ".$stk."
                                                AND stakeholder.is_reporting = 1    
                                                GROUP BY
                                                tbl_warehouse.dist_id) Q GROUP BY Q.mapping_id
				) E
			INNER JOIN map_district_mapping ON map_district_mapping.mapping_id = E.mapping_id
			INNER JOIN tbl_locations ON map_district_mapping.district_id = tbl_locations.PkLocID
			WHERE
				map_district_mapping.stakeholder_id = ".$stk."
		) B ON A.district_id = B.district_id
                ) C
                RIGHT JOIN (
                        SELECT DISTINCT
                                map_district_mapping.district_id,
                                map_district_mapping.mapping_id,
                                map_district_mapping.district_name
                        FROM
                                map_district_mapping
                        WHERE
                                map_district_mapping.stakeholder_id = ".$stk."
                               $provFilter2
                ) D ON C.district_id = D.district_id";

      $result = mysql_query($query);
         if($result){
             $row = mysql_fetch_all($result);
       }
       else{
           echo "Failed";
       }

        echo json_encode($row);
       

       function mysql_fetch_all($result)
        {
            $all = array();
            while ($row = mysql_fetch_assoc($result)){
                $all[] = $row;
            }
            return $all;
        }

?>