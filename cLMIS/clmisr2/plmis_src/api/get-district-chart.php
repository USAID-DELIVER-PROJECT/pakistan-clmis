<?php

    include_once("../../plmis_inc/common/CnnDb.php");

    $year = $_REQUEST["year"];
    $month = $_REQUEST["month"];
    $stk = $_REQUEST["stk"];
    $sector = $_REQUEST["sector"];
    $product = $_REQUEST["product"];
    $district_id = $_REQUEST["district_id"];
    $level = $_REQUEST["level"];
    $consumtionType = $_REQUEST["type"];

    if($month>9){}else{$month = "0".$month;}
    $day = $year."-".$month;
    
    if($sector == "0" &&  $stk == "all"){
        $stkId = '';
        $stkType = "AND stakeholder.stk_type_id =".$sector;
        $stk = $sector;
        $start = 'SUM(';
        $end = ')';
    }
    else if($sector == "1" &&  $stk == "all"){
        $stkId = '';
        $stkType = "AND stakeholder.stk_type_id =".$sector;
        $stk = $sector;
        $start = 'SUM(';
        $end = ')';
    }
    else{
        $stkId = "AND summary_district.stakeholder_id =".$stk;
        $stkType ='';
        $start = '';
        $end = '';
    }
    
      $f_month = date('Y-m', strtotime("-1 month", strtotime($day)));
      $t_month = date('Y-m', strtotime("-2 month", strtotime($day)));
      
      if($level == "all"){
           $type = 'ROUND('.$start.'summary_district.soh_district_lvl'.$end.' / '.$start.'summary_district.avg_consumption'.$end.',2) AS VALUE';
      }
      else if($level == "3"){
          $type = 'ROUND('.$start.'summary_district.soh_district_store'.$end.' / '.$start.'summary_district.avg_consumption'.$end.',2) AS VALUE';
      }
      else{
          $type = 'ROUND(('.$start.'summary_district.soh_district_lvl'.$end.' - '.$start.'summary_district.soh_district_store'.$end.') / '.$start.'summary_district.avg_consumption'.$end.',2) AS VALUE';
      }
      
      if($consumtionType == "C"){
          $type = 'ROUND('.$start.'summary_district.consumption'.$end.') AS VALUE';
      }
      else if($consumtionType == "A"){
          $type = 'ROUND('.$start.'summary_district.avg_consumption'.$end.') AS VALUE';
      }
      else{}
   
      $query = "SELECT 
                     B.MONTH AS label,
                     COALESCE(A.value,null,0) AS value
                FROM
                (SELECT
                        A.MONTH,
                        SUM(A.VALUE) AS value
                FROM
                        (
                                SELECT
                                        map_district_mapping.district_id,
                                        DATE_FORMAT(summary_district.reporting_date,'%Y-%m') AS MONTH,
                                        $type
                                FROM
                                        summary_district
                                INNER JOIN map_district_mapping ON map_district_mapping.district_id = summary_district.district_id
                                INNER JOIN stakeholder ON stakeholder.stkid = summary_district.stakeholder_id
                                WHERE
                                        summary_district.item_id = '".$product."'
                                AND map_district_mapping.mapping_id = ".$district_id."
                                AND DATE_FORMAT(summary_district.reporting_date,'%Y-%m') BETWEEN '".$t_month."' AND '".$day."'
                                $stkType 
                        	$stkId
                                GROUP BY
                                        summary_district.reporting_date,
                                        map_district_mapping.district_id
                        ) A
                GROUP BY
                        A.MONTH)A 
                RIGHT JOIN (
                    SELECT '$t_month' AS MONTH UNION
                    SELECT '$f_month' AS MONTH UNION
                    SELECT '$day' AS MONTH ) B ON B.MONTH = A.MONTH";

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