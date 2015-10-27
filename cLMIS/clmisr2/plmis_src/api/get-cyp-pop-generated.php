<?php

include_once("../../plmis_inc/common/CnnDb.php");

$year       = $_REQUEST["year"];
$datefrom   = $_REQUEST["datefrom"];
$dateto     = $_REQUEST["dateto"];
$sector     = $_REQUEST["sector"];
$stk        = $_REQUEST["stk"];
$province   = $_REQUEST["province"];
$product    = $_REQUEST["product"];


if($year == ""){
    $start = $datefrom;
    $end = $dateto;
}
else{
    $start = $year."-01-01";
    $end   = $year."-12-31";
}

if($province == "all"){
    $provFilter = '';
}
else{
    $provFilter = 'AND map_district_mapping.province_id ='.$province;
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
    $stkId = "AND summary_district.stakeholder_id = ".$stk;
}

 if($product == "all"){
        $product = "%";
   }

    $query = "SELECT
                    map_district_mapping.mapping_id,
                    map_district_mapping.district_id,
                    tbl_locations.LocName AS district_name,
                    COALESCE(A.CYP, NULL, 0) AS cyp,
                    map_population.population as pop,
                    Round((COALESCE(A.CYP, NULL, 0) / map_population.population * 100),3) AS cyp_pop
                    FROM
                    (
                         SELECT
                            map_district_mapping.mapping_id,
                            ROUND(SUM(summary_district.consumption * itminfo_tab.extra)) AS CYP
                            FROM
                             tbl_locations
                            INNER JOIN summary_district ON tbl_locations.PkLocID = summary_district.district_id
                            INNER JOIN map_district_mapping ON map_district_mapping.district_id = summary_district.district_id
                            INNER JOIN tbl_locations AS Mapping ON map_district_mapping.district_id = Mapping.PkLocID
                            INNER JOIN itminfo_tab ON itminfo_tab.itmrec_id = summary_district.item_id
                            INNER JOIN stakeholder ON stakeholder.stkid = summary_district.stakeholder_id
                            WHERE
                             summary_district.item_id like '".$product."'
                            $stkType  
                            $stkId
                            AND map_district_mapping.stakeholder_id = ".$stk."
                            AND summary_district.reporting_date BETWEEN '".$start."'
                            AND '".$end."'
                            GROUP BY
                             map_district_mapping.mapping_id
                            ) AS A
                      INNER JOIN map_district_mapping ON map_district_mapping.mapping_id = A.mapping_id
                      INNER JOIN tbl_locations ON map_district_mapping.district_id = tbl_locations.PkLocID
                      INNER JOIN map_population ON A.mapping_id = map_population.district_id
                      WHERE
                      map_district_mapping.stakeholder_id = ".$stk." $provFilter";
   
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