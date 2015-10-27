<?php

    include_once("../../plmis_inc/common/CnnDb.php");

    $datefrom = $_REQUEST["datefrom"];
    $dateto = $_REQUEST["dateto"];
    $stk = $_REQUEST["stk"];
    $sector = $_REQUEST["sector"];
    $product = $_REQUEST["product"];
    $district_id = $_REQUEST["district_id"];
    
    $start = $datefrom;
    $end = $dateto;
  
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
    
      $year = date('Y');
      $f_month = date('Y', strtotime("-1 year", strtotime($year)));
      $t_month = date('Y', strtotime("-3 year", strtotime($year)));
      
      $type = 'ROUND('.$start.'summary_district.consumption * itminfo_tab.extra'.$end.') AS VALUE';
         
          if($product == "all"){
        $query = "SELECT
                        A.YEAR AS label,
                        SUM(A.VALUE) AS value
                FROM
                        (
                                SELECT
                                        map_district_mapping.district_id,
                                        DATE_FORMAT(summary_district.reporting_date,'%Y') AS YEAR,
                                        itminfo_tab.itm_id,
                                        $type
                                FROM
                                        summary_district
                                INNER JOIN map_district_mapping ON map_district_mapping.district_id = summary_district.district_id
                                INNER JOIN stakeholder ON stakeholder.stkid = summary_district.stakeholder_id
                                INNER JOIN itminfo_tab ON itminfo_tab.itmrec_id = summary_district.item_id
                                WHERE
                                  map_district_mapping.mapping_id = ".$district_id."
                                AND DATE_FORMAT(summary_district.reporting_date,'%Y-%m') BETWEEN '".$t_month."' AND '".$year."'
                                $stkType 
                        	$stkId
                                GROUP BY
                                        summary_district.reporting_date,
                                        map_district_mapping.district_id,
                                        itminfo_tab.itm_id
                        ) A
                GROUP BY
                        A.YEAR";
    }
    else{
           $query = "SELECT
                        A.YEAR AS label,
                        SUM(A.VALUE) AS value
                FROM
                        (
                                SELECT
                                        map_district_mapping.district_id,
                                        DATE_FORMAT(summary_district.reporting_date,'%Y') AS YEAR,
                                        $type
                                FROM
                                        summary_district
                                INNER JOIN map_district_mapping ON map_district_mapping.district_id = summary_district.district_id
                                INNER JOIN stakeholder ON stakeholder.stkid = summary_district.stakeholder_id
                                INNER JOIN itminfo_tab ON itminfo_tab.itmrec_id = summary_district.item_id
                                WHERE
                                        summary_district.item_id = '".$product."'
                                AND map_district_mapping.mapping_id = ".$district_id."
                                AND DATE_FORMAT(summary_district.reporting_date,'%Y-%m') BETWEEN '".$t_month."' AND '".$year."'
                                $stkType 
                        	$stkId
                                GROUP BY
                                        summary_district.reporting_date,
                                        map_district_mapping.district_id
                        ) A
                GROUP BY
                        A.YEAR";
   
    }

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