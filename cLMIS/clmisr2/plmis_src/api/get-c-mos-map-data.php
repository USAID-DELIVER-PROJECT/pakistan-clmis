<?php

    include_once("../../plmis_inc/common/CnnDb.php");

    $year = $_REQUEST["year"];
    $month = $_REQUEST["month"];
    $stk = $_REQUEST["stk"];
    $sector = $_REQUEST["sector"];
    $province = $_REQUEST["province"];
    $product = $_REQUEST["product"];
    $level = $_REQUEST["level"];

    if($month>9){}else{$month = "0".$month;}
    $day = $year."-".$month;

    if($province == "all"){
        $province = '0';
        $provFilter = '';    
    }
    else{
        $provFilter = 'AND map_district_mapping.province_id ='.$province;
    }
      
    if($sector == "0" &&  $stk == "all"){
        $stkType = "AND Stk.stk_type_id = 0";
        $stkId = "";
        $stk = "0";
        $start = 'SUM(';
        $end = ')';
    }
    else if($sector == "1" &&  $stk == "all"){
        $stkType = "AND Stk.stk_type_id = 1" ;
        $stkId = "";
        $stk = "0";
        $start = 'SUM(';
        $end = ')';
    }
    else{
        $stkType ='';
        $stkId = "AND summary_district.stakeholder_id= ".$stk;
        $start = '';
        $end = '';
    }
      

      if($level == "all"){
           $type = 'ROUND('.$start.'summary_district.soh_district_lvl'.$end.' / '.$start.'summary_district.avg_consumption'.$end.',2) AS mos';
      }
      else if($level == "3"){
          $type = 'ROUND('.$start.'summary_district.soh_district_store'.$end.' / '.$start.'summary_district.avg_consumption'.$end.',2) AS mos';
      }
      else{
          $type = 'ROUND(('.$start.'summary_district.soh_district_lvl'.$end.' - '.$start.'summary_district.soh_district_store'.$end.') / '.$start.'summary_district.avg_consumption'.$end.',2) AS mos';
      }
    
      $query = "SELECT
                        D.mapping_id,
                        D.district_id,
                        D.district_name,
                        COALESCE (C.mos, NULL, 0) AS mos
                FROM
                (
                    SELECT
                            map_district_mapping.district_id,
                            map_district_mapping.district_name,
                            B.mos
                    FROM
                            (
                                SELECT
                                        A.district_id,
                                        A.district_name,
                                        SUM(A.mos) AS mos
                                FROM
                                        (
                                            SELECT
                                                    map_district_mapping.mapping_id AS district_id,
                                                    map_district_mapping.district_name,
                                                    $type
                                            FROM
                                                    tbl_warehouse
                                            INNER JOIN tbl_locations ON tbl_locations.PkLocID = tbl_warehouse.dist_id
                                            INNER JOIN stakeholder ON stakeholder.stkid = tbl_warehouse.stkofficeid
                                            INNER JOIN map_district_mapping ON tbl_warehouse.dist_id = map_district_mapping.district_id
                                            INNER JOIN summary_district ON map_district_mapping.district_id = summary_district.district_id
                                            INNER JOIN stakeholder AS Stk ON Stk.stkid = summary_district.stakeholder_id
                                            WHERE
                                                    stakeholder.lvl = 4
                                            $stkType  
                                            $stkId
                                            AND summary_district.item_id = '".$product."'
                                            AND DATE_FORMAT(summary_district.reporting_date,'%Y-%m') = '".$day."'
                                            $provFilter
                                            AND map_district_mapping.stakeholder_id = ".$stk."
                                            GROUP BY
                                                    map_district_mapping.district_id
					) A
				GROUP BY
					A.district_id
			) B
                        INNER JOIN map_district_mapping ON map_district_mapping.mapping_id = B.district_id
                        INNER JOIN tbl_locations ON map_district_mapping.district_id = tbl_locations.PkLocID
                        WHERE
                                map_district_mapping.stakeholder_id = ".$stk."
                        GROUP BY
                                map_district_mapping.district_id
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
                                        $provFilter
                        ) D ON C.district_id = D.district_id
                        GROUP BY D.district_id";

       $result = mysql_query($query);
         if($result){
             $row = mysql_fetch_all($result);
       }
       else{
           echo "Failed";
           return;
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